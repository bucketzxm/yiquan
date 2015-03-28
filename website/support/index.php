<html>
<head>
<title>用户支持</title>

<style type="text/css">
    a {
        color:#ffffff;
    }

    body * {
        font-family:"Consolas","Microsoft Yahei", Arial, sans-serif;
    }
    body {
        background: white;
        margin: 0;
        padding: 0;
    }
    input{
    	-webkit-appearance: none;
        font: 16px Arial,Tahoma,Verdana,sans-serif;
    }
    .container {
        width: 600px;
        margin: 0 auto 0px;
        opacity: 1;
        margin-top: 40px;
        text-align: center;
    }
    .container>.title {
        color: #8C8C8C;
        font-weight: bold;
        margin-bottom: 10px;
        background: #FFF;
        padding: 15px 15px;
        text-align: center;
        font-size: 25px;
        margin-top: 10px;
    }
    .contact{
    	margin-top: 20px;
    	margin-bottom: 20px;
    }
    .content{
    	margin-top: 20px;
    	margin-bottom: 20px;
    }
    .conta{
    	border: 2px solid #f0f0f0;
    	border-radius: 4px 4px 4px 4px;
    	width: 400px;
    	text-align: center;
    	height: 30px;
    }
    .conte{
    	border: 2px solid #f0f0f0;
    	border-radius: 4px 4px 4px 4px;
    	width: 400px;
    	text-align: center;
    	height: 300px;
    }
    .submit{
    	background: #4387da;
        background: -moz-linear-gradient(top, #4387da 0%, #0075d1 100%);
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#4387da), color-stop(100%,#0075d1));
        background: -webkit-linear-gradient(top, #4387da 0%,#0075d1 100%);
        background: -o-linear-gradient(top, #4387da 0%,#0075d1 100%);
        background: -ms-linear-gradient(top, #4387da 0%,#0075d1 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#4387da', endColorstr='#0075d1',GradientType=0 );
        background: linear-gradient(top, #4387da 0%,#0075d1 100%);
        border: 1px solid #418bc4;
        color: #ffffff;
        font: 16px Arial,Tahoma,Verdana,sans-serif;
        padding: 12px 10px;
        cursor: pointer;
        overflow: visible;
        border-radius: 4px 4px 4px 4px;
        float: center;
        width: 100px;
        box-shadow: 0 1px 1px #CCC;
    }
    .footer{
    	color: #666666;
    	font-size:12px;
    }

</style>

</head>
<body>
<div class="container">
		<div class="title">用户支持</div>
		<form action="submit.php" method="POST">
		<div class="contact"><input type="text" name="contact" id="contact" class="conta" placeholder="请输入可以联系到您的邮箱或者电话"></div>
		<div class="content"><input type="text" name="content" id="content" class="conte" placeholder="请输入您遇到的问题"></div>
		<input type="submit" class="submit">
		</form>
		<div class="footer">
		你也可以联系 support@oneto-tech.com 寻求支持
		<br>
		<br>
		2015 © 上海圈逸网络科技有限公司
		</div>
	</div>
</body>
</html>