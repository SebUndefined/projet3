<?php
namespace BlogWriter\DAO;

use BlogWriter\Domain\Reporting;
use BlogWriter\Domain\Comment;

class ReportingDAO extends DAO
{
	private $commentDAO;
	
	public function findAll($limit = null)
	{
		$sql = "SELECT * FROM Reportings ORDER BY report_date DESC";
		if ($limit != null) {
			$sql = $sql . " LIMIT " . $limit;
		}
		$result = $this->getDb()->fetchAll($sql);
		
		// Convert query result to an array of domain objects
		$reportings = array();
		foreach ($result as $row) {
			$reportingId = $row['report_id'];
			$reportings[$reportingId] = $this->buildDomainObject($row);
		}
		return $reportings;
	}
	/**
	 * Saves a Report into the database.
	 *
	 * @param \BlogWriter\Domain\Reporting $report The comment to save
	 */
	public function save(Reporting $report) {
		//die(var_dump($report));
		$reportData = array(
				'report_reason' => $report->getReason(),
				'report_comment' => $report->getComment(),
				'report_date' => date('Y-m-d H:i:s'),
				'report_comment_id' =>$report->getCommentConcernedId(),
				
		);
		if ($report->getId()) {
			// The report has already been saved : update it
			$this->getDb()->update('Reportings', $reportData, array('com_id' => $report->getId()));
		} else {
			// The report has never been saved : insert it
			$this->getDb()->insert('Reportings', $reportData);
			// Get the id of the newly created report and set it on the entity.
			$id = $this->getDb()->lastInsertId();
			$report->setId($id);
		}
	}
	public function delete($id)
	{
		
		$this->getDb()->delete('Reportings', array('report_id'=>$id));
	}
	
	public function countReportings()
	{
		$sql = 'SELECT COUNT(*) AS total FROM Reportings';
		$reportingsCounter = $this->getDb()->query($sql);
		$nbReportings = $reportingsCounter->fetch();
		return $nbReportings['total'];
	}
	public function setCommentDAO(CommentDAO $commentDAO)
	{
		$this->commentDAO = $commentDAO;
	}
	protected function buildDomainObject(array $row)
	{
		$reporting = new Reporting();
		$reporting->setId($row['report_id']);
		$reporting->setReason($row['report_reason']);
		$reporting->setComment($row['report_comment']);
		$reporting->setDate($row['report_date']);
		$commentConcerned = $this->commentDAO->findById($row['report_comment_id']);
		$reporting->setCommentConcerned($commentConcerned);
		return $reporting;
	}
	
}