<?php
/**
* Configuraciones publicas de la aplicación
*
* A contar de la versión 0.0.2 se intentará comentar en inglés.
*/

/**
 * Se debe revisar si existe el arreglo de configuraciones, si es array, y si existen  as direcciones base, sino, lanza error
 */
if (!isset($config) || !is_array($config)) {
    throw new Exception ('Something was wrong. Configuration not available. Check your installation or go for support to app website');
}


$config["base-url"] = "http://localhost:4567/parabox/public/";
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
$config["locale-path"]   = _PBX_CONFIG_PATH_ . "locales" . _PBX_SEPARATOR_;
$config["locale-prefix"] = "locale-";


/**
 * This url values will replace correspondin placehoders. The app will use this system as a rouhgly template system
 */
$config["assets-url"] = $config["base-url"]   . _PBX_ASSETS_PATH_;
$config["css-url"]    = $config["assets-url"] . _PBX_CSS_PATH_;
$config["img-url"]    = $config["assets-url"] . _PBX_IMG_PATH_;
$config["js-url"]     = $config["assets-url"] . _PBX_JS_PATH_;

/**
 * Place holders for special urls in the app
 */
$config["css-tag"] = "[CSS]";
$config["img-tag"] = "[IMG]";
$config["js-tag"]  = "[JS]";


/**
 * Custom tags
 *
 * Here you can define your own custom tags which will be replaced for values provided for you in your controllers.
 *
 * Example, {title} will be converted into "Paradox. Open Source EdvApp", (without "), whereve it appears in the views or the fragments
 */
$config["custom-tags"] = [];
$config["custom-tags"]["title"] = [];
$config["custom-tags"]["title"]["tag"]   = "{title}";
$config["custom-tags"]["title"]["value"] = "Paradox. Open Source EdvApp.";
