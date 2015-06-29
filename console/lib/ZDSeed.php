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
			$cus = $this->db->Proseed->find (array('seed_dbWriteTime'=>array('$gt'=>time()-259200)))->sort(array('seed_hotness'=>-1));
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


######################################################################
	function getReport($configs = []) {
		$st = time ();
		$ed = time ();
		if (! isset ( $configs ['startday'] )) {
			$st = strtotime ( date ( 'Y-m-d', strtotime ( '-1 week' ) ) );
		} else {
			$st = $configs ['startday'];
		}
		
		$ans = [ ];
		$sst = $st;
		/*while ( $sst <= $ed ) {
			$cus = $this->db->Proseed->find ( array (
					'seed_time' => array (
							'$gte' => $sst,
							'$lt' => strtotime ( '+1 day', $sst ) 
					) 
			) );


			
			
			// var_dump(date('Y-m-d',$sst));
		}*/
		$c_notext=$this->db->Proseed->count ( array (
					'seed_time' => array (
							'$gte' => $sst,
							'$lt' => strtotime ( '+1 day', $sst ) 
					) ,'seed_completeStatus'=>'completed','seed_text'=>''
			) );
			/*while ( $cus->hasNext () ) {
				$doc = $cus->getNext ();
				if ($doc ['quote_ownerID'] != '') {
					$activeuser [$doc ['quote_ownerID']] = 1;

			}*/
			
			$ans ["$sst"] ['seed'] ['notextcount'] = $c_notext;



			$c_all=$this->db->Proseed->count ( array (
					'seed_time' => array (
							'$gte' => $sst,
							'$lt' => strtotime ( '+1 day', $sst ) 
					) 
			) );

			$c_uncompleted=$this->db->Proseed->count ( array (
					'seed_time' => array (
							'$gte' => $sst,
							'$lt' => strtotime ( '+1 day', $sst ) 
					) ,'seed_completeStatus'=>'uncompleted'
			) );
			$ans ["$sst"] ['seed'] ['uncompletedcount'] = $c_uncompleted;
			
			if ($c_all==0) {
				$ratio='今日无文章';
			}else{
				$ratio=round($c_notext/$c_all*100,2).'%';
			}
			$ans ["$sst"] ['seed'] ['ratio'] = $ratio;

			
		// var_dump($ans);
		return $ans;
	}


	
}
?>