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



	function getAccountsByRegion($region) {
		
		$cursor = $this->db->REAccount->find(array('account_province' => $region));

		$results = array();
		foreach ($cursor as $key => $value) {
			array_push($results, $value);
		}
		return $results;
	}

	function getContactsByAccount($account) {
		
		$cursor = $this->db->REContact->find(array('account_id' => $account));

		$results = array();
		foreach ($cursor as $key => $value) {
			array_push($results, $value);
		}
		return $results;
	}

	function getContactsByAccount($account) {
		
		$accountCursor = $this->db->REAccount->findOne(array('_id' => new MongoId($account)));
		$cursor = $this->db->REProject->find(array('project_accountCategory' => $accountCursor['account_category']));

		$results = array();
		foreach ($cursor as $key => $value) {
			array_push($results, $value);
		}
		return $results;
	}


	function getDetailsByAccount($account) {
		
		$results = array();

		//基本信息
		$cursor = $this->db->REAccount->findOne(array('_id' => new MongoId ($account)));

		array_push($results, $cursor);


		//联系人
		$contactCursor = $this->db->REContact->find(array('account_id' => $account));

		$contacts = array();
		foreach ($contactCursor as $key => $contact) {
			array_push($contacts, $contact);
		}
		array_push($results, $contacts);

		//动作记录
		$actionCursor = $this->db->REAction->find(array('account_id' => $account))->sort(array('action_time' => -1))->limit(10);
		$actions = array();
		foreach ($actionCursor as $keyaction => $action) {
			$projectCursor = $this->db->REProject->findOne(array('_id' => new MongoId($action['project_id'])));
			$action['action_project'] = $projectCursor['project_name'];
			array_push($actions, $action);
		}
		array_push($results, $actions);		

		return $results;
	}


	function getDetailsByContact($contact) {
		
		$results = array();

		//基本信息
		$cursor = $this->db->REContact->findOne(array('_id' => new MongoId ($contact)));

		array_push($results, $cursor);


		//动作记录
		$actionCursor = $this->db->REAction->find(array('contact_id' => $contact))->sort(array('action_time' => -1));
		$actions = array();
		foreach ($actionCursor as $keyaction => $action) {
			array_push($actions, $action);
		}
		array_push($results, $actions);		

		return $results;
	}


	function updateContactByAccount($arr){
		$row = array();

		
			$row['contact_lastName'] = $arr['lastName'];
			$row['contact_givenName'] = $arr['givenName'];
			$row['contact_position'] = $arr['position'];
			$row['contact_prefix'] = $arr['prefix'];
			$row['contact_role'] = $arr['role'];
			$row['contact_relationship'] = $arr['relationship'];
			$row['contact_mobile'] = $arr['mobile'];
			$row['contact_telephone'] = $arr['telephone'];
			$row['contact_email'] = $arr['email'];
			$row['contact_qq'] = $arr['qqnumber'];
			$row['contact_employer'] = $arr['employer'];
			$row['contact_discipline'] = $arr['discipline'];
			$row['contact_interests'] = $arr['interests'];
			$row['account_id'] = $arr['account_id'];


		$this->db->REContact->save($row);
	}


	function updateActionByAccount($arr){
		$row = array();

		
			$row['action_type'] = $arr['type'];
			$row['action_status'] = $arr['status'];
			$row['action_purpose'] = $arr['purpose'];
			$row['action_sender'] = $arr['sender'];
			$row['action_note'] = $arr['note'];
			$row['account_id'] = $arr['account_id'];
			$row['contact_id'] = $arr['contact_id'];
			$row['project_id'] = $arr['project_id'];
			$row['action_time'] = time();


		$this->db->REAction->save($row);
	}
	
}
?>