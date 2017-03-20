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
	
	protected function cleanString($string)
	{
		return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
		return $string;
	}
	
}