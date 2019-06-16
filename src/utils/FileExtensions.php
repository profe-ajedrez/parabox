<?php /** @noinspection ALL */
declare(strict_types=1);
namespace parabox\utils;

use JsonException;

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



    /**
     * @param string $file
     * @return mixed
     *
     * @throws JsonException
     */
    public static function readJsonFile(string $file = "", bool $assoc = false)
    {
        $locale = file_get_contents($file);
        if ($locale !== false) {
            $json = json_decode($locale, $assoc);
            $_json_error = json_last_error();
            if ($_json_error == 0) {
                return $json;
            }
            throw new JsonException("It is seems that json file $file was in incorrect format or bad constructed. json error N° " . $_json_error);
        }
    }


    public static function writeJsonFile(string $file, string $data)
    {
        file_put_contents($file, $data);
    }

    /**
     * @param string $file
     * @param string $excludeLinesStartedWith
     * @param string $eol
     * @return array
     */
    public static function fileToArray(
        string $file = '',
        string $excludeLinesStartedWith = '#',
        string $eol = '\n'
    ) : array {
        if (file_exists($file)) {
            $allCases = explode($eol, file_get_contents($file));
            $list = [];
            array_walk($allCases, "FileExtensions::step", $list);
        }

        self::$comment = $excludeLinesStartedWith;

        return [];
    }


    /**
     * @param $val
     * @param $key
     * @param $arr
     */
    public static function step($val, $key, &$arr)
    {
        $unCaso     = explode(self::$separator, $val);
        $posComment = strpos($unCaso[ 0 ], self::$comment);

        if ($posComment === false || $posComment > 0) {
            $arr[ $unCaso[ 0 ] ] = $unCaso[ 1 ];
        }
    }


    /**
     * @param string $sep
     */
    public static function setSeparator(string $sep = ' ')
    {
        self::$separator = $sep;
    }


    /**
     * @return string
     */
    public static function getLog()
    {
        return self::$log;
    }
}
