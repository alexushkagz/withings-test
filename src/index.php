<?php
require_once('config.php');

$oauth2_url = $api->authorize();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <button id="authBtn">Authorize</button>
    <script>
        document.getElementById('authBtn').addEventListener('click', () => {
            window.open("<?= $oauth2_url ?>", "OAuth2PopUp", "width=800," + "height=600," + "popup=1");
            let interval = setInterval(() => {
                fetch('./auth.php?isAuth')
                    .then(response => response.json())
                    .then(data => {
                        if (data.isAuth) {
                            clearInterval(interval);
                            location.href = './measurements.php';
                        }
                    })
                    .catch(err => console.error(err));
            }, 1000)
        })
    </script>
</body>
</html>