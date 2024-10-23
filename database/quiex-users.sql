CREATE DATABASE quiex;

USE quiex;

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

CREATE TABLE IF NOT EXISTS assessments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    content TEXT,
    status ENUM('draft', 'published') DEFAULT 'draft',
    unique_code VARCHAR(10) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE assessments ADD COLUMN time_limit INT(11) DEFAULT 0;

CREATE TABLE uploadAss (
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
-- INSERT INTO assessments (subject, title, status, lastUsed, descrip) VALUES
-- ('Science', 'Sample Science Quiz', 'In Progress', '2024-02-10', 'An examination of basic physics concepts.'),
-- ('History', 'Sample History Quiz', 'Not Started', '2024-03-01', 'An assignment about World War II.'),
-- ("Biology","Sample Biology Exam", "Done", "2023-11-09", "Sample Test Description.");

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

-- quizzes table (connected to users)
CREATE TABLE IF NOT EXISTS quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255),
    started_at DATETIME,
    finished_at DATETIME,
    time_taken TIME,
    marks INT,
    total_marks INT,
    points INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- questions table (connected to quizzes)
CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT,
    text TEXT,
    type VARCHAR(50),
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
);

-- choices table (connected to questions)
CREATE TABLE IF NOT EXISTS choices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT,
    text TEXT,
    is_correct BOOLEAN,
    FOREIGN KEY (question_id) REFERENCES questions(id)
);

-- user answers table (connected to questions and quizzes)
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

-- sample data for study companion, hardcoding only
-- sample user
INSERT INTO users (username, email, password) 
VALUES ('student1', 'student1@example.com', 'password123');

-- sample quiz
INSERT INTO quizzes (user_id, title, started_at, finished_at, time_taken, marks, total_marks, points) 
VALUES 
(1, 'Sample Quiz 1', '2024-10-20 10:00:00', '2024-10-20 10:30:00', '00:30:00', 0, 20, 0);

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
(1, 'Who wrote ''Pride and Prejudice''?', 'Multiple Choice'),
(1, 'What is the smallest continent?', 'Multiple Choice'),
(1, 'What is the hardest natural substance on Earth?', 'Multiple Choice'),
(1, 'Who is known as the Father of Geometry?', 'Multiple Choice'),
(1, 'What is the largest ocean on Earth?', 'Multiple Choice'),
(1, 'Which element is a diamond made of?', 'Multiple Choice'),
(1, 'What is the main language spoken in Egypt?', 'Multiple Choice'),
(1, 'Who discovered penicillin?', 'Multiple Choice'),
(1, 'What is the term for an animal that only eats plants?', 'Multiple Choice'),
(1, 'What is the capital of Canada?', 'Multiple Choice');

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

(11, 'Jane Austen', TRUE),
(11, 'Emily Brontë', FALSE),
(11, 'Charles Dickens', FALSE),
(11, 'Mark Twain', FALSE),

(12, 'Australia', TRUE),
(12, 'Europe', FALSE),
(12, 'Antarctica', FALSE),
(12, 'South America', FALSE),

(13, 'Diamond', TRUE),
(13, 'Gold', FALSE),
(13, 'Iron', FALSE),
(13, 'Sapphire', FALSE),

(14, 'Euclid', TRUE),
(14, 'Pythagoras', FALSE),
(14, 'Archimedes', FALSE),
(14, 'Galileo', FALSE),

(15, 'Atlantic Ocean', FALSE),
(15, 'Indian Ocean', FALSE),
(15, 'Arctic Ocean', FALSE),
(15, 'Pacific Ocean', TRUE),

(16, 'Carbon', TRUE),
(16, 'Oxygen', FALSE),
(16, 'Nitrogen', FALSE),
(16, 'Silicon', FALSE),

(17, 'Arabic', TRUE),
(17, 'English', FALSE),
(17, 'French', FALSE),
(17, 'Spanish', FALSE),

(18, 'Marie Curie', FALSE),
(18, 'Alexander Fleming', TRUE),
(18, 'Louis Pasteur', FALSE),
(18, 'Isaac Newton', FALSE),

(19, 'Carnivore', FALSE),
(19, 'Omnivore', FALSE),
(19, 'Herbivore', TRUE),
(19, 'Insectivore', FALSE),

(20, 'Toronto', FALSE),
(20, 'Ottawa', TRUE),
(20, 'Vancouver', FALSE),
(20, 'Montreal', FALSE);
-- study companion --

SELECT * from users;
