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
			$seeds = $db->Proseed->find(array('seed_hotness' => array('$gt' => 1)));
			foreach ($seeds as $key => $seed) {
				$para = $db->Prosystem->findOne(array('para_name'=>"user_count"));
				if (isset ($para[$seed['seed_industry']])) {
					$speed = $para[$seed['seed_industry']];
				}else{
					$speed = 0;
				}
				$seed['seed_hotness'] = $seed['seed_hotness'] * exp(-0.05) * ((time() - $seed['seed_hotnessTime'])/3600));
				foreach($seed['seed_industryHotness'] as $industry => $hotness){
					$seed['seed_industryHotness'][$industry] = $hotness * exp (-0.05) * ((time() - $seed['seed_hotnessTime'])/3600));
				}
				$seed['seed_hotnessTime'] = time();
				$db->Proseed->save($seed);
			}


			$words = $db->Prowords->find();
			foreach ($words as $key1 => $word) {
				if (floor((time() - $word['word_checkTime'])/86400) >0) {
					$newHotness = $word['word_hotness'] * exp(-0.05) * floor((time() - $word['word_checkTime'])/86400);	
					if ($word['word_type'] == 'default') {
						if ($newHotness < 100) {
							$word['word_hotness'] = 100;	
						}else{
							$word['word_hotness'] = $newHotness;
						}
					}else{
						$word['word_hotness'] = $newHotness;
					}
					
					$word['word_checkTime'] = time();
					$db->Prowords->save($word);
				}
			}

			
		



?>