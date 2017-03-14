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
 		$articles = $app['dao.article']->findLast();
 		$categories = $app['dao.category']->findRandom();
		return $app['twig']->render('index.html.twig', array('articles' => $articles, 'categories' => $categories));
	}
	public function articleAction($slug, Request $request, Application $app) 
	{
		$article = $app['dao.article']->findBySlug($slug);
		$categories = $app['dao.category']->findRandom();
		$comments = $app['dao.comment']->findAllByArticle($article->getId());
		return $app['twig']->render('article.html.twig', array(
				'article' => $article,
				'categories' =>$categories,
				'comments' => $comments,
				));
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