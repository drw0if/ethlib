CREATE DATABASE ethlib;
USE ethlib;

-- -------
-- User --
-- -------
CREATE TABLE `user`(
    `user_id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `hash` VARCHAR(255) NOT NULL,
    `user_type` BOOLEAN NOT NULL DEFAULT 0,

    PRIMARY KEY (`user_id`)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------
-- Book --
-- -------
CREATE TABLE `book`(
    `book_id` INT NOT NULL AUTO_INCREMENT,
    `isbn` VARCHAR(13) DEFAULT NULL,
    `local_name` VARCHAR(64) NOT NULL,
    `file_type` VARCHAR(5) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `rating_sum` FLOAT NOT NULL DEFAULT 0,
    `rating_count` INT NOT NULL DEFAULT 0,
    `private` BOOLEAN NOT NULL DEFAULT 0,

    `user_id` INT NOT NULL,

    PRIMARY KEY (`book_id`),
    FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ---------
-- Review --
-- ---------
CREATE TABLE `review`(
    `review_id` INT NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(100) NOT NULL,
    `content` TEXT NOT NULL,
    `rating` FLOAT NOT NULL CHECK (`rating` >= 0 AND `rating` <= 5),

    `user_id` INT NOT NULL,
    `book_id` INT NOT NULL,

    PRIMARY KEY(`review_id`),
    FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE,
    FOREIGN KEY (`book_id`) REFERENCES `book`(`book_id`) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- username: admin
-- password: admin
INSERT INTO `user`
    VALUES (DEFAULT, 'admin', 'admin@ethlib.com', '$2y$10$HWqQBNpNtYo1SRUfBdAprOlvDZHDIvaLpe4QxU4APAJWixcSCL/CW', 1);


