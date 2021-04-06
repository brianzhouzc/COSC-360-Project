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
) 