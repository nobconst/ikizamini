<?php

require_once '../core/Controller.php';
require_once '../app/models/User.php';
require_once '../app/models/Question.php';
require_once '../app/models/Payment.php';
require_once '../app/models/Test.php';

class AdminController extends Controller {
    
    private $user_model;
    private $question_model;
    private $payment_model;
    private $test_model;

    public function __construct() {
        parent::__construct();
        $this->requireLogin();
        $this->requireAdmin();
        
        $this->user_model = new User();
        $this->question_model = new Question();
        $this->payment_model = new Payment();
        $this->test_model = new Test();
    }

    // Admin Dashboard
    public function dashboard() {
        $total_users = $this->user_model->getTotalUsers();
        $total_questions = $this->question_model->getTotalQuestions();
        
        $stats = $this->payment_model->getSystemStats();
        
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM test_sessions WHERE completed = TRUE");
        $total_tests = $stmt->fetch()['count'];
        
        $categories = $this->db->query("SELECT * FROM categories")->fetchAll();
        
        $this->view('admin/dashboard', [
            'total_users' => $total_users,
            'total_questions' => $total_questions,
            'total_tests' => $total_tests,
            'stats' => $stats,
            'categories' => $categories
        ]);
    }

    // Manage Users
    public function users() {
        $page = $_GET['page'] ?? 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $users = $this->user_model->getAllUsers($limit, $offset);
        $total = $this->user_model->getTotalUsers();
        $total_pages = ceil($total / $limit);

        $this->view('admin/users', [
            'users' => $users,
            'page' => $page,
            'total_pages' => $total_pages
        ]);
    }

    public function blockUser($user_id) {
        $this->user_model->blockUser($user_id);
        $this->log('USER_BLOCKED', $user_id);
        $_SESSION['success'] = 'User blocked';
        header('Location: /admin/users');
        exit;
    }

    public function unblockUser($user_id) {
        $this->user_model->unblockUser($user_id);
        $this->log('USER_UNBLOCKED', $user_id);
        $_SESSION['success'] = 'User unblocked';
        header('Location: /admin/users');
        exit;
    }

    // Handle question actions (add, edit, delete, etc.)
    public function question($action = 'list') {
        switch($action) {
            case 'add':
                $this->addQuestion();
                break;
            default:
                $this->redirect('ikizamini/admin/questions');
        }
    }

    // Manage Questions
    public function questions() {
        $page = $_GET['page'] ?? 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;
        $lang = $_SESSION['lang'] ?? 'en';

        $questions = $this->question_model->getAllQuestions($limit, $offset, $lang);
        $total = $this->question_model->getTotalQuestions();
        $total_pages = ceil($total / $limit);

        $this->view('admin/questions', [
            'questions' => $questions,
            'page' => $page,
            'total_pages' => $total_pages,
            'lang' => $lang
        ]);
    }

