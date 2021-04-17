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
    `username` VARCHAR(50) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `content` TEXT NOT NULL,
    `timestamp` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `views` INT NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    FOREIGN KEY (`username`) REFERENCES `users`(`username`)
);
CREATE TABLE `comments` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `post_id` INT NOT NULL,
    `username` VARCHAR(50) NOT NULL,
    `content` TEXT NOT NULL,
    `timestamp` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `views` INT NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`),
    FOREIGN KEY (`username`) REFERENCES `users`(`username`)
);
CREATE TABLE `admins` (
    `username` VARCHAR(50) NOT NULL,
    FOREIGN KEY (`username`) REFERENCES `users`(`username`)
);

/*We need to hardcode a few users for testing purposes--they could be admins later as well*/
INSERT INTO users (username, email, password, enable) VALUES ("parker616", "peter@dailybugle.com", "notspidey", 1);
INSERT INTO users (username, email, password, enable) VALUES ("dvader", "clanker@sithlord.com", "snips", 1);
/*We need to hardcode posts in now that we have users to write them*/
INSERT INTO posts (username,title,content,views) VALUES ("parker616","Title 1", "This post is about my favourite place to get pizza, Joe's Pizza!", 5);
INSERT INTO posts (username,title,content) VALUES ("parker616", "Title 2","There's nothing better than New York pizza!");
INSERT INTO posts (username,title,content,views) VALUES ("parker616", "Title 3","Does anyone know how to hack Stark indusrtries' firewall?", 8);
INSERT INTO posts (username,title,content) VALUES ("parker616", "Title 4","You should rent a bike in central park!");
INSERT INTO posts (username,title,content) VALUES ("dvader", "Title 5","The First Death Star had the best pizza place in the whole galaxy. Rest in peace.", 2);
INSERT INTO posts (username,title,content,views) VALUES ("dvader", "Title 6","Wow I love speederbikes.");
INSERT INTO posts (username,title,content) VALUES ("dvader", "Title 7","I'm having issues with a faulty protocol droid's memory banks. Can't read the programming language it's written in.");
INSERT INTO posts (username,title,content) VALUES ("dvader", "Title 8","I need a 'hand' coding my prothstetic arm.");
/*Comments*/
INSERT INTO comments (post_id, username, content) VALUES (1, "dvader", "This is a comment");
INSERT INTO comments (post_id, username, content) VALUES (1, "dvader", "This is a comment too");
INSERT INTO comments (post_id, username, content) VALUES (1, "parker616", "This also is a comment");
INSERT INTO comments (post_id, username, content) VALUES (2, "dvader", "This is a comment");
INSERT INTO comments (post_id, username, content) VALUES (2, "parker616", "This is a comment too");
INSERT INTO comments (post_id, username, content) VALUES (2, "parker616", "This also is a comment");
/*Admin*/
INSERT INTO admins (username) VALUES ("dvader");
