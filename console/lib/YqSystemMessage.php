<?php
require_once 'YqMessage.php';
class YqSystemMessage extends YqMessage {
	function addSystemMessage($toall = 0, $message_receiverId = '', $message_type, $message_title, $message_labels, $message_detail) {
		if ($toall == 1) {
			$cus = $this->db->user->find ();
			$con = 0;
			while ( $cus->hasNext () ) {
				$doc = $cus->getNext ();
				if ($doc ['user_name'] != 'system') {
					if ($this->addMessagev2 ( 'system', $doc ['user_name'], $message_type, $message_title, $message_labels, '', '', $message_detail )) {
						$con ++;
					}
				}
			}
			return $con;
		}
		return $this->addMessagev2 ( 'system', $message_receiverId, $message_type, $message_title, $message_labels, '', '', $message_detail );
	}
	function getSystemMessage($way, $type = '', $starttime = 0, $endtime) {
		try {
			$endtime = ( int ) $endtime;
			$starttime = ( int ) $starttime;
			if ($way == 'recive') {
				if ($type == '') {
					$result = $this->db->message->find ( array (
							'message_receiverId' => 'system',
							'message_postTime' => array (
									'$lt' => $endtime,
									'$gte' => $starttime 
							) 
					) )->sort ( array (
							'message_postTime' => - 1 
					) );
				} else {
					$result = $this->db->message->find ( array (
							'message_receiverId' => 'system',
							'message_postTime' => array (
									'$lt' => $endtime,
									'$gte' => $starttime 
							),
							
							'message_type' => $type 
					) )->sort ( array (
							'message_postTime' => - 1 
					) );
				}
			} else if ($way == 'send') {
				if ($type == '') {
					$result = $this->db->message->find ( array (
							'message_senderId' => 'system',
							'message_postTime' => array (
									'$lt' => $endtime,
									'$gte' => $starttime 
							) 
					) )->sort ( array (
							'message_postTime' => - 1 
					) );
				} else {
					$result = $this->db->message->find ( array (
							'message_senderId' => 'system',
							'message_postTime' => array (
									'$lt' => $endtime,
									'$gte' => $starttime 
							),
							'message_type' => $type 
					) )->sort ( array (
							'message_postTime' => - 1 
					) );
				}
			}
			$count = 0;
			$res = array ();
			foreach ( $result as $key => $value ) {
				$user = $this->db->user->findOne ( array (
						'user_name' => $value ['message_senderId'] 
				) );
				$value ['sender_nickname'] = $user ['user_nickname'];
				$value ['sender_pic'] = $user ['user_pic'];
				array_push ( $res, $value );
				$count ++;
			}
			return $res;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	function getSystemMessagePages($arr, $limit) {
		$res = [ ];
		for($i = 0; $i < count ( $arr ); $i ++) {
			if ($i % $limit == 0) {
				$res [] = $i;
			}
		}
		return $res;
	}
}

?>
