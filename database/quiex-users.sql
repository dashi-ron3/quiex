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

-- Quizzes table to store quiz metadata
CREATE TABLE IF NOT EXISTS quizzes (
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
CREATE TABLE IF NOT EXISTS questions (
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
CREATE TABLE IF NOT EXISTS options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    option_text VARCHAR(255) NOT NULL,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

-- Add subject column to questions table if it doesn't already exist
ALTER TABLE questions ADD COLUMN IF NOT EXISTS subject VARCHAR(50);

-- Set sample data for testing purposes
UPDATE questions SET subject = 'Biology' WHERE id = 1;
UPDATE questions SET subject = 'Chemistry' WHERE id = 2;

-- Attempts table to store user attempts on quizzes
CREATE TABLE IF NOT EXISTS attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    quiz_title VARCHAR(255),
    user_id INT NOT NULL,
    score INT NOT NULL,
    max_score INT NOT NULL,
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
    -- Assuming the users table exists, otherwise comment out the next line
    -- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Answers table to store the student's answers
CREATE TABLE IF NOT EXISTS answers (
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

-- Uploaded assessments table
CREATE TABLE IF NOT EXISTS uploadedAss (
    upAss INT AUTO_INCREMENT PRIMARY KEY,
    quizId INT NOT NULL,
    subject VARCHAR(255),
    title TEXT,
    status VARCHAR(255) DEFAULT 'Published',
    lastUsed DATE,
    descrip TEXT,
    shared TINYINT DEFAULT 0,
    FOREIGN KEY (quizId) REFERENCES quizzes(id)
);

-- Add a publish status column to questions table if it doesn't exist
ALTER TABLE questions ADD COLUMN IF NOT EXISTS publish_status TINYINT(1) DEFAULT 0;

-- Insert test data into uploadedAss table
INSERT INTO uploadedAss (quizId, subject, title, status, lastUsed, descrip) 
VALUES
(1, 'Science', 'Sample Science Quiz 1', 'In Progress', '2024-02-10', 'An examination of basic physics concepts.');

-- To display the questions at the teacher-assessment page
SELECT 
    q.id AS question_id, 
    q.question_text, 
    o.id AS option_id, 
    o.option_text
FROM 
    questions q
LEFT JOIN 
    options o ON q.id = o.question_id
WHERE 
    q.quiz_id = 1;

-- leaderboard
CREATE TABLE leaderboard (
    id INT AUTO_INCREMENT PRIMARY KEY,
	user_id INT NOT NULL,
    quiz_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    points INT NOT NULL,
    max_score INT NOT NULL,
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

-- Insert sample data into the questions table with subject
INSERT INTO Questions (id, assessment_id, question_text, question_type, correct_answer, points, explanation, subject) VALUES
(1, 1, 'What is the largest mammal on Earth?', 'multiple-choice', 'Blue Whale', 5, 'The Blue Whale is the largest mammal on Earth.', 'Biology'),
(2, 1, 'What is the formula for calculating the area of a circle?', 'multiple-choice', 'πr²', 5, 'The area of a circle is calculated using the formula πr², where r is the radius.', 'Math'),
(3, 1, 'What is the boiling point of water in Celsius?', 'multiple-choice', '100', 5, 'Water boils at 100 degrees Celsius at sea level.', 'Chemistry'),
(4, 1, 'What is the capital of Italy?', 'multiple-choice', 'Rome', 5, 'The capital of Italy is Rome.', 'General'),
(5, 1, 'Who is known as the father of modern physics?', 'multiple-choice', 'Albert Einstein', 5, 'Albert Einstein is known as the father of modern physics.', 'Science'),
(6, 1, 'What is the main function of the legislative branch of government?', 'multiple-choice', 'To make laws', 5, 'The legislative branch is responsible for making laws.', 'Ethics'),
(7, 1, 'What is the capital of France?', 'short-answer', 'Paris', 1, 'Correct! Paris is the capital of France.', 'Geography'),
(8, 1, 'What is the capital of Germany?', 'short-answer', 'Berlin', 1, 'Incorrect. The capital of Germany is Berlin.', 'Geography'),
(9, 1, 'What is the largest planet in our solar system?', 'multiple-choice', 'Jupiter', 5, 'Jupiter is the largest planet in our solar system.', 'Astronomy'),
(10, 1, 'What is the chemical symbol for water?', 'multiple-choice', 'H2O', 5, 'The chemical symbol for water is H2O.', 'Chemistry'),
(11, 1, 'Which gas do plants absorb from the atmosphere?', 'multiple-choice', 'Carbon Dioxide', 5, 'Plants absorb Carbon Dioxide during photosynthesis.', 'Biology'),
(12, 1, 'What is the powerhouse of the cell?', 'multiple-choice', 'Mitochondria', 5, 'Mitochondria are known as the powerhouse of the cell.', 'Biology'),
(13, 1, 'What is the capital of Japan?', 'multiple-choice', 'Tokyo', 5, 'Tokyo is the capital of Japan.', 'Geography'),
(14, 1, 'Who wrote "Romeo and Juliet"?', 'multiple-choice', 'William Shakespeare', 5, 'William Shakespeare wrote "Romeo and Juliet".', 'Literature');


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
SELECT * FROM leaderboard;
SELECT * FROM uploadedAss;
