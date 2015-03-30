<?php
require_once 'YqTopic.php';
class YqSystemTopic extends YqTopic {
	function listallsystemTopicPages($arr, $limit) {
		$pages = [ ];
		$i = 0;
		foreach ( $arr as $v ) {
			if ($i % $limit == 0) {
				$pages [] = $i;
			}
			$i ++;
		}
		return $pages;
	}
	function getSystemTopics($topic_direction, $topic_time) {
		return json_decode ( $this->queryTopicByName ( 'system', 'second', $topic_direction, $topic_time, 'dialogue' ), true );
	}
	function addSystemtopic($topic_networks, $topic_ownerName, $topic_type, $topic_title, $topic_labels) {
		return $this->addTopic ( $topic_networks, $topic_ownerName, $topic_type, $topic_title, $topic_labels );
	}
	function updateSystemTopic($postdata) {
		$row = $this->db->topic->findOne ( array (
				'_id' => new MongoId ( $postdata ['topicid'] ) 
		) );
		
		if ($row == null)
			return 0;
		
		$m_labels = explode ( ',', $postdata ['labels'] );
		$row ['topic_labels'] = $m_labels;
		$row ['topic_title'] = $postdata ['title'];
		$row ['topic_postTime'] = time ();
		$this->db->topic->save ( $row );
		return 1;
	}
}

?>
