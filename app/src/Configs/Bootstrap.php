<?php
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Query\QueryBuilder;

use Acme\System\Model;

date_default_timezone_set('UTC');
require_once __DIR__.'/../../vendor/autoload.php';

$app = new Application();
$app['debug'] = True;

/**
 * Serviço do Doctrine - SQLite3
 */
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/../../db/task.sqlite',
    ),
));

/**
 * [$error Vai customizar a devolução de erros das Exceptions em formato JSON]
 */
$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    $error = array("msg" => $e->getMessage(), 'status' => $code);
    return $app->json($error, $code);
});

Model::$db = $app['db'];
return $app;
