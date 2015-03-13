<?php
require_once 'YqBase.php';

/* Report all errors except E_NOTICE */
// error_reporting ( E_ALL & ~ E_NOTICE );
class YqPlatform extends YqBase {
	protected $bcs_host = 'bcs.duapp.com';
	function getLastestVersion($plat) {
		$canshu = 'lastestVersion_' . $plat;
		$row = $this->db->generalSettings->findOne ( array (
				'name' => $canshu 
		) );
		
		if ($row == null) {
			$row = [ ];
			$row ['name'] = $canshu;
			$row ['value'] = '0.0.0';
		}
		$row ['platform'] = $plat;
		return $row;
	}
	function getLastestVersion_showform($arr) {
		echo '<div><form method="post" action="?action=version">';
		echo '<div class="form-group"><h2>最新' . $arr ['platform'] . '版本号</h2></div>';
		echo '<input type="hidden" name="plat" value="' . $arr ['platform'] . '" />';
		echo '<input type="text" class="form-control" name="lastestVersion" value="' . $arr ['value'] . '"/>';
		
		echo '<input type="submit" value="修改最新版本号" />';
		echo '</form></div>';
	}
	function updateLastestVersion($plat, $value) {
		// var_dump ( $plat );
		$canshu = 'lastestVersion_' . $plat;
		$row = $this->db->generalSettings->findOne ( array (
				'name' => $canshu 
		) );
		if ($row == null)
			$row = array (
					'name' => $canshu 
			);
		
		$row ['value'] = $value;
		$this->db->generalSettings->save ( $row );
		return 1;
	}
	function getPlatformStatistic() {
		$ans = [ ];
		$ans ['user_count'] = $this->db->user->count ();
		$ans ['user_active_count'] = $this->db->user->count ( array (
				'user_state' => 1 
		) );
		$ans ['user_inactive_count'] = $this->db->user->count ( array (
				'user_state' => array (
						'$ne' => 1 
				) 
		) );
		
		$ans ['topic_count'] = $this->db->topic->count ();
		$ans ['reply_count'] = $this->db->reply->count ();
		$ans ['message_count'] = $this->db->message->count ();
		
		return $ans;
	}
	function getPlatformStatistic_showtable($arr) {
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr><th>内容</th><th>值</th></tr></thead>';
		foreach ( $arr as $key => $v ) {
			echo '<tr><td>' . $key . '</td><td>' . $v . '</td></tr>';
		}
		echo '</table></div>';
	}
	function getUserStatistic($configs = array('type'=>'days','value'=>7)) {
		$ans = [ ];
		$ans ['user_count'] = $this->db->user->count ();
		$ans ['user_active_count'] = $this->db->user->count ( array (
				'user_state' => 1 
		) );
		$ans ['user_inactive_count'] = $this->db->user->count ( array (
				'user_state' => array (
						'$ne' => 1 
				) 
		) );
		
		$st = time ();
		$ed = time ();
		if ($configs ['type'] == 'days') {
			$querystr = '-' . $configs ['value'] . ' day';
			$st = strtotime ( $querystr );
			
			$res = [ ];
			$cus = $this->db->user->find ( array (
					'user_regdate' => array (
							'$gte' => new MongoDate ( $st ),
							'$lte' => new MongoDate ( $ed ) 
					) 
			) );
			
			while ( $cus->hasNext () ) {
				$doc = $cus->getNext ();
				if (! isset ( $res [date ( 'Y-M-d', $doc ['user_regdate']->sec )] )) {
					$res [date ( 'Y-M-d', $doc ['user_regdate']->sec )] = 1;
				} else {
					$res [date ( 'Y-M-d', $doc ['user_regdate']->sec )] += 1;
				}
			}
		} else if ($configs ['type'] == 'weeks') {
			
			$date = date ( 'Y-m-d' ); // 当前日期
			
			$first = 1; // $first =1 表示每周星期一为开始日期 0表示每周日为开始日期
			
			$w = date ( 'w', strtotime ( $date ) ); // 获取当前周的第几天 周日是 0 周一到周六是 1 - 6
			
			$now_start = date ( 'Y-m-d', strtotime ( "$date -" . ($w ? $w - $first : 6) . ' days' ) ); // 获取本周开始日期，如果$w是0，则表示周日，减去 6 天
			
			$now_end = date ( 'Y-m-d', strtotime ( "$now_start +6 days" ) ); // 本周结束日期
			
			$res = [ ];
			
			for($i = 1; $i <= $config ['value']; $i ++) {
				$cus = $this->db->user->count ( array (
						'user_regdate' => array (
								'$gte' => new MongoDate ( $now_start ),
								'$lte' => new MongoDate ( $now_end ) 
						) 
				) );
				$res [$now_start . '-' . $now_end] = $cus;
				$now_start = date ( 'Y-m-d', strtotime ( "$now_start -7 days" ) );
				$now_end = date ( 'Y-m-d', strtotime ( "$now_end -7 days" ) );
			}
		} elseif ($configs ['type'] == 'months') {
			$res = [ ];
			$day = date ();
			for($i = 1; $i <= $config ['value']; $i ++) {
				$tp = $this->getthemonth ( $day );
				$cus = $this->db->user->count ( array (
						'user_regdate' => array (
								'$gte' => new MongoDate ( $tp [0] ),
								'$lte' => new MongoDate ( $tp [1] ) 
						) 
				) );
				$res ["$tp[0]" . '-' . "$tp[1]"] = $cus;
				$day = date ( "Y-m-01", strtotime ( "$day -1 month" ) );
			}
		}
		
		$finalans = [ ];
		$finalans [] = $ans;
		$finalans [] = $res;
		return $finalans;
	}
	function getUserStatistic_showtable($arr) {
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr><th>内容</th><th>值</th></tr></thead>';
		foreach ( $arr [1] as $key => $v ) {
			echo '<tr><td>' . $key . '</td><td>' . $v . '</td></tr>';
		}
		echo '</table></div>';
	}
	function getUserStatistic_showsearchform() {
		echo '<div class="table-responsive"><table class="table table-striped">';
		
		echo '</table></div>';
	}
	function getthemonth($date) {
		$firstday = date ( 'Y-m-01', strtotime ( $date ) );
		$lastday = date ( 'Y-m-d', strtotime ( "$firstday +1 month -1 day" ) );
		return array (
				$firstday,
				$lastday 
		);
	}
	function GetMonth($sign = "1") {
		// 得到系统的年月
		$tmp_date = date ( "Ym" );
		// 切割出年份
		$tmp_year = substr ( $tmp_date, 0, 4 );
		// 切割出月份
		$tmp_mon = substr ( $tmp_date, 4, 2 );
		$tmp_nextmonth = mktime ( 0, 0, 0, $tmp_mon + 1, 1, $tmp_year );
		$tmp_forwardmonth = mktime ( 0, 0, 0, $tmp_mon - 1, 1, $tmp_year );
		if ($sign == 0) {
			// 得到当前月的下一个月
			return $fm_next_month = date ( "Y-m", $tmp_nextmonth );
		} else {
			// 得到当前月的上一个月
			return $fm_forward_month = date ( "Y-m", $tmp_forwardmonth );
		}
	}
	function getStatisticforActiveuser($configs = array('type'=>'days','value'=>7)) {
		$st = time ();
		$ed = time ();
		if ($configs ['type'] == 'days') {
			$querystr = '-' . $configs ['value'] . ' day';
			$st = strtotime ( $querystr );
			
			$res = [ ];
			$cus = $this->db->callmethodlog->find ( array (
					'date' => array (
							'$gte' => new MongoDate ( $st ),
							'$lte' => new MongoDate ( $ed ) 
					) 
			), array (
					'date' => true,
					'user_name' => true 
			) )->sort ( array (
					'date' => 1 
			) );
			
			while ( $cus->hasNext () ) {
				$doc = $cus->getNext ();
				$res [date ( 'Y-m-d', $doc ['date']->sec )] [$doc ['user_name']] = 1;
			}
			
			$ans = array ();
			
			foreach ( $res as $key => $v ) {
				$ans [$key] = count ( $v );
			}
			return $ans;
		}
	}
	function searchingRegex_showlist() {
		echo '<div class="table-responsive"><ul>';
		
		echo '<li><a href="?action=report&type=day&value=7">7天内</a></li>';
		echo '<li><a href="?action=report&type=day&value=14">14天内</a></li>';
		echo '<li><a href="?action=report&type=month&value=1">本月内</a></li>';
		echo '<li><a href="?action=report&type=month&value=2">前二月内</a></li>';
		
		echo '</ul></div>';
	}
	
	
	function getdailyreport_showtable()
	{
		
	}
	
