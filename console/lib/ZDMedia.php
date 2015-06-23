<?php
require_once 'YqBase.php';
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
############################

class Media extends YqBase{
	protected $bcs_host = 'bcs.duapp.com';






	function getAllmediaInfo($congifs=[]){
		$cus=$this->db->Prosource->find();
			
		while ( $cus->hasNext () ) {
			$doc = $cus->getNext ();
			$this->getMediaDetailInfo ( $doc );
			$ans [] = $doc;
		}
		
	}

	function getMediaDetailInfo(&$arr){
		$arr['source_name']=$this->db->Prosource->find(array('source_name'=>$arr['source_name']));
		$arr['source_description']=$this->db->Prosource->find(array('source_description'=>$arr['source_description']));
		$arr['source_industry']=$this->db->Prosource->find(array('source_industry'=>$arr['source_description']));
		$arr['source_rssURL']=$this->db->Prosource->find(array('source_rssURL'=>$arr['source_rssURL']));
		$arr['source_tag']=$this->db->Prosource->find(array('source_tag'=>$arr['source_tag']));
		$arr['text_openingTag']=$this->db->Prosource->find(array('text_openingTag'=>$arr['text_openingTag']));
		$arr['text_closingTag']=$this->db->Prosource->find(array('text_closingTag'=>$arr['text_closingTag']));

	}
}