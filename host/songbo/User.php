<?php
require_once 'YqBase.php';

/* Report all errors except E_NOTICE */
// error_reporting ( E_ALL & ~ E_NOTICE );
class User extends YqBase {
	protected $bcs_host = 'bcs.duapp.com';
	
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
	 * made by wwq reg是指用户的注册 
	 * 接受参数依次为 用户名 密码 手机号码 
	 * 返回值 注册成功1 注册发生异常-1 
	 * soap客户端使用方法 $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" ); 
	 * $result2 = $soap->reg ( 'q', '12345'，'13566632325' ); echo $result2 . "<br/>";
	 */
	function reg($user_name, $user_pwd, $user_mobile) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkNameExist ( $user_name )) {
			return 0;
		}
		
		$this->logCallMethod ( $user_name, __METHOD__ );
		try {
			$id = $this->mid ( 'user', $this->db );
			
			$this->db->user->save ( array (
					'uid' => $id,
					'user_name' => $user_name,
					'user_pin' => crypt ( $user_pwd ),
					'user_mobile' => $user_mobile,
					'user_nickname' => $user_name,
					'user_pic' => null 
			) );
			
			return 1;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	/*
	 * made by wwq username_exist指探测用户名是否已经存在
	 * 接受参数为 用户名 返回值 存在是1 不存在是0 异常是-1 
	 * soap客户端使用方法 $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" ); $
	 * result2 = $soap->username_exist ( 'q'); echo $result2 . "<br/>";
	 */
	function checkNameExist($user_name) {
		try {
			if ($this->yiquan_version == 0) {
				return - 2;
			}
			$this->logCallMethod ( 'anonymous', __METHOD__ );
			$ans = $this->db->user->findOne ( array (
					'user_name' => "$user_name" 
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
	 * made by wwq mobile_exist指探测手機號碼是否已经存在 
	 * 接受参数为 手机号 返回值 存在是1 不存在是0 异常是-1 
	 * soap客户端使用方法 $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" ); 
	 * $result2 = $soap->mobile_exist ( '123456789'); echo $result2 . "<br/>";
	 */
	function checkMobileExist($user_mobile) {
		try {
			if ($this->yiquan_version == 0) {
				return - 2;
			}
			$this->logCallMethod ( 'anonymous ', __METHOD__ );
			$ans = $this->db->user->findOne ( array (
					'user_mobile' => "$user_mobile" 
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
	 * made by wwq user_login指探测手機號碼是否已经存在 
	 * 接受参数为 用户名 密码 返回值 登陆成功是1 用户名不存在是2 密码错误是3 异常是-1 注意 还会记录最近的登录时间哦 
	 * soap客户端使用方法 $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" ); 
	 * $result2 = $soap->user_login ( 'wang','12344'); echo $result2 . "<br/>";
	 */
	function loginByUser($user_name, $user_pwd) {
		try {
			if ($this->yiquan_version == 0) {
				return - 2;
			}
			$this->logCallMethod ( $user_name, __METHOD__ );
			$ans = $this->db->user->findOne ( array (
					'user_name' => "$user_name" 
			) );
			
			if ($ans == null) {
				$ans = $this->db->user->findOne ( array (
						'user_mobile' => "$user_name" 
				) );
			}
			
			if ($ans == null)
				return 2; // no user
			else if ($ans ['user_pin'] != crypt ( $user_pwd, $ans ['user_pin'] ))
				return 3; // wrong pwd
			else {
				$gd = makeGuid ();
				setcookie ( "user", $user_name, 0, '/' );
				setcookie ( "user_token", $gd, 0, '/' );
				$_SESSION ['user'] = $user_name;
				$_SESSION ['user_token'] = $gd;
				return 1;
			}
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	/*
	 * made by wwq getuserbyname_xml指将指定用户名的用户的所有信息以json方式返回
	 *  接受参数为 用户名 
	 *  返回值 一个xml字符串  
	 *  soap客户端使用方法 $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" ); 
	 *  $result2 = $soap->getuserbyname_xml ( 'wang' ); echo $result2 . "<br/>";
	 */
	function getUserByName($user_name) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}
		
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$ans = $this->db->user->findOne ( array (
				'user_name' => "$user_name" 
		) );
		if ($ans == null)
			return 2;
		$t = $ans ['_id'];
		// echo $t;
		$ans2 = $this->db->userProfile->findOne ( array (
				'user_objid' => $t 
		) );
		
		// echo var_dump ( $ans2 );
		if ($ans2 != null) {
			$ans ['userProfile'] = $ans2;
		}
		
		$ans ['countMyReplyByName'] = (new Reply ())->countMyReplyByName ( $user_name );
		$ans ['countTopicByName'] = (new Topic ())->countTopicByName ( $user_name );
		$ans ['countFirstFriendsByName'] = $this->countFirstFriendsByName ( $user_name );
		$ans ['countSecondFriendsByName'] = $this->countSecondFriendsByName ( $user_name );
		return json_encode ( $ans );
	}
	
	/*
	 * made by wwq getuserbymobile_xml指将指定手机号的用户的所有信息以json方式返回
	 *  接受参数为 用户名 
	 *  返回值 一个xml字符串  
	 *  soap客户端使用方法 $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" ); 
	 *  $result2 = $soap->getuserbyname_xml ( 'wang' ); echo $result2 . "<br/>";
	 */
	function getUserByMobile($user_mobile) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$ans = $this->db->user->findOne ( array (
				'user_mobile' => "$user_mobile" 
		) );
		if ($ans == null)
			return 4;
		$t = $ans ['_id'];
		// echo $t;
		$ans2 = $this->db->userProfile->findOne ( array (
				'user_objid' => $t 
		) );
		
		// echo var_dump ( $ans2 );
		if ($ans2 != null) {
			$ans ['userProfile'] = $ans2;
		}
		
		$ans ['countMyReplyByName'] = (new Reply ())->countMyReplyByName ( $user_name );
		$ans ['countTopicByName'] = (new Topic ())->countTopicByName ( $user_name );
		$ans ['countFirstFriendsByName'] = $this->countFirstFriendsByName ( $user_name );
		$ans ['countSecondFriendsByName'] = $this->countSecondFriendsByName ( $user_name );
		return json_encode ( $ans );
	}
	
	/*
	 * made by wwq getuserby_id_xml指将指定_id的用户的所有信息以xml方式返回 
	 * 接受参数为 用户名 返回值 一个json字符串  
	 * soap客户端使用方法 $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" );
	 *  $result2 = $soap->getuserby_id_xml ( '54c25f6ca3136ab006000002' ); echo $result2 . "<br/>";
	 */
	function getUserByID($user_id) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$ans = $this->db->user->findOne ( array (
				'_id' => new MongoId ( $user_id ) 
		) );
		
		if ($ans == null)
			return 4;
		$t = $ans ['_id'];
		// echo $t;
		$ans2 = $this->db->userProfile->findOne ( array (
				'user_objid' => $t 
		) );
		
		// echo var_dump ( $ans2 );
		if ($ans2 != null) {
			$ans ['userProfile'] = $ans2;
		}
		
		$ans ['countMyReplyByName'] = (new Reply ())->countMyReplyByName ( $user_name );
		$ans ['countTopicByName'] = (new Topic ())->countTopicByName ( $user_name );
		$ans ['countFirstFriendsByName'] = $this->countFirstFriendsByName ( $user_name );
		$ans ['countSecondFriendsByName'] = $this->countSecondFriendsByName ( $user_name );
		return json_encode ( $ans );
	}
	
	/*
	 * made by wwq makefriendwithby_id指将指定_id的用户a 与制定_id的用户b建立好友关系 
	 * 接受参数为 用户a的id 用户b的id 返回值 1代表成功 请注意 我这个表叫做userRelationships 还有一个要注意的 就是 type这东西 是为了方便以后扩展用的 人和人之间的关系除了好友以外还有别的 soap客户端
	 * 使用方法 $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" ); $result2 = $soap->makefriendwithby_id ( '54c25f6ca3136ab006000002','54c25f6ca3136gc006000002' ); echo $result2 . "<br/>";
	 */
	function addFriendByID($user_idA, $user_idB) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$ans = $this->db->userRelationship->findOne ( array (
				'usera_id' => new MongoId ( $user_idA ),
				'userb_id' => new MongoId ( $user_idB ),
				'relation_type' => 1 
		) );
		
		if ($ans == null) {
			$this->db->userRelationship->save ( array (
					'usera_id' => new MongoId ( $user_idA ),
					'userb_id' => new MongoId ( $user_idB ),
					'relation_type' => 1 
			) );
			
			$row = $this->db->userRelationship->findOne ( array (
					'usera_id' => new MongoId ( $user_idA ),
					'userb_id' => new MongoId ( $user_idB ),
					'relation_type' => 1 
			) );
			
			$tp = $this->db->user->findOne ( array (
					'_id' => new MongoId ( $user_idA ) 
			) );
			
			if (! isset ( $tp ['user_relationships'] )) {
				$tp ['user_relationships'] = array ();
			}
			
			$key = array_search ( $row, $tp );
			if ($key == false) {
				$tp ['user_relationships'] [] = $row;
			}
			// echo var_dump ( $tp );
			$this->db->user->save ( $tp );
		}
		
		$ans = $this->db->userRelationship->findOne ( array (
				'usera_id' => new MongoId ( $user_idB ),
				'userb_id' => new MongoId ( $user_idA ),
				'relation_type' => 1 
		) );
		
		if ($ans == null) {
			$this->db->userRelationship->save ( array (
					'usera_id' => new MongoId ( $user_idB ),
					'userb_id' => new MongoId ( $user_idA ),
					'relation_type' => 1 
			) );
			
			$row = $this->db->userRelationship->findOne ( array (
					'usera_id' => new MongoId ( $user_idB ),
					'userb_id' => new MongoId ( $user_idA ),
					'relation_type' => 1 
			) );
			
			$tp = $this->db->user->findOne ( array (
					'_id' => new MongoId ( $user_idB ) 
			) );
			
			if (! isset ( $tp ['user_relationships'] )) {
				$tp ['user_relationships'] = array ();
			}
			
			$key = array_search ( $row, $tp );
			if ($key == false) {
				$tp ['user_relationships'] [] = $row;
			}
			
			$this->db->user->save ( $tp );
		}
		// 双向好友关系的建立 正反都做一次
		return 1;
	}
	
	/*
	 * made by wwq makefriendwithbyuname指将指定用户a 与用户b建立好友关系 
	 * 接受参数为 用户a的name 用户b的name 返回值 1代表成功 请注意 我直接调用了makefriendwithby_id 
	 * soap客户端使用方法 $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" ); $result2 = $soap->makefriendwithbyuname ( 'qing','zhu' ); echo $result2 . "<br/>";
	 */
	function addFriendByName($user_nameA, $user_nameB) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$a_id = $this->db->user->findOne ( array (
				'user_name' => $user_nameA 
		) )['_id'];
		
		$b_id = $this->db->user->findOne ( array (
				'user_name' => $user_nameB 
		) )['_id'];
		// 找到各自的_id
		if ($a_id == null || $b_id == null) {
			return 4;
		}
		return $this->addFriendByID ( $a_id, $b_id );
	}
	
	/*
	 * made by wwq findAllfriendsby_id指将指定用户 的所有好友都罗列出来 
	 * 接受参数为 用户id 
	 * 返回值 
	 * soap客户端使用方法 $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" ); $result2 = $soap->findAllfriendsby_id ( '54c25f6ca3136ab006000002' ); echo $result2 . "<br/>";
	 */
	function queryFirstFriendsByID($user_id) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$ans = $this->db->user->findOne ( array (
				'_id' => new MongoId ( $user_id ) 
		) )['user_relationships'];
		
		$res = Array ();
		// 遍历$ans 指针
		foreach ( $ans as $k => $v ) {
			$tkp = $this->db->user->findOne ( array (
					'_id' => $v ['userb_id'] 
			), array (
					'_id' => 1,
					'user_name' => 1,
					'user_mobile' => 1,
					'user_nickname' => 1,
					'user_pic' => 1 
			) );
			$res [] = $tkp;
		}
		
		return json_encode ( $res );
	}
	
	/*
	 * made by wwq findAllfriendsby_uname指将指定用户 的所有好友都罗列出来 
	 * 接受参数为 用户username 返回值 好友的_id 
	 * soap客户端使用方法 $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" ); $result2 = $soap->findAllfriendsby_uname ( 'qing' ); echo $result2 . "<br/>";
	 */
	function queryFirstFriendsByName($user_name) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$ans = $this->db->user->findOne ( array (
				'user_name' => $user_name 
		) )['user_relationships'];
		
		/*
		 * $ans = $this->db->userRelationship->find ( array ( 'usera_id' => new MongoId ( $id ) ) );
		 */
		
		$res = Array ();
		// 遍历$ans 指针
		foreach ( $ans as $k => $v ) {
			
			$tkp = $this->db->user->findOne ( array (
					'_id' => $v ['userb_id'] 
			), array (
					'_id' => 1,
					'user_name' => 1,
					'user_mobile' => 1,
					'user_nickname' => 1,
					'user_pic' => 1 
			) );
			$res [] = $tkp;
		}
		
		return json_encode ( $res );
	}
	
	/*
	 * made by wwq findAllfriendsby_uname指将指定用户 的所有好友数量
	 *  接受参数为 用户username 返回值 一度好友数量值
	 *   soap客户端使用方法 $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" ); $result2 = $soap->findAllfriends_count_by_uname ( 'qing' ); echo $result2 . "<br/>";
	 */
	function countFirstFriendsByName($user_name) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$res = $this->db->user->findOne ( array (
				'user_name' => $user_name 
		) )['user_relationships'];
		return count ( $res );
	}
	
	/*
	 * made by wwq addProfilebyuname指将指定用户增加额外信息放入userProfiles表 
	 * 接受参数为 用户username 一个json字符串数据
	 *  返回值 1代表成功 soap客户端使用方法 $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" ); 
	 *  $result2 = $soap->addProfilebyuname ( 'q', '{"like":890,"father":"dave","good":"moring","mother":"0022"}' ); echo $result2 . "<br/>";
	 */
	function addProfileByName($user_name, $user_profile) {
		try {
			if ($this->yiquan_version == 0) {
				return - 2;
			}
			if ($this->checkToken () == 0) {
				return - 3;
			}
			$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
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
	
	/**
	 * 对象数组转为普通数组
	 *
	 * AJAX提交到后台的JSON字串经decode解码后为一个对象数组，
	 * 为此必须转为普通数组后才能进行后续处理，
	 * 此函数支持多维数组处理。
	 *
	 * @param
	 *        	array
	 * @return array
	 */
	private function objarray_to_array($obj) {
		$ret = array ();
		foreach ( $obj as $key => $value ) {
			if (gettype ( $value ) == "array" || gettype ( $value ) == "object") {
				$ret [$key] = objarray_to_array ( $value );
			} else {
				$ret [$key] = $value;
			}
		}
		return $ret;
	}
	/*
	 * made by wwq findAllfriendsby_id_usearray指将指定用户id 的所有好友都罗列出来 
	 * 接受参数为 用户id fromid 指某一个人将会被排除在寻找范围之外 返回值 一个数组集合 没有转json 不建议soap使用
	 */
	private function findAllfriendsby_id_usearray_except($id, $fromid) {
		$ans = $this->db->user->findOne ( array (
				'_id' => new MongoId ( $id ) 
		) );
		
		$res = Array ();
		// 遍历$ans 指针
		foreach ( $ans ['user_relationships'] as $k => $v ) {
			
			if ($v ['userb_id'] == $fromid) {
				continue;
			}
			$res [] = $v ['userb_id']->{'$id'};
		}
		
		return $res;
	}
	
	/*
	 * made by wwq get_AllFriends_of_Myfriends_info_by_uname_usearray指将指定用户名的好友以及二度好友好友都罗列出来 
	 * 接受参数为 用户uname 返回值 一个数组集合 没有转json 不建议soap使用
	 */
	private function get_AllFriends_of_Myfriends_info_by_uname_usearray($user_name) {
		$row = $this->db->user->findOne ( array (
				'user_name' => $user_name 
		) );
		
		$user_namerels = $row ['user_relationships'];
		
		$res = Array ();
		
		foreach ( $user_namerels as $k => $v ) {
			$res [] = $v ['userb_id']->{'$id'};
			// echo var_dump($this->objarray_to_array($v['userb_id'])).'<br/>';
			$res = array_merge ( $res, $this->findAllfriendsby_id_usearray_except ( $v ['userb_id'], $row ['_id'] ) );
		}
		
		return array_flip ( array_flip ( $res ) );
	}
	private function get_AllFriends_of_Myfriends_info_by_uname_usearray_bfs($user_name) {
		$row = $this->db->user->findOne ( array (
				'user_name' => $user_name 
		) );
		
		$user_namerels = $row ['user_relationships'];
		
		$res = Array ();
		
		foreach ( $user_namerels as $k => $v ) {
			$res [] = $v ['userb_id']->{'$id'};
			// echo var_dump($this->objarray_to_array($v['userb_id'])).'<br/>';
			$res = array_merge ( $res, $this->findAllfriendsby_id_usearray_except ( $v ['userb_id'], $row ['_id'] ) );
		}
		
		return array_flip ( array_flip ( $res ) );
	}
	
	/*
	 * made by wwq get_All_erdu_Friends_of_Myfriends_info_by_uname_usearray指将指定用户名的二度好友好友都罗列出来 
	 * 接受参数为 用户uname 返回值 一个数组集合 没有转json 不建议soap使用
	 */
	private function get_All_erdu_Friends_of_Myfriends_info_by_uname_usearray($user_name) {
		$row = $this->db->user->findOne ( array (
				'user_name' => $user_name 
		) );
		
		$user_namerels = $row ['user_relationships'];
		
		$res = Array ();
		$res2 = Array ();
		foreach ( $user_namerels as $k => $v ) {
			$res2 [] = $v ['userb_id']->{'$id'};
			// echo var_dump($this->objarray_to_array($v['userb_id'])).'<br/>';
			$res = array_merge ( $res, $this->findAllfriendsby_id_usearray_except ( $v ['userb_id'], $row ['_id'] ) );
		}
		
		$res = array_flip ( array_flip ( $res ) );
		return array_diff ( $res, $res2 );
	}
	
	/*
	 * made by wwq 按照用户名寻找到他的所有 一度 二度的好友 的用户信息 
	 * 返回 所有好友的信息集合 json $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" ); $result2 = $soap->get_AllFriends_info_of_Myfeinds_by_uname ( 'q' );
	 */
	function queryAllFriendsByName($user_name) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$res = Array ();
		$pt = $this->get_AllFriends_of_Myfriends_info_by_uname_usearray ( $user_name );
		foreach ( $pt as $v ) {
			$pkt = $this->db->user->findOne ( array (
					'_id' => new MongoId ( $v ) 
			), array (
					'_id' => 1,
					'user_name' => 1,
					'user_mobile' => 1,
					'user_nickname' => 1,
					'user_pic' => 1 
			) );
			$res [] = $pkt;
		}
		return json_encode ( $res );
	}
	
	/*
	 * made by wwq 按照用户名寻找到他的所有 一度 二度的好友的总数 返回 所有好友的总数
	 *  $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" ); $result2 = $soap->get_AllFriends_count_of_Myfeinds_by_uname ( 'q' );
	 */
	function countAllFriendsByName($user_name) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		return count ( $this->get_AllFriends_of_Myfriends_info_by_uname_usearray ( $user_name ) );
	}
	
	/*
	 * made by wwq 按照用户名寻找到他的仅仅二度的好友 的用户信息 返回 所有好友的信息集合 json 
	 * $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" ); $result2 = $soap->get_AllFriends_info_of_Myfeinds_by_uname ( 'q' );
	 */
	function querySecondFriendsByName($user_name) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$res = Array ();
		$pt = $this->get_All_erdu_Friends_of_Myfriends_info_by_uname_usearray ( $user_name );
		foreach ( $pt as $v ) {
			$pkt = $this->db->user->findOne ( array (
					'_id' => new MongoId ( $v ) 
			), array (
					'_id' => 1,
					'user_name' => 1,
					'user_mobile' => 1,
					'user_nickname' => 1,
					'user_pic' => 1 
			) );
			$res [] = $pkt;
		}
		return json_encode ( $res );
	}
	
	/*
	 * made by wwq 按照用户名寻找到他的仅仅二度的好友的总数 返回 所有好友的总数
	 *  $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" ); $result2 = $soap->get_AllFriends_count_of_Myfeinds_by_uname ( 'q' );
	 */
	function countSecondFriendsByName($user_name) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		return count ( $this->get_All_erdu_Friends_of_Myfriends_info_by_uname_usearray ( $user_name ) );
	}
	
	/*
	 * made by wwq 
	 * 按照用户名寻找到他的仅仅二度的好友 的用户信息 返回 所有好友的用户名字符串 逗号分隔
	* $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" ); 
	* $result2 = $soap->get_All_erdu_Friends_info_of_Myfriends_by_uname_dotstring ( 'q' );
	*/
	function listSecondFriendsByName($user_name) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$res = 'system,';
		$pt = $this->get_All_erdu_Friends_of_Myfriends_info_by_uname_usearray ( $user_name );
		foreach ( $pt as $v ) {
			$pkt = $this->db->user->findOne ( array (
					'_id' => new MongoId ( $v ) 
			), array (
					'_id' => 1,
					'user_name' => 1 
			) );
			$res .= $pkt ['user_name'];
			$res .= ',';
		}
		return substr ( $res, 0, strlen ( $res ) - 1 );
	}
	
	/*
	 * made by wwq 按照用户名寻找到他的所有 一度 二度的好友 的用户信息  字符串
	* 返回 所有好友的信息集合 json $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" ); 
	* $result2 = $soap->get_AllFriends_info_of_Myfriends_by_uname_dotstring ( 'q' );
	*/
	function listAllFriendsByName($user_name) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$res = 'system,';
		$pt = $this->get_AllFriends_of_Myfriends_info_by_uname_usearray ( $user_name );
		foreach ( $pt as $v ) {
			$pkt = $this->db->user->findOne ( array (
					'_id' => new MongoId ( $v ) 
			), array (
					'_id' => 1,
					'user_name' => 1 
			) );
			$res .= $pkt ['user_name'];
			$res .= ',';
		}
		return substr ( $res, 0, strlen ( $res ) - 1 );
	}
	
	/*根据两个用户名 a 和  b
	 * 找到他们的共同好友
	 * */
	function queryCommonFriendsByName($user_nameA, $user_nameB) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$friendsofa = $this->db->user->findOne ( array (
				'user_name' => $user_nameA 
		), array (
				'user_relationships' => 1 
		) )['user_relationships'];
		$friendsofb = $this->db->user->findOne ( array (
				'user_name' => $user_nameB 
		), array (
				'user_relationships' => 1 
		) )['user_relationships'];
		
		$duibi = array ();
		
		foreach ( $friendsofa as $key => $v ) {
			// echo var_dump($v['userb_id']);
			$k = $v ['userb_id']->{'$id'};
			if (array_key_exists ( $k, $duibi ))
				$duibi [$k] += 1;
			else
				$duibi [$k] = 1;
		}
		
		foreach ( $friendsofb as $key => $v ) {
			// echo var_dump($v['userb_id']);
			$k = $v ['userb_id']->{'$id'};
			if (array_key_exists ( $k, $duibi ))
				$duibi [$k] += 1;
			else
				$duibi [$k] = 1;
		}
		// var_dump($duibi);
		
		$ans = array ();
		
		foreach ( $duibi as $k => $v ) {
			if ($v == 2) {
				$tkp = $this->db->user->findOne ( array (
						'_id' => new MongoId ( $k ) 
				), array (
						'_id' => 1,
						'user_name' => 1,
						'user_mobile' => 1,
						'user_nickname' => 1,
						'user_pic' => 1 
				) );
				$ans [] = $tkp;
			}
		}
		
		return json_encode ( $ans );
	}
	
	/*根据用户名上传他的照片  照片需要用base64编码传送       原始大照片进入bcs  小照片进入user_pic字段
	 * 成功返回1 bcs出错返回3  没有这个用户返回2
	* */
	function updateUserpicByUsername($data, $user_name) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$row = $this->db->user->findOne ( array (
				'user_name' => $user_name 
		) );
		if ($row == null)
			return 2;
		$rawpic = base64_decode ( $data );
		$bucket = 'yiquan';
		$object = '/userPics/' . $row ['_id'];
		// echo $object;
		$baiduBCS = new BaiduBCS ( $this->ak, $this->sk, $this->bcs_host );
		$response = $baiduBCS->create_object_by_content ( $bucket, $object, $rawpic );
		if (! $response->isOK ()) {
			return 3; // bcs error
		}
		
		$im = new Imagick ();
		$im->readImageBlob ( $rawpic );
		$geo = $im->getImageGeometry ();
		$w = $geo ['width'];
		$h = $geo ['height'];
		$maxWidth = $maxHeight = 100;
		$fitbyWidth = (($maxWidth / $w) < ($maxHeight / $h)) ? true : false;
		
		if ($fitbyWidth) {
			$im->thumbnailImage ( $maxWidth, 0, false );
		} else {
			$im->thumbnailImage ( 0, $maxHeight, false );
		}
		$row ['user_pic'] = base64_encode ( $im );
		$this->db->user->save ( $row );
		return 1;
	}
	
	/*根据用户名返回原始图片  base64编码*/
	function getRawuserPicByUsername($user_name) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$row = $this->db->user->findOne ( array (
				'user_name' => $user_name 
		) );
		
		if ($row == null)
			return 2;
			
			// return $row ['user_pic'];
		$bucket = 'yiquan';
		
		$object = '/userPics/' . $row ['_id'];
		
		$baiduBCS = new BaiduBCS ( $this->ak, $this->sk, $this->bcs_host );
		
		$response = $baiduBCS->get_object ( $bucket, $object );
		// var_dump($response);
		if (! $response->isOK ()) {
			return null;
		}
		// echo $response->body;
		return base64_encode ( $response->body );
	}
	function deleteFriendByName($user_nameA, $user_nameB) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		try {
			$rowa = $this->db->user->findOne ( array (
					'user_name' => $user_nameA 
			) );
			
			$rowb = $this->db->user->findOne ( array (
					'user_name' => $user_nameB 
			) );
			if ($rowa == null || $rowb == null) {
				return 2;
			}
			foreach ( $rowa ['user_relationships'] as $key => $v ) {
				if ($v ['userb_id'] == $rowb ['_id']) {
					array_splice ( $rowa ['user_relationships'], $key, 1 );
					break;
				}
			}
			
			foreach ( $rowb ['user_relationships'] as $key => $v ) {
				if ($v ['userb_id'] == $rowa ['_id']) {
					array_splice ( $rowb ['user_relationships'], $key, 1 );
					break;
				}
			}
			$this->db->user->save ( $rowa );
			$this->db->user->save ( $rowb );
			$resatob = $this->db->userRelationship->remove ( array (
					'usera_id' => $rowa ['_id'],
					'userb_id' => $rowb ['_id'] 
			) );
			
			$resbtoa = $this->db->userRelationship->remove ( array (
					'usera_id' => $rowb ['_id'],
					'userb_id' => $rowa ['_id'] 
			) );
			return 1;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	function changePassword($user_name, $user_oldpwd, $user_newpwd) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$row = $this->db->user->findOne ( array (
				'user_name' => $user_name 
		) );
		
		if ($row == null)
			return 2;
		
		return $this->changePasswordByID ( $row ['_id'], $user_oldpwd, $user_newpwd );
	}
	function changePasswordByID($user_id, $user_oldpwd, $user_newpwd) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		try {
			$row = $this->db->user->findOne ( array (
					'_id' => $user_id 
			) );
			
			if ($row == null) {
				return 4;
			}
			if ($row ['user_pin'] != crypt ( $user_oldpwd, $row ['user_pin'] )) {
				return 3;
			}
			
			$row ['user_pin'] = crypt ( $user_newpwd );
			
			$this->db->user->save ( $row );
			return 1;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	function getSecondFriendStats($user_name) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$res1 = $this->get_All_erdu_Friends_of_Myfriends_info_by_uname_usearray ( $user_name );
		$ans = Array ();
		foreach ( $res1 as $v ) {
			$tpa = $this->db->userProfile->findOne ( array (
					'user_objid' => new MongoId ( $v ) 
			) );
			
			if ($tpa == null)
				continue;
			$q = $tpa ['profile_city'];
			if (! isset ( $ans ["$q"] )) {
				$ans ["$q"] = 1;
			} else {
				$ans ["$q"] ++;
			}
		}
		return json_encode ( $ans );
	}
}

