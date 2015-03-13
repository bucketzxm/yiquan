<?php

// wsdl方式调用web service
// wsdl方式中由于wsdl文件写定了，如果发生添加删除函数等操作改动，不会反应到wsdl，相对non-wsdl方式
// 来说不够灵活
// $soap = new SoapClient('http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/test.wsdl');
$soap = new SoapClient ( "http://yiquanhost.duapp.com/songbo/Reply.wsdl" );
// $soap = new SoapClient ( "http://localhost/userclass.wsdl" );
// non-wsdl方式调用web service
// 在non-wsdl方式中option location系必须提供的,而服务端的location是选择性的，可以不提供
// $soap = new SoapClient ( null, array (
// 'location' => "http://127.0.0.1/testclass_server.php",
// 'uri' => 'testclass_server.php'
// ) );

// 两种调用方式，直接调用方法，和用__soapCall简接调用

$result2 = $soap-> ( 'q', '{"like":890,"father":"dave","good":"moring","mother":"0022"}' );

echo $result2 . "<br/>";

$result2 = $soap->getuserbyname_xml ( 'q' );

echo $result2 . "<br/>";

?>