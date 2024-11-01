CREATE DATABASE quiez;

USE quiez;

CREATE TABLE Assessments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(255) UNIQUE NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT
);

CREATE TABLE Questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    assessment_id INT,
    type ENUM('multiple_choice', 'true_false', 'short_answer','long_answer') NOT NULL,
    question_text TEXT NOT NULL,
    correct_answer TEXT NOT NULL,
    FOREIGN KEY (assessment_id) REFERENCES Assessments(id)
);

CREATE TABLE Options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT,
    option_text TEXT NOT NULL,
    is_correct BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (question_id) REFERENCES Questions(id)
);

-- Insert questions to assessment ID 28
INSERT INTO Questions (assessment_id, type, question_text, correct_answer) VALUES
(28, 'multiple_choice', 'What is the capital of France?', 'Paris'),
(28, 'true_false', 'The Earth is flat.', 'False'),
(28, 'short_answer', 'What is the largest mammal?', 'Blue Whale'),
(28, 'long_answer', 'Explain the theory of relativity.', 'The theory of relativity explains how space and time are linked for objects that are moving at a constant speed in a straight line.'),
(28, 'multiple_choice', 'Which planet is known as the Red Planet?', 'Mars'),
(28, 'true_false', 'Water freezes at 0 degrees Celsius.', 'True'),
(28, 'short_answer', 'What is the chemical symbol for Gold?', 'Au'),
(28, 'long_answer', 'Describe the process of photosynthesis.', 'Photosynthesis is the process by which green plants and some other organisms use sunlight to synthesize foods with the help of chlorophyll from carbon dioxide and water.');


SELECT id FROM Questions WHERE assessment_id = 28;

INSERT INTO Options (question_id, option_text, is_correct) VALUES
(1, 'Berlin', FALSE),
(1, 'Madrid', FALSE),
(1, 'Paris', TRUE),
(1, 'Rome', FALSE),
(5, 'Earth', FALSE),
(5, 'Mars', TRUE),
(5, 'Venus', FALSE),
(5, 'Jupiter', FALSE);

SELECT * FROM Assessments;
