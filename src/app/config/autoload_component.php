<?php
/**
 * Componente de autocarga de clases.
 */

spl_autoload_register('pbx_vendors_loader');

function pbx_vendors_loader($className)
{
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    require _PBX_SRC_PATH_ . $classname - '.php';
}

function pbx_app_loader($className)
{
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    require _PBX_APP_PATH_ . $classname - '.php';
}