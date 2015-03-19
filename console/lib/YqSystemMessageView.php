<?php
require_once 'YqSystemMessage.php';
class YqSystemMessageView extends YqSystemMessage {
	function showMessagetype($name) {
		echo '<select name="' . $name . '">';
	    echo '<option value="userMessage">userMessage</option>';
	    echo '<option value="webview">webview</option>';
	    echo '<option value="systemNotice">systemNotice</option>';
	    echo '<option value="newReply">newReply</option>';
	    echo '<option value="invitation">invitation</option>';
	    echo '</select>';
	}
	function addSystemMessage_form() {
		echo '<div><form method="post" action="?action=addsystemtopic">';
		echo '<div class="form-group"><h2>请输入消息标题</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="title"></textarea></div>';
		echo '<div class="form-group"><h2>请输入消息内容</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="detail"></textarea></div>';
		echo '<div class="form-group"><h2>请输入接收人</h2>';
		echo '<input class="form-control" type="text"  name="reciver" /></div>';
		echo '<div class="form-group"><h2>请输入标签</h2>';
		echo '<input class="form-control" type="text"  name="labels" /></div>';
		echo '<div class="form-group"><h2>请选择消息类型</h2>';
		$this->showMessagetype('type');
		echo '</div>';
		echo '<div class="form-group"><h2>是否群发</h2>';
		echo '<input class="form-control" type="checkbox"  name="forall" value="0"/></div>';
		echo '<div class="form-group"><input type="submit" value="提交" /></div>';
		echo '</form></div>';
	}
}
?>
