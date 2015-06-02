<?php
require_once 'YqBase.php';

    
class Proseed extends YqBase {
	private $collection;
	// static $conn; // 连接
	// function __construct() {
	// try {
	// if (self::$conn == null) {
	// self::$conn = connectDb ();
	// }
	// self::$conn->connect ();
	// } catch ( Exception $e ) {
	// self::$conn = connectDb ();
	// }
	// while ( 1 ) {
	// $this->db = self::$conn->selectDB ( $this->dbname );
	// if ($this->user != '' && $this->pwd != '') {
	// $fa = $this->db->authenticate ( $this->user, $this->pwd );
	// if ($fa ['ok'] == 0) {
	// sleep ( 1 );
	// continue;
	// }
	// }
	// break;
	// }
	// if (! isset ( $_SESSION )) {
	// session_start ();
	// }
	// $this->yiquan_version = $this->checkagent ();
	// }
	// function __destruct() {
	// self::$conn->close ();
	// }
	// 此类用于 message 表
	// private $dbname = 'test';
	// private $table = 'topic';
	
	// message的属性:
	// sender_id
	// receiver_id
	// life
	// labels
	// type
	// postTime
	// title
	
	function queryMySeedsByName($user_id,$time){
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkQuoteToken () != 1) {
			return - 3;
		}
		
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}

		try {
			/*
			//获得我圈子里的人
			$prouser = new Prouser ();
			$myPros = $prouser->findMyPros ($user_id);

			$seeds = array ();

			//获得我圈子里赞过的话题
			$agreedSeeds = $this->db->Proworth->find (
				array (
					'like_user' => array ('$in' => $myPros),
					'like_time' => array ('$gt' => (time()-86400*3))
					),
				array ('_id' => 1)
				);
			foreach ($agreedSeeds as $key => $value) {
				if (in_array ((string)$value['_id'],$seeds)) {
					# code...
				}else{
					array_push ($seeds,(string)$value['_id']);
				}
			}
			*/
			$user = $this->db->Prouser->findOne (array ('_id' => new MongoId ($user_id)));
			//$sources = $this->db->Prosource->find(array ('source_industry' => $user['current']['user_industry']));
			//获得我的行业关注的媒体的人

			//foreach ($sources as $key => $source) {
				$sourceSeeds = $this->db->Proseed->find (
					array (
						'$or' => array(
							array ('seed_industry' => $user['current']['user_industry']),
							array ('seed_industry' => $user['current']['user_interestA']),
							array ('seed_industry' => $user['current']['user_interestB'])
							), 
						'seed_time' => array ('$gt' => (time()-86400*3))
						),
					array ('_id'=> 1)
				);
				/*
				foreach ($sourceSeeds as $key => $value) {
					
					if (in_array ((string)$value['_id'],$seeds)) {
						# code...
					}else{
						array_push ($seeds,(string)$value['_id']);
					}
				}*/

			//}

			$unreadSeeds = array ();
			foreach ($sourceSeeds as $key => $seed) {
				$cursor = $this->db->Proread->findOne(array ('seed_id' => $seed,'user_id'=>$user_id));
				//if ($cursor == null) {
					array_push($unreadSeeds,(string)$seed['_id']);
				//}
			}
			
			$res = array ();
			$res1 = array ();
			//计算所有新闻的热度
			foreach ($unreadSeeds as $key => $value) {
				$stats = $this->getHotness($user_id,$value);
				//return $stats;
				$res[$value] = $stats['priority'];
				$res1[$value] = $stats['priorityType'];
				
			}
			//排序
			arsort($res);
			//删选
			$topRes = array_slice($res,0,30);

			$results = array ();
			foreach ($topRes as $key => $value) {
				$selectedSeed = $this->db->Proseed->find(
					array ('_id'=> new MongoId($key)
					)
					);
				
				foreach ($selectedSeed as $key1 => $value1) {
					$item = array ();
					$item['_id'] = $value1['_id'];
					$item['seed_source'] = $value1['seed_source'];
					$item['seed_sourceID'] = $value1['seed_sourceID'];
					$item['seed_title'] = $value1['seed_title'];
					$item['seed_link'] = $value1['seed_link'];
					$item['seed_time'] = $value1['seed_time'];
					$item['seed_industry'] = $value1['seed_industry'];
					$item['seed_agreeCount'] = $value1['seed_agreeCount'];
					$item['seed_hotness'] = $value;
					$item['seed_priorityType'] = $res1[$key];
				
					array_push ($results,$item);

					//增加用户阅读的记录：
					$readLog = $this->db->Proread->findOne (array ('seed_id' => (string)$value1['_id'],'user_id'=>$user_id,'read_type'=>'0'));
					if ($readLog == null) {
						$data = array (
							'seed_id' => (string)$value1['_id'],
							'user_id' => $user_id,
							'read_time' => time(),
							'read_type' => '0'

							);
						$this->db->Proread->save ($data);
					}

				}

				

			}
			return json_encode($results);
		}catch (Exception $e){
			return $e;
		}
	}

