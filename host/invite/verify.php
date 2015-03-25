<?php
	$invitationCode = $_GET['invitationCode'];
	$inviteName = $_GET['name'];
	//$invitationCode = 'mlq6k1';

	$ch = curl_init();
	$data_in = "<?xml version=\"1.0\" encoding=\"utf-8\"?><soap:Envelope xmlns:xsl=\"http://www.w3.org/1999/XSL/Transform\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\"> <soap:Body><checkInvitation><invcode>".$invitationCode."</invcode></checkInvitation></soap:Body></soap:Envelope>";
	
	$headers['User-Agent'] = 'YiQuan/0.1.0 iOS/8.1\r\n';
	//$headers['SOAPAction'] = 'urn:User#User#reg\r\n';
	$headers['Host'] = 'yiquanhost.oneto-tech.com';
	$headers['Content-Type']= 'text/xml; charset=utf-8';
	
	$headerArr = array(); 
	foreach( $headers as $n => $v ) {
    	$headerArr[] = $n .':' . $v;
	}

	curl_setopt($ch, CURLOPT_URL, "https://yiquanhost.oneto-tech.com/User_server.php");
	//curl_setopt($curl, CURLOPT_USERAGENT, 'YiQuan/0.1.0 iOS/8.1'); 
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_in);
	curl_setopt ($ch, CURLOPT_HTTPHEADER , $headerArr);
	$data = curl_exec($ch);
	curl_close($ch);

	$data = (string) $data;
	//echo $data;

	$pos = strpos($data,"</checkInvitation>");
	$result = substr($data, $pos-1, 1); 
	//result = 1时为有邀请
	//echo $result;
	
	if ($result == '1'){
		header("location: reg.php?invitationCode=".$invitationCode."&name=".$inviteName);
	}else{
		header("location: error.html");
	}

?>