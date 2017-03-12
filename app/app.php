<?php


use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;
use BlogWriter\DAO\CategoryDAO;
use BlogWriter\DAO\ArticleDAO;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

//##########################################################################
//##################### Loading Services providers##########################
//##########################################################################
//Loading Doctrine
$app->register(new Silex\Provider\DoctrineServiceProvider());
//Loading twig Template Engine
$app->register(new Silex\Provider\TwigServiceProvider(), array(
		'twig.path' => __DIR__.'/../views',
));
//Loading Assets for Twig
$app->register(new Silex\Provider\AssetServiceProvider(), array(
		'assets.version' => 'v1'
));

//##########################################################################
//##################### Loading APP Services ###############################
//##########################################################################

$app['dao.article'] = function ($app) {
	$articleDAO = new ArticleDAO($app['db']);
	$articleDAO->setCategoryDAO($app['dao.category']);
	return $articleDAO;
};

$app['dao.category'] = function ($app) {
	return new CategoryDAO($app['db']);
};