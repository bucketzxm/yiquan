<?php

// wsdl��ʽ����web service
// wsdl��ʽ������wsdl�ļ�д���ˣ�����������ɾ�������Ȳ����Ķ������ᷴӦ��wsdl�����non-wsdl��ʽ
// ��˵�������
// $soap = new SoapClient('http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/test.wsdl');
$soap = new SoapClient ( "http://yiquanhost.duapp.com/songbo/Reply.wsdl" );
// $soap = new SoapClient ( "http://localhost/userclass.wsdl" );
// non-wsdl��ʽ����web service
// ��non-wsdl��ʽ��option locationϵ�����ṩ��,������˵�location��ѡ���Եģ����Բ��ṩ
// $soap = new SoapClient ( null, array (
// 'location' => "http://127.0.0.1/testclass_server.php",
// 'uri' => 'testclass_server.php'
// ) );

// ���ֵ��÷�ʽ��ֱ�ӵ��÷���������__soapCall��ӵ���

$result2 = $soap-> ( 'q', '{"like":890,"father":"dave","good":"moring","mother":"0022"}' );

echo $result2 . "<br/>";

$result2 = $soap->getuserbyname_xml ( 'q' );

echo $result2 . "<br/>";

?>