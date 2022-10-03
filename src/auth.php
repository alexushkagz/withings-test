<?php
require_once('config.php');

if (isset($_GET['isAuth'])) {
    http_response_code(200);
    header('Content-Type: application/json; charset=utf-8');
    $isAuth = isset($_SESSION['access_token']) ? true : false;
    $response = [
        'isAuth' => $isAuth
    ];
    echo json_encode($response);
    exit();
}

// var_dump($_SESSION);
if (isset($_GET['code'])) {
    $response = $api->requestTokens($_GET['code']);
    $response = json_decode($response, true);
    if(array_key_exists("access_token", $response["body"])) {
        $response_body = $response["body"];
        $_SESSION['access_token'] = $response_body['access_token'];
        $_SESSION['refresh_token'] = $response_body['refresh_token'];
    }
    if(array_key_exists("error", $response)) {
        echo "<br>Request Error: " . $response["error"];
    }
    // exit();
}

if (isset($_SESSION['access_token'])) {
    echo("<br>You've been authorized...");
    ?>
    <script>
        setTimeout(() => window.close(), 2000);
    </script>
<?php
}