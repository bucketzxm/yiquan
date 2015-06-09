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
			$sources = $db->Prosource->find();
			foreach ($sources as $key => $source) {
				$source['check_time'] = time() - 1000000000;
				$db->Prosource->save($source);
			}
			
		
			//修改行业字典
			$dicts = $db->Prosystem->find(array('para_name' => 'industry_dict'));
			foreach ($dicts as $industry => $dict) {
				$theDict = $dict['industry_words']; 
				foreach ($theDict as $key => $word) {
					$theDict[$word] = $word;
					unset($theDict[$word]);
				}
				$db->Prosystem->save($dict);
			}



?>