<?php
	//$invitationCode = 'mlq6k1';
	$guid = $_GET['guid'];
	$ch = curl_init();
	$data_in = "<?xml version=\"1.0\" encoding=\"utf-8\"?><soap:Envelope xmlns:xsl=\"http://www.w3.org/1999/XSL/Transform\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\"> <soap:Body><queryTopicByRoomIDprivate><topic_roomID>".$guid."</topic_roomID></queryTopicByRoomIDprivate></soap:Body></soap:Envelope>";
	
	$headers['User-Agent'] = 'YiQuan/0.1.0 iOS/8.1\r\n';
	//$headers['SOAPAction'] = 'urn:User#User#reg\r\n';
	$headers['Host'] = 'yiquanhost.oneto-tech.com';
	$headers['Content-Type']= 'text/xml; charset=utf-8';
	
	$headerArr = array(); 
	foreach( $headers as $n => $v ) {
    	$headerArr[] = $n .':' . $v;
	}

	curl_setopt($ch, CURLOPT_URL, "https://yiquanhost.oneto-tech.com/Topic_server.php");
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

	$pos1 = strpos($data,"<queryTopicByRoomIDprivate xsi:type=\"xsd:string\">");
	$pos2 = strpos($data,"</queryTopicByRoomIDprivate>");

	$result = substr($data, $pos1+49, $pos2-$pos1-49); 
	//result = 1时为有邀请
	//echo $result;

	$result_json = json_decode($result, true);
	//var_dump($result_json);
	?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title>话题分享</title>
	<script src="https://lib.sinaapp.com/js/jquery/2.0.3/jquery-2.0.3.min.js"></script>
	<script src="sha1.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){

            $(".container").animate({
                opacity:'1',
                marginTop: '20px'
            },500);



        });
	</script>
	<!--
		TODO: Styltsheet
	-->
	<link rel="stylesheet" type="text/css" href="topic.css">

</head>

<body>
<div class="nav"> 由 <?php echo $result_json["user_nickname"]; ?> 发送的话题 </div>
<div class="container">
	<div class="user">
		<img class="avatar" src= <?php echo "\"data:image/jpg;base64,"; echo $result_json["user_pic"]; echo "\""?>  alt ="Avatar" width="60" height="60"/>  
		<div class="profile">
			<div class="profile_name"> <?php echo $result_json["user_nickname"]; ?> </div>
			<div class="profile_location_industry"> <?php echo $result_json["user_city"]; echo " ";echo $result_json["user_industry"];?> </div>
			<div class="profile_intro"> <?php echo $result_json["user_intro"]; ?> </div>
		</div>
	</div>

	<div class="tag" ><?php echo $result_json["topic_labels"][0]; echo "  ";echo $result_json["topic_labels"][1]; ?></div>
	<div class="topic"><?php echo $result_json["topic_title"]; ?></div>

	<?php
	$invitationCode = $_GET['invitationCode'];
	$inviteName = $_GET['name'];
	//$invitationCode = 'mlq6k1';

	$ch2 = curl_init();
	$data_in2 = "<?xml version=\"1.0\" encoding=\"utf-8\"?><soap:Envelope xmlns:xsl=\"http://www.w3.org/1999/XSL/Transform\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\"> <soap:Body><checkInvitation><invcode>".$invitationCode."</invcode></checkInvitation></soap:Body></soap:Envelope>";
	
	$headers['User-Agent'] = 'YiQuan/0.1.0 iOS/8.1\r\n';
	//$headers['SOAPAction'] = 'urn:User#User#reg\r\n';
	$headers['Host'] = 'yiquanhost.oneto-tech.com';
	$headers['Content-Type']= 'text/xml; charset=utf-8';
	
	$headerArr = array(); 
	foreach( $headers as $n => $v ) {
    	$headerArr[] = $n .':' . $v;
	}
	//echo $data_in2;

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
	//echo $data2;

	$pos2 = strpos($data2,"</checkInvitation>");
	$result2 = substr($data2, $pos2-1, 1); 
	//result = 1时为有邀请
	//echo $result2;
	
	if ($result2 == '1'){
		echo "<div class=\"invite\">您被  "; echo $_GET['name']; echo"  邀请加入一圈</div>";
		
		echo "<div class=\"transportClass\"><input type=\"button\" class=\"transport\" value=\"注册\" onclick=\"window.location.href='reg.php?invitationCode=";
		echo $invitationCode;
		echo "&name=";
		echo $inviteName;
		echo "'\"/></div>";
	}else{
		echo "<div class=\"invite\">您可以向您的好友"; echo $_GET['name']; echo"索取一圈的邀请注册资格</div>";
	}

	?>
	
</div>
</body>

</html>