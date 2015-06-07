<?php
require_once 'YqBase.php';

    
class Protext extends YqBase {
	private $collection;

	
function parseText($text){
	$prosource = new Prosource;
	$newText = $prosource->clear_unmeaningful_char($text,$industries);

    $titleLen = mb_strlen($title, 'utf-8');
    
    $keywordDict = array();
    $industryResult = array();
    $industryDict = array ();
    $result = array();




    //遍历文章每个字
   
        
        //遍历所有的行业
    foreach($industries as $industry => $dict){
        $wordCount = 0;

		for ($i = 0; $i < $titleLen-1; $i++) {
		        
		    $twoStr = mb_substr($title, $i, 2, 'utf-8');

        	//遍历所有字典
        	foreach ($dict as $word) {
        		if ($twoStr == $word) {
        			//构建字典
        			if (isset($keywordDict[$word])) {
        				$keywordDict[$word] ++;
        			}else{
        				$keywordDict[$word] = 1;
        			}

        			//增加Count
        			$wordCount ++;
        		}
        	}
        }

        //判断Result中不中
        if ($wordCount>10) {
        	array_push($industryResult,$industry);
        }
    }


    array_push($result, $industryResult);	
    array_push($result,$keywordDict);

    return $result;
}
	
}
?>
