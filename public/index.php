<?php

use Phalcon\Mvc\Application;
use Phalcon\Autoload\Loader;

error_reporting(E_ALL);
ini_set('display_errors', '1');

define('APP_PATH', dirname(__DIR__));

require APP_PATH . '/vendor/autoload.php';

try {

    $loader = new Loader();

    $loader->setDirectories([
        APP_PATH . '/app/controllers/',
        APP_PATH . '/app/models/',
    ])->register();

    require APP_PATH . '/app/config/services.php';

    $application = new Application($di);

    $uri = $_GET['_url'] ?? '/';

    $response = $application->handle($uri);

    $response->send();

} catch (\Throwable $e) {
    echo 'Exception: ', $e->getMessage(), ' pada file ', $e->getFile(), ' baris ', $e->getLine();
}
