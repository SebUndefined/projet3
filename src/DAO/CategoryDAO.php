<?php

namespace BlogWriter\DAO;

use BlogWriter\Domain\Category;
use Symfony\Component\Validator\Constraints\Length;

class CategoryDAO extends DAO
{
	/**
	 * Choose 6 categories randomly in the database
	 * @return \BlogWriter\Domain\Category[]
	 */
	public function findAll($limit = null)
	{
		
		$sql = "SELECT * FROM Categories";
		if ($limit != null) {
			$sql = $sql . " ORDER BY RAND() LIMIT " . $limit;
		}
		$result = $this->getDb()->fetchAll($sql);
		
		// Convert query result to an array of domain objects
		$categories = array();
		foreach ($result as $row) {
			$categoryId = $row['cat_id'];
			$categories[$categoryId] = $this->buildDomainObject($row);
		}
		return $categories;
	}
	/**
	 * Return a specific category - Depending of the ID given 
	 * @param integer $idCategory
	 * @throws \Exception
	 * @return \BlogWriter\Domain\Category
	 */
	public function find($idCategory)
	{
		$sql = "select * from Categories where cat_id=?";
		$row = $this->getDb()->fetchAssoc($sql, array($idCategory));
		
		if ($row)
			return $this->buildDomainObject($row);
			else
				throw new \Exception("Pas de catégorie portant le numéro " . $id);
	}
	
	
	/**
	 * Find all category with the number of article for each of them
	 * @return \BlogWriter\Domain\Category[]
	 */
	public function findCategories()
	{
		$sql = 'SELECT c.cat_id, c.cat_name, c.cat_slug, COUNT(*) as nb_article 
				FROM Categories c 
				INNER JOIN Articles a ON a.art_category_id = c.cat_id 
				GROUP BY c.cat_id ';
		$result = $this->getDb()->fetchAll($sql);
		$categories = array();
		foreach ($result as $row) {
			$categoryId = $row['cat_id'];
			$categories[$categoryId] = $this->buildDomainObject($row);
		}
		return $categories;
		
	}
	/**
	 * Save a category in the Database, check before for the slug 
	 * @param Category $category
	 * 
	 */
	public function save(Category $category)
	{
		$futureSlug = $this->cleanString($category->getName());
		
		$categories = $this->findAll();
		
		$find = false;
		$i = 0;
		//To do a boucle
		die(var_dump($categories));
	}
	public function countCategories()
	{
		$sql = 'SELECT COUNT(*) AS total FROM Categories';
		$categoryCounter = $this->getDb()->query($sql);
		$nbcategories = $categoryCounter->fetch();
		return $nbcategories['total'];
	}
	
	protected function buildDomainObject(array $row)
	{
		$category = new Category();
		$category->setId($row['cat_id']);
		$category->setName($row['cat_name']);
		$category->setSlug($row['cat_slug']);
		if (array_key_exists('nb_article', $row)) {
			$nbArticle = $row['nb_article'];
			$category->setNbArticle($nbArticle);
		}
		return $category;
	}
}