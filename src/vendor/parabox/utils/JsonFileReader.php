<?php
declare(strict_types=1);
namespace parabox\utils;


use JsonException;
use TypeError;
use Exception;

class LocaleManager
{

    /**
     * @param string $localeFile
     * @param string $fileExtension
     * @param callable|null $jsonLoader
     * @return mixed
     *
     * @throws TypeError
     * @throws JsonException
     * @throws Exception
     */
    public static function getLocale(
        string $localeFile = "",
        string $fileExtension = "json",
        callable $jsonLoader = null)
    {
        if (empty($localeFile)) {
            throw new TypeError("A localization file name with path was expected. Received null or empty.");
        }

        if (file_exists($localeFile . "." . $fileExtension)) {
            return FileExtensions::readJsonFile($localeFile . "." . $fileExtension);
        }
        throw new Exception("Locale file not found");
    }
}
