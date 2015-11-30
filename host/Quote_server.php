<?php
ini_set ( 'soap.wsdl_cache_enabled', '0' );
$w = $_SERVER ['PHP_SELF'];
$p = explode ( "/", $w );
$q = explode ( "_", $p [1] );
include ($q [0] . ".php"); // 你要生成的文件
                           // 包含提供服务的类进来
include ("SoapDiscovery.class.php");
function wdo() {
	global $q;
	$disco = new SoapDiscovery ( $q [0], $q [0] ); // 第一个参数是类名（生成的wsdl文件就是以它来命名的），即person类，第二个参数是服务的名字（这个可以随便写）。
	$wsdl = $disco->getWSDL ();
	// $disco->getDiscovery();
	$fp = fopen ( "$q[0].wsdl", "w" );
	fwrite ( $fp, $wsdl );
	fclose ( $fp );
}

if (isset ( $_GET ['reb'] )) {
	wdo ();
} else {
	// sleep ( 1 );
	// Enciende el servidor o despliega WSDL
	// $servidorSoap = new SoapServer(null,array("uri"=>"server.php"));
	//$servidorSoap = new SoapServer ( "$q[0].wsdl" );
	
	 $servidorSoap = new SoapServer ( null, array (
	 "location" => "http://127.0.0.1/$p[1].php",
	 "uri" => "$p[1].php"
	 ) );
	$servidorSoap->setClass ( $q [0] );
	$servidorSoap->handle ();
}
?>
