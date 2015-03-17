<?php
require_once 'YqBase.php';
class YqTopic extends YqBase {
	// private $dbname = 'test';
	private $table = 'topic';
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
	// 此函数为 'addtopic' by haozi
	// 使用样例：
	// $soap->newTopic ('second, all','hello','type','title','1.2.3');
	// 参数：$network_type, $owner_name, $room_type, $room_title, $room_labels
	// 类型：number, string, string, string, string(with '.')
	// 如果执行成功，返回1，否则，返回0
	function addTopic($topic_networks, $topic_ownerName, $topic_type, $topic_title, $topic_labels) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}
		
		if (! isset ( $_COOKIE ['user'] ) || $_COOKIE ['user'] != $topic_ownerName) {
			//return - 4;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$topic_postTime = time ();
		$topic_replyCount = 0;
		$m_network = explode ( ',', $topic_networks );
		$m_labels = explode ( ',', $topic_labels );
		
		$data = array (
				"topic_ownerName" => $topic_ownerName,
				"topic_type" => $topic_type,
				"topic_title" => $topic_title,
				"topic_labels" => $m_labels,
				"topic_networks" => $m_network,
				"topic_postTime" => $topic_postTime,
				"topic_replyCount" => $topic_replyCount,
				"topic_likeNames" => array (),
				"topic_dislikeNames" => array () 
		);
		try {
			$result = $this->db->topic->insert ( $data );
			return 1;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	// 此函数为查询话题列表 by haozi
	// 输入
	// 1. 一个由英文','隔开的用户列表字符串
	// 2. network_type，一个字符串
	// 3. 方向，为0即查询大于该时间的所有项，为1为查询小于该时间的30项。
	// 4. unix time
	// 5. 一个由英文','隔开的类型字符串，类型默认为null，即查询所有类型
	function queryTopicByName($topic_user, $topic_networks, $topic_direction, $topic_time, $topic_type = null) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}
		
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		// if ($type != null) {
		$time_int = ( int ) $topic_time;
		$direction_int = ( int ) $topic_direction;
		$userArray = explode ( ',', $topic_user );
		$typeArray = explode ( ',', $topic_type );
		// 生成类似如下的查询语句
		// db.topic.find({$and:[{$or:[{owner_name:"test1"},{owner_name:"test2"}]},{room_type:"system"}]});
		
		$userQueryArray = Array ();
		$typeQueryArray = Array ();
		
		// 处理用户名称，用or
		foreach ( $userArray as $key => $value ) {
			array_push ( $userQueryArray, array (
					'topic_ownerName' => $value 
			) );
		}
		$lastUserQueryArray = array (
				'$or' => $userQueryArray 
		);
		
		// 小于等于某个时间
		if ($direction_int == 1)
			$timeQueryArray = array (
					'topic_postTime' => array (
							'$lt' => $time_int 
					) 
			);
			// 大于某个时间
		else
			$timeQueryArray = array (
					'topic_postTime' => array (
							'$gte' => $time_int 
					) 
			);
			
			// 处理network_type
			// 这边我不会怎么mongodb要怎么判断一个包含数组的字段里面是否含有某个值，所以放在下面处理了
			
		// 处理room_type，用or
		foreach ( $typeArray as $key => $value ) {
			array_push ( $typeQueryArray, array (
					"topic_type" => $value 
			) );
		}
		$lastTypeQueryArray = array (
				'$or' => $typeQueryArray 
		);
		
		// 如果类型是空的话，最终语句里面不加入类型筛选
		if ($topic_type != null)
			$query = array (
					'$and' => array (
							$lastUserQueryArray,
							$lastTypeQueryArray,
							$timeQueryArray 
					) 
			);
		else
			$query = array (
					'$and' => array (
							$lastUserQueryArray,
							$timeQueryArray 
					) 
			);
			
			// 执行查询
		try {
			if ($direction_int == 1)
				$result = $this->db->topic->find ( $query )->sort ( array (
						"topic_postTime" => - 1 
				) )->limit ( 30 );
			else
				$result = $this->db->topic->find ( $query )->sort ( array (
						"topic_postTime" => - 1 
				) );
			$res = array ();
			foreach ( $result as $key => $value ) {
				// 判断network_type
				if (in_array ( $topic_networks, $value ['topic_networks'] )) {
					$user_nickname = $this->db->user->findOne ( array (
							'user_name' => $value ['topic_ownerName'] 
					), array (
							'user_nickname' => 1 
					) );
					$value ['user_nickname'] = $user_nickname ['user_nickname'];
					array_push ( $res, $value );
				}
			}
			return json_encode ( $res );
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	// 按照标题关键字查询
	function queryTopicByKeyword($topic_user, $topic_keyword, $topic_time) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		
		$userArray = explode ( ',', $topic_user );
		$time_int = ( int ) $topic_time;
		
		$userQueryArray = Array ();
		
		// 处理用户名称，用or
		foreach ( $userArray as $key => $value ) {
			array_push ( $userQueryArray, array (
					'topic_ownerName' => $value 
			) );
		}
		
		$lastUserQueryArray = array (
				'$or' => $userQueryArray 
		);
		
		$timeQueryArray = array (
				'topic_postTime' => array (
						'$lt' => $time_int 
				) 
		);
		
		$keyWordQueryArray = array (
				'topic_title' => new MongoRegex ( "/$topic_keyword/" ) 
		);
		
		$query = array (
				'$and' => array (
						$keyWordQueryArray,
						$lastUserQueryArray,
						$timeQueryArray 
				) 
		);
		
		try {
			$result = $this->db->topic->find ( $query )->sort ( array (
					"topic_postTime" => - 1 
			) )->limit ( 30 );
			$res = array ();
			foreach ( $result as $key => $value ) {
				$user_nickname = $this->db->user->findOne ( array (
						'user_name' => $value ['topic_ownerName'] 
				), array (
						'user_nickname' => 1 
				) );
				$value ['user_nickname'] = $user_nickname ['user_nickname'];
				array_push ( $res, $value );
			}
			return json_encode ( $res );
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	// 按照label查询
	function queryTopicByLabel($topic_user, $topic_label, $topic_time) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		
		$time_int = ( int ) $topic_time;
		$userArray = explode ( ',', $topic_user );
		
		$userQueryArray = Array ();
		
		// 处理用户名称，用or
		foreach ( $userArray as $key => $value ) {
			array_push ( $userQueryArray, array (
					'topic_ownerName' => $value 
			) );
		}
		
		$lastUserQueryArray = array (
				'$or' => $userQueryArray 
		);
		
		$timeQueryArray = array (
				'topic_postTime' => array (
						'$lt' => $time_int 
				) 
		);
		
		$query = array (
				'$and' => array (
						$lastUserQueryArray,
						$timeQueryArray 
				) 
		);
		
		try {
			$result = $this->db->topic->find ( $query )->sort ( array (
					"topic_postTime" => - 1 
			) ); // 感觉效率会很低。
			$res = array ();
			$maxCount = 30;
			$count = 0;
			foreach ( $result as $key => $value ) {
				if (in_array ( $topic_label, $value ["topic_labels"], true )) {
					
					$user_nickname = $this->db->user->findOne ( array (
							'user_name' => $value ['topic_ownerName'] 
					), array (
							'user_nickname' => 1 
					) );
					$value ['user_nickname'] = $user_nickname ['user_nickname'];
					
					array_push ( $res, $value );
					if ($count >= $maxCount) {
						break;
					} else {
						$count ++;
					}
				}
			}
			return json_encode ( $res );
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	// 按照发起人查询
	function queryMyTopicByName($topic_ownerName, $topic_time) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$time_int = ( int ) $topic_time;
		try {
			$result = $this->db->topic->find ( array (
					'topic_ownerName' => $topic_ownerName,
					'topic_postTime' => array (
							'$lt' => $time_int 
					) 
			) )->sort ( array (
					"topic_postTime" => - 1 
			) )->limit ( 30 );
			$res = array ();
			foreach ( $result as $key => $value ) {
				$user_nickname = $this->db->user->findOne ( array (
						'user_name' => $value ['topic_ownerName'] 
				), array (
						'user_nickname' => 1 
				) );
				$value ['user_nickname'] = $user_nickname ['user_nickname'];
				array_push ( $res, $value );
			}
			return json_encode ( $res );
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	// 查询用户话题数量
	function countTopicByName($topic_ownerName) {
		try {
			if ($this->yiquan_version == 0) {
				return - 2;
			}
			
			if ($this->checkToken () == 0) {
				return - 3;
			}
			$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
			$count = $this->db->topic->find ( array (
					'topic_ownerName' => $topic_ownerName 
			) )->count ();
			return $count;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	// 按照ID查询
	function queryTopicByRoomID ($topic_roomID) {
		try {
			if ($this->yiquan_version == 0) {
				return - 2;
			}
			
			if ($this->checkToken () == 0) {
				return - 3;
			}
			$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
			$result = $this->db->topic->findOne ( array (
					'_id' => new MongoID ( $topic_roomID ) 
			) );
			$user_nickname = $this->db->user->findOne ( array (
					'user_name' => $result ['topic_ownerName'] 
			), array (
					'user_nickname' => 1 
			) );
			$result ['user_nickname'] = $user_nickname ['user_nickname'];
			return json_encode ( $result );
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	// 按照ID查询话题，无token验证和user-agent验证
	function queryTopicByRoomIDprivate($topic_roomID) {
		try {
			$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
			$result = $this->db->topic->findOne ( array (
					'_id' => new MongoID ( $topic_roomID )
			) );
			$user_nickname = $this->db->user->findOne ( array (
					'user_name' => $result ['topic_ownerName']
			), array (
					'user_nickname' => 1,
					'user_pic' => 1
			) );
			$result ['user_nickname'] = $user_nickname ['user_nickname'];
			$result ['user_pic'] = $user_nickname ['user_pic'];
			
			$t = $user_nickname ['_id'];
			// echo $t;
			$ans2 = $this->db->userProfile->findOne ( array (
					'user_objid' => $t
			) );
			
			$result ['user_gender'] = $ans2 ['profile_gender'];
			$result ['user_city'] = $ans2 ['profile_city'];
			$result ['user_industry'] = $ans2 ['profile_industry'];
			$result ['user_intro'] = $ans2 ['profile_intro'];
			return json_encode ( $result );
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	// 喜欢该话题
	function likeTopic($topic_id, $user_name) {
		if (! isset ( $_COOKIE ['user'] ) || $_COOKIE ['user'] != $user_name) {
			//return - 4;
		}
		try {
			if ($this->yiquan_version == 0) {
				return - 2;
			}
			
			if ($this->checkToken () == 0) {
				return - 3;
			}
			$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
			$where = array (
					"_id" => new MongoID ( $topic_id ) 
			);
			$param = array (
					"\$addToSet" => array (
							'topic_likeNames' => $user_name 
					) 
			);
			
			$result = $this->db->topic->update ( $where, $param );
			return 1;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	// 不喜欢该话题
	function dislikeTopic($topic_id, $user_name) {
		if (! isset ( $_COOKIE ['user'] ) || $_COOKIE ['user'] != $user_name) {
			//return - 4;
		}
		try {
			if ($this->yiquan_version == 0) {
				return - 2;
			}
			
			if ($this->checkToken () == 0) {
				return - 3;
			}
			$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
			$where = array (
					"_id" => new MongoID ( $topic_id ) 
			);
			$param = array (
					"\$addToSet" => array (
							'topic_dislikeNames' => $user_name 
					) 
			);
			
			$result = $this->db->topic->update ( $where, $param );
			return 1;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	// 删除某个话题
	function deleteTopic($topic_id, $topic_networks = null) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		if ($topic_networks == null) {
			try {
				$this->db->topic->remove ( array (
						'_id' => new MongoID ( $topic_id ) 
				) );
				return 1;
			} catch ( Exception $e ) {
				return - 1;
			}
		} else {
			try {
				$where = array (
						'_id' => new MongoID ( $topic_id ) 
				);
				$param = array (
						'$pull' => array (
								'topic_networks' => $topic_networks 
						) 
				);
				
				$this->db->topic->update ( $where, $param );
				return 1;
			} catch ( Exception $e ) {
				return - 1;
			}
		}
	}
	
	// function queryTopicByGroup( $topic_networks ) {
	// try {
	
	// } catch (Exception $e) {
	
	// }
	// }
	
	// function queryTopicByMyGroup() {
	// try {
	
	// } catch (Exception $e) {
	
	// }
	// }
	
	// 统计我所有发出去的话题中收到多少赞
	function countMyTopicAgreeByName($user_name) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		
		try {
			$where = array (
					'topic_likeNames' => $user_name 
			);
			
			$result = $this->db->topic->find ( $where );
			$sum = 0;
			foreach ( $result as $key => $value ) {
				$sum += count ( $value ['topic_likeNames'] );
			}
			
			return $sum;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	// 添加一条回复
	function addReply($topic_id) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		try {
			$where = array (
					'_id' => new MongoID ( $topic_id ) 
			);
			$param = array (
					'$inc' => array (
							'topic_replyCount' => 1 
					) 
			);
			
			$result = $this->db->topic->update ( $where, $param );
			return 1;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	// 记录微信分享
	function logWeixinShare($user_name, $topic_id) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		try {
			
			$res = [ ];
			$res ['user_name'] = $user_name;
			$res ['date'] = new MongoDate ();
			$res ['topic_id'] = new MongoId ( $topic_id );
			$result = $this->db->weixinsharetopiclog->save ( $res );
			return 1;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
}

?>
