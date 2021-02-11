<?php

use Phalcon\Config\Adapter\Ini;
use Phalcon\DI,
    Phalcon\DI\FactoryDefault;
use Phalcon\Loader;

ini_set('display_errors',1);
error_reporting(E_ALL | E_STRICT | E_DEPRECATED);

define('ROOT_PATH', __DIR__);
define('PATH_INCUBATOR', ROOT_PATH . '/../vendor/phalcon/incubator/');
define('PATH_CONFIG', ROOT_PATH . '/../app/config/config.ini');
define('PATH_FORMS', ROOT_PATH . '/../app/forms/');
define('PATH_FORMS_VALIDATOR', ROOT_PATH . '/../app/forms/validator/');
define('PATH_HELPER', ROOT_PATH . '/../app/helpers/');
define('PATH_LIBRARY', ROOT_PATH . '/../app/library/');
define('PATH_MODELS', ROOT_PATH . '/../app/models/');
define('PATH_SERVICES', ROOT_PATH . '/../app/services/');

set_include_path(
    ROOT_PATH . PATH_SEPARATOR . get_include_path()
);

require_once ROOT_PATH . '/../vendor/autoload.php';

// use the application autoloader to autoload the classes
// autoload the dependencies found in composer
$loader = new Loader();

$loader->registerDirs(array(
    ROOT_PATH,
    PATH_MODELS
));

$loader->registerClasses([
    'Services' => ROOT_PATH . '/../app/Services.php'
]);

$loader->registerNamespaces(array(
    'Phalcon' => PATH_INCUBATOR . 'Library/Phalcon/',
    'App\Helpers' => PATH_HELPER,
    'App\Library' => PATH_LIBRARY,
    'App\Forms' => PATH_FORMS,
    'App\Services' => PATH_SERVICES,
));

$loader->register();

$config = new Ini(PATH_CONFIG);

$di = new FactoryDefault();

DI::reset();

// add any needed services to the DI here

DI::setDefault($di);