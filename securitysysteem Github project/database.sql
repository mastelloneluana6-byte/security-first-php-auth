CREATE DATABASE IF NOT EXISTS secure_auth CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE secure_auth;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS failed_login_attempts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    attempted_at DATETIME NOT NULL,
    blocked_until DATETIME NULL,
    INDEX idx_ip_attempted (ip_address, attempted_at),
    INDEX idx_ip_blocked (ip_address, blocked_until)
) ENGINE=InnoDB;
