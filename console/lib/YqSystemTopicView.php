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
	
	
	function showaddSystemtopic_form()
	{
		echo '<div><form method="post" action="?action=addsystemtopic">';
		echo '<div class="form-group"><h2>请输入话题内容</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="title"></textarea></div>';
		echo '<div class="form-group"><h2>请输入标签</h2>';
		echo '<input class="form-control" type="text"  name="labels" /></div>';
		echo '<div class="form-group"><input type="submit" value="提交" /></div>';
		echo '</form></div>';
	}
}

?>
