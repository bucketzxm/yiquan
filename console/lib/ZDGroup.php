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
					$cus=$this->db->Prosource->findOne( array('source_name' => "$name"));
					$row['mediaGroup_sourceList'][]=$cus['_id'];
				}
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