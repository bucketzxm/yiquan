<?php
require_once 'YqBase.php';

function load_file($url) {
    $ch = curl_init($url);
    #Return http response in string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $xml = simplexml_load_string(curl_exec($ch));
    return $xml;
}

function find_same2($keywords,$seedToCheck){
    $sameCount = 0;
    foreach ($keywords as $word) {
        if (isset($seedToCheck[$word])) {
            $sameCount ++;
        }
    }
    
    if ($sameCount>= 7) {
        return 1;
    }elseif($sameCount > 4){
        return 2;
    }else{
    	return 0;
    }
}


function clear_unmeaningful_char($title){
           $title = str_replace("·", "", $title);
        $title = str_replace("？", "", $title);
        $title = str_replace("?", "", $title);
        $title = str_replace("！", "", $title);
        $title = str_replace("!", "", $title);
        $title = str_replace("，", "", $title);
        $title = str_replace(",", "", $title);
        $title = str_replace("。", "", $title);
        $title = str_replace(".", "", $title);
        $title = str_replace("、", "", $title);
        $title = str_replace("“", "", $title);
        $title = str_replace("”", "", $title);
        $title = str_replace("\"", "", $title);
        $title = str_replace("/", "", $title);
        $title = str_replace("&", "", $title);
        $title = str_replace("=", "", $title);
        $title = str_replace(";", "", $title);
        $title = str_replace("；", "", $title);
        $title = str_replace("_", "", $title);
        $title = str_replace("-", "", $title);
        $title = str_replace("……", "", $title);
        $title = str_replace("——", "", $title);
        $title = str_replace("|", "", $title);
        $title = str_replace("【", "", $title);
        $title = str_replace("】", "", $title);
        $title = str_replace("《", "", $title);
        $title = str_replace("》", "", $title);
        $title = str_replace("（", "", $title);
        $title = str_replace("）", "", $title);
        $title = str_replace("(", "", $title);
        $title = str_replace(")", "", $title);
        $title = str_replace("「", "", $title);
        $title = str_replace("」", "", $title);
        $title = str_replace("<", "", $title);
        $title = str_replace(">", "", $title);
        $title = str_replace("：", "", $title);
        $title = str_replace(":", "", $title);
        $title = str_replace("-", "", $title);
        $title = str_replace("+", "", $title);
        $title = str_replace(" ", "", $title);
        $title = str_replace("a", "", $title);
        $title = str_replace("b", "", $title);
        $title = str_replace("c", "", $title);
        $title = str_replace("d", "", $title);
        $title = str_replace("e", "", $title);
        $title = str_replace("f", "", $title);
        $title = str_replace("g", "", $title);
        $title = str_replace("h", "", $title);
        $title = str_replace("i", "", $title);
        $title = str_replace("j", "", $title);
        $title = str_replace("k", "", $title);
        $title = str_replace("l", "", $title);
        $title = str_replace("m", "", $title);
        $title = str_replace("n", "", $title);
        $title = str_replace("o", "", $title);
        $title = str_replace("p", "", $title);
        $title = str_replace("q", "", $title);
        $title = str_replace("r", "", $title);
        $title = str_replace("s", "", $title);
        $title = str_replace("t", "", $title);
        $title = str_replace("u", "", $title);
        $title = str_replace("v", "", $title);
        $title = str_replace("w", "", $title);
        $title = str_replace("x", "", $title);
        $title = str_replace("y", "", $title);
        $title = str_replace("z", "", $title);
        $title = str_replace("A", "", $title);
        $title = str_replace("B", "", $title);
        $title = str_replace("C", "", $title);
        $title = str_replace("D", "", $title);
        $title = str_replace("E", "", $title);
        $title = str_replace("F", "", $title);
        $title = str_replace("G", "", $title);
        $title = str_replace("H", "", $title);
        $title = str_replace("I", "", $title);
        $title = str_replace("J", "", $title);
        $title = str_replace("K", "", $title);
        $title = str_replace("L", "", $title);
        $title = str_replace("M", "", $title);
        $title = str_replace("N", "", $title);
        $title = str_replace("O", "", $title);
        $title = str_replace("P", "", $title);
        $title = str_replace("Q", "", $title);
        $title = str_replace("R", "", $title);
        $title = str_replace("S", "", $title);
        $title = str_replace("T", "", $title);
        $title = str_replace("U", "", $title);
        $title = str_replace("V", "", $title);
        $title = str_replace("W", "", $title);
        $title = str_replace("X", "", $title);
        $title = str_replace("Y", "", $title);
        $title = str_replace("Z", "", $title);
        $title = str_replace("0", "", $title);
        $title = str_replace("1", "", $title);
        $title = str_replace("2", "", $title);
        $title = str_replace("3", "", $title);
        $title = str_replace("4", "", $title);
        $title = str_replace("5", "", $title);
        $title = str_replace("6", "", $title);
        $title = str_replace("7", "", $title);
        $title = str_replace("8", "", $title);
        $title = str_replace("9", "", $title);
        $title = str_replace("%", "", $title);
        $title = str_replace("的", "", $title);
        $title = str_replace("了", "", $title);
        $title = str_replace("和", "", $title);
        $title = str_replace("与", "", $title);
        $title = str_replace("或", "", $title);
        $title = str_replace("于", "", $title);
        $title = str_replace("这", "", $title);
        $title = str_replace("那", "", $title);
        $title = str_replace("你", "", $title);
        $title = str_replace("我", "", $title);
        $title = str_replace("们", "", $title);
        $title = str_replace("是", "", $title);
        $title = str_replace("不", "", $title);
        $title = str_replace("在", "", $title);
        $title = str_replace("再", "", $title);
        $title = str_replace("就", "", $title);
        $title = str_replace("为", "", $title);
        $title = str_replace("吗", "", $title);
        $title = str_replace("啊", "", $title);
        $title = str_replace("哪", "", $title);
        $title = str_replace("要", "", $title);
        $title = str_replace("么", "", $title);
        $title = str_replace("什", "", $title);
        $title = str_replace("怎", "", $title);
        $title = str_replace("还", "", $title);
        $title = str_replace("谁", "", $title);
        $title = str_replace("没", "", $title);
        $title = str_replace("有", "", $title);
        $title = str_replace("年", "", $title);
        $title = str_replace("月", "", $title);
        $title = str_replace("日", "", $title);
        $title = str_replace("啥", "", $title);
        $title = str_replace("又", "", $title);
        $title = str_replace("只", "", $title);
        $title = str_replace("为", "", $title);
        $title = str_replace("以", "", $title);
        $title = str_replace("够", "", $title);
        $title = str_replace("更", "", $title);
        $title = str_replace("给", "", $title);
        $title = str_replace("但", "", $title);
        $title = str_replace("而", "", $title);
        $title = str_replace("千", "", $title);
        $title = str_replace("万", "", $title);
        $title = str_replace("亿", "", $title);
        $title = str_replace("百", "", $title);
        $title = str_replace("元", "", $title);
        $title = str_replace("很", "", $title);
        $title = str_replace("到", "", $title);
        $title = str_replace("无", "", $title);
        $title = str_replace("多少", "", $title);
        $title = str_replace("如何", "", $title);
        
        $title = str_replace("\n", "", $title);
        $title = str_replace("\t", "", $title);
    return $title;
}

