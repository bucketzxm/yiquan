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

		//获得我圈子里的人
		$prouser = new Prouser ();
		$myPros = $prouser->findMyPros ($user_id);

		$seeds = array ();

		//获得我圈子里赞过的话题
		$agreedSeeds = $this->db->Proworth->find (
			array (
				'like_user' => array ('$in' => $myPros),
				'like_time' => array ('$gt' => (time()-86400*2))
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

		$user = $this->db->Prouser->findOne (array ('_id' => new MongoId ($user_id)));
		$sources = $this->db->Prosource->find(array ('source_industry' => $user['user_industry']));
		//获得我的行业关注的媒体的人

		foreach ($sources as $key => $source) {
			$sourceSeeds = $this->db->Proseed->find (
				array (
					'seed_sourceID' => (string)$source['_id'], 
					'seed_time' => array ('$gt' => (time()-86400*1))
					),
				array ('_id'=> 1)
				);
			foreach ($sourceSeeds as $key => $value) {
				
				if (in_array ((string)$value['_id'],$seeds)) {
					# code...
				}else{
					array_push ($seeds,(string)$value['_id']);
				}
			}

		}

		
		$res = array ();
		//计算所有新闻的热度
		foreach ($seeds as $key => $value) {
			$res[$value] = $this->getHotness($user_id,$value);
			
		}
		//排序
		ksort($res);
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
				$item['_id'] = (string)$value1['_id'];
				$item['seed_source'] = $value1['seed_source'];
				$item['seed_sourceID'] = $value1['seed_sourceID'];
				$item['seed_title'] = $value1['seed_title'];
				$item['seed_link'] = $value1['seed_link'];
				$item['seed_time'] = $value1['seed_time'];
				$item['seed_agreeCount'] = $this->db->Proworth->find (array('like_seed' => (string)$value1['_id']))->count ();
			
				array_push ($results,$item);

				//增加用户阅读的记录：

			}

			

		}
		return json_encode($results);

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

		//做好阅读记录
		$readLog = $this->db->Proread->findOne (array ('seed_id' => $seed_id,'user_id'=>$user_id));
		if ($readLog == null) {
			$data = array (
				'seed_id' => $seed_id,
				'user_id' => $user_id,
				'read_time' => time()
				);
			$this->db->Proread->save ($data);
		}

		//找到seed_id的内容
		$seed = $this->db->Proseed->find (array ('_id'=> new MongoId($seed_id)));
		foreach ($seed as $key => $value) {
			$text = array ();
			$text['seed_text'] = $value['seed_text'];	
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
			//获得我圈子里的人
			$prouser = new Prouser ();
			$myPros = $prouser->findMyPros ($user_id);

			//找到这个新闻
			$seed = $this->db->Proseed->findOne (array ('_id' => new MongoId($seed_id)));

			//找到我圈子里的人对这个话题的赞
			$agrees = $this->db->Proworth->find (
				array (
					'like_seed'=>$seed_id,
					'like_user'=> array ('$in'=>$myPros)
					)
				);

			//计算初始温度的当前热度
			$hotness = $this->calculateHotness(100,$seed['seed_time']);

			//计算所有点赞的热度
			foreach ($agrees as $key => $value) {
				$incrementalHotness = $this->calculateHotness ($value['like_weight'],$value['like_time']);
				$hotness += $incrementalHotness;
			}

			//计算这个新闻的关键字在我值得一读的匹配程度
			//找到我所有agree的话题
			$myAgrees = $this->db->Proread->find (
				array (
					'user_id'=> $user_id,
					'read_time' => array ('$gt' => (time()-86400*30))
					)
				);
			$matchWords =  array ();
			foreach ($myAgrees as $agree) {

				$cursor = $this->db->Proseed->findOne (array ('_id' => $agree['seed_id']));
				foreach ($cursor['seed_keywords'] as $word){
					array_push ($matchWords,$word);
				}
			}
			$keywordCount = array_count_values($matchWords);

			$matchCount = 0;
			foreach ($seed['seed_keywords'] as $keyword) {
				$matchCount += $keywordCount[$keyword];
			}
			$hotness += $matchCount/10;

			return $hotness;
			
		}catch (Exception $e){
			return $e;
		}
		
	}
	function calculateHotness($weight,$time){
		$weight = (int) $weight;
		$time = (int) $time;
		return $weight * exp(-0.05 * ((time() - $time)/3600));


	}

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

		$existWorth = $this->db->Proworth->findOne (array ('like_user'=>$user_id,'like_seed'=>$seed_id));
		if ($existWorth == null) {
			$cursor = $this->db->Proseed->findOne (array ('_id'=>$seed_id),array('seed_sourceID' => 1));
			$data = array (
				'like_user' => $user_id,
				'like_seed' => $seed_id,
				'like_weight' => $user['current']['user_weight'],
				'like_seedSource' => $cursor['seed_sourceID'],
				'like_comment' => $like_comment,
				'like_time' => time()
				);

			$this->db->Proworth->save ($data);
		}
		
		return 1;

	}

	function addLikeComment($user_id,$seed_id,$like_comment){
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
		$this->db->Proworth->save($like);
		return 1;


	}


	function queryMyLikedSeeds ($user_id){

		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkQuoteToken () != 1) {
			return - 3;
		}
		
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}

		$cursor = $this->db->Proworth->find(array ('like_user'=> $user_id));
		$myLikedSeeds = array ();
		foreach ($cursor as $key => $value) {
			$seed = $this->db->Proseed->find(array ('_id'=> new MongoId($value['like_seed'])));

			foreach ($seed as $key => $item) {
				$item['like_comment'] = $value['like_comment'];
				$item['seed_agreeCount'] = $this->db->Proworth->find (array('like_seed' => (string)$item['_id']))->count ();
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

		$result = array ();
		$likes = $this->db->Proworth->find (array ('like_seed'=> $seed_id,'like_user'=> array ('$in'=>$myPros),'like_comment' => array ('$ne' => '')));
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

}
?>
