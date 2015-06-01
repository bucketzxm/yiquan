<?php
	require_once 'YqBase.php';

	function load_file($url) {
		$ch = curl_init($url);
		#Return http response in string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$xml = simplexml_load_string(curl_exec($ch));
		return $xml;
	}

	function find_same($string1, $string2){
		// return true when two strings are similar
		
		$const_dif = 50; //const of diffrence

		if (mb_strlen($string2) < mb_strlen($string1)){
			$string_temp = $string1;
			$string1 = $string2;
			$string2 = $string_temp;
		}
		// Make sure that $string1 is shorter than $string2

		$string1_array = unpack('C*', $string1);
		$string2_array = unpack('C*', $string2);
		// Initialize before calculating

		$benchmark = 0;
		foreach ($string1_array as $key) {
			$benchmark += $key;
		}
		// get Benchmark value of $string1

		for($i = 0; $i <= mb_strlen($string2)-mb_strlen($string1); $i++){
			$judge = 0;
			for ($j = $i; $j < $i + count($string1_array) ; $j++){
				$judge += $string2_array[$j];
			}
			// get String2 value sum

			if (abs($judge - $benchmark) <= $const_dif){
				return true;
			}
			// judge
		}
		return false;
	}


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
			
			$proseed = $db->Proseed;
			
		//提出一个月以前的
		/*
		$currentTime = time();
		$timeMonthAgo = $currentTime - 86400*30;
		$db->Proseed->remove (array ('seed_time' => array ('$lt' => $timeMonthAgo)));*/


		//依次读取每个Source
		foreach ($sources as $key => $value) {
			echo "<h2>" . $value['source_name'] . "</h2>";
			$checkTime = $value['check_time'];
			
			//读取每个Source的URL地址
			foreach ($value['source_rssURL'] as $key => $url) {
			
				//读取每个URL地址的网页HTML
				$feedurl = $url;
				
		        //$feeds = file_get_contents($feedurl);
		        $ch = curl_init($feedurl);
		        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		        $feeds = curl_exec($ch);

		        //HTML进行UTF-8转码
		        $encode = mb_detect_encoding( $feeds, array('ASCII','UTF-8','GB2312','GBK',"EUC-CN","CP936"));

				if ( $encode !='UTF-8' ){
					//$encode = $encode . "//IGNORE"
					$feeds = iconv($encode,'UTF-8//IGNORE',$feeds);

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

				$seedsToLoad = array();
				//判断HTML的读取方式为正则还是RSS，并生成响应的数据
				if (isset($value['source_rexTemplate'])) {
					
					$feeds = preg_replace("/[\t\n\r]+/","",$feeds); 
					$feeds = preg_replace("<script .*? /script>", "", $feeds);
					$feeds = preg_replace("<link .*? >", "", $feeds);
					$feeds = preg_replace("<link .*? >", "", $feeds);
					$feeds = preg_replace("<iframe .*? /iframe>", "", $feeds);
					$pattern = $value['source_rexTemplate'];
					echo $pattern;
					preg_match_all($pattern,$feeds,$result); 


					$seedCount = count($result[0]);

					for ($i=0;$i<$seedCount;$i++){
						$seedToAdd = array();

						$link = $result[1][$i];
						$pos = strpos($link, 'http');
						
						if ($pos != 0) {
							$link = $url . $link;
						}
						echo "这是什么鬼".$link . "状态".$pos;

						$title = $result[2][$i];
						$title = str_replace(" ", "", $title);
						$title = str_replace("\n", "", $title);
						$title = str_replace("\t", "", $title);

						$postTime = time();

						$seedToAdd['title'] = $title;
						$seedToAdd['link'] = $link;
						$seedToAdd['postTime'] = $postTime;

						array_push($seedsToLoad,$seedToAdd);

					}
					var_dump($result);

				}else{

			        $start = strpos($feeds, "<?xml");
			        $start2 = strpos($feeds, "<rss");
			        if($start>$start2){
			        	$start=$start2;
			        }
			        $feeds = substr($feeds, $start);
			        $feeds = str_replace("<content:encoded>","<contentEncoded>",$feeds);
			        $feeds = str_replace("</content:encoded>","</contentEncoded>",$feeds);
			        $feeds = str_replace("CDATA<","CDATA[<",$feeds);

			        var_dump($feeds);
			        $rss = simplexml_load_string($feeds,'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_COMPACT | LIBXML_PARSEHUGE);

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

						$pubTime = str_replace("星期一","Mon",$pubTime);
						$pubTime = str_replace("星期二","Tue",$pubTime);
						$pubTime = str_replace("星期三","Wed",$pubTime);
						$pubTime = str_replace("星期四","Thu",$pubTime);
						$pubTime = str_replace("星期五","Fri",$pubTime);
						$pubTime = str_replace("星期六","Sat",$pubTime);
						$pubTime = str_replace("星期日","Sun",$pubTime);
						$pubTime = str_replace("星期天","Sun",$pubTime);
						$pubTime = str_replace("一月","Jan",$pubTime);
						$pubTime = str_replace("二月","Feb",$pubTime);
						$pubTime = str_replace("三月","Mar",$pubTime);
						$pubTime = str_replace("四月","Apr",$pubTime);
						$pubTime = str_replace("五月","May",$pubTime);
						$pubTime = str_replace("六月","Jun",$pubTime);
						$pubTime = str_replace("七月","Jul",$pubTime);
						$pubTime = str_replace("八月","Aug",$pubTime);
						$pubTime = str_replace("九月","Sep",$pubTime);
						$pubTime = str_replace("十月","Oct",$pubTime);
						$pubTime = str_replace("十一月","Nov",$pubTime);
						$pubTime = str_replace("十二月","Dec",$pubTime);
						$pubTime = str_replace("\n","",$pubTime);

						if ($pubTime != "" ) {//&& strlen($pubTime) > 24
							var_dump($pubTime);
							$postTime = $aaa->createFromFormat($value['time_format'],$pubTime)->getTimestamp();	
						}else{
							var_dump($pubTime);
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

						if ($postTime < $checkTime){

						}else{
							array_push($seedsToLoad,$seedToAdd);
						}

					}

				}


				//统一进行查重
				foreach ($seedsToLoad as $key => $seed) {
					foreach ($value['source_industry'] as $key => $industry) {
						$titles = $proseed -> find(array (
									'source_industry' => (string)$industry,
									 'seed_time' => array('$gt' => ($postTime - 86400))
									 ));

						$same = false;

						foreach ($titles as $key => $value) {

							if (find_same($value['seed_title'],$seed['title'])){
								$same = true;
								break;
							}
						}


						if ($same == false){

							//获取时间
							$postTime = $seed['postTime'];

							//进行标题拆字
							$title = $seed['title'];
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
						
							//Split keywords
							$titleLen = mb_strlen($title,'utf-8');
							$keywords = array ();
							for ($i = 0; $i<$titleLen-1;$i++){
								$twoStr = mb_substr($title, $i,2,'utf-8');
								array_push($keywords,$twoStr);
								//$threeStr = mb_substr($title, $i,3,'utf-8');
								//array_push($keywords,$threeStr);
							}
					//修复Link

							//处理文章的链接
							if (isset($value['source_linkReplace'])) {
					        	$linkToReplace = $seed['link'];
					        	$link = str_replace($value['source_linkReplace'][0], $value['source_linkReplace'][1], $linkToReplace);
					        }else{
					        	$link = $seed['link'];
					        }
					//获取Text
					//对Text进行处理

					//处理正文（RSS）
							$text = '';
					        if (isset($value['source_tag'])) {

					        	$oh = curl_init($link);
				        		curl_setopt($oh, CURLOPT_RETURNTRANSFER, true);
				        		$originalText = curl_exec($oh);

				        		$encode = mb_detect_encoding($originalText, array('ASCII','UTF-8','GB2312','GBK',"EUC-CN","CP936"));

								if ( $encode !='UTF-8' ){
									//$encode = $encode . "//IGNORE"
									$originalText = iconv($encode,'UTF-8//IGNORE',$originalText);

									//var_dump($feeds);
								}


				        		//var_dump(curl_error($oh));
				        		$opening = strpos($originalText, $value['source_tag'][0]);
				        		$closing = strpos($originalText, $value['source_tag'][1]);
				        		//$text = $originalText;
				        		$text = substr($originalText, $opening,$closing-$opening);

					        }else{

								$description = $seed['description'];
						        $content = $seed['content'];
						        $desString = $description;
						        $contentString = $content;
						        $desLen = strlen($desString);
						        $contentLen = strlen($contentString);
						        

						        if ($desLen < $contentLen) {
						        	$text = (string)$contentString;
						        }else{
						        	$text = (string)$desString;
						        }
					    	}

					    	if ($text != '' && $text != null) {
					    	
						    	if (isset($value['text_closingTag'])) {
						    	    $closingCursor = strpos($text,$value['text_closingTag']);
						    	    if ($closingCursor != false) {
						    	    		$text = substr($text,0,$closingCursor);
						    	    	}	
						    	}

						    	if (isset($value['text_startingTag'])) {
						    	    $startingCursor = strpos($text,$value['text_startingTag']);
						    	    if ($startingCursor != false) {
						    	    		$text = substr($text,$startingCursor,-1);
						    	    	}	
						    	}

								$text = str_replace("style=", "", $text);
								$text = str_replace("width", "", $text);
								$text = str_replace("height", "", $text);
								$text = str_replace("font-size", "", $text);
								$text = str_replace("size=", "", $text);
								    
								$title = $seed['title'];


								$seed = array (
									'seed_source' => $value['source_name'],
									'seed_sourceLower' => strtolower($value['source_name']),
									'seed_sourceID' => (string)$value['_id'],
									'seed_title' => $title,
									'seed_titleLower' => strtolower($title),
									'seed_link' => $link,
									'seed_text' => $text,
									'seed_time' => $postTime,
									'seed_keywords' =>$keywords,
									'seed_hotness' => 100,
									'seed_hotnessTime' => time(),
									'seed_industry' => $industry,
									'seed_agreeCount' => 0
								);
							
								//var_dump($keywords);
								//var_dump($proseed->save($seed));
								//var_dump($seed);
								$proseed->save($seed);	
							}

							echo "<h2>" . $value['source_name'].",".$title."," . $link.",".$postTime."</h2>";
						}
					}	
				}
			
			}


			$value['check_time'] = time();
			$prosource->save($value);
		}



?>