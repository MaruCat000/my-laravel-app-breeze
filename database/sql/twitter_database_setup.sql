
-- Create the twitter database
CREATE DATABASE twitter;

-- Use the twitter database
USE twitter;

-- Create the users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Create the tweets table
CREATE TABLE tweets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    tweet TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create the replys table
CREATE TABLE replys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tweet_id INT,
    user_id INT,
    reply TEXT,
    FOREIGN KEY (tweet_id) REFERENCES tweets(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
