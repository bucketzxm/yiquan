<?php
	$phoneNumber = $_GET['phoneNumber'];
	//$invitationCode = 'mlq6k1';
	
	$ch = curl_init();
	$data_in = "<?xml version=\"1.0\" encoding=\"utf-8\"?><soap:Envelope xmlns:xsl=\"http://www.w3.org/1999/XSL/Transform\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\"> <soap:Body><getRegisterCode><mobilenumber>".$phoneNumber."</mobilenumber><expireMinute>20</expireMinute></getRegisterCode></soap:Body></soap:Envelope>";
	
	$headers['User-Agent'] = 'YiQuan/0.1.0 iOS/8.1\r\n';
	//$headers['SOAPAction'] = 'urn:User#User#reg\r\n';
	$headers['Host'] = 'yiquanhost.oneto-tech.com';
	$headers['Content-Type']= 'text/xml; charset=utf-8';
	
	$headerArr = array(); 
	foreach( $headers as $n => $v ) {
    	$headerArr[] = $n .':' . $v;
	}

	curl_setopt($ch, CURLOPT_URL, "http://yiquanhost.oneto-tech.com/User_server.php");
	//curl_setopt($curl, CURLOPT_USERAGENT, 'YiQuan/0.1.0 iOS/8.1'); 
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_in);
	curl_setopt ($ch, CURLOPT_HTTPHEADER , $headerArr);
	$data = curl_exec($ch);
	curl_close($ch);
	
?>