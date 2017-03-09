<?php


use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();


//Loading twig Template Engine
$app->register(new Silex\Provider\TwigServiceProvider(), array(
		'twig.path' => __DIR__.'/../views',
));
//Loading Assets for Twig
$app->register(new Silex\Provider\AssetServiceProvider(), array(
		'assets.version' => 'v1'
));

