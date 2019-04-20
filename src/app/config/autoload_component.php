<?php
/**
 * Componente de autocarga de clases.
 */

spl_autoload_register('pbx_vendors_loader');
spl_autoload_register('pbx_app_loader');
spl_autoload_register('pbx_controllers_loader');


function pbx_vendors_loader($className)
{
    requireIfExist($className, _PBX_VENDOR_PATH_);
}

function pbx_app_loader($className)
{
    requireIfExist($className, _PBX_APP_PATH_);
}

function pbx_controllers_loader($className)
{
    requireIfExist($className, _PBX_CTRLS_);
}

function requireIfExist($file, $path)
{
    $file = $path . str_replace("\\", DIRECTORY_SEPARATOR, $file) . '.php';
    if (file_exists($file)) {
        require_once($file);
    }
}