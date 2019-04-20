<?php declare(strict_types=1);
namespace parabox\services\dependencies;


use parabox\services\request\RequestBody;
use parabox\services\routing\Router;

class DependencyContainer
{
    public static function routerFabric()
    {
        return new Router( new RequestBody() );
    }
}