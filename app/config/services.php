<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Manager as SessionManager;
use Phalcon\Session\Adapter\Stream as SessionAdapter;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Html\Escaper;
use Phalcon\Mvc\Dispatcher;

$di = new FactoryDefault();

$di->setShared('config', function () {
    return require APP_PATH . '/app/config/config.php';
});

$di->setShared('url', function () {
    $config = $this->getConfig();
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);
    return $url;
});

$di->setShared('view', function () {
    $config = $this->getConfig();
    $view = new View();
    $view->setViewsDir($config->application->viewsDir);

    $view->disableLevel(View::LEVEL_LAYOUT);

    $view->registerEngines([
        '.volt' => function ($view) {
            $volt = new VoltEngine($view, $this);
            $volt->setOptions([
                'path'      => sys_get_temp_dir() . '/',
                'separator' => '_',
                'always'    => true, // set false saat production
            ]);
            return $volt;
        },
    ]);

    return $view;
});

$di->setShared('db', function () {
    $config = $this->getConfig();
    $dbConfig = $config->database->toArray();
    unset($dbConfig['adapter']);
    return new DbAdapter($dbConfig);
});

$di->setShared('modelsManager', function () {
    return new ModelsManager();
});

$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

$di->setShared('session', function () {
    $session = new SessionManager();
    $session->setAdapter(new SessionAdapter());
    $session->start();
    return $session;
});

$di->setShared('flash', function () {
    $flash = new FlashSession(new Escaper());
    $flash->setCssClasses([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning',
    ]);
    return $flash;
});

$di->setShared('dispatcher', function () {
    $dispatcher = new Dispatcher();
    $dispatcher->setDefaultNamespace('');
    return $dispatcher;
});
