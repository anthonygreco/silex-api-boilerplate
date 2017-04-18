<?php

use Silex\Application;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Doctrine\DBAL\DBALException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ParameterBag;


$app['debug'] = DEV;

// handling CORS preflight request
$app->before(function (Request $request) {
   if ($request->getMethod() === 'OPTIONS') {
        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,DELETE,OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
        $response->setStatusCode(200);
        return $response->send();
    }
    if (strpos($request->headers->get('Content-Type'), 'application/json') === 0) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : []);
    }
}, Application::EARLY_EVENT);

// handling CORS response with right headers
$app->after(function (Request $request, Response $response) {
   $response->headers->set('Access-Control-Allow-Origin', '*');
   $response->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,DELETE,OPTIONS');
});

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile'   => __DIR__.'/logs/app.log',
    'monolog.name'      => 'APP_LOG'
));

$app->register(new DoctrineServiceProvider(), array(
    'db.options' => $dbConfig
));

// error handling
$app->error(function (Exception $e, $code) use ($app) {
    $message = 'Unknown Error';

    return $app->json([
        'message' => $message,
        'fullError' => $e->getMessage()
    ], (is_int($code) ? $code : 500));
});

$app->get('/', function() use ($app) {
    $sql = 'select * from apiTest';
    return $app->json($app['db']->fetchAll($sql), 200);
});

return $app;
