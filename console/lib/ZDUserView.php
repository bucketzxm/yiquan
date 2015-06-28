<?php
require_once 'ZDUser.php';

/* Report all errors except E_NOTICE */
// error_reporting ( E_ALL & ~ E_NOTICE );
class ZDUserView extends ZDUser {
	function listallusers_table($arr, $start, $len) {
		//var_dump($arr);
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		
		echo '<th>用戶名</th>';
		echo '<th>行业</th>';
		echo '<th>ID</th>';
		echo '<th>状态</th>';

		echo '</tr></thead>';
		
		for($i = $start; $i < min ( $start + $len, count ( $arr ) ); $i ++) {
			echo '<tr>';
			$uid = (string)$arr [$i] ['_id'];
			echo '<td>' . (isset ( $arr [$i] ['current']['user_name'] ) ? $arr [$i] ['current']['user_name'] : '') . '</td>';
			echo '<td>' . (isset ( $arr [$i] ['current']['user_industry'] ) ? $arr [$i] ['current']['user_industry'] : '') . '</td>';
			echo '<td>' . "$uid" . '</td>';
			$cus = $this->db->callmethodlog->findOne(array('user_name' =>$uid, 'class'=>'Proseed' ));
			if (isset($cus['date']) && time()-strtotime($cus['date'])<259200) {
				echo '<td>' . '过去三天使用过'. '</td>';
			}else{
				echo '<td>' . '超过三天未使用'. '</td>';
			}
			echo '</tr>';
		}
		echo '</table></div>';
	}
	
	// =====================================================================================
	// =====================================================================================
	// =====================================================================================
	// =====================================================================================
	function htUserSearch_showform() {
	}
	function htListAllUsers_table($data) {
		$su = json_decode ( $data, true );
		// var_dump ( $su );
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		
		echo '<th>id</th>';
		echo '<th>用戶名</th>';
		echo '<th>手机号</th>';
		echo '<th>昵称</th>';
		echo '<th>头像</th>';
		echo '<th>' . '操作1' . '</th>';
		echo '<th>' . '操作2' . '</th>';
		echo '<th>' . '操作3' . '</th>';
		echo '<th>' . '操作4' . '</th>';
		echo '</tr></thead>';
		
		foreach ( $su ['data'] as $row ) {
			echo '<tr>';
			$pid = $row ['_id'] ['$id'];
			echo '<td>' . (isset ( $row ['uid'] ) ? $row ['uid'] : '') . '</td>';
			echo '<td>' . (isset ( $row ['user_name'] ) ? $row ['user_name'] : '') . '</td>';
			echo '<td>' . (isset ( $row ['user_mobile'] ) ? $row ['user_mobile'] : '') . '</td>';
			echo '<td>' . (isset ( $row ['user_nickname'] ) ? $row ['user_nickname'] : '') . '</td>';
			if (isset ( $row ['user_smallavatar'] )) {
				echo '<td>' . '<img width="40px" src="http://' . $row ['user_smallavatar'] . '" /></td>';
			} else {
				echo '<td></td>';
			}
			echo '<td><a href="?action=edit' . '&uid=' . $pid . '">编辑</a>';
			echo '<td><a href="?action=delete' . '&uid=' . $pid . '">删除</a>';
			echo '<td><a href="?action=behavior' . '&uid=' . $pid . '&type=days' . '&value=7' . '">行为分析</a>';
			echo '<td><a href="?action=delete' . '&uid=' . $pid . '">他的话题</a>';
			echo '</tr>';
		}
		echo '</table></div>';
	}
	function htgetBehaviorsByid_showtable($arr, $level) {
		if (count ( $arr ) == 0)
			return;
		if ($level == 1) {
			echo '<div class="table-responsive"><table class="table table-striped">';
			echo '<tr>';
			echo '<td>时间</td><td>类方法与调用次数</td>';
			echo '</tr>';
			
			foreach ( $arr as $key => $value ) {
				echo '<tr>';
				echo '<td>' . $key . '</td>';
				echo '<td>';
				$this->htgetBehaviorsByid_showtable ( $value, $level + 1 );
				echo '</td>';
				echo '</tr>';
			}
			
			echo '</table></div>';
		} else if ($level == 2) {
			echo '<div class="table-responsive"><table class="table table-striped">';
			foreach ( $arr as $key => $value ) {
				if ($key == 'statistics')
					continue;
				echo '<tr>';
				echo '<td>' . $key . '</td>';
				echo '<td>';
				$this->htgetBehaviorsByid_showtable ( $value, $level + 1 );
				echo '</td>';
				echo '</tr>';
			}
			echo '<tr>';
			echo '<td>' . '统计' . '</td>';
			echo '<td>';
			$this->htgetBehaviorsByid_showtable ( $arr ['statistics'], $level + 1 );
			echo '</td>';
			echo '</tr>';
			echo '</table></div>';
		} else {
			echo '<div class="table-responsive"><table class="table table-striped">';
			foreach ( $arr as $key => $value ) {
				echo '<tr>';
				echo '<td>' . $key . '</td>';
				echo '<td>' . $value . '</td>';
				echo '</tr>';
			}
			echo '</table></div>';
		}
	}
	function htgetAllUserState_showtable($arr, $level) {
		if ($level == 1) {
			echo '<div class="table-responsive"><table class="table table-striped">';
			echo '<thead><tr>';
			echo '<th>地区分布</th><th>职业分布</th>';
			echo '</tr></thead>';
			echo '<tr>';
			echo '<td>';
			$this->htgetAllUserState_showtable ( $arr [0], $level + 1 );
			echo '</td>';
			echo '<td>';
			$this->htgetAllUserState_showtable ( $arr [1], $level + 1 );
			echo '</td>';
			echo '</tr>';
			
			echo '</table></div>';
		} else {
			echo '<div class="table-responsive"><table class="table table-striped">';
			
			foreach ( $arr as $key => $v ) {
				echo '<tr>' . '<td>' . $key . '</td><td>' . $v . '</td></tr>';
			}
			echo '</table></div>';
		}
	}
}


?>
