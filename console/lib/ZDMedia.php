<?php
require_once 'YqBase.php';
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
############################
function is_notempty($name){
	(isset($name) ? $name : '');
}

class Media extends YqBase{
	protected $bcs_host = 'bcs.duapp.com';






	function getAllmediaInfo($congifs=[]){
		$cus=$this->db->Prosource->find();
			
		foreach ($cus as $key => $value) {
			echo is_notempty($value['source_name']);
			echo is_notempty($value['source_description']);
			echo is_notempty(implode(',', $value['source_industry']));
			echo is_notempty(implode(',', $value['source_rssURL']));
			echo is_notempty(implode(',', ['source_tag']));
			echo is_notempty($value['text_openingTag']);
			echo is_notempty($value['text_closingTag']);
		}

	
		/*$arr['source_name']=$this->db->Prosource->find(array('source_name'=>$arr['source_name']));
		$arr['source_description']=$this->db->Prosource->find(array('source_description'=>$arr['source_description']));
		$arr['source_industry']=$this->db->Prosource->find(array('source_industry'=>$arr['source_description']));
		$arr['source_rssURL']=$this->db->Prosource->find(array('source_rssURL'=>$arr['source_rssURL']));
		$arr['source_tag']=$this->db->Prosource->find(array('source_tag'=>$arr['source_tag']));
		$arr['text_openingTag']=$this->db->Prosource->find(array('text_openingTag'=>$arr['text_openingTag']));
		$arr['text_closingTag']=$this->db->Prosource->find(array('text_closingTag'=>$arr['text_closingTag']));
*/
	}
}
?>