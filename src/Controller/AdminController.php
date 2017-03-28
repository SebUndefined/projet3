<?php 

namespace BlogWriter\Controller;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use BlogWriter\Domain\Category;
use BlogWriter\Form\Type\CategoryType;
use BlogWriter\Domain\Article;
use BlogWriter\Form\Type\ArticleType;

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
		$reportings = $app['dao.reporting']->findAll();
		return $app['twig']->render('admin.index.html.twig', array(
				"nbArticles" => $nbArticles,
				'nbCategories' => $nbCategories,
				'nbComments' => $nbComments,
				'nbReportings' => $nbReportings,
				'comments' => $comments,
				'reportings' => $reportings,
		));
	}
	//##########################################################################
	//##################### Categories management###############################
	//##########################################################################
	public function adminCategoryAction(Application $app)
	{
		$categories = $app['dao.category']->findCategories();
		return $app['twig']->render('admin.category.html.twig', array(
				'title' => 'Gestionnaire de catégorie',
				'categories' => $categories,
		));
	}
	public function addCategoryAction(Request $request, Application $app)
	{
		$category = new Category();
		$categoryForm = $app['form.factory']->create(CategoryType::class, $category);
		$categoryForm->handleRequest($request);
		if ($categoryForm->isSubmitted() && $categoryForm->isValid())
		{
			$id = $app['dao.category']->save($category);
			$app['session']->getFlashBag()->add('success', 'La catégorie a bien été ajouté !');
			return $app->redirect($id . '/edit');
		}
		return $app['twig']->render('admin.category_form.html.twig', array(
				'title' => 'Nouvelle catégorie',
				'categoryForm' => $categoryForm->createView(),
		));
	}
	public function editCategoryAction(Request $request, Application $app, $id)
	{
		//The user is not allowed to modify the default category
		if ($id == 1)
		{
			$app['session']->getFlashBag()->add('error', 'Impossible d\'éditer la catégorie par défaut');
			return $app->redirect($app['url_generator']->generate('manager_category'));
		}
		$category = $app['dao.category']->find($id);
		$categoryForm = $app['form.factory']->create(CategoryType::class, $category);
		$categoryForm->handleRequest($request);
		if ($categoryForm->isSubmitted() && $categoryForm->isValid())
		{
			$app['dao.category']->save($category);
			$app['session']->getFlashBag()->add('success', 'La catégorie a bien été mise à jour !');
		}
		return $app['twig']->render('admin.category_form.html.twig', array(
				'title' => 'Editer la catégorie',
				'categoryForm' => $categoryForm->createView(),
		));
	}
	public function deleteCategoryAction(Request $request, Application $app, $id)
	{
		//The user is not allowed to delete the default category
		if ($id == 1) 
		{
			$app['session']->getFlashBag()->add('error', 'Désolé, impossible de supprimer la catégorie par défaut');
			return $app->redirect($app['url_generator']->generate('manager_category'));
		}
		else 
		{
			$app['dao.article']->changeToDefaultCategory($id);
			$app['dao.category']->delete($id);
			$app['session']->getFlashBag()->add('success', 'La catégorie a bien été supprimé, pensez à reclasser vos articles');
			return $app->redirect($app['url_generator']->generate('manager_category'));
		}
		
	}
	//##########################################################################
	//##################### Comments management ################################
	//##########################################################################
	
	public function deleteCommentAction(Request $request, Application $app, $id) {
		$app['dao.comment']->delete($id);
		$app['session']->getFlashBag()->add('success', 'Le commentaire a bien été supprimé');
		return $app->redirect($app['url_generator']->generate('manager_reporting'));
	}
	
	
	
	//##########################################################################
	//##################### Reportings management ##############################
	//##########################################################################
	public function adminReportingAction(Application $app)
	{
		$reportings = $app['dao.reporting']->findAll();
		return $app['twig']->render('admin.reporting.html.twig', array(
				'title' => 'Gestionnaire de signalement',
				'reportings' => $reportings,
		));
	}
	public function deleteReportingAction(Request $request, Application $app, $id, $commentId = null) {
		
		if ($commentId != null)
		{
			$this->deleteCommentAction($request, $app, $commentId);
			
		}
		else 
		{
			$app['dao.reporting']->delete($id);
			$app['session']->getFlashBag()->add('success', 'Le signalement a bien été supprimé');
		}
		return $app->redirect($app['url_generator']->generate('manager_reporting'));
	}
	//##########################################################################
	//##################### Article management ##############################
	//##########################################################################
	
	public function addArticleAction(Request $request, Application $app)
	{
		$categories = $app['dao.category']->findAll();
		$users = $app['dao.user']->findAll();
		$article = new Article();
		$articleForm = $app['form.factory']->create(ArticleType::class, $article, array(
				'categories' => $categories,
				'users' => $users
		));
		$articleForm->handleRequest($request);
		if ($articleForm->isSubmitted() && $articleForm->isValid())
		{

				
			if ($article->getImg() !== null)
			{
				$img = $article->getImg();
				$messageUser = $app['dao.file']->uploadable($img, array('jpeg', 'png'));
				if ($messageUser !== true)
				{
					$app['session']->getFlashBag()->add($messageUser[0], $messageUser[1]);
				}
				else {
					$newWidth = 750;
					$maxHeight = 700;
					$messageUser = $app['dao.file']->checkImageDimension($img, $newWidth, $maxHeight);
					if (array_key_exists('newHeight', $messageUser))
					{
						$filename = $app['dao.file']->uploadFile($img,IMAGES, $newWidth, $messageUser['newHeight']);
						if ($filename) {
							$article->setImg('./assets/images/' . $filename);
							
						}
							
					}
					else
					{
						$app['session']->getFlashBag()->add($messageUser[0], $messageUser[1]);
					}
				}
			}
			$id = $app['dao.article']->save($article);
			$app['session']->getFlashBag()->add('success', 'L\'article a bien été ajouté !');
			//return $app->redirect($id . '/edit');
			return $app->redirect($request->getRequestUri());
			
		}
		return $app['twig']->render('admin.article_form.html.twig', array(
				'title' => 'Nouvel Article',
				'articleForm' => $articleForm->createView(),
		));
	}
	public function editArticleAction(Request $request, Application $app, $id)
	{	
		$article = $app['dao.article']->findById($id);
		//A faire dans la consturction de l'objet
		if ($article->getPublished() == 1){
			$article->setPublished(true);
		}
		else 
		{
			$article->setPublished(false);
		}
			
		$categories = $app['dao.category']->findAll();
		$users = $app['dao.user']->findAll();
		$articleForm = $app['form.factory']->create(ArticleType::class, $article, array(
				'categories' => $categories,
				'users' => $users
		));
		$articleForm->handleRequest($request);
		if ($articleForm->isSubmitted() && $articleForm->isValid())
		{
			
			$app['dao.article']->save($article);
			$app['session']->getFlashBag()->add('success', 'La catégorie a bien été mise à jour !');
		}
		return $app['twig']->render('admin.article_form.html.twig', array(
				'title' => 'Editer l\'article',
				'articleForm' => $articleForm->createView(),
		));
	}
	
	
	
	
}