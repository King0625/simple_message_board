CREATE TABLE posts(
    cid INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(256) NOT NULL,
    topic VARCHAR(256) NOT NULL,
    content VARCHAR(512) NOT NULL,
    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE posts MODIFY COLUMN date TIMESTAMP;

ALTER TABLE `posts` CHANGE COLUMN `username` `name` VARCHAR(256) NOT NULL;