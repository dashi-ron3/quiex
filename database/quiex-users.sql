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

SELECT * from users;