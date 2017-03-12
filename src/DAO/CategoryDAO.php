<?php

namespace BlogWriter\DAO;

use BlogWriter\Domain\Category;

class CategoryDAO extends DAO
{
	
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