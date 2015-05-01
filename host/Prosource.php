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


				$description = $item->description;
		        $content = $item->contentEncoded;
		        $desString = $description[0];
		        $contentString = $content[0];
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
						'seed_time' =>$postTime
					);
				
					$proseed->save ($seed);

					//$timeStamp = ;
					echo "<h2>" . $item->title . "</h2>";
					echo "<h2>" . $item->link . "</h2>";
					echo "<h2>" . $postTime. "</h2>";
					//echo "<p>" . $item->description . "</p>";
				}

			}
			$value['check_time'] = time();
			$prosource->save($value);
		}



?>