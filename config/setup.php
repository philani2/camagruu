<?php
    include("database.php");


    try {
        $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
    $db->query('CREATE DATABASE IF NOT EXISTS `camagru`;');
    $db->query('USE `camagru`;');
    $create_user = $db->prepare('CREATE TABLE `user` (
    	`id` int AUTO_INCREMENT NOT NULL,
    	`username` varchar(256) NOT NULL,
    	`password` varchar(256) NOT NULL,
    	`mail` varchar(256) NOT NULL,
        `confirmed` int,
    	PRIMARY KEY (`id`)
    );');
    $create_img = $db->prepare('CREATE TABLE `img` (
        `id` int AUTO_INCREMENT NOT NULL,
        `picture` LONGTEXT NOT NULL,
        `vote` int,
        `user_vote` MEDIUMTEXT,
        `user` varchar(256) NOT NULL,
        `comment` MEDIUMTEXT,
        `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    );');
    $create_user->execute();
    $create_img->execute();
    echo "SETUP COMPLETE";
?>
