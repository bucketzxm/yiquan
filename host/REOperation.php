<?php
require_once 'YqBase.php';

function load_file($url) {
    $ch = curl_init($url);
    #Return http response in string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $xml = simplexml_load_string(curl_exec($ch));
    return $xml;
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

$accountCursor = $db->REContact->find();
foreach ($accountCursor as $key => $value) {
    
    echo '<h4>'.$value['account_id'].';'.$value['contact_lastName'].$value['contact_givenName'].';'.(string)$value['_id'].'</h4>';
}







?>