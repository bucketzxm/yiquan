<?php
require_once 'YqBase.php';
class Message extends YqBase {
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
	function addMessage($message_senderId, $message_receiverId, $message_type, $message_title, $message_labels, $message_topicID) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$message_postTime = time ();
		
		$m_labels = explode ( ',', $message_labels );
		
		$data = array (
				'message_senderId' => $message_senderId,
				'message_receiverId' => $message_receiverId,
				'message_type' => $message_type,
				'message_title' => $message_title,
				'message_life' => 1,
				'message_postTime' => $message_postTime,
				'message_labels' => $m_labels,
				'message_topicID' => $message_topicID 
		);
		
		try {
			$cursor = $this->db->message->findOne ( 
					array (
						'message_receiverId' => $message_receiverId,
						'message_topicID'	 => $message_topicID,
						'message_life'		 => 1
					)
				);
			if ( $cursor == NULL ) {
				$result = $this->db->message->insert ( $data );
				return 1;
			}
			else
				return 0;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	// 查询用户收到的
	function queryMessageByName($message_receiverId) {
		try {
			if ($this->yiquan_version == 0) {
				return - 2;
			}
			
			if ($this->checkToken () == 0) {
				return - 3;
			}
			$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
			$result = $this->db->message->find ( array (
					'message_receiverId' => $message_receiverId,
					'message_life' => 1 
			) )->sort ( array (
					'message_postTime' => - 1 
			) );
			$res = array ();
			foreach ( $result as $key => $value ) {
				$user = $this->db->user->findOne ( array (
						'user_name' => $value ['message_senderId'] 
				) );
				$value ['sender_nickname'] = $user ['user_nickname'];
				array_push ( $res, $value );
			}
			return json_encode ( $res );
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	// 表示用户收到了数据（就是查看了）
	function readMessage($message_id) {
		try {
			if ($this->yiquan_version == 0) {
				return - 2;
			}
			
			if ($this->checkToken () == 0) {
				return - 3;
			}
			$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
			$result = $this->db->message->update ( array (
					'_id' => new Mongoid ( $message_id ) 
			), 			// 条件
			array (
					'$set' => array (
							'message_life' => 0 
					) 
			) ); // 把life set为0
			
			return 1;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
}
?>
