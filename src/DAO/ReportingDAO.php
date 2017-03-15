<?php
namespace BlogWriter\DAO;

use BlogWriter\Domain\Reporting;

class ReportingDAO extends DAO
{
	
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
	
	protected function buildDomainObject(array $row)
	{
		
	}
	
}