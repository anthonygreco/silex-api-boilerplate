<?php

date_default_timezone_set('America/New_York');

$dev = false;

if (file_exists(__DIR__.'../../.env')) {
    $envFile = fopen(__DIR__.'../../.env', 'r');
    while (($line = fgets($envFile)) !== false) {
        if (trim($line) !== '') {
            putenv(trim($line));
        }
    }
    fclose($envFile);
}

$dbConfig = [
    'driver' => 'pdo_mysql',
    'dbhost' => 'localhost',
    'dbname' => '{dbname}',
    'user' => getenv('DB_USER'),
    'password' => getenv('DB_PASS')
];

define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT']);
