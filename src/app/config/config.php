<?php
/**
* Configuraciones publicas de la aplicación
*/

/**
 * Se debe revisar si existe el arreglo de configuraciones, si es array, y si existen  as direcciones base, sino, lanza error
 */
if (!isset($config) || !is_array($config)) {
    throw new Exception ('Something was wrong. Configuration not available. Check your installation or go for support to app website');
}

if (! file_exists(_PBX_APP_CONFIG_ . 'settings.ini')) {
    throw new Exception ('Configuration file not available. Check your installation or go for support to app website');
}

$config["driver"]   = "pgsql";
$config["host"]     = "localhost";
$config["db-usr"]   = "";
$config["password"] = "";
$config["database"] = "parabox";
$config["port"]     = "5432";

$config["cdn"]      = "host=" .      $config["host"] .
                      " port=" .     $config["port"] .
                      " dbname=" .   $config["database"] .
                      " user=" .     $config["db-usr"] .
                      " password=" . $config["password"];
