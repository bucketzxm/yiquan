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
		foreach ($classes as $key => $value) {
			array_push($results, $value);
		}
		return json_encode($results);
	}

	function myClassesByStudentID ($id){

		$student = $this->db->MoStudent->findOne(array('_id'=> new MongoId($id)));
		if ($student != nil) {
			$classesToLearn = $student['student_classToLearn'];
			$results = array();
			foreach ($classesToLearn as $key => $value) {
				$theClass = $this->db->MoClass->findOne(array('_id'=> new MongoId($value)));
				if ($theClass != nil) {
					array_push($results, $theClass);
				}
				
			}
			return json_encode($results);

		}else{
			return -1;
		}
		
	}

	function cardsByClassID($id){

		$theClass = $this->db->MoClass->findOne(array('_id' => new MongoId($id)));
		if ($theClass != nil) {
			$cards = $theClass['class_cards'];
			$results = array();
			foreach ($cards as $key => $value) {
				$theCard = $this->db->MoCard->findOne(array('_id' => new MongoId($value)));
				if ($theCard != nil) {
					array_push($results, $theCard);
				}
			}
			return json_encode($results);
		}else{
			return -1;
		}

	}

}
?>
