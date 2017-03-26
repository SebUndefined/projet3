<?php 

namespace BlogWriter\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use BlogWriter\Domain\Reporting;
use BlogWriter\Form\Type\ReportingType;
use BlogWriter\Domain\Comment;
use BlogWriter\Form\Type\CommentType;
//test
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
class HomeController 
{
	
	/**
	 * Home page controller.
	 *
	 * @param Application $app Silex application retourne les éléments pertinents pour la vue
	 */
	public function indexAction(Application $app) {
 		$articles = $app['dao.article']->findLast();
 		$categories = $app['dao.category']->findAll(6);
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
		$messagesPerPage = 5;
		$numberOfArticles = $app['dao.article']->countArticles();
		$NumberOfPage = ceil($numberOfArticles / $messagesPerPage);
		
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
		$categories = $app['dao.category']->findAll(6);
		
		return $app['twig']->render('articles.all.html.twig', array(
				'articles' => $articlesOfPage, 
				'categories' => $categories,
				'numberOfPage' => $NumberOfPage,
				'currentPage' => $currentPage,
				'paramPage' => $page,
		));
		
		
	}
	public function categoriesIndexAction(Application $app)
	{
		$categories = $app['dao.category']->findCategories();
		$articles = $app['dao.article']->findLast();
		return $app['twig']->render('categories.all.html.twig', array(
				'categories' => $categories,
				'articles' =>$articles,
		));
	}
	
	public function categoryAction($slug, Application $app)
	{
		$category = $app['dao.category']->findBySlug($slug);
		$articles = $app['dao.article']->findByCategory($slug);
		$categories = $app['dao.category']->findAll(6);
		
		return $app['twig']->render('category.html.twig', array(
				'category' => $category,
				'articles' => $articles,
				'categories' => $categories
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
		$categories = $app['dao.category']->findAll(6);
		$comment = new Comment();
		$comment->setArticle($article);
		$commentForm = $app['form.factory']->create(CommentType::class, $comment);
		$commentForm->handleRequest($request);
		if ($commentForm->isSubmitted() && $commentForm->isValid()) {
			if (strlen($comment->getContent()) > 500 || strlen($comment->getContent()) < 0)
			{
				$app['session']->getFlashBag()->add('error', 'Votre commentaire est invalide.');
			}
			else 
			{
				$app['dao.comment']->save($comment);
				$app['session']->getFlashBag()->add('success', 'Votre commentaire a bien été posté, merci !!');
				return $app->redirect($request->getRequestUri());
			}
			
		}
		$commentFormView = $commentForm->createView();
		$report = new Reporting();
		//test
		//$testForm = $app['form.factory']->createBuilder(FormType::class)->add('comment', TextareaType::class)->getForm();
		//endTest
		$reportingForm = $app['form.factory']->create(ReportingType::class, $report);
		$reportingForm->handleRequest($request);
		if ($reportingForm->isSubmitted() && $reportingForm->isValid()) {
			$app['dao.reporting']->save($report);
			$app['session']->getFlashBag()->add('successReport', 'Votre signalement a bien été pris en compte, merci pour votre coopération !');
			return $app->redirect($request->getRequestUri());
			
		}
		$reportinFormView = $reportingForm->createView();
		$comments = $app['dao.comment']->findAllByArticle($article->getId());
// 		die(var_dump($comments));
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
	
	
}