<?php
session_set_cookie_params(3600,"/");
session_start();
// phpinfo();

spl_autoload_register(function($class) {
    if (is_readable("classes/{$class}.class.php"))
        include "classes/{$class}.class.php";
});

// date_default_timezone_set("Europe/Paris");

$api = new ApiConnection();