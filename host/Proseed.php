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
			$prosystem = $db->prosystem;
			$proseed = $db->Proseed;
			$checkTimePara = $prosystem->findOne(array ('para_name' => "checkTime"));


		$feedurl = 'http://36kr.com/feed';
		$rss = load_file($feedurl);


	foreach ($rss->channel->item as $item) {
	
		$aaa = new DateTime ();
		$postTime = $aaa->createFromFormat("D, d M Y H:i:s O",$item->pubDate)->getTimestamp();

		if ($postTime < $checkTimePara['check_Time']){
			echo "<h2>" . "已经刷新过了" . "</h2>";
		}else{
			$seed = array (
				'seed_source' => $rss->channel->title,
				'seed_title' => $item->title,
				'seed_link' => $item->link,
				'seed_time' =>$postTime
			);
		
			$proseed->save ($seed);

			//$timeStamp = ;
			echo "<h2>" . $item->title . "</h2>";
			echo "<h2>" . $item->link . "</h2>";
			echo "<h2>" . $bbb->getTimestamp() . "</h2>";
			//echo "<p>" . $item->description . "</p>";
		}

	}

	$checkTimePara['check_Time'] = time();
	$prosystem->save($checkTimePara);

?>