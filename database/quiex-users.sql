CREATE DATABASE quiex;

USE quiex;

-- user registration, login, and data for the user profile in settings
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(100) NULL,
    age INT NULL, 
    gr_level VARCHAR(50) NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- user type classification during sign up
ALTER TABLE users ADD COLUMN user_type ENUM('student', 'teacher') NOT NULL;

INSERT INTO users (username, email, name, age, gr_level, password, user_type)
VALUES 
('john_doe', 'john@example.com', 'John Doe', 16, '10th Grade', 'password123', 'student'),
('jane_smith', 'jane@example.com', 'Jane Smith', 15, '10th Grade', 'password123', 'student');

-- create assessment
CREATE TABLE IF NOT EXISTS assessments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject VARCHAR(255),
    title VARCHAR(255),
    content TEXT,
    status ENUM('draft', 'published') DEFAULT 'draft',
    unique_code VARCHAR(10) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO assessments (subject, title, content, status, unique_code, created_at)
VALUES 
('Math','Math Quiz 1', 'Basic Algebra Quiz', 'published', 'MATH101', "2024-10-21 11:00:00");

-- Quizzes table to store quiz information
CREATE TABLE quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    description TEXT,
    open_date DATETIME,
    close_date DATETIME,
    max_attempts INT,
    randomize_order TINYINT
);

-- Questions table to store individual quiz questions
CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    question_text TEXT NOT NULL,
    question_type TEXT NOT NULL,
    correct_answer TEXT,
    points INT DEFAULT 0,
    feedback TEXT,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

-- Options table for multiple choice answers
CREATE TABLE options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    option_text VARCHAR(255) NOT NULL,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

-- Attempts table to store user attempts on quizzes
CREATE TABLE attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    quiz_title VARCHAR(255),
    user_id INT NOT NULL,
    score INT NOT NULL,
    max_score INT NOT NULL,
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- added
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Answers table to store the student's answers
CREATE TABLE answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attempt_id INT NOT NULL,
    quiz_id INT NOT NULL,
    question_id INT NOT NULL,
    student_answer TEXT NOT NULL,
    points_awarded INT DEFAULT 0,
    correct TINYINT DEFAULT 0,
    FOREIGN KEY (attempt_id) REFERENCES attempts(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

SELECT * FROM assessments;

-- save questions in the database
CREATE TABLE quiex_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    assessment_id INT NOT NULL,
    question_type ENUM('multiple-choice', 'true-false', 'long-answer', 'short-answer', 'checkboxes') NOT NULL,
    question_text TEXT NOT NULL,
    points INT DEFAULT 0,
    FOREIGN KEY (assessment_id) REFERENCES assessments(id) ON DELETE CASCADE
);

-- quiex choices
CREATE TABLE quiex_choices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    choice_text VARCHAR(255) NOT NULL,
    is_correct BOOLEAN DEFAULT 0,
    FOREIGN KEY (question_id) REFERENCES quiex_questions(id) ON DELETE CASCADE
);

SELECT * FROM quiex_choices;

-- img or vid upload
CREATE TABLE files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type ENUM('image', 'video') NOT NULL, 
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (question_id) REFERENCES quiex_questions(id) ON DELETE CASCADE
);

-- uploaded assessments
CREATE TABLE uploadedAss (
    upAss INT AUTO_INCREMENT PRIMARY KEY,
    quizId INT NOT NULL,
    subject VARCHAR(255),
    title text,
    status VARCHAR(255) DEFAULT 'Done',
    lastUsed DATE,
    descrip text,
    shared TINYINT DEFAULT (0),
    FOREIGN KEY (quizId) REFERENCES quizzes(id)
);

SELECT * FROM uploadedAss;

-- FOR TESTING ASSESSMENTS
INSERT INTO uploadedAss (quizId, subject, title, status, lastUsed, descrip) VALUES
(1, 'Science', 'Sample Science Quiz 1', 'In Progress', '2024-02-10', 'An examination of basic physics concepts.');
-- ('History', 'Sample History Quiz', 'Not Started', '2024-03-01', 'An assignment about World War II.'),
-- ("Biology","Sample Biology Exam", "Done", "2023-11-09", "Sample Test Description.");

-- leaderboard
CREATE TABLE leaderboard (
    id INT AUTO_INCREMENT PRIMARY KEY,
	user_id INT NOT NULL,
    quiz_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    points INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
);

-- FOR TESTING OF LEADERBOARD
INSERT INTO leaderboard (name, points) VALUES 
    ("Student1", "1000"),
    ("Student2", "200"),
    ("Student3", "1200"),
    ("Student4", "800"),
    ("Student5", "500");

