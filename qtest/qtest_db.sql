CREATE DATABASE qtest;

CREATE TABLE quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    description TEXT,
    open_date DATETIME NOT NULL,
    close_date DATETIME NOT NULL,
    timer_hours INT DEFAULT 0,
    timer_minutes INT DEFAULT 0,
    max_attempts INT DEFAULT 1,
    randomize_order TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    question_text TEXT NOT NULL,
    correct_answer TEXT,
    points INT NOT NULL,
    feedback TEXT,
    question_type ENUM('short_answer', 'multiple_choice', 'true_false', 'essay') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

CREATE TABLE options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT,
    option_text VARCHAR(255) NOT NULL,
    is_correct TINYINT DEFAULT 0,
    FOREIGN KEY (question_id) REFERENCES questions(id)
);

CREATE TABLE attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    score INT NOT NULL,
    max_score INT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

CREATE TABLE answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attempt_id INT NOT NULL,
    question_id INT NOT NULL,
    student_answer TEXT NOT NULL,
    points_awarded INT DEFAULT 0,
    correct TINYINT(1) DEFAULT 0,
    FOREIGN KEY (attempt_id) REFERENCES attempts(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

SELECT * FROM quizzes;
SELECT * FROM questions;
SELECT * FROM answers;
SELECT * FROM attempts;
