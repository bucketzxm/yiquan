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
            $media_helper=[];
            foreach ($row['mediaGroup_sourceList'] as $key => $media) {
            	$helper_id=$media['source_id'];
            	$media_helper["$helper_id"]=$media;
            }
			$row['mediaGroup_sourceList']=[];

			if ($arr ['source_List']!= "") {
				$source_listArr=explode(',',$arr['source_List']);
				$ids=[];
				foreach ($source_listArr as $key => $name) {

					$cus=$this->db->Prosource->findOne( array('source_name' => $name));
					
					$id=(string)$cus['_id'];
					$rationale=(isset($media_helper[$id]['source_rationale']) ?$media_helper[$id]['source_rationale']:'');
					$industry=(isset($media_helper[$id]['source_industry']) ?$media_helper[$id]['source_industry']:'');

					$row['mediaGroup_sourceList'][$key]['source_id']=$id;
					$row['mediaGroup_sourceList'][$key]['source_industry']=$industry;
					$row['mediaGroup_sourceList'][$key]['source_rationale']=$rationale;
					if ($id=="") {
						unset($source_listArr[$key]);
					}else{
						$ids["$id"]=$id;
					}

					
				}
				/*$should_delete=[];
				foreach ($media_helper as $key => $id) {
						if (isset($ids["$id"])!=true) {
							$should_delete["$id"]="$id";
						}
					}
				$keys=[];
				foreach ($row['mediaGroup_sourceList'] as $key => $value) {
					$id=$value['source_id'];
					if (isset($should_delete["$id"])) {
						$keys["$key"]=$key;
					}


				}	
				foreach ($keys as $key => $value) {
					# code...
				
						unset($row['mediaGroup_sourceList'][$value]);
			



				}	*/	


			}  

			
			if ($arr['source_box']!=array() OR  isset($arr['source_box'])){
				foreach ($arr['source_box'] as $key => $name) {
					
					$cus=$this->db->Prosource->findOne( array('source_name' => $name));

					#$cus=$this->db->Prosource->findOne( array('_id' => new MongoId("$value") ));
					if ($cus !=null){
						
					/*$id=(string)$cus['_id'];
					$row['mediaGroup_sourceList'][$id]=$id;*/
						$id=(string)$cus['_id'];
						if ($id=="") {
							unset($s_List[$key]);
						}

						$i=0;
						foreach ($row['mediaGroup_sourceList'] as $key => $source) {
							if (isset($source['source_id']) && $source['source_id']=="$id") {
								$i+=1;
							}
						}
						if ($i==0) {
							$row['mediaGroup_sourceList'][]['source_id']="$id";
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
		$row['mediaGroup_counts']		['media_count']=count($row['mediaGroup_sourceList']);
		$count=0;
		foreach ($row['mediaGroup_sourceList'] as $key => $value) {
			$id=$value['source_id'];
			$cus=$this->db->Prosource->findOne( array('_id' => $id));
			$count+=$cus['agree_count'];
		}
		$row['mediaGroup_counts']		['worth_count']=$count;

		return $this->db->ProMediaGroup->save ( $row );
	}




	function updateMedias($arr) {
		$row = $this->db->ProMediaGroup->findOne ( array (
				'_id' => new MongoId ( $arr ['id'] ) 
		) );
		if ($row != null) {

				# code...
			

			if ($arr ['industry']!= array() ||  isset($arr['industry'])) {
				foreach ($arr['industry'] as $key => $value) {

					if ($value!="") {
						$row ['mediaGroup_sourceList'][$key]['source_industry'] = $value;
					}
					else{
						$row ['mediaGroup_sourceList'][$key]['source_industry'] = '还没有行业';
					}
				}

			}  
				
			if ($arr ['rationale']!= array()) {
				foreach ($arr['rationale'] as $key => $value) {

					if ($value!="") {
						$row ['mediaGroup_sourceList'][$key]['source_rationale'] = $value;
					}else{
						$row ['mediaGroup_sourceList'][$key]['source_rationale'] = '还没有推荐理由';

					}

				}

			}  
			

			
		}
		$row['mediaGroup_counts']		['media_count']=count($row['mediaGroup_sourceList']);
		$count=0;
		foreach ($row['mediaGroup_sourceList'] as $key => $value) {
			$id=$value['source_id'];
			$cus=$this->db->Prosource->findOne( array('_id' => $id));
			$count+=$cus['agree_count'];
		}
		$row['mediaGroup_counts']		['worth_count']=$count;
		return $this->db->ProMediaGroup->save ( $row );
	}





	function createMediaGroup($arr=[]) {
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


			
		
			$count=0;
			
			if ($arr['source_box']!=array()|| isset($arr['source_box'])){
				foreach ($arr['source_box'] as $key => $name) {
					
					$cus=$this->db->Prosource->findOne( array('source_name' => $name));

					#$cus=$this->db->Prosource->findOne( array('_id' => new MongoId("$value") ));
					if ($cus !=null){
						$count+=$cus['agree_count'];
	
						$id=(string)$cus['_id'];
						$row['mediaGroup_sourceList'][]['source_id']=$id;
					}
				}
			}

			
		$row['mediaGroup_counts']	['media_count']=count($row['mediaGroup_sourceList']);
			

		$row['mediaGroup_counts']		['worth_count']=$count;


		
		return $this->db->ProMediaGroup->insert ( $row );
	}

		
	
	
}








?>