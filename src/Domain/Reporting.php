<?php

namespace BlogWriter\Domain;


class Reporting
{
	
	private $id;
	private $reason;
	private $comment;
	private $date;
	private $comment;
	
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
	public function getReason() {
		return $this->reason;
	}
	
	/**
	 *
	 * @param unknown_type $reason        	
	 */
	public function setReason($reason) {
		$this->reason = $reason;
		return $this;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getComment() {
		return $this->comment;
	}
	
	/**
	 *
	 * @param unknown_type $comment        	
	 */
	public function setComment($comment) {
		$this->comment = $comment;
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
	public function getComment() {
		return $this->comment;
	}
	
	/**
	 *
	 * @param unknown_type $comment        	
	 */
	public function setComment(Comment $comment) {
		$this->comment = $comment;
		return $this;
	}
	
	
	
	
	
	
	
	
	
}