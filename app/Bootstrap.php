<?php

namespace Skybet;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require __DIR__ . '/../vendor/autoload.php';

require __DIR__."/ErrorReporting/php_error.php";

\php_error\reportErrors();

error_reporting(E_ALL);

$environment = 'development';

//loading routes

$router = new \League\Route\RouteCollection;

$router->addRoute('GET', '/', 'Skybet\Controllers\MainController::ListAll'); // Classname::methodName
$router->addRoute( 'POST', '/add','Skybet\Controllers\MainController::add'); 
$router->addRoute( 'POST', '/update', 'Skybet\Controllers\MainController::update'); 
$router->addRoute( 'POST', '/delete', 'Skybet\Controllers\MainController::del'); 

$dispatcher = $router->getDispatcher();

$response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'],$_SERVER['REQUEST_URI']);

$response->send();