/*
$a = new User ();
//echo $a->loginByUser('abc1','110');
echo $a->getUserByName('abc1');
$a->reg ( 'abc0', '110', '110' );
$a->reg ( 'abc1', '110', '110' );
$a->reg ( 'abc2', '112', '110' );
$a->reg ( 'abc3', '110', '110' );
$a->reg ( 'abc4', '110', '110' );
$a->reg ( 'abc5', '110', '110' );
$a->reg ( 'abc6', '110', '110' );
$a->reg ( 'abc7', '110', '110' );

$a->addProfileByName ( 'abc0', '{"profile_city":"shanghai"}' );
$a->addProfileByName ( 'abc1', '{"profile_city":"shanghai"}' );
$a->addProfileByName ( 'abc2', '{"profile_city":"shanghai"}' );
$a->addProfileByName ( 'abc3', '{"profile_city":"shanghai"}' );
$a->addProfileByName ( 'abc4', '{"profile_city":"shanghai"}' );
$a->addProfileByName ( 'abc5', '{"profile_city":"shanghai"}' );
$a->addProfileByName ( 'abc6', '{"profile_city":"shanghai"}' );
$a->addProfileByName ( 'abc7', '{"profile_city":"山东"}' );



$a->addFriendByName ( 'abc0', 'abc2' );
$a->addFriendByName ( 'abc1', 'abc2' );
$a->addFriendByName ( 'abc1', 'abc5' );
$a->addFriendByName ( 'abc5', 'abc3' );
$a->addFriendByName ( 'abc3', 'abc4' );
$a->addFriendByName ( 'abc5', 'abc6' );
$a->addFriendByName ( 'abc6', 'abc7' );
$a->addFriendByName ( 'abc4', 'abc7' );
$a->addFriendByName ( 'abc4', 'abc1' );
$a->addFriendByName ( 'abc2', 'abc4' );
$a->addFriendByName ( 'abc2', 'abc5' );
$a->addFriendByName ( 'abc0', 'abc2' );
echo $a->getSecondFriendStats ( 'abc2' );
*/
?>
