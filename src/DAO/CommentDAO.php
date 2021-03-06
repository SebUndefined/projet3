<?php

namespace BlogWriter\DAO;

use BlogWriter\Domain\Comment;

class CommentDAO extends DAO
{
	/**
	 * @var \\BlogWriter\Domain\Article
	 */
	private $articleDAO;
	
	/**
	 * Return a list of comment
	 * @param integer $limit by default at null
	 * @return \BlogWriter\Domain\Comment[]
	 */
	public function findAll($limit = null)
	{
		$sql = "select * from Comments ORDER BY com_date DESC";
		if ($limit != null)
		{
			$sql = $sql . " LIMIT " . $limit;
		}
		$result = $this->getDb()->fetchAll($sql);
		$comments = array();
		foreach ($result as $row) {
			$article = $this->articleDAO->findByID($row['com_id_art']);
			$comId = $row['com_id'];
			$comment = $this->buildDomainObject($row);
			// The associated article is defined for the constructed comment
			$comment->setArticle($article);
			$comments[$comId] = $comment;
		}
		return $comments;
	}
	
	/**
	 * Return a list of all comments for an article, sorted by date (most recent last).
	 * @param integer $articleId The article id.
	 * @return array A list of all comments for the article.
	 */
	public function findAllByArticle($articleId) {
		// The associated article is retrieved only once
		$article = $this->articleDAO->findByID($articleId);

		// art_id is not selected by the SQL query
		// The article won't be retrieved during domain objet construction
		$sql = "select * from Comments where com_id_art=? ORDER BY com_id DESC";
		$result = $this->getDb()->fetchAll($sql, array($articleId));
	
		$comments = array();
		foreach ($result as $row) {
			$comId = $row['com_id'];
			$comment = $this->buildDomainObject($row);
			$comment->setArticle($article);
			$comments[$comId] = $comment;
		}
		$commentsSorted = CommentDAO::buildCommentsTree($comments);
		return $commentsSorted;
	}
	
	public function findById($id)
	{
		$sql = "select * from Comments where com_id=?";
		$row = $this->getDb()->fetchAssoc($sql, array($id));
		
		if ($row)
			return $this->buildDomainObject($row);
			else
				throw new \Exception("No comment matching id " . $id);
	}
	/**
	 * Saves a comment into the database.
	 * @param \BlogWriter\Domain\Comment $comment The comment to save
	 */
	public function save(Comment $comment) {
		if ($comment->getParent() !== null) {
			$parentCommentId = $comment->getParent()->getId();
			$level = $comment->getParent()->getLevel()+1;
		}
		else 
		{
			$parentCommentId = null;
			$level = 1;
		}
		$commentData = array(
				'com_date' => date('Y-m-d H:i:s'),
				'com_pseudo' => $comment->getPseudo(),
				'com_content' => $comment->getContent(),
				'com_id_parent' => $parentCommentId,
				'com_level' => $level,
				'com_id_art' => $comment->getArticle()->getId(),
		);
		if ($comment->getId()) {
			// The comment has already been saved : update it
			$this->getDb()->update('Comments', $commentData, array('com_id' => $comment->getId()));
		} else {
			// The comment has never been saved : insert it
			$this->getDb()->insert('Comments', $commentData);
			// Get the id of the newly created comment and set it on the entity.
			$id = $this->getDb()->lastInsertId();
			$comment->setId($id);
		}
	}
	public function delete($id)
	{
		$this->getDb()->delete('Comments', array('com_id'=>$id));
	}
	
	public function setArticleDAO(ArticleDAO $articleDAO) {
		$this->articleDAO = $articleDAO;
	}
	public static function buildCommentsTree($comments)
	{
		foreach ($comments as $k=>$comment)
		{
			if ($comment->getLevel() > 1)
			{
				$comments[$comment->getParent()->getId()]->children[] = $comment;
				unset($comments[$k]);
			}
		}
		return $comments;
	}
	
	public function countComments()
	{
		$sql = 'SELECT COUNT(*) AS total FROM Comments';
		$commentsCounter = $this->getDb()->query($sql);
		$nbComments = $commentsCounter->fetch();
		return $nbComments['total'];
	}
	
	protected function buildDomainObject(array $row) {
		$comment = new Comment();
		$comment->setId($row['com_id']);
		$comment->setDate($row['com_date']);
		$comment->setPseudo($row['com_pseudo']);
		$comment->setContent($row['com_content']);
		//check
		if (empty($row['com_id_parent']) != true)
		{
			$parentId = $row['com_id_parent'];
			$parent = $this->findById($parentId);
			$comment->setParent($parent);
		}		
		$comment->setLevel($row['com_level']);
		$articleID = $row['com_id_art'];
		$article = $this->articleDAO->findById($articleID);
		$comment->setArticle($article);
		return $comment;
		
	}
	
}