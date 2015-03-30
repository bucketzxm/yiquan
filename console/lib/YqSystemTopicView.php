<?php
require_once 'YqSystemTopic.php';
class YqSystemTopicView extends YqSystemTopic {
	function showTopicPageLink($arr, $limit) {
		echo '<div>';
		foreach ( $arr as $key => $v ) {
			$ky = $key + 1;
			echo '<a href="?view&page=' . $key . '">' . $ky . '</a> ';
		}
		echo '</div>';
	}
	function showSystemTopic_table($arr, $start, $length) {
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		echo '<th>发送者</th>';
		echo '<th>话题类型</th>';
		echo '<th>标题</th>';
		echo '<th>产生时间</th>';
		echo '<th>回复次数</th>';
		echo '<th>' . '操作1' . '</th>';
		echo '<th>' . '操作2' . '</th>';
		echo '<th>' . '操作3' . '</th>';
		echo '<th>' . '操作4' . '</th>';
		echo '</tr></thead>';
		
		for($i = $start; $i <= min ( ($i + $length), count ( $arr ) ) - 1; $i ++) {
			$pid = $arr [$i] ['_id'] ['$id'];
			echo '<tr>';
			
			echo '<td>' . $arr [$i] ['topic_ownerName'] . '</td>';
			echo '<td>' . $arr [$i] ['topic_type'] . '</td>';
			echo '<td>' . $arr [$i] ['topic_title'] . '</td>';
			echo '<td>' . date ( 'Y-m-d H:i:s', $arr [$i] ['topic_postTime'] ) . '</td>';
			echo '<td>' . $arr [$i] ['topic_replyCount'] . '</td>';
			echo '<td><a href="?action=edit' . '&uid=' . $pid . '">编辑</a>';
			echo '<td><a href="?action=delete' . '&uid=' . $pid . '">删除</a>';
			echo '<td><a href="?action=behavior' . '&uid=' . $pid . '&type=days' . '&value=7' . '">行为分析</a>';
			echo '<td><a href="?action=delete' . '&uid=' . $pid . '">他的话题</a>';
			echo '</tr>';
		}
		
		echo '</table></div>';
	}
	function showaddSystemtopic_form() {
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
