-- PROVISOR EXAM SYSTEM DATABASE
-- Import this file in phpMyAdmin to create the database

CREATE DATABASE IF NOT EXISTS provisor_exam;
USE provisor_exam;

-- 1. USERS TABLE
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin','super_admin') DEFAULT 'user',
    status ENUM('active','blocked') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 2. CATEGORIES TABLE
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. QUESTIONS TABLE
CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    image VARCHAR(255),
    difficulty ENUM('easy','medium','hard') DEFAULT 'medium',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- 4. QUESTION TRANSLATIONS
CREATE TABLE IF NOT EXISTS question_translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    language VARCHAR(5),
    question_text TEXT NOT NULL,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
    UNIQUE KEY unique_question_lang (question_id, language)
);

-- 5. ANSWERS TABLE
CREATE TABLE IF NOT EXISTS answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    is_correct BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

-- 6. ANSWER TRANSLATIONS
CREATE TABLE IF NOT EXISTS answer_translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    answer_id INT NOT NULL,
    language VARCHAR(5),
    answer_text TEXT NOT NULL,
    FOREIGN KEY (answer_id) REFERENCES answers(id) ON DELETE CASCADE,
    UNIQUE KEY unique_answer_lang (answer_id, language)
);

-- 7. TEST SESSIONS
CREATE TABLE IF NOT EXISTS test_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    questions JSON,
    score INT DEFAULT 0,
    completed BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    start_time DATETIME,
    duration INT DEFAULT 1200,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 8. TEST ANSWERS LOG
CREATE TABLE IF NOT EXISTS test_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    test_id INT NOT NULL,
    question_id INT NOT NULL,
    selected_answer_id INT,
    is_correct BOOLEAN,
    FOREIGN KEY (test_id) REFERENCES test_sessions(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

-- 9. PAYMENT PLANS
CREATE TABLE IF NOT EXISTS payment_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    price INT NOT NULL,
    test_count INT,
    duration_days INT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 10. PAYMENTS TABLE
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    plan_id INT NOT NULL,
    amount INT NOT NULL,
    phone VARCHAR(20),
    status ENUM('pending','success','failed') DEFAULT 'pending',
    transaction_id VARCHAR(100),
    payment_method VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES payment_plans(id) ON DELETE CASCADE
);

-- 11. USER ACCESS / CREDITS
CREATE TABLE IF NOT EXISTS user_access (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    remaining_tests INT DEFAULT 0,
    unlimited BOOLEAN DEFAULT FALSE,
    expires_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 12. ACTIVITY LOGS
CREATE TABLE IF NOT EXISTS logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255),
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- INSERT DEFAULT CATEGORIES
INSERT INTO categories (name, description, icon) VALUES
('Road Signs', 'Identify and understand road signs', '🛑'),
('Road Rules', 'Driving rules and regulations', '📋'),
('Safety', 'Safety measures and precautions', '🛡️'),
('Vehicle Maintenance', 'Basic vehicle care and maintenance', '🔧'),
('Parking', 'Parking rules and techniques', '🅿️');

-- INSERT SAMPLE PAYMENT PLANS
INSERT INTO payment_plans (name, price, test_count, duration_days) VALUES
('2 Tests', 200, 2, NULL),
('6 Tests', 500, 6, NULL),
('12 Tests', 1000, 12, NULL),
('Daily Access', 1900, NULL, 1),
('Weekly Access', 4500, NULL, 7),
('Monthly Access', 9000, NULL, 30);

-- CREATE INDEXES FOR BETTER PERFORMANCE
CREATE INDEX idx_user_id ON test_sessions(user_id);
CREATE INDEX idx_question_category ON questions(category_id);
CREATE INDEX idx_test_user ON test_answers(test_id);
CREATE INDEX idx_payment_user ON payments(user_id);
CREATE INDEX idx_payment_status ON payments(status);
CREATE INDEX idx_logs_user ON logs(user_id);
CREATE INDEX idx_logs_created ON logs(created_at);
