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

        // Get categories
        $stmt = $this->db->query("SELECT id FROM categories");
        $categories = $stmt->fetchAll();

        $selected = [];
        $lang = $_SESSION['lang'] ?? 'rw';
        $generation_lang = $lang; // generate test questions from user's selected language

        // Get at least 2 questions per category
        foreach ($categories as $cat) {
            $stmt = $this->db->prepare("
                SELECT q.id FROM questions q
                JOIN question_translations qt ON q.id = qt.question_id
                WHERE q.category_id = ? AND qt.language = ?
                ORDER BY RAND() LIMIT 2
            ");
            $stmt->execute([$cat['id'], $generation_lang]);
            $questions = $stmt->fetchAll();

            foreach ($questions as $q) {
                $selected[] = $q['id'];
            }
        }

        // Fill to 20 questions
        $remaining = TOTAL_QUESTIONS - count($selected);
        if ($remaining > 0) {
            $ids = implode(',', $selected ?: [0]);
            $remaining = max(0, intval($remaining));
            $sql = "
                SELECT q.id FROM questions q
                JOIN question_translations qt ON q.id = qt.question_id
                WHERE q.id NOT IN ($ids) AND qt.language = ?
                ORDER BY RAND()
                LIMIT $remaining
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$generation_lang]);
            foreach ($stmt->fetchAll() as $q) {
                $selected[] = $q['id'];
            }
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

        // Check if time is up
        if ($this->test->isTimeUp($test_id)) {
            // Auto submit with current answers
        }

        // Save answers
        $answers = $_POST['answers'] ?? [];

        foreach ($answers as $q_id => $a_id) {
            $this->test->saveAnswer($test_id, $q_id, $a_id);
        }

        // Complete test and get score
        $score = $this->test->completeTest($test_id);

        // Consume test credit
        $this->payment->consumeTest($user_id);

        $this->log('TEST_SUBMITTED');

        header('Location: /test/result/' . $test_id);
        exit;
    }

    // Show result
    public function result($test_id) {
        $user_id = $_SESSION['user_id'];

        $session = $this->test->getSession($test_id);

        if (!$session || $session['user_id'] != $user_id) {
            $this->redirect('/dashboard');
        }

        // Get answers
        $stmt = $this->db->prepare("
            SELECT ta.*, q.id as q_id, qt.question_text, a.is_correct as correct_answer,
                   at.answer_text as user_answer
            FROM test_answers ta
            JOIN questions q ON ta.question_id = q.id
            LEFT JOIN question_translations qt ON q.id = qt.question_id AND qt.language = 'en'
            LEFT JOIN answers a ON a.question_id = q.id AND a.is_correct = TRUE
            LEFT JOIN answer_translations at ON ta.selected_answer_id = at.answer_id AND at.language = 'en'
            WHERE ta.test_id = ?
        ");
        $stmt->execute([$test_id]);
        $answers = $stmt->fetchAll();

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
