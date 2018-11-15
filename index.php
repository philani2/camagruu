<?php
    include("config/database.php");
    session_start();

    if ($_SESSION['log_in'] == ""){
        header("Location: login.php");
    }

    function is_log() {
        if ($_SESSION['log_in'] != "")
        {
            echo "<h2>Welcome " . $_SESSION['log_in']. " !</h2><br />";
            echo "<a href='manage_account.php'>Manage account</a><br />";
        }
    }

    function display_pics(){
        require("config/database.php");
        try {
            $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
        $db->query('USE `camagru`;');
        $get_pics = $db->prepare('SELECT * FROM `img` WHERE `user` = :user ORDER BY `date` DESC;');
        $get_pics->execute(array('user' => $_SESSION['log_in']));
        $result = $get_pics->fetchAll();
        foreach ($result as $row)
        {
            echo "<img id='".$row['id']."' class='gal-pics' style='width:30%; margin: 5px' src='".$row['picture']."'/>";
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Camagru</title>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/form.css">
    </head>
    <header>

    <body>
        <div class="header">
            
            <h1>Camagru</h1>
            <a href='index.php'>Home</a>
            <a href="gallery.php">Gallery</a>
            <a href='logout.php'>Logout</a>

        </div>
        </div>
        </header><hr>
        <?php if ($_GET['success'] == 1) {
        echo "<p>
            Username correctly changed !
            </p>";}
            else if ($_GET['success'] == 2) {
            echo "<p>
                Password correctly changed !
            </p>";}
            else if ($_GET['success'] == 3) {
            echo "<p>
                Picture saved !
            </p>";}
        ?>
        <center>
        <div class="main-frame" style="width: 100%;">
            <div class="left-panel">
                <div class="block">
                    <?php is_log() ?>
                    <input type="file" id="cust_pic" accept="image/*">
                </div>
            </div>
            <div class="right-panel" style="overflow:auto;">
                <p>Click on a picture to delete it</p>
                <?php display_pics() ?>
            </div>
            <video style="width: 30%" autoplay></video>
        </div><br /><br />
        </center>
    <hr>
        <center>
            <div class="main-frame" style="width:100%">
                <div class="left-panel">
                    <div class="block">
                        <h2>Filtre</h2>
                        <p>Click on the camera to take a picture !</p>
                        <div class="filter">
                            <img id="glasses" src="filtre/glasses.png" style="width:30%" alt="">
                            <img id="willface" src="filtre/willface.png"style="width:30%" alt="" />
                            <img id="ghost" src="filtre/ghost.png" style="width:30%" alt="">
                        </div>
                    </div>
                </div>
                <div class="right-panel" style="text-align:left">
                    <div id="send-container">
                            <form id="myform" action="save_pictures.php" method="post">
                                <div class="block">
                                    <label for="x">X position: </label><input id="x" type="range" name="x" value="150" min="-150" max="500">
                                </div>
                                <div class="block">
                                    <label for="y">Y position: </label><input id="y" type="range" name="y" value="150" min="-150" max="500">
                                </div>
                                <input id="pic" type="text" name="pic" style="display:none" value=""/>
                                <input id="filter" type="text" name="filter" style="display:none;" value="">
                                <a href="javascript:{}" id="login" class="myButton" onclick="document.getElementById(`myform`).submit(); return false;">Save Pic</a>
                            </form>
                    </div>
                </div>
                <center><img id="picture" src="" style="display:none;width:30%;"></center>
                <canvas style="display:none"></canvas><br>
                <canvas id="save" style="display:none"></canvas><br>
            </div>
        </center>


    </body>
    <footer>

    </footer>
    <script src= js/cm.js>
    
    </script>
   <div class="footer">
  <p>Created by bgumede 2018</p>
</div>
</html>
