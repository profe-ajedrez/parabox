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

$router->add('/', 'app\controllers\IndexController#index');
$router->add('/index.php', 'app\controllers\IndexController#index');

$router->add('/controlpanel', 'app\controllers\ControlPanelController#index');
$router->add('/activity',     'app\controllers\ActivityController#index');
