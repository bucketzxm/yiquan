<?php
require_once 'YqBase.php';
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
$sources = $prosource->find();




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

            $text = preg_replace("<script.*?/script>", "", $text);
            $text = preg_replace("<link.*?>", "", $text);
            $text = preg_replace("<iframe.*?/iframe>", "", $text);

            $cleanedText = clear_unmeaningful_char($text);
            $textLen = mb_strlen($cleanedText,'utf-8');
            echo $textLen;
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
            
            $text = iconv($encode, 'UTF-8//IGNORE', $text);
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
                if (!isset($seed['seed_industryHotness'][$industry])) {
                    $seed['seed_industryHotness'][$industry] = 0;
                }
            }
            


            $db->Proseed->save($seed);
            echo $seed['seed_source'].','.$seed['seed_title'].','.$seed['seed_imageLink'];
    }else{
        $seed['seed_completeStatus'] = 'uncompleted';
        $db->Proseed->save($seed);
    }
}




?>