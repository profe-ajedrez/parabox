<?php declare(strict_types=1);
/**
 * Registre aquí sus rutas en la forma:
 *
 * $router->add( string $rutaUrl, function() { ... } );
 *
 * o en la forma Clase#controlador
 *
 * ejemplo:
 *
 * $router->add('/', function() use ( $view ) {
 *
 *     $view->display('home.php');
 * });
 *
 */

$router->add('/', 'controllers\IndexController#index');
$router->add('/parabox/', 'controllers\IndexController#index');
$router->add('/parabox/index.php', 'controllers\IndexController#index');