<?php /** @noinspection ALL */
/**
* Configuraciones publicas de la aplicación
*/

/**
 * Se debe revisar si existe el arreglo de configuraciones, si es array, y si existen  as direcciones base, sino, lanza error
 */
if (!isset($config) || !is_array($config)) {
    /** @noinspection PhpUnhandledExceptionInspection */
    throw new Exception ('Something was wrong. Configuration not available. Check your installation or go for support to app website');
}

if (! file_exists(_PBX_APP_CONFIG_ . 'settings.ini')) {
    /** @noinspection PhpUnhandledExceptionInspection */
    throw new Exception ('Configuration file not available. Check your installation or go for support to app website');
}

$config["base-url"] = "http://localhost/parabox/";
$config["driver"]   = "pgsql";
$config["host"]     = "localhost";
$config["db-usr"]   = "jacobopus";
$config["password"] = "mariafeliz";
$config["database"] = "parabox";
$config["port"]     = "5432";

$config["cdn"]      = "host=" .      $config["host"] .
                      " port=" .     $config["port"] .
                      " dbname=" .   $config["database"] .
                      " user=" .     $config["db-usr"] .
                      " password=" . $config["password"];

$config["active-locale"] = "spanish";
$config["locale-path"]   = _PBX_APP_CONFIG_ . "locales" . _PBX_SEPARATOR_;
$config["locale-prefix"] = "locale-";
