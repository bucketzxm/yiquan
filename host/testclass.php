<?php
include_once 'user_agent.php';
class testclass {
	/**
	 *
	 * @return string
	 */
	function show() {
		return 'hello world!';
	}
	function showapp() {
		return 'hello world33!';
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
?>