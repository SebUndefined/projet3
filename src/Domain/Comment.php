<?php

namespace BlogWriter\Domain;

class Comment 
{
	private $id;
	private $date;
	private $content;
	private $parent;
	private $level;
	private $article;
	
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
	public function getDate() {
		return $this->date;
	}
	
	/**
	 *
	 * @param unknown_type $date        	
	 */
	public function setDate($date) {
		$this->date = $date;
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
	public function getParent() {
		return $this->parent;
	}
	
	/**
	 *
	 * @param unknown_type $Parent        	
	 */
	public function setParent(Comment $parent) {
		$this->parent = $parent;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getLevel() {
		return $this->level;
	}
	
	/**
	 *
	 * @param unknown_type $level        	
	 */
	public function setLevel($level) {
		$this->level = $level;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getArticle() {
		return $this->article;
	}
	
	/**
	 *
	 * @param unknown_type $article        	
	 */
	public function setArticle(Article $article) {
		$this->article = $article;
		return $this;
	}
}