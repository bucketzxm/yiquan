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


}
?>