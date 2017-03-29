<?php

namespace BlogWriter\DAO;

use Doctrine\DBAL\Connection;


abstract class DAO {
	
	/**
	 * Database connection
	 *
	 * @var \Doctrine\DBAL\Connection
	 */
	private $db;
	/**
	 * Constructor
	 *
	 * @param \Doctrine\DBAL\Connection The database connection object
	 */
	public function __construct(Connection $db)
	{
		$this->db = $db;
	}
	/**
	 * Grants access to the database connection object
	 *
	 * @return \Doctrine\DBAL\Connection The database connection object
	 */
	protected function getDb()
	{
		return $this->db;
	}
	/**
	 * Builds a domain object from a DB row.
	 * Will be defined in each child class, please refer to them.
	 */
	protected abstract function buildDomainObject(array $row);
	
	/**
	 * Return a usable slug
	 * ###########################################################################
	 * ######################## MORE INFO ON GITHUB ##############################
	 * ###########https://github.com/SebUndefined/StringToSlugExplained###########
	 * ###########################################################################
	 * @param string $string
	 * @return string
	 */
	protected function cleanString($string)
	{
		$string = htmlentities($string, ENT_QUOTES, 'UTF-8');
		$string = preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|copy|th|tilde|uml);~i', '$1', $string);
		$string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
		$string = preg_replace('~[^0-9a-z]+~i', '-', $string);
		$string = trim($string, '-');
		$string = strtolower($string);
		return $string;
	}
	/**
	 * 
	 * @param string $futureSlug
	 * @param array $data
	 * @param integer $objectId
	 * @return string
	 */
	protected function validOrAdaptSlug($futureSlug, $data, $objectId = null)
	{
		//the slug is wrong at the beginning
		$isValid = false;
		//index
		$i = 1;
		//Temporary slug
		$tempSlug = $futureSlug;
		while ($isValid == false)
		{
			foreach ($data as $row)
			{
				//if the objectID is different to the current object, we adapt it
				if ($objectId !== $row->getId())
				{
					//if the temp slug is equal to the current slug, we adapt it by adding an index
					if ($tempSlug == $row->getSlug())
					{
						$tempSlug = $row->getSlug() . '-' . $i;
						$isValid = false;
						$i++;
					}
					else
					{
						$isValid = true;
					}
				}
				else 
				{
					$isValid = true;
				}
				
			}
		}
		if ($isValid)
		{
			$futureSlug = $tempSlug;
		}
		return $futureSlug;
		
	}
	
}