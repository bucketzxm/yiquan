<?php
require_once 'YqBase.php';
    
class MoSession extends YqBase {
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
	
	function sessionsByCurriculum (){

		
		$sessions = $this->db->MoSession->find();
		
		$results = array();
		foreach ($sessions as $key => $value) {
			array_push($results, $value);
		}
		return json_encode($results);
				
	}

	function classesBySessionID ($id){

		$classes = $this->db->MoClass->find(array('session_id' => $id));
		$results = array();
		foreach ($classes $key => $value) {
			array_push($results, $value);
		}
		return json_encode($results);


	}


}
?>
