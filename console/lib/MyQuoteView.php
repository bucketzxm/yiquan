<?php
require_once 'MyQuote.php';
require_once 'MyQuoteuser.php';

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
class MyQuoteView extends Quote {
	// private $dbname = 'test';
	private $table = 'Quote';
	function showQuotes_table($arr, $start, $limit) {
		$q = new Quoteuser ();
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr><th>时间</th><th>标题</th><th>签名</th><th>撰写人</th><th>用户头像</th><th>操作1</th></tr></thead>';
		for($i = $start; $i <= $start + $limit - 1 && $i < count ( $arr ); $i ++) {
			echo '<tr>';
			echo '<td>' . date ( "Y-m-d h:m:s", $arr [$i] ['quote_time'] ) . '</td>';
			echo '<td>' . $arr [$i] ['quote_title'] . '</td>';
			echo '<td>' . $arr [$i] ['quote_signature'] . '</td>';
			echo '<td>' . $q->getUserMobileByID ( $arr [$i] ['quote_ownerID'] ) . '</td>';
			if (isset ( $arr [$i] ['user_pic'] )) {
				echo '<td>' . '<img width="40px" src="http://' . $arr [$i] ['user_pic'] . '" /></td>';
			} else {
				echo '<td></td>';
			}
			echo '<td><a href="?action=delete&mindex=' . $arr [$i] ['_id']->{'$id'} . '">删除</a></td>';
			echo '</tr>';
		}
		echo '</table></div>';
	}
	
	function showDeleteView($id)
	{
		echo '<form method="post" action="?action=delete">';
		echo '确定删除该吗？';
		echo $id;
		echo '<input type="hidden" name="qid" value="' . $id . '" />';
		echo '<div class="form-group"><input type="submit" value="确定" /></div>';
		echo '</form>';
	}
}

?>
