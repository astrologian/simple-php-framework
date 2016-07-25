<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 'on');

define('ROOT', __DIR__ . '/');
require 'System/autoload.php';

require 'App/bootstrap.php';

$uri_a = explode('?', $_SERVER["REQUEST_URI"]);
$uri = trim($uri_a[0], '/');

$container = System\Container::factory('services');
$container->set('System\Request', null);
$container->set('root_dir', ROOT);
$router = $container->get('System\Router');
$router->dispatch($uri);
