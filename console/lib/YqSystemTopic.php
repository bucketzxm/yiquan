<?php
require_once 'YqTopic.php';
class YqSystemTopic extends YqTopic {
	function listallsystemTopicPages($arr, $limit) {
		$pages = [ ];
		$i=0;
		foreach ($arr as $v)
		{
			if($i % $limit==0)
			{
				$pages[]=i;
			}
			$i++;
		}
		return $pages;
	}
	function getSystemTopics($topic_direction, $topic_time) {
		return json_decode ( $this->queryTopicByName ( 'system', 'second', $topic_direction, $topic_time, 'dialogue' ), true );
	}
	function addSystemtopic($topic_networks, $topic_ownerName, $topic_type, $topic_title, $topic_labels) {
		return $this->addTopic ( $topic_networks, $topic_ownerName, $topic_type, $topic_title, $topic_labels );
	}
	
	
	
}

?>
