<?php
	$invCode = $_POST['invCode'];
	$phoneNumber = $_POST['phoneNumber'];
	$veriCode = $_POST['veriCode'];
	$reg_user = $_POST['reg_user'];
	$reg_pass = $_POST['reg_pass'];

	//$invitationCode = 'mlq6k1';

	$ch = curl_init();
	$data_in = "<?xml version=\"1.0\" encoding=\"utf-8\"?><soap:Envelope xmlns:xsl=\"http://www.w3.org/1999/XSL/Transform\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\"> <soap:Body><checkRegisterCode><mobilenumber>".$phoneNumber."</mobilenumber><code>".$veriCode."</code></checkRegisterCode></soap:Body></soap:Envelope>";
	
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

	$pos = strpos($data,"</checkRegisterCode>");
	$result = substr($data, $pos-1, 1); 
	//result = 1时为有邀请
	//echo $result;
	if ($result == '2'){
		header("location: expire.html");
	}
	if ($result == '1'){
		$ch2 = curl_init();
		$data_in2 = "<?xml version=\"1.0\" encoding=\"utf-8\"?><soap:Envelope xmlns:xsl=\"http://www.w3.org/1999/XSL/Transform\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\"> <soap:Body><regByInvitation><user_name>".$reg_user."</user_name><user_pwd>".$reg_pass."</user_pwd><user_mobile>".$phoneNumber."</user_mobile><invcode>".$invCode."</invcode></regByInvitation></soap:Body></soap:Envelope>";
	
		curl_setopt($ch2, CURLOPT_URL, "https://yiquanhost.oneto-tech.com/User_server.php");
		//curl_setopt($curl, CURLOPT_USERAGENT, 'YiQuan/0.1.0 iOS/8.1'); 
		curl_setopt($ch2, CURLOPT_POST, 1);
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 300);
		curl_setopt($ch2, CURLOPT_POSTFIELDS, $data_in2);
		curl_setopt ($ch2, CURLOPT_HTTPHEADER , $headerArr);
		$data2 = curl_exec($ch2);
		curl_close($ch2);

		$data2 = (string) $data2;
		$pos = strpos($data2,"</regByInvitation>");
		$result2 = substr($data2, $pos-1, 1); 
		//echo $result2;
		if ($result2 == '1'){
			header("location: jump.html");
		}else{
			header("location: sameName.html");
		}
	}else{
		header("location: wrongVeri.html");
	}

?>