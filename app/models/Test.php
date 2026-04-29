<?php

require_once '../core/Database.php';

class Test {
    
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function createSession($user_id, $question_ids, $duration = 1200) {
        $stmt = $this->db->prepare("
            INSERT INTO test_sessions (user_id, questions, start_time, duration)
            VALUES (?, ?, NOW(), ?)
        ");
        
        if ($stmt->execute([$user_id, json_encode($question_ids), $duration])) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function getSession($test_id) {
        $stmt = $this->db->prepare("
            SELECT * FROM test_sessions WHERE id = ?
        ");
        $stmt->execute([$test_id]);
        return $stmt->fetch();
    }

    public function getActiveSession($user_id) {
        $stmt = $this->db->prepare("
            SELECT * FROM test_sessions 
            WHERE user_id = ? AND completed = FALSE AND is_active = TRUE
            ORDER BY created_at DESC
            LIMIT 1
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetch();
    }

    public function getRemainingTime($test_id) {
        $session = $this->getSession($test_id);
        
        if (!$session) return 0;
        
        $start_time = strtotime($session['start_time']);
        $elapsed = time() - $start_time;
        $remaining = $session['duration'] - $elapsed;
        
        return max(0, $remaining);
    }

    public function isTimeUp($test_id) {
        return $this->getRemainingTime($test_id) <= 0;
    }

    public function saveAnswer($test_id, $question_id, $answer_id = null) {
        $answer_id = $answer_id !== null && $answer_id !== '' ? (int) $answer_id : null;
        $is_correct = false;

        if ($answer_id) {
            $stmt = $this->db->prepare("
                SELECT is_correct FROM answers WHERE id = ? AND question_id = ?
            ");
            $stmt->execute([$answer_id, $question_id]);
            $answer = $stmt->fetch();
            if ($answer) {
                $is_correct = (bool) $answer['is_correct'];
            } else {
                $answer_id = null;
            }
        }

        $this->db->prepare("
            DELETE FROM test_answers WHERE test_id = ? AND question_id = ?
        ")->execute([$test_id, $question_id]);

        $log_stmt = $this->db->prepare("
            INSERT INTO test_answers (test_id, question_id, selected_answer_id, is_correct)
            VALUES (?, ?, ?, ?)
        ");

        return $log_stmt->execute([$test_id, $question_id, $answer_id, $is_correct ? 1 : 0]);
    }

    public function completeTest($test_id) {
        // Get all answers and calculate score
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as score FROM test_answers 
            WHERE test_id = ? AND is_correct = TRUE
        ");
        $stmt->execute([$test_id]);
        $result = $stmt->fetch();
        $score = $result['score'];
        
        // Update test session
        $update = $this->db->prepare("
            UPDATE test_sessions SET score = ?, completed = TRUE, is_active = FALSE
            WHERE id = ?
        ");
        $update->execute([$score, $test_id]);
        
        return $score;
    }

    public function deactivateSession($test_id) {
        $stmt = $this->db->prepare("
            UPDATE test_sessions SET is_active = FALSE WHERE id = ?
        ");
        return $stmt->execute([$test_id]);
    }

    public function getUserTestHistory($user_id, $limit = 50, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT * FROM test_sessions 
            WHERE user_id = ? AND completed = TRUE
            ORDER BY created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$user_id, $limit, $offset]);
        return $stmt->fetchAll();
    }

    public function getTestResult($test_id) {
        $session = $this->getSession($test_id);
        
        $stmt = $this->db->prepare("
            SELECT * FROM test_answers WHERE test_id = ?
        ");
        $stmt->execute([$test_id]);
        $answers = $stmt->fetchAll();
        
        return [
            'session' => $session,
            'answers' => $answers,
            'passed' => $session['score'] >= PASS_SCORE
        ];
    }

    public function getTotalTestsCompleted($user_id) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count FROM test_sessions 
            WHERE user_id = ? AND completed = TRUE
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetch()['count'];
    }

    public function getAverageScore($user_id) {
        $stmt = $this->db->prepare("
            SELECT AVG(score) as avg_score FROM test_sessions 
            WHERE user_id = ? AND completed = TRUE
        ");
        $stmt->execute([$user_id]);
        $result = $stmt->fetch();
        return round($result['avg_score'] ?? 0, 2);
    }

    public function getPassRate($user_id) {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN score >= ? THEN 1 ELSE 0 END) as passed
            FROM test_sessions 
            WHERE user_id = ? AND completed = TRUE
        ");
        $stmt->execute([PASS_SCORE, $user_id]);
        $result = $stmt->fetch();
        
        if ($result['total'] == 0) return 0;
        return round(($result['passed'] / $result['total']) * 100, 2);
    }

    public function getMostFailedQuestions($limit = 10) {
        $stmt = $this->db->prepare("
            SELECT q.id, qt.question_text, 
                   COUNT(*) as times_answered,
                   SUM(CASE WHEN ta.is_correct = 1 THEN 1 ELSE 0 END) as times_correct,
                   ROUND(((SUM(CASE WHEN ta.is_correct = 1 THEN 1 ELSE 0 END) / COUNT(*)) * 100), 2) as success_rate
            FROM test_answers ta
            JOIN questions q ON ta.question_id = q.id
            LEFT JOIN question_translations qt ON q.id = qt.question_id AND qt.language = 'en'
            GROUP BY q.id
            HAVING times_answered >= 3
            ORDER BY success_rate ASC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}
