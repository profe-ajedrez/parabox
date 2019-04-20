<?php declare(strict_types=1);
namespace parabox\service\dependencies;


use parabox\service\request\RequestBody;
use parabox\service\routing\Router;

class DependencyContainer
{
    public static function routerFabric()
    {
        return new Router( new RequestBody() );
    }
}