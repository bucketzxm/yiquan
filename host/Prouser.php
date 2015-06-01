<?php
require_once 'YqBase.php';

    
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
			$this->logCallMethod ( $user_name, __METHOD__ );

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
							'user_messageCheckTime' => time()
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


	function updateUserProfile ($user_id,$user_name,$user_gender,$user_city,$user_industry,$user_company,$user_title,$user_interestA,$user_interestB,$user_seniority,$profile_type){
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

				if ($user[$profile_type]['user_industry'] != null) {
					$para[$user[$profile_type]['user_industry']] --;
				}
				if (isset($para[$user[$profile_type]['user_industry']])) {
					$para[$user_industry] ++;
				}else{
					$para[$user_industry] = 1;
				}
				$this->db->Prosystem->save($para);

				$user[$profile_type]['user_name'] = $user_name;
				$user[$profile_type]['user_city'] = $user_city;
				$user[$profile_type]['user_industry'] = $user_industry;
				$user[$profile_type]['user_seniority'] = $user_seniority;
				$user[$profile_type]['user_interestA'] = $user_interestA;
				$user[$profile_type]['user_interestB'] = $user_interestB;
				$user[$profile_type]['user_company'] = $user_company;
				$user[$profile_type]['user_title'] = $user_title;
				$user[$profile_type]['user_gender'] = $user_gender;

				if ($user_seniority == '1-3年'){
					$user[$profile_type]['user_weight'] = 1;	
				}else if ($user_seniority == '3-5年'){
					$user[$profile_type]['user_weight'] = 3;	
				}else if ($user_seniority == '5-10年'){
					$user[$profile_type]['user_weight'] = 8;	
				}else if ($user_seniority == '10年以上'){
					$user[$profile_type]['user_weight'] = 15;	
				}else{
					$user[$profile_type]['user_weight'] = 0;	
				}
				
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
			return 1;
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


}
?>
