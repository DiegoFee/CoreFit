<?php

$isProduction = false; // true para producción

if ($isProduction) {
    define('BASE_URL', 'https://corefit.com/');
} else {
    define('BASE_URL', 'http://localhost/CoreFit/');
    // define('BASE_URL', 'http://192.168.0.15/CoreFit/');
}

define('ROOT_PATH', __DIR__);

?>