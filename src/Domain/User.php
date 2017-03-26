<?php
namespace BlogWriter\Domain;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
	/**
	 * User id.
	 *
	 * @var integer
	 */
	private $id;

	/**
	 * User pseudo.
	 *
	 * @var string
	 */
	private $username;
	
	/**
	 * User email
	 * 
	 * @var string
	 */
	private $email; 
	
	
	/**
	 * user first name
	 * 
	 * @var string
	 */
	private $firstName;
	
	/**
	 * user last name
	 *
	 * @var string
	 */
	private $lastName;

	/**
	 * User password.
	 *
	 * @var string
	 */
	private $password;

	/**
	 * Salt that was originally used to encode the password.
	 *
	 * @var string
	 */
	private $salt;

	/**
	 * Role.
	 * Values : ROLE_USER or ROLE_ADMIN.
	 *
	 * @var string
	 */
	private $role;
	
	/**
	 *
	 * @return the integer
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 *
	 * @param
	 *        	$id
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	
	/**
	 *
	 * @return the string
	 */
	public function getUsername() {
		return $this->username;
	}
	
	/**
	 *
	 * @param
	 *        	$pseudo
	 */
	public function setUserName($username) {
		$this->username = $username;
		return $this;
	}
	
	/**
	 *
	 * @return the string
	 */
	public function getEmail() {
		return $this->email;
	}
	
	/**
	 *
	 * @param
	 *        	$email
	 */
	public function setEmail($email) {
		$this->email = $email;
		return $this;
	}
	
	/**
	 *
	 * @return the string
	 */
	public function getFirstName() {
		return $this->firstName;
	}
	
	/**
	 *
	 * @param
	 *        	$firstName
	 */
	public function setFirstName($firstName) {
		$this->firstName = $firstName;
		return $this;
	}
	
	/**
	 *
	 * @return the string
	 */
	public function getLastName() {
		return $this->lastName;
	}
	
	/**
	 *
	 * @param
	 *        	$lastName
	 */
	public function setLastName($lastName) {
		$this->lastName = $lastName;
		return $this;
	}
	
	/**
	 *
	 * @return the string
	 */
	public function getPassword() {
		return $this->password;
	}
	
	/**
	 *
	 * @param
	 *        	$password
	 */
	public function setPassword($password) {
		$this->password = $password;
		return $this;
	}
	
	/**
	 *
	 * @return the string
	 */
	public function getSalt() {
		return $this->salt;
	}
	
	/**
	 *
	 * @param
	 *        	$salt
	 */
	public function setSalt($salt) {
		$this->salt = $salt;
		return $this;
	}
	
	/**
	 *
	 * @return the string
	 */
	public function getRole() {
		return $this->role;
	}
	
	/**
	 *
	 * @param
	 *        	$role
	 */
	public function setRole($role) {
		$this->role = $role;
		return $this;
	}
	
	
	/**
	 * @inheritDoc
	 */
	public function getRoles()
	{
		return array($this->getRole());
	}
	/**
	 * @inheritDoc
	 */
	public function eraseCredentials() {
		// Nothing to do here
	}
}
