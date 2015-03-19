<?php
require_once 'YqMessage.php';
class YqSystemMessage extends YqMessage {
	function addSystemMessage($toall, $message_receiverId, $message_type, $message_title, $message_labels, $message_topicID, $message_topicTitle, $message_detail) {
		return $this->addMessagev2 ( 'system', $message_receiverId, $message_type, $message_title, $message_labels, $message_topicID, $message_topicTitle, $message_detail );
	}
}

?>
