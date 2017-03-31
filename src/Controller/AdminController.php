<?php 

namespace BlogWriter\Controller;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use BlogWriter\Domain\Category;
use BlogWriter\Form\Type\CategoryType;
use BlogWriter\Domain\Article;
use BlogWriter\Form\Type\ArticleType;
use BlogWriter\Domain\User;
use BlogWriter\Form\Type\UserType;

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
	public function deleteCategoryAction(Application $app, $id)
	{
		//The user is not allowed to delete the default category
		if ($id == 1) 
		{
			$app['session']->getFlashBag()->add('error', 'Désolé, impossible de supprimer la catégorie par défaut');
			return $app->redirect($app['url_generator']->generate('manager_category'));
		}
		else 
		{
			//Before deleting the categorie, we move the articles in the default one
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
	
	public function adminArticleAction(Application $app)
	{
		$articles = $app['dao.article']->findAll(null, false);
		return $app['twig']->render('admin.article.html.twig', array(
				'title' => 'Vos articles',
				'articles' => $articles,
		));
	}
	/**
	 * Add the article to the database by using the save function from ArticleDAO
	 * @param Request $request
	 * @param Application $app
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|unknown
	 */
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
			//We set the img header
			if ($article->getImg() == null)
			{
				$article->setImg(null);
			}
			else
			{
				$article = $this->setImgHeader($article, $app);
			}
			
			
			$id = $app['dao.article']->save($article);
			$app['session']->getFlashBag()->add('success', 'L\'article a bien été ajouté !');
			return $app->redirect($app['url_generator']->generate('manager_article_edit', array('id' => $id)));
			
		}
		return $app['twig']->render('admin.article_form.html.twig', array(
				'title' => 'Nouvel Article',
				'articleForm' => $articleForm->createView(),
		));
	}
	public function editArticleAction(Request $request, Application $app, $id)
	{	
		$article = $app['dao.article']->findById($id);
		//
		if ($article->getPublished() == 1){
			$article->setPublished(true);
		}
		else 
		{
			$article->setPublished(false);
		}
			
		$categories = $app['dao.category']->findAll();
		$users = $app['dao.user']->findAll();
		$articleImg = $article->getImg();
		$articleForm = $app['form.factory']->create(ArticleType::class, $article, array(
				'categories' => $categories,
				'users' => $users
		));
		$articleForm->handleRequest($request);
		if ($articleForm->isSubmitted() && $articleForm->isValid())
		{
			//If the picture is not the default one
			if ($articleImg !== './assets/images/default.jpg')
			{
				//and if it has been changed by the user, we adapt it !
				if ($article->getImg() !== null)
				{
					$article = $this->setImgHeader($article, $app);
				}
				//otherwise we put the same uploaded picture
				else 
				{
					$article->setImg($articleImg);
				}
			}
			//else if the pic is the default one and if it has been change by the user we upload the new pic
			elseif ($article->getImg() !== null)
			{
				$article = $this->setImgHeader($article, $app);
			}
			//Otherwise, we leave the default picture
			else 
			{
				$article->setImg($articleImg);
			}
			$app['dao.article']->save($article);
			$app['session']->getFlashBag()->add('success', 'L\'article a bien été mise à jour !');
		}
		return $app['twig']->render('admin.article_form.html.twig', array(
				'title' => 'Editer l\'article',
				'articleForm' => $articleForm->createView(),
		));
	}
	public function deleteArticleAction(Application $app, $id)
	{
		$app['dao.article']->delete($id);
		$app['session']->getFlashBag()->add('success', 'l\'article est bien supprimé');
		return $app->redirect($app['url_generator']->generate('manager_article'));
	}
	/**
	 * Manage the article img header upload// Separated only for lisibility
	 * @param Article $article
	 * @param Application $app
	 */
	private function setImgHeader(Article $article, Application $app) {
		$img = $article->getImg();
		$messageUser = $app['dao.file']->uploadable($img, array('jpeg', 'png'));
		//If the file is not uploadable, we set the pic to the default one and return a message in session var
		if ($messageUser !== true)
		{
			$app['session']->getFlashBag()->add($messageUser[0], $messageUser[1]);
			$article->setImg('./assets/images/default.jpg');
		}
		else {
			$newWidth = 750;
			$maxHeight = 700;
			$messageUser = $app['dao.file']->checkImageDimension($img, $newWidth, $maxHeight);
			//If the table has the newHeight row
			//the picture fit to the dimension and we save it
			if (array_key_exists('newHeight', $messageUser))
			{
				$filename = $app['dao.file']->uploadFile($img, IMAGES, $newWidth, $messageUser['newHeight']);
				if ($filename) {
					$article->setImg('./assets/images/' . $filename);
					return $article;
				}
					
			}
			//Otherwise we set the pic to default
			else
			{
				$app['session']->getFlashBag()->add($messageUser[0], $messageUser[1]);
				$article->setImg('./assets/images/default.jpg');
			}
		}
		return $article;
		
	}
	//##########################################################################
	//##################### User management ####################################
	//##########################################################################
	public function adminUserAction(Application $app)
	{
		$users = $app['dao.user']->findAll();
		return $app['twig']->render('admin.user.html.twig', array(
				'title' => 'Tous les articles',
				'users' => $users,
		));
	}
	
	public function addUserAction(Application $app, Request $request)
	{
		$user = new User();
		$userForm = $app['form.factory']->create(UserType::class, $user);
		$userForm->handleRequest($request);
		
		if ($userForm->isSubmitted() && $userForm->isValid())
		{
			$isUserUnique = $app['dao.user']->isUserUnique($user);
			$isEmailUnique = $app['dao.user']->isEmailUnique($user);
			if ($isUserUnique)
			{
				if ($isEmailUnique)
				{
					$userSalt = substr(md5(time()),0, 23);
					$user->setSalt($userSalt);
					$clearPassword = $user->getPassword();
					$encoder = $app['security.encoder.bcrypt'];
					$encodedPassword = $encoder->encodePassword($clearPassword, $user->getSalt());
					$user->setPassword($encodedPassword);
					$id = $app['dao.user']->save($user);
					$app['session']->getFlashBag()->add('success', 'L\'utilisateur à bien été ajouté');
					return $app->redirect($id . '/edit');
				}
				else {
					$app['session']->getFlashBag()->add('error', 'Email déjà utilisé...');
				}
			}
			else 
			{
				$app['session']->getFlashBag()->add('error', 'Nom d\'utilisateur déjà utilisé');
			}
			
		}
		return $app['twig']->render('admin.user_form.html.twig', array(
			'title'=> 'Nouvel Utilisateur',
			'userForm' => $userForm->createView(),
		));
	}
	public function editUserAction($id, Application $app, Request $request)
	{
		$user = $app['dao.user']->find($id);
		$userForm = $app['form.factory']->create(UserType::class, $user);
		$userForm->handleRequest($request);
	
		if ($userForm->isSubmitted() && $userForm->isValid())
		{
			$isUserUnique = $app['dao.user']->isUserUnique($user);
			$isEmailUnique = $app['dao.user']->isEmailUnique($user);
			if ($isUserUnique)
			{
				if ($isEmailUnique)
				{
					$userSalt = substr(md5(time()),0, 23);
					$user->setSalt($userSalt);
					$clearPassword = $user->getPassword();
					$encoder = $app['security.encoder.bcrypt'];
					$encodedPassword = $encoder->encodePassword($clearPassword, $user->getSalt());
					$user->setPassword($encodedPassword);
					$app['dao.user']->save($user);
					$app['session']->getFlashBag()->add('success', 'L\'utilisateur à bien été mis à jour!');
				}
				else {
					$app['session']->getFlashBag()->add('error', 'Email déjà utilisé...');
				}
			}
			else
			{
				$app['session']->getFlashBag()->add('error', 'Nom d\'utilisateur déjà utilisé');
			}
				
		}
		return $app['twig']->render('admin.user_form.html.twig', array(
				'title'=> 'Editer l\'utilisateur',
				'userForm' => $userForm->createView(),
		));
	}
	
}