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
			$cus = $this->db->Proseed->find (array('seed_dbWriteTime'=>array('$gt'=>(time()-86400))))->sort(array('seed_dbWriteTime' => -1));
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

	function querySeedByChannel($channel) {
		$ans = array();

			$cus = $this->db->Proseed->find (array('seed_dbWriteTime'=>array('$gt'=>(time()-86400))))->sort(array('seed_dbWriteTime' => -1));
			while ( $cus->hasNext () ) {
				$doc = $cus->getNext ();
				$ans [] = $doc;
			}
		
		return $ans;
	}

	function querySeedBySource($source) {
		$ans = array();

			$cus = $this->db->Proseed->find (array('seed_sourceID' => $source,'seed_editorRating' => -1,'seed_dbWriteTime'=>array('$gt'=>(time()-86400*3))))->sort(array('seed_dbWriteTime' => -1));
			while ( $cus->hasNext () ) {
				$doc = $cus->getNext ();
				$ans [] = $doc;
			}
		
		return $ans;
	}	

	function queryBizSeedToReview($configs = []) {
		$ans = [ ];
		if (empty ( $configs )) {
			$cus = $this->db->Proseed->find (array('seed_domain' => 'business','seed_editorRating' => -1,'seed_text' => array('$ne' => ''),'seed_dbWriteTime'=>array('$gt'=>(time()-86400*3))))->limit(500)->sort(array('seed_dbWriteTime' => -1));
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

	function queryBizSeedPassed($configs = []) {
		$ans = [ ];
		if (empty ( $configs )) {
			$cus = $this->db->Proseed->find (array('seed_domain' => 'business','seed_editorRating' => array('$gte' => 0),'seed_dbWriteTime'=>array('$gt'=>(time()-86400*3))))->sort(array('seed_dbWriteTime' => -1));
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

	function queryBizSeedDead($configs = []) {
		$ans = [ ];
		if (empty ( $configs )) {
			$cus = $this->db->Proseed->find (array('seed_domain' => 'business','seed_editorRating' => array('$lt' => -1),'seed_dbWriteTime'=>array('$gt'=>(time()-86400*3))))->sort(array('seed_dbWriteTime' => -1));
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

	function queryLifeSeedToReview($configs = []) {
		$ans = [ ];
		if (empty ( $configs )) {
			$cus = $this->db->Proseed->find (array('seed_domain' => 'life','seed_editorRating' => -1,'seed_text' => array('$ne' => ''),'seed_dbWriteTime'=>array('$gt'=>(time()-86400*3))))->limit(500)->sort(array('seed_dbWriteTime' => -1));
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

	function queryLifeSeedPassed($configs = []) {
		$ans = [ ];
		if (empty ( $configs )) {
			$cus = $this->db->Proseed->find (array('seed_domain' => 'life','seed_editorRating' => array('$gte' => 0),'seed_dbWriteTime'=>array('$gt'=>(time()-86400*3))))->sort(array('seed_dbWriteTime' => -1));
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

	function queryLifeSeedDead($configs = []) {
		$ans = [ ];
		if (empty ( $configs )) {
			$cus = $this->db->Proseed->find (array('seed_domain' => 'life','seed_editorRating' => array('$lt' => -1),'seed_dbWriteTime'=>array('$gt'=>(time()-86400*3))))->sort(array('seed_dbWriteTime' => -1));
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
				
				$row['seed_title'] = '';
				//unset($row ['seed_title']);
			}


			if ($arr ['imageLink'] != "") {
				
				$row ['seed_imageLink'] = $arr ['imageLink'];


			}  else {
				
				$row['seed_imageLink'] = '';
				//unset($row ['seed_title']);
			}

			if ($arr ['industry']!= "") {
				$row ['seed_industry'] = explode(',', $arr ['industry']);

			}  else {
				$row['seed_industry'] = array();
				//unset($row ['seed_industry']);
			}

			if ($arr ['seedDomain']!= "") {
				$row ['seed_domain'] = $arr['seedDomain'];

			}  else {
				//$row['seed_industry'] = array();
				//unset($row ['seed_industry']);
			}			

			if ($arr['hotness']!= "") {
				$row ['seed_hotness'] = $arr ['hotness'];

			}  else {

				//unset($row ['seed_hotness']);
			}


			if ($arr['agreeCount']!= "") {
				$row ['seed_agreeCount'] = $arr['agreeCount'];

			}  else {

				//unset($row ['seed_agreeCount']);
			}
			if ($arr['rating']!= "") {
				$row ['seed_editorRating'] = (int)$arr['rating'];

			}  else {

				//unset($row ['seed_editorRating']);
			}


			if ($arr['source_box']!=array() OR  isset($arr['source_box'])){
				foreach ($arr['source_box'] as $key => $name) {
					
					if (isset($row['seed_industry'])) {
						if (!in_array($name, $row['seed_industry'])) {
							array_push($row['seed_industry'], $name);
						}	
					}else{
						$row['seed_industry'] = array();
						array_push($row['seed_industry'], $name);
					}
					
				}
			}

			
			
			
		}
		return $this->db->Proseed->save ( $row );
	}
	function deleteSeed($Seedid) {
		try {

			$seedToDelete = $this->db->Proseed->findOne ( array (
					'_id' => new MongoID ( $Seedid ) 
			) );
			$seedToDelete['seed_editorRating'] = -2;
			$this->db->Proseed->save($seedToDelete);
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
			
			$ans ["$sst"] ['seed'] ['没有文章数'] = $c_notext;



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
			$ans ["$sst"] ['seed'] ['uncompleted数量'] = $c_uncompleted;
			
			if ($c_all==0) {
				$ratio='今日无文章';
			}else{
				$ratio=round($c_notext/$c_all*100,2).'%';
			}
			$ans ["$sst"] ['seed'] ['文章为空比例'] = $ratio;
			$ans ["$sst"] ['seed'] ['今日文章总数'] = $c_all;

			
		// var_dump($ans);
		return $ans;
	}


	
}
?>