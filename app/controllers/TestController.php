<?php

require_once '../core/Controller.php';
require_once '../app/models/Test.php';
require_once '../app/models/Question.php';
require_once '../app/models/Payment.php';

class TestController extends Controller {
    
    private $test;
    private $question;
    private $payment;

    public function __construct() {
        parent::__construct();
        $this->requireLogin();
        $this->test = new Test();
        $this->question = new Question();
        $this->payment = new Payment();
    }

    // Show test start page
    public function index() {
        // Check if user has active test
        $active = $this->test->getActiveSession($_SESSION['user_id']);
        if ($active) {
            $this->redirect('/test/take');
        }
        
        $this->view('test/start');
    }

    // Start test
    public function start() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/test');
        }

        $user_id = $_SESSION['user_id'];
        
        // Check access
        if (!$this->payment->canTakeTest($user_id)) {
            $_SESSION['error'] = 'Please purchase a plan first';
            $this->redirect('/payment');
        }

        $selected = [];
        $lang = $_SESSION['lang'] ?? 'rw';
        $generation_lang = $lang; // generate test questions from user's selected language

        // Get 20 random questions from all available questions
        $stmt = $this->db->prepare("
            SELECT q.id FROM questions q
            JOIN question_translations qt ON q.id = qt.question_id
            WHERE qt.language = ?
            ORDER BY RAND()
            LIMIT 20
        ");
        $stmt->execute([$generation_lang]);
        $questions = $stmt->fetchAll();

        foreach ($questions as $q) {
            $selected[] = $q['id'];
        }

        $selected = array_values(array_unique($selected));
        if (empty($selected)) {
            $_SESSION['error'] = 'No questions are available for this test. Please contact the administrator or try again later.';
            $this->redirect('/test');
        }

        shuffle($selected);

        // Create test session
        $test_id = $this->test->createSession($user_id, $selected);
        
        if ($test_id) {
            $_SESSION['test_id'] = $test_id;
            $this->log('TEST_STARTED');
            $this->redirect('/test/take');
        } else {
            $_SESSION['error'] = 'Failed to start test';
            $this->redirect('/test');
        }
    }

    // Take test
    public function take() {
        $user_id = $_SESSION['user_id'];
        $test_id = $_SESSION['test_id'] ?? null;

        if (!$test_id) {
            // Check if there's an active session
            $active = $this->test->getActiveSession($user_id);
            if ($active) {
                $test_id = $active['id'];
                $_SESSION['test_id'] = $test_id;
            } else {
                $this->redirect('/test');
            }
        }

        $session = $this->test->getSession($test_id);

        if (!$session || $session['user_id'] != $user_id) {
            $this->redirect('/test');
        }

        if ($session['completed']) {
            $this->redirect('/test/result/' . $test_id);
        }

        $lang = $_SESSION['lang'] ?? 'rw';
        $question_ids = json_decode($session['questions'], true);

        if (empty($question_ids) || !is_array($question_ids)) {
            $this->test->deactivateSession($test_id);
            unset($_SESSION['test_id']);
            $_SESSION['error'] = 'Your active test session has no questions. Please start a new test.';
            $this->redirect('/test');
        }

        // Get questions with answers in the user's language, fallback to Kinyarwanda if needed
        $result = $this->question->getQuestionsWithAnswers($question_ids, $lang);
        if (empty($result)) {
            $result = $this->question->getQuestionsWithAnswers($question_ids, 'rw');
        }

        // Organize questions
        $questions = [];
        foreach ($result as $row) {
            if (!isset($questions[$row['id']])) {
                $questions[$row['id']] = [
                    'id' => $row['id'],
                    'text' => $row['question_text'],
                    'image' => $row['image'],
                    'answers' => []
                ];
            }
            
            if ($row['answer_id']) {
                $questions[$row['id']]['answers'][] = [
                    'id' => $row['answer_id'],
                    'text' => $row['answer_text']
                ];
            }
        }

        $questions = array_values($questions);

        $remaining_time = $this->test->getRemainingTime($test_id);

        $this->view('test/take', [
            'test_id' => $test_id,
            'questions' => $questions,
            'remaining_time' => $remaining_time,
            'total_questions' => count($questions)
        ]);
    }

    // Submit test
    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/test');
        }

        $user_id = $_SESSION['user_id'];
        $test_id = $_SESSION['test_id'] ?? null;

        if (!$test_id) {
            $this->redirect('/test');
        }

        $session = $this->test->getSession($test_id);

        if (!$session || $session['user_id'] != $user_id || $session['completed']) {
            $this->redirect('/test');
        }

        $question_ids = json_decode($session['questions'], true);
        if (empty($question_ids) || !is_array($question_ids)) {
            $this->redirect('/test');
        }

        $answers = $_POST['answers'] ?? [];

        foreach ($question_ids as $q_id) {
            $selected = $answers[$q_id] ?? null;
            $this->test->saveAnswer($test_id, $q_id, $selected);
        }

        // Complete test and get score
        $score = $this->test->completeTest($test_id);

        // Consume test credit
        $this->payment->consumeTest($user_id);
        unset($_SESSION['test_id']);

        $this->log('TEST_SUBMITTED');

        header('Location: ' . SITE_URL . '/test/result/' . $test_id);
        exit;
    }

    // Show result
    public function result($test_id) {
        $user_id = $_SESSION['user_id'];

        $session = $this->test->getSession($test_id);

        if (!$session || $session['user_id'] != $user_id) {
            $this->redirect('/dashboard');
        }

        $lang = $_SESSION['lang'] ?? 'rw';
        $question_ids = json_decode($session['questions'] ?? '[]', true);
        $questionOrderSql = '';
        if (!empty($question_ids) && is_array($question_ids)) {
            $safeIds = array_map('intval', $question_ids);
            $questionOrderSql = 'ORDER BY FIELD(ta.question_id, ' . implode(',', $safeIds) . ')';
        }

        $stmt = $this->db->prepare("
            SELECT ta.*, q.id as q_id,
                   COALESCE(qt_user.question_text, qt_en.question_text, qt_rw.question_text) as question_text,
                   user_at.answer_text as user_answer,
                   correct_a.id as correct_answer_id,
                   COALESCE(correct_at_user.answer_text, correct_at_en.answer_text, correct_at_rw.answer_text) as correct_answer
            FROM test_answers ta
            JOIN questions q ON ta.question_id = q.id
            LEFT JOIN question_translations qt_user ON q.id = qt_user.question_id AND qt_user.language = ?
            LEFT JOIN question_translations qt_en ON q.id = qt_en.question_id AND qt_en.language = 'en'
            LEFT JOIN question_translations qt_rw ON q.id = qt_rw.question_id AND qt_rw.language = 'rw'
            LEFT JOIN answer_translations user_at ON ta.selected_answer_id = user_at.answer_id AND user_at.language = ?
            LEFT JOIN answers correct_a ON correct_a.question_id = q.id AND correct_a.is_correct = TRUE
            LEFT JOIN answer_translations correct_at_user ON correct_a.id = correct_at_user.answer_id AND correct_at_user.language = ?
            LEFT JOIN answer_translations correct_at_en ON correct_a.id = correct_at_en.answer_id AND correct_at_en.language = 'en'
            LEFT JOIN answer_translations correct_at_rw ON correct_a.id = correct_at_rw.answer_id AND correct_at_rw.language = 'rw'
            WHERE ta.test_id = ?
            $questionOrderSql
        ");
        $stmt->execute([$lang, $lang, $lang, $test_id]);
        $answers = $stmt->fetchAll();

        $answeredQuestionIds = array_map('intval', array_column($answers, 'q_id'));
        $missingQuestionIds = [];
        if (!empty($question_ids) && is_array($question_ids)) {
            $missingQuestionIds = array_values(array_diff(array_map('intval', $question_ids), $answeredQuestionIds));
        }

        if (!empty($missingQuestionIds)) {
            $placeholders = implode(',', array_fill(0, count($missingQuestionIds), '?'));
            $missingStmt = $this->db->prepare("
                SELECT NULL as id,
                       ? as test_id,
                       q.id as question_id,
                       NULL as selected_answer_id,
                       0 as is_correct,
                       q.id as q_id,
                       COALESCE(qt_user.question_text, qt_en.question_text, qt_rw.question_text) as question_text,
                       NULL as user_answer,
                       correct_a.id as correct_answer_id,
                       COALESCE(correct_at_user.answer_text, correct_at_en.answer_text, correct_at_rw.answer_text) as correct_answer
                FROM questions q
                LEFT JOIN question_translations qt_user ON q.id = qt_user.question_id AND qt_user.language = ?
                LEFT JOIN question_translations qt_en ON q.id = qt_en.question_id AND qt_en.language = 'en'
                LEFT JOIN question_translations qt_rw ON q.id = qt_rw.question_id AND qt_rw.language = 'rw'
                LEFT JOIN answers correct_a ON correct_a.question_id = q.id AND correct_a.is_correct = TRUE
                LEFT JOIN answer_translations correct_at_user ON correct_a.id = correct_at_user.answer_id AND correct_at_user.language = ?
                LEFT JOIN answer_translations correct_at_en ON correct_a.id = correct_at_en.answer_id AND correct_at_en.language = 'en'
                LEFT JOIN answer_translations correct_at_rw ON correct_a.id = correct_at_rw.answer_id AND correct_at_rw.language = 'rw'
                WHERE q.id IN ($placeholders)
            ");
            $missingStmt->execute(array_merge([$test_id, $lang, $lang], $missingQuestionIds));
            $answers = array_merge($answers, $missingStmt->fetchAll());

            $orderMap = array_flip(array_map('intval', $question_ids));
            usort($answers, function ($a, $b) use ($orderMap) {
                return ($orderMap[(int) $a['q_id']] ?? 0) <=> ($orderMap[(int) $b['q_id']] ?? 0);
            });
        }

        $passed = $session['score'] >= PASS_SCORE;

        $this->view('test/result', [
            'session' => $session,
            'answers' => $answers,
            'passed' => $passed,
            'pass_score' => PASS_SCORE
        ]);
    }

    // API: Get remaining time
    public function getTime() {
        $test_id = $_POST['test_id'] ?? null;

        if (!$test_id) {
            $this->json(['error' => 'Test ID required'], 400);
        }

        $remaining = $this->test->getRemainingTime($test_id);

        $this->json(['time' => $remaining]);
    }
}
