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
    private $jsonLoader;

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
        $this->fileExtension = $fileExtensions;
        $this->jsonLoader    = (is_null($jsonLoader) ? FileExtensions::readJsonFile : $jsonLoader);
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
            return $this->jsonLoader($this->localeFile . "." . $this->fileExtension);
        }
        throw new Exception("Locale file not found");
    }
}
