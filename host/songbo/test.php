<?php
include 'dbobj.php';
	//show error msg in the browser
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	//ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
	
	$host = 'localhost';
	$port = '27017';

    //create a connection
    $con = new MongoClient($host.":".$port);
    
    //select a database
    $db = $con->onetofriend;
    $collection = $db->room;
    
    $document = array(
    		'title' => 'ddd',
    		'description' => 'im a descript',
    		'likes' => 100,
    	);
    
    try{
    	$cursor = $collection ->find(array('title' => 'dddd'),array('likes' => 1));
    	var_dump(explode(',', "string"));
    	echo '<br/>';
    	
    }catch (Exception $e){
    	var_dump($e);
    }
    $con->close();
    echo 'oj';

?>
