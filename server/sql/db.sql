CREATE TABLE `users` (
    `username` VARCHAR(50) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(32) NOT NULL,
    `avatar` BLOB,
    `session` VARCHAR(255),
    `reset_token` VARCHAR(6),
    `reset_token_timestamp` INT,
    `enable` BOOLEAN,
    PRIMARY KEY (`username`, `email`)
) CREATE TABLE `posts` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(255) NOT NULL,
    `content` VARCHAR NOT NULL,
    `timestamp` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `views` NOT NULL DEFAULT '0',
    PRIMARY KEY (`username`, `email`)
) CREATE TABLE `comments` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `post_id` INT NOT NULL,
    `username` VARCHAR(255) NOT NULL,
    `content` VARCHAR NOT NULL,
    `timestamp` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `views` NOT NULL DEFAULT '0',
    PRIMARY KEY (`username`, `email`)
)