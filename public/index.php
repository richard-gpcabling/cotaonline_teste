<?php

error_reporting(E_ALL); // all enabled
//error_reporting(0); // disable all

use Phalcon\Mvc\Application;
use Phalcon\Config\Adapter\Ini as ConfigIni;

try {
    define('APP_PATH', realpath('..') . '/');

    /**
     * Read the configuration
     */
    $config = new ConfigIni(APP_PATH . 'app/config/config.ini');
    if (is_readable(APP_PATH . 'app/config/config.ini.dev')) {
        $override = new ConfigIni(APP_PATH . 'app/config/config.ini.dev');
        $config->merge($override);
    }

    /**
     * Auto-loader configuration
     */
    require APP_PATH . 'app/config/loader.php';
    require_once APP_PATH . 'vendor/autoload.php'; // mpdf

    //include APP_PATH . 'vendor/mpdf/mpdf.php';

    $application = new Application(new Services($config));

    // NGINX - PHP-FPM already set PATH_INFO variable to handle route
    echo $application->handle(!empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : null)->getContent();
} catch (Exception $e) {
    if (isset($_SERVER['CO_ENVIRONMENT']) && $_SERVER['CO_ENVIRONMENT'] === 'development') {
        echo get_class($e), ": ", $e->getMessage(), "\n";
        echo " File=", $e->getFile(), "\n";
        echo " Line=", $e->getLine(), "\n";
        echo $e->getTraceAsString();
    }
}
