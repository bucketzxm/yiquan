<?php
include_once 'user_agent.php';
include_once 'YqBase.php';
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
class testclass extends YqBase {
	/**
	 *
	 * @return string
	 */
	function show() {
		$str = "Hello";
		echo md5 ( $str );
	}
	function showapp() {
		$auth = new Auth ( $this->qiniuAK, $this->qiniuSK );
		$bucket = 'yiquanhost-avatar';
		$token = $auth->uploadToken ( $bucket );
		// var_dump ( $token );
		$uploadMgr = new UploadManager ();
		$bucketMgr = new BucketManager ( $auth );
		list ( $ret, $err ) = $bucketMgr->stat ( $bucket, md5 ( 'bk2.jpg' ) );
		if ($err !== null) {
		} else {
			echo 'del!!';
			list ( $ret, $err ) = $bucketMgr->delete ( $bucket, md5 ( 'bk2.jpg' ) );
			echo "\n====> delete result: \n";
			if ($err !== null) {
				var_dump($err);
			} else {
				echo "Success!";
			}
		}
		$data = file_get_contents ( 'b.jpg' );
		list ( $ret, $err ) = $uploadMgr->put ( $token, md5 ( 'bk2.jpg' ), $data );
		echo "\n====> put result: \n";
		if ($err !== null) {
			var_dump ( $err );
		} else {
			var_dump ( $ret );
		}
	}
	function showapp333($a) {
		return 'hello world33!' . " $a";
	}
	function showjson() {
		$obj = new stdClass ();
		$obj->body = 'another post';
		$obj->id = 21;
		$obj->approved = true;
		$obj->favorite_count = 1;
		$obj->status = NULL;
		
		$arr = array ();
		array_push ( $arr, $obj );
		array_push ( $arr, $obj );
		
		return json_encode ( $arr );
	}
	function showurl() {
		return 'http://www.baidu.com';
	}
	function cookieSet() {
		session_start ();
		setcookie ( "user", "wwqk3333", time () + 3600 );
		return 1;
	}
	function showmycookie() {
		return json_encode ( $_COOKIE );
	}
	function showmyagent() {
		$user_agent = $_SERVER ['HTTP_USER_AGENT'];
		$ua = new CI_User_agent ( $user_agent );
		return '软件名：' . $ua->platform () . '系统：' . $ua->browser () . '软件版本：' . $ua->version () . '系统版本：' . $ua->platform_version ();
	}
}
// $a = new testclass ();
// $a->showapp ();



// $redis = new redis();  
// $redis->connect('127.0.0.1', 6379);  
// $result = $redis->set('test','wwwaadd');
// var_dump($result);   //结果：string(11) "11111111111"
// $result = $redis->get('test');  
// var_dump($result);   //结果：string(11) "11111111111"  

?>