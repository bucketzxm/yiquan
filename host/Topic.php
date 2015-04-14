<?php
require_once 'YqBase.php';

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
class Topic extends YqBase {
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
	function addTopic($topic_networks, $topic_ownerName, $topic_type, $topic_title, $topic_labels, $topic_detailText) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}
		
		if (! isset ( $_COOKIE ['user'] ) || $_COOKIE ['user'] != $topic_ownerName) {
			// return - 4;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$topic_postTime = time ();
		$topic_replyCount = 0;
		$m_network = explode ( ',', $topic_networks );
		$m_labels = explode ( ',', $topic_labels );
		$detailHtmlText = '<html xmlns=http://www.w3.org/1999/xhtml><head><meta http-equiv=Content-Type content="text/html;charset=utf-8"><link href="http://7xid8v.com2.z0.glb.qiniucdn.com/style.css" rel="stylesheet"></head><body>' . $topic_detailText . '</body></html>';
		
		$data = array (
				"topic_ownerName" => $topic_ownerName,
				"topic_type" => $topic_type,
				"topic_title" => $topic_title,
				"topic_labels" => $m_labels,
				"topic_networks" => $m_network,
				"topic_postTime" => $topic_postTime,
				"topic_replyCount" => $topic_replyCount,
				"topic_likeNames" => array (),
				"topic_dislikeNames" => array (),
				"topic_followNames" => array (),
				"topic_archiveCounts" => 0,
				"topic_detailname" => '',
				"topic_detail" => '' 
		);
		if (($topic_detailText != nil) && ($topic_detailText != '')) {
			if ($this->QiniuUploadhtml_url ( $data, $detailHtmlText ) == 1) {
				try {
					array_push ( $data ['topic_labels'], "长话题" );
					$result = $this->db->topic->insert ( $data );
					return 1;
				} catch ( Exception $e ) {
					return - 1;
				}
			}
		} else {
			try {
				$result = $this->db->topic->insert ( $data );
				return 1;
			} catch ( Exception $e ) {
				return - 1;
			}
		}
	}
	
    function addTopicInGroup ($topic_group, $topic_ownerName, $topic_type, $topic_title, $topic_labels, $topic_detailText) {
        if ($this->yiquan_version == 0) {
            return - 2;
        }
        
        if ($this->checkToken () == 0) {
            return - 3;
        }
        
        if (! isset ( $_COOKIE ['user'] ) || $_COOKIE ['user'] != $topic_ownerName) {
            // return - 4;
        }
        $this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
        $topic_postTime = time ();
        $topic_replyCount = 0;
        $m_labels = explode ( ',', $topic_labels );
        $detailHtmlText = '<html xmlns=http://www.w3.org/1999/xhtml><head><meta http-equiv=Content-Type content="text/html;charset=utf-8"><link href="http://7xid8v.com2.z0.glb.qiniucdn.com/style.css" rel="stylesheet"></head><body>' . $topic_detailText . '</body></html>';
        if ($topic_group != 'second'){
            $group = new MongoId ($topic_gropu);
        }else{
            $group = $topic_group;
        }
        
        $data = array (
                       "topic_ownerName" => $topic_ownerName,
                       "topic_type" => $topic_type,
                       "topic_title" => $topic_title,
                       "topic_labels" => $m_labels,
                       "topic_group" => $group,
                       "topic_postTime" => $topic_postTime,
                       "topic_replyCount" => $topic_replyCount,
                       "topic_likeNames" => array (),
                       "topic_dislikeNames" => array (),
                       "topic_followNames" => array (),
                       "topic_archiveCounts" => 0,
                       "topic_detailname" => '',
                       "topic_detail" => ''
                       );
        if (($topic_detailText != nil) && ($topic_detailText != '')) {
            if ($this->QiniuUploadhtml_url ( $data, $detailHtmlText ) == 1) {
                try {
                    array_push ( $data ['topic_labels'], "长话题" );
                    $result = $this->db->topic->insert ( $data );
                    return 1;
                } catch ( Exception $e ) {
                    return - 1;
                }
            }
        } else {
            try {
                $result = $this->db->topic->insert ( $data );
                return 1;
            } catch ( Exception $e ) {
                return - 1;
            }
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
					),
					'topic_networks' => array (
							'$ne' => [ ] 
					) 
			);
			
			// 执行查询
		try {
			if ($direction_int == 1)
				$result = $this->db->topic->find ( $query )->sort ( array (
						"topic_postTime" => - 1 
				) ); // ->limit ( 30 );
			else
				$result = $this->db->topic->find ( $query )->sort ( array (
						"topic_postTime" => - 1 
				) );
			$count = 0;
			$res = array ();
			foreach ( $result as $key => $value ) {
				// 判断network_type
				//if (in_array ( $topic_networks, $value ['topic_networks'] )) {
					
					// 判断是否有被用户Block
					if (in_array ( $_COOKIE ['user'], $value ['topic_dislikeNames'] )) {
					} else {
						
						$user_nickname = $this->db->user->findOne ( array (
								'user_name' => $value ['topic_ownerName'] 
						), array (
								'user_nickname' => 1 
						) );
						$value ['user_nickname'] = $user_nickname ['user_nickname'];
						array_push ( $res, $value );
						$count ++;
					}
				//}
				if ($count == 30) {
					break;
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
				),
				'topic_networks' => array (
						'$ne' => [ ] 
				) 
		);
		
		try {
			$result = $this->db->topic->find ( $query )->sort ( array (
					"topic_postTime" => - 1 
			) ); // ->limit ( 30 );
			$count = 0;
			$res = array ();
			foreach ( $result as $key => $value ) {
				// 判断是否有被用户Block
				if (in_array ( $_COOKIE ['user'], $value ['topic_dislikeNames'] )) {
				} else {
					$user_nickname = $this->db->user->findOne ( array (
							'user_name' => $value ['topic_ownerName'] 
					), array (
							'user_nickname' => 1 
					) );
					$value ['user_nickname'] = $user_nickname ['user_nickname'];
					array_push ( $res, $value );
					$count ++;
				}
				if ($count == 30) {
					break;
				}
			}
			return json_encode ( $res );
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
    function queryTopicByKeywordInAllGroup ($user_name, $topic_keyword, $topic_time) {
        if ($this->yiquan_version == 0) {
            return - 2;
        }
        
        if ($this->checkToken () == 0) {
            return - 3;
        }
        $this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
        $user = new User();
        $secondList = $user->listAllFriendsByName($user_name);
        $secondListWithMe = $user_name . ',' . $secondList;
        $userArray = explode ( ',', $secondListWithMe );
        
        $cursor = $this->db->user->findOne(array ('user_name' => $user_name));
        $groups = $cursor['user_groups'];
        
        $time_int = ( int ) $topic_time;
        
        $lastUserQueryArray = array (
                                     '$or' => array (
                                        array (
                                            'topic_group' => array ('$in' => $groups)
                                                     ),
                                        array (
                                            'topic_ownerName' => array ('$in' => $userArray),
                                            'topic_group' => 'second'
                                                     )
                                     )
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
                                         ),
                        'topic_networks' => array (
                                                   '$ne' => [ ]
                                                   ) 
                        );
        
        try {
            $result = $this->db->topic->find ( $query )->sort ( array (
                                                                       "topic_postTime" => - 1 
                                                                       ) ); // ->limit ( 30 );
            $count = 0;
            $res = array ();
            foreach ( $result as $key => $value ) {
                // 判断是否有被用户Block
                if (in_array ( $_COOKIE ['user'], $value ['topic_dislikeNames'] )) {
                } else {
                    $user_nickname = $this->db->user->findOne ( array (
                                                                       'user_name' => $value ['topic_ownerName'] 
                                                                       ), array (
                                                                                 'user_nickname' => 1 
                                                                                 ) );
                    $value ['user_nickname'] = $user_nickname ['user_nickname'];
                    array_push ( $res, $value );
                    $count ++;
                }
                if ($count == 30) {
                    break;
                }
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
				),
				'topic_networks' => array (
						'$ne' => [ ] 
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
					// 判断是否有被用户Block
					if (in_array ( $_COOKIE ['user'], $value ['topic_dislikeNames'] )) {
					} else {
						$user_nickname = $this->db->user->findOne ( array (
								'user_name' => $value ['topic_ownerName'] 
						), array (
								'user_nickname' => 1 
						) );
						$value ['user_nickname'] = $user_nickname ['user_nickname'];
						
						array_push ( $res, $value );
					}
				}
				if ($count >= $maxCount) {
					break;
				} else {
					$count ++;
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
					),
					'topic_group' => array (
							'$ne' => ''
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
    
    function queryTopicByAllGroup ($user_name, $topic_time){
        if ($this->yiquan_version == 0) {
            return - 2;
        }
        
        if ($this->checkToken () == 0) {
            return - 3;
        }
        $this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
        $time_int = ( int ) $topic_time;
        
        $user = $this->db->user->findOne (array ( 'user_name' => $user_name));
        $groups = $user['user_groups'];

        
        $userclass = new User ();
        $firstList = $userclass->listFirstFriendsByName ($user_name);
        $firstListWithMe = $user_name . ',' . $firstList;
        $first_array = explode (',',$firstListWithMe);

        
        try {
            $cursor = $this->db->topic->find (array (
                                           '$or' => array (
                                                           array (
                                                                  'topic_group' => array ('$in' => $groups),
                                                                  'topic_postTime' => array ('$lt' => $time_int)
                                                        ),
                                                           array (
                                                                'topic_ownerName' => array ('$in' => $first_array),
                                                                  'topic_postTime' => array ('$lt' => $time_int),
                                                                  'topic_group' => 'second'
                                                           )
                                                  )
                                           ))->sort (array( 'topic_postTime' => -1))->limit (30);
            
            $res = array ();
            foreach ( $cursor as $key => $value ) {
                $user_nickname = $this->db->user->findOne ( array (
                                                                   'user_name' => $value ['topic_ownerName']
                                                                   ), array (
                                                                             'user_nickname' => 1
                                                                             ) );
                $value ['user_nickname'] = $user_nickname ['user_nickname'];
                array_push ( $res, $value );
            }
            return json_encode ($res);
        }catch (Exception $e){
            return $e;
        }
        
        
    }
    
    function queryHighlightedTopicByGroup($group_user, $group_id) {
        if ($this->yiquan_version == 0) {
            return - 2;
        }
        
        if ($this->checkToken () == 0) {
            return - 3;
        }
        $this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
        $time_int = ( int ) $topic_time;
        try {
            
                //QueryTopic BY ID
                $result = $this->db->topic->find ( array (
                                                          
 
                                                          'topic_group' => new MongoId ($group_id),
                                                          'topic_highlighted' => 1;
                                                          
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
            return $e;
        }
    }

    
    // 按照组别查询
    function queryTopicByGroup($group_user, $group_id,$topic_time) {
        if ($this->yiquan_version == 0) {
            return - 2;
        }
        
        if ($this->checkToken () == 0) {
            return - 3;
        }
        $this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
        $time_int = ( int ) $topic_time;
        try {
            
            if ($group_id == 'first'){
                $user = new User ();
                $firstList = $user->listFirstFriendsByName ($group_user);
                $firstListWithMe = $group_user . ',' . $firstList;
                $res = $this->queryTopicByName ($firstListWithMe, 'second', '1', $topic_time);
                return $res;
                
            }else if ($group_id == 'second'){
                $user = new User ();
                $secondList = $user->listAllFriendsByName ($group_user);
                $secondListWithMe = $group_user . ',' . $secondList;
                $res = $this->queryTopicByName ($secondListWithMe, 'second', '1', $topic_time);
                return $res;
                
            }else if ($group_id == 'all'){
                $res = $this->queryTopicByAllGroup ($group_user, $topic_time);
                return $res;
                
            }else{
            
                //QueryTopic BY ID
                $result = $this->db->topic->find ( array (
                                                          
                                                          'topic_postTime' => array (
                                                                                     '$lt' => $time_int
                                                                                     ),
                                                          'topic_group' => new MongoId ($group_id),
                                                          'topic_highlighted' => 0;
                                                          
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
            }
        } catch ( Exception $e ) {
            return $e;
        }
    }
    
	// 查询我收藏的话题
	function queryMyArchiveByName($archive_ownerName, $topic_time) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$time_int = ( int ) $topic_time;
		$cursor = $this->db->user->findOne ( array (
				"user_name" => $archive_ownerName 
		) );
		$archive = $cursor ['user_archiveTopic'];
		// $idArray = array ();
		// foreach ( $archive as $topicID ) {
		// array_push ( $idArray, new MongoId ( $topicID ) );
		// }
		try {
			$result = $this->db->topic->find ( array (
					'_id' => array (
							'$in' => $archive 
					),
					'topic_postTime' => array (
							'$lt' => $time_int 
					),
					'topic_group' => array (
							'$ne' => ''
					) 
			) )->sort ( array (
					"topic_postTime" => - 1 
			) )->limit ( 30 );
			$res = array ();
			foreach ( $result as $value ) {
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
					'topic_ownerName' => $topic_ownerName,
					'topic_group' => array (
							'$ne' => ''
					) 
			) )->count ();
			return $count;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	// 按照ID查询
	function queryTopicByRoomID($topic_roomID) {
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
			if (($result == nil) || ($result ['topic_networks'] == '')) {
				return 2;
			} else {
				$user_nickname = $this->db->user->findOne ( array (
						'user_name' => $result ['topic_ownerName'] 
				), array (
						'user_nickname' => 1 
				) );
				$result ['user_nickname'] = $user_nickname ['user_nickname'];
				return json_encode ( $result );
			}
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
					'user_smallavatar' => 1 
			) );
			$result ['user_nickname'] = $user_nickname ['user_nickname'];
			$result ['user_smallavatar'] = $user_nickname ['user_smallavatar'];
			
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
    
    function highlightTopic ($topic_id,$user_name){
        if ($this->yiquan_version == 0) {
            return - 2;
        }
        
        if ($this->checkToken () == 0) {
            return - 3;
        }
        if (! isset ( $_COOKIE ['user'] ) || $_COOKIE ['user'] != $user_name) {
            return - 4;
        }
        
        $this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
        
        $topic = $this->db->topic->findOne ( array ( '_id' => new MongoId ($topic_id)));
        $group = $this->db->group->findOne ( array ('_id' => $topic['topic_group']));
        if ($user_name != $group['group_founder']){
            return 3;
        }
        try {
            
            $topic['topic_highlighted'] = 1;
            $this->db->topic->save ($topic);
            return 1;
            
            
        }catch (Exception $e){
            return $e;
        }
        
        
    }
    
    
	// 喜欢该话题
	function likeTopic($topic_id, $user_name) {
		if (! isset ( $_COOKIE ['user'] ) || $_COOKIE ['user'] != $user_name) {
			return - 4;
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
			return - 4;
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
			
			$where1 = array (
					"user_name" => $user_name 
			);
			$param1 = array (
					"\$addToSet" => array (
							'user_blockTopic' => new MongoID ( $topic_id ) 
					) 
			);
			
			$result1 = $this->db->user->update ( $where1, $param1 );
			
			return 1;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	// 关注某话题
	function followTopic($topic_id, $user_name) {
		if (! isset ( $_COOKIE ['user'] ) || $_COOKIE ['user'] != $user_name) {
			return - 4;
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
							'topic_followNames' => $user_name 
					) 
			);
			
			$result = $this->db->topic->update ( $where, $param );
			
			$where1 = array (
					"user_name" => $user_name 
			);
			$param1 = array (
					"\$addToSet" => array (
							'user_followTopic' => new MongoID ( $topic_id ) 
					) 
			);
			
			$result1 = $this->db->user->update ( $where1, $param1 );
			
			return 1;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	// 取消关注某话题
	function disfollowTopic($topic_id, $user_name) {
		if (! isset ( $_COOKIE ['user'] ) || $_COOKIE ['user'] != $user_name) {
			return - 4;
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
					"\$pull" => array (
							'topic_followNames' => $user_name 
					) 
			);
			
			$result = $this->db->topic->update ( $where, $param );
			
			$where1 = array (
					"user_name" => $user_name 
			);
			$param1 = array (
					"\$pull" => array (
							'user_followTopic' => new MongoID ( $topic_id ) 
					) 
			);
			
			$result1 = $this->db->user->update ( $where1, $param1 );
			return 1;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	// 收藏某话题
	function archiveTopic($topic_id, $user_name) {
		if (! isset ( $_COOKIE ['user'] ) || $_COOKIE ['user'] != $user_name) {
			return - 4;
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
					'$inc' => array (
							'topic_archiveCounts' => 1 
					) 
			);
			
			$result = $this->db->topic->update ( $where, $param );
			
			$where1 = array (
					"user_name" => $user_name 
			);
			$param1 = array (
					"\$addToSet" => array (
							'user_archiveTopic' => new MongoID ( $topic_id ) 
					) 
			);
			
			$result1 = $this->db->user->update ( $where1, $param1 );
			
			return 1;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	// 取消收藏某话题
	function unarchiveTopic($topic_id, $user_name) {
		if (! isset ( $_COOKIE ['user'] ) || $_COOKIE ['user'] != $user_name) {
			return - 4;
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
					'$inc' => array (
							'topic_archiveCounts' => - 1 
					) 
			);
			
			$result = $this->db->topic->update ( $where, $param );
			
			$where1 = array (
					"user_name" => $user_name 
			);
			$param1 = array (
					"\$pull" => array (
							'user_archiveTopic' => new MongoID ( $topic_id ) 
					) 
			);
			
			$result1 = $this->db->user->update ( $where1, $param1 );
			
			return 1;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
	
	// 删除某个话题
	function deleteTopic($topic_id, $user_name) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}
        if (! isset ( $_COOKIE ['user'] ) || $_COOKIE ['user'] != $user_name) {
            return - 4;
        }
        
        $this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		
        $topic = $this->db->topic->findOne(array ('_id' => new MongoID ( $topic_id )));
        $group = $this->db->group->findOne(array ('_id' => $topic['topic_group']));
        
        if (($topic['topic_ownerName'] != $user_name) && ($group['group_founder'] != $user_name) ){
            return 3;
        }

        try {
            
            $topic['topic_group'] = '';
            
            $this->db->topic->save ($topic);
            return 1;
        } catch ( Exception $e ) {
            return - 1;
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
	function addRichTopic($username, $passwordHash, $topic_title, $topic_type, $topic_labels, $topic_networks, $html) {
		try {
			if ($this->checkUsernameAndPassword ( $username, $passwordHash ) == 0)
				return 0;
			
			$topic_postTime = time ();
			$topic_replyCount = 0;
			$m_network = explode ( ',', $topic_networks );
			$m_labels = explode ( ',', $topic_labels );
			$html = base64_decode ( $html );
			
			$data = array (
					"topic_ownerName" => $username,
					"topic_type" => $topic_type,
					"topic_title" => $topic_title,
					"topic_labels" => $m_labels,
					"topic_networks" => $m_network,
					"topic_postTime" => $topic_postTime,
					"topic_replyCount" => $topic_replyCount,
					"topic_likeNames" => array (),
					"topic_dislikeNames" => array (),
					"topic_followNames" => array (),
					"topic_archiveCounts" => 0,
					"topic_detailname" => '',
					"topic_detail" => '' 
			);
		} catch ( Exception $e ) {
			return $e;
		}
		
		if ($this->QiniuUploadhtml_url ( $data, $html ) == 1) {
			try {
				array_push ( $data ['topic_labels'], "长话题" );
				$result = $this->db->topic->insert ( $data );
				return 1;
			} catch ( Exception $e ) {
				return - 1;
			}
		}
	}
	protected function QiniuUploadhtml_url(&$arr, $html) {
		$auth = new Auth ( $this->qiniuAK, $this->qiniuSK );
		$bucket = 'yiquan-topics';
		$uploadMgr = new UploadManager ();
		$bucketMgr = new BucketManager ( $auth );
		$token = $auth->uploadToken ( $bucket );
		
		list ( $ret, $err ) = $uploadMgr->put ( $token, null, $html );
		// var_dump($ret);
		// var_dump($err);
		if ($err == null) {
			$arr ['topic_detailname'] = $ret ['key'];
			$arr ['topic_detail'] = $this->topicsbucketUrl . '/' . $ret ['key'];
		} else {
			return $err;
		}
		return 1;
	}
}
// $a = new Topic ();
// var_dump($a->addRichTopic ( 'abc2', '11', 'wuliaohuati','', 'qiuzhidao,qiujiaowang', '', '<html xmlns=http://www.w3.org/1999/xhtml><head><meta http-equiv=Content-Type content="text/html;charset=utf-8"><link href="http://7xid8v.com2.z0.glb.qiniucdn.com/style.css" rel="stylesheet"></head><body><div>这个问题其实是相当复杂的。从 Google 的发布会提出 Material Design 的设计规范以来，许多人都为 Material Design 所惊艳到。但事实上，大多数应用都无法很好及时跟进这一设计标准。这一点，尤其是在国内，显得更为的突出。我自己实践 Material Design 也有了一段时间，我觉得至少有三点原因。</div><div><br></div><div>一、Material Design 设计语言非常复杂，学习成本高，实现难度大。</div><div><br></div><div>于 Material Design 复杂的设计语言相比，我敢说，学习难度比你跟进 iOS 的平面化的开发标准要困难十倍以上。Material Design 并不是使用 Google 提供的这些控件、图片设计出来的东西就是 Material Design 了。Material Design 的核心是一个高度抽象化的设计逻辑是对真实事物的逻辑层面的模拟，比起 iOS 以前那种单纯视觉上的拟物比起来，这是一种非常高层次的拟物概念，理解起来确实比较费事。</div><div><br></div><div>举个例子来说，</div><div>当你有一个如同这样的页面布局。</div><div><br></div><div>这样的页面布局下，当用户手指从下向上滚动屏幕的时候，我们先想象一下，这个布局应该如何跟随调整？通常情况下，我们会选择整页内容一起向上滚动。但实际上，这种方法并不是很正确。</div><div><br></div><div>我们仔细观察这个布局，去掉状态栏，这个页面也有五个不同的“色块”组成的独立元素。他们分别是 用来选择操作的顶栏Toolbar，然后是 Featured Image，然后是 Topic，下方的 Detail，和一个标记状态的 Button。</div><div><br></div><div>然后我们把这五个东西想象成五张真实存在的纸片，他们堆叠在一起类似于下图这样的：</div><div><br></div><div>当你移动下面的 Detail 页的时候，其他元素其实应该有着不同的相对运动才对，而不是整体上移。比如 Featured Image 不动，下面的纸片从它上方运动覆盖移动过去，而推到顶时，Topic 页可以成为这页的标题，而下方的 Detail 也继续移动。这个设计来自于 Google I/O 2014 App 的设计。（此应用源代码可以到 GitHub 下载）</div><div><br></div><div>这样的设计逻辑并不是来自于哪个现成的模板，而是针对你应用的不同布局不同考虑的，甚至是像素级的细节考虑，对设计者的要求很高，对程序实现的要求同样也很高。这是 Material Design 中许多细腻的 “激动人心的细节” 背后深藏的设计逻辑。更何况，我只能说，我举的这个例子也是 Material Design 复杂语言的一个很小的部分而已。</div><div><br></div><div>二、Material Design 的设备兼容性不够好。</div><div><br></div><div>Material Design 的设备兼容性是比较差的，当然比起当年 Holo 设计在 Android 2.x 上的完全不兼容不同，Material Design 是可以做到 4.x 的半兼容的。所谓半兼容，指的是使用 Google 提供的控件和兼容包，可以基本显示。但是比如状态栏的颜色的设置、各个控件的 elevation 阴影、selectableBackground 的按钮响应动画都会失效。</div><div><br></div><div>（如上图这样的 Elevation 效果，在 Android 4.x 上会被直接“压扁”显示）</div><div><br></div><div>而与 Android 2.x 更是完全不兼容了。开发者即使愿意忍痛让 4.x 用户看一个不完整的设计，也不能满足 2.x 用户的兼容需求。</div><div><br></div><div>虽然这种苛刻的兼容需求对于大多数应用来说都不是很有关系，但是比如像 QQ（最低兼容至 Android 1.6）、微信（最低兼容至 Android 2.2）这样的应用，他们的市场的广度迫使他们不能尝试这样的事情，毕竟在中国，使用 Android 2.x 的手机的用户依然还是有相当一部分的。</div><div><br></div><div>在这样的背景下，Material Design 在 QQ、微信、淘宝 这样的应用上，短时间是不可能实现的。他们处于兼容性的考量，需要使用系统最基础的控件以及利用这些控件组合的自定义控件，而不能去使用高版本才拥有的特性。这样的应用又卡、又慢、又丑也是有一定客观原因的。</div><div><br></div><div>三、Material Design 的一些其他劣势。</div><div><br></div><div>当然上述的两点原因并不是一些大厂商不使用 Material Design，宁可用自己设计的极丑无比的界面的唯一原因。还有一些细碎的原因，也是左右这个设计普及受阻的砝码。比如像阿里、腾讯、百度这样的企业，他们并不是设计驱动的，而是商业驱动的。如果跟进 Material Design，势必会影响他们的一些商业利益。</div><div><br></div><div>比如说，页面的逻辑会受到牵制，他们再也无法放一些活动、广告的按钮放在用户最易点击的地方，颜色也不能总是整片整片的大红大绿。一些页面的访问频次会随着逻辑层级变深而降低。这也是当时 微信 5.2 测试版刚开始试图 Holo 化又叫停的重要原因。</div><div><br></div><div>（微信 5.2 内测版截图，图源网络）</div><div>更有一些想法是希望 Android 和 iOS 能拥有一样的 UI，使得用户降低学习成本，更快上手，更好赚钱。所以在 Android 上出现底栏两层皮、三层皮什么的就是出于这样的想法。</div><div><br></div><div>在目前中国市场上，用户对设计的品味还处于一个比较初级的阶段，对设计几乎没有要求。而你就算有要求，你为了使用应用也愿意去做这样的妥协。开发者做跟进花费的代价远小于他们的收益。恐怕这是许多公司宁可设计一套奇丑无比的 UI，也不愿意跟进 Material Design 的核心原因。</div></body></html>' ));
?>
