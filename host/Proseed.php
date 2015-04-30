<?php
	require_once 'YqBase.php';

	function load_file($url) {
		$ch = curl_init($url);
		#Return http response in string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$xml = simplexml_load_string(curl_exec($ch));
		return $xml;
	}

	$feedurl = 'http://36kr.com/feed';
	$rss = load_file($feedurl);

	foreach ($rss->channel->item as $item) {
	
		$seed = array (
			'seed_title' => $item->title,
			'seed_link' => $item->link,
			'seed_time' =>$item->pubDate

			);
		$aaa = new DateTime ();
		$bbb = $aaa->createFromFormat(RSS,$item->pubDate);

		//$timeStamp = ;
		echo "<h2>" . $item->title . "</h2>";
		echo "<h2>" . $item->link . "</h2>";
		echo "<h2>" . $bbb . "</h2>";
		//echo "<p>" . $item->description . "</p>";
	}

?>