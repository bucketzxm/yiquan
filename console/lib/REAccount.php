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
			array_push($actions, $action);
		}
		array_push($results, $actions);		

		return $results;
	}


	
}
?>