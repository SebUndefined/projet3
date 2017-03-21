<?php


use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;
use BlogWriter\DAO\CategoryDAO;
use BlogWriter\DAO\ArticleDAO;
use BlogWriter\DAO\UserDAO;
use BlogWriter\DAO\CommentDAO;
use BlogWriter\DAO\ReportingDAO;

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
$app->register(new Silex\Provider\ValidatorServiceProvider());
//Loading Assets for Twig
$app->register(new Silex\Provider\AssetServiceProvider(), array(
		'assets.version' => 'v1'
));
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
		'security.firewalls'=> array(
				'secured' => array(
						//which app part is secured ? Here, all the app
						'pattern' =>'^/',
						//Allow anonymous user to access the app
						'anonymous' => true,
						//Allow user to logout
						'logout'=> true,
						//Allow us to use a form for auth
						'form' => array(
								'login_path' => '/login', 
								'check_path' => '/login_check'
						),
						//Define the sources of the entity for auth, here, UserDAO
						'users' => function () use ($app)
											{
												return new UserDAO($app['db']);
											}
				),
		),
		'security.role.hierarchy' => array(
				'ROLE_ADMIN' => array('ROLE_USER'),
		),
		'security.access_rules' => array(
				array('^/manager', array('ROLE_USER', 'ROLE_ADMIN')),
		),
));
//Load Form service provider and components
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());
//##########################################################################
//##################### Loading APP Services ###############################
//##########################################################################

$app['dao.article'] = function ($app) {
	$articleDAO = new ArticleDAO($app['db']);
	$articleDAO->setCategoryDAO($app['dao.category']);
	$articleDAO->setUserDAO($app['dao.user']);
	return $articleDAO;
};

$app['dao.category'] = function ($app) {
	return new CategoryDAO($app['db']);
};

$app['dao.user'] = function ($app) {
	return new UserDAO($app['db']);
};
$app['dao.comment'] = function ($app) {
	$commentDAO = new CommentDAO($app['db']);
	$commentDAO->setArticleDAO($app['dao.article']);
	return $commentDAO;
};
$app['dao.reporting'] = function ($app) {
	$reportingDAO = new ReportingDAO($app['db']);
	$reportingDAO->setCommentDAO($app['dao.comment']);
	return $reportingDAO;
};