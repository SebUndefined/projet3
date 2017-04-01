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
	 * 
	 * @param integer $limit
	 * @param string $hideDisabled Allow you to show or hide the disabled articles
	 * @return \BlogWriter\Domain\Article[]
	 */
	public function findAll($limit = null, $hideDisabled = true)
	{
		$sql = "SELECT * FROM Articles";
		if ($hideDisabled === true)
		{
			$sql = $sql . " WHERE art_published = 1";
		}
		$sql = $sql . " ORDER BY art_create_date DESC";
		if ($limit != null){
			$sql = $sql . " LIMIT " . $limit;
		}
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
				throw new \Exception("Pas d'article avec le slug " . $slug);
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
				WHERE Categories.cat_slug = ? and Articles.art_published = 1";
		$result = $this->getDb()->fetchAll($sql, array($slug));
		$articles = array();
		foreach ($result as $row) {
			$articleId = $row['art_id'];
			$articles[$articleId] = $this->buildDomainObject($row);
		}
		return $articles;
	}
	/**
	 * Return all the articles matching the supplied category id, useful for replacing the article in the default one
	 * @param integer $id
	 * @return \BlogWriter\Domain\Article[]
	 */
	public function findByCategoryId($id) {
		$sql = "SELECT * FROM Articles WHERE art_category_id = ?";
		$result = $this->getDb()->fetchAll($sql, array($id));
		$articles = array();
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
				throw new \Exception("Pas d\'article avec l\'id " . $id);
	}
	
	public function changeToDefaultCategory($catId)
	{
		$articles = $this->findByCategoryId($catId);
		foreach ($articles as $article)
		{
			$this->getDb()->update('Articles', array('art_category_id' => 1), array('art_id' => $article->getId()));
		}
		
	}
	
	/**
	 * 
	 * @param integer $firstEntry
	 * @param integer $messagesPerPages
	 * @return \BlogWriter\Domain\Article[]
	 */
	public function findPerPage($firstEntry, $messagesPerPages)
	{
		$sql = 'SELECT * FROM Articles WHERE Articles.art_published = 1 ORDER BY art_id DESC LIMIT :firstEntry, :messagesPerPages';
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
	public function save($article) 
	{
		$futureSlug = $this->cleanString($article->getTitle());
		$articles = $this->findAll();
		if ($article->getId() !== null)
		{
			$validSlug = $this->validOrAdaptSlug($futureSlug, $articles, $article->getId());
		}
		else
		{
			$validSlug = $this->validOrAdaptSlug($futureSlug, $articles);
		}
		$article->setSlug($validSlug);
		$articleData = array(
				'art_title' => $article->getTitle(),
				'art_update_date' => date('Y-m-d H:i:s'),
				'art_content' => $article->getContent(),
				'art_slug' => $article->getSlug(),
				'art_author_id' => $article->getAuthor()->getId(),
				'art_category_id' => $article->getCategorie()->getId(),
				'art_img' => $article->getImg(),
				
		);
		if ($article->getPublished() == false) 
		{
			$articleData['art_published'] = 0;
		}
		else 
		{
			$articleData['art_published'] = 1;
		}
		if ($article->getId())
		{
			//if the category has an id, we update it but before
			$this->getDb()->update('Articles', $articleData, array('art_id' => $article->getId()));
		}
		else
		{
			$articleData['art_create_date'] = date('Y-m-d H:i:s');
			$this->getDb()->insert('Articles', $articleData);
			$id = $this->getDb()->lastInsertId();
			$article->setId($id);
		}
		return $article->getId();
	}
	public function delete($id)
	{
		$row = $this->getDb()->delete('Articles', array('art_id' => $id));
		return $row;
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
		$article->setPublished($row['art_published']);
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

