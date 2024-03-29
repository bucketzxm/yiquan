<?php
require_once 'YqBase.php';
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
############################


############
#  helper  #
############



###############################
class Seed extends YqBase{
	protected $bcs_host = 'bcs.duapp.com';


	function getAllProjects (){

		$results = array();
		$cursor = $this->db->REProject->find(array('project_type' =>'产品'));

		foreach ($cursor as $key => $value) {
			array_push($results, $value);
		}

		return $results;
	}

	function getApplicationsByProject($projectID){

		$cursor = $this->db->REApplication->find(array('project_id' => $projectID));
		$results = array();
		foreach ($cursor as $key => $value) {
			$accountCursor = $this->db->REAccount->findOne(array('_id' => new MongoId($value['account_id'])));	
			$studentCursor = $this->db->REStudent->findOne(array('_id' => new MongoId($value['student_id'])));

			$value['applicant_name'] = $studentCursor['student_lastName']. $studentCursor['student_givenName'];
			$value['applicant_id'] = (string)$studentCursor['_id'];
			$value['applicant_accountName'] = $accountCursor['account_name'];
			array_push($results, $value);

		}

		return $results;

	}


	function getApplicantDetailsByID($studentID){

		$cursor = $this->db->REStudent->findOne(array('_id' => new MongoId($studentID)));
		$accountCursor = $this->db->REAccount->findOne(array('_id'=> new MongoId($cursor['student_accountID'])));
		$cursor['student_accountName'] = $accountCursor['account_name'];
 
		return $cursor;
	}



}
?>