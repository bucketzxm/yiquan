<?php
	require_once 'YqBase.php';


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

			//构建行业字典
			$industryDict = array();

			$dicts = $db->Prosystem->find(array('para_name' => 'industry_dict'));
			foreach ($dicts as $industry => $dict) {
				$industryDict[$industry] = $dict;
			}

			$protext = new Protext;
			//遍历所有的Seed
			$seeds = $db->Proseed->find();
			foreach ($seeds as $key => $seed) {

				$parserResult = $protext->parseText($seed['seed_text']);

				$seed['seed_textIndustryWords'] = $parserResult[0];
				$seed['seed_industryParsed'] = $parserResult[1];

				$db->Proseed->save($seed);

				echo '<h3>'.$seed['seed_title'].', '.$seed['seed_industryParsed'].', '.$seed['seed_textIndustryWords'].'</h3>';
			}
			
		



?>