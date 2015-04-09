<?php
require_once 'dbobj.php';
require_once 'user_agent.php';
require_once 'bcs.class.php';
require_once 'Topic.php';
require_once 'Message.php';
require_once 'Reply.php';
require_once 'YqSystemMessage.php';
require_once 'Qiniu/Http/Request.php';
require_once 'Qiniu/Http/Client.php';
require_once 'Qiniu/Http/Error.php';
require_once 'Qiniu/Http/Response.php';
require_once 'Qiniu/Processing/Operation.php';
require_once 'Qiniu/Processing/PersistentFop.php';
require_once 'Qiniu/Storage/BucketManager.php';
require_once 'Qiniu/Storage/FormUploader.php';
require_once 'Qiniu/Storage/ResumeUploader.php';
require_once 'Qiniu/Storage/UploadManager.php';
require_once 'Qiniu/Auth.php';
require_once 'Qiniu/Config.php';
require_once 'Qiniu/Etag.php';
require_once 'Qiniu/functions.php';

/* Report all errors except E_NOTICE */
// error_reporting ( E_ALL & ~ E_NOTICE );
class YqBase {
	static $yidb;
	static $yilogdb;
	protected $db;
	// protected $user = 'hGQdTvvG8oiEFe3EseT4aoLT';
	// protected $pwd = 'TT1tVoot4Neo8lXRclxP0xIimqR7QnA1';
	protected $ak = 'hGQdTvvG8oiEFe3EseT4aoLT';
	protected $sk = 'TT1tVoot4Neo8lXRclxP0xIimqR7QnA1';
	protected $user = 'test';
	protected $pwd = 'yiquanTodo';
	// protected $dbname = 'YiDDTYNSihVFhKGsicHU';
	protected $dbname = 'yiquan';
	protected $yiquan_version = 0;
	protected $yiquan_platform = 'unknown';
	protected $qiniuAK = 'brOfo9rKPPpkaDy9JCyTqNwRWR8wDsgwTrEezgHz';
	protected $qiniuSK = 'Tb41FAE5cPiZI_hNIxhh8auO1g_Pfd693Tk6yGQL';
	protected $userpicbucketUrl = 'dn-yiquanhost-avatar.qbox.me';
	protected $topicsbucketUrl = 'dn-yiquantopics.qbox.me';
	/*
	 * made by wwq 构造函数 疯狂连接与认证 实属无奈
	 */
	function __construct() {
		while ( 1 ) {
			try {
				if (self::$yidb == null) {
					// self::$yidb = new Mongo("mongodb://$this->user:$this->pwd@$this->dbname");
					
					self::$yidb = connectDbTwo ( $this->user, $this->pwd, $this->dbname );
				}
				self::$yidb->connect ();
				break;
			} catch ( Exception $e ) {
				writeLog ( 'Exceptions', 'ex1 happened' );
			}
			sleep ( 1 );
			self::$yidb = connectDbTwo ( $this->user, $this->pwd, $this->dbname );
		}
		while ( 1 ) {
			try {
				$this->db = self::$yidb->selectDB ( $this->dbname );
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
		// self::$yidb->close ();
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
		// return 1;
		try {
			if (isset ( $_COOKIE ['user'] ) && isset ( $_COOKIE ['user_token'] )) { // && $_COOKIE ['user'] == $_SESSION ['user'] // && isset ( $_SESSION ['user'] )
				
				$rdt = $this->getRedis ( $_COOKIE ['user'] );
				
				if ($rdt == false) {
					return 0;
				} else {
					if ($rdt == $_COOKIE ['user_token']) {
						return 1;
					} else {
						return 0;
					}
				}
			} else {
				return 0;
			}
		} catch ( Exception $e ) {
			return 0;
		}
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
	function setRedis($key, $value) {
		$redis = new redis ();
		$redis->connect ( '127.0.0.1', 6379 );
		$result = $redis->set ( $key, $value );
		return $result;
	}
	function getRedis($key) {
		$redis = new redis ();
		$redis->connect ( '127.0.0.1', 6379 );
		$result = $redis->get ( $key );
		return $result;
	}
	function delRedis($key) {
		$redis = new redis ();
		$redis->connect ( '127.0.0.1', 6379 );
		$result = $redis->delete ( $key );
		return $result;
	}
	function checkUsernameLegal($name) {
		$this->writeTofile ( 'debug.dat', $name );
		$rname = strtolower ( trim ( $name ) );
		var_dump ( $rname );
		if ($rname == 'sencetivelist')
			return 0;
			// $this->delRedis ( 'sencetiveList' );
			// die();
		$arr = unserialize ( $this->getRedis ( 'sencetivelist' ) );
		// var_dump($arr);
		if ($arr == false || empty ( $arr )) {
			$arr = [ ];
			$file = 'sensetive.txt';
			$handle = fopen ( $file, 'r' );
			if ($handle) {
				while ( ! feof ( $handle ) ) {
					$buffer = fgets ( $handle, 4096 );
					// echo $buffer;
					if (strtolower ( trim ( $buffer ) ) != '')
						$arr [strtolower ( trim ( $buffer ) )] = 1;
				}
				fclose ( $handle );
				// var_dump ( $arr );
			}
			$this->setRedis ( 'sencetivelist', serialize ( $arr ) );
		}
		if (isset ( $arr [$rname] )) {
			return 0;
		}
		
		foreach ( $arr as $key => $value ) {
			if (strstr ( $rname, $key )) {
				// echo $rname, ' ', $key . '<br/>';
				return 0;
			}
		}
		
		return 1;
	}
	function checkUsernameAndPassword($uname, $pwd) {
		$row = $this->db->user->findOne ( array (
				'user_name' => $uname 
		) );
		
		if ($row == null)
			return 0;
		
		if ($row ['user_pin'] == crypt ( $pwd, $row ['user_pin'] ))
			return 1; // right pwd
		
		return 0;
	}
	function writeTofile($filename, $data) {
		$handle = fopen ( $filename, "a" );
		fwrite ( $handle, date ( 'Y-m-d H:i:s' ) . '  ' . $data . "\n" );
		fclose ( $handle );
	}
}

$a = new YqBase ();
$a->checkUsernameLegal ( 'aaa' );
?>
