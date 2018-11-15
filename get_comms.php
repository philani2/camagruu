<?php
    include("config/database.php");

    try {
        $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
    $db->query('USE `camagru`;');
    $select_coms = $db->prepare('SELECT * FROM `img` WHERE `img`.`id` = :id');
    if ($_GET['id'] !== "") {
        $select_coms->execute(array(':id' => $_GET['id']));
        $result = $select_coms->fetch(PDO::FETCH_ASSOC);
        if ($result['comment'] == "") echo "There are no comments";
        else {
            $coms = unserialize($result['comment']);
            $tab = array();
            foreach($coms as $c) {
                $tab[] = array("user" => $c['user'], "commentaire" => $c['commentaire']);
            }
            echo json_encode($tab);
        }
    }
?>
