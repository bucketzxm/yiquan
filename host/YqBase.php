<?php
require_once 'dbobj.php';
require_once 'user_agent.php';
require_once 'bcs.class.php';
require_once 'Topic.php';
require_once 'Message.php';
require_once 'Reply.php';

/* Report all errors except E_NOTICE */
// error_reporting ( E_ALL & ~ E_NOTICE );
class YqBase {
	static $yidb;
	static $yilogdb;
	protected $db;
	protected $user = 'hGQdTvvG8oiEFe3EseT4aoLT';
	protected $pwd = 'TT1tVoot4Neo8lXRclxP0xIimqR7QnA1';
	protected $ak = 'hGQdTvvG8oiEFe3EseT4aoLT';
	protected $sk = 'TT1tVoot4Neo8lXRclxP0xIimqR7QnA1';
	// protected $user = 'test';
	// protected $pwd = 'test';
	protected $dbname = 'YiDDTYNSihVFhKGsicHU';
	// protected $dbname = 'yiquan';
	protected $yiquan_version = 0;
	protected $yiquan_platform = 'unknown';
	/*
	 * made by wwq 构造函数 疯狂连接与认证 实属无奈
	 */
	function __construct() {
		try {
			if (self::$yidb == null) {
//				self::$yidb = new Mongo("mongodb://$this->user:$this->pwd@$this->dbname");

			self::$yidb = connectDbTwo ( $this->user, $this->pwd, $this->dbname );
			}
			self::$yidb->connect ();
		} catch ( Exception $e ) {
			writeLog ( 'Exceptions', 'ex1 happened' );
			self::$yidb = connectDbTwo ( $this->user, $this->pwd, $this->dbname );
		}
		while ( 1 ) {
			try {
				$this->db = self::$yidb->selectDB ( $this->dbname );
				// if ($this->user != '' && $this->pwd != '') {
				// if (self::$needauth == 1) {
				// $fa = $this->db->authenticate ( $this->user, $this->pwd );
				// if ($fa ['ok'] == 0) {
				// sleep ( 1 );
				// continue;
				// }
				// }
				// }
				break;
			} catch ( Exception $e ) {
				writeLog ( 'Exceptions', 'ex2 happened' );
				sleep ( 1 );
				continue;
			}
		}
		if (! isset ( $_SESSION )) {
			session_start ();
		}
		$this->yiquan_version = $this->checkagent ();
	}
	
	/*
	 * made by wwq 析构函数 顺便关闭连接 mongo的无奈
	 */
	function __destruct() {
		self::$yidb->close ();
	}
	function checkagent() {
		$user_agent = $_SERVER ['HTTP_USER_AGENT'];
		// echo $user_agent;
		$ua = new CI_User_agent ( $user_agent );
		if ($ua->browser () != 'YiQuan')
			return 0;
		
		$this->yiquan_platform = $ua->platform ();
		return $ua->version ();
	}
	function checkToken() {
		return 1;
		/*
		try {
			if (isset ( $_COOKIE ['user'] ) && isset ( $_SESSION ['user'] ) && isset ( $_COOKIE ['user_token'] ) && isset ( $_SESSION ['user_token'] ) && $_COOKIE ['user'] == $_SESSION ['user'] && $_COOKIE ['user_token'] == $_SESSION ['user_token']) {
				return 1;
			} else {
				return 0;
			}
		} catch ( Exception $e ) {
			return 0;
		}*/
	}
	function logCallMethod($user_name, $classandname) {
		$month = intval ( date ( 'm' ) );
		$day = intval ( date ( 'd' ) );
		$year = intval ( date ( 'Y' ) );
		$hour = intval ( date ( 'H' ) );
		$minute = intval ( (intval ( date ( 'i' ) ) / 10) ) * 10;
		$time1 = mktime ( $hour, $minute, 0, $month, $day, $year );
		
		$p = explode ( "::", $classandname );
		$row = $this->db->callmethodlog->findOne ( array (
				'date' => new MongoDate ( $time1 ),
				'class' => $p [0],
				'user_name' => $user_name 
		) );
		
		if ($row == null) {
			$nr = array (
					'date' => new MongoDate ( $time1 ),
					'class' => $p [0],
					'user_name' => $user_name,
					'methods' => array (
							$p [1] => 1 
					) 
			);
			$this->db->callmethodlog->save ( $nr );
		} else {
			if (isset ( $row ['methods'] [$p [1]] )) {
				$row ['methods'] [$p [1]] ++;
			} else {
				$row ['methods'] [$p [1]] = 1;
			}
			$this->db->callmethodlog->save ( $row );
		}
		return 1;
	}
	function getCurrentUsername() {
		if (isset ( $_SESSION ['user'] )) {
			return $_SESSION ['user'];
		} else {
			return 'anonymous';
		}
	}
}
