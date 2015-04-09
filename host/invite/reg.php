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
	
	if ($result != '1'){
		header("location: error.html");
	}

?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title>邀请注册</title>
	<script src="https://lib.sinaapp.com/js/jquery/2.0.3/jquery-2.0.3.min.js"></script>
	<script src="sha1.min.js"></script>
	<!--
		TODO: Styltsheet
	-->
	<link rel="stylesheet" type="text/css" href="reg_style.css">

	<script type="text/javascript">

		function timer(time) {
    		var btn = $("#verifySend");

    		//var btn = document.getElementById("verifySend");
    		btn.attr("disabled", true);  //按钮禁止点击
    		if (time>0){
    			btn.val("" + (time));
    		}
    		
    		var hander = setInterval(function() {
        		if (time <= 0) {
            		clearInterval(hander); //清除倒计时
            		btn.val("发送验证码");
            		btn.attr("disabled", false);
            		return false;
        		}else {
            		btn.val("" + (time--));
        		}
    		}, 1000);
		}

		function SendSMS(){
			var phoneNumber = document.getElementById("phoneNumber").value;
			if (phoneNumber.length == 11){
				var frame = document.createElement("frame");
				frame.src = "phone.php"+"?phoneNumber="+phoneNumber;
    			document.body.appendChild(frame);
    			//alert("验证码已发送");
    			$(document).ready(function(){
    			$(".container2").css('visibility','visible'); 
    			$(".veriCode").css('visibility','visible');
            	$(".container2").animate({
            	    opacity:'1',
            	},500);});
            	timer(30);
			}else{
				alert("手机号格式不正确，应为11位");
			}
			
		}

		function SendReg(){
			//Check Name and Password
			var msg = "对不起，输入错误\n";
        	var regform = document.all.sendForm;
        	var username = regform.reg_user;
        	var password = regform.reg_pass;
        	var usern = /^[a-z0-9]{1,}$/;
        	var passn = /^[a-zA-Z0-9]{1,}$/;
        	if (!username.value.match(usern)) {
                msg += "用户名只能由字母数字组成\n";
                alert(msg);
                username.value = '';
                username.focus();
                return false;
        	}
        	if (!password.value.match(passn)) {
                msg += "密码只能由字母数字组成\n";
                alert(msg);
                username.value = '';
                username.focus();
                return false;
        	}

			//Check Name and Password Length
			var usrval = document.getElementById("reg_user").value;
			var pasval = document.getElementById("reg_pass").value;
			if (usrval.length<4 || usrval.length >20){
				msg += "用户名在4-20字之间";
				alert(msg);
				return false;
			}

			if (pasval.length<6 || pasval.length>20){
				msg += "密码在6-20字之间";
				alert(msg);
				return false;
			}

			//Hash Password
			var password = document.getElementById("reg_pass").value;
			password = sha1(sha1(sha1(sha1(password+"yiquan")+"yidaquan")));
			document.getElementById("reg_pass").value=password;
			//alert(password);
			//return false;
			return true;
		}

		$(document).ready(function(){

            $(".container").animate({
                opacity:'1',
                marginTop: '20px'
            },500);



        });

        $(function() {  
    		$("form[name='contractForm'] input").keypress(function(e) {  
    		if (e.which == 13) {// 判断所按是否回车键  
        		var inputs = $("form[name='contractForm']").find("input"); // 获取表单中的所有输入框 
        		//inputs.push($form[name='contractForm'].find(":password")); 
        		var idx = inputs.index(this); // 获取当前焦点输入框所处的位置  
        		//alert (idx);
        		if (idx == inputs.length - 1) {// 判断是否是最后一个输入框  
            		//if (confirm("最后一个输入框已经输入,是否提交?")) // 用户确认  
            		//$("form[name='contractForm']").submit(); // 提交表单  
        		} else {  
            		inputs[idx + 1].focus(); // 设置焦点  
            		inputs[idx + 1].select(); // 选中文字  
            		if (idx == 1){
            			inputs[idx + 2].focus();
            			inputs[idx + 2].select();
            		}
        		}  
        	return false;// 取消默认的提交行为  
    		}  
    		});  
		}); 

	</script>

</head>

<body>
<div class="nav"> 欢迎加入我们新的聚集地 </div>
<div class="container">
	<form action="reg_finish.php" method="POST" id="sendForm" name="contractForm" onsubmit="return SendReg();">
		<div class="invite">您被 <?php echo $_GET['name']?> 邀请加入一圈</div>
		<div class="sendline">
		<input type="text" class="phoneNumber" id="phoneNumber" name="phoneNumber" autocomplete="off" placeholder="您的手机号">
		</div>
		<br>
		<div class="sendline2">
		<input type="text" class="veriCode" name="veriCode" id="veriCode" autocomplete="off" placeholder="输入验证码">
		<input onClick="SendSMS()" type="button" class="veriSend" name="verifySend" id="verifySend" tabIndex="1000" value="发送验证码">
		</div>

		
		<div class="container2">
		<div class="sendline3">
		<input type="text" class="reg_user" name="reg_user" id="reg_user" autocomplete="off" placeholder="您的圈号（即用户名）">
		</div>
		<div class="sendline4">
		<input type="password" class="reg_pass" name="reg_pass" id="reg_pass" placeholder="您的密码">
		</div>
		<div class="sendline5">
		<input class= "submit" type="submit" value="注册">
		<div class="invitecode" ><input class="invCode" type="text" name="invCode" id="invCode" value=<?php echo "\"".$_GET['invitationCode']."\""?>></div>
		</div>
		<div class="footer">本邀请将于注册成功后失效。</div>
		</div>
	</form>
</div>
</body>

</html>