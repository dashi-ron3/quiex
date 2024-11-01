-- SAMPLE 

CREATE DATABASE qtest;

CREATE TABLE quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    description TEXT,
    open_date DATETIME,
    close_date DATETIME,
    timer_hours INT,
    timer_minutes INT,
    max_attempts INT,
    randomize_order TINYINT(1)
);

CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    question_text TEXT NOT NULL,
    correct_answer TEXT,
    points INT DEFAULT 0,
    feedback TEXT,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

CREATE TABLE attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    score INT NOT NULL,
    max_score INT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
);


SELECT * FROM quizzes;
SELECT * FROM questions;
SELECT * FROM attempts;
