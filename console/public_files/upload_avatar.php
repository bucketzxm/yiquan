<?php
// include shared code
include '../lib/common.php';
include '../lib/db.php';
include '../lib/functions.php';
include '../lib/User.php';
include '../lib/JpegThumbnail.php';

include '401.php';

// start or continue session
session_start();

$user=User::getById($_SESSION['userId']);


//print($_FILES['avatar']['name']);
if($user)
{
	if(!$_FILES['avatar']['error'])
	{
        //print($_FILES['avatar']['tmp_name']);
        //$img=new JpegThumbnail();
        //$img->generate($_FILES['avatar']['tmp_name'],'avatars/'.$user->username.'.jpg');
        $name='avatars/'.$user->username.'.jpg';
        $s2 = new SaeStorage();
		$img = new SaeImage();
		$img_data = file_get_contents($_FILES['avatar']['tmp_name']);//获取本地上传的图片数据
		$img->setData($img_data);
		$img->resize(50,50); //图片缩放为200*310
		$img->improve();//提高图片质量的函数
		$new_data = $img->exec(); // 执行处理并返回处理后的二进制数据
		$s2->write('smallform',$name,$new_data);//将xxx修改为自己的storage 名称
        
	}
    
}

$page="myinfo.php";
echo "<script>alert('success!'); window.location = \"".$page."\";</script>";


?>