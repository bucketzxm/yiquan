<?php
include 'dbobj.php';
class userclass {
	static $yidb;
	private $db;
	private $user = 'hGQdTvvG8oiEFe3EseT4aoLT';
	private $pwd = 'TT1tVoot4Neo8lXRclxP0xIimqR7QnA1';
	// private $user = '';
	// private $pwd = '';
	private $dbname = 'YiDDTYNSihVFhKGsicHU';
	// private $dbname = 'yiquan';
	
	/*made by wwq
	 * 构造函数
	 * 疯狂连接与认证
	 * 实属无奈*/
	function __construct() {
		try {
			if (self::$yidb == null) {
				self::$yidb = connectDb ();
			}
			self::$yidb->connect ();
		} catch ( Exception $e ) {
			self::$yidb = connectDb ();
		}
		while ( 1 ) {
			$this->db = self::$yidb->selectDB ( $this->dbname );
			if ($this->user != '' && $this->pwd != '') {
				$fa = $this->db->authenticate ( $this->user, $this->pwd );
				if ($fa ['ok'] == 0) {
					sleep ( 1 );
					continue;
				}
			}
			
			break;
		}
	}
	
	/*made by wwq
	 * 析构函数  顺便关闭连接  mongo的无奈
	 * */
	function __destruct() {
		self::$yidb->close ();
	}
	
