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
define('TWILIO_SID', 'ACcdaa72871411c299d3d3fd5fe9d92296');
define('TWILIO_AUTH_TOKEN', 'f3d81b158af6cbf31104b2376be62018');
define('TWILIO_FROM', '+14359992317');

?>