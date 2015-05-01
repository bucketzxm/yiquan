<?php
require_once 'YqBase.php';

    
class Proseed extends YqBase {
	private $collection;
	// static $conn; // 连接
	// function __construct() {
	// try {
	// if (self::$conn == null) {
	// self::$conn = connectDb ();
	// }
	// self::$conn->connect ();
	// } catch ( Exception $e ) {
	// self::$conn = connectDb ();
	// }
	// while ( 1 ) {
	// $this->db = self::$conn->selectDB ( $this->dbname );
	// if ($this->user != '' && $this->pwd != '') {
	// $fa = $this->db->authenticate ( $this->user, $this->pwd );
	// if ($fa ['ok'] == 0) {
	// sleep ( 1 );
	// continue;
	// }
	// }
	// break;
	// }
	// if (! isset ( $_SESSION )) {
	// session_start ();
	// }
	// $this->yiquan_version = $this->checkagent ();
	// }
	// function __destruct() {
	// self::$conn->close ();
	// }
	// 此类用于 message 表
	// private $dbname = 'test';
	// private $table = 'topic';
	
	// message的属性:
	// sender_id
	// receiver_id
	// life
	// labels
	// type
	// postTime
	// title
	
	function queryMySeedsByName($user_id,$time){
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkQuoteToken () != 1) {
			return - 3;
		}
		
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}

		$data = $this->db->Proseed->find();
		$seeds = array ();
		foreach ($data as $key => $value) {
			array_push ($seeds,$value);
		}
		return json_encode($seeds);

	}

	function likeSeed($user_id,$seed_id,$like_comment){
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkQuoteToken () != 1) {
			return - 3;
		}
		
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}

		$data = array (
			'like_user' => $user_id,
			'like_seed' => $seed_id,
			'like_comment' => $like_comment
			);

		$this->db->Proworth->save ($data);
		return 1;

	}

}
?>
