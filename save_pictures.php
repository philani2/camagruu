<?php
    include("config/database.php");
    session_start();

    if ($_SESSION['log_in'] === "" || $_POST['pic'] === "" || $_POST['filter'] === "")
        header("Location: index.php?error=3");
    else {
        header("Location: index.php?success=3");
        try {
            $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
        $db->query('USE `camagru`;');
        $pic = explode(',', $_POST['pic']);
        $filtre = imagecreatefrompng($_POST['filter']);
        $pictures = imagecreatefromstring(base64_decode($pic[1]));
        $detail = getimagesize($_POST['filter']);
        imagealphablending($pictures, true);
        imagecopy($pictures, $filtre, $_POST['x'], $_POST['y'], 0, 0, $detail[0], $detail[1]);
        ob_start();
        imagepng($pictures);
        $image = ob_get_clean();

        $pictures = 'data:image/png;base64,'.base64_encode($image);

        $save_pictures = $db->prepare('INSERT INTO `img` (`picture`, `user`, `vote`) VALUES (:pic, :user, :vote);');
        $save_pictures->execute(array(
            ':pic' => $pictures,
            ':user' => $_SESSION['log_in'],
            ':vote' => 0
        ));

}
?>
