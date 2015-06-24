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




		
	
	
}


















?>