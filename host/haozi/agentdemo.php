<?php include('user_agent.php');
 
$user_agent = 'YiQuan/0.0.0.1 (Android/4.0.3)';
$ua = new CI_User_agent($user_agent);
echo $user_agent . '<br>'; 
echo '软件名：'.$ua->platform() . '<br>';
echo '系统：'.$ua->browser() . '<br>';
echo '软件版本：'.$ua->version() . '<br>';
echo '系统版本：'.$ua->platform_version() . '<br>';
echo $ua->robot() . '<br>';
echo $ua->mobile() . '<br>';

?>