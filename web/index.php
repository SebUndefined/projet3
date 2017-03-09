<?php
//Looking for the Autoloader of Composer
require_once __DIR__.'/../vendor/autoload.php';

//Create the app
$app = new Silex\Application();

require __DIR__.'/../app/config/dev.php';
//require __DIR__.'/../app/config/prod.php'; // Config for production


require __DIR__.'/../app/app.php';
require __DIR__.'/../app/routes.php';

$app->run();