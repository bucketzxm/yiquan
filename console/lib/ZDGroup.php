<?php
require_once 'YqBase.php';

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

class Group extends YqBase{
	protected $bcs_host = 'bcs.duapp.com';

	


	function queryGroup($configs = []) {
		$ans = [ ];
		if (empty ( $configs )) {
			$cus = $this->db->ProMediaGroup->find ();
			while ( $cus->hasNext () ) {
				$doc = $cus->getNext ();
				$ans [] = $doc;
			}
		} else {
			if (isset ( $configs ['type'] )) {
				if ($configs ['type'] == 'findone') {
					$ans = $this->db->ProMediaGroup->findOne ( array (
							'_id' => new MongoId ( $configs ['value'] ) 
					) );
				}
			}
		}
		return $ans;
	}

	function updateGroupBasic($arr) {
		$row = $this->db->ProMediaGroup->findOne ( array (
				'_id' => new MongoId ( $arr ['id'] ) 
		) );
		if ($row != null) {

			if ($arr ['title']!= "") {
				$row ['mediaGroup_title'] = $arr ['title'];

			}  else {

				unset($row ['mediaGroup_title']);
			}


			if ($arr ['detail']!= "") {
				$row ['mediaGroup_detail'] = $arr ['detail'];

			}  else {

				unset($row ['mediaGroup_detail']);
			}


			if ($arr ['thanknote']!= "") {
				$row ['mediaGroup_thanknote'] =  $arr ['thanknote'];

			}  else {

				unset($row ['mediaGroup_thanknote']);
			}


			
		}
		return $this->db->ProMediaGroup->save ( $row );
	}





	function updateGroupMedia($arr) {
		$row = $this->db->ProMediaGroup->findOne ( array (
				'_id' => new MongoId ( $arr ['id'] ) 
		) );
		if ($row != null) {
			
			if ($arr['source_box']!=array()){
				foreach ($arr['source_box'] as $key => $name) {
					$cus=$this->db->Prosource->findOne( array('source_name' => $name));

					#$cus=$this->db->Prosource->findOne( array('_id' => new MongoId("$value") ));
					if ($cus !=null){
						$id=(string)$cus['_id'];
						$row['mediaGroup_sourceList'][$id]=$id;
					}
				}
			}




			if ($arr ['source_list']!= "") {
				$source_listArr=explode(',',$arr['source_list']);
				$row['mediaGroup_sourceList']=[];
				foreach ($source_listArr as $key => $name) {
					$cus=$this->db->Prosource->findOne( array('source_name' => $name));
					if ($cus !=null){
						$id=(string)$cus['_id'];
						$row['mediaGroup_sourceList'][$id]=$id;
					}
				}
			}  else {

				unset($row ['mediaGroup_sourceList']);
			}

			



			if ($arr ['title']!= "") {
				$row ['mediaGroup_title'] = $arr ['title'];

			}  else {

				unset($row ['mediaGroup_title']);
			}




			
			


		}
		return $this->db->ProMediaGroup->save ( $row );
	}




		
	
	
}


















?>