CREATE TABLE student_scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quizId INT,
    assessmentTitle VARCHAR(255) NOT NULL,
    studentName VARCHAR(255) NOT NULL,
    score INT NOT NULL,
    totalPoints INT NOT NULL,
    incorrectQuestions VARCHAR(255),
    FOREIGN KEY (quizId) REFERENCES quizzes(id)
);
-- SAMPLE DATA FOR student scores
INSERT INTO student_scores (assessmentTitle, studentName, score, totalPoints, incorrectQuestions) VALUES
    ('Sample Science Quiz', 'Alice Smith', 90, 100, '1,3'),
    ('Sample Science Quiz', 'John Doe', 65, 90, '2,5,6'),
    ('Sample Science Quiz', 'Jane Johnson', 77, 85, '2,8'),
    ('Sample Science Quiz', 'Alice Smith', 94, 100, '4');

-- Insert sample data into the quizzes table
INSERT INTO quizzes (title, subject, description, open_date, close_date, max_attempts, randomize_order)
VALUES ('Sample Quiz', 'Mathematics', 'This is a sample quiz on basic math concepts.', '2024-11-01 08:00:00', '2024-11-10 23:59:59', 3, 0);

-- Insert sample data into the questions table
INSERT INTO questions (quiz_id, question_text, question_type, correct_answer, points, feedback)
VALUES 
    (1, 'What is 2 + 2?', 'multiple-choice', '4', 1, 'The correct answer is 4.'),
    (1, 'What is 5 + 5?', 'multiple-choice', '10', 1, 'Incorrect. The correct answer is 10.'),
    (1, 'Is the Earth flat?', 'true-false', 'false', 1, 'The Earth is not flat.'),
    (1, 'Is the moon made of cheese?', 'true-false', 'false', 1, 'Incorrect. The moon is not made of cheese.'),
    (1, 'Explain the process of photosynthesis.', 'long-answer', 'Plants convert sunlight into energy.', 2, 'Correct answer.'),
    (1, 'What is the process of photosynthesis?', 'long-answer', 'Plants produce oxygen from carbon dioxide.', 2, 'Incorrect answer. The correct process involves converting sunlight into energy.'),
    (1, 'What is the capital of France?', 'short-answer', 'Paris', 1, 'Correct! Paris is the capital of France.'),
    (1, 'What is the capital of Germany?', 'short-answer', 'Berlin', 1, 'Incorrect. The capital of Germany is Berlin.');

-- Insert sample data into the options table for multiple choice and true/false questions
INSERT INTO options (question_id, option_text)
VALUES 
    (1, '3'), 
    (1, '4'), 
    (1, '5'), 
    (1, '6'),
    (2, '10'), 
    (2, '11'), 
    (2, '12'), 
    (2, '9'),
    (3, 'True'), 
    (3, 'False'), 
    (4, 'True'), 
    (4, 'False');

INSERT INTO users (username, email, name, age, gr_level, password, user_type)
VALUES 
('student', 'student@example.com', 'Student', 16, '10th Grade', 'student', 'student');

-- Insert sample data into the attempts table
INSERT INTO attempts (quiz_id, user_id, score, max_score, started_at, submitted_at)
VALUES (1, 3, 3, 4, '2024-11-02 10:00:00', '2024-11-02 10:15:00');

-- Insert sample data into the answers table
INSERT INTO answers (attempt_id, question_id, student_answer, points_awarded, correct)
VALUES 
    (1, 1, '4', 1, 1),  -- Correct answer for Question 1
    (1, 2, '11', 0, 0),  -- Incorrect answer for Question 2
    (1, 3, 'false', 1, 1),  -- Correct answer for Question 3
    (1, 4, 'true', 0, 0),  -- Incorrect answer for Question 4
    (1, 5, 'Plants convert sunlight into energy.', 2, 1),  -- Correct answer for Question 5
    (1, 6, 'Plants produce oxygen from carbon dioxide.', 0, 0),  -- Incorrect answer for Question 6
    (1, 7, 'Paris', 1, 1),  -- Correct answer for Question 7
    (1, 8, 'Berlin', 0, 0);  -- Incorrect answer for Question 8

SELECT * FROM users;
SELECT * FROM quizzes;
SELECT * FROM questions;
SELECT * FROM options;
SELECT * FROM attempts;
SELECT * FROM answers;
SELECT * FROM quizzes WHERE id = 1;
SELECT * FROM options WHERE question_id = 1;


