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



	function querySeed($configs = []) {
		$ans = [ ];
		if (empty ( $configs )) {
			$cus = $this->db->Proseed->find (array('seed_time'=>array('$gt'=>time()-259200)))->sort(array('seed_hotness'=>1));
			while ( $cus->hasNext () ) {
				$doc = $cus->getNext ();
				$ans [] = $doc;
			}
		} else {
			if (isset ( $configs ['type'] )) {
				if ($configs ['type'] == 'findone') {
					$ans = $this->db->Proseed->findOne ( array (
							'_id' => new MongoId ( $configs ['value'] ) 
					) );
				}
			}
		}
		return $ans;
	}




	function updateSeed($arr) {
		
		$row = $this->db->Proseed->findOne ( array (
				'_id' => new MongoId ( $arr ['id'] ) 
		) );
		if ($row != null) {

			if ($arr ['title'] != "") {
				
				$row ['seed_title'] = $arr ['title'];


			}  else {
				
				unset($row ['seed_title']);
			}



			if ($arr ['industry']!= "") {
				$row ['seed_industry'] = explode(',', $arr ['industry']);

			}  else {

				unset($row ['seed_industry']);
			}



			if ($arr['hotness']!= "") {
				$row ['seed_hotness'] = $arr ['hotness'];

			}  else {

				unset($row ['seed_hotness']);
			}


			if ($arr['agreeCount']!= "") {
				$row ['seed_agreeCount'] = $arr['agreeCount'];

			}  else {

				unset($row ['seed_agreeCount']);
			}




			
			
			
		}
		return $this->db->Proseed->save ( $row );
	}
	function deleteSeed($Seedid) {
		try {
			$this->db->Proseed->remove ( array (
					'_id' => new MongoID ( $Seedid ) 
			) );
			return 1;
		} catch ( Exception $e ) {
			return - 1;
		}
	}




	
}
?>