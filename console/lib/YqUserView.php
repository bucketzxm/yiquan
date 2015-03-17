<?php
require_once 'YqUser.php';

/* Report all errors except E_NOTICE */
// error_reporting ( E_ALL & ~ E_NOTICE );
class YqUserView extends YqUser {
	function htUserSearch_showform() {
	}
	function htListAllUsers_table($data) {
		$su = json_decode ( $data, true );
		// var_dump ( $su );
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		
		foreach ( $su ['data'] [0] as $key => $v ) {
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
		
		foreach ( $su ['data'] as $row ) {
			echo '<tr>';
			$pid = $row ['_id'] ['$id'];
			foreach ( $row as $key => $v ) {
				switch ($key) {
					case 'user_pic' :
						echo '<td>' . '<img class="img-responsive" src="data:image/jpeg;base64,' . $v . '"' . ' data-holder-rendered="true"/>' . '</td>';
						break;
					case 'user_relationships' :
						break;
					case 'user_pin' :
						break;
					case '_id' :
						break;
					case 'user_blocklist' :
						break;
					case 'user_regdate' :
						echo '<td>' . date ( 'Y-m-d h:i:s', $v ['sec'] ) . '</td>';
						break;
					default :
						echo '<td>' . $v . '</td>';
				}
			}
			
			echo '<td><a href="?action=edit' . '&uid=' . $pid . '">编辑</a>';
			echo '<td><a href="?action=delete' . '&uid=' . $pid . '">删除</a>';
			echo '<td><a href="?action=behavior' . '&uid=' . $pid . '&type=days' . '&value=7' . '">行为分析</a>';
			echo '<td><a href="?action=delete' . '&uid=' . $pid . '">他的话题</a>';
			echo '</tr>';
		}
		echo '</table></div>';
	}
	function showPageNumbers_table($arr, $startpos, $length, $limit) {
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<tr><td>';
		for($i = $startpos; $i < min ( ($startpos + $length), count ( $arr ) ); $i ++) {
			echo '<a href="?action=view&page=' . $i . '&limit=' . $limit . '">' . $i . '</a>';
		}
		echo '</td></tr></table></div>';
	}
	function htEditUserById_form($jsondata) {
		$p = json_decode ( $jsondata, true );
		//var_dump($p);
		// =================================================
		echo '<div><form method="post" action="?action=edit">';
		echo '<div class="form-group"><h2>基本信息</h2></div>';
		echo '<div class="form-group">';
		echo '<input type="hidden" class="form-control" name="edittype" value="basic"/>';
		echo '<input type="hidden" class="form-control" name="_id" value="' . $p ['_id'] ['$id'] . '"/></div>';
		foreach ( $p as $k => $v ) {
			if (is_array ( $v ) || startWith ( $k, 'count' ) || in_array ( $k, array (
					'user_pin',
					'uid' 
			) )) {
				continue;
			}
			if ($k == 'user_pic') {
			} else {
				echo '<div class="form-group"><label for="' . $k . '">' . $k . '</label>';
				echo '<input type="text" class="form-control" name="' . $k . '" value="' . $v . '"/></div>';
			}
		}
		
		echo '<input type="submit" value="修改基本信息" />';
		echo '</form></div>';
		// =================================================
		echo '<div><form method="post" action="?action=edit">';
		echo '<div class="form-group"><h2>用户密码修改</h2></div>';
		echo '<div class="form-group">';
		echo '<input type="hidden" class="form-control" name="edittype" value="password"/>';
		echo '<input type="hidden" class="form-control" name="_id" value="' . $p ['_id'] ['$id'] . '"/></div>';
		echo '<div class="form-group"><label for="user_pin">user_pin</label>';
		echo '<input type="text" class="form-control" name="user_pin" value=""/></div>';
		echo '<input type="submit" value="修改用户密码" />';
		echo '</form></div>';
		// =================================================
		echo '<div><form method="post" action="?action=edit">';
		echo '<div class="form-group"><h2>自定义档案</h2></div>';
		echo '<div class="form-group">';
		echo '<input type="hidden" class="form-control" name="edittype" value="profile"/>';
		echo '<input type="hidden" class="form-control" name="_id" value="' . $p ['_id'] ['$id'] . '"/></div>';
		if (isset ( $p ['userProfile'] )) {
			foreach ( $p ['userProfile'] as $k => $v ) {
				
				if (is_array ( $v ) || in_array ( $k, array (
						'_id',
						'uid' 
				) )) {
					continue;
				}
				
				if ($k == 'user_pic') {
				} else {
					echo '<div class="form-group"><label for="' . 'userProfile-' . $k . '">' . $k . '</label>';
					echo '<input type="text"  class="form-control" name="' . 'userProfile-' . $k . '" value="' . $v . '"/></div>';
				}
			}
		}
		
		echo '<input type="submit" value="修改用户档案" />';
		echo '</form></div>';
		// =================================================
		echo '<div><form method="post" action="?action=edit"  enctype="multipart/form-data">';
		echo '<div class="form-group"><h2>用户头像修改</h2></div>';
		echo '<div class="form-group">';
		echo '<input type="hidden" class="form-control" name="edittype" value="userpic"/>';
		echo '<input type="hidden" class="form-control" name="_id" value="' . $p ['_id'] ['$id'] . '"/></div>';
		echo '<div class="form-group"><label for="user_pic">user_pic</label>';
		echo '<img class="img-responsive" src="data:image/jpeg;base64,' . $p ['user_pic'] . '"' . ' data-holder-rendered="true"/>';
		echo '<input type="file" name="user_pic"  /></div>';
		echo '<input type="submit" value="修改用户头像" />';
		echo '</form></div>';
	}
	function htDeleteUserById_form($jsondata) {
		$p = json_decode ( $jsondata, true );
		echo '<form method="post" action="?action=delete">';
		echo '确定删除该用户吗？';
		echo $p ['user_name'];
		echo '<input type="hidden" name="uid" value="' . $p ['_id'] ['$id'] . '" />';
		echo '<div class="form-group"><input type="submit" value="确定" /></div>';
		echo '</form>';
	}
	function htaddProfileById($user_id, $user_profile) {
		try {
			
			$arr = json_decode ( $user_profile, true ); // 将json数据变成php的数组
			
			$arr ['user_objid'] = new MongoId ( $user_id ); // 给arr数组加一个字段user_objid arr数组是后面整体要做更新的数组
			
			$update = $arr;
			
			$query = array (
					"user_objid" => new MongoId ( $user_id ) 
			);
			// 注意看这里 mongodb支持一个叫findandmodify的操作 就是先修改后查询 很好用
			// 我这里代码的意图是 $query指定的数据 用$update里面的数据更新掉
			$command = array (
					"findandmodify" => 'userProfile',
					"update" => $update,
					"query" => $query,
					"new" => true,
					"upsert" => true 
			);
			
			$id = $this->db->command ( $command ); // 执行更新
			
			if (isset ( $arr ['user_nickname'] )) {
				$ob ['user_nickname'] = $arr ['user_nickname'];
				$this->db->user->save ( $ob );
			}
			
			return 1;
		} catch ( Exception $e ) {
			return - 1;
		}
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

// // $a = new YqUser ();
// // $a->htEditUserById_form ( $a->getUserByID ( '54d6462d72740e9c0b000021' ), 1 );
// $a = new YqUser ();
// // echo $a->loginByUser('abc1','110');
// // echo $a->getUserByName('abc1');
// $a->reg ( 'abc0', '110', '110' );
// $a->reg ( 'abc1', '110', '110' );
// $a->reg ( 'abc2', '112', '110' );
// $a->reg ( 'abc3', '110', '110' );
// $a->reg ( 'abc4', '110', '110' );
// $a->reg ( 'abc5', '110', '110' );
// $a->reg ( 'abc6', '110', '110' );
// $a->reg ( 'abc7', '110', '110' );

// $a->addProfileByName ( 'abc0', '{"profile_city":"shanghai"}' );
// $a->addProfileByName ( 'abc1', '{"profile_city":"shanghai"}' );
// $a->addProfileByName ( 'abc2', '{"profile_city":"shanghai"}' );
// $a->addProfileByName ( 'abc3', '{"profile_city":"shanghai"}' );
// $a->addProfileByName ( 'abc4', '{"profile_city":"shanghai"}' );
// $a->addProfileByName ( 'abc5', '{"profile_city":"shanghai"}' );
// $a->addProfileByName ( 'abc6', '{"profile_city":"shanghai"}' );
// $a->addProfileByName ( 'abc7', '{"profile_city":"山东"}' );

// $a->addFriendByName ( 'abc0', 'abc2' );
// $a->addFriendByName ( 'abc1', 'abc2' );
// $a->addFriendByName ( 'abc1', 'abc5' );
// $a->addFriendByName ( 'abc5', 'abc3' );
// $a->addFriendByName ( 'abc3', 'abc4' );
// $a->addFriendByName ( 'abc5', 'abc6' );
// $a->addFriendByName ( 'abc6', 'abc7' );
// $a->addFriendByName ( 'abc4', 'abc7' );
// $a->addFriendByName ( 'abc4', 'abc1' );
// $a->addFriendByName ( 'abc2', 'abc4' );
// $a->addFriendByName ( 'abc2', 'abc5' );
// $a->addFriendByName ( 'abc0', 'abc2' );

?>
