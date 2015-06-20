<?php
require_once 'YqBase.php';
class Protext extends YqBase {
    private $collection;

    function clear_unmeaningful_text($title){
        $title = strtr($title,"·", "");
        $title = strtr($title,"？", "");
        $title = strtr($title,"?", "");
        $title = strtr($title,"！", "");
        $title = strtr($title,"!", "");
        $title = strtr($title,"，", "");
        $title = strtr($title,",", "");
        $title = strtr($title,"。", "");
        $title = strtr($title,".", "");
        $title = strtr($title,"、", "");
        $title = strtr($title,"“", "");
        $title = strtr($title,"”", "");
        $title = strtr($title,"\"", "");
        $title = strtr($title,"/", "");
        $title = strtr($title,"&", "");
        $title = strtr($title,"=", "");
        $title = strtr($title,";", "");
        $title = strtr($title,"；", "");
        $title = strtr($title,"_", "");
        $title = strtr($title,"-", "");
        $title = strtr($title,"……", "");
        $title = strtr($title,"——", "");
        $title = strtr($title,"|", "");
        $title = strtr($title,"【", "");
        $title = strtr($title,"】", "");
        $title = strtr($title,"《", "");
        $title = strtr($title,"》", "");
        $title = strtr($title,"（", "");
        $title = strtr($title,"）", "");
        $title = strtr($title,"(", "");
        $title = strtr($title,")", "");
        $title = strtr($title,"「", "");
        $title = strtr($title,"」", "");
        $title = strtr($title,"<", "");
        $title = strtr($title,">", "");
        $title = strtr($title,"：", "");
        $title = strtr($title,":", "");
        $title = strtr($title,"-", "");
        $title = strtr($title,"+", "");
        $title = strtr($title," ", "");
        $title = strtr($title,"a", "");
        $title = strtr($title,"b", "");
        $title = strtr($title,"c", "");
        $title = strtr($title,"d", "");
        $title = strtr($title,"e", "");
        $title = strtr($title,"f", "");
        $title = strtr($title,"g", "");
        $title = strtr($title,"h", "");
        $title = strtr($title,"i", "");
        $title = strtr($title,"j", "");
        $title = strtr($title,"k", "");
        $title = strtr($title,"l", "");
        $title = strtr($title,"m", "");
        $title = strtr($title,"n", "");
        $title = strtr($title,"o", "");
        $title = strtr($title,"p", "");
        $title = strtr($title,"q", "");
        $title = strtr($title,"r", "");
        $title = strtr($title,"s", "");
        $title = strtr($title,"t", "");
        $title = strtr($title,"u", "");
        $title = strtr($title,"v", "");
        $title = strtr($title,"w", "");
        $title = strtr($title,"x", "");
        $title = strtr($title,"y", "");
        $title = strtr($title,"z", "");
        $title = strtr($title,"A", "");
        $title = strtr($title,"B", "");
        $title = strtr($title,"C", "");
        $title = strtr($title,"D", "");
        $title = strtr($title,"E", "");
        $title = strtr($title,"F", "");
        $title = strtr($title,"G", "");
        $title = strtr($title,"H", "");
        $title = strtr($title,"I", "");
        $title = strtr($title,"J", "");
        $title = strtr($title,"K", "");
        $title = strtr($title,"L", "");
        $title = strtr($title,"M", "");
        $title = strtr($title,"N", "");
        $title = strtr($title,"O", "");
        $title = strtr($title,"P", "");
        $title = strtr($title,"Q", "");
        $title = strtr($title,"R", "");
        $title = strtr($title,"S", "");
        $title = strtr($title,"T", "");
        $title = strtr($title,"U", "");
        $title = strtr($title,"V", "");
        $title = strtr($title,"W", "");
        $title = strtr($title,"X", "");
        $title = strtr($title,"Y", "");
        $title = strtr($title,"Z", "");
        $title = strtr($title,"0", "");
        $title = strtr($title,"1", "");
        $title = strtr($title,"2", "");
        $title = strtr($title,"3", "");
        $title = strtr($title,"4", "");
        $title = strtr($title,"5", "");
        $title = strtr($title,"6", "");
        $title = strtr($title,"7", "");
        $title = strtr($title,"8", "");
        $title = strtr($title,"9", "");
        $title = strtr($title,"%", "");
        $title = strtr($title,"的", "");
        $title = strtr($title,"了", "");
        $title = strtr($title,"和", "");
        $title = strtr($title,"与", "");
        $title = strtr($title,"或", "");
        $title = strtr($title,"于", "");
        $title = strtr($title,"这", "");
        $title = strtr($title,"那", "");
        $title = strtr($title,"你", "");
        $title = strtr($title,"我", "");
        $title = strtr($title,"们", "");
        $title = strtr($title,"是", "");
        $title = strtr($title,"不", "");
        $title = strtr($title,"在", "");
        $title = strtr($title,"再", "");
        $title = strtr($title,"就", "");
        $title = strtr($title,"为", "");
        $title = strtr($title,"吗", "");
        $title = strtr($title,"啊", "");
        $title = strtr($title,"哪", "");
        $title = strtr($title,"要", "");
        $title = strtr($title,"么", "");
        $title = strtr($title,"什", "");
        $title = strtr($title,"怎", "");
        $title = strtr($title,"还", "");
        $title = strtr($title,"谁", "");
        $title = strtr($title,"没", "");
        $title = strtr($title,"有", "");
        $title = strtr($title,"年", "");
        $title = strtr($title,"月", "");
        $title = strtr($title,"日", "");
        $title = strtr($title,"啥", "");
        $title = strtr($title,"又", "");
        $title = strtr($title,"只", "");
        $title = strtr($title,"为", "");
        $title = strtr($title,"以", "");
        $title = strtr($title,"够", "");
        $title = strtr($title,"更", "");
        $title = strtr($title,"给", "");
        $title = strtr($title,"但", "");
        $title = strtr($title,"而", "");
        $title = strtr($title,"千", "");
        $title = strtr($title,"万", "");
        $title = strtr($title,"亿", "");
        $title = strtr($title,"百", "");
        $title = strtr($title,"元", "");
        $title = strtr($title,"很", "");
        $title = strtr($title,"到", "");
        $title = strtr($title,"无", "");
        $title = strtr($title,"多少", "");
        $title = strtr($title,"如何", "");
        
        $title = strtr($title,"\n", "");
        $title = strtr($title,"\t", "");

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
            if ($labelCount/$textLen > 0.002) {
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
