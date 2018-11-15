<?php
include("config/database.php");
session_start();

try {
    $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
$db->query('USE `camagru`;');
$select_users = $db->prepare('SELECT * FROM `user` WHERE `username` = :username');

    $select_users->execute(array('username' => $_POST['username']));
    $result = $select_users->fetch(PDO::FETCH_ASSOC);
    if ($_POST['username'] == $result['username'] && hash("whirlpool", $_POST['password']) == $result['password'])
    {
        if ($result['confirmed'] != 0){
            $_SESSION['log_in'] = $result['username'];
            echo "OK";
        }
        else {
            echo "You must validate your account using the link in the mail that have been sent to you.<br />";
        }
    }
    else {
        echo "Invalid username or password<br /><a href='restpwd.php'>Password lost ? Click on this link</a><br />";
    }
 ?>
