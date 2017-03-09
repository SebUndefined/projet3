<?php 

namespace BlogWriter\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;


class HomeController 
{
	
	/**
	 * Home page controller.
	 *
	 * @param Application $app Silex application retourne les éléments pertinents pour la vue
	 */
	public function indexAction(Application $app) {
// 		$articles = $app['dao.article']->findAll();
// 		return $app['twig']->render('index.html.twig', array('articles' => $articles));
		return $app['twig']->render('index.html.twig');
	}
	
	/**
	 * Contact page controller.
	 *
	 * @param Application $app Silex application retourne les éléments pertinents pour la vue
	 */
	public function contactAction(Application $app) {
		// 		$articles = $app['dao.article']->findAll();
		// 		return $app['twig']->render('index.html.twig', array('articles' => $articles));
		return $app['twig']->render('contact.html.twig');
	}
	
	
}