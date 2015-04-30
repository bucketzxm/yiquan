<?php

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
	echo "<h2>" . $item->title . "</h2>";
	echo "<p>" . $item->description . "</p>";
	}

?>