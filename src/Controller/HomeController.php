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
 		$articles = $app['dao.article']->findAll(6, true);
 		$categories = $app['dao.category']->findAll(6);
		return $app['twig']->render('index.html.twig', array('articles' => $articles, 'categories' => $categories));
	}
	/**
	 * Show the article per pages
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
	/**
	 * Show all the category with the number of article
	 * @param Application $app
	 * @return unknown
	 */
	public function categoriesIndexAction(Application $app)
	{
		$categories = $app['dao.category']->findCategories();
		$articles = $app['dao.article']->findAll(6, true);
		return $app['twig']->render('categories.all.html.twig', array(
				'categories' => $categories,
				'articles' =>$articles,
		));
	}
	/**
	 * Show the article depending of a specific category
	 * @param string $slug
	 * @param Application $app
	 * @return unknown
	 */
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
		$report = new Reporting();
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
				));
	}
	/**
	 * Add a comment 
	 * @param integer $idParent
	 * @param integer $idArticle
	 * @param Request $request
	 * @param Application $app
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function addCommentAction($idParent, $idArticle, Request $request, Application $app)
	{
		$comment = new Comment();
		$article = $app['dao.article']->findById($idArticle);
		$comment->setArticle($article);
		if ($idParent !== 'null')
		{
			$commentParent = $app['dao.comment']->findById($idParent);
			$comment->setLevel($commentParent->getLevel()+1);
			$comment->setParent($commentParent);
			$comment->setContent($request->get('answerComment'. $idParent));
		}
		else 
		{
			$comment->setContent($request->get('RootComment'));
			$comment->setLevel(1);
		}
		//Check if comment is not too long
		if (strlen($comment->getContent()) > 500)
		{
			$app['session']->getFlashBag()->add('error', 'Votre commentaire est trop long...');
		}
		else
		{
			$app['dao.comment']->save($comment);
			$app['session']->getFlashBag()->add('successReport', 'Votre commentaire est posté, merci de votre participation ! ');
			
		}
		return $app->redirect($app['url_generator']->generate('article', array('slug' => $article->getSlug())));
		
	}
	/**
	 * Contact page controller.
	 *
	 * @param Application $app Silex application retourne les éléments pertinents pour la vue
	 */
	public function contactAction(Application $app) {
		return $app['twig']->render('contact.html.twig');
	}
	
	
}