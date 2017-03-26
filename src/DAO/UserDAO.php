<?php

namespace BlogWriter\DAO;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use BlogWriter\Domain\User;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserDAO extends DAO implements UserProviderInterface
{
	
	
	public function findAll()
	{
		$sql = "select * from Users";
		
		$result = $this->getDb()->fetchAll($sql);
		
		// Convert query result to an array of domain objects
		$users = array();
		foreach ($result as $row) {
			$userId = $row['user_id'];
			$users[$userId] = $this->buildDomainObject($row);
		}
		return $users;
	}
	/**
	 * 
	 * @param integer $id
	 * @throws \Exception
	 * @return \BlogWriter\Domain\User
	 */
	public function find($id) {
		$sql = "select * from Users where user_id=?";
		$row = $this->getDb()->fetchAssoc($sql, array($id));
	
		if ($row)
			return $this->buildDomainObject($row);
			else
				throw new \Exception("No user matching id " . $id);
	}
	
	
	/**
	 * {@inheritDoc}
	 */
	public function loadUserByUsername($username)
	{
		$sql = "select * from Users where user_name=?";
		$row = $this->getDb()->fetchAssoc($sql, array($username));
	
		if ($row)
			return $this->buildDomainObject($row);
			else
				throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \BlogWriter\DAO\DAO::buildDomainObject()
	 */
	protected function buildDomainObject(array $row) {
		$user = new User();
		$user->setId($row['user_id']);
		$user->setUsername($row['user_name']);
		$user->setEmail($row['user_email']);
		$user->setFirstName($row['user_first_name']);
		$user->setLastName($row['user_last_name']);
		$user->setPassword($row['user_password']);
		$user->setSalt($row['user_salt']);
		$user->setRole($row['user_role']);
		return $user;
	}
	/**
	 * {@inheritDoc}
	 */
	public function refreshUser(UserInterface $user)
	{
		$class = get_class($user);
		if (!$this->supportsClass($class)) {
			throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
		}
		return $this->loadUserByUsername($user->getUsername());
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function supportsClass($class)
	{
		return 'BlogWriter\Domain\User' === $class;
	}
}