function queryMySeedsByKeyword($user_id,$time,$keyword){
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkQuoteToken () != 1) {
			return - 3;
		}
		
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}

		try {
			$keyword = strtolower($keyword);

			$user = $this->db->Prouser->findOne (array ('_id' => new MongoId ($user_id)));
			
			array_push($user['user_searchWords'],$keyword);
			if (count($user['user_searchWords'])>10) {
				unset($user['user_searchWords'][0]);
			}
			
			$this->db->Prouser->save($user);



			$time = (int)$time;
			$sourceSeeds = $this->db->Proseed->find (
				array (

					'$or' => array(
							array ('seed_industry' => $user['current']['user_industry']),
							array ('seed_industry' => $user['current']['user_interestA']),
							array ('seed_industry' => $user['current']['user_interestB'])
							), 
					'seed_time' => array ('$lt' => $time),
					'$or' => array (
						array('seed_titleLower' => new MongoRegex ("/$keyword/")),
						array('seed_sourceLower' => new MongoRegex ("/$keyword/"))
						)
					)
			)->sort(array('seed_time'=> -1))->limit(30);
			/*
			$unreadSeeds = array ();
			foreach ($sourceSeeds as $key => $seed) {
				//$cursor = $this->db->Proread->findOne(array ('seed_id' => $seed,'user_id'=>$user_id,'read_type'=>'0'));
				//if ($cursor == null) {
					array_push($unreadSeeds,(string)$seed['_id']);
				//}
			}
			
			$res = array ();
			$res1 = array ();
			//计算所有新闻的热度
			foreach ($unreadSeeds as $key => $value) {
				$stats = $this->getHotness($user_id,$value);
				//return $stats;
				$res[$value] = $stats['priority'];
				$res1[$value] = $stats['priorityType'];
				
			}
			//排序
			arsort($res);
			//删选
			$topRes = array_slice($res,0,30);
			*/

			$results = array ();
			/*
			foreach ($sourceSeeds as $key => $value) {
				$selectedSeed = $this->db->Proseed->find(
					array ('_id'=> new MongoId($key)
					)
					);
				*/
				foreach ($sourceSeeds as $key1 => $value1) {
					$item = array ();
					$item['_id'] = $value1['_id'];
					$item['seed_source'] = $value1['seed_source'];
					$item['seed_sourceID'] = $value1['seed_sourceID'];
					$item['seed_title'] = $value1['seed_title'];
					$item['seed_link'] = $value1['seed_link'];
					$item['seed_industry'] = $value1['seed_industry'];
					$item['seed_time'] = $value1['seed_time'];
					$item['seed_agreeCount'] = $value1['seed_agreeCount'];
					//$item['seed_hotness'] = $value;
					//$item['seed_priorityType'] = $res1[$key];
				
					array_push ($results,$item);

					//增加用户阅读的记录：
					$readLog = $this->db->Proread->findOne (array ('seed_id' => (string)$value1['_id'],'user_id'=>$user_id,'read_type'=>'0'));
					if ($readLog == null) {
						$data = array (
							'seed_id' => (string)$value1['_id'],
							'user_id' => $user_id,
							'read_time' => time(),
							'read_type' => '0'

							);
						$this->db->Proread->save ($data);
					}

				}
			return json_encode($results);
		}catch (Exception $e){
			return $e;
		}
	}

	function getSeedText ($seed_id,$user_id){
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkQuoteToken () != 1) {
			return - 3;
		}
		
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}		
		
		$readLog = $this->db->Proread->findOne (array ('seed_id' => $seed_id,'user_id'=>$user_id));
		if ($readLog != null) {
			$readLog['read_type'] = '1';
			/*
			$data = array (
				'seed_id' => $seed_id,
				'user_id' => $user_id,
				'read_time' => time(),
				'read_type' => '1'

				);*/
			$this->db->Proread->save ($readLog);
		}

		//找到seed_id的内容
		$seeds = $this->db->Proseed->find(array('_id'=> new MongoId($seed_id)));
		foreach ($seeds as $key => $seed) {
			/*
			foreach ($seed['seed_keywords'] as $key => $word) {
				if (isset($user['user_keywords'][$word])) {
					$user['user_keywords'][$word] += 1;
				}else{
					$user['user_keywords'][$word] = 1;
				}
			}
			$this->db->Prouser->save($user);
			*/
			$text = array ();
			$text['seed_text'] = $seed['seed_text'];	

		}
		return json_encode($text);
	}

	function getHotness ($user_id, $seed_id){
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkQuoteToken () != 1) {
			return - 3;
		}
		
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}

		try {
			/*
			//获得我圈子里的人
			$prouser = new Prouser ();
			$myPros = $prouser->findMyPros ($user_id);
			*/
			//找到这个新闻
			$seed = $this->db->Proseed->findOne (array ('_id' => new MongoId($seed_id)));

			//找到我圈子里的人对这个话题的赞
			/*
			$agrees = $this->db->Proworth->find (
				array (
					'like_seed'=>$seed_id,
					'like_user'=> array ('$in'=>$myPros)
					)
				);*/

			$user = $this->db->Prouser->findOne(array ('_id'=> new MongoId($user_id)));
			/*
			//计算单词点赞的放大因子
			$para = $this->db->Prosystem->findOne(array('para_name'=>"user_count"));

			//计算所有点赞的热度
			$seed['seed_hotness'] = $seed['seed_hotness'] * exp(-($para[$seed['seed_industry']]*0.0001) * ((time() - $seed['seed_hotnessTime'])/3600));
			$seed['seed_hotnessTime'] = time();
			$this->db->Proseed->save($seed);
			*/
			/*
			$agreeness = 0;
			foreach ($agrees as $key => $value) {
				$incrementalHotness = $this->calculateHotness ($value['like_weight'],$value['like_time']);
				$agreeness += $incrementalHotness*$amp;
			}*/

			//计算这个新闻的关键字在我值得一读的匹配程度
			//找到我所有读过的话题
			
	
			$matchCount = 0;
			$dismatchCount = 0;
			$matchness = 0;

			//计算和已经读过的文章的匹配数
			$myAgrees = $this->db->Proread->find (
				array (
					'user_id'=> $user_id,
					//'read_time' => array ('$gt' => (time()-86400*30)),
					'read_type' => '1'
					)
				)->sort(array('read_time'=> -1))->limit(500);
			$seedIDs =  array ();
			if ($myAgrees != null) {
				foreach ($myAgrees as $agree) {
					array_push($seedIDs,new MongoId($agree['seed_id']));
				}
			}
			
			
			$news = $this->db->Proseed->find (array ('_id' => array ('$in' =>$seedIDs)));
			foreach ($news as $value) {
			
				$keywordCount = 0;
				foreach ($seed['seed_keywords'] as $keyword) {
					if (in_array($keyword,$value['seed_keywords'])) {
						$keywordCount += 1;
					}
				}
				$matchCount += $keywordCount/count($value['seed_keywords']);

			}

			//更新关键词匹配的值
			$seedCount = count($seedIDs);
			$matchness += $matchCount*500/$seedCount;

			/*
			//计算和不想读的文章的反匹配度
			$myDisagrees = $this->db->Proread->find (
				array (
					'user_id'=> $user_id,
					//'read_time' => array ('$gt' => (time()-86400*30)),
					'read_type' => '0'
					)
				)->sort(array('read_time'=> -1))->limit(500);
			$disSeedIDs =  array ();
			if ($myDisagrees != null) {
				foreach ($myDisagrees as $disagree) {
					array_push($disSeedIDs,new MongoId($disagree['seed_id']));
				}
			}
			
			
			$disNews = $this->db->Proseed->find (array ('_id' => array ('$in' =>$disSeedIDs)));
			foreach ($disNews as $disValue) {
			
				$keywordCount = 0;
				foreach ($seed['seed_keywords'] as $keyword) {
					if (in_array($keyword,$disValue['seed_keywords'])) {
						$keywordCount += 1;
					}
				}
				$dismatchCount -= $keywordCount/count($disValue['seed_keywords']);

			}*/


			//更新关键词匹配的值
			$disSeedCount = count($disSeedIDs);
			$matchness += $dismatchCount*500/$disSeedCount;



			//计算和自己的关键词的疲惫度
			foreach ($user['user_keywords'] as $word) {
				$pos = strpos($seed['seed_titleLower'], $word);
				if ($pos !== false) {
					$matchness += 5;
				}
			}	
			

			//匹配搜索记录的相关性
			foreach ($user['user_searchWords'] as $searchWord) {
				$pos = strpos($seed['seed_titleLower'], $searchWord);
				if ($pos !== false) {
					$matchness += 5;
				}
			}


			$hotness = $seed['seed_hotness'];
			$priority = $hotness+$matchness;
			
			$hotcent = $hotness / $priority;
			//$agreecent = $agreeness / $priority;
			$matchcent = $matchness / $priority;
			$priorityType = '';
			if ($matchcent > 0.3){
				$priorityType = "猜您喜欢";
			}else{
				$priorityType = "圈内热门";
			}
			
			$result = array ();
			$result['priority'] = $priority;
			$result['priorityType'] = $priorityType;


			return $result;
			
		}catch (Exception $e){
			return $e;
		}
		
	}

	function calculateHotness($weight,$time){
		$weight = (int) $weight;
		$time = (int) $time;
		return $weight * exp(-0.05 * ((time() - $time)/3600));
	}
