<?php
$obj = new stdClass();
$obj->body = 'another post';
$obj->id = 21;
$obj->approved = true;
$obj->favorite_count = 1;
$obj->status = NULL;

$arr = array();
array_push($arr,$obj);
array_push($arr,$obj);

echo json_encode($arr);

$test = explode('.', 'abc.txt'); 
echo $test[1];//output txt 
?>