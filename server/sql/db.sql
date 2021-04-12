CREATE TABLE `users` (
    `username` VARCHAR(50) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(32) NOT NULL,
    `avatar` BLOB,
    `session` VARCHAR(255),
    `reset_token` VARCHAR(6),
    `reset_token_timestamp` DATETIME,
    `enable` BOOLEAN NOT NULL DEFAULT TRUE,
    PRIMARY KEY (`username`, `email`)
);
CREATE TABLE `posts` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(255) NOT NULL,
    `content` TEXT NOT NULL,
    `timestamp` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `views` INT NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    FOREIGN KEY (`username`) REFERENCES `users`(`username`)
);
CREATE TABLE `comments` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `post_id` INT NOT NULL,
    `username` VARCHAR(255) NOT NULL,
    `content` TEXT NOT NULL,
    `timestamp` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `views` INT NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`),
    FOREIGN KEY (`username`) REFERENCES `users`(`username`)
);

INSERT INTO users (username, email, password) VALUES ('test', 'test', 'test')