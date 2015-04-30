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
			$host = 'yiquandb.oneto-tech.com';
			$port = '27017';
			$user = 'test';
			$pwd = 'yiquanTodo';
			 
			$mongoClient = new MongoClient("mongodb://{$host}:{$port}",array(
			    		'username'=>$user,
			    		'password'=>$pwd,
			    		'db'=>$dbname
			));
			$db=$mongoClient->yiquan;
			$collection=$db->Proseed;

		$feedurl = 'http://36kr.com/feed';
		$rss = load_file($feedurl);


	foreach ($rss->channel->item as $item) {
	
		$aaa = new DateTime ();
		$bbb = $aaa->createFromFormat("D, d M Y H:i:s O",$item->pubDate);

		$seed = array (
			'seed_title' => $item->title,
			'seed_link' => $item->link,
			'seed_time' =>$bbb->getTimestamp()
		);
	
		$collection->save ($seed);

		//$timeStamp = ;
		echo "<h2>" . $item->title . "</h2>";
		echo "<h2>" . $item->link . "</h2>";
		echo "<h2>" . $bbb->getTimestamp() . "</h2>";
		//echo "<p>" . $item->description . "</p>";
	}

?>