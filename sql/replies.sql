CREATE TABLE replies(
    rid INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    parent_rid INT(11) NOT NULL,
    cid INT(11) NOT NULL,
    username VARCHAR(256) NOT NULL,
    content VARCHAR(512) NOT NULL,
    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
);

ALTER TABLE `replies` ADD CONSTRAINT r_cid  FOREIGN KEY(cid) REFERENCES posts(cid);

ALTER TABLE `replies` CHANGE COLUMN `username` `name` VARCHAR(256) NOT NULL;
