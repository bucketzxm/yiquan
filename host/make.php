<?php
function access_url($url) {
	if ($url == '')
		return false;
	$file = '';
	$fp = fopen ( $url, 'r' ) or exit ( "Open $url faild!" );
	if ($fp) {
		while ( ! feof ( $fp ) ) {
			$file .= fgets ( $fp ) . "";
		}
		fclose ( $fp );
	}
	return $file;
}

$host = $_GET['host'];

if($host=='host'){
	access_url ( 'http://yiquanhost.oneto-tech.com/Topic_server.php?reb' );

	access_url ( 'http://yiquanhost.oneto-tech.com/Message_server.php?reb' );

	// access_url ( 'http://yiquanhost.duapp.com/userclass_server.php?reb' );

	access_url ( 'http://yiquanhost.oneto-tech.com/Reply_server.php?reb' );

	access_url ( 'http://yiquanhost.oneto-tech.com/User_server.php?reb' );

	access_url ( 'http://yiquanhost.oneto-tech.com/Quotemessage_server.php?reb' );

	access_url ( 'http://yiquanhost.oneto-tech.com/Quoteuser_server.php?reb' );

	access_url ( 'http://yiquanhost.oneto-tech.com/Quote_server.php?reb' );

	access_url ( 'http://yiquanhost.oneto-tech.com/testclass_server.php?reb' );

	access_url ( 'http://yiquanhost.oneto-tech.com/Label_server.php?reb' );
	    
	access_url ( 'http://yiquanhost.oneto-tech.com/Group_server.php?reb' );

	access_url ( 'http://yiquanhost.oneto-tech.com/Proseed_server.php?reb' );

	access_url ( 'http://yiquanhost.oneto-tech.com/Prouser_server.php?reb' );

	access_url ( 'http://yiquanhost.oneto-tech.com/MoSession_server.php?reb' );

	access_url ( 'http://yiquanhost.oneto-tech.com/MoStudent_server.php?reb' );

	echo 'ok';
}elseif ($host=='dev') {
	access_url ( 'http://yiquandev.oneto-tech.com/Topic_server.php?reb' );

	access_url ( 'http://yiquandev.oneto-tech.com/Message_server.php?reb' );

	// access_url ( 'http://yiquandev.duapp.com/userclass_server.php?reb' );

	access_url ( 'http://yiquandev.oneto-tech.com/Reply_server.php?reb' );

	access_url ( 'http://yiquandev.oneto-tech.com/User_server.php?reb' );

	access_url ( 'http://yiquandev.oneto-tech.com/Quotemessage_server.php?reb' );

	access_url ( 'http://yiquandev.oneto-tech.com/Quoteuser_server.php?reb' );

	access_url ( 'http://yiquandev.oneto-tech.com/Quote_server.php?reb' );

	access_url ( 'http://yiquandev.oneto-tech.com/testclass_server.php?reb' );

	access_url ( 'http://yiquandev.oneto-tech.com/Label_server.php?reb' );
	    
	access_url ( 'http://yiquandev.oneto-tech.com/Group_server.php?reb' );

	access_url ( 'http://yiquandev.oneto-tech.com/Proseed_server.php?reb' );

	access_url ( 'http://yiquandev.oneto-tech.com/Prouser_server.php?reb' );

	access_url ( 'http://yiquandev.oneto-tech.com/MoSession_server.php?reb' );

	access_url ( 'http://yiquandev.oneto-tech.com/MoStudent_server.php?reb' );

	echo 'ok';
}else{
	echo 'no input';
}


?>