    public function addQuestion() {
        $categories = $this->db->query("SELECT * FROM categories")->fetchAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category_id = $_POST['category_id'] ?? null;
            $languages = $_POST['languages'] ?? [];

            if (!$category_id) {
                $_SESSION['error'] = 'Category is required';
                $this->redirect('/admin/question/add');
            }

            if (empty($languages)) {
                $_SESSION['error'] = 'Please select at least one language';
                $this->redirect('/admin/question/add');
            }

            // Validate each selected language
            foreach ($languages as $lang) {
                $question_text = $_POST["question_${lang}"] ?? '';
                if (empty($question_text)) {
                    $_SESSION['error'] = "Question text is required for " . strtoupper($lang);
                    $this->redirect('/admin/question/add');
                }

                // Check all 4 answers are filled
                for ($i = 1; $i <= 4; $i++) {
                    $answer_text = $_POST["answer_${lang}_${i}"] ?? '';
                    if (empty($answer_text)) {
                        $_SESSION['error'] = "All 4 answer options must be filled for " . strtoupper($lang);
                        $this->redirect('/admin/question/add');
                    }
                }

                // Check exactly one correct answer
                $correct_answer = $_POST["correct_${lang}"] ?? null;
                if (!$correct_answer) {
                    $_SESSION['error'] = "Please select exactly ONE correct answer for " . strtoupper($lang);
                    $this->redirect('/admin/question/add');
                }
            }

            $image_name = null;

            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $max_size = 2 * 1024 * 1024; // 2MB

                if (!in_array($_FILES['image']['type'], $allowed_types)) {
                    $_SESSION['error'] = 'Invalid image format. Only JPG, PNG, GIF, WebP allowed';
                    $this->redirect('/admin/question/add');
                }

                if ($_FILES['image']['size'] > $max_size) {
                    $_SESSION['error'] = 'Image size must not exceed 2MB';
                    $this->redirect('/admin/question/add');
                }

                // Create upload directory if not exists
                $upload_dir = '../public/assets/images/questions/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                // Generate unique filename
                $image_name = uniqid('q_') . '_' . basename($_FILES['image']['name']);
                $upload_path = $upload_dir . $image_name;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $_SESSION['error'] = 'Failed to upload image';
                    $this->redirect('/admin/question/add');
                }
            }

            // Create question (only once)
            $question_id = $this->question_model->create($category_id, $image_name);

            if ($question_id) {
                // Process each selected language independently
                foreach ($languages as $lang) {
                    $question_text = $_POST["question_${lang}"];
                    
                    // Add question translation for this language
                    $this->question_model->addTranslation($question_id, $lang, $question_text);
                }

                // Get the correct answer number from the first language
                $first_lang = $languages[0];
                $correct_answer_num = $_POST["correct_${first_lang}"];

                // Create 4 answers (only once for the question)
                for ($i = 1; $i <= 4; $i++) {
                    $is_correct = ($i == $correct_answer_num) ? 1 : 0;
                    $answer_id = $this->question_model->addAnswer($question_id, $is_correct);

                    if ($answer_id) {
                        // Add answer translations for each selected language
                        foreach ($languages as $lang) {
                            $answer_text = $_POST["answer_${lang}_${i}"];
                            $this->question_model->addAnswerTranslation($answer_id, $lang, $answer_text);
                        }
                    }
                }

                $_SESSION['success'] = 'Question added successfully in ' . count($languages) . ' language(s)';
                $this->log('QUESTION_ADDED');
                $this->redirect('/admin/questions');
            }
        }

        $this->view('admin/question-add', ['categories' => $categories]);
    }

    public function drivingTestImport() {
        $categories = $this->db->query("SELECT * FROM categories")->fetchAll();
        $this->view('admin/driving-test-import', ['categories' => $categories]);
    }

    public function importDrivingTestQuestions() {
        // Clean any output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Set JSON header
        header('Content-Type: application/json');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            $questions = json_decode($_POST['questions'] ?? '[]', true);
            $category_id = $_POST['category_id'] ?? 1;

            if (empty($questions)) {
                throw new Exception('No questions to import');
            }

            $imported = 0;
            $updated = 0;
            $errors = 0;

            // Begin transaction
            $this->db->beginTransaction();

            foreach ($questions as $q) {
                try {
                    // Check if question already exists by question number
                    $existingStmt = $this->db->prepare("
                        SELECT q.id FROM questions q
                        JOIN question_translations qt ON q.id = qt.question_id
                        WHERE qt.question_text LIKE ? AND qt.language = 'rw'
                        LIMIT 1
                    ");
                    $existingStmt->execute(['%' . trim($q['question']) . '%']);
                    $existing = $existingStmt->fetch();

                    if ($existing) {
                        // Update existing question
                        $question_id = $existing['id'];
                        $updated++;
                    } else {
                        // Create new question
                        $stmt = $this->db->prepare("INSERT INTO questions (category_id, image) VALUES (?, ?)");
                        $stmt->execute([$category_id, null]);
                        $question_id = $this->db->lastInsertId();
                        $imported++;
                    }

                    // Add/update question translation (Kinyarwanda)
                    $qtStmt = $this->db->prepare("
                        INSERT INTO question_translations (question_id, language, question_text)
                        VALUES (?, 'rw', ?)
                        ON DUPLICATE KEY UPDATE question_text = VALUES(question_text)
                    ");
                    $qtStmt->execute([$question_id, trim($q['question'])]);

                    // Delete existing answers for this question to avoid duplicates
                    $delStmt = $this->db->prepare("DELETE FROM answers WHERE question_id = ?");
                    $delStmt->execute([$question_id]);

                    // Create 4 answers
                    $choices = [$q['choice1'], $q['choice2'], $q['choice3'], $q['choice4']];
                    $correctLetter = strtolower($q['correct_answer']);
                    $correctIndex = ord($correctLetter) - ord('a'); // a=0, b=1, c=2, d=3

                    for ($i = 0; $i < 4; $i++) {
                        $isCorrect = ($i === $correctIndex) ? 1 : 0;
                        
                        // Add answer
                        $ansStmt = $this->db->prepare("
                            INSERT INTO answers (question_id, is_correct) 
                            VALUES (?, ?)
                        ");
                        $ansStmt->execute([$question_id, $isCorrect]);
                        $answer_id = $this->db->lastInsertId();

                        // Add answer translation (Kinyarwanda)
                        $atStmt = $this->db->prepare("
                            INSERT INTO answer_translations (answer_id, language, answer_text)
                            VALUES (?, 'rw', ?)
                        ");
                        $atStmt->execute([$answer_id, trim($choices[$i])]);
                    }

                } catch (Exception $e) {
                    $errors++;
                    error_log("Error importing question {$q['question_number']}: " . $e->getMessage());
                    continue;
                }
            }

            // Commit transaction
            $this->db->commit();

            $this->log('DRIVING_TEST_IMPORTED', [
                'imported' => $imported,
                'updated' => $updated,
                'errors' => $errors
            ]);

            echo json_encode([
                'success' => true,
                'imported' => $imported,
                'updated' => $updated,
                'errors' => $errors,
                'message' => "Successfully processed {$imported} new questions and updated {$updated} existing questions."
            ]);
            exit;

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }

    public function deleteQuestion($question_id) {
        $this->question_model->deleteQuestion($question_id);
        $this->log('QUESTION_DELETED');
        $_SESSION['success'] = 'Question deleted';
        header('Location: /ikizamini/admin/questions');
        exit;
    }
    public function viewQuestion($question_id) {
        // Get question with all translations
        $stmt = $this->db->prepare("
            SELECT DISTINCT q.*, 
                   qt_en.question_text as question_text,
                   qt_en.language as en_lang,
                   qt_fr.question_text as question_fr,
                   qt_fr.language as fr_lang,
                   qt_rw.question_text as question_rw,
                   qt_rw.language as rw_lang,
                   c.name as category_name
            FROM questions q
            LEFT JOIN question_translations qt_en ON q.id = qt_en.question_id AND qt_en.language = 'en'
            LEFT JOIN question_translations qt_fr ON q.id = qt_fr.question_id AND qt_fr.language = 'fr'
            LEFT JOIN question_translations qt_rw ON q.id = qt_rw.question_id AND qt_rw.language = 'rw'
            LEFT JOIN categories c ON q.category_id = c.id
            WHERE q.id = ?
        ");
        $stmt->execute([$question_id]);
        $question = $stmt->fetch();

        if (!$question) {
            $_SESSION['error'] = 'Question not found';
            header('Location: /admin/questions');
            exit;
        }

        // Get answers with all translations
        $answers_stmt = $this->db->prepare("
            SELECT DISTINCT a.id,
                   at_en.answer_text as answer_text,
                   at_en.language as en_lang,
                   at_fr.answer_text as answer_fr,
                   at_fr.language as fr_lang,
                   at_rw.answer_text as answer_rw,
                   at_rw.language as rw_lang,
                   a.is_correct
            FROM answers a
            LEFT JOIN answer_translations at_en ON a.id = at_en.answer_id AND at_en.language = 'en'
            LEFT JOIN answer_translations at_fr ON a.id = at_fr.answer_id AND at_fr.language = 'fr'
            LEFT JOIN answer_translations at_rw ON a.id = at_rw.answer_id AND at_rw.language = 'rw'
            WHERE a.question_id = ?
            ORDER BY a.id
        ");
        $answers_stmt->execute([$question_id]);
        $answers = $answers_stmt->fetchAll();

        $this->view('admin/question-view', [
            'question' => $question,
            'answers' => $answers
        ]);
    }

    public function editQuestion($question_id) {
        $categories = $this->db->query("SELECT * FROM categories")->fetchAll();

        // Get question with all translations
        $stmt = $this->db->prepare("
            SELECT DISTINCT q.*, 
                   qt_en.question_text as question_en,
                   qt_fr.question_text as question_fr,
                   qt_rw.question_text as question_rw
            FROM questions q
            LEFT JOIN question_translations qt_en ON q.id = qt_en.question_id AND qt_en.language = 'en'
            LEFT JOIN question_translations qt_fr ON q.id = qt_fr.question_id AND qt_fr.language = 'fr'
            LEFT JOIN question_translations qt_rw ON q.id = qt_rw.question_id AND qt_rw.language = 'rw'
            WHERE q.id = ?
        ");
        $stmt->execute([$question_id]);
        $question = $stmt->fetch();

        if (!$question) {
            $_SESSION['error'] = 'Question not found';
            header('Location: /admin/questions');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category_id = $_POST['category_id'] ?? $question['category_id'];
            $languages = $_POST['languages'] ?? [];

            if (!$category_id || empty($languages)) {
                $_SESSION['error'] = 'Category and at least one language are required';
            } else {
                // Validate each selected language
                foreach ($languages as $lang) {
                    $question_text = $_POST["question_${lang}"] ?? '';
                    if (empty($question_text)) {
                        $_SESSION['error'] = "Question text is required for " . strtoupper($lang);
                        $this->redirect('/admin/editQuestion/' . $question_id);
                    }

                    // Check all 4 answers are filled
                    for ($i = 1; $i <= 4; $i++) {
                        $answer_text = $_POST["answer_${lang}_${i}"] ?? '';
                        if (empty($answer_text)) {
                            $_SESSION['error'] = "All 4 answer options must be filled for " . strtoupper($lang);
                            $this->redirect('/admin/editQuestion/' . $question_id);
                        }
                    }

                    // Check exactly one correct answer
                    $correct_answer = $_POST["correct_${lang}"] ?? null;
                    if (!$correct_answer) {
                        $_SESSION['error'] = "Please select exactly ONE correct answer for " . strtoupper($lang);
                        $this->redirect('/admin/editQuestion/' . $question_id);
                    }
                }

                // Handle image upload if new image is provided
                $image_name = $question['image']; // Keep current image by default
                
                if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
                    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    $max_size = 2 * 1024 * 1024; // 2MB

                    if (!in_array($_FILES['image']['type'], $allowed_types)) {
                        $_SESSION['error'] = 'Invalid image format. Only JPG, PNG, GIF, WebP allowed';
                        $this->redirect('/admin/editQuestion/' . $question_id);
                    }

                    if ($_FILES['image']['size'] > $max_size) {
                        $_SESSION['error'] = 'Image size must not exceed 2MB';
                        $this->redirect('/admin/editQuestion/' . $question_id);
                    }

                    // Create upload directory if not exists
                    $upload_dir = '../public/assets/images/questions/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }

                    // Generate unique filename
                    $image_name = uniqid('q_') . '_' . basename($_FILES['image']['name']);
                    $upload_path = $upload_dir . $image_name;

                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                        $_SESSION['error'] = 'Failed to upload image';
                        $this->redirect('/admin/editQuestion/' . $question_id);
                    }
                }

                // Update question category and image
                $update_stmt = $this->db->prepare("UPDATE questions SET category_id = ?, image = ? WHERE id = ?");
                $update_stmt->execute([$category_id, $image_name, $question_id]);

                // Process each selected language independently
                foreach ($languages as $lang) {
                    $question_text = $_POST["question_${lang}"];
                    
                    // Add/update question translation for this language
                    $this->question_model->addTranslation($question_id, $lang, $question_text);
                }

                // Get the correct answer number from the first language
                $first_lang = $languages[0];
                $correct_answer_num = $_POST["correct_${first_lang}"];

                // Update answers (only once for the question)
                for ($i = 1; $i <= 4; $i++) {
                    $is_correct = ($i == $correct_answer_num) ? 1 : 0;
                    
                    // Check if answer exists
                    $check_stmt = $this->db->prepare("SELECT id FROM answers WHERE id = ? LIMIT 1");
                    $check_stmt->execute([$_POST["answer_{$i}_id"] ?? 0]);
                    if ($check_stmt->fetch()) {
                        $answer_id = $_POST["answer_{$i}_id"];
                        $this->db->prepare("UPDATE answers SET is_correct = ? WHERE id = ?")->execute([$is_correct, $answer_id]);
                    } else {
                        $answer_id = $this->question_model->addAnswer($question_id, $is_correct);
                    }

                    if ($answer_id) {
                        // Add/update answer translations for each selected language
                        foreach ($languages as $lang) {
                            $answer_text = $_POST["answer_${lang}_${i}"];
                            $this->question_model->addAnswerTranslation($answer_id, $lang, $answer_text);
                        }
                    }
                }

                $_SESSION['success'] = 'Question updated successfully';
                $this->log('QUESTION_UPDATED');
                $this->redirect('/admin/questions');
            }
        }

        // Get answers with all translations
        $answers_stmt = $this->db->prepare("
            SELECT DISTINCT a.id,
                   at_en.answer_text as answer_en,
                   at_fr.answer_text as answer_fr,
                   at_rw.answer_text as answer_rw,
                   a.is_correct
            FROM answers a
            LEFT JOIN answer_translations at_en ON a.id = at_en.answer_id AND at_en.language = 'en'
            LEFT JOIN answer_translations at_fr ON a.id = at_fr.answer_id AND at_fr.language = 'fr'
            LEFT JOIN answer_translations at_rw ON a.id = at_rw.answer_id AND at_rw.language = 'rw'
            WHERE a.question_id = ?
            ORDER BY a.id
        ");
        $answers_stmt->execute([$question_id]);
        $answers = $answers_stmt->fetchAll();

        $this->view('admin/question-edit', [
            'question' => $question,
            'categories' => $categories,
            'answers' => $answers
        ]);
    }
    // Manage Payments
    public function payments() {
        $page = $_GET['page'] ?? 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $stmt = $this->db->prepare("
            SELECT p.*, pp.name, u.name as user_name
            FROM payments p
            LEFT JOIN payment_plans pp ON p.plan_id = pp.id
            LEFT JOIN users u ON p.user_id = u.id
            ORDER BY p.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        $payments = $stmt->fetchAll();

        $total_stmt = $this->db->query("SELECT COUNT(*) as count FROM payments");
        $total = $total_stmt->fetch()['count'];
        $total_pages = ceil($total / $limit);

        $this->view('admin/payments', [
            'payments' => $payments,
            'page' => $page,
            'total_pages' => $total_pages
        ]);
    }

    // Reports
    public function reports() {
        $failed_questions = $this->test_model->getMostFailedQuestions(10);

        $this->view('admin/reports', [
            'failed_questions' => $failed_questions
        ]);
    }

    // Categories
    public function categories() {
        $categories = $this->db->query("SELECT * FROM categories")->fetchAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            if (empty($name)) {
                $_SESSION['error'] = 'Category name is required';
            } else {
                $stmt = $this->db->prepare("
                    INSERT INTO categories (name, description)
                    VALUES (?, ?)
                ");
                $stmt->execute([$name, $description]);
                $_SESSION['success'] = 'Category added';
                $this->log('CATEGORY_ADDED');
            }

            header('Location: /admin/categories');
            exit;
        }

        $this->view('admin/categories', ['categories' => $categories]);
    }
}
