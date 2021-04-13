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
CREATE TABLE `posts` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(255) NOT NULL,
    `content` VARCHAR NOT NULL,
    `timestamp` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `views` NOT NULL DEFAULT '0',
    PRIMARY KEY (`username`, `email`)
) 
CREATE TABLE `comments` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `post_id` INT NOT NULL,
    `username` VARCHAR(255) NOT NULL,
    `content` VARCHAR NOT NULL,
    `timestamp` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `views` NOT NULL DEFAULT '0',
    PRIMARY KEY (`username`, `email`)
)

/*We need to hardcode a few users for testing purposes--they could be admins later as well*/
INSERT INTO users ('username','email','password','enable') VALUES ("parker616", "peter@dailybugle.com", "notspidey", 'true');
INSERT INTO users ('username','email','password','enable') VALUES ("dvader", "clanker@sithlord.com", "snips", 'true');
/*We need to hardcode posts in now that we have users to write them*/
INSERT INTO posts ('id','username','content','timestamp','views') VALUES (0, "parker616", "This post is about my favourite place to get pizza, Joe's Pizza!", 2021-01-03, 0) WHERE 'username' = 'parker616';
INSERT INTO posts ('id','username','content','timestamp','views') VALUES (1, "parker616", "There's nothing better than New York pizza!", 2020-02-14, 0) WHERE 'username' = 'parker616';
INSERT INTO posts ('id','username','content','timestamp','views') VALUES (2, "parker616", "Does anyone know how to hack Stark indusrtries' firewall?", 2016-06-03, 0) WHERE 'username' = 'parker616';
INSERT INTO posts ('id','username','content','timestamp','views') VALUES (3, "parker616", "You should rent a bike in central park!", 2019-08-14, 0) WHERE 'username' = 'parker616';
INSERT INTO posts ('id','username','content','timestamp','views') VALUES (4, "dvader", "The First Death Star had the best pizza place in the whole galaxy. Rest in peace.", 1977-12-26, 0) WHERE 'username' = 'dvader';
INSERT INTO posts ('id','username','content','timestamp','views') VALUES (5, "dvader", "Wow I love speederbikes.", 1983-01-07, 0) WHERE 'username' = 'dvader';
INSERT INTO posts ('id','username','content','timestamp','views') VALUES (6, "dvader", "I'm having issues with a faulty protocol droid's memory banks. Can't read the programming language it's written in.", 1999-03-19, 0) WHERE 'username' = 'dvader';
INSERT INTO posts ('id','username','content','timestamp','views') VALUES (7, "dvader", "I need a 'hand' coding my prothstetic arm.", 2002-03-16, 0) WHERE 'username' = 'dvader';
