<?php
require_once 'YqBase.php';
class Protext extends YqBase {
    private $collection;

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
    	
    function parseTitle($title,$dict,$ENDict){

        $titleLength = mb_strlen($title);
        preg_match_all("(\\d+.\\d+|\\w+)", $title, $keywords_eng);
        
            
        foreach ($keywords_eng[0] as $keyy => $valuey) {
            foreach ($ENDict as $key3 => $word) {
                if (strpos($valuey,strtolower($word)) !== false) {
                    return 1;
                }
            }
        }

        $noEngTitle = preg_replace("(\\d+.\\d+|\\w+)", "", $title);
        $titleLength = mb_strlen($noEngTitle)-2;
        for($i = 0; $i<$titleLength;++$i){
            $twoStr = mb_substr($title,$i,2,'utf-8');
            $oneStr = mb_substr($title,0,1,'utf-8');
            if (isset($dict[$twoStr])){
                return 1;    
            }
            if (isset($dict[$oneStr])){
                return 1;    
            }

        }
        
    }

    function parseText($text,$industries){
    	
        $EnglishWords = array();
        preg_match_all("(\\d+.\\d+|\\w+)", $text, $allWords_eng);
        foreach ($allWords_eng[0] as $key0 => $Enword) {
            $EnglishWords[strtolower($Enword)] = strtolower($Enword);
        }
    	$text = $this->clear_unmeaningful_text($text);

        
        $keywordDict = array();
        $industryResult = array();
        $industryDict = array ();
        $result = array();
        $textLen = mb_strlen($text)-4;

        $matchPosInPara = array();
        $statics = array();

        //获得文章段落书
        //preg_match_all("<\/p>", $text, $paragraphs);
        $paragraphCount = $textLen/200;
        $avgParaLen = 200;

        //遍历文章每个字
        $textDict = array();
        $i = 0;
        while ($i<($textLen)) {
            $twoStr = mb_substr($text, $i,2,'utf-8');
            $threeStr = mb_substr($text,$i,3,'utf-8');
            $fourStr = mb_substr($text,$i,4,'utf-8');

            if (isset($textDict[$twoStr])) {
                array_push($textDict[$twoStr], ceil($i/$avgParaLen));
            }else{
                $textDict[$twoStr] = array();
                array_push($textDict[$twoStr], ceil($i/$avgParaLen));
            }

            if (isset($textDict[$threeStr])) {
                array_push($textDict[$threeStr], ceil($i/$avgParaLen));
            }else{
                $textDict[$threeStr] = array();
                array_push($textDict[$threeStr], ceil($i/$avgParaLen));
            }       
            
            if (isset($textDict[$fourStr])) {
                array_push($textDict[$fourStr], ceil($i/$avgParaLen));
            }else{
                $textDict[$fourStr] = array();
                array_push($textDict[$fourStr], ceil($i/$avgParaLen));
            }

            $i ++; 

        }
            
        //遍历所有的行业
        foreach($industries as $industry => $value){
            
            $wordCount = 0;
            if (isset($value['chinese'])) {
                $dict = $value['chinese'];
            
                foreach ($dict as $key => $keyword) {
                    if (isset($textDict[$keyword])) {
                        if (isset($keywordDict[$keyword])) {
                            $keywordDict[$keyword] += count($textDict[$keyword]);
                        }else{
                            $keywordDict[$keyword] = count($textDict[$keyword]);
                        }

                        $wordCount += count($textDict[$keyword]);  

                        foreach ($textDict[$keyword] as $position) {
                            array_push($matchPosInPara, $position);       
                        }      
                    }     
                }    
            }
             

            //增加英文的匹配
            if (isset($value['english'])) {
                $dictEN = $value['english'];
                foreach ($dictEN as $key1 => $keywordEn) {
                    $keywordEn = strtolower($keywordEn);
                    if (isset($EnglishWords[$keywordEn])) {
                        if (isset($keywordDict[$keywordEn])) {
                            $keywordDict[$keywordEn] += count($EnglishWords[$keywordEn]);
                        }else{
                            $keywordDict[$keywordEn] = count($EnglishWords[$keywordEn]);
                        }

                        $wordCount += count($EnglishWords[$keywordEn]);  

                        //暂时不做分布判断（英文分布的问题可能不会太明显）
                    }     
                } 
            }


            if ($wordCount>0) {
            	
    	        $matchRatio = $wordCount/$textLen;

    	        $square = 0;
                if (count($matchPosInPara) == 0) {
                    $variance = 1;
                }else{
                    $paraAvg = array_sum($matchPosInPara)/count($matchPosInPara);
                    foreach ($matchPosInPara as $pos) {
                        $square += pow($pos-$paraAvg, 2);
                    }
                    $stdSquare = pow($square/count($matchPosInPara), 0.5);
                    $variance = $stdSquare/$paragraphCount;    
                }

    	        //方差，计算
    	        //判断Result中不中
    	        if ($matchRatio>0.005 && $variance > 0.05 ) {
    	        	array_push($industryResult,$industry);
    	        }
                
                $statics[$industry] = $matchRatio;// * $variance;
                /*
    	        array_push($statics, $matchRatio);
    	        array_push($statics, $variance);
    	        array_push($statics, $wordCount);
    	        array_push($statics, $textLen);
    	        array_push($statics, $paraAvg);
    	        array_push($statics, $stdSquare);
    	        array_push($statics, $paragraphCount);
                */
            }
            
        }
        /*
        //继续Parse Segment
        $segmentDict = array();
        foreach ($industryResult as $parsedIndustry) {
            $dicts = $this->db->Prosystem->find(array('para_name'=>'segment','parent_industry' => $parsedIndustry));
            foreach ($dicts as $segment => $dict) {
                
                $matchCount = 0;
                if (isset($dict['segment_words'])) {
                    foreach ($dict['segment_words'] as $key1 => $value1) {
                        if (isset($textDict[$value1])) {
                            $matchCount += count($textDict[$value1]);
                        }                           
                    }    
                }

                //修改英文的增加
                if (isset($dict['segment_ENGDict'])) {
                    foreach ($dict['segment_ENGDict'] as $key2 => $value2) {
                        $value2 = strtolower($value2);
                        if (isset($EnglishWords[$value2])) {
                            $matchCount += count($EnglishWords[$value2]);
                        }  
                    }    
                }
                
                if (($matchCount/$textLen) > 0.01 ) {
                    array_push($segmentDict, $dict['segment_name']);
                }
            }
        }
        */
        /*
        foreach ($segmentDict as $segment) {
            array_push($industryResult,$segment);
        }*/

        //判断keywordDict的标签
        $labelsParsed = array();
        foreach ($keywordDict as $theWord => $labelCount) {
            if ($labelCount/$textLen > 0.003) {
                array_push($labelsParsed, $theWord);
            }
        }


        $result[0]=$keywordDict;
        $result[1]=$industryResult;
        $result[2]=$statics;
        $result[3]=$labelsParsed;
        //$result[3]=$segmentDict;
        unset($keywordDict);
        return $result;
    }
    
