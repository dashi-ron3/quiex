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
    randomize_order TINYINT(1)
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
    user_id INT NOT NULL,
    score INT NOT NULL,
    max_score INT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Answers table to store the student's answers
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
    status VARCHAR(255),
    lastUsed DATE,
    descrip text,
    shared TINYINT DEFAULT (0),
    FOREIGN KEY (quizId) REFERENCES assessments(id)
);

SELECT * FROM uploadedAss;

-- FOR TESTING ASSESSMENTS
INSERT INTO uploadedAss (quizId, subject, title, status, lastUsed, descrip) VALUES
(1, 'Science', 'Sample Science Quiz 1', 'In Progress', '2024-02-10', 'An examination of basic physics concepts.');
-- ('History', 'Sample History Quiz', 'Not Started', '2024-03-01', 'An assignment about World War II.'),
-- ("Biology","Sample Biology Exam", "Done", "2023-11-09", "Sample Test Description.");

-- leaderboard
CREATE TABLE leaderboard (
    -- quizID INT AUTO_INCREMENT PRIMARY KEY,
    id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    profile_pic VARCHAR(255) NOT NULL,
    points INT NOT NULL
    -- FOREIGN KEY (quizID) REFERENCES Quiz Table_name(quizID),
	-- FOREIGN KEY (id) REFERENCES users(id)
);

-- FOR TESTING OF LEADERBOARD
-- INSERT INTO lb (name, profile_pic, points) VALUES 
-- ("Student1", "desktop_wp.jpg", "1000"),

-- study companion --

-- quizzes table (connected to users) SAMPLE
CREATE TABLE IF NOT EXISTS quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255),
    started_at DATETIME,
    finished_at DATETIME,
    time_taken TIME,
    marks INT,
    total_marks INT,
    points INT, -- points = score
    FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO quizzes (user_id, title, started_at, finished_at, time_taken, points, total_marks)
VALUES 
(1, 'Math Quiz 1', '2024-01-01 10:00:00', '2024-01-01 10:30:00', '00:30:00', 80, 100),
(2, 'Math Quiz 1', '2024-01-01 10:00:00', '2024-01-01 10:30:00', '00:30:00', 60, 100);


-- questions table (connected to quizzes) SAMPLE
CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT,
    text TEXT,
    type VARCHAR(50),
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
);

-- choices table (connected to questions) SAMPLE
CREATE TABLE IF NOT EXISTS choices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT,
    text TEXT,
    is_correct BOOLEAN,
    FOREIGN KEY (question_id) REFERENCES questions(id)
);

-- user answers table (connected to questions and quizzes) SAMPLE
CREATE TABLE IF NOT EXISTS user_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    quiz_id INT,
    question_id INT,
    answer_id INT,
    is_correct BOOLEAN,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id),
    FOREIGN KEY (question_id) REFERENCES questions(id),
    FOREIGN KEY (answer_id) REFERENCES choices(id)
);

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

-- SAMPLE data for study companion, hardcoding only 
-- SAMPLE user
INSERT INTO users (username, email, password) 
VALUES ('student1', 'student1@example.com', 'password123');

-- SAMPLE quiz
-- GRADED QUIZ
INSERT INTO quizzes (user_id, title, started_at, finished_at, time_taken, marks, total_marks, points) -- points = score
VALUES 
(3, 'Sample Quiz 1', '2024-10-20 10:00:00', '2024-10-20 10:30:00', '00:30:00', 0, 20, 0);

-- SAMPLE QUESTIONS
INSERT INTO questions (quiz_id, text, type) VALUES
(3, 'What is the chemical symbol for water?', 'Multiple Choice'),
(3, 'Who painted the Mona Lisa?', 'Multiple Choice'),
(3, 'In which year did the Titanic sink?', 'Multiple Choice'),
(3, 'What is the main ingredient in guacamole?', 'Multiple Choice'),
(3, 'What is the speed of light?', 'Multiple Choice');

INSERT INTO choices (question_id, text, is_correct) VALUES
(1, 'H2O', TRUE),
(1, 'CO2', FALSE),
(1, 'O2', FALSE),
(1, 'NaCl', FALSE),

(2, 'Vincent Van Gogh', FALSE),
(2, 'Pablo Picasso', FALSE),
(2, 'Leonardo da Vinci', TRUE),
(2, 'Claude Monet', FALSE),

(3, '1905', FALSE),
(3, '1912', TRUE),
(3, '1920', FALSE),
(3, '1898', FALSE),

(4, 'Tomato', FALSE),
(4, 'Avocado', TRUE),
(4, 'Onion', FALSE),
(4, 'Pepper', FALSE),

(5, '300,000 km/s', TRUE),
(5, '150,000 km/s', FALSE),
(5, '450,000 km/s', FALSE),
(5, '750,000 km/s', FALSE);

-- SAMPLE user answers for Quiz 1
INSERT INTO user_answers (user_id, quiz_id, question_id, answer_id, is_correct) VALUES
(3, 3, 1, 4, FALSE),  -- NaCl (incorrect)
(3, 3, 2, 7, TRUE),   -- Leonardo da Vinci (correct)
(3, 3, 3, 11, FALSE), -- 1920 (incorrect)
(3, 3, 4, 14, TRUE),  -- Avocado (correct)
(3, 3,  5, 17, TRUE);  -- 300,000 km/s (correct)

-- study companion --
