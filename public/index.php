<?php

require_once __DIR__.'/vendor/autoload.php';

// load {project} classes
foreach(scandir(__DIR__.'/{project}') as $filename) {
    $file = __DIR__.'/{project}/'.$filename;
    if(is_file($file)) {
        require $file;
    }
}

// load environment config
require __DIR__.'/config.php';

// load any local overrides
if (file_exists(__DIR__.'/local.config.php')) {
    require __DIR__.'/local.config.php';
}

// set dev constant
define('DEV', $dev);

// set error reporting
error_reporting((DEV) ? E_ALL : 0);
ini_set('display_errors', (DEV) ? 1 : 0);

// create app
$app = new Silex\Application();

// load controllers
foreach(scandir(__DIR__.'/controllers') as $filename) {
    $file = __DIR__.'/controllers/'.$filename;
    if(is_file($file)) {
        require $file;
    }
}

// load app
require __DIR__.'/app.php';

// check for cli
if (isset($argv) && count($argv) > 0) {
    define('CLI', true);
    list($_, $method, $path) = $argv;
    $request = Request::create($path, $method);
    $app->run($request);
} else {
    $app->run();
}
