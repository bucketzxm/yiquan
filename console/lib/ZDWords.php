<?php
require_once 'YqBase.php';

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

class Words extends YqBase{
	protected $bcs_host = 'bcs.duapp.com';

	


	function queryWords($configs = []) {
		$ans = [ ];
		if (empty ( $configs )) {
			$cus = $this->db->Prosystem->find (array( "para_name"=> "industry_dict"));
			while ( $cus->hasNext () ) {
				$doc = $cus->getNext ();
				$ans [] = $doc;
			}
		} else {
			if (isset ( $configs ['type'] )) {
				if ($configs ['type'] == 'findone') {
					$ans = $this->db->Prosystem->findOne ( array (
							'_id' => new MongoId ( $configs ['value'] ) 
					) );
				}
			}
		}
		return $ans;
	}







	function updateWords($arr) {
		$row = $this->db->Prosystem->findOne ( array (
				'_id' => new MongoId ( $arr ['id'] ) 
		) );

		if ($row != null) {
            
		
			if ($arr ['name']!= "") {
				$row ['industry_name'] = $arr ['name'];

			}  else {

				
				echo "没有行业名";
			}

			if ($arr ['words']!= "" ) {
				$words=explode(',', $arr['words']);



				foreach ($words as $key => $value) {
					$row ['industry_words']["$value"]=$value;
				}
				
			}  else {

				
				echo "没有中文关键词";
			}
			if ($arr ['ENDict']!= "" ) {
				$row ['industry_ENDict'] = explode(',', $arr['ENDict']);

			}  else {

				
				echo "没有英文关键词";
			}


			
			$row["para_name"]= "industry_dict";


		}
		return $this->db->Prosystem->save ( $row );
	}














	function createWords($arr) {
		$row = [];
		

			if ($arr ['name']!= "") {
				$row ['industry_name'] = $arr ['name'];

			}  else {

				echo '请输入行业名称<br>';
			}


			if ($arr ['words']!= "") {
				$words=explode(',', $arr['words']);

				foreach ($words as $key => $value) {
					$row ['industry_words']["$value"]=$value;
				}


			}  else {

				echo '请输入中文关键词';
			}


			if ($arr ['ENDict']!= "") {
				$row ['industry_ENDict'] = explode(',', $arr ['ENDict']);

			}  else {

				echo '请输入英文关键词';
			}


			
		
			

			
			$row["para_name"]= "industry_dict";
			
			


		
		return $this->db->Prosystem->insert ( $row );
	}

		
	
	
}


















?>