function special_entities($string){
	$string = str_replace("&quote;", "\"", $title);
	$string = str_replace("&amp;", "&", $title);
	$string = str_replace("&lt;", "<", $title);
	$string = str_replace("&gt;", ">", $title);
	$string = str_replace("&OElig;", "Œ", $title);
	$string = str_replace("&oelig;", "œ", $title);
	$string = str_replace("&Scaron;", "Š", $title);
	$string = str_replace("&scaron;", "š", $title);
	$string = str_replace("&Yumi;", "Ÿ", $title);
	$string = str_replace("&circ;", "ˆ", $title);
	$string = str_replace("&tilde;", "˜", $title);
	$string = str_replace("&ensp;", " ", $title);
	$string = str_replace("&emsp;", " ", $title);
	$string = str_replace("&thinsp;", " ", $title);
	$string = str_replace("&zwnj;", "‌", $title);
	$string = str_replace("&zwj;", "", $title);
	$string = str_replace("&lrm;", "", $title);
	$string = str_replace("&rlm;", "", $title);
	$string = str_replace("&ndash;", "–", $title);
	$string = str_replace("&mdash;", "—", $title);
	$string = str_replace("&lsquo;", "‘", $title);
	$string = str_replace("&rsquo;", "’", $title);
	$string = str_replace("&sbquo;", "‚", $title);
	$string = str_replace("&ldquo;", "“", $title);
	$string = str_replace("&rdquo;", "”", $title);
	$string = str_replace("&bdquo;", "„", $title);
	$string = str_replace("&dagger;", "†", $title);
	$string = str_replace("&Dagger;", "‡", $title);
	$string = str_replace("&permil;", "‰", $title);
	$string = str_replace("&lsaquo;", "‹", $title);
	$string = str_replace("&rsaquo;", "›", $title);
	$string = str_replace("&euro;", "€", $title);
    $string = str_replace("&#8221", "\"", $title);
	return $string;
}

