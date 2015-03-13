<?php 
session_start();

$handle=fopen('log.txt','a+');

fwrite($handle,'hahahahah');
rewind($handle);
$contents = fread($handle, filesize('log.txt'));
echo $contents;

?>

