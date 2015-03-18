<?php
require_once 'YqSystemTopic.php';
class YqSystemTopicView extends YqSystemTopic {
	function showTopicPageLink($arr, $limit) {
		echo '<div><ul>';
		foreach ( $arr as $key => $v ) {
			$ky = $key + 1;
			echo '<li>' . '<a href="?view&page=' . $key . '">' . $ky . '</a></li>';
		}
		echo '</ul></div>';
	}
	function showSystemTopic_table($arr, $start, $end) {
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		
		foreach ( $arr [0] as $key => $v ) {
			switch ($key) {
				case 'user_relationships' :
					break;
				case 'user_blocklist' :
					break;
				case 'user_pin' :
					break;
				case '_id' :
					break;
				default :
					echo '<th>' . $key . '</th>';
			}
		}
		echo '<th>' . '操作1' . '</th>';
		echo '<th>' . '操作2' . '</th>';
		echo '<th>' . '操作3' . '</th>';
		echo '<th>' . '操作4' . '</th>';
		echo '</tr></thead>';
		
		for($i = $start; i <= $end; $i ++) {
			echo '<tr>';
			foreach ( $arr [i] as $key => $v ) {
				echo '<td>'.$v.'</td>';
			}
			echo '<td><a href="?action=edit' . '&uid=' . $pid . '">编辑</a>';
			echo '<td><a href="?action=delete' . '&uid=' . $pid . '">删除</a>';
			echo '<td><a href="?action=behavior' . '&uid=' . $pid . '&type=days' . '&value=7' . '">行为分析</a>';
			echo '<td><a href="?action=delete' . '&uid=' . $pid . '">他的话题</a>';
			echo '</tr>';
		}
	}
}

?>
