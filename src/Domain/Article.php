<?php 

namespace BlogWriter\Domain;

class Article
{
	
	private $id;
	private $createDate;
	private $updateDate;
	private $title;
	private $content;
	private $slug;
	private $author;
	private $categorie;
	private $published;
	private $img;
	
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
	public function getCreateDate() {
		return $this->createDate;
	}
	
	/**
	 *
	 * @param unknown_type $createDate        	
	 */
	public function setCreateDate($createDate) {
		$this->createDate = $createDate;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getUpdateDate() {
		return $this->updateDate;
	}
	
	/**
	 *
	 * @param unknown_type $updateDate        	
	 */
	public function setUpdateDate($updateDate) {
		$this->updateDate = $updateDate;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getTitle() {
		return $this->title;
	}
	
	/**
	 *
	 * @param unknown_type $title        	
	 */
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getContent() {
		return $this->content;
	}
	
	/**
	 *
	 * @param unknown_type $content        	
	 */
	public function setContent($content) {
		$this->content = $content;
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
	public function getAuthor() {
		return $this->author;
	}
	
	/**
	 *
	 * @param unknown_type $author        	
	 */
	public function setAuthor(User $author) {
		$this->author = $author;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getCategorie() {
		return $this->categorie;
	}
	
	/**
	 *
	 * @param unknown_type $categorie        	
	 */
	public function setCategorie(Category $categorie) {
		$this->categorie = $categorie;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getPublished() {
		return $this->published;
	}
	
	/**
	 *
	 * @param unknown_type $published        	
	 */
	public function setPublished($published) {
		$this->published = $published;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getImg() {
		return $this->img;
	}
	
	/**
	 *
	 * @param unknown_type $img        	
	 */
	public function setImg($img) {
		$this->img = $img;
		return $this;
	}
}