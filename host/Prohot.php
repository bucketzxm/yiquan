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
				if ($para[$seed['seed_industry']] == null) {
					$speed = 0;
				}else{
					$speed = $para[$seed['seed_industry']];
				}
				$seed['seed_hotness'] = $seed['seed_hotness'] * exp(-($speed*0.0001) * ((time() - $seed['seed_hotnessTime'])/3600));
				$seed['seed_hotnessTime'] = time();
				$db->Proseed->save($seed);
			}
			
		



?>