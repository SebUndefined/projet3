<?php

namespace BlogWriter\DAO;

use BlogWriter\Domain\Article;


class ArticleDAO extends DAO
{
	private $categoryDAO;
	private $userDAO;
	
	
	
	public function findLast()
	{
		$sql = "SELECT * FROM Articles ORDER BY art_create_date DESC LIMIT 6";
		$result = $this->getDb()->fetchAll($sql);
		
		// Convert query result to an array of domain objects
		$articles = array();
		foreach ($result as $row) {
			$articleId = $row['art_id'];
			$articles[$articleId] = $this->buildDomainObject($row);
		}
		return $articles;
	}
	
	public function setCategoryDAO(CategoryDAO $categoryDAO) {
		$this->categoryDAO = $categoryDAO;
	}
	
	protected function buildDomainObject(array $row)
	{
		$article = new Article();
		$article->setId($row['art_id']);
		$article->setCreateDate($row['art_create_date']);
		$article->setUpdateDate($row['art_update_date']);
		$article->setTitle($row['art_title']);
		$article->setContent($row['art_content']);
		$article->setSlug($row['art_slug']);
		
		$categoryID = $row['art_category_id'];
		$category = $this->categoryDAO->find($categoryID);
		$article->setCategorie($category);
		//Missing some settings
		if (!$row['art_img'])
		{
			$article->setImg('./assets/images/default.jpg');
		}
		else
		{
			$article->setImg($row['art_img']);
		}
		return $article;
		
		
	}
}

