<?php

require_once '../core/Database.php';

class Question {
    
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function create($category_id, $image, $difficulty = 'medium') {
        $stmt = $this->db->prepare("
            INSERT INTO questions (category_id, image, difficulty)
            VALUES (?, ?, ?)
        ");
        
        if ($stmt->execute([$category_id, $image, $difficulty])) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function addTranslation($question_id, $language, $question_text) {
        $stmt = $this->db->prepare("
            INSERT INTO question_translations (question_id, language, question_text)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE question_text = ?
        ");
        return $stmt->execute([$question_id, $language, $question_text, $question_text]);
    }

    public function addAnswer($question_id, $is_correct = false) {
        $stmt = $this->db->prepare("
            INSERT INTO answers (question_id, is_correct)
            VALUES (?, ?)
        ");
        
        if ($stmt->execute([$question_id, $is_correct])) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function addAnswerTranslation($answer_id, $language, $answer_text) {
        $stmt = $this->db->prepare("
            INSERT INTO answer_translations (answer_id, language, answer_text)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE answer_text = ?
        ");
        return $stmt->execute([$answer_id, $language, $answer_text, $answer_text]);
    }

    public function getRandomQuestions($count = 20, $language = 'en') {
        $stmt = $this->db->prepare("
            SELECT q.id, qt.question_text, q.image
            FROM questions q
            JOIN question_translations qt ON q.id = qt.question_id
            WHERE qt.language = ?
            ORDER BY RAND()
            LIMIT ?
        ");
        $stmt->execute([$language, $count]);
        return $stmt->fetchAll();
    }

    public function getQuestionsWithAnswers($question_ids, $language = 'en') {
        if (empty($question_ids)) return [];
        
        $ids = implode(',', array_map('intval', $question_ids));
        
        $sql = "
            SELECT q.id, qt.question_text, q.image,
                   a.id as answer_id, at.answer_text
            FROM questions q
            JOIN question_translations qt ON q.id = qt.question_id
            LEFT JOIN answers a ON q.id = a.question_id
            LEFT JOIN answer_translations at ON a.id = at.answer_id AND at.language = ?
            WHERE qt.language = ? AND q.id IN ($ids)
            ORDER BY q.id, a.id
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$language, $language]);
        return $stmt->fetchAll();
    }

    public function getById($id, $language = 'en') {
        $stmt = $this->db->prepare("
            SELECT q.id, qt.question_text, q.image, q.category_id
            FROM questions q
            JOIN question_translations qt ON q.id = qt.question_id
            WHERE q.id = ? AND qt.language = ?
        ");
        $stmt->execute([$id, $language]);
        return $stmt->fetch();
    }

    public function getAnswers($question_id, $language = 'en') {
        $stmt = $this->db->prepare("
            SELECT a.id, at.answer_text, a.is_correct
            FROM answers a
            LEFT JOIN answer_translations at ON a.id = at.answer_id AND at.language = ?
            WHERE a.question_id = ?
            ORDER BY a.id
        ");
        $stmt->execute([$language, $question_id]);
        return $stmt->fetchAll();
    }

    public function getCorrectAnswer($question_id, $language = 'en') {
        $stmt = $this->db->prepare("
            SELECT a.id, at.answer_text
            FROM answers a
            LEFT JOIN answer_translations at ON a.id = at.answer_id AND at.language = ?
            WHERE a.question_id = ? AND a.is_correct = 1
        ");
        $stmt->execute([$language, $question_id]);
        return $stmt->fetch();
    }

    public function getByCategory($category_id, $count = 20, $language = 'en') {
        $stmt = $this->db->prepare("
            SELECT q.id, qt.question_text, q.image
            FROM questions q
            JOIN question_translations qt ON q.id = qt.question_id
            WHERE q.category_id = ? AND qt.language = ?
            ORDER BY RAND()
            LIMIT ?
        ");
        $stmt->execute([$category_id, $language, $count]);
        return $stmt->fetchAll();
    }

    public function getAllQuestions($limit = 50, $offset = 0, $language = 'en') {
        $stmt = $this->db->prepare("
            SELECT q.*, qt.question_text, c.name as category_name
            FROM questions q
            LEFT JOIN question_translations qt ON q.id = qt.question_id AND qt.language = ?
            LEFT JOIN categories c ON q.category_id = c.id
            ORDER BY q.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$language, $limit, $offset]);
        return $stmt->fetchAll();
    }

    public function getTotalQuestions() {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM questions");
        return $stmt->fetch()['count'];
    }

    public function deleteQuestion($id) {
        $stmt = $this->db->prepare("DELETE FROM questions WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
