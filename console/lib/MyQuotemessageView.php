<?php
require_once 'MyQuotemessage.php';
require_once 'MyQuoteuser.php';

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
class MyQuotemessageView extends Quotemessage {
	// private $dbname = 'test';
	private $table = 'Quote';
	function showQuoteMessgaes_table($arr, $start, $limit) {
		$q = new Quoteuser ();
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr><th>时间</th><th>标题</th><th>接收人</th><th>撰写人</th><th>操作1</th></tr></thead>';
		for($i = $start; $i <= $start + $limit - 1 && $i < count ( $arr ); $i ++) {
			echo '<tr>';
			echo '<td>' . date ( "Y-m-d h:m:s", $arr [$i] ['message_postTime'] ) . '</td>';
			echo '<td>' . $arr [$i] ['message_title'] . '</td>';
			if ($arr [$i] ['message_receiverId'] == 'system') {
				echo '<td>' . $arr [$i] ['message_receiverId'] . '</td>';
			} else {
				echo '<td>' . $q->getUserMobileByID ( $arr [$i] ['message_receiverId'] ) . '</td>';
			}
			echo '<td>' . $q->getUserMobileByID ( $arr [$i] ['message_senderId'] ) . '</td>';
			echo '<td><a href="?action=detail&mindex=">详细</a></td>';
			echo '</tr>';
		}
		echo '</table></div>';
	}
}

?>
