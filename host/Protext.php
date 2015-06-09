<?php
require_once 'YqBase.php';


function clear_unmeaningful_text($title){
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
    //$title = str_replace("<", "", $title);
    //$title = str_replace(">", "", $title);
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
    //$title = str_replace("p", "", $title);
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
	
function parseTitle($title_Keywords,$dict){

    foreach ($title_Keywords as $key2 => $keyword){
        foreach ($dict as $key3 => $word) {
            if ($keyword == $word) {
                return 1;    
            }
            if (mb_strpos($keyword, $word) !== false) {
                return 1;
            }
        }
    }
}
function parseText($text,$industries){
	
	$text = clear_unmeaningful_text($text);

    
    $keywordDict = array();
    $industryResult = array();
    $industryDict = array ();
    $result = array();
    $textLen = mb_strlen($text);

    $matchPosInPara = array();
    $statics = array();

    //获得文章段落书
    preg_match_all("<\/p>", $text, $paragraphs);
    $paragraphCount = count($paragraphs[0]);
    $avgParaLen = 200;

    //遍历文章每个字
   
        
        //遍历所有的行业
    foreach($industries as $industry => $dict){
        $wordCount = 0;
       
        $i = 0;
		while ($i<=$textLen-2) {
		    
            $twoStr = mb_substr($text, $i, 2, 'utf-8');
        	//遍历所有字典
        	foreach ($dict as $word) {
                $matchWord = '';
                $oneStr = mb_substr($twoStr,1,1,'utf-8');
                if ($oneStr == $word) {
                    $matchWord = $oneStr;
                    $i ++;    
                }else{
                    if ($twoStr == $word) {    
                        $matchWord = $twoStr;
                        $i += 2;
                    }else{
                        $i ++;
                    }

                }

                if ($matchWord != '') {
                    //构建字典
                    if (isset($keywordDict[$matchWord])) {
                        $keywordDict[$matchWord] ++;
                    }else{
                        $keywordDict[$matchWord] = 1;
                    }

                    //增加Count
                    $wordCount ++;

                    //获得文章的平均段落数
                    $paraPos = ceil($i/$avgParaLen);
                    array_push($matchPosInPara, $paraPos);   
                }
        	}		
        }

        if ($wordCount>0) {
        	
	        $matchRatio = $wordCount/$textLen;

	        $square = 0;
	        $paraAvg = array_sum($matchPosInPara)/count($matchPosInPara);
	        foreach ($matchPosInPara as $pos) {
	        	$square += pow($pos-$paraAvg, 2);
	        }
	        $stdSquare = pow($square/count($matchPosInPara), 0.5);
	        $variance = $stdSquare/$paragraphCount;
	        

	        //方差，计算
	        //判断Result中不中
	        if ($matchRatio>0.005 && $variance > 0.05 ) {
	        	array_push($industryResult,$industry);
	        }

	        array_push($statics, $matchRatio);
	        array_push($statics, $variance);
	        array_push($statics, $wordCount);
	        array_push($statics, $textLen);
	        array_push($statics, $paraAvg);
	        array_push($statics, $stdSquare);
	        array_push($statics, $paragraphCount);
        }
        
    }


    array_push($result,$keywordDict);
    array_push($result,$industryResult);
	array_push($result,$statics);


    return $result;
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

			//构建行业字典
			$industryDict = array();

			$dicts = $db->Prosystem->find(array('para_name' => 'industry_dict'));
			foreach ($dicts as $industry => $dict) {
				$industryDict[$dict['industry_name']] = $dict['industry_words'];
			}
			//遍历所有的Seed
			$seeds = $db->Proseed->find();
			foreach ($seeds as $key => $seed) {

                $seedIndustry = array();

                //分析标题
                foreach ($industryDict as $industry => $dict) {
                    $checkResult = parseTitle($seed['seed_keywords'],$dict);    
                    if ($checkResult == 1) {
                        if (!isset($seed['seed_industryParsed'][$industry])) {
                            $seed['seed_industryParsed'][$industry] = 1;
                        }
                    }
                }
                

                /*
                //分析正文
                $parserResult = parseText($seed['seed_text'],$industryDict);

                $seed['seed_textIndustryWords'] = $parserResult[0];
                foreach ($parserResult[1] as $industryKey => $industryValue) {
                    if (!isset($seed['seed_industryParsed'][$industryValue])) {
                        $seed['seed_industryParsed'][$industryValue] = 1;
                    }               
                }    
                */
                

				$db->Proseed->save($seed);
                if (isset($seed['seed_industryParsed'])) {
                    $seedIndustry = $seed['seed_industryParsed'];
                }

				echo '<h3>'.$seed['seed_title'].', '.implode(';',$seedIndustry).', '.'</h3>';//implode(';',$parserResult[2]).
			}
	

?>
