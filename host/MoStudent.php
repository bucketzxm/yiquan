<?php
require_once 'YqBase.php';
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
/* Report all errors except E_NOTICE */
// error_reporting ( E_ALL & ~ E_NOTICE );
class MoStudent extends YqBase {
	//protected $bcs_host = 'bcs.duapp.com';
	
	/*
	 * made by wwq mid方法是实现id自动增长的一个辅助方法 原本用于users表的uid自动增长 现在已经可以无视
	 */
	private function mid($name, $db) {
		$update = array (
				'$inc' => array (
						"id" => 1 
				) 
		);
		
		$query = array (
				"name" => $name 
		);
		$command = array (
				"findandmodify" => 'ids',
				"update" => $update,
				"query" => $query,
				"new" => true,
				"upsert" => true 
		);
		
		$id = $db->command ( $command );
		return $id ['value'] ['id'];
	}
	
	/*
	 * made by wwq reg是指用户的注册 接受参数依次为 用户名 密码 手机号码 返回值 注册成功1 注册发生异常-1 soap客户端使用方法 $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" ); $result2 = $soap->reg ( 'q', '12345'，'13566632325' ); echo $result2 . "<br/>";
	 */
	function regStudent($user_name, $user_pwd) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkNameExist ( $user_name )) {
			return 0;
		}
		
		// if ($this->checkUsernameLegal ( $user_name ) == 0) {
		// return 0;
		// }
		
		//$this->logCallMethod ( $user_name, __METHOD__ );
		try {
			//$id = $this->mid ( 'user', $this->db );
			
			$neo = array (
			//		'uid' => $id,
					'student_username' => $user_name,
					'student_passworduser' => crypt ( $user_pwd ),
					'student_profile' => array(),
					'student_smallavatar' => '',
					'student_bigavatar' => '',
					'student_bigavatarname' => '',
					'student_smallavatarname' => '',
					'student_profile' => array (),
					"student_nameCN" =>'',
    				"student_nameEN" => '',
    				"student_gender" => '',
				    "student_province" => '',
				    "student_city" => '',
				    "student_schoolEN" => '',
				    "student_shcoolCN" => '',
				    "student_schoolID" => '',
				    "student_clubsNum" => 0,
				    "student_clubIDs" => array (),
				    "student_graduationYear" => '',
				    "student_contactInfo" => array (
				      "student_mobile" => '',
				      "student_qq" => '',
				      "student_email" => '',
				      "student_address" => ''
				    ),
				    "student_classToLearn"=> array(),
				    "user_state" => 1
			
			);
			$this->db->MoStudent->save ( $neo );
			
			;
			
			return 1;//$this->addProfileByName ( $user_name, json_encode ( $profile ) );
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	/*
	 * made by wwq username_exist指探测用户名是否已经存在 接受参数为 用户名 返回值 存在是1 不存在是0 异常是-1 soap客户端使用方法 $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" ); $ result2 = $soap->username_exist ( 'q'); echo $result2 . "<br/>";
	 */
	function checkStudentNameExist($user_name) {
		try {
			if ($this->yiquan_version == 0) {
				return - 2;
			}
			$this->logCallMethod ( 'anonymous', __METHOD__ );
			$ans = $this->db->MoStudent->findOne ( array (
					'student_username' => "$user_name" 
			) );
			
			self::$yidb->close ();
			if ($ans != null) {
				return 1;
			} else {
				return 0;
			}
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	/*
	 * made by wwq mobile_exist指探测手機號碼是否已经存在 接受参数为 手机号 返回值 存在是1 不存在是0 异常是-1 soap客户端使用方法 $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" ); $result2 = $soap->mobile_exist ( '123456789'); echo $result2 . "<br/>";
	 */
	function checkStudentMobileExist($user_mobile) {
		try {
			if ($this->yiquan_version == 0) {
				return - 2;
			}
			$this->logCallMethod ( 'anonymous ', __METHOD__ );
			$ans = $this->db->MoStudent->findOne ( array (
					'student_profile.student_contactInfo.student_mobile' => "$user_mobile" 
			) );
			
			if ($ans != null) {
				return 1;
			} else {
				return 0;
			}
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	/*
	 * made by wwq user_login指探测手機號碼是否已经存在 接受参数为 用户名 密码 返回值 登陆成功是1 用户名不存在是2 密码错误是3 异常是-1 注意 还会记录最近的登录时间哦 soap客户端使用方法 $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" ); $result2 = $soap->user_login ( 'wang','12344'); echo $result2 . "<br/>";
	 */
	function loginByStudent($user_name, $user_pwd) {
		try {
			if ($this->yiquan_version == 0) {
				return - 2;
			}
			$this->logCallMethod ( $user_name, __METHOD__ );
			$ans = $this->db->MoStudent->findOne ( array (
					'student_username' => "$user_name" 
			) );
			/*
			if ($ans == null) {
				$ans = $this->db->user->findOne ( array (
						'user_mobile' => "$user_name" 
				) );
			}*/
			
			if ($ans == null)
				return 2; // no user
			else if ($ans ['student_password'] != $user_pwd)//crypt ( $user_pwd, $ans ['student_password'] ))
				return 3; // wrong pwd
			else if ($ans ['user_state'] != 1)
				return 4;
			else {
				$gd = makeGuid ();
				setcookie ( "user", $user_name, time () + 3600 * 2400, '/' );
				setcookie ( "user_token", $gd, time () + 3600 * 2400, '/' );
				$_SESSION ['user'] = $user_name;
				// $_SESSION ['user_token'] = $gd;
				$rt = $this->db->usertoken->findOne ( array (
						'user_name' => $user_name 
				) );
				if ($rt == null) {
					$rt = array (
							'user_name' => $user_name 
					);
				}
				
				$rt ['user_token'] = $gd;
				$this->db->usertoken->save ( $rt );
				
				if ($this->setRedis ( $user_name, $gd ) == false) {
					return - 5; // redis wrong
				}
				return 1;
			}
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	

	/*
	 * made by wwq getuserbyname_xml指将指定用户名的用户的所有信息以json方式返回 接受参数为 用户名 返回值 一个xml字符串 soap客户端使用方法 $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" ); $result2 = $soap->getuserbyname_xml ( 'wang' ); echo $result2 . "<br/>";
	 */
	function getStudentByName($user_name) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}
		
		//$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		
		
		$ans = $this->db->MoStudent->findOne ( array (
				'student_username' => $user_name
		) );
		
		if ($ans == null)
			return 2;

		return json_encode ( $ans );
	}



	/*
	 * 注册设备
	 */
	
	function registerGetuiClientID($user_name, $getui_clientID) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}
		if (! isset ( $_COOKIE ['user'] ) || $_COOKIE ['user'] != $user_name) {
			return - 4;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		
		$cursor = $this->db->getuiClientID->findOne ( array (
				'user_name' => $user_name 
		) );
		try {
			if ($cursor == null) {
				$data = array (
						"user_name" => $user_name,
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

	function removeGetuiClientID($user_name) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		/*
		 * if ($this->checkToken () == 0) { return - 3; }
		 */
		if (! isset ( $_COOKIE ['user'] ) || $_COOKIE ['user'] != $user_name) {
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
	


}

/*
 * $a = new User (); // $t = json_decode ( $a->getRegisterCode ( '13564957795', 30 ), true ); // echo $t['msg']; $r=$a->checkRegisterCode('13564957795','uw89'); echo $r; $a = new User (); echo $a->enhanceRelationshipByName ( 'abc1', 'abc2', 100 ); $a = new User (); echo $a->weihu (); echo $a->changeSecondName ( 'abc0', 'kkmmjj2222', 'abc2' ); var_dump ( $a->getSecondFriendStats ( 'abc0' ) ); $a->reg ( 'abc1', '110', '110' ); $a->reg ( 'abc2', '112', '110' ); $a->reg ( 'abc3', '110', '110' ); $a->reg ( 'abc4', '110', '110' ); $a->reg ( 'abc5', '110', '110' ); $a->reg ( 'abc6', '110', '110' ); $a->reg ( 'abc7', '110', '110' ); $a->addProfileByName ( 'abc0', '{"profile_city":"shanghai"}' ); $a->addProfileByName ( 'abc1', '{"profile_city":"shanghai"}' ); $a->addProfileByName ( 'abc2', '{"profile_city":"shanghai"}' ); $a->addProfileByName ( 'abc3', '{"profile_city":"shanghai"}' ); $a->addProfileByName ( 'abc4', '{"profile_city":"shanghai"}' ); $a->addProfileByName ( 'abc5', '{"profile_city":"shanghai"}' ); $a->addProfileByName ( 'abc6', '{"profile_city":"shanghai"}' ); $a->addProfileByName ( 'abc7', '{"profile_city":"山东"}' ); $a->addFriendByName ( 'abc0', 'abc2' ); $a->addFriendByName ( 'abc1', 'abc2' ); $a->addFriendByName ( 'abc1', 'abc5' ); $a->addFriendByName ( 'abc5', 'abc3' ); $a->addFriendByName ( 'abc3', 'abc4' ); $a->addFriendByName ( 'abc5', 'abc6' ); $a->addFriendByName ( 'abc6', 'abc7' ); $a->addFriendByName ( 'abc4', 'abc7' ); $a->addFriendByName ( 'abc4', 'abc1' ); $a->addFriendByName ( 'abc2', 'abc4' ); $a->addFriendByName ( 'abc2', 'abc5' ); $a->addFriendByName ( 'abc0', 'abc2' ); echo $a->queryFirstFriendsByName ( 'abc0' ); echo $a-> ( 'abc0' ); echo $a->queryAllFriendsByName ( 'abc0' ); echo $a->countAllFriendsByName ( 'abc0' ); echo $a->querySecondFriendsByName ( 'abc0' ); echo $a->countSecondFriendsByName ( 'abc0' ); echo $a->listSecondFriendsByName ( 'abc0' ); echo $a->listAllFriendsByName ( 'abc0' ); echo $a->queryCommonFriendsByName ( 'abc0', 'abc1' ); $a = new User (); echo $a->deleteFriendByName ( 'abc0', 'abc1' ); echo $a->queryCommonFriendsByName ( 'abc0', 'abc1' );
 */

?>
