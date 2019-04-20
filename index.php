<?php
/**ERRORES PHP VISIBLES**/
error_reporting(E_ALL);
ini_set('display_errors', '1');


/**
 * Configuración de constantes importantes para la aplicación.
 */
define('_PBX_', '4AEA74B7234017F0CFA26DFD6A9C643173700EAFE03DE0A05C9FEE2AE73DA096AAA6A48CDB8590688C6DA47678EB255D8AA03BE0A89F8FBDF69C9010A6518B54');
define('_PBX_SEPARATOR_', '/');
define('_PBX_BASE_PATH_', __DIR__ . _PBX_SEPARATOR_);
define('_PBX_SRC_PATH_', _PBX_BASE_PATH_ . 'src' . _PBX_SEPARATOR_);
define('_PBX_APP_PATH_', _PBX_SRC_PATH_ . 'app' . _PBX_SEPARATOR_);
define('_PBX_VENDOR_PATH_', _PBX_SRC_PATH_ . 'vendor' . _PBX_SEPARATOR_);
define('_PBX_APP_CONFIG_', _PBX_APP_PATH_ . 'config' . _PBX_SEPARATOR_);
define('_PBX_PARABOX_LIB_', _PBX_VENDOR_PATH_ . 'parabox' . _PBX_SEPARATOR_);

define('_PBX_VIEWS_', _PBX_APP_PATH_ . 'views' . _PBX_SEPARATOR_);
define('_PBX_CTRLS_', _PBX_APP_PATH_ . 'controllers' . _PBX_SEPARATOR_);

/**
 * $config
 * Almacena las configuraciones de la aplicación.
 * Se carga en ./app/config/config.php
 */
$config = [];

/**
 * Cargamos las ocnfiguraciones
 */
require _PBX_APP_CONFIG_ . 'config.php';

/**
 * Cargamos el componente de autocarga de clases
 */
require _PBX_APP_CONFIG_ . 'autoload_component.php';

use parabox\service\dependencies\DependencyContainer;

$router = DependencyContainer::routerFabric();


require _PBX_APP_CONFIG_ . 'rutas.php';

/* ¡Empieza la magia! */
$router->dispatch();
