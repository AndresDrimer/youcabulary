CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    hash_password TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    remember_token VARCHAR(255) NULL,
    remember_token_expiry DATETIME,
    password_reset_token VARCHAR(255) NULL,
    password_reset_token_expiry DATETIME;
);

CREATE INDEX username_idx ON users (username);
CREATE INDEX email_idx ON users (email);

CREATE TABLE IF NOT EXISTS words (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    str VARCHAR(255) NOT NULL UNIQUE,
    definition TEXT NOT NULL,
    user_uuid VARCHAR(255) NOT NULL,
    FOREIGN KEY (user_uuid) REFERENCES users(uuid),  
    pronunciation VARCHAR(255) 
);