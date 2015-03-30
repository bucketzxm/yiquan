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
		echo '</tr></thead>';
		
		for($i = $start; $i <= min ( ($i + $length), count ( $arr ) ) - 1; $i ++) {
			$pid = $arr [$i] ['_id'] ['$id'];
			echo '<tr>';
			
			echo '<td>' . $arr [$i] ['topic_ownerName'] . '</td>';
			echo '<td>' . $arr [$i] ['topic_type'] . '</td>';
			echo '<td>' . $arr [$i] ['topic_title'] . '</td>';
			echo '<td>' . date ( 'Y-m-d H:i:s', $arr [$i] ['topic_postTime'] ) . '</td>';
			echo '<td>' . $arr [$i] ['topic_replyCount'] . '</td>';
			echo '<td><a href="?action=edit' . '&tid=' . $pid . '">编辑</a>';
			echo '<td><a href="?action=delete' . '&tid=' . $pid . '">删除</a>';
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
	function showeditSystemtopic_form($data) {
		$arr = json_decode ( $data, true );
		echo '<div><form method="post" action="?action=edit">';
		echo '<div class="form-group"><h2>请输入话题内容</h2>';
		echo '<input type="hidden" name="topicid" value="' . $arr['_id']['$id'] . '" />';
		echo '<textarea class="form-control" rows="3" cols="80" name="title">' . $arr ['topic_title'] . '</textarea></div>';
		echo '<div class="form-group"><h2>请输入标签</h2>';
		$labelword = "";
		for($i = 0; $i < count ( $arr ['topic_labels'] ); $i ++) {
			$labelword .= $arr ['topic_labels'] [$i];
			if ($i < count ( $arr ['topic_labels'] ) - 1) {
				$labelword .= ',';
			}
		}
		
		echo '<input class="form-control" type="text"  name="labels" value="' . $labelword . '"/></div>';
		echo '<div class="form-group"><input type="submit" value="提交" /></div>';
		echo '</form></div>';
	}
	function DeleteSysTopicById_form($topicId) {
		echo '<form method="post" action="?action=delete">';
		echo '确定删除该话题吗？';
		echo '<input type="hidden" name="topicid" value="' . $topicId . '" />';
		echo '<div class="form-group"><input type="submit" value="确定" /></div>';
		echo '</form>';
	}
}

?>
