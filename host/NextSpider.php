<?php
require_once 'YqBase.php';



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

//定义一个时间变量

$daysCount = 444;
$daysCount = 0;
while ($daysCount <= 5) {
    $timeStr = "-".$daysCount." day";
    $url = "http://next.36kr.com/posts.html?start_on=".date("Y-m-d",strtotime($timeStr));
    

    
    //读取每个Source的URL地址
    
        

            //读取每个URL地址的网页HTML
            $feedurl = $url;

            //$feeds = file_get_contents($feedurl);
            $ch = curl_init($feedurl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $feeds = curl_exec($ch);

            $value['loading_status'] = curl_getinfo($ch,CURLINFO_HTTP_CODE);
            $loadingCount = 0;
            if (curl_getinfo($ch,CURLINFO_HTTP_CODE) == 200) {
                //HTML进行UTF-8转码
                $encode = mb_detect_encoding($feeds, array('ASCII', 'UTF-8', 'GB2312', 'GBK', "EUC-CN", "CP936"));

                if ($encode != 'UTF-8') {
                    //$encode = $encode . "//IGNORE"
                    $feeds = iconv($encode, 'UTF-8//IGNORE', $feeds);

                    //var_dump($feeds);

                    $feeds = str_replace('encoding="gb2312"', 'encoding="utf-8"', $feeds);
                    $feeds = str_replace('encoding="ascii"', 'encoding="utf-8"', $feeds);
                    $feeds = str_replace('encoding="gbk"', 'encoding="utf-8"', $feeds);
                    $feeds = str_replace('encoding="euc-cn"', 'encoding="utf-8"', $feeds);
                    $feeds = str_replace('encoding="cp936"', 'encoding="utf-8"', $feeds);

                    $feeds = str_replace('encoding="GB2312"', 'encoding="utf-8"', $feeds);
                    $feeds = str_replace('encoding="ASCII"', 'encoding="utf-8"', $feeds);
                    $feeds = str_replace('encoding="GBK"', 'encoding="utf-8"', $feeds);
                    $feeds = str_replace('encoding="EUC-CN"', 'encoding="utf-8"', $feeds);
                    $feeds = str_replace('encoding="CP936"', 'encoding="utf-8"', $feeds);
                }


                //$feeds = preg_replace("/[\t\n\r]+/", "",$feeds);
                
                $pattern = /<span class=\"vote-count\">(.*?)<\/span>/;
                /*$pattern = <span class=\"vote-count\">(.*?)</span>.*?<a class=\"post-url.*?>(.*?)</a>.*?<span class=\"post-tagline\">(.*?)</span>";
                */
                //echo $pattern;
                preg_match_all($pattern, $feeds, $result);

                //var_dump($result);
                $seedCount = count($result[0]);
                $elementCount = count($result);

                for ($i = 0; $i < $seedCount; ++$i) {
                    $len = strlen($result[1][$i]);
                    echo $result[1][$i]." ".$result[2][$i]." ".$result[3][$i]." ".$result[4][$i]."</br>";   
                    //substr($result[1][$i],$len-10,10)
                }
                //var_dump($seedsToLoad);

            }


        
                
    
    

    $daysCount ++;    
}




?>