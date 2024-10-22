CREATE DATABASE quiex;

USE quiex;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    age INT, 
    gr_level VARCHAR(50),
    password VARCHAR(255) NOT NULL
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE assessments (
    upAss INT AUTO_INCREMENT PRIMARY KEY,
    --id INT,
    subject VARCHAR(255)
    title text,
    status VARCHAR(255),
    lastUsed DATE,
    descrip text,
    shared TINYINT(1) DEFAULT (0)
    --FOREIGN KEY (quizID) REFERENCES Quiz Table_name(quizID)
);

--FOR TESTING ASSESSMENTS
-- INSERT INTO assessments (subject, title, status, lastUsed, descrip) VALUES
-- ('Science', 'Sample Science Quiz', 'In Progress', '2024-02-10', 'An examination of basic physics concepts.'),
-- ('History', 'Sample History Quiz', 'Not Started', '2024-03-01', 'An assignment about World War II.'),
-- ("Biology","Sample Biology Exam", "Done", "2023-11-09", "Sample Test Description.");

CREATE TABLE lb (
    --quizID INT AUTO_INCREMENT PRIMARY KEY,
    id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    profile_pic VARCHAR(255) NOT NULL,
    points INT NOT NULL
    --FOREIGN KEY (quizID) REFERENCES Quiz Table_name(quizID),
    --FOREIGN KEY (id) REFERENCES users(id)
);

-- FOR TESTING OF LEADERBOARD
-- INSERT INTO lb (name, profile_pic, points) VALUES 
-- ("Student1", "desktop_wp.jpg", "1000"),

-- Quiz info for study companion
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

SELECT * from users;
