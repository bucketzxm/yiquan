<?php
date_default_timezone_set ( "PRC" );
function connectDb() {
	$host = 'mongo.duapp.com';
	$port = '8908';
	
	// $host = 'localhost';
	// $port = '27017';
	while ( 1 ) {
		try {
			/* 建立连接后，在进行集合操作前，需要先select使用的数据库，并进行auth */
			
			$mongoClient = new MongoClient ( "mongodb://{$host}:{$port}",array("persist" => "x"));
			
			return $mongoClient;
		} catch ( Exception $e ) {
			sleep ( 1 );
			continue;
		}
	}
}
function connectDbTwo($username, $password, $dbname) {
//	$host = 'mongo.duapp.com';
//	$port = '8908';
	
 	$host = 'localhost';
 	$port = '27017';
	while ( 1 ) {
		try {
			/* 建立连接后，在进行集合操作前，需要先select使用的数据库，并进行auth */
			
			$mongoClient = new MongoClient ( "mongodb://{$host}:{$port}", array (
					'username' => $username,
					'password' => $password,
					'db' => $dbname 
			) );
			
			return $mongoClient;
		} catch ( Exception $e ) {
			sleep ( 1 );
			continue;
		}
	}
}
function convent_from_array_to_xml_b($a) {
	$res = '';
	foreach ( $a as $key => $value ) {
		$res .= "<$key>";
		if (is_array ( $value )) {
			$res .= convent_from_array_to_xml_b ( $value );
		} else {
			$res .= $value;
		}
		
		$res .= "</$key>";
	}
	return $res;
}
function convent_from_array_to_xml($a) {
	$res = '<result>';
	$res .= convent_from_array_to_xml_b ( $a );
	$res .= '</result>';
	return $res;
}
function makeGuid() {
	if (function_exists ( 'com_create_guid' )) {
		return com_create_guid ();
	} else {
		mt_srand ( ( double ) microtime () * 10000 ); // optional for php 4.2.0 and up.
		$charid = strtoupper ( md5 ( uniqid ( rand (), true ) ) );
		$hyphen = chr ( 45 ); // "-"
		$uuid = chr ( 123 ) . 		// "{"
		substr ( $charid, 0, 8 ) . $hyphen . substr ( $charid, 8, 4 ) . $hyphen . substr ( $charid, 12, 4 ) . $hyphen . substr ( $charid, 16, 4 ) . $hyphen . substr ( $charid, 20, 12 ) . chr ( 125 ); // "}"
		return $uuid;
	}
}
function writeLog($filename, $content) {
	$t = time ();
	$fname = $filename . '.log';
	$fp = fopen ( $fname, 'a+' );
	fwrite ( $fp, $content . ' ' . date ( 'Y-m-d h:i:s', time () ) . "\r\n" );
	fclose ( $fp );
}
function arrcmp1($a, $b) {
	return strcmp ( $a ["user_name"], $b ["user_name"] );
}

/*
 * @length   邀请码的长度
* @num      邀请码的数量
*/
function yqinvcode($length, $num = 1) {
	$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
	$j = 0;
	$code_arr = array ();
	while ( $j < $num ) {
		$random = '';
		for($i = 0; $i < $length; $i ++) {
			$random .= $chars [mt_rand ( 0, strlen ( $chars ) - 1 )];
		}
		$code_arr [$j] = $random;
		$j ++;
	}
	return $code_arr;
}
?>
