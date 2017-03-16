<?php 

namespace BlogWriter\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use BlogWriter\Domain\Reporting;
use BlogWriter\Form\Type\ReportingType;
use BlogWriter\Domain\Comment;
use BlogWriter\Form\Type\CommentType;


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
	/**
	 * 
	 * @param number $page
	 * @param Application $app
	 * @return unknown
	 */
	public function ArticleIndexAction($page = 1, Application $app)
	{
		$messagesPerPage = 3;
		$numberOfArticles = $app['dao.article']->countArticles();
		$NumberOfPage = ceil($numberOfArticles['total'] / $messagesPerPage);
		
		if (isset($page))
		{
			$currentPage = intval($page);
			if ($currentPage>$NumberOfPage)
			{
				$currentPage = $NumberOfPage;
			}
		}
		else 
		{
			$currentPage = 1;
		}
		$firstEntry = ($currentPage - 1) * $messagesPerPage;
		$articlesOfPage = $app['dao.article']->findPerPage($firstEntry, $messagesPerPage);
		$categories = $app['dao.category']->findRandom();
		
		return $app['twig']->render('articles.all.html.twig', array(
				'articles' => $articlesOfPage, 
				'categories' => $categories,
				'numberOfPage' => $NumberOfPage,
				'currentPage' => $currentPage,
				'paramPage' => $page,
		));
		
		
	}
	
	
	/**
	 * 
	 * @param string $slug
	 * @param Request $request
	 * @param Application $app
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|unknown
	 */
	public function articleAction($slug, Request $request, Application $app) 
	{
		$article = $app['dao.article']->findBySlug($slug);
		$categories = $app['dao.category']->findRandom();
		$comment = new Comment();
		$comment->setArticle($article);
		$commentForm = $app['form.factory']->create(CommentType::class, $comment);
		$commentForm->handleRequest($request);
		if ($commentForm->isSubmitted() && $commentForm->isValid()) {
			$app['dao.comment']->save($comment);
			$app['session']->getFlashBag()->add('success', 'Votre commentaire a bien été posté, merci !!');
			return $app->redirect($request->getRequestUri());
		}
		$commentFormView = $commentForm->createView();
		$report = new Reporting();
// 		$report->setComment($commentTTT);
		$reportingForm = $app['form.factory']->create(ReportingType::class, $report);
		$reportingForm->handleRequest($request);
		if ($reportingForm->isSubmitted() && $reportingForm->isValid()) {
			$app['dao.reporting']->save($report);
			$app['session']->getFlashBag()->add('successReport', 'Votre signalement a bien été pris en compte, merci pour votre coopération !');
			return $app->redirect($request->getRequestUri());
			
		}
		$reportinFormView = $reportingForm->createView();
		$comments = $app['dao.comment']->findAllByArticle($article->getId());
		return $app['twig']->render('article.html.twig', array(
				'article' => $article,
				'categories' =>$categories,
				'comments' => $comments,
				'reportingForm' =>$reportinFormView,
				'commentForm' => $commentFormView,
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
	public function redirect($url)
	{
		header('Location: http://www.example.com/');
	}
	
}