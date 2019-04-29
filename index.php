<?php
/**
 * index.php
 *
 * Bootstrapping services
 *
 * A contar de la versión 0.0.2  se intentará comentar en inglés [AR].
 */

/**ERRORES PHP VISIBLES**/
error_reporting(E_ALL);
ini_set('display_errors', '1');

/**--------------------------------------------------------------------------------------------------------------------
 * Cargamos el componente de manejo de excepciones mejorado Whoops
 */
require_once __DIR__ . '/vendor/autoload.php';
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

/**--------------------------------------------------------------------------------------------------------------------
 * Configuración de constantes importantes para la aplicación.
 */
define('_PBX_', '4AEA74B7234017F0CFA26DFD6A9C643173700EAFE03DE0A05C9FEE2AE73DA096AAA6A48CDB8590688C6DA47678EB255D8AA03BE0A89F8FBDF69C9010A6518B54');
define('_PBX_SEPARATOR_', '/');
define('_PBX_BASE_PATH_',   __DIR__           . _PBX_SEPARATOR_);
define('_PBX_PUBLIC_PATH_', _PBX_BASE_PATH_ . 'public' . _PBX_SEPARATOR_);
define('_PBX_CONFIG_PATH_', _PBX_PUBLIC_PATH_ . 'config' . _PBX_SEPARATOR_);
define('_PBX_SRC_PATH_', _PBX_BASE_PATH_ . 'src' . _PBX_SEPARATOR_);

define('_PBX_VIEWS_PATH_', _PBX_PUBLIC_PATH_ . 'views' . _PBX_SEPARATOR_);
define('_PBX_FRAGMENTS_PATH_', _PBX_VIEWS_PATH_ . 'shared_fragments' . _PBX_SEPARATOR_);

define('_PBX_ASSETS_PATH_', 'assets' . _PBX_SEPARATOR_);
define('_PBX_CSS_PATH_', 'css' . _PBX_SEPARATOR_);
define('_PBX_IMG_PATH_', 'img' . _PBX_SEPARATOR_);
define('_PBX_JS_PATH_', 'js' . _PBX_SEPARATOR_);

/**--------------------------------------------------------------------------------------------------------------------
 * $config
 * Almacena las configuraciones de la aplicación.
 * Se carga en ./app/config/config.php
 */
$config = [];
/**
 * Cargamos las ocnfiguraciones
 */
require _PBX_CONFIG_PATH_ . 'config.php';

/**--------------------------------------------------------------------------------------------------------------------
 * Inicializar componente parabox de routing
 */
use \parabox\services\dependencies\DependencyContainer;

DependencyContainer::setConfigDependency($config);
$router = DependencyContainer::routerFabric();

require _PBX_CONFIG_PATH_ . 'rutas.php';


/**--------------------------------------------------------------------------------------------------------------------
 * ¡Empieza la magia!
 */
$router->dispatch();
