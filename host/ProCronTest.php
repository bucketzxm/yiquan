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

			$count = $db->Prosystem->count(array('cron_message' => 'cron'));
			if ($count <5) {
				$data = array(
					'cron_time' = time(),
					'cron_message' = time (),
					);	
				$db->Prosystem->save($data);
			}
			
			

?>