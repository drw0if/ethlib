CREATE DATABASE ethib;
USE ethib;

-- -------
-- User --
-- -------
CREATE TABLE `User`(
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
CREATE TABLE `Book`(
    `book_id` INT NOT NULL AUTO_INCREMENT,
    `isbn` VARCHAR(13) DEFAULT NULL,
    `local_name` VARCHAR(64) NOT NULL,
    `file_type` VARCHAR(5) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `mark_sum` FLOAT NOT NULL DEFAULT 0,
    `mark_count` INT NOT NULL DEFAULT 0,
    `private` BOOLEAN NOT NULL DEFAULT 0,

    `user_id` INT NOT NULL,

    PRIMARY KEY (`book_id`),
    FOREIGN KEY (`user_id`) REFERENCES `User`(`user_id`)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------
-- Mark --
-- -------
CREATE TABLE `Mark`(
    `mark_id` INT NOT NULL AUTO_INCREMENT,
    `value` FLOAT NOT NULL CHECK (`value` >= 0 AND `value` <= 5),

    `user_id` INT NOT NULL,
    `book_id` INT NOT NULL,

    PRIMARY KEY (`mark_id`),
    FOREIGN KEY (`user_id`) REFERENCES `User`(`user_id`),
    FOREIGN KEY (`book_id`) REFERENCES `Book`(`book_id`)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ---------
-- Review --
-- ---------
CREATE TABLE `Review`(
    `review_id` INT NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(100) NOT NULL,
    `content` TEXT NOT NULL,

    `user_id` INT NOT NULL,
    `book_id` INT NOT NULL,

    PRIMARY KEY(`review_id`),
    FOREIGN KEY (`user_id`) REFERENCES `User`(`user_id`),
    FOREIGN KEY (`book_id`) REFERENCES `Book`(`book_id`)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ---------
-- Author --
-- ---------
CREATE TABLE `Author`(
    `author_id` INT NOT NULL AUTO_INCREMENT,
    `name_surname` VARCHAR(200) NOT NULL,

    PRIMARY KEY (`author_id`)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------
-- Author_Book --
-- --------------`
CREATE TABLE `Author_Book`(
    `author_id` INT NOT NULL,
    `book_id` INT NOT NULL,

    FOREIGN KEY (`author_id`) REFERENCES `Author`(`author_id`),
    FOREIGN KEY (`book_id`) REFERENCES `Book`(`book_id`)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------
-- Genre --
-- --------
CREATE TABLE `Genre`(
    `genre_id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(200) NOT NULL,

    PRIMARY KEY(`genre_id`)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- -------------
-- Genre_Book --
-- -------------
CREATE TABLE `Genre_Book`(
    `genre_id` INT NOT NULL,
    `book_id` INT NOT NULL,

    FOREIGN KEY (`genre_id`) REFERENCES `Genre`(`genre_id`),
    FOREIGN KEY (`book_id`) REFERENCES `Book`(`book_id`)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- username: admin
-- password: admin
INSERT INTO `User`
    VALUES (DEFAULT, 'admin', 'admin@ethib.com', '$2y$10$HWqQBNpNtYo1SRUfBdAprOlvDZHDIvaLpe4QxU4APAJWixcSCL/CW', 1);


