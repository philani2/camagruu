<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Login / Camagru </title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/form.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    </head>
    <body>
        <div class="login-container">
            <form id="form" class="form-container" action="login.php" method="post">
            <div class="form-title"><h2>Login</h2></div>
                <label for="username">Username: </label><input class="form-field"type="text" name="username" value="" placeholder="Username"><br>
                <label for="password">Password: </label><input class="form-field" type="password" name="password" value="" placeholder="password"><br />
                <div class="submit-container">
                    <input class="submit-button" type="submit" name="login" value="Login" />
                </div><br />
                <span id="error"></span>
                <a href="register.php">No account ? Register now</a>
            </form>
        </div>
        <script type="text/javascript">
            const xhr = new XMLHttpRequest();
            const form = document.querySelector('#form');

            form.addEventListener('submit', (e) => {
                e.preventDefault();
                xhr.open('POST', 'log.php');
                xhr.onload = () => {
                    if (xhr.status === 200 && xhr.readyState === 4) {
                        let rep = xhr.responseText;
                        if (rep === "OK") {
                            window.location = 'index.php';
                        }
                        else {
                            document.querySelector('#error').innerHTML = rep;
                        }
                    }
                }
                xhr.send(new FormData(form));
            }, false);
        </script>
    </body>
</html>
