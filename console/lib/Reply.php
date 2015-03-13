<?php
require_once 'YqBase.php';
// error_reporting ( E_ALL);
// ini_set('display_errors', '1');
/**
 * Reply table related operations
 * Author: Song Bo
 * Date: 2015.1.25
 * Email: sbo@zju.edu.cn
 * columns of 'Reply' table
 * - MongoId - _id,
 * - string - reply_topicID,
 * - string - reply_senderName,
 * - int - reply_level,
 * - int - reply_time, (UNIX time)
 * - int - reply_visibility,(0 or 1)
 * - string - reply_content,
 * - ing - reply_hostAgreeCount, (0 or 1)
 * - array(string) - reply_guestAgreeNames,
 */
class Reply extends YqBase {
	private $tableName = 'reply';
	
	/**
	 * get all reply under a given topic_id
	 * the return value depends on the reply_visibility configuration
	 *
	 * @param $topic_id -
	 *        	string - MongoId of the topic. It must exist in the topic table
	 *        	$user_name - string - MongoId of the user who is calling this function
	 *        	$time - int - UNIX time
	 * @return return json text contains replies of given topic_id.
	 *         Information of reply is given in the following order:
	 *         - string - reply_id,
	 *         - string - topic_id,
	 *         - string - reply_senderName,
	 *         - int - reply_time. UNIX Time
	 *         - int - reply_level,
	 *         - string - reply_content
	 *         - int - reply_hostAgreeCount. It can only be 0 or 1
	 *         - array(string) - reply_guestAgreeNames
	 *         return an empty string if no reply
	 *         return a negative number if failed:
	 *         - -1: room_id illegal
	 *         - -2: room_ownerName does not exist in user table
	 *        
	 */
	function queryAllReplyByTopic($topic_id, $user_name, $reply_loadTime, $maxSize = 20) {
		$state = $this->verifyUserAndWriteLog ( __METHOD__ );
		if ($state != 1) {
			return $state;
		}
		if (is_int ( $reply_loadTime ) == false) {
			try {
				$reply_loadTime = ( int ) $reply_loadTime;
			} catch ( Exception $e ) {
				return - 1;
			}
		}
		// get topic_owner_id
		try {
			$cursor = $this->db->topic->findOne ( array (
					"_id" => new MongoId ( $topic_id ) 
			), array (
					"topic_ownerName" => 1 
			) );
		} catch ( Exception $e ) {
			return - 1;
		}
		if ($cursor == NULL) {
			return 4; // topic does not exist
		}
		$topic_ownerName = $cursor ['topic_ownerName'];
		
		// set user's visibility
		if ($topic_ownerName == $user_name) {
			// user is the topic_owner, he or she can view all the replies
			$visible = 1;
		} else {
			$visible = 0;
		}
		
		// find replies in table
		$where = array (
				'$and' => array (
						array (
								'reply_time' => array (
										'$gt' => $reply_loadTime 
								) 
						),
						array (
								"reply_topicID" => $topic_id 
						) 
				) 
		);
		try {
			$cursor = $this->db->reply->find ( $where )->sort ( array (
					'reply_time' => 1 
			) );
		} catch ( Exception $e ) {
			return - 1;
		}
		$re = array ();
		$count = 0;
		// var_dump($cursor);
		while ( $cursor->hasNext () && $count < $maxSize ) {
			$document = $cursor->getNext ();
			if ($visible == 1 || $document ['reply_visibility'] == '1' || $document ['reply_senderName'] == $user_name) {
				try {
					$userCursor = $this->db->user->findOne ( array (
							"user_name" => $document ['reply_senderName'] 
					), array (
							"user_nickname" => 1,
							"user_pic" => 1 
					) );
				} catch ( Exception $e ) {
					return - 102;
				}
				$document ['user_nickname'] = $userCursor ['user_nickname'];
				$document ['user_pic'] = $userCursor ['user_pic'];
				array_push ( $re, $document );
				$count ++;
			}
		}
		return json_encode ( $re );
	}
	
