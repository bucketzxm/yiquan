<?php
	require_once 'YqBase.php';

	function load_file($url) {
		$ch = curl_init($url);
		#Return http response in string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$xml = simplexml_load_string(curl_exec($ch));
		return $xml;
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

		foreach ($sources as $key => $value) {
			echo "<h2>" . $value['source_name'] . "</h2>";
			$checkTime = $value['check_time'];
			
			foreach ($value['source_rssURL'] as $key => $url) {
			
				$feedurl = $url;
				
		        //$feeds = file_get_contents($feedurl);
		        $ch = curl_init($feedurl);
		        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		        $feeds = curl_exec($ch);

		        $start = strpos($feeds, "<?xml");
		        $start2 = strpos($feeds, "<rss");
		        if($start>$start2){
		        	$start=$start2;
		        }
		        $feeds = substr($feeds, $start);
		        $feeds = str_replace("<content:encoded>","<contentEncoded>",$feeds);
		        $feeds = str_replace("</content:encoded>","</contentEncoded>",$feeds);
		        $feeds = str_replace("CDATA<","CDATA[<",$feeds);
		        //var_dump($feeds);
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
			
				foreach ($rss->channel->item as $item) {
					
					$aaa = new DateTime ();
					
					$pubTime = $item->pubDate;
					if ($pubTime != "") {
						$pubTime = str_replace("\n","",$pubTime);
						var_dump($pubTime);
						$postTime = $aaa->createFromFormat($value['time_format'],$pubTime)->getTimestamp();	
					}else{
						$postTime = time();
					}
					

					$title = $item->title;
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
					//Split keywords
					$titleLen = mb_strlen($title,'utf-8');
					$keywords = array ();
					for ($i = 0; $i<$titleLen-1;$i++){
						$twoStr = mb_substr($title, $i,2,'utf-8');
						array_push($keywords,$twoStr);
						//$threeStr = mb_substr($title, $i,3,'utf-8');
						//array_push($keywords,$threeStr);
					}

					//Add code to check whether the word is in the keyword category for specific category
					/*
					$validKeywords = array ();
					foreach ($keywords as $keyword){
						$dictitem = $db->Prodict->findOne (array ('word_name'=> $keyword));
						if ($dictitem != null) {
							array_push($validKeywords,$keyword);
						}
					}*/

					$description = $item->description;
			        $content = $item->contentEncoded;
			        $desString = $description;
			        $contentString = $content;
			        $desLen = strlen($desString);
			        $contentLen = strlen($contentString);
			        $text = '';

			        if (isset($value['source_linkReplace'])) {
			        	$linkToReplace = (string)$item->link;
			        	$link = str_replace($value['source_linkReplace'][0], $value['source_linkReplace'][1], $linkToReplace);
			        }else{
			        	$link = (string)$item->link;
			        }

			        if (isset($value['source_tag'])) {

			        	$oh = curl_init($link);
		        		curl_setopt($oh, CURLOPT_RETURNTRANSFER, true);
		        		$originalText = curl_exec($oh);

		        		$opening = strpos($originalText, $value['source_tag'][0]);
		        		$closing = strpos($originalText, $value['source_tag'][1]);
		        		//$text = $originalText;
		        		$text = substr($originalText, $opening,$closing-$opening);

			        }else{
				        if ($desLen < $contentLen) {
				        	$text = (string)$contentString;
				        }else{
				        	$text = (string)$desString;
				        }
			    	}

					if ($postTime < $checkTime){
						//echo "<h2>" . "已经刷新过了" . "</h2>";
					}else{

						foreach ($value['source_industry'] as $key => $industry) {
							$title = $item->title;
							$seed = array (
								'seed_source' => $value['source_name'],
								'seed_sourceLower' => strtolower($value['source_name']),
								'seed_sourceID' => (string)$value['_id'],
								'seed_title' => (string)$title[0],
								'seed_titleLower' => strtolower($title[0]),
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
							$res = $proseed->save ($seed);	
						}
						
						//$timeStamp = ;
						echo "<h2>" . $item->title . "</h2>";
						echo "<h2>" . $titleLen . "</h2>";
						//echo "<h2>" . $postTime. "</h2>";
						//echo "<p>" . $item->description . "</p>";
					}

				}
			
			}


			$value['check_time'] = time();
			$prosource->save($value);
		}



?>