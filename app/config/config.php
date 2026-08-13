<?php

use Phalcon\Config\Config;

function detectBaseUri(): string
{
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';

    $baseUri = str_replace('index.php', '', $scriptName);

    $baseUri = preg_replace('#public/?$#', '', $baseUri);

    if ($baseUri === '' || $baseUri === null) {
        $baseUri = '/';
    }

    $baseUri = '/' . trim($baseUri, '/');
    $baseUri = $baseUri === '/' ? '/' : $baseUri . '/';

    return $baseUri;
}

return new Config([
    'database' => [
        'adapter'  => 'Mysql',
        'host'     => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname'   => 'db_kampus',
        'charset'  => 'utf8mb4',
    ],
    'application' => [
        'appDir'         => APP_PATH . '/app/',
        'controllersDir' => APP_PATH . '/app/controllers/',
        'modelsDir'      => APP_PATH . '/app/models/',
        'viewsDir'       => APP_PATH . '/app/views/',
        'baseUri'        => detectBaseUri(),
    ],
]);
