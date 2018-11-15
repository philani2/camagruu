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
    $change_username = $db->prepare('UPDATE `user` SET `username` = :newusr WHERE `user`.`username` = :oldusr;');
    $change_password = $db->prepare('UPDATE `user` SET `password` = :newpsw WHERE `user`.`username` = :oldusr;');
    $change_usr_of_pics = $db->prepare('UPDATE `img` SET `user` = :newusr WHERE `img`.`user` = :oldusr;');

    $accounts = $db->prepare('SELECT `username` FROM `user` WHERE `username` = :user;');
    $accounts->execute(array(':user' => $_POST['username']));
    $res = $accounts->fetch(PDO::FETCH_ASSOC);
    if ($res['username'] != "") {
        header("Loction: index.php");
    }

    else if ($_SESSION['log_in'] === "")
    {
        header("Location: index.php");
    }

    else {
        if ($_POST['sub_new_usr'] === 'Change' && $_POST['username'] !== ""
            && preg_match("/^([A-Za-z0-9]){4,15}$/", $_POST['username'])) {
            $change_username->execute(array(':newusr' => $_POST['username'],
                                            ':oldusr' => $_SESSION['log_in']));
            $change_usr_of_pics->execute(array(':newusr' => $_POST['username'],
                                               ':oldusr' => $_SESSION['log_in']));
            $_SESSION['log_in'] = $_POST['username'];
            header("Location: index.php?success=1");
        }

        if ($_POST['sub_new_psw'] === 'Change' && $_POST['password'] !== ""
            && $_POST['password'] === $_POST['vpassword']
                && preg_match("/^([A-Za-z0-9]){4,15}$/", $_POST['password'])) {
            $change_password->execute(array(':newpsw' => hash("whirlpool", $_POST['password']),
                                            ':oldusr' => $_SESSION['log_in']));
            header("Location: index.php?success=2");
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Manage Account / Camagru</title>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <header>
    <div class="header">
        <h1><a href="index.php">Camagru</a></h1>
        <a id="gal" href="gallery.php" style="margin-right: 15px;;">Gallery</a>
        <a href='logout.php'>Logout</a>
    </div>
    </header>
    <body>
        <div class="container manage">
            <div class="block">
                <form class="" action="manage_account.php" method="post">
                    <label for="username">Change username: </label><input type="text" name="username" value="">
                    <input type="submit" name="sub_new_usr" value="Change">
                </form>
            </div>
            <div class="block">
                <form class="" action="manage_account.php" method="post">
                    <label for="password">Change password: </label><input type="password" name="password" value="" />
                    <label for="vpassword">Verify password: </label><input type="password" name="vpassword" value="">
                    <input type="submit" name="sub_new_psw" value="Change">
                </form>
            </div>
            <div style="text-align: right" class="block">
                <a class="myButton" onclick='sure()'>DELETE ACCOUNT</a>
            </div>
        </div>
    </body>
    <script type="text/javascript">
        function sure(){
            if (window.confirm("Are you sure that you want to delete your account ? Every pictures will be erased !") === true) {
                window.location.replace("delete_account.php");
        }
        }
    </script>
</html>
