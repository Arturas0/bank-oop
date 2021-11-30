<?php
session_start();

require __DIR__ . '/vendor/autoload.php';

function getSetting(string $option)
{
    $settings = require __DIR__ .  '/app/settings.php';
    return $settings[$option] ?? null;
}

define('URL',  'http://' . $_SERVER["SERVER_NAME"] . '/public/');
define('DIR', __DIR__ . '/');
define('INSTALL_DIR', '/public/');
