<?php
	require_once 'YqBase.php';

	function load_file($url) {
		$ch = curl_init($url);
		#Return http response in string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$xml = simplexml_load_string(curl_exec($ch));
		return $xml;
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
			$sources = $db->Prosource->find(array('source_domain' => 'life'));
            $seeds = $db->Proseed->find();

            $sources = $db->Prosource->find(array('source_domain' => 'business','source_status' => 'active'));

            $worths = $db->Proworth->find();
            /*
            foreach ($worths as $key => $value) {
                if (!isset($value['like_status'])) {
                    $value['like_status'] = 'active';
                    $db->Proworth->save($value);
                }
            }*/

            /*
            $mediaGroups = $db->ProMediaGroup->find();

            //$bizGroups = array();
            foreach ($mediaGroups as $key => $value) {
                //$bizGroups[$value['industry_name']] = $value['industry_name'];
                
                if (!isset($value['mediaGroup_counts']['follower_count'])) {
                    $value['mediaGroup_counts']['follower_count'] = 0;
                    $db->ProMediaGroup->save($value);
                }

                if (isset($value['para_name'])) {
                    $db->ProMediaGroup->remove($value);
                }

            }*/

            /*
            $segments = $db->Prosystem->find(array('para_name' => 'segment'));

            foreach ($segments as $keys => $values) {
                $bizGroups[$values['segment_name']] = $values['segment_name'];
            }*/

            
            foreach ($sources as $key => $value) {
                //$value['source_status'] = 'active';
                echo '<h3>'.$value['source_name'].' '.$value['loading_status'].' '.date('Y-m-d H:i',$value['check_time']).'</h3>';

                /*
                if (isset($value['source_image'])) {
                    
                    if ($value['source_image'] != '') {
                        $start = strpos($value['source_image'], '.com/');
                        $end = strpos($value['source_image'], '.png');
                        if ($end === false) {
                            $end = strpos($value['source_image'], '.jpg');
                        }

                        $imageName = substr($value['source_image'], $start+5,$end-$start-5);
                        $value['source_imageName'] = $imageName;
                        //echo '<h3>'.$imageName.'</h3>';
                        
                    }
                    
                }*/
            //}

             /*
                    if (isset($seed['seed_industry'])) {
                        $seedIndustries = array ();
                        foreach ($seed['seed_industry'] as $key => $value) {
                            if (!isset($bizGroups[$value])) {
                                array_push($seedIndustries, $value);
                            }
                        }
                        $seed['seed_industry'] = $seedIndustries;
                    }

                    $db->Proseed->save($seed);

                   

                    $source = $db->Prosource->findOne(array('_id' => new MongoId($seed['seed_sourceID'])));
                    if (isset($source['source_blackList'])) {
                        foreach ($source['source_blackList'] as $key => $value) {
                            if (strpos($seed['seed_titleLower'], $value) !== false) {
                                $seed['seed_active'] = '0';
                                break;
                            }
                        }    
                    }
                    
                    switch ($seed['seed_editorRating']) {

                        case '0' :
                            $seed['seed_editorRating'] = 0;
                            break;                        

                        case '1' :
                            $seed['seed_editorRating'] = 1;
                            break;
                        
                        case '2' :
                            $seed['seed_editorRating'] = 2;
                            break;
                        case '-2' :
                            $seed['seed_editorRating'] = -2;
                            break;
                        case '-3' :
                            $seed['seed_editorRating'] = -3;
                            break;
                    }
                    
                    $db->Proseed->save($seed);
                    
                    //$seed['seed_domain'] = $source['source_domain'];
                    
             */       
            }


            //foreach ($seeds as $key => $value) {
             //   $value['seed_active'] = '1';
              //  $db->Proseed->save($value);

                //echo '<h3>'.$value['seed_title'].'  '.implode(';', $value['seed_industry']).'</h3>';
                /*
                $protext = new Protext;
                $parserResult = $protext->parseIndustry($value['seed_text'],$value['seed_titleLower']);
                $keywords = array();
                foreach ($parserResult['seed_textIndustryWords'] as $wordkey => $wordValue) {
                    array_push($keywords, $wordkey);
                }
                
                if (isset($parserResult['seed_industryParsed'])) {
                    echo '<h3>'.$value['seed_source'].' '.$value['seed_title'].':'.implode('@', $parserResult['seed_industryParsed']).'</3>';     
                }else{
                    echo '<h3>'.$value['seed_source'].' '.$value['seed_title'].'</3>';     
                }
                */
            //}
			
			/*
			foreach ($sources as $key => $source) {
				$source['check_time'] = time() - 1000000000;
				$db->Prosource->save($source);
			}*/
            
			
			/*
			//修改行业字典
			$dicts = $db->Prosystem->find(array('para_name' => 'industry_dict'));
			foreach ($dicts as $industry => $dict) {
				$theDict = $dict['industry_words']; 
				$newDict = array();
				foreach ($theDict as $key => $word) {
					$newDict[$word] = $word;
				}
				$dict['industry_words'] = $newDict;
				$db->Prosystem->save($dict);
				echo '<h2>'.$dict['industry_name'].'</h2>';
			}
			*/
            /*
			$dicts = $db->Prosystem->find(array('para_name' => 'industry_dict'));
			
			foreach ($dicts as $industry => $dict) {
				$industryName = $dict['industry_name'];
				$seeds = $db->Proseed->find();
				$count = 0;
				foreach ($seeds as $key => $value) {
					if (isset($value['seed_industry'])) {
						if (in_array($industryName,$value['seed_industry'])) {
							$count ++;
						}	
					}
				}
				echo '<h3>'.$industryName.$count.'</h3>';
			}
            */
	        
            /*
			$seeds = $db->Proseed->find();
			foreach ($seeds as $key => $value) {
                
				$cleanedText = clear_unmeaningful_char($value['seed_text']);
				$value['seed_textLen'] = mb_strlen($cleanedText,'utf-8');
				$db->Proseed->save($value);
                
	
			}*/
            

			/*
			foreach ($count as $key3 => $value3) {
				echo '<h3>'.$key3.':'.$value3.'</3>';
			}
			*/
			

			/*
			foreach ($dicts as $key => $industry) {
				if (isset($industry['search_words'])) {
					foreach ($industry['search_words'] as $key1 => $word) {
					$item = $db->Prowords->findOne(array('word_name' => $word));
						if ($item == null) {
							$data = array(
								'word_name' => $word,
								'word_industry' => $industry['industry_name'],
								'word_hotness' => 100,
								'word_checkTime' => time()
							);
							$db->Prowords->save($data);
						}
					}	
				}
				
			}
			*/
			/*
			foreach ($sources as $key => $source) {
				$source['source_status'] = 'active';
				$db->Prosource->save($source);
			}
			*/








?>