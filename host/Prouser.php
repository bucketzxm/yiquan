<?php
require_once 'YqBase.php';
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
    
class Prouser extends YqBase {
	private $collection;
	// static $conn; // 连接
	// function __construct() {
	// try {
	// if (self::$conn == null) {
	// self::$conn = connectDb ();
	// }
	// self::$conn->connect ();
	// } catch ( Exception $e ) {
	// self::$conn = connectDb ();
	// }
	// while ( 1 ) {
	// $this->db = self::$conn->selectDB ( $this->dbname );
	// if ($this->user != '' && $this->pwd != '') {
	// $fa = $this->db->authenticate ( $this->user, $this->pwd );
	// if ($fa ['ok'] == 0) {
	// sleep ( 1 );
	// continue;
	// }
	// }
	// break;
	// }
	// if (! isset ( $_SESSION )) {
	// session_start ();
	// }
	// $this->yiquan_version = $this->checkagent ();
	// }
	// function __destruct() {
	// self::$conn->close ();
	// }
	// 此类用于 message 表
	// private $dbname = 'test';
	// private $table = 'topic';
	
	// message的属性:
	// sender_id
	// receiver_id
	// life
	// labels
	// type
	// postTime
	// title
	
	function loginByMobile ($user_mobile, $code){
			if ($this->yiquan_version == 0) {
				return - 2;
			}
		

		try{
			$res = $this->checkRegisterCode($user_mobile,$code);
			if ($res == 1){

				$ans = $this->db->Prouser->findOne ( array (
						'user_mobile' => $user_mobile
				) );

				if ($ans == null){
					$neo = array (
							'uid' => $id,
							'user_mobile' => $user_mobile,
							'user_state' => 1,
							'user_regdate' => new MongoDate (),
							'user_favoriteSource' => array (),
							'user_readSeeds' => array (),
							'user_keywords' => array (),
							'user_searchWords' => array(),
							'user_messageCheckTime' => time(),
							'user_mediaGroups' => array()
					);
					$this->db->Prouser->save ( $neo );

				}
					$ans = $this->db->Prouser->findOne ( array (
							'user_mobile' => $user_mobile
					) );

					$userID = (string)$ans['_id'];
					$gd = makeGuid ();
					setcookie ( "user_id", $userID, time () + 3600 * 2400, '/' );
					setcookie ( "quser_token", $gd, time () + 3600 * 2400, '/' );

					// $_SESSION ['user_token'] = $gd;
					

					$rt = $this->db->usertoken->findOne ( array (	
							'user_id' => $userID
					) );
					if ($rt == null) {
						$rt = array (
								'user_id' => $userID 
						);
					}
					
					$rt ['quser_token'] = $gd;
					$this->db->usertoken->save ( $rt );
					
					if ($this->setRedis ( $userID, $gd ) == false) {
						return - 5; // redis wrong
					}

					$logger = $this->db->Prouser->findOne (array ('user_mobile' => $user_mobile));
					$this->expireRegistercode ( $user_mobile, $code );
					return json_encode($logger);
			}else{
				return $res;
			}

		} catch ( Exception $e ) {
			return $e;
		}


	}


	function updateUserProfile ($user_id,$user_industry,$user_seniority){
			if ($this->yiquan_version == 0) {
				return - 2;
			}
			if ($this->checkQuoteToken () != 1) {
				return - 3;
			}
			if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
				return - 4;
			}
			

