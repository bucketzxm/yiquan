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

access_url ( 'http://yiquanhost.duapp.com/Topic_server.php?reb' );

access_url ( 'http://yiquanhost.duapp.com/Message_server.php?reb' );

// access_url ( 'http://yiquanhost.duapp.com/userclass_server.php?reb' );

access_url ( 'http://yiquanhost.duapp.com/Reply_server.php?reb' );

access_url ( 'http://yiquanhost.duapp.com/User_server.php?reb' );

access_url ( 'http://yiquanhost.duapp.com/testclass_server.php?reb' );

echo 'ok';
?>
