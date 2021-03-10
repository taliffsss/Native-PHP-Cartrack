<?php

/**
 * Set Environment
 */
define('ENVIRONMENT', isset($_SERVER['CARTRACK_ENV']) ? $_SERVER['CARTRACK_ENV'] : 'development');

 // Namespaces
define('CLASS_NAMESPACE', 'Cartrack');
define('DIR_ROOT', dirname(__FILE__));

switch (ENVIRONMENT) {
	case 'test':
	case 'development':
		error_reporting(-1);
		ini_set('display_errors', 1);
		ini_set("error_log", "logs/".date('Y-m-d').'.log');
	break;
	case 'production':
		ini_set('display_errors', 0);
		ini_set("error_log", "logs".Constant::ERROR_LOG.date('Y-m-d').'.log');
		error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
	break;
	default:
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'The application environment is not set correctly.';
		exit(1);
}

require_once __DIR__ . DIRECTORY_SEPARATOR .'vendor/autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'autoload.php'; 

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;
use Cartrack\Controller\Crud;
use Cartrack\Core\App;
use Cartrack\Libraries\AuthorizationMiddleware;

$app = AppFactory::create();

$app->group('/user', function (RouteCollectorProxy $group) {
	
    $group->delete('/remove', '\Cartrack\Controller\Crud::remove')->add(new AuthorizationMiddleware);
    $group->get('/show', '\Cartrack\Controller\Crud::show')->add(new AuthorizationMiddleware);
    $group->post('/store', '\Cartrack\Controller\Crud::store');
    $group->patch('/update/{id}', '\Cartrack\Controller\Crud::update')->add(new AuthorizationMiddleware);
});


$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write('PHP Version '.phpversion());
    return $response;
});

$app->run();