	/*made by wwq
	 * mid方法是实现id自动增长的一个辅助方法
	 * 原本用于users表的uid自动增长  现在已经可以无视*/
	function mid($name, $db) {
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
	
	/* made by wwq
	 * reg是指用户的注册
	 * 接受参数依次为  用户名   密码   手机号码
	 * 返回值 注册成功1  注册发生异常0
	 * soap客户端使用方法
	 * $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" );
	 * $result2 = $soap->reg ( 'q', '12345'，'13566632325' );
	 * echo $result2 . "<br/>";*/
	function reg($uname, $pwd, $mobile) {
		try {
			$id = $this->mid ( 'user', $this->db );
			
			$this->db->users->save ( array (
					'uid' => $id,
					'user_name' => $uname,
					'user_pin' => $pwd,
					'user_mobile' => $mobile 
			) );
			
			return 1;
		} catch ( Exception $e ) {
			return 0;
		}
	}
	
	/* made by wwq
	 * username_exist指探测用户名是否已经存在
	 * 接受参数为  用户名
	 * 返回值  存在是1  不存在是0  异常是-1
	 * soap客户端使用方法
	 * $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" );
	 * $result2 = $soap->username_exist ( 'q');
	 * echo $result2 . "<br/>";
	 * */
	function username_exist($uname) {
		try {
			$ans = $this->db->users->findOne ( array (
					'user_name' => "$uname" 
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
	
	/* made by wwq
	 * mobile_exist指探测手機號碼是否已经存在
	 * 接受参数为   手机号
	 * 返回值  存在是1  不存在是0  异常是-1
	 * soap客户端使用方法
	 * $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" );
	* $result2 = $soap->mobile_exist ( '123456789');
	* echo $result2 . "<br/>";
	* */
	function mobile_exist($mb) {
		try {
			$ans = $this->db->users->findOne ( array (
					'user_mobile' => "$mb" 
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
	
	/* made by wwq
	 * user_login指探测手機號碼是否已经存在
	 * 接受参数为  用户名  密码 
	* 返回值  登陆成功是1  用户名不存在是2 密码错误是3  异常是-1  
	* 注意  还会记录最近的登录时间哦
	* soap客户端使用方法
	* $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" );
	* $result2 = $soap->user_login ( 'wang','12344');
	* echo $result2 . "<br/>";
	* */
	function user_login($uname, $pw) {
		try {
			$ans = $this->db->users->findOne ( array (
					'user_name' => "$uname" 
			) );
			
			if ($ans == null) {
				$ans = $this->db->users->findOne ( array (
						'user_mobile' => "$uname" 
				) );
			}
			
			if ($ans == null)
				return 2; // no user
			else if ($ans ['user_pin'] != $pw)
				return 3;
			else
				return 1;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	/* made by wwq
	 * getuserbyname_xml指将指定用户名的用户的所有信息以xml方式返回
	 * 接受参数为  用户名  
	* 返回值  一个xml字符串
	* 下面是样例
	* <result><_id>54c1a888a3136a41b8000000</_id><uid>460</uid><user_name>q</user_name><user_pin>1233</user_pin><user_mobile>1231222231</user_mobile><userProfile><_id>54c24e0f216b570388a0212c</_id><like>890</like><father>dave</father><good>moring</good><mother>0022</mother><user_objid>54c1a888a3136a41b8000000</user_objid></userProfile></result>
	* soap客户端使用方法
	* $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" );
	* $result2 = $soap->getuserbyname_xml ( 'wang' );
	* echo $result2 . "<br/>";
	* */
	function getuserbyname_xml($uname) {
		$ans = $this->db->users->findOne ( array (
				'user_name' => "$uname" 
		) );
		
		$t = $ans ['_id'];
		// echo $t;
		$ans2 = $this->db->userProfiles->findOne ( array (
				'user_objid' => $t 
		) );
		
		// echo var_dump ( $ans2 );
		if ($ans2 != null) {
			$ans ['userProfile'] = $ans2;
		}
		
		return json_encode ( $ans );
	}
	
	/* made by wwq
	 * getuserby_id_xml指将指定_id的用户的所有信息以xml方式返回
	 * 接受参数为  用户名
	* 返回值  一个xml字符串
	* 下面是样例
	* <result><_id>54c1a888a3136a41b8000000</_id><uid>460</uid><user_name>q</user_name><user_pin>1233</user_pin><user_mobile>1231222231</user_mobile><userProfile><_id>54c24e0f216b570388a0212c</_id><like>890</like><father>dave</father><good>moring</good><mother>0022</mother><user_objid>54c1a888a3136a41b8000000</user_objid></userProfile></result>
	* soap客户端使用方法
	* $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" );
	* $result2 = $soap->getuserby_id_xml ( '54c25f6ca3136ab006000002' );
	* echo $result2 . "<br/>";
	* */
	function getuserby_id_xml($id) {
		$ans = $this->db->users->findOne ( array (
				'_id' => new MongoId ( $id ) 
		) );
		
		return json_encode ( $ans );
	}
	
	/* made by wwq
	 * makefriendwithby_id指将指定_id的用户a 与制定_id的用户b建立好友关系  
	 * 接受参数为  用户a的id  用户b的id
	* 返回值  1代表成功
	* 
	* 请注意 我这个表叫做userRelationships  还有一个要注意的 就是 type这东西 是为了方便以后扩展用的  人和人之间的关系除了好友以外还有别的
	* 
	* soap客户端使用方法
	* $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" );
	* $result2 = $soap->makefriendwithby_id ( '54c25f6ca3136ab006000002','54c25f6ca3136gc006000002' );
	* echo $result2 . "<br/>";
	* */
	function makefriendwithby_id($a_id, $b_id) {
		$ans = $this->db->userRelationships->findOne ( array (
				'usera_id' => new MongoId ( $a_id ),
				'userb_id' => new MongoId ( $b_id ),
				'type' => 1 
		) );
		
		if ($ans == null) {
			$this->db->userRelationships->save ( array (
					'usera_id' => new MongoId ( $a_id ),
					'userb_id' => new MongoId ( $b_id ),
					'type' => 1 
			) );
			
			$row = $this->db->userRelationships->findOne ( array (
					'usera_id' => new MongoId ( $a_id ),
					'userb_id' => new MongoId ( $b_id ),
					'type' => 1 
			) );
			
			$tp = $this->db->users->findOne ( array (
					'_id' => new MongoId ( $a_id ) 
			) );
			
			if (! isset ( $tp ['relationships'] )) {
				$tp ['relationships'] = array ();
			}
			
			$key = array_search ( $row, $tp );
			if ($key == false) {
				$tp ['relationships'] [] = $row;
			}
			// echo var_dump ( $tp );
			$this->db->users->save ( $tp );
		}
		
		$ans = $this->db->userRelationships->findOne ( array (
				'usera_id' => new MongoId ( $b_id ),
				'userb_id' => new MongoId ( $a_id ),
				'type' => 1 
		) );
		
		if ($ans == null) {
			$this->db->userRelationships->save ( array (
					'usera_id' => new MongoId ( $b_id ),
					'userb_id' => new MongoId ( $a_id ),
					'type' => 1 
			) );
			
			$row = $this->db->userRelationships->findOne ( array (
					'usera_id' => new MongoId ( $b_id ),
					'userb_id' => new MongoId ( $a_id ),
					'type' => 1 
			) );
			
			$tp = $this->db->users->findOne ( array (
					'_id' => new MongoId ( $b_id ) 
			) );
			
			if (! isset ( $tp ['relationships'] )) {
				$tp ['relationships'] = array ();
			}
			
			$key = array_search ( $row, $tp );
			if ($key == false) {
				$tp ['relationships'] [] = $row;
			}
			
			$this->db->users->save ( $tp );
		}
		// 双向好友关系的建立 正反都做一次
		return 1;
	}
	
	/* made by wwq
	 * makefriendwithbyuname指将指定用户a 与用户b建立好友关系
	 * 接受参数为  用户a的name  用户b的name
	* 返回值  1代表成功
	*
	* 请注意 我直接调用了makefriendwithby_id
	*
	* soap客户端使用方法
	* $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" );
	* $result2 = $soap->makefriendwithbyuname ( 'qing','zhu' );
	* echo $result2 . "<br/>";
	* */
	function makefriendwithby_uname($unamea, $unameb) {
		$a_id = $this->db->users->findOne ( array (
				'user_name' => $unamea 
		) )['_id'];
		
		$b_id = $this->db->users->findOne ( array (
				'user_name' => $unameb 
		) )['_id'];
		// 找到各自的_id
		
		return $this->makefriendwithby_id ( $a_id, $b_id );
	}
	
	/* made by wwq
	 * findAllfriendsby_id指将指定用户 的所有好友都罗列出来
	 * 接受参数为  用户id
	* 返回值  一个以<results> <result>。。。</result><result>。。。</result></results>作为大框架的xml字符串
	
	* soap客户端使用方法
	* $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" );
	* $result2 = $soap->findAllfriendsby_id ( '54c25f6ca3136ab006000002' );
	* echo $result2 . "<br/>";
	* */
	function findAllfriendsby_id($id) {
		$ans = $this->db->users->find ( array (
				'_id' => new MongoId ( $id ) 
		) )['relationships'];
		
		$res = Array ();
		// 遍历$ans 指针
		foreach ( $ans as $k => $v ) {
			$res [] = $v;
		}
		
		return json_encode ( $res );
	}
	
	/* made by wwq
	 * findAllfriendsby_uname指将指定用户 的所有好友都罗列出来
	 * 接受参数为  用户username
	* 返回值  好友的_id
	
	* soap客户端使用方法
	* $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" );
	* $result2 = $soap->findAllfriendsby_uname ( 'qing' );
	* echo $result2 . "<br/>";
	* */
	function findAllfriendsby_uname($uname) {
		$ans = $this->db->users->findOne ( array (
				'user_name' => $uname 
		) )['relationships'];
		
		/* $ans = $this->db->userRelationships->find ( array (
				'usera_id' => new MongoId ( $id ) 
		) ); */
		
		$res = Array ();
		// 遍历$ans 指针
		foreach ( $ans as $k => $v ) {
			$res [] = $v;
		}
		
		return json_encode ( $res );
	}
	
	/* made by wwq
	 * findAllfriendsby_uname指将指定用户 的所有好友数量
	* 接受参数为  用户username
	* 返回值  一度好友数量值
	* soap客户端使用方法
	* $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" );
	* $result2 = $soap->findAllfriends_count_by_uname ( 'qing' );
	* echo $result2 . "<br/>";
	* */
	function findAllfriends_count_by_uname($uname) {
		$res = $this->db->users->findOne ( array (
				'user_name' => $uname 
		) )['relationships'];
		return count ( $res );
	}
	
	/* made by wwq
	 * addProfilebyuname指将指定用户增加额外信息放入userProfiles表
	 * 接受参数为  用户username  一个json字符串数据
	* 返回值  1代表成功
	* soap客户端使用方法
	* $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" );
	* $result2 = $soap->addProfilebyuname ( 'q', '{"like":890,"father":"dave","good":"moring","mother":"0022"}' );
	* echo $result2 . "<br/>";
	* */
	function addProfileby_uname($uname, $jsondata) {
		try {
			$arr = json_decode ( $jsondata, true ); // 将json数据变成php的数组
			$ob = $this->db->users->findOne ( array (
					'user_name' => $uname 
			) );
			
			$arr ['user_objid'] = $ob ['_id']; // 给arr数组加一个字段user_objid arr数组是后面整体要做更新的数组
			
			$update = $arr;
			
			$query = array (
					"user_objid" => $ob ['_id'] 
			);
			// 注意看这里 mongodb支持一个叫findandmodify的操作 就是先修改后查询 很好用
			// 我这里代码的意图是 $query指定的数据 用$update里面的数据更新掉
			$command = array (
					"findandmodify" => 'userProfiles',
					"update" => $update,
					"query" => $query,
					"new" => true,
					"upsert" => true 
			);
			
			$id = $this->db->command ( $command ); // 执行更新
			
			return 1;
		} catch ( Exception $e ) {
			return 0;
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
	function objarray_to_array($obj) {
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
	/* made by wwq
	 * findAllfriendsby_id_usearray指将指定用户id 的所有好友都罗列出来
	* 接受参数为  用户id fromid 指某一个人将会被排除在寻找范围之外
	* 返回值  一个数组集合  没有转json
	* 不建议soap使用
	*
	* */
	function findAllfriendsby_id_usearray_except($id, $fromid) {
		$ans = $this->db->users->findOne ( array (
				'_id' => new MongoId ( $id ) 
		) );
		
		$res = Array ();
		// 遍历$ans 指针
		foreach ( $ans ['relationships'] as $k => $v ) {
			
			if ($v ['userb_id'] == $fromid) {
				continue;
			}
			$res [] = $this->objarray_to_array ( $v ['userb_id'] )['$id'];
		}
		
		return $res;
	}
	
	/* made by wwq
	 * get_AllFriends_of_Myfeinds_info_by_uname_usearray指将指定用户名的好友以及二度好友好友都罗列出来
	* 接受参数为  用户uname
	* 返回值  一个数组集合  没有转json
	* 不建议soap使用
	*
	* */
	function get_AllFriends_of_Myfeinds_info_by_uname_usearray($uname) {
		$row = $this->db->users->findOne ( array (
				'user_name' => $uname 
		) );
		
		$unamerels = $row ['relationships'];
		
		$res = Array ();
		
		foreach ( $unamerels as $k => $v ) {
			$res [] = $this->objarray_to_array ( $v ['userb_id'] )['$id'];
			// echo var_dump($this->objarray_to_array($v['userb_id'])).'<br/>';
			$res = array_merge ( $res, $this->findAllfriendsby_id_usearray_except ( $v ['userb_id'], $row ['_id'] ) );
		}
		
		return array_flip ( array_flip ( $res ) );
	}
	
	/* made by wwq
	 * 按照用户名寻找到他的所有 一度  二度的好友  的用户信息
	 * 返回 所有好友的信息集合  json
	 * $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" );
	 * $result2 = $soap->get_AllFriends_info_of_Myfeinds_by_uname ( 'q' );
	 * */
	function get_AllFriends_info_of_Myfeinds_by_uname($uname) {
		$res = Array ();
		$pt = $this->get_AllFriends_of_Myfeinds_info_by_uname_usearray ( $uname );
		foreach ( $pt as $v ) {
			$res [] = json_decode ( $this->getuserby_id_xml ( $v ), true );
		}
		return json_encode ( $res );
	}
	
	/* made by wwq
	 * 按照用户名寻找到他的所有 一度  二度的好友的总数 
	* 返回 所有好友的总数
	* 
	*  $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" );
	*  $result2 = $soap->get_AllFriends_count_of_Myfeinds_by_uname ( 'q' );
	* */
	function get_AllFriends_count_of_Myfeinds_by_uname($uname) {
		return count ( $this->get_AllFriends_of_Myfeinds_info_by_uname_usearray ( $uname ) );
	}
}

/*
$a = new userclass ();
$a->reg('abc0','110','110');
$a->reg('abc1','110','110');
$a->reg('abc2','110','110');
$a->reg('abc3','110','110');
$a->reg('abc4','110','110');
$a->reg('abc5','110','110');
$a->reg('abc6','110','110');
$a->reg('abc7','110','110');

$a->makefriendwithby_uname('abc0','abc2');
$a->makefriendwithby_uname('abc1','abc2');
$a->makefriendwithby_uname('abc1','abc5');
$a->makefriendwithby_uname('abc5','abc3');
$a->makefriendwithby_uname('abc3','abc4');
$a->makefriendwithby_uname('abc5','abc6');
$a->makefriendwithby_uname('abc6','abc7');
$a->makefriendwithby_uname('abc4','abc7');
$a->makefriendwithby_uname('abc2','abc4');

$res = $a->findAllfriends_count_by_uname ( 'abc0' );
echo var_dump($res); 

*/

?>
