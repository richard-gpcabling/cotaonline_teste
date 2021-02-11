<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */

$loader->registerDirs([
    APP_PATH . $config->application->controllersDir,
    APP_PATH . $config->application->pluginsDir,
    APP_PATH . $config->application->libraryDir,
    APP_PATH . $config->application->modelsDir,
    APP_PATH . $config->application->servicesDir,
    APP_PATH . $config->application->formsDir
]);

$loader->registerClasses([
    'Services' => APP_PATH . 'app/Services.php'
]);


$loader->registerNamespaces([
    'App\Helpers' => APP_PATH . 'app/helpers',
    'App\Services' => APP_PATH . 'app/services',
    'App\Forms' => APP_PATH . 'app/forms',
    'App\Forms\Validator' => APP_PATH . 'app/forms/validator',
    'App\Library' => APP_PATH . 'app/library',
    'App\Controllers' => APP_PATH . $config->application->controllersDir
]);

$loader->register();