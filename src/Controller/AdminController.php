<?php 

namespace BlogWriter\Controller;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use BlogWriter\Domain\Category;
use BlogWriter\Form\Type\CategoryType;

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
	
	public function addCategoryAction(Request $request, Application $app)
	{
		$category = new Category();
		$categoryForm = $app['form.factory']->create(CategoryType::class, $category);
		$categoryForm->handleRequest($request);
		if ($categoryForm->isSubmitted() && $categoryForm->isValid())
		{
			$app['dao.category']->save($category);
			$app['session']->getFlashBag()->add('success', 'La catégorie a bien été ajouté !');
		}
		return $app['twig']->render('admin.category_form.html.twig', array(
				'title' => 'Nouvelle catégorie',
				'categoryForm' => $categoryForm->createView(),
		));
	}
	
}