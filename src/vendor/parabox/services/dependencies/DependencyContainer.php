<?php declare(strict_types=1);
namespace parabox\services\dependencies;

use Exception;
use parabox\services\request\RequestBodyInterface;
use parabox\services\request\RequestBody;
use parabox\services\routing\Router;

class DependencyContainer
{
    private static $config = null;

    public static function setConfigDependency(array $config)
    {
        self::$config = $config;
    }

    public static function routerFabric()
    {
        return new Router( new RequestBody() );
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function configFabric()
    {
        if (self::$config === null) {
            throw new Exception("Can't find config dependency. I can't return dependency");
        }
        return self::$config;
    }
}