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
		$currentTime = time();
		$timeMonthAgo = $currentTime - 86400*30;
		$db->Proseed->remove (array ('seed_time' => array ('$lt' => $timeMonthAgo)));

		foreach ($sources as $key => $value) {
			echo "<h2>" . $value['source_name'] . "</h2>";
			$checkTime = $value['check_time'];
			$feedurl = $value['source_rssURL'];
			
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
		
			foreach ($rss->channel->item as $item) {
				
				$aaa = new DateTime ();
				$postTime = $aaa->createFromFormat($value['time_format'],$item->pubDate)->getTimestamp();

				$title = $item->title;
				$title = str_replace("？", "", $title);
				$title = str_replace("！", "", $title);
				$title = str_replace("，", "", $title);
				$title = str_replace("。", "", $title);
				$title = str_replace("“", "", $title);
				$title = str_replace("”", "", $title);
				$title = str_replace("【", "", $title);
				$title = str_replace("】", "", $title);
				$title = str_replace("：", "", $title);
				$title = str_replace(" ", "", $title);
				//Split keywords
				$titleLen = mb_strlen($title,'utf-8');
				$keywords = array ();
				for ($i = 0; $i<$titleLen;$i++){
					$twoStr = mb_substr($title, $i,2,'utf-8');
					array_push($keywords,$twoStr);
					$threeStr = mb_substr($title, $i,3,'utf-8');
					array_push($keywords,$threeStr);
				}

				//Add code to check whether the word is in the keyword category for specific category
				$validKeywords = array ();
				foreach ($keywords as $keyword){
					$dictitem = $db->Prodict->findOne (array ('word_name'=> $keyword));
					if ($dictitem != null) {
						array_push($validKeywords,$keyword);
					}
				}

				$description = $item->description;
		        $content = $item->contentEncoded;
		        $desString = $description;
		        $contentString = $content;
		        $desLen = strlen($desString);
		        $contentLen = strlen($contentString);
		        $text = '';

		        if ($desLen < $contentLen) {
		        	$text = $contentString;
		        }else{
		        	$text = $desString;
		        }

				if ($postTime < $checkTime){
					//echo "<h2>" . "已经刷新过了" . "</h2>";
				}else{

					$seed = array (
						'seed_source' => $value['source_name'],
						'seed_sourceID' => (string)$value['_id'],
						'seed_title' => $item->title,
						'seed_link' => $item->link,
						'seed_text' => $text,
						'seed_time' =>$postTime,
						'seed_keywords' =>$validKeywords,
						'seed_hotness' => 100,
						'seed_hotnessTime' => time()
					);
				
					var_dump($keywords);
					$res = $proseed->save ($seed);
					


					//$timeStamp = ;
					echo "<h2>" . $item->title . "</h2>";
					echo "<h2>" . $titleLen . "</h2>";
					//echo "<h2>" . $postTime. "</h2>";
					//echo "<p>" . $item->description . "</p>";
				}

			}
			$value['check_time'] = time();
			$prosource->save($value);
		}



?>