    function parseIndustry($seed_text,$seed_titleLower){
        $industryDict = array();

        $dicts = $this->db->Prosystem->find(array('para_name' => 'industry_dict'));
        foreach ($dicts as $industry => $dict) {
            $industryDict[$dict['industry_name']] = array();
            if (isset($dict['industry_words'])) {
                $industryDict[$dict['industry_name']]['chinese'] = $dict['industry_words'];
            }
            if (isset($dict['industry_ENDict'])) {
                $industryDict[$dict['industry_name']]['english'] = $dict['industry_ENDict'];    
            }
            
        }
        //遍历所有的Seed
        

        $seed = array();
        $seed['seed_industryParsed'] = array();
        $seedIndustry = array();

        $parserResult = $this->parseText($seed_text,$industryDict);

        if (count($parserResult[1])>0 && array_sum($parserResult[0])>5) {
            foreach ($parserResult[1] as $industryKey => $industryValue) {
                if (!isset($seed['seed_industryParsed'][$industryValue])) {
                    $seed['seed_industryParsed'][$industryValue] = $industryValue;
                }               
            } 
        }else{
            //分析标题
            foreach ($industryDict as $industry => $dict) {
                $checkResult = $this->parseTitle($seed_titleLower,$dict['chinese'],$dict['english']);    
                if ($checkResult == 1) {
                    if (!isset($seed['seed_industryParsed'][$industry])) {
                        $seed['seed_industryParsed'][$industry] = $industry;
                    }
                }
            }

            if (count($seed['seed_industryParsed'])==0) {
                $statics = $parserResult[2];
                if (count($statics) > 0 ) {
                    $maxValue = max($statics);
                    $maxIndustry = array_search($maxValue,$statics);
                    $seed['seed_industryParsed'][$maxIndustry] = $maxIndustry;    
                }
            }
        }
        
        foreach ($parserResult[3] as $label) {
            if (!isset($seed['seed_industryParsed'][$label])) {
                $seed['seed_industryParsed'][$label] = $label;
            }
        }

        $seed['seed_textIndustryWords'] = $parserResult[0];
        //$seed['seed_segmentParsed'] = $parserResult[3];
            
        //分析正文
        return $seed;

    }
    /*
    function parseSegment($industryDict,$industry){
        $segmentDict = array();
        $dicts = $this->db->Prosystem->find(array('para_name'=>'segment','parent_industry' => $industry));
        foreach ($dicts as $segment => $dict) {
            
            $matchCount = 0;
            
            foreach ($dict['segment_words'] as $key1 => $value1) {
                if (isset($industryDict[$value1])) {
                    $matchCount += $industryDict[$value1];
                }                           
            }
            foreach ($dict['segment_ENDict'] as $key2 => $value2) {
                if (isset($industryDict[$value2])) {
                    $matchCount += $industryDict[$value2];
                }  
            }
            if ($matchCount>10) {
                array_push($segmentDict, $dict['segment_name']);
            }
        }
        //分析正文
        return $segmentDict;
    }
    */
}

