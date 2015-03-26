<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="refresh" content="3;url=index.html"> 
<title>御坂网络树洞</title>
<script src="http://libs.baidu.com/jquery/2.0.3/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="status_style.css">
</head>
<body>

<?php
    $email = $_POST['email'];
    $fp=fopen("mail.txt","a+");
          fwrite($fp,$email);
          fwrite($fp,"\r\n");
          fclose($fp);
?>


<!-- HTML -->
<div class="container">
  <div class="title">一圈<br></div>
  <div class="notify">我们已收到您的申请，我们会尽快联系您<br></div>
  <div class="jump">3秒后跳转<br></div>
</div>
<!-- Javascript -->
    <script type="text/javascript">
        $(document).ready(function(){

            $(".container").animate({
                opacity:'1',
                marginTop: '300px'
            },1000);

        });
    </script>


</body>
</html>