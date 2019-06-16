<?php
declare(strict_types=1);
namespace parabox\utils;

use JsonException;
use TypeError;
use Exception;

class JsonFileReader
{
    private $localeFile    = "";
    private $fileExtension = "json";
    private $jsonLoaderAlgoritmo;

    /**
     * JsonFileReader constructor.
     * @param string $localeFile
     * @param string $fileExtension
     * @param callable|null $jsonLoader
     */
    public function __construct(
        string $localeFile = "",
        string $fileExtension = "json",
        callable $jsonLoader = null
    ) {
        $this->localeFile    = $localeFile;
        $this->fileExtension = $fileExtension;

        if (is_null($jsonLoader)) {
            $this->jsonLoaderAlgoritmo  = function($f) {
                return FileExtensions::readJsonFile($f);
            };
        } else {

            $this->jsonLoaderAlgoritmo =  $jsonLoader;
        }
    }


    /**
     * @return mixed
     *
     * @throws TypeError
     * @throws JsonException
     * @throws Exception
     */
    public function getLocale()
    {
        if (empty($this->localeFile)) {
            throw new TypeError("A localization file name with path was expected. Received null or empty.");
        }

        if (file_exists($this->localeFile . "." . $this->fileExtension)) {
            return $this->jsonLoaderAlgoritmo($this->localeFile . "." . $this->fileExtension);
        }
        throw new Exception("Locale file not found");
    }


    public function jsonLoader($file = "", $assoc = false) 
    {
        if (file_exists($file)) {
            return FileExtensions::readJsonFile($file, $assoc);
        }
        throw new Exception("Json file not found");
    }


    public function jsonWriter(string $file, string $data) 
    {
        FileExtensions::writeJsonFile($file, $data);
    }
}
