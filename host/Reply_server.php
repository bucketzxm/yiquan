<?php
ini_set ( 'soap.wsdl_cache_enabled', '0' );
 error_reporting(E_ALL);
 ini_set('display_errors', '1');
// header("Cache-control:no-cache,no-store,must-revalidate");
// header("Pragma:no-cache");
// header("Expires:0"); 

$w = $_SERVER ['PHP_SELF'];
$p = explode ( "/", $w );
$q = explode ( "_", $p[count($p) - 1] );
include ($q [0] . ".php"); // 你要生成的文件
                           // 包含提供服务的类进来

include ("SoapDiscovery.class.php");
function wdo() {
	global $q;
	$disco = new SoapDiscovery ( $q [0], $q [0] ); // 第一个参数是类名（生成的wsdl文件就是以它来命名的），即person类，第二个参数是服务的名字（这个可以随便写）。
	$wsdl = $disco->getWSDL ();
	$fp = fopen ( "$q[0].wsdl", "w" );
	fwrite ( $fp, $wsdl );
	fclose ( $fp );
}

if (isset ( $_GET ['reb'] )) {
	wdo ();
} else {

	$servidorSoap = new SoapServer ( "$q[0].wsdl" );
	$servidorSoap->setClass ( $q [0] );
	$servidorSoap->handle ();
}
?>
