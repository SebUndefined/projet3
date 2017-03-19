<?php 

namespace BlogWriter\Controller;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

class AdminController {

	public function loginIndex (Request $request, Application $app)
	{
		return $app['twig']->render('login.html.twig', array(
			'error' => $app['security.last_error']($request),
			'last_username' =>$app['session']->get('_security.last_username'),
		));
	}
	
	public function adminIndex(Application $app){
		
		$nbArticles = $app['dao.article']->countArticles();
		$nbCategories = $app['dao.category']->countCategories();
		$nbComments = $app['dao.comment']->countComments();
		$nbReportings = $app['dao.reporting']->countReportings();
		$comments = $app['dao.comment']->findAll();
		return $app['twig']->render('admin.index.html.twig', array(
				"nbArticles" => $nbArticles,
				'nbCategories' => $nbCategories,
				'nbComments' => $nbComments,
				'nbReportings' => $nbReportings,
				'comments' => $comments,
		));
	}
	
}