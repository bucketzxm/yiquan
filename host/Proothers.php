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
			
			/*
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
					if (isset($value['seed_industry'])) {
						if (in_array($industryName,$value['seed_industry'])) {
							$count ++;
						}	
					}
				}
				echo '<h3>'.$industryName.$count.'</h3>';
			}

	
			$seeds = $db->Proseed->find();
			foreach ($seeds as $key => $value) {
				
				$value['seed_industryCount'] = count($value['seed_industry']);
				$db->Proseed->save ($value);	

				echo '<h3>'.$value['seed_title'].':'.implode(';', $value['seed_industry']).'</3>';
			}

			/*
			foreach ($count as $key3 => $value3) {
				echo '<h3>'.$key3.':'.$value3.'</3>';
			}
			*/
			

			/*
			foreach ($dicts as $key => $industry) {
				if (isset($industry['search_words'])) {
					foreach ($industry['search_words'] as $key1 => $word) {
					$item = $db->Prowords->findOne(array('word_name' => $word));
						if ($item == null) {
							$data = array(
								'word_name' => $word,
								'word_industry' => $industry['industry_name'],
								'word_hotness' => 100,
								'word_checkTime' => time()
							);
							$db->Prowords->save($data);
						}
					}	
				}
				
			}
			*/
			/*
			foreach ($sources as $key => $source) {
				$source['source_status'] = 'active';
				$db->Prosource->save($source);
			}
			*/








?>