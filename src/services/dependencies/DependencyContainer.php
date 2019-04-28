<?php declare(strict_types=1);
namespace parabox\services\dependencies;

use Exception;
use parabox\services\request\RequestBodyInterface;
use parabox\services\request\RequestBody;
use parabox\services\routing\Router;
use parabox\utils\FileExtensions;
use parabox\utils\JsonFileReader;

class DependencyContainer
{
    private static $config = null;

    public static function setConfigDependency(array $config)
    {
        self::$config = $config;
    }

    /**
     * @return JsonFileReader
     */
    public static function jsonFileReaderFabric() : JsonFileReader
    {
        $localeFile = sprintf(
            "%s%s%s",
            self::$config["locale-path"],
            self::$config["locale-prefix"],
            self::$config["active-locale"]
        );

        return new JsonFileReader(
            $localeFile,
            "json",
            FileExtensions::readJsonFile
        );
    }

    /**
     * @return Router
     * @throws Exception
     */
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