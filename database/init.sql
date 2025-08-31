-- Database Setup --
--------------------
CREATE USER IF NOT EXISTS '$DB_USER'@'$DB_HOST' IDENTIFIED BY '$DB_PASS';
CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'$DB_HOST';
FLUSH PRIVILEGES;


-- Schema Setup --
------------------
USE $DB_NAME;

CREATE TABLE IF NOT EXISTS users(
    uid        BIGINT PRIMARY KEY AUTO_INCREMENT,
    username   VARCHAR(32) UNIQUE NOT NULL,
    email      VARCHAR(255) UNIQUE NOT NULL,
    password   VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    first_name VARCHAR(32) DEFAULT NULL,
    last_name  VARCHAR(32) DEFAULT NULL,
    birth_date DATE DEFAULT NULL,

    INDEX username_index (username),
    INDEX email_index (email)
);


-- Verification --
------------------
SELECT user, host FROM mysql.user WHERE user = '$DB_USER';
SHOW GRANTS FOR '$DB_USER'@'$DB_HOST';
SELECT DATABASE();
SHOW TABLES;
