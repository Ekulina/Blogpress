<?php
$createUsers = "

CREATE TABLE `d82652_kasutaja`.`users`(
    `id` SERIAL,
    `email` VARCHAR(100) NOT NULL,
    `password` VARCHAR(60) NOT NULL,
    `added` DATETIME NOT NULL,
    `added by` INT NOT NULL,
    `edited` DATETIME ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `edited by` INT NOT NULL
) ENGINE = InnoDB;
";

$createPosts = "
CREATE TABLE `d82652_kasutaja`.`posts`(
    `id` SERIAL NOT NULL,
    `post_title` VARCHAR(100) NOT NULL,
    `body` VARCHAR(1000) NOT NULL,
    `status` VARCHAR(20) NOT NULL ,
    `added` DATETIME NOT NULL,
    `added by` INT NOT NULL,
    `edited` DATETIME ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `edited by` INT NOT NULL
) ENGINE = InnoDB;
";

$createTranslations = "
CREATE TABLE `translations`(
    `id` SERIAL,
    `translation_name` VARCHAR(255) NOT NULL,
    `translation` TEXT NOT NULL,
    `language` VARCHAR(3) NOT NULL,
    `model` VARCHAR(50) NOT NULL,
    `model_id` INT NOT NULL
) ENGINE = INNODB;
";