<?php
require_once 'YqTopic.php';
class YqSystemTopic extends YqTopic {
	
	function listallsystemTopicPages($limit) {
		$cus = $this->db->topic->find ()->sort ( array (
				'_id' => 1
		) );
		$pages = [ ];
		$c = - 1;
		while ( $cus->hasNext () ) {
			$doc = $cus->getNext ();
			$c ++;
			echo $c;
			if ($c % $limit == 0) {
				$pages [] = $doc ['_id']->{'$id'};
			}
		}
		return $pages;
	}
	function getSystemTopics($topic_direction, $topic_time) {
		return json_decode ( $this->queryTopicByName ( 'system', 'second', $topic_direction, $topic_time, 'dialogue' ), true );
	}
}

?>
