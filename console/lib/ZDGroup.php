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
            //这几个case顺序不能换
			if ($arr ['source_List']!= "") {
				$source_listArr=explode(',',$arr['source_List']);
				$row['mediaGroup_sourceList']=[];
				foreach ($source_listArr as $key => $name) {
					$cus=$this->db->Prosource->findOne( array('source_name' => $name));
					
					$id=(string)$cus['_id'];
					$row['mediaGroup_sourceList'][$id]=$id;
					
				}
			}  

			
			if ($arr['source_box']!=array()){
				foreach ($arr['source_box'] as $key => $name) {
					
					$cus=$this->db->Prosource->findOne( array('source_name' => $name));

					#$cus=$this->db->Prosource->findOne( array('_id' => new MongoId("$value") ));
					if ($cus !=null){
						
					/*$id=(string)$cus['_id'];
					$row['mediaGroup_sourceList'][$id]=$id;*/
						$id=(string)$cus['_id'];
						$i=0
						foreach ($row['mediaGroup_sourceList'] as $key => $source) {
							if ($source['source_id']==$id) {
								$i+=1;
							}
						if ($i==0) {
							$row['mediaGroup_sourceList'][]['source_id']=$id;
						}
						}
					}
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























	function createMediaGroup($arr) {
		$row = [];
		

			if ($arr ['title']!= "") {
				$row ['mediaGroup_title'] = $arr ['title'];

			}  else {

				echo '请输入信息组/行业/标签名称<br>';
			}


			if ($arr ['detail']!= "") {
				$row ['mediaGroup_detail'] = $arr ['detail'];

			}  else {

				echo '请输入信息组描述';
			}


			if ($arr ['thanknote']!= "") {
				$row ['mediaGroup_thanknote'] =  $arr ['thanknote'];

			}  else {

				echo '请输入鸣谢';
			}


			
		
			
			
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

			
			


		
		return $this->db->ProMediaGroup->insert ( $row );
	}

		
	
	
}


















?>