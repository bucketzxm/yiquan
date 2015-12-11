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

	function classesBySessionID ($session_user_id){

		$idArray = explode('/',$session_user_id);
		$session_id = $idArray[0];
		$user_id = $idArray[1];

		$classes = $this->db->MoClass->find(array('session_id' => $session_id));
		$results = array();
		foreach ($classes as $key => $value) {
			$cursor = $this->db->MoStudy->findOne(array('class_id'=> (string)$value['_id'],'study_type'=>'cursor','student_id'=>$user_id));
			if ($cursor == null) {
				$value['my_cursor'] = 0;
			}else{
				$value['my_cursor'] = 1;
			}
			array_push($results, $value);
		}
		return json_encode($results);
	}

	function myClassesByStudentID ($id){

		$student = $this->db->MoStudent->findOne(array('_id'=> new MongoId($id)));
		$classCursors = $this->db->MoStudy->find(array('student_id' => $id,'study_type' => 'cursor','class_status' => 'progress'))->sort(array('cursor_time' =>1));

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

	function pinnedClassesByStudentID ($id){

		$student = $this->db->MoStudent->findOne(array('_id'=> new MongoId($id)));
		$classCursors = $this->db->MoStudy->find(array('student_id' => $id,'study_type' => 'card','pin_status' =>'pinned'));

		//Build class id array
		$pinnedClassIDs = array();
		foreach ($classCursors as $key => $value) {
			if (!in_array($value['class_id'], $pinnedClassIDs)) {
				array_push($pinnedClassIDs, $value['class_id']);
			}
		}

		//插入排序的代码

		//if (count($pinnedClassIDs) > 0) {
			//$classesToLearn = $student['student_classToLearn'];
			$results = array();
			foreach ($pinnedClassIDs as $keyID => $valueID) {
				$theClass = $this->db->MoClass->findOne(array('_id'=> new MongoId($valueID)));
				if ($theClass != nil) {

					$theClass['my_cursor'] = 0;

					//Find the update cursor
					$cursor = $this->db->MoStudy->find(array(
						'student_id' => $id,
						'study_type' => 'card',
						'class_id' => (string)$theClass['_id'],
						'pin_status' => 'pinned'

						))->count();
					//if ($cursor != null) {
						$theClass['pinnedCard_Num'] = $cursor;
					//}


					array_push($results, $theClass);
				}
				
			}
			return json_encode($results);

		//}else{
		//	return -1;
		//}
		
	}

	function completedClassesByStudentID ($id){

		$student = $this->db->MoStudent->findOne(array('_id'=> new MongoId($id)));
		$classCursors = $this->db->MoStudy->find(array('student_id' => $id,'study_type' => 'cursor','class_status' =>'completed'));

		//Build class id array
		$completedClassIDs = array();
		foreach ($classCursors as $key => $value) {
			if (!in_array($value['class_id'], $completedClassIDs)) {
				array_push($completedClassIDs, $value['class_id']);
			}
		}

		//插入排序的代码

		//if (count($pinnedClassIDs) > 0) {
			//$classesToLearn = $student['student_classToLearn'];
			$results = array();
			foreach ($completedClassIDs as $keyID => $valueID) {
				$theClass = $this->db->MoClass->findOne(array('_id'=> new MongoId($valueID)));
				if ($theClass != nil) {

					array_push($results, $theClass);
				}
				
			}
			return json_encode($results);

		//}else{
		//	return -1;
		//}
		
	}

	function pinnedCardsByClassID($user_class_id){

		$idArray = explode('/',$user_class_id);
		$user_id = $idArray[0];
		$class_id = $idArray[1];


		$pinnedCards = $this->db->MoStudy->find(array('study_type' =>'card','student_id'=>$user_id,'class_id'=>$class_id,'pin_status' => 'pinned'));
		$pinnedCardIDs = array();
		foreach ($pinnedCards as $key => $value) {
			array_push($pinnedCardIDs, new MongoId($value['card_id']));
		}

		$theCards = $this->db->MoCard->find(array('_id' => array('$in' => $pinnedCardIDs)));
		$results = array();
		$resultCards = array();
		foreach ($theCards as $keycard => $card) {
			array_push($resultCards, $card);
		}
		$results['cards'] = $resultCards;
		$results['cursor'] = 0;

		return json_encode($results);

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
				$sectionResult = array();
				foreach ($value as $section => $singleCard) {
					$theCard = $this->db->MoCard->findOne(array('_id' => new MongoId($singleCard)));
					if ($theCard != nil) {

						if (isset($pinnedCards[(string)$theCard['_id']])) {
							$theCard['pin_status'] = 'pinned';
						}else{
							$theCard['pin_status'] = 'unpinned';
						}

						array_push($sectionResult, $theCard);
					}
				}
				array_push($cardsGot, $sectionResult);
				
			}
			$results['cards'] = $cardsGot;

			//get the Card Num
			$results['card_num'] = $theClass['class_cardNum'];
			//Find the cursor
			$cursor = $this->db->MoStudy->findOne(array(
					'student_id' => $user_id,
					'study_type' => 'cursor',
					'class_id' => $class_id
				));

			if ($cursor != null) {
				$results['card_cursor']= $cursor['card_cursor'];	
				$results['activity_cursor'] = $cursor['activity_cursor'];
				$results['class_status'] = $cursor['class_status'];
 			}else{
				$results['card_cursor'] = 0;
				$results['activity_cursor'] = 0;
				$results['class_status'] = 'progress';
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

		if ($cursorInt > 0) {
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

	function testsByCard($user_card_id){

		$idArray = explode('/',$user_card_id);
		$user_id = $idArray[0];
		$card_id = $idArray[1];

		$theCard = $this->db->MoCard->findOne(array('_id' => new MongoId($card_id)));
		$theRecord = $this->db->MoStudy->findOne(array('student_id'=> $user_id,'class_id' => $theCard['class_id'],'study_type' =>'cursor'));

		$theAnswers = $theRecord['activity_answers'][$card_id];

		$testResult = array();

		for ($i=0; $i < count($theCard['card_tests']); $i++) { 
			$theTest = $this->db->MoTest->findOne(array('_id' => new MongoId($theCard['card_tests'][$i]));
			if ($theAnswers != nil) {
				$theTest['user_answer'] = $theAnswers[$i];
			}
			array_push($testResult, $theTest);
		}

		return json_encode($testResult);

	}

	function uploadAnswersByActivity ($user_class_card_id,$answers,$activity_cursor,$){
		$idArray = explode('/',$user_class_id);
		$user_id = $idArray[0];
		$class_id = $idArray[1];
		$card_id = $idArray[2];
		$answerArray = explode('/',$answers);



		$activity_cursor = (int)$activity_cursor;
		$studyRecord = $this->db->MoStudy->findOne(array('student_id' => $user_id,'class_id' => $class_id,'study_type' => 'cursor'));
		if ($studyRecord != nil) {
			if ($studyRecord['activity_cursor'] < $activity_cursor+1) {
			 		$studyRecord['activity_cursor'] = $activity_cursor+1;
			 	} 

			 if ($studyRecord['activity_answers'] == nil) {
			 	$studyRecord['activity_answers'] == array();
			 }
			 $studyRecord['activity_answers'][$card_id] = $answerArray;

			 $this->db->MoStudy->save($studyRecord);
		} 

		return $studyRecord['activity_cursor'];

	}

	function finishClassByStudent($user_class_id){
		$idArray = explode('/',$user_class_id);
		$user_id = $idArray[0];
		$class_id = $idArray[1];

		$record = $this->db->MoStudy->findOne(array('student_id' =>$user_id,'class_id' =>$class_id));
		$record['class_status'] = 'completed';
		$this->db->MoStudy->save($record);

		return 1;
	}
}
?>
