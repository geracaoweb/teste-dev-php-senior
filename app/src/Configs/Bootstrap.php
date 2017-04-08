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
 * Serviço do Doctrine - MySQL
 */
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
  'db.options' => array(
    'driver'    => "pdo_mysql",
    'host'      => "mysql",
    'port'      => "3306",
    'dbname'    => "tasks",
    'user'      => "root",
    'password'  => "root"
  ),
));
/**
 * Middleware = Content-Type: application/json
 */
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

/**
 * [$error Vai customizar a devolução de erros das Exceptions em formato JSON]
 */
$app->error(function (\Exception $e, Request $request, $code) use ($app) {
  $error = array("msg" => $e->getMessage(), 'status' => $e->getCode());
  return $app->json($error, $e->getCode());
});

Model::$db = $app['db'];
return $app;
