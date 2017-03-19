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
	/**
	 * Returns an article matching the supplied slug.
	 *
	 * @param integer $id
	 *
	 * @return \BlogWriter\Domain\Article|throws an exception if no matching article is found
	 */
	public function findBySlug($slug) {
		$sql = "select * from Articles where art_slug=?";
		$row = $this->getDb()->fetchAssoc($sql, array($slug));
	
		if ($row)
			return $this->buildDomainObject($row);
			else
				throw new \Exception("No article with slug " . $slug);
	}
	/**
	 * Returns all articles matching the supplied slug.
	 *
	 * @param string $slug
	 *
	 * @return \BlogWriter\Domain\Article|throws an exception if no matching article is found
	 */
	public function findByCategory($slug) {
		$sql = "SELECT `Articles`.*, `Categories`.cat_id FROM `Articles` 
				LEFT JOIN `Categories` ON `Articles`.`art_category_id` = `Categories`.`cat_id` 
				WHERE Categories.cat_slug = ? ";
		$result = $this->getDb()->fetchAll($sql, array($slug));
		$articles = array();
		//die(var_dump($result));
		foreach ($result as $row) {
			$articleId = $row['art_id'];
			$articles[$articleId] = $this->buildDomainObject($row);
		}
		return $articles;
	}
	
	
	/**
	 * Returns an article matching the supplied id.
	 *
	 * @param integer $id
	 *
	 * @return \BlogWriter\Domain\Article|throws an exception if no matching article is found
	 */
	public function findById($id) {
		$sql = "select * from Articles where art_id=?";
		$row = $this->getDb()->fetchAssoc($sql, array($id));
	
		if ($row)
			return $this->buildDomainObject($row);
			else
				throw new \Exception("No article with slug " . $id);
	}
	
	public function findPerPage($firstEntry, $messagesPerPages)
	{
		$sql = 'SELECT * FROM Articles ORDER BY art_id DESC LIMIT :firstEntry, :messagesPerPages';
		$stmt = $this->getDb()->prepare($sql);
		$stmt->bindValue("firstEntry", $firstEntry, "integer");
		$stmt->bindValue("messagesPerPages", $messagesPerPages, "integer");
		$stmt->execute();
		$result = $stmt->fetchAll();
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
	public function setUserDAO(UserDAO $userDAO) {
		$this->userDAO = $userDAO;
	}
	
	public function countArticles()
	{
		$sql = 'SELECT COUNT(*) AS total FROM Articles';
		$articleCounter = $this->getDb()->query($sql);
		$nbArticles = $articleCounter->fetch();
		return $nbArticles['total'];
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
		$authorID = $row['art_author_id'];
		$author = $this->userDAO->find($authorID);
		$article->setAuthor($author);
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