			try {
				$user = $this->db->Prouser->findOne(array ('_id'=>new MongoId ($user_id)));

				//Update user count
				$para = $this->db->Prosystem->findOne(array('para_name'=>'user_count'));

				if ($user['user_industry'] != null) {
					$para[$user['user_industry']] --;
				}
				if (isset($para[$user['user_industry']])) {
					$para[$user_industry] ++;
				}else{
					$para[$user_industry] = 1;
				}
				$this->db->Prosystem->save($para);

//				$user[$profile_type]['user_name'] = $user_name;
//				$user[$profile_type]['user_city'] = $user_city;
				$user['user_industry'] = $user_industry;
				$user['user_seniority'] = $user_seniority;
//				$user[$profile_type]['user_interestA'] = $user_interestA;
//				$user[$profile_type]['user_interestB'] = $user_interestB;
//				$user[$profile_type]['user_company'] = $user_company;
//				$user[$profile_type]['user_title'] = $user_title;
//				$user[$profile_type]['user_gender'] = $user_gender;

				if ($user_seniority == '1-3年'){
					$user['user_weight'] = 3;	
				}else if ($user_seniority == '3-5年'){
					$user['user_weight'] = 6;	
				}else if ($user_seniority == '5-10年'){
					$user['user_weight'] = 10;	
				}else if ($user_seniority == '10年以上'){
					$user['user_weight'] = 15;	
				}else{
					$user['user_weight'] = 1;	
				}
				
				$this->db->Prouser->save ($user);
				return json_encode($user);
				
			}catch (Exception $e){
				return $e;
			}
			
	}

		function updateUserInterests ($user_id,$user_industryInterested,$user_lifeInterested){
			if ($this->yiquan_version == 0) {
				return - 2;
			}
			if ($this->checkQuoteToken () != 1) {
				return - 3;
			}
			if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
				return - 4;
			}
			

			try {



				//Split the string
				$industryInterested = explode(',', $user_industryInterested);
				$lifeInterested = explode(',', $user_lifeInterested);

				//Update user count
				foreach ($industryInterested as $keyIndus => $industryValue) {
					$para = $this->db->ProMediaGroup->findOne(array('mediaGroup_title'=>$industryValue));
					$para['mediaGroup_counts']['follower_count'] ++;
					$this->db->ProMediaGroup->save($para);
				}

				foreach ($lifeInterested as $keyLife => $lifeValue) {
					$para = $this->db->ProMediaGroup->findOne(array('mediaGroup_title'=>$industryValue));
					$para['mediaGroup_counts']['follower_count'] ++;
					$this->db->ProMediaGroup->save($para);
				}

				$user = $this->db->Prouser->findOne(array ('_id'=>new MongoId ($user_id)));
				$user['user_industryInterested'] = $industryInterested;
				$user['user_lifeInterested'] = $lifeInterested;


				$this->db->Prouser->save ($user);
				return json_encode($user);
				
			}catch (Exception $e){
				return $e;
			}
			
	}

	function findMyPros($user_id){
			if ($this->yiquan_version == 0) {
				return - 2;
			}
			if ($this->checkQuoteToken () != 1) {
				return - 3;
			}
			if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
				return - 4;
			}
			
		//找到用户
			$user = $this->db->Prouser->find (array ('_id'=> new MongoId($user_id)));
			foreach ($user as $key => $value) {
				$industry = $value['current']['user_industry'];
				
			}

		//找到和用户同一个行业的人（行业说的细致）
			$pros = $this->db->Prouser->find (
				array (
					'current.user_industry' =>$industry
					)
				);
		//return 一个array
			$proList = array ();
			foreach ($pros as $key => $pro){
				array_push ($proList, (string)$pro['_id']);
			}
			return $proList;
	}


	function addMyFavorSource ($user_id,$source_id){
			if ($this->yiquan_version == 0) {
				return - 2;
			}
			if ($this->checkQuoteToken () != 1) {
				return - 3;
			}
			if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
				return - 4;
			}
		//找到用户
			$user = $this->db->Prouser->findOne (array ('_id'=> new MongoId($user_id)));

		//addMyFavoriteSource
		if (in_array($source_id,$user['user_favoriteSource'])) {
			return 1;
		}else{
			array_push ($user['user_favoriteSource'],$source_id);
			$this->db->Prouser->save ($user);
			return 1;
		}
	}


	function removeMyFavorSource($user_id,$source_id){

			if ($this->yiquan_version == 0) {
				return - 2;
			}
			if ($this->checkQuoteToken () != 1) {
				return - 3;
			}
			if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
				return - 4;
			}

			try{
				$where1 = array (
						"user_id" => new MongoId($user_id )
				);
				$param1 = array (
						"\$pull" => array (
								'user_favoriteSource' => $source_id
						) 
				);
				
				$this->db->Prouser->update ( $where1, $param1 );

				return 1;
				
			}catch (Exception $e){
				return $e;
			}
	}

	function registerGetuiClientID($user_id, $getui_clientID) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkQuoteToken () != 1) {
			return - 3;
		}
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		
		$cursor = $this->db->getuiClientID->findOne ( array (
				'user_id' => $user_id
		) );
		try {
			if ($cursor == null) {
				$data = array (
						"user_id" => $user_id,
						"getui_clientID" => $getui_clientID,
						"platform" => $this->yiquan_platform 
				);
				$result = $this->db->getuiClientID->save ( $data );
				return 1;
			} else {
				$cursor ['getui_clientID'] = $getui_clientID;
				$cursor ['platform'] = $this->yiquan_platform;
				$this->db->getuiClientID->save ( $cursor );
				return 1;
			}
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	/*
	 * 注销设备
	 */
	function removeGetuiClientID($user_id) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		/*
		 * if ($this->checkQuoteToken () != 1) { return - 3; }
		 */
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		
		$cursor = $this->db->getuiClientID->findOne ( array (
				'user_name' => $user_name 
		) );
		
		if ($cursor != null) {
			try {
				$this->db->getuiClientID->remove ( array (
						'_id' => $cursor ['_id'] 
				) );
				return 1;
			} catch ( Exception $e ) {
				return - 1;
			}
		} else {
			return 1;
		}
	}

	function getRegisterCode($mobilenumber, $expireMinute) {
		if ($mobilenumber != '13800008888') {
			$tp = yqregcode ( 4 )[0];
			$endtime = new MongoDate ( strtotime ( '+' . $expireMinute . ' minute' ) );
			$row = $this->db->regcode->findOne ( array (
					'mobilenumber' => $mobilenumber 
			) );
			
			if (is_null ( $row )) {
				$row = array (
						'mobilenumber' => $mobilenumber,
						'count' => 0 
				);
			} else {
				$intvalhour = floor ( (time () - $row ['expiredDate']->sec) % 86400 / 3600 );
				$intvalmin = floor ( (time () - $row ['expiredDate']->sec) % 86400 / 60 );
				if ($intvalhour <= 24) {
					if ($row ['count'] >= 3) {
						return 3; // 超过3条限制
					} else {
						$row ['count'] ++;
					}
				} elseif ($intvalhour > 24) {
					$row ['count'] = 1;
				}
			}
			
			$row ['regcode'] = $tp;
			$row ['expiredDate'] = $endtime;
			$this->db->regcode->save ( $row );
			
			$ch = curl_init ();
			curl_setopt ( $ch, CURLOPT_URL, "http://sms-api.luosimao.com/v1/send.json" );
			
			curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 30 );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
			curl_setopt ( $ch, CURLOPT_HEADER, FALSE );
			
			curl_setopt ( $ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
			curl_setopt ( $ch, CURLOPT_USERPWD, 'api:key-61cb2ba0c4b3c5d7aa9e05182df6dbc9' );
			
			curl_setopt ( $ch, CURLOPT_POST, TRUE );
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, array (
					'mobile' => $mobilenumber,
					'message' => '验证码：' . $tp . '	- ' . $expireMinute . '分钟有效,区分大小写【每言】' 
			) );
			
			$res = curl_exec ( $ch );
			curl_close ( $ch );
			// $res = curl_error( $ch );
			// var_dump($res);
			
			return $res;
		}
			
	}
	function checkRegisterCode($mobilenumber, $code) {
		$row = $this->db->regcode->findOne ( array (
				'mobilenumber' => $mobilenumber 
		) );
		
		if (is_null ( $row )) {
			return 0;
		} else if ($row ['regcode'] == $code) {
			$exdate = $row ['expiredDate'];
			if ($exdate->sec < time ()) {
				return 1; // expired need to be corrected after debugging
			} else {
				return 1;
			}
		} else {
			return 3;
		}
	}

	function expireRegistercode($mob, $code) {
		$row = $this->db->regcode->findOne ( array (
				'mobilenumber' => $mob,
				'regcode' => $code 
		) );
		
		if (is_null ( $row ))
			return 0;
		
		$row ['expiredDate'] = new MongoDate ();
		$this->db->regcode->save ( $row );
		return 1;
	}

	function checkIOSVersion ($build){
		$build = (int) $build;
		$latestBuild = $this->db->Prosystem->findOne(array ('para_name' => "version_control"));
		if ($build < $latestBuild['iOS']) {
			return $latestBuild['note'];
		}else{
			return 0;
		}
	}


	function updateKeywords($user_id,$keywords){
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkQuoteToken () != 1) {
			return - 3;
		}
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}

		$separated_keywords = explode ( ',', $keywords);
		$user = $this->db->Prouser->findOne(array ('_id'=> new MongoId($user_id)));
		$user['user_keywords'] = array ();
		foreach($separated_keywords as $word){
			array_push($user['user_keywords'], $word);
		}

		$this->db->Prouser->save($user);
		return json_encode($user);
	}

	function updateUserpicByUsername($data, $user_id) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkQuoteToken () != 1) {
			return - 3;
		}
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}
		
		
		$row = $this->db->Prouser->findOne ( array (
				'_id' => new MongoId ( $user_id ) 
		) );
		if ($row == null) {
			return 2;
		}
		$rawpic = base64_decode ( $data );
		
		$im = new Imagick ();
		$im->readImageBlob ( $rawpic );
		$geo = $im->getImageGeometry ();
		$w = $geo ['width'];
		$h = $geo ['height'];
		$maxWidth = $maxHeight = 160;
		$fitbyWidth = (($maxWidth / $w) < ($maxHeight / $h)) ? true : false;
		
		if ($fitbyWidth) {
			$im->thumbnailImage ( $maxWidth, 0, false );
		} else {
			$im->thumbnailImage ( 0, $maxHeight, false );
		}
		
		// save to qiniu
		$rep = $this->QiniuUploadpic ( $row, $rawpic, $im );
		if ($rep != 1) {
			return $rep;
		}
		$this->db->Prouser->save ( $row );
		return json_encode ( $row );
	}

	protected function QiniuUploadpic(&$arr, $bigdata, $smalldata) {
		$auth = new Auth ( $this->qiniuAK, $this->qiniuSK );
		$bucket = 'yiquanhost-avatar';
		$uploadMgr = new UploadManager ();
		$bucketMgr = new BucketManager ( $auth );
		
		if (isset ( $arr ['user_bigavatarname'] )) {
			list ( $ret, $err ) = $bucketMgr->delete ( $bucket, $arr ['user_bigavatarname'] );
		}
		if (isset ( $arr ['user_smallavatarname'] )) {
			list ( $ret, $err ) = $bucketMgr->delete ( $bucket, $arr ['user_smallavatarname'] );
		}
		
		$token = $auth->uploadToken ( $bucket );
		list ( $ret, $err ) = $uploadMgr->put ( $token, null, $bigdata );
		if ($err == null) {
			$arr ['user_bigavatarname'] = $ret ['key'];
			$arr ['user_bigavatar'] = $this->userpicbucketUrl . '/' . $ret ['key'];
		} else {
			return $err;
		}
		
		list ( $ret, $err ) = $uploadMgr->put ( $token, null, $smalldata );
		if ($err == null) {
			$arr ['user_smallavatarname'] = $ret ['key'];
			$arr ['user_smallavatar'] = $this->userpicbucketUrl . '/' . $ret ['key'];
		} else {
			return $err;
		}
		return 1;
	}


	function bindingByWeixin($user_id, $open_id, $access_token, $refresh_token) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkQuoteToken () != 1) {
			return - 3;
		}
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}
		$user = $this->db->Prouser->findOne ( array (
				'_id' => new MongoId ( $user_id ) 
		) );
		if ($user != null) {
			if ($this->checkWeixinExist == 1) {
				return - 3;
			} else {
				$res = $this->getWXUserInfo ( $access_token, $open_id );
				try {
					$userInfo = json_decode ( $res, TRUE );
					if ($userInfo ['openid'] != null) {
						$user ['weixin_Avatar'] = $userInfo ['headimgurl'];
						$user ['weixin_openID'] = $open_id;
						$user ['weixin_accessToken'] = $access_token;
						$user ['weixin_refreshToken'] = $refresh_token;
						$user ['user_city'] = $userInfo ['city'];
						if ($user ['user_smallavatar'] == '') {
							$user ['user_smallavatar'] = $userInfo ['headimgurl'];
						}
						$this->db->Prouser->save ( $user );
						return json_encode ( $user );
					}
				} catch ( Exception $e ) {
					return $e;
				}
			}
		} else {
			return - 2;
		}
	}

	function checkWeixinExist($open_id) {

		if ($this->yiquan_version == 0) {
			return - 2;
		}
		$ans = $this->db->Prouser->findOne ( array (
				'weixin_openID' => $open_id 
		) );
		
		if ($ans != null) {
			return 1;
		} else {
			return 0;
		}
	}

	function getWXUserInfo($access_token, $open_id) {
		$urlAdd = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $open_id;
		$ch = curl_init ( $urlAdd );
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 30 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt ( $ch, CURLOPT_BINARYTRANSFER, TRUE );
		curl_setopt ( $ch, CURLOPT_HEADER, FALSE );
		
		$res = curl_exec ( $ch );
		curl_close ( $ch );
		return $res;
	}

	function loginByWeixin($open_id, $access_token, $refresh_token) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		$ans = $this->db->Prouser->findOne ( array (
				'weixin_openID' => $open_id 
		) );
		if ($ans != null) {
			$gd = makeGuid ();
			setcookie ( "user_id", $ans ['_id'], time () + 3600 * 2400, '/' );
			setcookie ( "quser_token", $gd, time () + 3600 * 2400, '/' );
			
			// $_SESSION ['user_token'] = $gd;
			$rt = $this->db->usertoken->findOne ( array (
					'user_id' => $ans ['_id'] 
			) );
			if ($rt == null) {
				$rt = array (
						'user_id' => $ans ['_id'] 
				);
			}
			
			$rt ['quser_token'] = $gd;
			$this->db->usertoken->save ( $rt );
			
			if ($this->setRedis ( $ans ['_id'], $gd ) == false) {
				return - 5; // redis wrong
			}
			return json_encode ( $ans );
		} else {
			$res = $this->getWXUserInfo ( $access_token, $open_id );
			
			try {
				$userInfo = json_decode ( $res, TRUE );
				if ($userInfo ['openid'] != null) {
					//$id = $this->mid ( 'Quoteuser', $this->db );
					
					$neo = array (
							'user_mobile' => '',
							'user_nickname' => $userInfo ['nickname'],
							'user_state' => 1,
							'user_regdate' => new MongoDate (),
							'user_smallavatar' => $userInfo ['headimgurl'],
							'user_bigavatar' => '',
							'user_bigavatarname' => '',
							'user_smallavatarname' => '',
							'user_city' => $userInfo ['city'],
							'weixin_Avatar' => $userInfo ['headimgurl'],
							'weixin_openID' => $open_id,
							'weixin_accessToken' => $access_token,
							'weixin_refreshToken' => $refresh_token,
							'user_mediaGroups' => array(),
							'user_searchWords' => array(),
							'user_messageCheckTime' => 0,
							'user_gender' => $userInfo['sex'],
							'user_weight' => 1,
					);
					$this->db->Prouser->save ( $neo );
					
					$ans = $this->db->Prouser->findOne ( array (
							'weixin_openID' => $open_id 
					) );
					
					$gd = makeGuid ();
					setcookie ( "user_id", $ans ['_id'], time () + 3600 * 2400, '/' );
					setcookie ( "quser_token", $gd, time () + 3600 * 2400, '/' );
					
					// $_SESSION ['user_token'] = $gd;
					$rt = $this->db->usertoken->findOne ( array (
							'user_id' => $ans ['_id'] 
					) );
					if ($rt == null) {
						$rt = array (
								'user_id' => $ans ['_id'] 
						);
					}
					
					$rt ['quser_token'] = $gd;
					$this->db->usertoken->save ( $rt );
					
					if ($this->setRedis ( $ans ['_id'], $gd ) == false) {
						return - 5; // redis wrong
					}
					
					return json_encode ( $ans );
				} else {
					return - 1;
				}
			} catch ( Exception $e ) {
				return $e;
			}
		}
	}

	function getUserProfile($user_id){
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkQuoteToken () != 1) {
			return - 3;
		}
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}

		$user = $this->db->Prouser->findOne(array(
			'_id' => new MongoId($user_id)));
		return json_encode($user);
	}



}
?>
