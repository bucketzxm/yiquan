<?php
require_once 'YqBase.php';
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
/* Report all errors except E_NOTICE */
// error_reporting ( E_ALL & ~ E_NOTICE );
class YqPlatform extends YqBase {
	protected $bcs_host = 'bcs.duapp.com';
	function uploadSmallPicForUser(&$arr) {
		$auth = new Auth ( $this->qiniuAK, $this->qiniuSK );
		$bucket = 'yiquanhost-avatar';
		$uploadMgr = new UploadManager ();
		if (isset ( $arr ['user_pic'] )) {
			$fnamesmall = $arr ['user_name'] . '_small';
			$token = $auth->uploadToken ( $bucket, md5 ( $fnamesmall ) );
			list ( $ret, $err ) = $uploadMgr->put ( $token, md5 ( $fnamesmall ), base64_decode ( $arr ['user_pic'] ) );
			if ($err === null) {
				$arr ['user_smallavatar'] = $this->userpicbucketUrl . '/' . $ret ['key'];
			}
		}
		
		$bucket = 'yiquan';
		$object = '/userPics/' . $arr ['_id'];
		$baiduBCS = new BaiduBCS ( $this->ak, $this->sk, $this->bcs_host );
		
		$response = $baiduBCS->get_object ( $bucket, $object );
		// var_dump($response);
		if ($response->isOK ()) {
			$fnamebig = $arr ['user_name'] . '_big';
			$token = $auth->uploadToken ( $bucket, md5 ( $fnamebig ) );
			list ( $ret, $err ) = $uploadMgr->put ( $token, md5 ( $fnamebig ), $response->body );
			if ($err === null) {
				$arr ['user_bigavatar'] = $this->userpicbucketUrl . '/' . $ret ['key'];
			}
		}
		// echo $response->body;
	}
	function addProfileByName($user_name, $user_profile) {
		try {
			$arr = json_decode ( $user_profile, true ); // 将json数据变成php的数组
			$ob = $this->db->user->findOne ( array (
					'user_name' => $user_name 
			) );
			
			$arr ['user_objid'] = $ob ['_id']; // 给arr数组加一个字段user_objid arr数组是后面整体要做更新的数组
			
			$update = $arr;
			
			$query = array (
					"user_objid" => $ob ['_id'] 
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
	function platformWeihu() {
		$cus = $this->db->user->find ();
		$datetime = new MongoDate ();
		while ( $cus->hasNext () ) {
			$doc = $cus->getNext ();
			if (! isset ( $doc ['user_relationships'] ))
				$doc ['user_relationships'] = [ ];
			$repl = [ ];
			foreach ( $doc ['user_relationships'] as $k => $v ) {
				if (! isset ( $v ['date'] )) {
					$v ['date'] = $datetime;
				}
				$repl [$v ['userb_id']->{'$id'}] = $v;
			}
			$doc ['user_relationships'] = $repl;
			
			if (! isset ( $doc ['user_state'] ))
				$doc ['user_state'] = 1;
			
			if (! isset ( $doc ['user_blocklist'] )) {
				$doc ['user_blocklist'] = [ ];
			}
			
			if (! isset ( $doc ['user_blockTopic'] )) {
				$doc ['user_blockTopic'] = [ ];
			}
			if (! isset ( $doc ['user_archiveTopic'] )) {
				$doc ['user_archiveTopic'] = [ ];
			}
			if (! isset ( $doc ['user_followTopic'] )) {
				$doc ['user_followTopic'] = [ ];
			}
			if (! isset ( $doc ['user_regdate'] )) {
				$doc ['user_regdate'] = new MongoDate ();
			}
			
			if (! isset ( $doc ['user_exp'] )) {
				$doc ['user_exp'] = 0;
			}
			
			if (! isset ( $doc ['user_privilege'] )) {
				$doc ['user_privilege'] = 0;
			}
			
			if (! isset ( $doc ['user_smallavatar'] )) {
				$doc ['user_smallavatar'] = '';
			}
			
			if (! isset ( $doc ['user_bigavatar'] )) {
				$doc ['user_bigavatar'] = '';
			}
			
			// $this->uploadSmallPicForUser ( $doc );
			
			$this->db->user->save ( $doc );
			
			$t = $doc ['_id'];
			// echo $t;
			$ans2 = $this->db->userProfile->findOne ( array (
					'user_objid' => $t 
			) );
			
			if (is_null ( $ans2 )) {
				$profile = array (
						'profile_intro' => '保密',
						'profile_city' => '保密',
						'profile_industry' => '保密',
						'profile_org' => '保密',
						'profile_position' => '保密' 
				);
				
				$this->addProfileByName ( $doc ['user_name'], json_encode ( $profile ) );
			}
		}
		$cus = $this->db->userRelationship->find ();
		
		while ( $cus->hasNext () ) {
			$doc = $cus->getNext ();
			if (! isset ( $doc ['weight'] )) {
				$doc ['weight'] = 0;
			}
			
			if (! isset ( $doc ['remark'] )) {
				$doc ['remark'] = '';
			}
			
			if (! isset ( $doc ['date'] )) {
				$doc ['date'] = $datetime;
			}
			$this->db->userRelationship->save ( $doc );
		}
		
		$cus = $this->db->topic->find ();
		
		while ( $cus->hasNext () ) {
			$doc = $cus->getNext ();
			if (! isset ( $doc ['topic_labels'] )) {
				$doc ['topic_labels'] = [ ];
			}
			
			if ((count ( $doc ['topic_labels'] ) == 2) && ($doc ['topic_detail'] != '')) {
				array_push ( $doc ['topic_labels'], "长话题" );
			}
			if (! isset ( $doc ['topic_followNames'] )) {
				$doc ['topic_followNames'] = [ ];
			} else {
				$doc ['topic_followNames'] = [ ];
			}
			if (isset ( $doc ['topic_followCounts'] )) {
				unset ( $doc ['topic_followCounts'] );
			}
			if (! isset ( $doc ['topic_archiveCounts'] )) {
				$doc ['topic_archiveCounts'] = 0;
			}
			$this->db->topic->save ( $doc );
		}
		
		$cus = $this->db->message->find ( array (
				'message_life' => 0 
		) );
		
		while ( $cus->hasNext () ) {
			$doc = $cus->getNext ();
			$this->db->oldMessage->save ( $doc );
		}
		
		$this->db->message->remove ( array (
				'message_life' => 0 
		) );
		
		// ======topic
		$cus = $this->db->topic->find ();
		while ( $cus->hasNext () ) {
			$doc = $cus->getNext ();
			
			if (! isset ( $doc ['topic_detailname'] )) {
				$doc ['topic_detailname'] = '';
			}
			
			if (! isset ( $doc ['topic_detail'] )) {
				$doc ['topic_detail'] = '';
			}
			
			if (isset ( $doc ['topic_detailname'] ) && $doc ['topic_detailname'] != '') {
				$doc ['topic_detail'] = $this->topicsbucketUrl . '/' . $doc ['topic_detailname'];
			}
			
			$this->db->topic->save ( $doc );
		}
		
		// ======message
		$cus = $this->db->message->find ();
		while ( $cus->hasNext () ) {
			$doc = $cus->getNext ();
			if (! isset ( $doc ['message_detail'] )) {
				$doc ['message_detail'] = '';
			}
			
			$this->db->message->save ( $doc );
		}
		
		return 1;
	}
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
	function getUserStatistic($configs = array('type'=>'days','value'=>30)) {
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
			$querystr = '-' . ($configs ['value'] - 1) . ' day';
			$st = strtotime ( $querystr );
			
			$res = [ ];
			
			$kt = time ();
			while ( $kt >= $st ) {
				$res [date ( 'Y-m-d', $kt )] = 0;
				$kt = strtotime ( '-1 day', $kt );
			}
			
			$cus = $this->db->user->find ( array (
					'user_regdate' => array (
							'$gte' => new MongoDate ( $st ),
							'$lte' => new MongoDate ( $ed ) 
					) 
			) )->sort ( array (
					'user_regdate' => - 1 
			) );
			
			while ( $cus->hasNext () ) {
				$doc = $cus->getNext ();
				if (! isset ( $res [date ( 'Y-m-d', $doc ['user_regdate']->sec )] )) {
					$res [date ( 'Y-m-d', $doc ['user_regdate']->sec )] = 1;
				} else {
					$res [date ( 'Y-m-d', $doc ['user_regdate']->sec )] += 1;
				}
			}
		} else if ($configs ['type'] == 'weeks') {
			$date = date ( 'Y-m-d' ); // 当前日期
			$first = 1; // $first =1 表示每周星期一为开始日期 0表示每周日为开始日期
			$w = date ( 'w', strtotime ( $date ) ); // 获取当前周的第几天 周日是 0 周一到周六是 1 - 6
			$now_start = strtotime ( date ( 'Y-m-d', strtotime ( "$date -" . ($w ? $w - $first : 6) . ' day' ) ) ); // 获取本周开始日期，如果$w是0，则表示周日，减去 6 天
			$now_end = strtotime ( date ( 'Y-m-d', strtotime ( ' +6 day', $now_start ) ) ); // 本周结束日期
			
			$res = [ ];
			
			for($i = 1; $i <= $configs ['value']; $i ++) {
				$cus = $this->db->user->count ( array (
						'user_regdate' => array (
								'$gte' => new MongoDate ( $now_start ),
								'$lte' => new MongoDate ( $now_end ) 
						) 
				) );
				$res [date ( 'Y-m-d', $now_start ) . '-->' . date ( 'Y-m-d', $now_end )] = $cus;
				$now_start = strtotime ( "-7 days", $now_start );
				$now_end = strtotime ( "-7 days", $now_end );
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
				$day = date ( "Y-m-01", strtotime ( "-1 month", $day ) );
			}
		}
		
		$finalans = [ ];
		$finalans [] = $ans;
		$finalans [] = $res;
		return $finalans;
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
	function getStatisticforActiveuser($configs = array('type'=>'days','value'=>30)) {
		$st = time ();
		$ed = time ();
		// echo 'aaa';
		if ($configs ['type'] == 'days') {
			$querystr = '-' . ($configs ['value'] - 1) . ' day';
			$st = strtotime ( date ( 'Y-m-d', strtotime ( $querystr ) ) );
			
			$res = [ ];
			$ans = array ();
			$kt = strtotime ( date ( 'Y-m-d', time () ) );
			while ( $kt >= $st ) {
				$ans [date ( 'Y-m-d', $kt )] ['activecount'] = 0;
				$dt = strtotime ( "+1 day", $kt );
				$usercount = $this->db->user->count ( array (
						'user_regdate' => array (
								'$lte' => new MongoDate ( $dt ) 
						) 
				) );
				$ans [date ( 'Y-m-d', $kt )] ['user_count'] = $usercount;
				$kt = strtotime ( '-1 day', $kt );
			}
			// var_dump ( $ans );
			$cus = $this->db->callmethodlog->find ( array (
					'date' => array (
							'$gte' => new MongoDate ( $st ),
							'$lte' => new MongoDate ( $ed ) 
					) 
			), array (
					'date' => true,
					'user_name' => true 
			) )->sort ( array (
					'date' => - 1 
			) );
			
			while ( $cus->hasNext () ) {
				$doc = $cus->getNext ();
				$res [date ( 'Y-m-d', $doc ['date']->sec )] [$doc ['user_name']] = 1;
			}
			
			foreach ( $res as $key => $v ) {
				$ans [$key] ['activecount'] = count ( $v );
			}
			// var_dump($ans);
			return $ans;
		} else if ($configs ['type'] == 'weeks') {
			$querystr = '-' . ($configs ['value']) . ' week Monday';
			$st = strtotime ( $querystr );
			$i = $configs ['value'];
			$zong = [ ];
			while ( $i ) {
				$res = [ ];
				$cus = $this->db->callmethodlog->find ( array (
						'date' => array (
								'$gte' => new MongoDate ( $st ),
								'$lt' => new MongoDate ( strtotime ( '+1 week Monday', $st ) ) 
						) 
				), array (
						'date' => true,
						'user_name' => true 
				) )->sort ( array (
						'date' => - 1 
				) );
				
				while ( $cus->hasNext () ) {
					$doc = $cus->getNext ();
					$res [date ( 'Y-m-d', $st )] [$doc ['user_name']] = 1;
				}
				
				$ans = array ();
				// var_dump ($res);
				foreach ( $res as $key => $v ) {
					$ans ['activecount'] = count ( $v );
					$dt = strtotime ( '+1 week Monday', $st );
					$usercount = $this->db->user->count ( array (
							'user_regdate' => array (
									'$lt' => new MongoDate ( $dt ) 
							) 
					) );
					$ans ['user_count'] = $usercount;
				}
				$zong [date ( 'Y-m-d', $st ) . '-->' . date ( 'Y-m-d', strtotime ( '+1 week Monday', $st ) )] = $ans;
				$st = strtotime ( '+1 week Monday', $st );
				$i --;
			}
			return $zong;
		}
	}
	function getDailyCountReport($time) {
		$res = [ ];
		
		$start = strtotime ( date ( "Y-m-d ", $time ) );
		$endday = $start + 86400;
		$row = $this->db->user->count ( array (
				'user_regdate' => array (
						'$lte' => new MongoDate ( $endday ) 
				) 
		) );
		$res ['user_count'] = $row;
		
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
		$start = strtotime ( date ( "Y-m-d ", $time ) );
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
		
		$cus = $this->db->user->find ();
		while ( $cus->hasNext () ) {
			$doc = $cus->getNext ();
			$arr1 = $doc ['user_relationships'];
			$arr2 = [ ];
			$arr3 = [ ];
			$arr3 [] = $doc ['_id']->{'$id'};
			
			foreach ( $doc ['user_relationships'] as $v ) {
				$row = $this->db->user->findOne ( array (
						'_id' => $v ['userb_id'] 
				) );
				
				foreach ( $row ['user_relationships'] as $vvv ) {
					$arr2 [] = $vvv ['userb_id']->{'$id'};
				}
			}
			
			$arr2 = array_flip ( array_flip ( $arr2 ) );
			// var_dump ( $arr3 );
			$sum += count ( array_diff ( $arr2, $arr3 ) );
		}
		
		$d = round ( $sum / $con, 2 );
		
		$res ['averageof_userQuanfriends'] = $d;
		
		// ========================================
		return $res;
	}
	function getMethodsCallStat($st) {
		$cus = $this->db->callmethodlog->find ( array (
				'date' => array (
						'$gte' => new MongoDate ( $st ) 
				) 
		) );
		
		$ans = [ ];
		while ( $cus->hasNext () ) {
			$doc = $cus->getNext ();
			
			foreach ( $doc ['methods'] as $key => $v ) {
				if (! isset ( $ans [$doc ['class']] [$key] )) {
					$ans [$doc ['class']] [$key] = $v;
				} else {
					$ans [$doc ['class']] [$key] += $v;
				}
			}
		}
		return $ans;
	}
}

?>
