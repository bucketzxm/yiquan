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
	
	function curriculumByStudent (){

		
		$sessions = $this->db->MoCurriculum->find();
		
		$results = array();
		foreach ($sessions as $key => $value) {
			array_push($results, $value);
		}
		return json_encode($results);
				
	}

	function sessionsByCurriculum ($curriculum_id){

		
		$sessions = $this->db->MoSession->find(array('curriculum_id' => $curriculum_id))->sort(array('session_num' => 1));
		
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
		$classCursors = $this->db->MoStudy->find(array('student_id' => $id,'study_type' => 'cursor'));

		//插入排序的代码

		if ($classCursors != nil) {
			//$classesToLearn = $student['student_classToLearn'];
			$results = array();
			foreach ($classCursors as $key => $value) {
				$theClass = $this->db->MoClass->findOne(array('_id'=> new MongoId($value['class_id'])));
				if ($theClass != nil) {

					$theClass['my_cursor'] = 0;

					//Find the update cursor
					$cursor = $this->db->MoStudy->findOne(array(
						'student_id' => $id,
						'study_type' => 'cursor',
						'class_id' => (string)$theClass['_id']

						));
					if ($cursor != null) {
						$theClass['my_cursor'] = $cursor['card_cursor'];
					}


					array_push($results, $theClass);
				}
				
			}
			return json_encode($results);

		}else{
			return -1;
		}
		
	}

	function cardsByClassID($user_id,$class_id){

		$theClass = $this->db->MoClass->findOne(array('_id' => new MongoId($class_id)));
		if ($theClass != nil) {
			
			//Find all the pinned cards by that user
			$pinnedCursor = $this->db->MoStudy->find(array(
				'student_id' => $user_id, 
				'class_id' => $class_id,
				'pin_status' => "pinned",
				'study_type' => 'card'

				));

			$pinnedCards = array();
			foreach ($pinnedCursor as $cardkey => $card) {
				$pinnedCards[$card['card_id']] = $card['card_id'];
			}
			//Find all cards of the class
			$cards = $theClass['class_cards'];
			
			$results = array();
			$cardsGot = array();
			foreach ($cards as $key => $value) {
				$theCard = $this->db->MoCard->findOne(array('_id' => new MongoId($value)));
				if ($theCard != nil) {

					if (isset($pinnedCards[(string)$theCard['_id']])) {
						$theCard['pin_status'] = 'pinned';
					}else{
						$theCard['pin_status'] = 'unpinned';
					}

					array_push($cardsGot, $theCard);
				}
			}
			$results['cards'] = $cardsGot;

			//Find the cursor
			$cursor = $this->db->MoStudy->findOne(array(
					'student_id' => $user_id,
					'study_type' => 'cursor',
					'class_id' => $class_id
				));

			if ($cursor != null) {
				$results['cursor']= $cursor['card_cursor'];	
			}else{
				$results['cursor'] = 0;
			}

			return json_encode($results);
		}else{
			return -1;
		}

	}

	function pinCardByUser($user_id,$card_id,$pin_action){
		$pinnedCard = $this->db->MoStudy->findOne(
				array(
					'study_type' => 'card',
					'student_id' => $user_id,
					'card_id' => $card_id
					)
			);

		$card = $this->db->MoCard->findOne(
				array(
					'_id' => new MongoId ($card_id)
					)
			);

		if ($pin_action == 'pin') {
			if ($pinnedCard != null) {
			$pinnedCard['pin_status'] = 'pinned';
			$pinnedCard['pin_time'] = time();
			$this->db->MoStudy->save ($pinnedCard);
			}else{
				$newPin = array(
					'study_type' => 'card',
					'student_id' => $user_id,
					'card_id' => $card_id,
					'class_id' => $card['class_id'],
					'pin_time' => time(),
					'pin_status' => 'pinned'
					);
				$this->db->MoStudy->save ($newPin);
			}	
		}else if ($pin_action == 'unpin'){
			if ($pinnedCard != null) {
				$pinnedCard['pin_status'] = 'unpinned';
				$pinnedCard['pin_time'] = time();
				$this->db->MoStudy->save ($pinnedCard);
			}else{

			}

		}
		
		return 1;
	}


	function updateClassCursor($user_class_id,$card_position){

		$idArray = explode('/',$user_class_id);
		$user_id = $idArray[0];
		$class_id = $idArray[1];

		$cursorInt = (int)$card_position;

		if ($cursorInt > 1) {
			$record = $this->db->MoStudy->findOne(array(
					'student_id' => $user_id,
					'study_type' => 'cursor',
					'class_id' => $class_id,
				));

			if ($record == null) {
				
				$newCursor = array(
						'student_id' => $user_id,
						'study_type' => 'cursor',
						'class_id' => $class_id,
						'card_cursor' => $cursorInt
					);

				$this->db->MoStudy->save($newCursor);

			}else{
				if ($cursorInt > $record['card_cursor']) {
					$record['card_cursor'] = $cursorInt;
					$this->db->MoStudy->save($record);
				}

			}
		}
			


	}

	
}
?>
