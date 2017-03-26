<?php

namespace BlogWriter\Domain;


class Category

{
	
	private $id;
	private $name; 
	private $slug;
	private $nbArticle;
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 *
	 * @param unknown_type $id        	
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 *
	 * @param unknown_type $name        	
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getSlug() {
		return $this->slug;
	}
	
	/**
	 *
	 * @param unknown_type $slug        	
	 */
	public function setSlug($slug) {
		$this->slug = $slug;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getNbArticle() {
		return $this->nbArticle;
	}
	
	/**
	 *
	 * @param unknown_type $nbArticle        	
	 */
	public function setNbArticle($nbArticle) {
		$this->nbArticle = $nbArticle;
		return $this;
	}
	
}