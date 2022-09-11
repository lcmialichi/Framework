<?php

$envPath = __DIR__ . "/../.env";

if(file_exists($envPath)){
    $envs = parse_ini_file($envPath);
    foreach($envs as $key => $value){
        putenv($key. "=" . $value);
    }
}

define("TELEGRAM_URL", "https://api.telegram.org/bot" . getenv("TELEGRAM_TOKEN"));

/**
 * Router
 */
define("BAD_REQUEST", 400);
define("NOT_FOUND", 404);
define("METHOD_NOT_ALLOWED", 405);
define("NOT_IMPLEMENTED", 501);

