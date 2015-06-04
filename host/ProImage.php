<?php
require_once 'YqBase.php';




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

foreach ($uncompleteSeeds as $key => $uncompleteSeed) {
    
    $seed['seed_completeStatus'] = 'inProcess';
    $db->Proseed->save($seed);


    $feedurl = $uncompleteSeed['seed_link'];

    //$feeds = file_get_contents($feedurl);
    $ch = curl_init($feedurl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $html = curl_exec($ch);

    //HTML进行UTF-8转码
    $encode = mb_detect_encoding($feeds, array('ASCII', 'UTF-8', 'GB2312', 'GBK', "EUC-CN", "CP936"));

    if ($encode != 'UTF-8') {
        //$encode = $encode . "//IGNORE"
        $html = iconv($encode, 'UTF-8//IGNORE', $feeds);

        //var_dump($feeds);

        $html = str_replace('encoding="gb2312"', 'encoding="utf-8"', $feeds);
        $html = str_replace('encoding="ascii"', 'encoding="utf-8"', $feeds);
        $html = str_replace('encoding="gbk"', 'encoding="utf-8"', $feeds);
        $html = str_replace('encoding="ecu-cn"', 'encoding="utf-8"', $feeds);
        $html = str_replace('encoding="cp936"', 'encoding="utf-8"', $feeds);

        $html = str_replace('encoding="GB2312"', 'encoding="utf-8"', $feeds);
        $html = str_replace('encoding="ASCII"', 'encoding="utf-8"', $feeds);
        $html = str_replace('encoding="GBK"', 'encoding="utf-8"', $feeds);
        $html = str_replace('encoding="EUC-CN"', 'encoding="utf-8"', $feeds);
        $html = str_replace('encoding="CP936"', 'encoding="utf-8"', $feeds);
    }



        $html = preg_replace("/[\t\n\r]+/", "", $feeds);
        $html = preg_replace("<script .*? /script>", "", $feeds);
        $html = preg_replace("<link .*? >", "", $feeds);
        $html = preg_replace("<link .*? >", "", $feeds);
        $html = preg_replace("<iframe .*? /iframe>", "", $feeds);

        $source = $db->Prosource->findOne(array('_id' => new MongoId($uncompleteSeed['seed_sourceID'])));

        $source_openTag = $source['source_tag'][0];
        $source_closeTag = $source['source_tag'][1];
        
        

        $openTag_pos = strpos($html, $source_openTag);
        $closeTag_pos = strpos($html, $source_closeTag);
        $cutHTML = mb_substr($html, $openTag_pos,$closeTag_pos-$openTag_pos);

        if (isset($source['text_startingTag']) {
            $text_startTag = $source['text_startingTag'];
            $startTag_pos = strpos($cutHTML,$text_startTag);
            $cutHTML = mb_substr($cutHTML, $startTag_pos);
        }

        if (isset($source['text_closingTag']) {
            $text_endTag = $source['text_closingTag'];
            $endTag_pos = strpos($cutHTML,$text_endTag);
            $cutHTML = mb_substr($cutHTML, $endTag_pos);
        }

        $text = $cutHTML;
        $text = str_replace("style=", "", $text);
        $text = str_replace("width", "", $text);
        $text = str_replace("height", "", $text);
        $text = str_replace("font-size", "", $text);
        $text = str_replace("size=", "", $text);

        $imgPattern = "<img.*?src=\"(.*?)\".*?>";

        preg_match_all($imgPattern, $wholeString, $imgResult);

        if (count($imgResult[0])>0) {
            $imageLink = $imgResult[1][0];    
        }else{
            $imageLink = '';
        }

        $seed['seed_text'] = $text;
        $seed['seed_imageLink'] = $imageLink;
        $seed['seed_completeStatus'] = 'completed';


        $db->Proseed->save($seed);

        echo $seeed['seed_source'].','.$seed['seed_title'].','.$seed['seed_imageLink'];

}




?>