function find_same($string1, $string2){
    // return true when two strings are similar

    $const_dif = 30; //const of diffrence
    $const_dif2 = 1.01;
    $string1Len = mb_strlen($string1);
    $string2Len = mb_strlen($string2);

    if ($string2Len < $string1Len ){
        $string_temp = $string1;
        $string1 = $string2;
        $string2 = $string_temp;
    }
    // Make sure that $string1 is shorter than $string2

    $string1_array = unpack('C*', $string1);
    $string2_array = unpack('C*', $string2);
    // Initialize before calculating

    $benchmark = 0;

    $benchmark2 = 1;

    foreach ($string1_array as $key) {
        $benchmark += $key;
    }

    foreach ($string1_array as $key) {
        $benchmark2 *= $key;
    }
    // get Benchmark value of $string1
    $strLenGap = $string2Len -$string1Len;
    $string1ArrayCount = count($string1_array);

    for($i = 0; $i <= $strLenGap; ++$i){
        $judge = 0;
        $judge2 = 1;

        for ($j = $i; $j < $i + $string1ArrayCount ; ++$j){
            $judge += isset($string2_array[$j])?$string2_array[$j]:0;
            $judge2 *= isset($string2_array[$j])?$string2_array[$j]:1;
        }
        // get String2 value sum

        if ((abs($judge - $benchmark) <= $const_dif) && ((($judge2/$benchmark2)<$const_dif2 && ($judge2/$benchmark2)>=1)|| (($judge2/$benchmark2) > (1.0/$const_dif2) && ($judge2/$benchmark2)<=1))){
            echo "<p>".$judge." ".$benchmark." ".$judge2." ".$benchmark2." ".$string1." ".$string2."</p>";
            return true;
        }
        // judge
    }
    return false;
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
$prosource = $db->Prosource;
$sources = $prosource->find(array('source_status' => 'active'));

$proseed = $db->Proseed;

//提出一个月以前的
/*
$currentTime = time();
$timeMonthAgo = $currentTime - 86400*30;
$db->Proseed->remove (array ('seed_time' => array ('$lt' => $timeMonthAgo)));*/

//执行Prohot
$seeds = $db->Proseed->find(array('seed_hotness' => array('$gt' => 1)));
foreach ($seeds as $key => $seed) {
    /*
    $para = $db->Prosystem->findOne(array('para_name'=>"user_count"));
    if (isset ($para[$seed['seed_industry']])) {
        $speed = $para[$seed['seed_industry']];
    }else{
        $speed = 0;
    }*/
    $seed['seed_hotness'] = $seed['seed_hotness'] * exp((-0.05) * ((time() - $seed['seed_hotnessTime'])/3600));
    /*
    foreach($seed['seed_industryHotness'] as $industry => $hotness){
        $seed['seed_industryHotness'][$industry] = $hotness * exp ((-0.05) * ((time() - $seed['seed_hotnessTime'])/3600));
    }*/
    $seed['seed_hotnessTime'] = time();
    $db->Proseed->save($seed);
}


$words = $db->Prowords->find();
foreach ($words as $key1 => $word) {
    if (floor((time() - $word['word_checkTime'])/86400) >0) {
        $newHotness = $word['word_hotness'] * exp((-0.05) * floor((time() - $word['word_checkTime'])/86400)); 
        if ($word['word_type'] == 'default') {
            if ($newHotness < 100) {
                $word['word_hotness'] = 100;    
            }else{
                $word['word_hotness'] = $newHotness;
            }
        }else{
            $word['word_hotness'] = $newHotness;
        }
        
        $word['word_checkTime'] = time();
        $db->Prowords->save($word);
    }
}


//运行Prosource
//依次读取每个Source
foreach ($sources as $key => $value) {
    echo "<h2>" . $value['source_name'] . "</h2>";
    $checkTime = $value['check_time'];



    //维护数据完整性
    if (!isset($value['read_count'])) {
        $value['read_count'] = 0;
    }
    if (!isset($value['agree_count'])) {
        $value['agree_count'] = 0;
    }

    if ($value['read_count'] > 0) {
        $mediaAddition = $value['agree_count'] / $value['read_count'];
    } else {
        $mediaAddition = 0;
    }

    //读取每个Source的URL地址
    foreach ($value['source_rssURL'] as $keyz => $url) {
        try {

            //读取每个URL地址的网页HTML
            $feedurl = $url;

            //$feeds = file_get_contents($feedurl);
            $ch = curl_init($feedurl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $feeds = curl_exec($ch);

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

                $seedsToLoad = array();
                //判断HTML的读取方式为正则还是RSS，并生成响应的数据
                if (isset($value['source_rexTemplate'])) {

                    $feeds = preg_replace("/[\t\n\r]+/", "",$feeds);

                    $pattern = $value['source_rexTemplate'];
                    //echo $pattern;
                    preg_match_all($pattern, $feeds, $result);

                    //var_dump($feeds);
                    $seedCount = count($result[0]);
                    $elementCount = count($result);

                    for ($i = 0; $i < $seedCount; ++$i) {
                        $seedToAdd = array();

                        
                        $link = $result[1][$i];    
                        
                        
                        //echo $link;
                        //var_dump(strpos($link, 'http'));
                        if (strpos($link, 'http') === false) {
                            //echo "relative link detected";
                            $link = $value['source_homeURL'] . $link;
                        } else {
                            //echo "definite link detected";
                        }

                        
                        $title = $result[2][$i];    
                        
                        
                        $title = str_replace(" ", "", $title);
                        $title = str_replace("\n", "", $title);
                        $title = str_replace("\t", "", $title);

                        $postTime = time();

                        

                        $seedToAdd['title'] = $title;
                        $seedToAdd['link'] = $link;
                        $seedToAdd['postTime'] = $postTime;
                        
                        
                        $wholeString = $result[0][$i];
                        $imgPattern = "<(?:img|IMG).*?(?:src|data-url)=\"(.*?)\".*?>";

                        preg_match_all($imgPattern, $wholeString, $imgResult);

                        if (count($imgResult[0])>0) {
                            $seedToAdd['imageLink'] = $imgResult[1][0];
                            
                        }
                        if (isset($seedToAdd['imageLink'])) {
                            $httpPos = strpos($seedToAdd['imageLink'], 'http');
                            if ($seedToAdd['imageLink'] != '' && $httpPos === false) {
                                $seedToAdd['imageLink'] = str_replace('../', '', $seedToAdd['imageLink']);
                                $seedToAdd['imageLink'] = str_replace('./', '', $seedToAdd['imageLink']);
                                $seedToAdd['imageLink'] = $value['source_homeURL'].$seedToAdd['imageLink'];
                            }    
                        }

                        if (isset($value['source_parent`'])) {
                            $sourceName = $value['source_parent'];
                        }else{
                            $sourceName = $value['source_name'];
                        }
                        if (mb_strpos($title, $sourceName) === false) {
                            array_push($seedsToLoad, $seedToAdd);    
                        }
                        

                    }
                    //var_dump($seedsToLoad);

                } else {

                    $start = strpos($feeds, "<?xml");
                    $start2 = strpos($feeds, "<rss");
                    if ($start > $start2) {
                        $start = $start2;
                    }
                    $feeds = substr($feeds, $start);
                    $feeds = str_replace("<content:encoded>", "<contentEncoded>", $feeds);
                    $feeds = str_replace("</content:encoded>", "</contentEncoded>", $feeds);
                    $feeds = str_replace("CDATA<", "CDATA[<", $feeds);

                    //var_dump($feeds);
                    $rss = simplexml_load_string($feeds, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_COMPACT | LIBXML_PARSEHUGE);

                    //Calculate average hotness
                    /*
                    $seeds = $db->Proseed->find(array ('seed_sourceID' => (string)$value['_id']))->count();
                    $likes = $db->Proworth->find(array ('like_seedSource' => (string)$value['_id']))->count();
                    $avgLikes = $likes/$seeds;
                    $value['average_hotness'] = $avgLikes;
                    $db->Prosource->save ($value);
                    */

                    //$rss = load_file($feedurl);
                    $rssItems = $rss->channel->item;

                    foreach ($rssItems as $item) {

                        $seedToAdd = array();

                        //开始处理时间（仅RSS需要，获得postTime）
                        $aaa = new DateTime ();

                        $pubTime = $item->pubDate;

                        $pubTime = str_replace("星期一", "Mon", $pubTime);
                        $pubTime = str_replace("星期二", "Tue", $pubTime);
                        $pubTime = str_replace("星期三", "Wed", $pubTime);
                        $pubTime = str_replace("星期四", "Thu", $pubTime);
                        $pubTime = str_replace("星期五", "Fri", $pubTime);
                        $pubTime = str_replace("星期六", "Sat", $pubTime);
                        $pubTime = str_replace("星期日", "Sun", $pubTime);
                        $pubTime = str_replace("星期天", "Sun", $pubTime);
                        $pubTime = str_replace("一月", "Jan", $pubTime);
                        $pubTime = str_replace("二月", "Feb", $pubTime);
                        $pubTime = str_replace("三月", "Mar", $pubTime);
                        $pubTime = str_replace("四月", "Apr", $pubTime);
                        $pubTime = str_replace("五月", "May", $pubTime);
                        $pubTime = str_replace("六月", "Jun", $pubTime);
                        $pubTime = str_replace("七月", "Jul", $pubTime);
                        $pubTime = str_replace("八月", "Aug", $pubTime);
                        $pubTime = str_replace("九月", "Sep", $pubTime);
                        $pubTime = str_replace("十月", "Oct", $pubTime);
                        $pubTime = str_replace("十一月", "Nov", $pubTime);
                        $pubTime = str_replace("十二月", "Dec", $pubTime);
                        $pubTime = str_replace("\n", "", $pubTime);

                        if ($pubTime != "" && $pubTime != null) { //&& strlen($pubTime) > 24
                            //var_dump($pubTime);
                            $postTime = $aaa->createFromFormat($value['time_format'], $pubTime)->getTimestamp();
                        } else {
                            //var_dump($pubTime);
                            $postTime = time();
                        }
                        //获得标题
                        $title = $item->title;
                        $title = (string)$title[0];
                        $title = str_replace(" ", "", $title);
                        $title = str_replace("\n", "", $title);
                        $title = str_replace("\t", "", $title);

                        //获取链接
                        $link = (string)$item->link;

                        //获取RSS内容
                        $description = $item->description;
                        $content = $item->contentEncoded;


                        $seedToAdd['postTime'] = $postTime;
                        $seedToAdd['title'] = $title;
                        $seedToAdd['link'] = $link;
                        $seedToAdd['description'] = $description;
                        $seedToAdd['content'] = $content;

                        if ($postTime < $checkTime) {

                        } else {
                            if (isset($value['source_parent`'])) {
                                $sourceName = $value['source_parent'];
                            }else{
                                $sourceName = $value['source_name'];
                            }
                            if (mb_strpos($title, $sourceName) === false) {
                                array_push($seedsToLoad, $seedToAdd);
                            }
                        }

                    }

                }


                //统一进行查重

                $titles_cursor = $db->Proseed->find(array(
                    'seed_dbWriteTime' => array('$gt' => (time() - 86400))));


                $titles = array();

                foreach ($titles_cursor as $keyx => $valuex) {
                    array_push($titles, $valuex);
                }

                $sourceTitles = array();
                $sourceTitle_cursor = $db->Proseed->find(array(
                    'seed_sourceID' => (string)$value['_id'],
                    'seed_dbWriteTime' => array('$gt' => (time() - 86400*30))
                    ));
                foreach ($sourceTitle_cursor as $keysx => $valuesx) {
                    array_push($sourceTitles, $valuesx);
                }

                foreach ($seedsToLoad as $key1 => $seed) {

                    //进行标题拆字
                    $title = $seed['title'];
                    $title = clear_unmeaningful_char($title);


                    //Split keywords
                    $titleLen = mb_strlen($title, 'utf-8')-1;
                    $keywords = array();
                    $keywordDict = array();
                    for ($i = 0; $i < $titleLen; ++$i) {
                        $twoStr = mb_substr($title, $i, 2, 'utf-8');
                        array_push($keywords, $twoStr);
                        $keywordDict[$twoStr] = 1;
                        //$threeStr = mb_substr($title, $i,3,'utf-8');
                        //array_push($keywords,$threeStr);
                    }
                    preg_match_all("(\\d+.\\d+|\\w+)", $seed['title'], $keywords_eng);

                    foreach ($keywords_eng[0] as $keyy => $valuey) {
                        array_push($keywords, strtolower($valuey));
                        $keywordDict[strtolower($valuey)] = 1;
                    }


                    

                        $same = false;

                        $seed_similar = array();

                        if (count($keywords) > 6){
                        	foreach ($titles as $key3 => $title_name) {

    	                        if ( find_same2($keywords, $title_name['seed_keywordDict'])==1) {//$title_name['seed_industry'] == $industry &&
    	                            //echo '<p>' . $seed['title'] . '</p>';
    	                            //echo '<p>' . $title_name['seed_title'] . '</p>';
    	                            $same = true;
    	                            break;
    	                        }

    	                        if (find_same2($keywords, $title_name['seed_keywordDict'])==2) {//$title_name['seed_industry'] == $industry && 
    	                            array_push($seed_similar, (string)$title_name['_id']);
    	                        }
    	                    }


                            foreach ($sourceTitles as $key4 => $sourceTitle_name) {
                                if ( find_same2($keywords, $sourceTitle_name['seed_keywordDict'])==1) {//$title_name['seed_industry'] == $industry &&
                                    //echo '<p>' . $seed['title'] . '</p>';
                                    //echo '<p>' . $title_name['seed_title'] . '</p>';
                                    $same = true;
                                    break;
                                }
                            }


                        }else{
                            foreach ($titles as $key3 => $title_name) {
                            	if (find_same($title, $title_name['seed_title'])==true) {//$title_name['seed_industry'] == $industry && (
        	                            //echo '<p>' . $seed['title'] . '</p>';
        	                            //echo '<p>' . $title_name['seed_title'] . '</p>';
        	                            $same = true;
        	                            break;
        	                        }

                            }

                            foreach ($sourceTitles as $key4 => $sourceTitle_name) {
                                if ( find_same($title, $sourceTitle_name['seed_title'])==true) {//$title_name['seed_industry'] == $industry &&
                                    //echo '<p>' . $seed['title'] . '</p>';
                                    //echo '<p>' . $sourceTitle_name['seed_title'] . '</p>';
                                    $same = true;
                                    break;
                                }
                            }

                        }

                        


                        if ($same == false) {

                            //获取时间
                            $postTime = $seed['postTime'];


                            //修复Link

                            //处理文章的链接
                            if (isset($value['source_linkReplace'])) {
                                $linkToReplace = $seed['link'];
                                $link = str_replace($value['source_linkReplace'][0], $value['source_linkReplace'][1], $linkToReplace);
                            } else {
                                $link = $seed['link'];
                            }
                            //获取Text
                            //对Text进行处理

                            //处理正文（RSS）


                            $text = '';
                            if (isset($value['source_tag'])) {
                                /*
                                $oh = curl_init($link);
                                curl_setopt($oh, CURLOPT_RETURNTRANSFER, true);
                                $originalText = curl_exec($oh);

                                $encode = mb_detect_encoding($originalText, array('ASCII','UTF-8','GB2312','GBK',"EUC-CN","CP936"));

                                if ( $encode !='UTF-8' ){
                                    //$encode = $encode . "//IGNORE"
                                    $originalText = iconv($encode,'UTF-8//IGNORE',$originalText);

                                    //var_dump($feeds);

                                    $feeds = str_replace('encoding="gb2312"', 'encoding="utf-8"', $feeds);
                                    $feeds = str_replace('encoding="ascii"', 'encoding="utf-8"', $feeds);
                                    $feeds = str_replace('encoding="gbk"', 'encoding="utf-8"', $feeds);
                                    $feeds = str_replace('encoding="ecu-cn"', 'encoding="utf-8"', $feeds);
                                    $feeds = str_replace('encoding="cp936"', 'encoding="utf-8"', $feeds);

                                    $feeds = str_replace('encoding="GB2312"', 'encoding="utf-8"', $feeds);
                                    $feeds = str_replace('encoding="ASCII"', 'encoding="utf-8"', $feeds);
                                    $feeds = str_replace('encoding="GBK"', 'encoding="utf-8"', $feeds);
                                    $feeds = str_replace('encoding="EUC-CN"', 'encoding="utf-8"', $feeds);
                                    $feeds = str_replace('encoding="CP936"', 'encoding="utf-8"', $feeds);
                                }


                                //var_dump(curl_error($oh));
                                $opening = strpos($originalText, $value['source_tag'][0]);
                                $closing = strpos($originalText, $value['source_tag'][1]);
                                //$text = $originalText;
                                $text = substr($originalText, $opening,$closing-$opening);
                                */
                            } else {

                                $description = $seed['description'];
                                $content = $seed['content'];
                                $desString = $description;
                                $contentString = $content;
                                $desLen = strlen($desString);
                                $contentLen = strlen($contentString);


                                if ($desLen < $contentLen) {
                                    $text = (string)$contentString;
                                } else {
                                    $text = (string)$desString;
                                }
                            }

                            if (true) {

                                if (isset($value['text_closingTag'])) {
                                    $closingCursor = strpos($text, $value['text_closingTag']);
                                    if ($closingCursor != false) {
                                        $text = substr($text, 0, $closingCursor);
                                    }
                                }

                                if (isset($value['text_startingTag'])) {
                                    $startingCursor = strpos($text, $value['text_startingTag']);
                                    if ($startingCursor != false) {
                                        $text = substr($text, $startingCursor, -1);
                                    }
                                }

                                $text = str_replace("style=", "", $text);
                                $text = str_replace("width", "", $text);
                                $text = str_replace("height", "", $text);
                                $text = str_replace("font-size", "", $text);
                                //$text = str_replace("size=", "", $text);
                                //去掉3W互联网沙龙的第一张无关图片；
                                $text = str_replace("\"http://mmbiz.qpic.cn/mmbiz/agEQQ7NdJSPvNmD077w8LlvW6UF4G0b50paUvp37W56uAI0BibsH4by9twNUQlvdUv6zqUdqwOibHicQgNYnYtfMQ/0?wx_fmt=png\"", "", $text);
                                $text = str_replace("\"http://mmbiz.qpic.cn/mmbiz/agEQQ7NdJSNmJibkdPTYyoEjyweiaaOGNoNEFH4TL7jqX66MAew9q28wZkGW77UiakSINicQpKaSRtU8Ck1p0fibT2Q/0\"", "", $text);


                                $text = preg_replace("<script.*?/script>", "", $text);
                                $text = preg_replace("<link.*?>", "", $text);
                                $text = preg_replace("<iframe.*?/iframe>", "", $text);

                                $cleanedText = clear_unmeaningful_char($text);
                                $textLen = mb_strlen($cleanedText,'utf-8');


                                //处理Title
                                $title = $seed['title'];
                                $title = preg_replace("/<.+?>/", "", $title);
                                $title = str_replace("&quot;", "", $title);


                                if ($title != '' && $title != null && strlen($title) > 0) {



                                    //若来源为门户频道，则使用门户的父名称
                                    if (isset($value['source_parent'])) {
                                        $sourceName = $value['source_parent'];
                                    } else {
                                        $sourceName = $value['source_name'];
                                    }
                                    if (isset($value['source_tag'])) {
                                        $sourceTag = $value['source_tag'];
                                    } else {
                                        $sourceTag = array();
                                    }

                                    $textTag = array();
                                    if (isset($value['text_startingTag'])) {
                                        array_push($textTag,$value['text_startingTag']);
                                    } else {
                                        array_push($textTag,'');
                                    }

                                    if (isset($value['text_closingTag'])) {
                                        array_push($textTag,$value['text_closingTag']);
                                    } else {
                                        array_push($textTag,'');
                                    }


                                    $seed['imageCount'] = 0;

                                    //获取正文中的第一张图片：
                                    if (!isset($seed['imageLink'])) {
                                        if ($text != '') {
                                            $imageReg = "<(?:img|IMG).*?(?:src|data-url)=\"(.*?)\".*?>";
                                            preg_match_all($imageReg, $text, $images);
                                            $imgCount = count($images[0]);
                                            if ($imgCount>0 && $imgCount<3) {
                                                $seed['imageLink'] = $images[1][0];    
                                            }else if ($imgCount >= 3){
                                                $seed['imageLink'] = $images[1][0];
                                            }else{
                                                    $seed['imageLink'] = '';        
                                            }
                                            $seed['imageCount'] = $imgCount;

                                        }else{
                                            $seed['imageLink'] = '';
                                        }
                                        $httpPos = strpos($seed['imageLink'], 'http');
                                        if ($seed['imageLink'] != '' && $httpPos === false) {

                                            $seed['imageLink'] = str_replace('../', '', $seed['imageLink']);
                                            $seed['imageLink'] = str_replace('./', '', $seed['imageLink']);
                                            $seed['imageLink'] = $value['source_homeURL'].$seed['imageLink'];
                                        }
                                        if ($value['source_name'] == 'TECH2IPO 创见') {
                                            $seed['imageLink'] = str_replace("/0/","/192/", $seed['imageLink']);
                                        }

                                    }

                                    if ($seed['imageCount'] > 5 ) {
                                        $title = $title.'（多图）';
                                    }


                                    $completeStatus = 'completed';
                                    if ($text == '') {
                                        $completeStatus = 'uncompleted';
                                    }

                                    $text = iconv($encode, 'UTF-8//IGNORE', $text);

                                    $dataToSave = array(
                                        'seed_source' => $sourceName,
                                        'seed_sourceLower' => strtolower($value['source_name']),
                                        'seed_sourceID' => (string)$value['_id'],
                                        'seed_title' => htmlspecialchars_decode($title),
                                        'seed_titleLower' => strtolower(htmlspecialchars_decode($title)),
                                        'seed_link' => $link,
                                        'seed_text' => $text,
                                        'seed_textLen' => $textLen,
                                        'seed_time' => $postTime,
                                        'seed_dbWriteTime' => time(),
                                        'seed_keywords' => $keywords,
                                        'seed_keywordDict' => $keywordDict,
                                        'seed_hotness' => ((100 + (20 * $mediaAddition * 10)) * exp((-0.05) * ((time() - $postTime) / 3600))),
                                        'seed_hotnessTime' => time(),
                                        //'seed_industry' => $industry,
                                        'seed_agreeCount' => rand(0,5),
                                        'seed_sourceTag' => $sourceTag,
                                        'seed_textTag' => $textTag,
                                        'seed_imageLink' => $seed['imageLink'],
                                        'seed_imageCount' => $seed['imageCount'],
                                        'seed_similar' => $seed_similar,
                                        'seed_completeStatus' => $completeStatus,
                                        'seed_active' => '1'
                                        
                                    );

                                    //解析行业
                                    $seedIndustry = array();
                                    //$industryHotness = array();

                                    if ($text != '') {
                                        $protext = new Protext; 
                                        $parserResult = $protext->parseIndustry($text,strtolower($title));    
                                        $dataToSave['seed_textIndustryWords'] = $parserResult['seed_textIndustryWords'];
                                        
                                        foreach($parserResult['seed_industryParsed'] as $industryParsed){

                                            array_push($seedIndustry,$industryParsed);
                                            //$industryHotness[$industryParsed] = 0;
                                            /*
                                            $segmentResult = $protext->parseSegment($parserResult['seed_textIndustryWords'],$industryParsed);
                                            if (count($segmentResult) > 0) {
                                                foreach ($segmentResult as $segment) {
                                                    array_push($seedIndustry, $segment);
                                                }
                                            }
                                            */
                                        };
                                        /*
                                        foreach ($parserResult['seed_segmentParsed'] as $key2 => $segment) {
                                            if (!in_array($segment,$seedIndustry)) {
                                                array_push($seedIndustry,$segment);
                                            }
                                        }
                                        */
                                    }

                                    /*
                                    if (isset($value['source_industry'])){
                                        foreach ($value['source_industry'] as $key2 => $industry) {
                                            if (!in_array($industry,$seedIndustry)) {
                                                array_push($seedIndustry,$industry);
                                                $industryHotness[$industry] = 0;
                                            }
                                        }    
                                    }*/
                                    

                                    $dataToSave['seed_industry'] = $seedIndustry;
                                    //$dataToSave['seed_industryHotness'] = $industryHotness;

                                    
                                    
                                


                                    //var_dump($keywords);
                                    //var_dump($proseed->save($seed));
                                    //var_dump($seed);
                                    //var_dump($dataToSave);
                                    $proseed->save($dataToSave);
                                    array_push($titles, $dataToSave);
                                    array_push($sourceTitles, $dataToSave);

                                    foreach ($dataToSave['seed_similar'] as $keyzzz => $valuezzz) {
                                    	$news = $proseed -> findOne(array('_id'=> new MongoId($valuezzz)));
                                    	array_push($news['seed_similar'], (string)$dataToSave['_id']);
                                    	$proseed->save($news);
                                    }

                                }

                                //}

                                echo "<h2>" . $value['source_name'] . "," . $title . "," . $link . "," . $postTime . ",". $textLen."</h2>";
                            }
                        }
                    //}

                }

                $value['check_time'] = time();
                $prosource->save($value);
            }
        } catch (Exception $e) {
            var_dump($e);
        }

        
    }
    
}




//运行ProImage

//

//找到所有的没有图片和正文的新闻

$uncompleteSeeds = $db->Proseed->find(array('seed_dbWriteTime'=> array ('$gt' => (time()-86400)),'seed_text' => '','seed_completeStatus' => 'uncompleted'));

foreach ($uncompleteSeeds as $key => $seed) {
    
    $seed['seed_completeStatus'] = 'inProcess';
    $db->Proseed->save($seed);


    $feedurl = $seed['seed_link'];

    //$feeds = file_get_contents($feedurl);
    $ch = curl_init($feedurl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $html = curl_exec($ch);

    //echo '<h3>'.$feedurl.'</h3>';
    //echo '<h3>'.curl_getinfo($ch,CURLINFO_HTTP_CODE).'</h3>';
    //echo '<h3>'.strlen($html).'</h3>';

    if (curl_getinfo($ch,CURLINFO_HTTP_CODE) != 0) {
        
    
        //HTML进行UTF-8转码
        $encode = mb_detect_encoding($html, array('ASCII', 'UTF-8', 'GB2312', 'GBK', "EUC-CN", "CP936"));

        if ($encode != 'UTF-8') {
            //$encode = $encode . "//IGNORE"
            $html = iconv($encode, 'UTF-8//IGNORE', $html);

            //var_dump($feeds);

            $html = str_replace('encoding="gb2312"', 'encoding="utf-8"', $html);
            $html = str_replace('encoding="ascii"', 'encoding="utf-8"', $html);
            $html = str_replace('encoding="gbk"', 'encoding="utf-8"', $html);
            $html = str_replace('encoding="ecu-cn"', 'encoding="utf-8"', $html);
            $html = str_replace('encoding="cp936"', 'encoding="utf-8"', $html);

            $html = str_replace('encoding="GB2312"', 'encoding="utf-8"', $html);
            $html = str_replace('encoding="ASCII"', 'encoding="utf-8"', $html);
            $html = str_replace('encoding="GBK"', 'encoding="utf-8"', $html);
            $html = str_replace('encoding="EUC-CN"', 'encoding="utf-8"', $html);
            $html = str_replace('encoding="CP936"', 'encoding="utf-8"', $html);
        }



            $html = preg_replace("/[\t\n\r]+/", "", $html);
            $html = preg_replace("<script .*? /script>", "", $html);
            $html = preg_replace("<link .*? >", "", $html);
            $html = preg_replace("<link .*? >", "", $html);
            $html = preg_replace("<iframe .*? /iframe>", "", $html);

            

            $source = $db->Prosource->findOne(array('_id' => new MongoId($seed['seed_sourceID'])));

            $source_openTag = $source['source_tag'][0];
            $source_closeTag = $source['source_tag'][1];
            
            
            

            $openTag_pos = strpos($html, $source_openTag);
            $closeTag_pos = strpos($html, $source_closeTag);
            $cutHTML = mb_substr($html, $openTag_pos,$closeTag_pos-$openTag_pos);
            

            if (isset($source['text_startingTag'])) {
                $text_startTag = $source['text_startingTag'];
                $startTag_pos = strpos($cutHTML,$text_startTag);
                if ($startTag_pos !== false) {
                    $cutHTML = mb_substr($cutHTML, $startTag_pos);    
                }
                
            }

            if (isset($source['text_closingTag'])) {
                $text_endTag = $source['text_closingTag'];
                $endTag_pos = strpos($cutHTML,$text_endTag);
                if ($endTag_pos !== false) {
                    $cutHTML = mb_substr($cutHTML,0,$endTag_pos);
                }
                
            }

            $text = $cutHTML;
            $text = str_replace("style=", "", $text);
            $text = str_replace("width", "", $text);
            $text = str_replace("height", "", $text);
            $text = str_replace("font-size", "", $text);
            //$text = str_replace("size=", "", $text);

            $text = preg_replace("<script.*?/script>", "",$text);
            $text = preg_replace("<link.*?>", "",$text);
            $text = preg_replace("<iframe.*?/iframe>", "",$text);

            $cleanedText = clear_unmeaningful_char($text);
            $textLen = mb_strlen($cleanedText,'utf-8');


            //解析行业
            $protext = new Protext;
            $parserResult = $protext->parseIndustry($text,strtolower($seed['seed_titleLower']));        


            $imgPattern = "<(?:img|IMG).*?(?:src|data-url)=\"(.*?)\".*?>";

            preg_match_all($imgPattern, $text, $imgResult);
            
            if (count($imgResult[0])>0) {
                $imageLink = $imgResult[1][0];    
                $imgCount = count($imgResult[0]);
            }else{
                $imageLink = '';    
                $imgCount = 0;
            }

            $httpPos = strpos($imageLink, 'http');
            if ($imageLink != '' && $httpPos === false) {
                $imageLink = $source['source_homeURL'].$imageLink;
            } 

            if ($source['source_name'] == '趋势网') {
                $imageLink = str_replace("uploads/../../", "", $imageLink);
            }else{
                $imageLink = str_replace("../", "", $imageLink);
            }
            
            if ($imgCount > 5 ) {
                $seed['seed_title'] = $seed['seed_title'].'（多图）';
            }
        
            $encodeAgain = mb_detect_encoding($text, array('ASCII', 'UTF-8', 'GB2312', 'GBK', "EUC-CN", "CP936"));
            $text = iconv($encodeAgain, 'UTF-8//IGNORE', $text);
            var_dump($text);
            $seed['seed_text'] = $text;
            $seed['seed_textLen'] = $textLen;
            $seed['seed_imageLink'] = $imageLink;
            $seed['seed_imageCount'] = $imgCount;
            $seed['seed_completeStatus'] = 'completed';
            $seed['seed_textIndustryWords'] = $parserResult['seed_textIndustryWords'];
            foreach ($parserResult['seed_industryParsed'] as $key1 => $industry) {
                if (!in_array($industry,$seed['seed_industry'])) {
                    array_push($seed['seed_industry'],$industry);
                }
                /*
                if (!isset($seed['seed_industryHotness'][$industry])) {
                    $seed['seed_industryHotness'][$industry] = 0;
                }*/
                /*
                $segmentResult = $protext->parseSegment($parserResult['seed_textIndustryWords'],$industryParsed);
                if (count($segmentResult) > 0) {
                    foreach ($segmentResult as $segment) {
                        array_push($seedIndustry, $segment);
                    }
                }
                */
            }
            /*
            foreach ($parserResult['seed_segmentParsed'] as $key2 => $segment) {
                if (!in_array($segment,$seed['seed_industry'])) {
                    array_push($seed['seed_industry'],$segment);
                }
            }*/

            $db->Proseed->save($seed);
            echo $seed['seed_source'].','.$seed['seed_title'].','.$seed['seed_imageLink'];
    }else{
        $seed['seed_completeStatus'] = 'uncompleted';
        $db->Proseed->save($seed);
    }
}





?>