<?php
require_once 'YqBase.php';
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
############################

class Media extends YqBase{
	protected $bcs_host = 'bcs.duapp.com';

	/*function getAllmediaInfo($congifs=[]){
		$cus=$this->db->Prosource->find();
			
		foreach ($cus as $key => $value) {
			echo $value['source_name'];
			echo (isset($value['source_description']) ? $value['source_description']: '');
			echo (isset($value['source_industry']) ? implode(',',$value['source_industry']): '');
			echo is_notempty(implode(',', $value['source_rssURL']));
			echo (isset($value['source_tag'] )? implode(',',$value['source_tag']): '');
			echo (isset($value['text_openingTag']) ? $value['text_openingTag']: '');
			echo (isset($value['text_closingTag'] )? $value['text_closingTag']: '');
		}
	}*/


	function queryMedia($configs = []) {
		$ans = [ ];
		if (empty ( $configs )) {
			$cus = $this->db->Prosource->find ();
			while ( $cus->hasNext () ) {
				$doc = $cus->getNext ();
				$ans [] = $doc;
			}
		} else {
			if (isset ( $configs ['type'] )) {
				if ($configs ['type'] == 'findone') {
					$ans = $this->db->Prosource->findOne ( array (
							'_id' => new MongoId ( $configs ['value'] ) 
					) );
				}
			}
		}
		return $ans;
	}




		/*$cus = $this->db->Prosource->find ();
		while ( $cus->hasNext () ) {
			$doc = $cus->getNext ();
			$ans [] = $doc;

		}
		return $ans;*/
		/*$arr['source_name']=$this->db->Prosource->find(array('source_name'=>$arr['source_name']));
		$arr['source_description']=$this->db->Prosource->find(array('source_description'=>$arr['source_description']));
		$arr['source_industry']=$this->db->Prosource->find(array('source_industry'=>$arr['source_description']));
		$arr['source_rssURL']=$this->db->Prosource->find(array('source_rssURL'=>$arr['source_rssURL']));
		$arr['source_tag']=$this->db->Prosource->find(array('source_tag'=>$arr['source_tag']));
		$arr['text_openingTag']=$this->db->Prosource->find(array('text_openingTag'=>$arr['text_openingTag']));
		$arr['text_closingTag']=$this->db->Prosource->find(array('text_closingTag'=>$arr['text_closingTag']));
*/
	function updateMediaTag($arr) {
		
		$row = $this->db->Prosource->findOne ( array (
				'_id' => new MongoId ( $arr ['id'] ) 
		) );
		if ($row != null) {
			$row ['source_name'] = $arr ['name'];
			$row ['source_tag'] = explode(',', $arr ['tag']);
			if (isset($row ['text_startingTag']) && isset($arr['startingTag'])) {
				$row ['text_startingTag'] = $arr ['startingTag'];

			} else if (isset($arr['startingTag'])){
				echo $arr['startingTag'];
				$row['text_startingTag']=$arr['startingTag'];

			} else {

				unset($row ['text_startingTag']);
			}
			
			$row ['text_closingTag'] = $arr['closingTag'];
			$row['source_rexTemplate']=$arr['rexTemplate'];
		}
		return $this->db->Prosource->save ( $row );
	}
	function updateMediaBasic($arr) {
		$row = $this->db->Prosource->findOne ( array (
				'_id' => new MongoId ( $arr ['id'] ) 
		) );
		if ($row != null) {
			$row ['source_name'] = $arr ['name'];
			$row ['source_description'] = $arr ['description'];
			$row ['source_industry'] = explode(',', $arr ['industry']);
			$row ['source_rssURL'] = explode(',',$arr ['rssURL']);
			$row ['source_status'] = $arr ['status'];
		}
		return $this->db->Prosource->save ( $row );
	}
	function deleteQuotes($Quoteid) {
		try {
			$this->db->Prosource->remove ( array (
					'_id' => new MongoID ( $Prosourceid ) 
			) );
			return 1;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
}
?>