<?php

require __DIR__."/../vendor/autoload.php";

session_start();

$settings = require __DIR__.'/../app/config/config.php';

$di = new SlimAura\Container($settings);
$app = new \Slim\App($di);

require __DIR__.'/../app/config/dependencies.php';
require __DIR__.'/../app/config/routes.php';

$app->run();