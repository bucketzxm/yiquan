<?php
session_start ();
// error_reporting ( E_ALL );
// ini_set ( 'display_errors', '1' );
// wsdl方式调用web service
// wsdl方式中由于wsdl文件写定了，如果发生添加删除函数等操作改动，不会反应到wsdl，相对non-wsdl方式
// 来说不够灵活
// $soap = new SoapClient('http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/test.wsdl');
// $soap = new SoapClient ( "http://yiquanhost.duapp.com/userclass.wsdl" );
$soap = new SoapClient ( "http://yiquanhost.oneto-tech.com/User.wsdl", array (
		'user_agent' => 'YiQuan/0.1.0 Android/4.0.3' 
) );
//var_dump ( $soap );
$soap->loginByUser ( 'abc33', '111' );
$file = 'a.jpg';
$st = base64_encode ( file_get_contents ( $file ) );
echo $st;

$res = $soap->updateUserpicByUsername ( $st, 'abc33' );
// $res = $soap->reg('abc33','111','111');
var_dump ( $res );
echo $res;

// $soap = new SoapClient ( "http://yiquanhost.duapp.com/Topic.wsdl", array (
// 'user_agent' => 'YiQuan/0.1.0 Android/4.0.3'
// ) );

// $res = $soap->queryTopicByName ( 'arition', 'second', 0, 0 );
// echo $res;

// $soap = new SoapClient ( "http://yiquanhost.duapp.com/Reply.wsdl", array (
// 'user_agent' => 'YiQuan/0.1.0 Android/4.0.3'
// ) );

// $res = $soap->queryAllReplyByTopic ( '54de228aa3136adb0700006c', 'arition', 0 );
// echo $res;
// //setcookie("aaa",'ccccccaa');
// //echo $_COOKIE["aaa"];
// var_dump( $res);
// var_dump ( $soap->_cookies );

// echo '<br/>';
// //header ( "Content-Type: image/jpg" );
// var_dump ($soap->_cookies['user'][0]) ;

// session_start();
// var_dump($_COOKIE);

/*
$soap->reg ( 'abc0', '110', '110' );
$soap->reg ( 'abc1', '110', '110' );
$soap->reg ( 'abc2', '112', '110' );
$soap->reg ( 'abc3', '110', '110' );
$soap->reg ( 'abc4', '110', '110' );
$soap->reg ( 'abc5', '110', '110' );
$soap->reg ( 'abc6', '110', '110' );
$soap->reg ( 'abc7', '110', '110' );

$soap->addProfileByName ( 'abc0', '{"profile_city":"shanghai"}' );
$soap->addProfileByName ( 'abc1', '{"profile_city":"shanghai"}' );
$soap->addProfileByName ( 'abc2', '{"profile_city":"shanghai"}' );
$soap->addProfileByName ( 'abc3', '{"profile_city":"shanghai"}' );
$soap->addProfileByName ( 'abc4', '{"profile_city":"shanghai"}' );
$soap->addProfileByName ( 'abc5', '{"profile_city":"shanghai"}' );
$soap->addProfileByName ( 'abc6', '{"profile_city":"shanghai"}' );
$soap->addProfileByName ( 'abc7', '{"profile_city":"山东"}' );



$soap->addFriendByName ( 'abc0', 'abc2' );
$soap->addFriendByName ( 'abc1', 'abc2' );
$soap->addFriendByName ( 'abc1', 'abc5' );
$soap->addFriendByName ( 'abc5', 'abc3' );
$soap->addFriendByName ( 'abc3', 'abc4' );
$soap->addFriendByName ( 'abc5', 'abc6' );
$soap->addFriendByName ( 'abc6', 'abc7' );
$soap->addFriendByName ( 'abc4', 'abc7' );
$soap->addFriendByName ( 'abc4', 'abc1' );
$soap->addFriendByName ( 'abc2', 'abc4' );
$soap->addFriendByName ( 'abc2', 'abc5' );
$soap->addFriendByName ( 'abc0', 'abc2' );
// $soap->reg ( 'abc0', '1233322', '13564995785' );
*/
// $data = base64_encode ( file_get_contents ( realpath ( "a.jpg" ) ) );

// $soap->update_userpic_by_uname ( $data, 'abc0' );
// echo $res;
// header ( "Content-Type: image/jpg" );
// echo $soap->get_commonfriendsof_aandb_by_uname ( 'queenczh', 'lonblues' );
// $soap = new SoapClient ( "http://localhost/userclass.wsdl" );
// non-wsdl方式调用web service
// 在non-wsdl方式中option location系必须提供的,而服务端的location是选择性的，可以不提供
// $soap = new SoapClient ( null, array (
// 'location' => "http://127.0.0.1/testclass_server.php",
// 'uri' => 'testclass_server.php'
// ) );

// 两种调用方式，直接调用方法，和用__soapCall简接调用

?>
