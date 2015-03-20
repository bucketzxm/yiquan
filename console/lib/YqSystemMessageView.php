<?php
require_once 'YqSystemMessage.php';
class YqSystemMessageView extends YqSystemMessage {
	function showMessagetype($name) {
		echo '<select name="' . $name . '">';
		echo '<option value="userMessage">userMessage</option>';
		echo '<option value="webview">webview</option>';
		echo '<option value="systemNotice">systemNotice</option>';
		echo '<option value="invitation">invitation</option>';
		echo '</select>';
	}
	function addSystemMessage_form() {
		echo '<div><form method="post" action="?action=addSystemMessage">';
		echo '<div class="form-group"><h2>请输入消息标题</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="title"></textarea></div>';
		echo '<div class="form-group"><h2>请输入消息内容</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="detail"></textarea></div>';
		echo '<div class="form-group"><h2>请输入接收人</h2>';
		echo '<input class="form-control" type="text"  name="reciver" /></div>';
		echo '<div class="form-group"><h2>请输入标签</h2>';
		echo '<input class="form-control" type="text"  name="labels" /></div>';
		echo '<div class="form-group"><h2>请选择消息类型</h2>';
		$this->showMessagetype ( 'type' );
		echo '</div>';
		echo '<div class="form-group"><h2>是否群发</h2>';
		echo '<input class="form-control" type="checkbox"  name="forall" /></div>';
		echo '<div class="form-group"><input type="submit" value="提交" /></div>';
		echo '</form></div>';
	}
	function showMessage_table($arr, $start, $limit) {
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr><th>发件人</th><th>收件人</th><th>标题</th><th>发送时间</th><th>类型</th><th>未读</th><th>操作1</th></tr></thead>';
		for($i = $start; $i <= $start + $limit - 1 && $i < count ( $arr ); $i ++) {
			echo '<tr>';
			echo '<td>' . $arr [$i] ['message_senderId'] . '</td>';
			echo '<td>' . $arr [$i] ['message_receiverId'] . '</td>';
			echo '<td>' . $arr [$i] ['message_title'] . '</td>';
			echo '<td>' . date ( "Y-m-d h:m:s", $arr [$i] ['message_postTime'] ) . '</td>';
			echo '<td>' . $arr [$i] ['message_type'] . '</td>';
			echo '<td>' . $arr [$i] ['message_life'] . '</td>';
			echo '<td><a href="?action=detail&mindex=' . $i . '&messageid=' . ($arr [$i] ['_id']->{'$id'}) . '">详细</a></td>';
			echo '</tr>';
		}
		echo '</table></div>';
	}
	function showMessagePages_div($arr, $action) {
		echo '<div>';
		for($i = 0; $i < count ( $arr ); $i ++) {
			echo '<a href="?action=' . $action . '&page=' . $i . '&limit=30">' . ($i + 1) . '</a>  ';
		}
		echo '</div>';
	}
	function showMessageDetail($arr, $mindex) {
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<tr><td>发件人</td><td>' . $arr [$mindex] ['message_senderId'] . '</td></tr>';
		echo '<tr><td>收件人</td><td>' . $arr [$mindex] ['message_receiverId'] . '</td></tr>';
		echo '<tr><td>主题</td><td>' . $arr [$mindex] ['message_title'] . '</td></tr>';
		if (isset ( $arr [$mindex] ['message_detail'] )) {
			echo '<tr><td>内容</td><td>' . $arr [$mindex] ['message_detail'] . '</td></tr>';
		}
		echo '<tr><td>时间</td><td>' . $arr [$mindex] ['message_postTime'] . '</td></tr>';
		echo '<tr><td>类型</td><td>' . $arr [$mindex] ['message_type'] . '</td></tr>';
		echo '</table></div>';
	}
}
?>
