CREATE DATABASE quiex;

USE quiex;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE assessments (
    --upQuiz INT AUTO_INCREMENT PRIMARY KEY,
    --id INT,
    -- subject VARCHAR(255)
    title text,
    status VARCHAR(255),
    lastUsed DATE,
    descrip text
    --FOREIGN KEY (quizID) REFERENCES Quiz Table_name(quizID)
);

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