#

	function likeSeed($user_id,$seed_id,$like_comment){
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkQuoteToken () != 1) {
			return - 3;
		}
		
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}

		//找到这个user
		$user = $this->db->Prouser->findOne (array ('_id' => new MongoId ($user_id)),array ('current' => 1));
		$cursor = $this->db->Proseed->findOne (array ('_id'=> new MongoId($seed_id)));
		$existWorth = $this->db->Proworth->findOne (array ('like_user'=>$user_id,'like_seed'=>$seed_id));
		$source = $this->db->Prosource->findOne (array ('_id' => new MongoId($cursor['seed_sourceID'])));


		if ($existWorth == null) {
			
			$data = array (
				'like_user' => $user_id,
				'like_seed' => $seed_id,
				'like_weight' => $user['current']['user_weight'],
				'like_industry' => $user['current']['user_industry'],
				'like_seedSource' => $cursor['seed_sourceID'],
				'like_comment' => $like_comment,
				'like_time' => time()
				);

			$this->db->Proworth->save ($data);

			if (in_array($user['current']['user_industry'],$source['source_industry'])) {
				$cursor['seed_hotness'] += (int)$user['current']['user_weight'];
				if(isset($cursor['seed_agreeCount'])){
					$cursor['seed_agreeCount'] ++;
				}else{
					$cursor['seed_agreeCount'] = 1;
				}
				//$cursor['seed_hotnessTime'] = time();
				$this->db->Proseed->save($cursor);
			}
		}
		
		return 1;

	}

	function addLikeComment($user_id,$seed_id,$like_comment,$like_public){
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkQuoteToken () != 1) {
			return - 3;
		}
		
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}

		$like = $this->db->Proworth->findOne(array ('like_user'=>$user_id,'like_seed'=>$seed_id));
		$like['like_comment'] = $like_comment;
		$like['like_public'] =$like_public;
		$this->db->Proworth->save($like);
		return 1;


	}


	function queryMyLikedSeeds ($user_id,$time){

		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkQuoteToken () != 1) {
			return - 3;
		}
		
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}
		$time = (int)$time;
		$cursor = $this->db->Proworth->find(array ('like_user'=> $user_id,'like_time'=> array('$lt' => $time)))->sort(array('like_time'=> -1))->limit(30);
		$myLikedSeeds = array ();
		foreach ($cursor as $key => $value) {
			$seed = $this->db->Proseed->find(array ('_id'=> new MongoId($value['like_seed'])));

			foreach ($seed as $key => $item) {
				$item['like_comment'] = $value['like_comment'];
				//$item['seed_agreeCount'] = $this->db->Proworth->find (array('like_seed' => (string)$item['_id']))->count ();
				array_push ($myLikedSeeds, $item);
			}
		}
		return json_encode($myLikedSeeds);
	}

	function queryMyLikedSeedsByKeyword ($user_id,$time,$keyword){

		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkQuoteToken () != 1) {
			return - 3;
		}
		
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}
		$keyword = strtolower($keyword);
		
		$user = $this->db->Prouser->findOne (array ('_id' => new MongoId ($user_id)));

		array_push($user['user_searchWords'],$keyword);
		if (count($user['user_searchWords'])>10) {
			unset($user['user_searchWords'][0]);
		}
		$this->db->Prouser->save($user);

		$time = (int)$time;
		$cursor = $this->db->Proworth->find(array ('like_user'=> $user_id,'like_time'=> array('$lt' => $time)))->sort(array('seed_time'=> -1));
		$myLikedSeeds = array ();
		foreach ($cursor as $key => $value) {
			$seed = $this->db->Proseed->find(array ('_id'=> new MongoId($value['like_seed']),
				'$or'=> array(
					array('seed_titleLower'=> new MongoRegex("/$keyword/")),
					array('seed_sourceLower'=> new MongoRegex("/$keyword/"))
					)
				));

			foreach ($seed as $key => $item) {

					$item['like_comment'] = $value['like_comment'];
					//$item['seed_agreeCount'] = $this->db->Proworth->find (array('like_seed' => (string)$item['_id']))->count ();
					array_push ($myLikedSeeds, $item);					
			}
		}
		return json_encode($myLikedSeeds);
	}

	function querySeedLikes ($seed_id,$user_id){
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkQuoteToken () != 1) {
			return - 3;
		}
		
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}
		$prouser = new Prouser ();
		$myPros = $prouser->findMyPros ($user_id);
		$seed = $this->db->Proseed->findOne(array('_id' => new MongoId($seed_id)));
		$result = array ();
		$likes = $this->db->Proworth->find (array ('like_seed'=> $seed_id,'like_industry'=> $seed['seed_industry'],'like_comment' => array ('$ne' => ''),'like_public' => '1'));
		foreach ($likes as $key => $value) {
			$users = $this->db->Prouser->find (array ('_id' => new MongoId($value['like_user'])));
			foreach ($users as $key => $user) {
				$value['user_id'] = (string)$user['_id'];
				$value['user_name'] = $user['current']['user_name'];
				$value['user_company'] = $user['current']['user_company'];
				$value['user_title'] = $user['current']['user_title'];
			}

			array_push ($result,$value);
		}
		return json_encode($result);

	}

	function queryMediaList($user_id){
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkQuoteToken () != 1) {
			return - 3;
		}
		
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}

		$user = $this->db->Prouser->findOne (array ('_id'=> new MongoId($user_id)));
		$cursor = $this->db->Prosource->find(array ('$or' => array(
							array ('source_industry' => $user['current']['user_industry']),
							array ('source_industry' => $user['current']['user_interestA']),
							array ('source_industry' => $user['current']['user_interestB'])
							)));
		$mediaList = array();
		foreach ($cursor as $key => $value) {
			array_push ($mediaList, $value);
		}
		return json_encode($mediaList);
	}

	function querySystemMessage($user_id){
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkQuoteToken () != 1) {
			return - 3;
		}
		
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}

		$cursor = $this->db->Promessage->find (array ('message_type' => 'system'))->sort(array ('message_postTime'=> -1))->limit (30);
		$systemMessages = array();
		foreach ($cursor as $key => $value) {
			array_push($systemMessages,$value);
		}
		return json_encode($systemMessages);
	}

	function queryFeedbackMessages($user_id){
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkQuoteToken () != 1) {
			return - 3;
		}
		
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}

		$cursor = $this->db->Promessage->find (
			array(
				'$and'=> array(
					array ('message_type'=>'feedback'), 
					array('$or'=> array(
						array ('message_senderID' => $user_id),
						array ('message_receiverID'=> $user_id)
						)
						)
					)
				)
			)->sort (array ('message_postTime'=> -1))->limit (30);
		$feedbackMessages = array();
		foreach ($cursor as $key => $value){
			if ($value['message_senderID'] != 'system') {
				$user = $this->db->Prouser->findOne(array('_id'=> new MongoId($value['message_senderID'])));
				$value['sender_name'] = $user['current']['user_name'];	
			}else{
				$value['sender_name'] = '值得一读团队';
			}
			
			array_push ($feedbackMessages,$value);
		}
		return json_encode($feedbackMessages);
	}

	function addSeedMessage($message_senderID, $message_receiverID, $message_type, $message_title) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}

		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $message_senderID) {
			return - 4;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$message_postTime = time ();

		
	try {		
		$data = array (
				'message_senderID' => $message_senderID,
				'message_receiverID' => $message_receiverID,
				'message_type' => $message_type,
				'message_title' => $message_title,
				'message_postTime' => $message_postTime
		);

		$this->db->Promessage->save($data);
                
		return 1;

		
		} catch ( Exception $e ) {
			return - 1;
		}
	}

	function checkMessageUpdate($user_id){
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}

		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );

		$user = $this->db->Prouser->findOne(array('_id' => new MongoId($user_id)));
		$result = 0;
		$unreadSystemMessage = $this->db->Promessage->find(array ('message_type'=>'system','message_postTime' => array ('$gt'=> $user['user_messageCheckTime'])))->count();
		if ($unreadSystemMessage >0) {
			$result += 1;
		}
		$unreadPersonalMessage = $this->db->Promessage->find(array ('message_receiverID'=>$user_id,'message_postTime' => array ('$gt'=> $user['user_messageCheckTime'])))->count();
		if ($unreadPersonalMessage > 0) {
			$result += 2;
		}

		$user['user_messageCheckTime'] = time();
		$this->db->Prouser->save ($user);
		return $result;

	}

}
?>
