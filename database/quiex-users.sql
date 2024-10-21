CREATE DATABASE quiex;

USE quiex;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE assessments (
-- subject VARCHAR(255)
    title text,
    status VARCHAR(255),
    lastUsed DATE,
    descrip text
);

CREATE TABLE lb (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    profile_pic VARCHAR(255) NOT NULL,
    points INT NOT NULL
);

-- FOR TESTING OF LEADERBOARD
-- INSERT INTO lb (name, profile_pic, points) VALUES 
-- ("Student1", "desktop_wp.jpg", "1000"),

SELECT * from users;