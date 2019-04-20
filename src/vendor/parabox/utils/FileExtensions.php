<?php
declare(strict_types=1);
namespace \parabox\utils;


/**
 * FileExtensions
 * Tiene la responsabilidad de englobar funciones ùtiles para el manejo de archivos que extienden las capacidades de PHP
 *
 * Creada en 18-04-2018/
 * @author Andrés Reyes a.k.a. Jacobopus
 */
class FileExtensions
{
    const VERSION = "0.0.1";
    protected static $log = '';
    protected static $separator = "";
    protected static $comment   = "#";


    public static function fileToArray(
        string $file = '',
        string $excludeLinesStartedWith = '#',
        string $eol = '\n'
    ) : array
    {
        if (file_exists($file)) {
            $allCases = explode($eol, file_get_contents($file));
            $list = [];
            array_walk($allCases, "FileExtensions::step", $list);
        }

        self::$comment = $excludeLinesStartedWith;

        return [];
    }


    public static function step($val, $key, &$arr)
    {
        $unCaso     = explode(self::$separator, $val);
        $posComment = strpos($unCaso[ 0 ], self::$comment);

        if ($posComment === false || $posComment > 0) {
            $arr[ $uncaso[ 0 ] ] = $unCaso[ 1 ];
        }
    }


    public static function setSeparator(string $sep = ' ')
    {
        self::$separator = $sep;
    }


    public static function getLog()
    {
        return self::$log;
    }
}