	function getDailyCountReport($time) {
		$res = [ ];
		
		$row = $this->db->user->count ();
		$res ['user_count'] = $row;
		
		$start = strtotime ( date ( "Y-m-d " ) );
		$endday = $start + 86400;
		
		$cus = $this->db->callmethodlog->find ( array (
				'date' => array (
						'$gte' => new MongoDate ( $start ),
						'$lte' => new MongoDate ( $endday ) 
				),
				'class' => 'User' 
		) );
		$res ['daily_inviation_count'] = 0;
		while ( $cus->hasNext () ) {
			$doc = $cus->getNext ();
			if (isset ( $doc ['methods'] ['addInvitation'] )) {
				$res ['daily_inviation_count'] += $doc ['methods'] ['addInvitation'];
			}
		}
		// 邀请码发放数量
		
		// =======================================
		
		// 日注册量
		$row = $this->db->user->count ( array (
				'user_regdate' => array (
						'$gte' => new MongoDate ( $start ),
						'$lte' => new MongoDate ( $endday ) 
				) 
		) );
		$res ['daily_reg_count'] = $row;
		
		// =======================================
		// 日用户活跃数
		$cus = $this->db->callmethodlog->find ( array (
				'date' => array (
						'$gte' => new MongoDate ( $start ),
						'$lte' => new MongoDate ( $endday ) 
				) 
		), array (
				'date' => true,
				'user_name' => true 
		) )->sort ( array (
				'date' => 1 
		) );
		
		$tp = [ ];
		
		while ( $cus->hasNext () ) {
			$doc = $cus->getNext ();
			$tp [$doc ['user_name']] = 1;
		}
		
		$res ['daily_active_user_count'] = count ( $tp );
		
		// =====================================
		return $res;
	}
	function getDailyBehavierReport($time) {
		$res = [ ];
		// =======================================
		// 日话题总数
		$start = strtotime ( date ( "Y-m-d " ) );
		$endday = $start + 86400;
		
		$row = $this->db->topic->count ( array (
				'topic_postTime' => array (
						'$gte' => $start,
						'$lte' => $endday 
				) 
		) );
		
		$res ['daily_topic_count'] = $row;
		
		// =======================================
		// 当日回复总数
		
		$row = $this->db->reply->count ( array (
				'reply_time' => array (
						'$gte' => $start,
						'$lte' => $endday 
				) 
		) );
		
		$res ['daily_reply_count'] = $row;
		
		// =======================================
		// 当日添加好友数量
		
		$cus = $this->db->callmethodlog->find ( array (
				'date' => array (
						'$gte' => new MongoDate ( $start ),
						'$lte' => new MongoDate ( $endday ) 
				),
				'class' => 'User' 
		) );
		
		$tp = 0;
		while ( $cus->hasNext () ) {
			$doc = $cus->getNext ();
			if (isset ( $doc ['methods'] ['addFriendByID'] )) {
				$tp += $doc ['methods'] ['addFriendByID'];
			}
		}
		
		$res ['daily_addfriend_count'] = $tp;
		// =======================================
		// 平均每个用户的好友数量
		
		$sum = 0;
		$con = $this->db->user->count ();
		
		$cus = $this->db->user->find ();
		
		while ( $cus->hasNext () ) {
			$doc = $cus->getNext ();
			$sum += count ( $doc ['user_relationships'] );
		}
		
		$d = round ( $sum / $con, 2 );
		
		$res ['averageof_userfriends'] = $d;
		// =======================================
		// 平均每个用户的圈友数量
		$sum = 0;
		
		$cus = $this->db->user - find ();
		while ( $cus->hasNext () ) {
			$doc = $cus->getNext ();
			$arr1 = $doc ['user_relationships'];
			$arr2 = [ ];
			$arr3 = $doc ['_id']->{'$id'};
			
			foreach ( $doc ['user_relationships'] as $v ) {
				$row = $this->db->user->findOne ( array (
						'_id' => $v ['userb_id'] 
				) );
				
				foreach ( $row ['user_relationships'] as $vvv ) {
					$arr2 [] = $vvv ['userb_id']->{'$id'};
				}
			}
			
			$arr2 = array_flip ( array_flip ( $arr2 ) );
			
			$sum += count ( array_diff ( $arr2, $arr3 ) );
		}
		
		$d = round ( $sum / $con, 2 );
		
		$res ['averageof_userQuanfriends'] = $d;
		
		// ========================================
		return $res;
	}
}

?>
