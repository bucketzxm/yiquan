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
			
			/*
			foreach ($sources as $key => $source) {
				$source['check_time'] = time() - 1000000000;
				$db->Prosource->save($source);
			}
			
		
			//修改行业字典
			$dicts = $db->Prosystem->find(array('para_name' => 'industry_dict'));
			foreach ($dicts as $industry => $dict) {
				$theDict = $dict['industry_words']; 
				$newDict = array();
				foreach ($theDict as $key => $word) {
					$newDict[$word] = $word;
				}
				$dict['industry_words'] = $newDict;
				$db->Prosystem->save($dict);
				echo '<h2>'.$dict['industry_name'].'</h2>';
			}
			*/

			$dicts = $db->Prosystem->find(array('para_name' => 'industry_dict'));
			foreach ($dicts as $industry => $dict) {
				$industryName = $dict['industry_name'];
				$seeds = $db->Proseed->find();
				$count = 0;
				foreach ($seeds as $key => $value) {
					if (isset($value['seed_industry'][$industryName])) {
						$count ++;
					}
					if (isset($value['seed_industryParsed'][$industryName])) {
						$count ++;
					}
				}
				echo '<h3>'.$industryName.$count.'</h3>';
			}

			$count = array();
			$seeds = $db->Proseed->find();
			foreach ($seeds as $key => $value) {
				$industryArray = array();
				foreach ($value['seed_industry'] as $key1 => $value1) {
					if (isset($industryArray[$value1])) {
						$industryArray[$value1] ++;
					}else{
						$industryArray[$value1] = 1;
					}
				}

				if (isset($value['seed_industryParsed'])) {
					foreach ($value['seed_industryParsed'] as $key2 => $value2) {
						if (isset($industryArray[$value2])) {
							$industryArray[$value2] ++;
						}else{
							$industryArray[$value2] = 1;
						}
					}
				}


			
				$totalCount = count($industryArray);

				if (isset($count[$totalCount])) {
					$count[$totalCount] ++;
				}else{
					$count[$totalCount] = 1;
				}
				
				$value['seed_industryCount'] = $totalCount;
				$this->db->Proseed->save ($value);	
			}

			foreach ($count as $key3 => $value3) {
				echo '<h3>'.$key3.':'.$value3.'</3>';
			}

		








?>