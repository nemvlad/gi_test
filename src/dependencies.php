<?php
// DIC configuration

$container = $app->getContainer();

// monolog
$container['logger'] = function (Psr\container\ContainerInterface $c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};


$container['db'] = function ($c) {
    $dsn = 'mysql:host='.$c['settings']['db']['host'].';dbname='.$c['settings']['db']['database'].';charset=utf8';
    $usr = $c['settings']['db']['username'];
    $pwd = $c['settings']['db']['password'];
    $pdo = new \Slim\PDO\Database($dsn, $usr, $pwd);
    return $pdo;
};

$container['App\controllers\UserController'] = function ($c) {
    return new App\controllers\UserController($c);
};