	/**
	 * Post a new reply
	 *
	 * @param $topic_id -
	 *        	string - MongoId of the topic. It must exist in the topic table
	 *        	$reply_senderName - string - author's name of this reply
	 *        	$reply_content - string - content of this reply
	 *        	$reply_visibility - bool - 1 - this reply is visible to everyone.
	 *        	0 - this reply is only visible to the topic owner
	 * @return 1: succeed;
	 *         0: db throws an exception
	 *         -1: room_id does not exist in db
	 *         -100+: database error
	 *        
	 */
	function addReply($topic_id, $reply_senderName, $reply_content, $reply_visibility) {
		if (!isset ( $_COOKIE ['user'] )|| $_COOKIE['user'] != $reply_senderName){
			return -4;
		}
		$state = $this->verifyUserAndWriteLog ( __METHOD__ );
		if ($state != 1) {
			return $state;
		}
		// get time
		$reply_time = time ();
		
		// get reply_level, start from 1
		try {
			$reply_level = $this->db->reply->find ( array (
					"reply_topicID" => $topic_id 
			) )->count () + 1;
		} catch ( Exception $e ) {
			return - 1;
		}
		// construct item data
		$data = array (
				"reply_topicID" => $topic_id,
				"reply_senderName" => $reply_senderName,
				"reply_level" => $reply_level,
				"reply_time" => $reply_time,
				"reply_visibility" => $reply_visibility,
				"reply_content" => $reply_content,
				"reply_hostAgreeCount" => 0,
				"reply_guestAgreeNames" => array () 
		);
		
		try {
			$this->db->reply->insert ( $data );
			$topic = new Topic ();
			$state = $topic->addReply ( $topic_id );
			if ($state != 1) {
				return - 1;
			}
			return 1;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	/**
	 * like a reply
	 *
	 * @param $reply_id -
	 *        	string - MongoId of the reply
	 *        	$user_name - string - MongoId of the user who like a reply
	 *        	
	 * @return 1: succeed
	 *         0: repeated like
	 *         -1:reply_id does not exist
	 *         -2:topic does not exist
	 *        
	 */
	function likeReply($reply_id, $user_name) {
		if (!isset ( $_COOKIE ['user'] )|| $_COOKIE['user'] != $user_name){
			return -4;
		}
		$state = $this->verifyUserAndWriteLog ( __METHOD__ );
		if ($state != 1) {
			return $state;
		}
		// get topic_id
		try {
			$cursor = $this->db->reply->findOne ( array (
					'_id' => new MongoId ( $reply_id ) 
			), array (
					'reply_topicID' => 1,
					'reply_guestAgreeNames' => 1 
			) );
		} catch ( Exception $e ) {
			return - 1;
		}
		if ($cursor == NULL) {
			return 4; // reply_id does not exist
		}
		
		$topic_id = $cursor ['reply_topicID'];
		$reply_guestAgreeNames = $cursor ['reply_guestAgreeNames'];
		try {
			$cursor = $this->db->topic->findOne ( array (
					'_id' => new MongoId ( $topic_id ) 
			), array (
					'topic_ownerName' => 1 
			) );
		} catch ( Exception $e ) {
			return - 1;
		}
		if ($cursor == NULL) {
			return 4; // topic_id does not exist
		}
		// get topic_owner_id
		$topic_ownerName = $cursor ['topic_ownerName'];
		if ($topic_ownerName == $user_name) {
			// user is the topic_owner
			try {
				$this->db->reply->update ( array (
						'_id' => new MongoId ( $reply_id ) 
				), array (
						'$set' => array (
								'reply_hostAgreeCount' => 1 
						) 
				) );
			} catch ( Exception $e ) {
				return - 1;
			}
			return 1;
		} else {
			if (in_array ( $user_name, $reply_guestAgreeNames )) {
				return 1;
			} else {
				array_push ( $reply_guestAgreeNames, $user_name );
				try {
					$this->db->reply->update ( array (
							'_id' => new MongoId ( $reply_id ) 
					), array (
							'$set' => array (
									'reply_guestAgreeNames' => $reply_guestAgreeNames 
							) 
					) );
				} catch ( Exception $e ) {
					return - 1;
				}
				return 1;
			}
		}
	}
	
	/**
	 * get total number of replies written by a given $user_name
	 *
	 * @param $user_name -
	 *        	string -
	 * @return a non-negative integer - total number of replies of $user_name
	 *         -1: $user_name does not exist
	 *        
	 *        
	 */
	function countMyReplyByName($user_name) {
		$state = $this->verifyUserAndWriteLog ( __METHOD__ );
		if ($state != 1) {
			return $state;
		}
		$cursor = $this->db->user->findOne ( array (
				'user_name' => $user_name 
		) );
		if ($cursor == NULL) {
			return 2;
		}
		try {
			$count = $this->db->reply->find ( array (
					'reply_senderName' => $user_name 
			) )->count ();
			return $count;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	/**
	 * get replied topics of user
	 *
	 * @param $user_name -
	 *        	string
	 *        	$time - int - UNIX time
	 * @return array(topic) - array of topics
	 *         empty array - no replied topics
	 *         -1 - user_name does not exist
	 *        
	 */
	function queryRepliedTopicByName($user_name, $time) {
		if (!isset ( $_COOKIE ['user'] )|| $_COOKIE['user'] != $user_name){
			return -4;
		}
		$state = $this->verifyUserAndWriteLog ( __METHOD__ );
		if ($state != 1) {
			return $state;
		}
		try {
			$cursor = $this->db->user->findOne ( array (
					'user_name' => $user_name 
			) );
		} catch ( Exception $e ) {
			return - 1;
		}
		if ($cursor == NULL) {
			return 2;
		}
		if (is_int ( $time ) == false) {
			try {
				$time = ( int ) $time;
			} catch ( Exception $e ) {
				return - 1;
			}
		}
		$topic_ids = $this->db->reply->distinct ( 'reply_topicID', array (
				'reply_senderName' => $user_name 
		) );
		$topic_mongoIds = array ();
		foreach ( $topic_ids as $topic_id ) {
			array_push ( $topic_mongoIds, new MongoId ( $topic_id ) );
		}
		try {
			$cursor = $this->db->topic->find ( array (
					'$and' => array (
							array (
									'_id' => array (
											'$in' => $topic_mongoIds 
									) 
							),
							array (
									'topic_postTime' => array (
											'$lt' => $time 
									) 
							) 
					) 
			) )->sort ( array (
					'topic_postTime' => - 1 
			) );
		} catch ( Exception $e ) {
			return - 1;
		}
		$maxCount = 30; // 只返回30条
		$count = 0;
		$re = array ();
		foreach ( $cursor as $topic_document ) {
			try {
				$userCursor = $this->db->user->findOne ( array (
						"user_name" => $topic_document ['topic_ownerName'] 
				), array (
						"user_nickname" => 1,
						"user_pic" => 1 
				) );
			} catch ( Exception $e ) {
				return - 1;
			}
			$topic_document ['user_nickname'] = $userCursor ['user_nickname'];
			$topic_document ['user_pic'] = $userCursor ['user_pic'];
			array_push ( $re, $topic_document );
			if ($count >= 30) {
				break;
			} else {
				$count ++;
			}
		}
		return json_encode ( $re );
	}
	function queryMyReplyAgreedTopicByName($user_name, $time) {
		if (!isset ( $_COOKIE ['user'] )|| $_COOKIE['user'] != $user_name){
			return -4;
		}
		$state = $this->verifyUserAndWriteLog ( __METHOD__ );
		if ($state != 1) {
			return $state;
		}
		try {
			$cursor = $this->db->user->findOne ( array (
					'user_name' => $user_name 
			) );
		} catch ( Exception $e ) {
			return - 1;
		}
		if ($cursor == NULL) {
			return 2;
		}
		if (is_int ( $time ) == false) {
			try {
				$time = ( int ) $time;
			} catch ( Exception $e ) {
				return - 1;
			}
		}
		$reply_ids = $this->db->reply->find ( array (
				'reply_senderName' => $user_name 
		) );
		
		$replyAgreedTopic_ids = array ();
		foreach ( $reply_ids as $reply_id ) {
			if ($reply_id ['reply_hostAgreeCount'] > 0 || count ( $reply_id ['reply_guestAgreeNames'] ) > 0) {
				array_push ( $replyAgreedTopic_ids, $reply_id ['reply_topicID'] );
			}
		}
		
		$topic_mongoIds = array ();
		foreach ( $replyAgreedTopic_ids as $replyAgreedTopic_id ) {
			array_push ( $topic_mongoIds, new MongoId ( $replyAgreedTopic_id ) );
		}
		try {
			$cursor = $this->db->topic->find ( array (
					'$and' => array (
							array (
									'_id' => array (
											'$in' => $topic_mongoIds 
									) 
							),
							array (
									'topic_postTime' => array (
											'$lt' => $time 
									) 
							) 
					) 
			) )->sort ( array (
					'topic_postTime' => - 1 
			) );
		} catch ( Exception $e ) {
			return - 1;
		}
		$maxCount = 30; // 只返回30条
		$count = 0;
		$re = array ();
		foreach ( $cursor as $topic_document ) {
			try {
				$userCursor = $this->db->user->findOne ( array (
						"user_name" => $topic_document ['topic_ownerName'] 
				), array (
						"user_nickname" => 1,
						"user_pic" => 1 
				) );
			} catch ( Exception $e ) {
				return - 1;
			}
			$topic_document ['user_nickname'] = $userCursor ['user_nickname'];
			$topic_document ['user_pic'] = $userCursor ['user_pic'];
			array_push ( $re, $topic_document );
			if ($count >= 30) {
				break;
			} else {
				$count ++;
			}
		}
		return json_encode ( $re );
	}
	
	function queryMyAgreedReplyByName($user_name, $time) {
		if (!isset ( $_COOKIE ['user'] )|| $_COOKIE['user'] != $user_name){
			return -4;
		}
		$state = $this->verifyUserAndWriteLog ( __METHOD__ );
		if ($state != 1) {
			return $state;
		}
		try {
			$cursor = $this->db->user->findOne ( array (
					'user_name' => $user_name
			) );
		} catch ( Exception $e ) {
			return - 1;
		}
		if ($cursor == NULL) {
			return 2;
		}
		if (is_int ( $time ) == false) {
			try {
				$time = ( int ) $time;
			} catch ( Exception $e ) {
				return - 1;
			}
		}
		$reply_ids = $this->db->reply->find ( array (
				'$and' => array(
						array (
								'reply_senderName' => $user_name
						),							
						array (
									'reply_time' => array (
											'$lt' => $time )
						)
					)
				)
		)->sort ( array (
					'reply_time' => - 1 
			) );
		
		$replyAgreedTopic_ids = array ();
		foreach ( $reply_ids as $reply_id ) {
			if ($reply_id ['reply_hostAgreeCount'] > 0 || count ( $reply_id ['reply_guestAgreeNames'] ) > 0) {
				array_push ( $replyAgreedTopic_ids, $reply_id );
			}
		}
		$maxCount = 30; // 只返回30条
		$count = 0;
		$re = array ();
		foreach ( $replyAgreedTopic_ids as $replyAgreedTopic_id  ) {
			try {
				$userCursor = $this->db->user->findOne ( array (
						"user_name" => $replyAgreedTopic_id['reply_senderName']
				), array (
						"user_nickname" => 1,
						"user_pic" => 1
				) );
			} catch ( Exception $e ) {
				return - 1;
			}
			try {
				$topicCursor = $this->db->topic->findOne ( array (
						'_id' => new MongoId ($replyAgreedTopic_id['reply_topicID'])
				) );
			} catch ( Exception $e ) {
				return - 1;
			}
			$replyAgreedTopic_id ['user_nickname'] = $userCursor ['user_nickname'];
			$replyAgreedTopic_id ['user_pic'] = $userCursor ['user_pic'];
			$replyAgreedTopic_id ['reply_topicTitle'] = $topicCursor ['topic_title'];
			
			array_push ( $re, $replyAgreedTopic_id );
			if ($count >= 30) {
				break;
			} else {
				$count ++;
			}
		}
		
		return json_encode ( $re );
	}
	
	function countMyRepliedTopicByName($user_name) {
		$state = $this->verifyUserAndWriteLog ( __METHOD__ );
		if ($state != 1) {
			return $state;
		}
		try {
			$cursor = $this->db->user->findOne ( array (
					'user_name' => $user_name 
			) );
		} catch ( Exception $e ) {
			return - 100;
		}
		if ($cursor == NULL) {
			return 2; // user name does not exist
		}
		try {
			$array_reply = $this->db->reply->distinct ( 'reply_topicID', array (
					'reply_senderName' => $user_name 
			) );
			$count = count ( $array_reply );
		} catch ( Exception $e ) {
			return - 101;
		}
		return $count;
	}
	function countMyReplyAgreeByName($user_name) {
		$state = $this->verifyUserAndWriteLog ( __METHOD__ );
		if ($state != 1) {
			return $state;
		}
		try {
			$cursor = $this->db->user->findOne ( array (
					'user_name' => $user_name 
			) );
		} catch ( Exception $e ) {
			return - 100; // db exception
		}
		if ($cursor == NULL) {
			return 2; // user name doest not exist
		}
		
		try {
			$cursor = $this->db->reply->find ( array (
					'reply_senderName' => $user_name 
			) );
		} catch ( Exception $e ) {
			return - 1;
		}
		$count = 0;
		foreach ( $cursor as $document ) {
			$count += $document ['reply_hostAgreeCount'] + count ( $document ['reply_guestAgreeNames'] );
		}
		return $count;
	}
	function countMyAgreedReplyByName($user_name) {
		$state = $this->verifyUserAndWriteLog ( __METHOD__ );
		if ($state != 1) {
			return $state;
		}
		try {
			$cursor = $this->db->user->findOne ( array (
					'user_name' => $user_name
			) );
		} catch ( Exception $e ) {
			return - 100; // db exception
		}
		if ($cursor == NULL) {
			return 2; // user name doest not exist
		}
	
		try {
			$cursor = $this->db->reply->find ( array (
					'reply_senderName' => $user_name
			) );
		} catch ( Exception $e ) {
			return - 1;
		}
		$count = 0;
		foreach ( $cursor as $document ) {
			if ($document ['reply_hostAgreeCount'] > 0 || count ( $document ['reply_guestAgreeNames'] ) > 0) {
				$count ++;
			}
		}
		return $count;
	}
	function verifyUserAndWriteLog($methodname) {
		// debug
		// return 1;
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), $methodname );
		return 1;
	}
}
// $reply = new Reply();
// addReply($topic_id, $reply_sender_name, $reply_content, $reply_visibility)
// echo $reply->addReply('54c77543a3136a8bdd000000','Oneto','this is a reply content only visiable to host.',0);
// echo '<br/>like reply: ';
// echo $reply->likeReply('54d200d9a3136a7504000000', 'zhonghui');
// echo '<br/>get all reply <br/>';
// getAllReply($topic_id, $user_name){
// echo $reply->queryAllReplyByTopic('54db5d40a3136a19de000006', 'lonblues', '1423845147');
// echo '<br/>count reply topic num:';
// echo $reply->countMyRepliedTopicByName('arition');
// echo '<br/>count my reply agree<br/>';
// echo $reply->countMyReplyAgreeByName('arition');
?>