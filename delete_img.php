<?php
    session_start();
    include("config/database.php");

    try {
        $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
    $db->query('USE `camagru`;');
    $select_img = $db->prepare('SELECT * FROM `img` WHERE `img`.`id` = :id;');
    $delete_pics = $db->prepare('DELETE FROM `img` WHERE `img`.`id` = :id');
    $select_img->execute(array(":id" => $_GET['id']));
    $result = $select_img->fetch(PDO::FETCH_ASSOC);
    if ($result['user'] == $_SESSION['log_in']) {
        $delete_pics->execute(array(":id" => $_GET['id']));
        echo "OK";
    } else { echo "ERROR"; }
?>
