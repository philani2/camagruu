<?php
    include("config/database.php");
    session_start();

    if ($_SESSION['log_in'] == "") { header("Location:login.php"); }
    try {
        $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
    $db->query('USE `camagru`;');
    $select_coms = $db->prepare('SELECT * FROM `img` WHERE `img`.`id` = :id;');
    $select_coms->execute(array(':id' => $_GET['id']));
    $result = $select_coms->fetch(PDO::FETCH_ASSOC);
    if ($result['picture'] == "") { header("Location: gallery.php"); }

    if ($_POST['comment'] != ""
         && $_SESSION['log_in'] != "" && $_GET['id']) {
        $select_user = $db->prepare('SELECT * FROM `user` WHERE `user`.`username` = :username;');
        $update_coms = $db->prepare('UPDATE `img` SET `comment` = :comment WHERE `img`.`id` = :id;');;
        $username = $result['user'];

        $result = unserialize($result['comment']);
        $n_com = array('user' => $_SESSION['log_in'], 'commentaire' => htmlspecialchars($_POST['comment']));
        $result[] = $n_com;
        $update_coms->execute(array(':id' => $_POST['id'], ':comment' => serialize($result)));

        $select_user->execute(array(":username" => $username));
        $mail = $select_user->fetch(PDO::FETCH_ASSOC);
        $to = $mail['mail'];
        $subject = 'Camagru | You received a comment';
        $message = '
            Someone comments your picture !
        ------------------------
        Username: '.$_SESSION['log_in'].'
        Comment: '.$_POST['comment'].'
        ------------------------';
        $headers = 'From:noreply@camagru.com' . "\r\n";
        mail($to, $subject, $message, $headers);
        header("Location: login.php?success=1");
    }

    function display_pics() {
        require("config/database.php");
        try {
            $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
        $db->query('USE `camagru`;');
        $get_pics = $db->prepare('SELECT * FROM `img` WHERE `id` = :id');
        $get_pics->execute(array(':id' => $_GET['id']));
        $result = $get_pics->fetch(PDO::FETCH_ASSOC);
        echo "<img class='gal-pics' style='width:50%; margin: 5px' src='".$result['picture']."'/>";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>comment / Camagru</title>
        <link rel="stylesheet" href="css/style.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <link rel="stylesheet" href="css/form.css">
    </head>
    <header>
    <div class="header">
        <h1><a href="index.php">Camagru</a></h1>
        <a id="gal" href="gallery.php" style="margin-right: 15px;">Gallery</a>
        <a href='logout.php'>Logout</a>
    </div>
    </header>
    <body>
        <center>
            <div style="width:60%;">
                <?php display_pics(); ?>
            </div>
        </center>

        <div>
            <div id="comment" class="comment" style="float:left;">
                <span></span>
            </div>
            <div class="add_com">
                <form id="com" class="form-container" style="float:left;" action=<?php echo '"comment.php?id='.$_GET['id'].'";' ?> method="post">
                    <div class="form-title"><h2>Comment picture</h2></div>
                        <div class="form-title">Your comments</div>
                        <textarea id="textarea" class="form-field" name="comment" rows="8" cols="80" required></textarea><br />
                        <input style="display:none;" type="text" name="id" value=<?php echo '"'.$_GET['id'].'"' ?>>
                        <div class="submit-container">
                            <input class="submit-button" type="submit" name="submit" value="Submit comment" />
                        </div>
                </form>
            </div>
        </div>
    <script type="text/javascript">
        const com = document.querySelector('#com');
        const xhr = new XMLHttpRequest();
        const comments = document.querySelector('#comment');


        com.addEventListener('submit', (e) => {
            e.preventDefault();
            let form = new FormData(com);
            xhr.open('POST', com.action, true);
            comments.innerHTML = "";
            xhr.onload = () => {
                if (xhr.status === 200 && xhr.readyState === 4) {
                    xhr.open('GET', 'get_comms.php?id=' + <?php echo $_GET['id'] ?>, true);
                    xhr.onload = () => {
                        if (xhr.status === 200 && xhr.readyState === 4) {
                                let com = JSON.parse(xhr.responseText);
                                for (let i = 0; i < com.length; i++) {
                                    let e = document.createElement('div');
                                    let h2 = document.createElement('h2');
                                    let p = document.createElement('p');
                                    e.setAttribute('class', 'comments');
                                    h2.setAttribute('class', 'user-comments');
                                    h2.innerHTML = com[i].user;
                                    p.setAttribute('class', 'comm');
                                    p.innerHTML = com[i].commentaire;
                                    e.appendChild(h2);
                                    e.appendChild(p);
                                    comments.appendChild(e);
                            }
                        }
                    }
                    xhr.send();
                }
            }
            xhr.send(form);
            document.getElementById('textarea').value = "";
        }, false);

        xhr.open('GET', 'get_comms.php?id=' + <?php echo $_GET['id'] ?>, true);
        xhr.onload = () => {
            if (xhr.status === 200 && xhr.readyState === 4) {
                if (xhr.responseText === "There are no comments") {
                    document.querySelector("span").innerHTML = xhr.responseText;
                } else {
                    let com = JSON.parse(xhr.responseText);
                    for (let i = 0; i < com.length; i++) {
                        let e = document.createElement('div');
                        let h2 = document.createElement('h2');
                        let p = document.createElement('p');
                        e.setAttribute('class', 'comments');
                        h2.setAttribute('class', 'user-comments');
                        h2.innerHTML = com[i].user;
                        p.setAttribute('class', 'comm');
                        p.innerHTML = com[i].commentaire;
                        e.appendChild(h2);
                        e.appendChild(p);
                        comments.appendChild(e);
                        }
                }
            }
        }
        xhr.send();
    </script>
    </body>
</html>
