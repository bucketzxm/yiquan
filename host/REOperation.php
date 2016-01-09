<?php
require_once 'YqBase.php';
require_once 'PHPMailer/PHPMailerAutoload.php';

function load_file($url) {
    $ch = curl_init($url);
    #Return http response in string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $xml = simplexml_load_string(curl_exec($ch));
    return $xml;
}


function setMails ($number){

	$mail = new PHPMailer();

    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host = 'hwsmtp.exmail.qq.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'material@youthimpactchina.com';
    $mail->Password = 'Yis2016';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $mail->SetFrom('material@youthimpactchina.com', 'Youth Impact China');
    $mail->addAddress('zhu.lun@youthimpactchina.com', 'Zhu Lun');
    $mail->isHTML(true);
    $mail->addAttachment('a.jpg','测试附件图片');

    $mail->Subject =
        "自动测试邮件发送：" .$number;
    $mail->Body =
		'<h3>邮件内容'.$number.'</h3>'.
		'<ul>'.
		'<li>One</li>'.
		'<li>Two</li>'.
		'<li>Three</li>'.
		'</ul>';
    if(!$mail->send()) {
        header("Location: /");
    } else {
        echo '<h4>您的邮件'.$number.'已经发送成功</h4>';
    }

}


ini_set("max_execution_time", 2400);

$dbname = 'yiquan';
$host = 'localhost';
$port = '27017';
$user = 'test';
$pwd = 'yiquanTodo';

$mongoClient = new MongoClient("mongodb://{$host}:{$port}",array(
    'username'=>$user,
    'password'=>$pwd,
    'db'=>$dbname
));
$db = $mongoClient->yiquan;

for ($i=0; $i < 2; $i++) { 
	setMails($i);
}





?>