<?php

$isProduction = false; // true para producción

if ($isProduction) {
    define('BASE_URL', 'https://corefit.com/');
} else {
    define('BASE_URL', 'http://localhost/CoreFit/');
    // define('BASE_URL', 'http://192.168.0.18/CoreFit/') //va la ip actual del equipo;
}

define('ROOT_PATH', __DIR__);

// variables de twilio para el envío de sms
define('TWILIO_SID', '...');
define('TWILIO_AUTH_TOKEN', '...');
define('TWILIO_FROM', '...');

?>