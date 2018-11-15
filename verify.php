<?php
include("config/database.php");
header("Location: login.php");
try {
    $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
$db->query('USE `camagru`;');
$activate = $db->prepare("UPDATE `user` SET `confirmed` = 1 WHERE `mail` = '" . $_GET['mail']."' AND `password` = '".$_GET['password']."';");
$activate->execute();
?>