/*
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
                $industryDict[$dict['industry_name']] = array();
				$industryDict[$dict['industry_name']]['chinese'] = $dict['industry_words'];
                $industryDict[$dict['industry_name']]['english'] = $dict['industry_ENDict'];
			}
			//遍历所有的Seed
			$seeds = $db->Proseed->find();
			foreach ($seeds as $key => $seed) {
                $seed['seed_industryParsed'] = array();
                $seedIndustry = array();

                $parserResult = parseText($seed['seed_text'],$industryDict);

                $seed['seed_textIndustryWords'] = $parserResult[0];
                if (count($parserResult[1])>0) {
                    foreach ($parserResult[1] as $industryKey => $industryValue) {
                        if (!isset($seed['seed_industryParsed'][$industryValue])) {
                            $seed['seed_industryParsed'][$industryValue] = $industryValue;
                        }               
                    } 
                }else{
                    //分析标题
                    foreach ($industryDict as $industry => $dict) {
                        $checkResult = parseTitle($seed['seed_titleLower'],$dict['chinese'],$dict['english']);    
                        if ($checkResult == 1) {
                            if (!isset($seed['seed_industryParsed'][$industry])) {
                                $seed['seed_industryParsed'][$industry] = $industry;
                            }
                        }
                    }

                    if (count($seed['seed_industryParsed'])==0) {
                        $statics = $parserResult[2];
                        if (count($statics) > 0 ) {
                            $maxValue = max($statics);
                            $maxIndustry = array_search($maxValue,$statics);
                            $seed['seed_industryParsed'][$maxIndustry] = $maxIndustry;    
                        }
                    }
                }
                
                //分析正文
				$db->Proseed->save($seed);
				echo '<h3>'.$seed['seed_title'].', '.implode(';',$seed['seed_industryParsed']).implode(';',$parserResult[2]).'</h3>';
			}
	
*/
?>
