<?php

require_once __DIR__.'/vendor/autoload.php';

if (file_exists(__DIR__.'/local.config.php')) {
    require __DIR__.'/local.config.php';
} else {
    require __DIR__.'/config.php';
}

if(DEV) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}


require __DIR__.'/app.php';
