<?php

namespace BlogWriter\DAO;

use BlogWriter\Domain\Category;

class CategoryDAO extends DAO
{
	public function findRandom()
	{
		$sql = "SELECT * FROM Categories ORDER BY RAND() LIMIT 6";
		$result = $this->getDb()->fetchAll($sql);
		
		// Convert query result to an array of domain objects
		$categories = array();
		foreach ($result as $row) {
			$categoryId = $row['cat_id'];
			$categories[$categoryId] = $this->buildDomainObject($row);
		}
		return $categories;
	}
	public function find($idCategory)
	{
		$sql = "select * from Categories where cat_id=?";
		$row = $this->getDb()->fetchAssoc($sql, array($idCategory));
		
		if ($row)
			return $this->buildDomainObject($row);
			else
				throw new \Exception("Pas de catégorie portant le numéro " . $id);
	}
	
	
	
	protected function buildDomainObject(array $row)
	{
		$category = new Category();
		$category->setId($row['cat_id']);
		$category->setName($row['cat_name']);
		$category->setSlug($row['cat_slug']);
		return $category;
	}
}