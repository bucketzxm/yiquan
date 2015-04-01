<?php
require_once 'YqMessage.php';
class YqSystemMessage extends YqMessage {
	function addMessagetolog($message_senderId, $message_receiverId, $message_type, $message_title, $message_labels, $message_topicID, $message_topicTitle, $message_detail, $message_webViewHeader, $message_webViewURL) {
		$m_labels = explode ( ',', $message_labels );
		$message_postTime = time ();
		$data = array (
				'message_senderId' => $message_senderId,
				'message_receiverId' => $message_receiverId,
				'message_type' => $message_type,
				'message_title' => $message_title,
				'message_detail' => $message_detail,
				'message_life' => 1,
				'message_postTime' => $message_postTime,
				'message_labels' => $m_labels,
				'message_topicID' => $message_topicID,
				'message_topicTitle' => $message_topicTitle,
				'message_webViewHeader' => $message_webViewHeader,
				'message_webViewURL' => $message_webViewURL 
		);
		
		try {
			$cursor = $this->db->sysmessagelog->findOne ( array (
					'message_receiverId' => $message_receiverId,
					'message_topicID' => $message_topicID,
					'message_type' => 'newReply',
					'message_life' => 1 
			) );
			if ($cursor == NULL) {
				$result = $this->db->sysmessagelog->insert ( $data );
				return 1;
			} else
				return 0;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	function addSystemMessage($toall = 0, $message_receiverId = '', $message_type, $message_title, $message_labels, $message_detail, $message_webViewHeader, $message_webViewURL) {
		if ($toall == 1) {
			$this->addMessagetolog ( 'system', 'systemToAll', $message_type, $message_title, $message_labels, '', '', $message_detail, $message_webViewHeader, $message_webViewURL );
			$cus = $this->db->user->find ();
			$con = 0;
			while ( $cus->hasNext () ) {
				$doc = $cus->getNext ();
				if ($doc ['user_name'] != 'system') {
					if ($this->addMessagev2 ( 'system', $doc ['user_name'], $message_type, $message_title, $message_labels, '', '', $message_detail, $message_webViewHeader, $message_webViewURL )) {
						$con ++;
					}
				}
			}
			return $con;
		} else {
			$this->addMessagetolog ( 'system', $message_receiverId, $message_type, $message_title, $message_labels, '', '', $message_detail, $message_webViewHeader, $message_webViewURL );
			$m_recivers = explode ( ',', $message_receiverId );
			$con = 0;
			for($i = 0; $i < count ( $m_recivers ); $i ++) {
				if ($this->addMessagev2 ( 'system', $m_recivers [$i], $message_type, $message_title, $message_labels, '', '', $message_detail, $message_webViewHeader, $message_webViewURL )) {
					$con ++;
				}
			}
			return $con;
		}
		return 0;
	}
	function getSystemMessage($way, $type = '', $starttime = 0, $endtime) {
		try {
			$endtime = ( int ) $endtime;
			$starttime = ( int ) $starttime;
			if ($way == 'unread') {
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
			} elseif ($way == 'readed') {
				if ($type == '') {
					$result = $this->db->oldMessage->find ( array (
							'message_receiverId' => 'system',
							'message_postTime' => array (
									'$lt' => $endtime,
									'$gte' => $starttime 
							) 
					) )->sort ( array (
							'message_postTime' => - 1 
					) );
				} else {
					$result = $this->db->oldMessage->find ( array (
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
			} elseif ($way == 'send') {
				if ($type == '') {
					$result = $this->db->sysmessagelog->find ( array (
							'message_senderId' => 'system',
							'message_postTime' => array (
									'$lt' => $endtime,
									'$gte' => $starttime 
							) 
					) )->sort ( array (
							'message_postTime' => - 1 
					) );
				} else {
					$result = $this->db->sysmessagelog->find ( array (
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
	function replySystemMessage($toall, $message_id, $reciver, $message_type, $message_title, $message_labels, $message_detail, $message_webViewHeader, $message_webViewURL) {
		$row = $this->db->oldMessage->findOne ( array (
				'_id' => new MongoId ( $message_id ) 
		) );
		if ($row == null)
			return 0;
		
		if ($row ['message_senderId'] != $reciver)
			return 0;
		
		return $this->addSystemMessage ( $toall, $reciver, $message_type, $message_title, $message_labels, '', '', $message_detail, $message_webViewHeader, $message_webViewURL );
	}
}

?>
