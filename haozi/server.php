<?php
    ini_set('soap.wsdl_cache_enabled','0');
  //包含提供服务的类进来
    include("class.php"); //你要生成的文件
    include("SoapDiscovery.class.php");
    function wdo()
    {
    $disco = new SoapDiscovery('test','test');//第一个参数是类名（生成的wsdl文件就是以它来命名的），即person类，第二个参数是服务的名字（这个可以随便写）。
    $wsdl = $disco->getWSDL();
    //$disco->getDiscovery();
    $fp = fopen("test.wsdl", "w");
    fwrite($fp, $wsdl);
    }
    wdo();
	sleep(1);
 // Enciende el servidor o despliega WSDL
	//$servidorSoap = new SoapServer(null,array("uri"=>"server.php"));
	$servidorSoap = new SoapServer("test.wsdl");
	$servidorSoap->setClass('test');
	$servidorSoap->handle();


?>