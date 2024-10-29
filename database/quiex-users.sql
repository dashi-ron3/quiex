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

-- create assessment
CREATE TABLE IF NOT EXISTS assessments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    content TEXT,
    status ENUM('draft', 'published') DEFAULT 'draft',
    unique_code VARCHAR(10) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE assessments ADD COLUMN time_limit INT(11) DEFAULT 0;

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
    FOREIGN KEY (question_id) REFERENCES quiex_questions(id) ON DELETE CASCADE
);

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
    shared TINYINT(1) DEFAULT (0),
    FOREIGN KEY (quizId) REFERENCES assessments(id)
);

-- FOR TESTING ASSESSMENTS
-- INSERT INTO uploadedAss (subject, title, status, lastUsed, descrip) VALUES
-- ('Science', 'Sample Science Quiz', 'In Progress', '2024-02-10', 'An examination of basic physics concepts.'),
-- ('History', 'Sample History Quiz', 'Not Started', '2024-03-01', 'An assignment about World War II.'),
-- ("Biology","Sample Biology Exam", "Done", "2023-11-09", "Sample Test Description.");

-- leaderboard
CREATE TABLE leaderboard (
   --  --quizID INT AUTO_INCREMENT PRIMARY KEY,
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
    is_graded BOOLEAN DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

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
    quiz_id INT,
    question_id INT,
    answer_id INT,
    is_correct BOOLEAN,
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
    incorrectQuestions VARCHAR(255)
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
INSERT INTO quizzes (user_id, title, started_at, finished_at, time_taken, marks, total_marks, points, is_graded) -- points = score
VALUES 
(1, 'Sample Quiz 1', '2024-10-20 10:00:00', '2024-10-20 10:30:00', '00:30:00', 0, 20, 0, 1);

-- PACTICE ASSESSMENT, NOT GRADED
INSERT INTO quizzes (user_id, title, started_at, finished_at, time_taken, marks, total_marks, points, is_graded) -- points = score
VALUES 
(1, 'Sample Quiz 2', '2024-10-21 11:00:00', '2024-10-21 11:30:00', '00:30:00', 0, 20, 0, 0);

-- SAMPLE QUESTIONS
INSERT INTO questions (quiz_id, text, type) VALUES
(1, 'What is the chemical symbol for water?', 'Multiple Choice'),
(1, 'Who painted the Mona Lisa?', 'Multiple Choice'),
(1, 'In which year did the Titanic sink?', 'Multiple Choice'),
(1, 'What is the main ingredient in guacamole?', 'Multiple Choice'),
(1, 'What is the speed of light?', 'Multiple Choice'),
(1, 'Which gas do plants absorb from the atmosphere?', 'Multiple Choice'),
(1, 'Who was the first President of the United States?', 'Multiple Choice'),
(1, 'What is the largest planet in our solar system?', 'Multiple Choice'),
(1, 'What is the currency of Japan?', 'Multiple Choice'),
(1, 'What is the powerhouse of the cell?', 'Multiple Choice'),
(2, 'What is the chemical formula for table salt?', 'Multiple Choice'),
(2, 'What planet is known as the Red Planet?', 'Multiple Choice'),
(2, 'What is the capital city of France?', 'Multiple Choice'),
(2, 'What is the process by which plants make food?', 'Multiple Choice'),
(2, 'Who wrote "Hamlet"?', 'Multiple Choice');

SELECT * FROM choices;
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
(5, '750,000 km/s', FALSE),

(6, 'Oxygen', FALSE),
(6, 'Nitrogen', FALSE),
(6, 'Carbon Dioxide', TRUE),
(6, 'Hydrogen', FALSE),

(7, 'George Washington', TRUE),
(7, 'Thomas Jefferson', FALSE),
(7, 'Abraham Lincoln', FALSE),
(7, 'John Adams', FALSE),

(8, 'Earth', FALSE),
(8, 'Jupiter', TRUE),
(8, 'Saturn', FALSE),
(8, 'Neptune', FALSE),

(9, 'Yen', TRUE),
(9, 'Won', FALSE),
(9, 'Dollar', FALSE),
(9, 'Peso', FALSE),

(10, 'Nucleus', FALSE),
(10, 'Mitochondria', TRUE),
(10, 'Ribosome', FALSE),
(10, 'Endoplasmic Reticulum', FALSE),

(11, 'NaCl', TRUE),
(11, 'H2O', FALSE),
(11, 'CO2', FALSE),
(11, 'O2', FALSE),

(12, 'Earth', FALSE),
(12, 'Venus', FALSE),
(12, 'Mars', TRUE),
(12, 'Jupiter', FALSE),

(13, 'London', FALSE),
(13, 'Berlin', FALSE),
(13, 'Paris', TRUE),
(13, 'Rome', FALSE),

(14, 'Respiration', FALSE),
(14, 'Digestion', FALSE),
(14, 'Photosynthesis', TRUE),
(14, 'Fermentation', FALSE),

(15, 'William Shakespeare', TRUE),
(15, 'Charles Dickens', FALSE),
(15, 'Mark Twain', FALSE),
(15, 'Homer', FALSE);

-- SAMPLE user answers for Quiz 1
INSERT INTO user_answers (quiz_id, question_id, answer_id, is_correct) VALUES
(1, 1, 4, FALSE),  -- H2O (incorrect)
(1, 2, 3, TRUE),   -- Leonardo da Vinci (correct)
(1, 3, 2, FALSE),  -- 1912 (incorrect)
(1, 4, 2, TRUE),   -- Avocado (correct)
(1, 5, 1, TRUE),   -- 300,000 km/s (correct)
(1, 6, 3, FALSE),  -- Carbon Dioxide (incorrect)
(1, 7, 1, TRUE),   -- George Washington (correct)
(1, 8, 2, FALSE),  -- Jupiter (incorrect)
(1, 9, 1, TRUE),   -- Yen (correct)
(1, 10, 2, TRUE);  -- Mitochondria (correct)

-- SAMPLE user answers for Quiz 2
INSERT INTO user_answers (quiz_id, question_id, answer_id, is_correct) VALUES
(2, 1, 1, TRUE),   -- NaCl (correct)
(2, 2, 2, FALSE),  -- Mars (incorrect)
(2, 3, 3, TRUE),   -- Paris (correct)
(2, 4, 1, FALSE),  -- Photosynthesis (incorrect)
(2, 5, 2, FALSE);  -- William Shakespeare (incorrect)

-- study companion --
