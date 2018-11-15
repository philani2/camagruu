<?php
    include("config/database.php");
    header("Location: index.php");
    $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);

?>
