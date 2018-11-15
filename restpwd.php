<?php
    include("config/database.php");

    function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    if ($_POST['reset'] === "Reset" && $_POST['mail'] != "") {
        $newpwd = generateRandomString(10);
        try {
            $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
        $db->query('USE `camagru`;');
        $change_password = $db->prepare('UPDATE `user` SET `password` = :newpwd WHERE `user`.`mail` = :mail;');
        $change_password->execute(array(':newpwd' => hash('whirlpool', $newpwd), ':mail' => $_POST['mail']));
        $to = $_POST['mail'];
        $subject = 'Camagru | Password Reset';
        $message = '
            You ask for a password reinitialisation !
        Your new pass word is now: '.$newpwd.'
        Change this password as soon as possible.
        ';
        $headers = 'From:noreply@camagru.com' . "\r\n";
        mail($to, $subject, $message, $headers);
        header("Location: login.php");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Reset Password / Camagru</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/form.css">
    </head>
    <body>
        <div class="cont">
            <form class="form-container" action="restpwd.php" method="post">
                <div class="form-title"><h2>Reset password</h2></div>
                    <div class="form-title">Your email</div>
                    <input class="form-field" type="text" name="mail" required/><br />
                    <div class="submit-container">
                        <input class="submit-button" type="submit" name="reset" value="Reset" />
                    </div>
            </form>
        </div>
    </body>
</html>
