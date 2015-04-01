<html>
	<body>
		<?php

        $username= $_POST['username'];
        $password= $_POST['password'];
        $title= $_POST['title'];
        $tag= $_POST['tag'];
    	$editor= $_POST['editor'];
    	$result = '<html xmlns=http://www.w3.org/1999/xhtml><head><meta http-equiv=Content-Type content="text/html;charset=utf-8"><link href="http://7xid8v.com2.z0.glb.qiniucdn.com/style.css" rel="stylesheet"></head><body>'.$editor.'</body></html>';

        $ch = curl_init();
        $data_in = '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"> <soap:Body><addRichTopic><username>'.$username.'</username><paswordHash>'.$password.'</passwordHash><topic>'.$title.'</topic><topic_labels>'.$tag.'</topic_labels><topic_networks>second</topic_networks><html>'.$result.'</html></addRichTopic></soap:Body></soap:Envelope>';
        $headers['Host'] = 'yiquanhost.oneto-tech.com';
        $headers['Content-Type']= 'text/xml; charset=utf-8';
        
        $headerArr = array(); 
        foreach( $headers as $n => $v ) {
            $headerArr[] = $n .':' . $v;
        }

        curl_setopt($ch, CURLOPT_URL, "http://yiquanhost.oneto-tech.com/Topic_server.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_in);
        curl_setopt ($ch, CURLOPT_HTTPHEADER , $headerArr);
        $data = curl_exec($ch);
        curl_close($ch);

		?>
	</body>
</html>