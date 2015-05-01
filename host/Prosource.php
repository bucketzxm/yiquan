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
			


		foreach ($sources as $key => $value) {
			$checkTime = $value['check_time'];
			$feedurl = $value['source_rssURL'];
			
	        $feeds = file_get_contents($feedurl);
	        $feeds = str_replace("<content:encoded>","<contentEncoded>",$feeds);
	        $feeds = str_replace("</content:encoded>","</contentEncoded>",$feeds);
	        $rss = simplexml_load_string($feeds,'SimpleXMLElement', LIBXML_NOCDATA);



			//$rss = load_file($feedurl);
		
			foreach ($rss->channel->item as $item) {
			
				$aaa = new DateTime ();
				$postTime = $aaa->createFromFormat("D, d M Y H:i:s O",$item->pubDate)->getTimestamp();

				$title = $item->title;
				//Split keywords
				$titleLen = strlen($title);
				$keywords = array ();
				for ($i = 0; $i<$titleLen;$i++){
					$twoStr = substr($title, $i,2);
					array_push($keywords,$twoStr);
					$threeStr = substr($title, $i,3);
					array_push($keywords,$threeStr);
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
					echo "<h2>" . "已经刷新过了" . "</h2>";
				}else{

					$seed = array (
						'seed_source' => $rss->channel->title,
						'seed_title' => $item->title,
						'seed_link' => $item->link,
						'seed_text' => $text,
						'seed_time' =>$postTime,
						'seed_keywords' =>$keywords
					);
				
					
					$res = $proseed->save ($seed);
					var_dump($res);


					//$timeStamp = ;
					echo "<h2>" . $item->title . "</h2>";
					//echo "<h2>" . $titleLen . "</h2>";
					echo "<h2>" . $postTime. "</h2>";
					//echo "<p>" . $item->description . "</p>";
				}

			}
			$value['check_time'] = time();
			$prosource->save($value);
		}



?>