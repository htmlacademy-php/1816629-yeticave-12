DROP DATABASE IF EXISTS yeticave;
CREATE DATABASE yeticave
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE categories (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            name VARCHAR(128) UNIQUE NOT NULL,
                            code VARCHAR(128) UNIQUE NOT NULL
);

CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       date_registration DATETIME DEFAULT CURRENT_TIMESTAMP,
                       email VARCHAR(128) UNIQUE NOT NULL,
                       name VARCHAR(128) NOT NULL,
                       password CHAR(128) NOT NULL,
                       contacts VARCHAR(128)
);

CREATE TABLE ads (
                     id INT AUTO_INCREMENT PRIMARY KEY,
                     name VARCHAR(128) NOT NULL UNIQUE,
                     description TEXT,
                     img TEXT,
                     start_price INT NOT NULL,
                     date_create DATETIME DEFAULT CURRENT_TIMESTAMP,
                     date_end DATE NOT NULL,
                     step_bet INT NOT NULL,
                     category_id INT NOT NULL,
                     winner_id INT,
                     user_id INT NOT NULL,
                     FOREIGN KEY (user_id) REFERENCES users(id),
                     FOREIGN KEY (winner_id) REFERENCES users(id),
                     FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE bets (
                      id INT AUTO_INCREMENT PRIMARY KEY,
                      date DATETIME DEFAULT CURRENT_TIMESTAMP,
                      price INT NOT NULL,
                      user_id INT NOT NULL,
                      ad_id INT NOT NULL,
                      FOREIGN KEY (user_id) REFERENCES users(id),
                      FOREIGN KEY (ad_id) REFERENCES ads(id)
);
