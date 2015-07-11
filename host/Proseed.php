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
	function checkIndustry($user_industries, $seed_industries){
		foreach ($user_industries as $industry) {
			if (isset($seed_industries[$industry])) {
				return true;
			}
		}
		return false;
	}


	function queryMySeedsByName($user_id,$time){
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkQuoteToken () != 1) {
			return $this->checkQuoteToken();
		}
		
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}

		try {

			$user = $this->db->Prouser->findOne (array ('_id' => new MongoId ($user_id)));
			
			//计算商业和生活的比例
			$businessRatio = $user['user_seedRead']['business_read'] / $user['user_seedRead']['total_read']; 
			if ($businessRatio>0.7) {
				$businessRatio = 0.7;
			}else if ($businessRatio < 0.3){
				$businessRatio = 0.3;
			}

			$businessSeedQuota = floor(10 * $businessRatio);
			$lifeSeedQuota = 10 - $businessSeedQuota;
			/////找到我读过的话题

			//$sources = $this->db->Prosource->find(array ('source_industry' => $user['current']['user_industry']));
			//获得我的行业关注的媒体的人
			$readSeeds = array ();
			//获得用户的行业信息
			/*
			$userIndustries = array();
			array_push($userIndustries, $user['current']['user_industry']);
			array_push($userIndustries, $user['current']['user_interestA']);
			array_push($userIndustries, $user['current']['user_interestB']);
			*/
			$readSeedsCursor = $this->db->Proread->find(array (
				'user_id'=>$user_id,
				'read_time'=> array ('$gt' => (time()-86400*3))
				));
			foreach ($readSeedsCursor as $readSeedKey => $readSeedValue) {
				//额外判断两个东西是否重合
				//if ($this->checkIndustry($userIndustries,$readSeedValue['seed_industry'])) {
					array_push($readSeeds,new MongoId($readSeedValue['seed_id']));
				//}
				
			}

				$myAgrees = $this->db->Proread->find (
				array (
					'user_id'=> $user_id,
					//'read_time' => array ('$gt' => (time()-86400*30)),
					'read_type' => '1'
					)
				)->sort(array('read_time'=> -1))->limit(50);


				$seedIDs =  array ();
				if ($myAgrees != null) {
					foreach ($myAgrees as $agree) {
						array_push($seedIDs,new MongoId($agree['seed_id']));
					}
				}

				$news = $this->db->Proseed->find (array ('_id' => array ('$in' =>$seedIDs)));

				$agreeWords = array();
				$agreeLabels = array();
				foreach ($news as $agreeNews) {
					foreach ($agreeNews['seed_keywords'] as $agreeKeyword) {
						if (isset($agreeWords[$agreeKeyword])) {
							$agreeWords[$agreeKeyword] ++;
						}else{
							$agreeWords[$agreeKeyword] = 1;
						}
					}
					foreach ($agreeNews['seed_industry'] as $agreeLabel) {
						if (isset($agreeLabels[$agreeLabel])) {
							$agreeLabels[$agreeLabel] ++;
						}else{
							$agreeLabels[$agreeLabel] = 1;
						}
					}
				}

				$myDisagrees = $this->db->Proread->find (
				array (
					'user_id'=> $user_id,
					//'read_time' => array ('$gt' => (time()-86400*30)),
					'read_type' => '0'
					)
				)->sort(array('read_time'=> -1))->limit(150);


				$disSeedIDs =  array ();
				if ($myDisagrees != null) {
					foreach ($myDisagrees as $disagree) {
						array_push($disSeedIDs,new MongoId($disagree['seed_id']));
					}
				}

				$disNews = $this->db->Proseed->find (array ('_id' => array ('$in' =>$disSeedIDs)));

				$disAgreeWords = array ();
				$disAgreeLabels = array ();
				foreach ($disNews as $disAgreeNews) {
					foreach ($disAgreeNews['seed_keywords'] as $disAgreeKeyword) {
						if (isset($disAgreeWords[$disAgreeKeyword])) {
							$disAgreeWords[$disAgreeKeyword] ++;
						}else{
							$disAgreeWords[$disAgreeKeyword] = 1;
						}
					}

					foreach ($disAgreeNews['seed_industry'] as $disAgreeLabel) {
						if (isset($disAgreeLabels[$disAgreeLabel])) {
							$disAgreeLabels[$disAgreeLabel] ++;
						}else{
							$disAgreeLabels[$disAgreeLabel] = 1;
						}
					}
				}


			//获取User Business Groups
			$userBusinessGroups = array ();
			foreach ($user['user_industryInterested'] as $keymmg => $userBusinessGroup) {
				array_push($userBusinessGroups, new MongoId($userBusinessGroup));
			}
			$businessGroups = $this->db->ProMediaGroup->find(array('_id' => array('$in' => $userBusinessGroups)));
			$industryList = array ();
			foreach ($businessGroups as $keyMG => $group) {
				/*
				foreach ($group['mediaGroup_sourceList'] as $keyS => $source) {
					if (!isset($sourceList[$source['source_id']])) {
						$sourceList[$source['source_id']] = $source['source_id'];
					}
				}
				*/
				array_push($industryList, $group['mediaGroup_title']);

			}
			//array_push($sourceList, "5542329709f778a5068b457f");

			//获得商业的Seeds
			$sourceBusiniessSeeds = $this->getSelectedSeeds($industryList,$readSeeds);

			$res = array ();
			$res1 = array ();
			//计算所有新闻的热度
			foreach ($sourceBusinessSeeds as $sourceSeedKey => $sourceBusinessSeed) {
				$stats = $this->getHotness($user,$sourceBusinessSeed,$agreeWords,count($seedIDs),$disAgreeWords,count($disSeedIDs),$agreeLabels,$disAgreeLabels);
				//return $stats;
				$res[(string)$sourceBusinessSeed['_id']] = $stats['priority'];
				$res1[(string)$sourceBusinessSeed['_id']] = $stats['priorityType'];
				
			}
			//排序
			arsort($res);
			//删选
			$topBusinessRes = array_slice($res,0,$businessSeedQuota);



			//获取User Life Groups
			$userLifeGroups = array ();
			foreach ($user['user_lifeInterested'] as $keymlf => $userLifeGroup) {
				array_push($userLifeGroups, new MongoId($userLifeGroup));
			}
			$lifeGroups = $this->db->ProMediaGroup->find(array('_id' => array('$in' => $userLifeGroups)));
			$lifeList = array ();
			foreach ($lifeGroups as $keyLF => $lifegroup) {
				/*
				foreach ($group['mediaGroup_sourceList'] as $keyS => $source) {
					if (!isset($sourceList[$source['source_id']])) {
						$sourceList[$source['source_id']] = $source['source_id'];
					}
				}
				*/
				array_push($lifeList, $lifegroup['mediaGroup_title']);

			}
			//array_push($sourceList, "5542329709f778a5068b457f");

			//获得商业的Seeds
			$sourceLifeSeeds = $this->getSelectedSeeds($lifeList,$readSeeds);

			$liferes = array ();
			$liferes1 = array ();
			//计算所有新闻的热度
			foreach ($sourceLifeSeeds as $sourceSeedKeyL => $sourceLifeSeed) {
				$stats = $this->getHotness($user,$sourceLifeSeed,$agreeWords,count($seedIDs),$disAgreeWords,count($disSeedIDs),$agreeLabels,$disAgreeLabels);
				//return $stats;
				$liferes[(string)$sourceLifeSeed['_id']] = $stats['priority'];
				$liferes1[(string)$sourceLifeSeed['_id']] = $stats['priorityType'];
				
			}
			//排序
			arsort($res);
			//删选
			$topLifeRes = array_slice($liferes,0,$lifeSeedQuota);


			$topRes = array();
			foreach ($topBusinessRes as $tbResKey => $tbResValue) {
				$topRes[$tbResKey] = $tbResValue;
			}
			foreach ($topLifeRes as $tlResKey => $tlResValue) {
				$topRes[$tlResKey] = $tlResValue;
			}


			arsort($topRes);

			$results = array ();
			foreach ($topRes as $key => $value) {
				$selectedSeed = $this->db->Proseed->find(
					array ('_id'=> new MongoId($key)
					)
					);
				
				foreach ($selectedSeed as $key1 => $value1) {

					$value1['seed_hotness'] = $value;
					$value1['seed_priorityType'] = $res1[$key];
					if ($value1['seed_text'] == '') {
						$value1['seed_textStatus'] = '0';
					}else{
						unset($value1['seed_text']);	
					}
					
					/*
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
					*/
					array_push ($results,$value1);

					//增加用户阅读的记录：
					$readLog = $this->db->Proread->findOne (array ('seed_id' => (string)$value1['_id'],'user_id'=>$user_id));
					if ($readLog == null) {
						$data = array (
							'seed_id' => (string)$value1['_id'],
							'source_id' => $value1['seed_sourceID'],
							'user_id' => $user_id,
							'read_time' => time(),
							'read_type' => '0'
							);
						$this->db->Proread->save ($data);
					}
				}
			}
			return count($sourceBusinessSeeds);//json_encode($results);
		}catch (Exception $e){
			return $e;
		}
	}

function getSelectedSeeds($industryList,$readSeeds){

	//foreach ($sources as $key => $source) {
			$sourceSeeds = array();
			foreach ($industryList as $key456 => $likedIndustry) {
				$industrySeeds = $this->db->Proseed->find (
				array (
					'$and' => array(
						/*
						array(
							'$or' => array(
								array ('seed_industry' => $user['current']['user_industry']),
								array ('seed_industry' => $user['current']['user_interestA']),
								array ('seed_industry' => $user['current']['user_interestB'])
								)
						),*/
						
						array(
							'$or' => array(
								array ('seed_textLen' => array('$gt'=> 400)),
								//array ('seed_textLen' => array('$lt'=> 1))
							)
						)
					),
					//'seed_sourceID' => array('$in' => $sourceList),
					'seed_industry' => $likedIndustry,
					'$nor' => array(
						array (
							'$and' => array (
								array ('seed_completeStatus' => 'completed'),
								//array ('seed_text' => '')
								)
							)
						),
					'seed_dbWriteTime' => array ('$gt' => (time()-86400*3)),
					'seed_active' => '1', 
					'_id' => array ('$nin' => $readSeeds)
					)
				);

				foreach ($industrySeeds as $key455 => $industrySeed) {
					/*
					if (!isset($sourceSeeds[(string)$industrySeed['_id']])) {
						$sourceSeeds[(string)$industrySeed['_id']] = $industrySeed;
					}
					*/

					array_push($sourceSeeds, $industrySeed);
				}

			}
			
				/*
				foreach ($sourceSeeds as $key => $value) {
					
					if (in_array ((string)$value['_id'],$seeds)) {
						# code...
					}else{
						array_push ($seeds,(string)$value['_id']);
					}
				}*/

			//}
			/*
			$unreadSeeds = array ();
			foreach ($sourceSeeds as $key => $seed) {
				$cursor = $this->db->Proread->findOne(array ('seed_id' => $seed,'user_id'=>$user_id));
				//if ($cursor == null) {
					array_push($unreadSeeds,(string)$seed['_id']);
				//}
			}*/
			return $sourceSeeds;//$sourceSeeds;
}

function querySeedsByGroup ($user_id,$group_id,$time){
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
	$group = $this->db->ProMediaGroup->findOne(array('_id' => new MongoId($group_id)));
	$groupSources = array();
	foreach ($group['mediaGroup_sourceList'] as $key => $value) {
		array_push($groupSources, $value['source_id']);
	}
	$sourceSeeds = $this->db->Proseed->find (
		array (
			'$and' => array(
				/*
				array(
					'$or' => array(
						array ('seed_industry' => $user['current']['user_industry']),
						array ('seed_industry' => $user['current']['user_interestA']),
						array ('seed_industry' => $user['current']['user_interestB'])
						)
				),*/
				
				array(
					'$or' => array(
						array ('seed_textLen' => array('$gt'=> 400)),
						array ('seed_textLen' => array('$lt'=> 1))
					)
				)
			),
			//'seed_sourceID' => array('$in' => $groupSources),
			'seed_industry' => $group['mediaGroup_title'],
			'seed_editorRating' => array ('$gte' => 0),
			'$nor' => array(
				array (
					'$and' => array (
						array ('seed_completeStatus' => 'completed'),
						array ('seed_text' => '')
						)
					)
				), 
			'seed_time' => array ('$lt' => $time),
			'seed_active' => '1', 
			))->sort(array('seed_time' => -1))->limit(10);
	$results = array();
	foreach ($sourceSeeds as $keys => $source) {
		unset($source['seed_text']);
		array_push($results, $source);
	}
	return json_encode($results);

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
				array_shift($user['user_searchWords']);
			}
			
			$this->db->Prouser->save($user);

			//存入Search统计
			$item = $this->db->Prowords->findOne(array('word_name' => $keyword));
			if ($item == null) {
				$data = array(
					'word_name' => $keyword,
					'word_industry' => $user['current']['user_industry'],
					'word_hotness' => 10,
					'word_checkTime' => time()
				);
				$this->db->Prowords->save($data);
			}else{
				$item['word_hotness'] += 10;
				$this->db->Prowords->save($item);
			}

			$time = (int)$time;
			$sourceSeeds = $this->db->Proseed->find (
				array (
					/*
					'$or' => array(
							array ('seed_industry' => $user['current']['user_industry']),
							array ('seed_industry' => $user['current']['user_interestA']),
							array ('seed_industry' => $user['current']['user_interestB'])
							), */
					'$nor' => array(
						array(
							'$and' => array (
								array ('seed_completeStatus' => 'completed'),
								array ('seed_text' => '')
								)
							)
						),
					'seed_time' => array ('$lt' => $time),
					'seed_editorRating' => array ('$gte' => 0),
					'$and' => array(
						array(
							'$or' => array(
								array('seed_textLen' => array('$gt'=> 400)),
								//array('seed_textLen' => 0)
							)
						),
						array(
							'$or' => array (
								array('seed_titleLower' => new MongoRegex ("/$keyword/")),
								array('seed_industry' => new MongoRegex ("/$keyword/"))
								//array('seed_sourceLower' => new MongoRegex ("/$keyword/"))
							)		
						),
					),
					'seed_active' => '1',
				)
			)->sort(array('seed_time'=> -1))->limit(10);
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
					//$value1['seed_hotness'] = $value;
					//$value1['seed_priorityType'] = $res1[$key];
					
					if ($value1['seed_text'] == '') {
						$value1['seed_textStatus'] = '0';
					}else{
						unset($value1['seed_text']);	
					}
					
					/*
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
					*/
					array_push ($results,$value1);

					//增加用户阅读的记录：
					$readLog = $this->db->Proread->findOne (array ('seed_id' => (string)$value1['_id'],'user_id'=>$user_id));
					if ($readLog == null) {
						$data = array (
							'seed_id' => (string)$value1['_id'],
							'user_id' => $user_id,
							'source_id' => $value1['seed_sourceID'],
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
		$textToDownload = array ();
		//找到seed_id的内容
		$seed = $this->db->Proseed->findOne(array('_id'=> new MongoId($seed_id)));
		if ($seed['seed_text'] == '') {
			/*
			$feedurl = $seed['seed_link'];

		    //$feeds = file_get_contents($feedurl);
		    $ch = curl_init($feedurl);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    $html = curl_exec($ch);

		    //HTML进行UTF-8转码
		    $encode = mb_detect_encoding($html, array('ASCII', 'UTF-8', 'GB2312', 'GBK', "EUC-CN", "CP936"));

		    if ($encode != 'UTF-8') {
		        //$encode = $encode . "//IGNORE"
		        $html = iconv($encode, 'UTF-8//IGNORE', $html);

		        //var_dump($feeds);

		        $html = str_replace('encoding="gb2312"', 'encoding="utf-8"', $html);
		        $html = str_replace('encoding="ascii"', 'encoding="utf-8"', $html);
		        $html = str_replace('encoding="gbk"', 'encoding="utf-8"', $html);
		        $html = str_replace('encoding="ecu-cn"', 'encoding="utf-8"', $html);
		        $html = str_replace('encoding="cp936"', 'encoding="utf-8"', $html);

		        $html = str_replace('encoding="GB2312"', 'encoding="utf-8"', $html);
		        $html = str_replace('encoding="ASCII"', 'encoding="utf-8"', $html);
		        $html = str_replace('encoding="GBK"', 'encoding="utf-8"', $html);
		        $html = str_replace('encoding="EUC-CN"', 'encoding="utf-8"', $html);
		        $html = str_replace('encoding="CP936"', 'encoding="utf-8"', $html);
		    }



		        $html = preg_replace("/[\t\n\r]+/", "", $html);
		        $html = preg_replace("<script .*? /script>", "", $html);
		        $html = preg_replace("<link .*? >", "", $html);
		        $html = preg_replace("<link .*? >", "", $html);
		        $html = preg_replace("<iframe .*? /iframe>", "", $html);

		        $source = $this->db->Prosource->findOne(array('_id' => new MongoId($seed['seed_sourceID'])));

		        $source_openTag = $source['source_tag'][0];
		        $source_closeTag = $source['source_tag'][1];

		        $openTag_pos = strpos($html, $source_openTag);
		        $closeTag_pos = strpos($html, $source_closeTag);
		        $cutHTML = mb_substr($html, $openTag_pos,$closeTag_pos-$openTag_pos);

		        if (isset($source['text_startingTag'])) {
		            $text_startTag = $source['text_startingTag'];
		            $startTag_pos = strpos($cutHTML,$text_startTag);
		            if ($startTag_pos !== false) {
		                $cutHTML = mb_substr($cutHTML, $startTag_pos);    
		            }
		            
		        }

		        if (isset($source['text_closingTag'])) {
		            $text_endTag = $source['text_closingTag'];
		            $endTag_pos = strpos($cutHTML,$text_endTag);
		            if ($endTag_pos !== false) {
		                $cutHTML = mb_substr($cutHTML,0,$endTag_pos);
		            }
		            
		        }

		        $text = $cutHTML;
		        $text = str_replace("style=", "", $text);
		        $text = str_replace("width", "", $text);
		        $text = str_replace("height", "", $text);
		        $text = str_replace("font-size", "", $text);
		        //$text = str_replace("size=", "", $text);

		        $text = preg_replace("<script.*?/script>", "",$text);
		        $text = preg_replace("<link.*?>", "",$text);
		        $text = preg_replace("<iframe.*?/iframe>", "",$text);

		                //解析行业
		        $protext = new Protext;
		        $parserResult = $protext->parseIndustry($text,strtolower($seed['seed_titleLower']));  

		        $imgPattern = "<(?:img|IMG).*?(?:src|data-url)=\"(.*?)\".*?>";

		        preg_match_all($imgPattern, $text, $imgResult);

		        if (count($imgResult[0])>0) {
		            $imageLink = $imgResult[1][0];    
		        }else{
		                $imageLink = '';    
		        }
		        $httpPos = strpos($imageLink, 'http');
		        if ($imageLink != '' && $httpPos === false) {
		            $imageLink = $source['source_homeURL'].$imageLink;
		        } 

		        if ($source['source_name'] == '趋势网') {
		            $imageLink = str_replace("uploads/../../", "", $imageLink);
		        }else{
		            $imageLink = str_replace("../", "", $imageLink);
		        }
		        
		        $seed['seed_text'] = $text;
		        $seed['seed_textLen'] = mb_strlen($text);
		        $seed['seed_imageLink'] = $imageLink;
		        $seed['seed_completeStatus'] = 'completed';
		        $seed['seed_textIndustryWords'] = $parserResult['seed_textIndustryWords'];

		        $seedIndustry = array ();

	            foreach($parserResult['seed_industryParsed'] as $industryParsed){
                    array_push($seedIndustry,$industryParsed);
                    //$seed['seed_industryHotness'][$industryParsed] = 0;
                };

                foreach ($parserResult['seed_segmentParsed'] as $key2 => $segment) {
                    if (!in_array($segment,$seedIndustry)) {
                        array_push($seedIndustry,$segment);
                    }
                }

		        $seed['seed_industry'] = $seedIndustry;
		        $seed['seed_hotness' ] = 100;
		        $this->db->Proseed->save($seed);

		        $textToDownload['seed_text'] = $seed['seed_text'];	
		    */



		}else{
			
			$textToDownload['seed_text'] = $seed['seed_text'];			
		}

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


		$source = $this->db->Prosource->findOne(array ('_id' => new MongoId($seed['seed_sourceID'])));
		$source['read_count'] ++;
		$this->db->Prosource->save($source);

		//做好阅读记录
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

		//修改阅读和点击的数量
		$user = $this->db->Prouser->findOne(array('_id' => new MongoId($user_id)));
		$user['user_seedRead']['total_read'] ++;

		if ($seed['seed_domain'] == 'business'){
			$user['user_seedRead']['business_read'] ++;
		}

		if ($seed['seed_domain'] == 'life'){
			$user['user_seedRead']['life_read'] ++;
		}
		$this->db->Prouser->save($user);


		if ($textToDownload['seed_text'] == '') {
			return 1;
		}else{
			return json_encode($textToDownload);	
		}

			


		
	}

	function getHotness ($user, $seed,$agreeWords,$agreeCount,$disAgreeWords,$disAgreeCount,$agreeLabels,$disAgreeLabels){

		try {
			/*
			//获得我圈子里的人
			$prouser = new Prouser ();
			$myPros = $prouser->findMyPros ($user_id);
			*/
			//找到这个新闻
			

			//找到我圈子里的人对这个话题的赞
			/*
			$agrees = $this->db->Proworth->find (
				array (
					'like_seed'=>$seed_id,
					'like_user'=> array ('$in'=>$myPros)
					)
				);*/
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
			$labelMatchCount = 0;
			$dismatchCount = 0;
			$disLabelMatchCount  = 0;
			$matchness = 0;

			//计算和已经读过的文章的匹配数

			if ($agreeCount>0) {
				foreach ($seed['seed_keywords'] as $key => $agreeWord) {
					if (isset($agreeWords[$agreeWord])) {
						$matchCount += $agreeWords[$agreeWord];	
					}	
				}
				$matchness += $matchCount/$agreeCount;

				foreach ($seed['seed_industry'] as $key => $agreeLabel) {
					if (isset($agreeLabels[$agreeLabel])) {
						$labelMatchCount += $agreeLabels[$agreeLabel];	
					}	
				}
				$matchness += $labelMatchCount/$agreeCount;
			}
		
			if ($disAgreeCount>0) {
				foreach ($seed['seed_keywords'] as $key => $disAgreeWord) {
					if (isset($disAgreeWords[$disAgreeWord])) {
						$dismatchCount += $disAgreeWords[$disAgreeWord];
					}
				}
				$matchness -= $dismatchCount/$disAgreeCount;

				foreach ($seed['seed_industry'] as $key1 => $disAgreeLabel) {
					if (isset($disAgreeLabels[$disAgreeLabel])) {
						$disLabelMatchCount += $disAgreeLabels[$disAgreeLabel];
					}
				}
				$matchness -= $disLabelMatchCount/$disAgreeCount;
			}	
			
			//更新关键词匹配的值
			
			//计算和自己的关键词的疲惫度
			/*
			if (isset($user['user_keywords'])) {
				foreach ($user['user_keywords'] as $keyword) {
					if ($keyword != '') {
						$keywordPos = strpos($seed['seed_titleLower'], $keyword);
						if ($keywordPos !== false) {
							$matchness += 1;
						}	
					}
				}		
			}*/

			//匹配搜索记录的相关性
			if (isset($user['user_searchWords'])) {
				foreach ($user['user_searchWords'] as $searchWord) {
					if ($searchWord != '') {
						$searchPos = strpos($seed['seed_titleLower'], $searchWord);
						if ($searchPos !== false) {
							$matchness += 1;
						}	
					}
					
				}	
			}
			
						

			$hotness = $seed['seed_hotness'];
			/*
			if (isset($seed['seed_industryHotness'][$user['current']['user_industry']])){
				$hotness += $seed['seed_industryHotness'][$user['current']['user_industry']];
			}
			if (isset($seed['seed_industryHotness'][$user['current']['user_interestA']])){
				$hotness += $seed['seed_industryHotness'][$user['current']['user_interestA']];
			}
			if (isset($seed['seed_industryHotness'][$user['current']['user_interestB']])){
				$hotness += $seed['seed_industryHotness'][$user['current']['user_interestB']];
			}*/

			$priority = $hotness * ($matchness*20+100)/100;
			
			$hotcent = $hotness / $priority;
			//$agreecent = $agreeness / $priority;
			$matchcent = 1- ($matchness / $priority);
			$priorityType = '';
			if ($matchcent > 0.1){
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
		$user = $this->db->Prouser->findOne (array ('_id' => new MongoId ($user_id)));
		$cursor = $this->db->Proseed->findOne (array ('_id'=> new MongoId($seed_id)));
		$existWorth = $this->db->Proworth->findOne (array ('like_user'=>$user_id,'like_seed'=>$seed_id));
		$source = $this->db->Prosource->findOne (array ('_id' => new MongoId($cursor['seed_sourceID'])));


		if ($existWorth == null) {
			
			$data = array (
				'like_user' => $user_id,
				'like_seed' => $seed_id,
				'like_weight' => $user['user_weight'],
				//'like_industry' => $user['user_industry'],
				'like_seedSource' => $cursor['seed_sourceID'],
				'like_comment' => $like_comment,
				'like_time' => time(),
				'like_status' => 'active'
				);

			$this->db->Proworth->save ($data);

		}else{
			$existWorth['like_status'] = 'active';
			$this->db->Proworth->save ($existWorth);			
		}

			$cursor['seed_hotness'] += (int)$user['user_weight']*10;
			if(isset($cursor['seed_agreeCount'])){
				$cursor['seed_agreeCount'] ++;
			}else{
				$cursor['seed_agreeCount'] = 1;
			}
			$this->db->Proseed->save($source);

			/*
			if (in_array($user['current']['user_industry'],$cursor['seed_industry'])) {
				$cursor['seed_industryHotness'][$user['current']['user_industry']] += (int)$user['current']['user_weight']*10;
				if(isset($cursor['seed_agreeCount'])){
					$cursor['seed_agreeCount'] += $user['current']['user_weight'];
				}else{
					$cursor['seed_agreeCount'] = $user['current']['user_weight'];
				}
				//$cursor['seed_hotnessTime'] = time();
				$this->db->Proseed->save($cursor);
			}else{
				$cursor['seed_industryHotness'][$user['current']['user_industry']] += (int)$user['current']['user_weight']*1;
				if(isset($cursor['seed_agreeCount'])){
					$cursor['seed_agreeCount'] ++;
				}else{
					$cursor['seed_agreeCount'] = 1;
				}
				//$cursor['seed_hotnessTime'] = time();
				$this->db->Proseed->save($cursor);
			}
			*/
			$source['agree_count'] ++;
			
			$this->db->Prosource->save($source);
		
		
		

		return 1;

	}

	function dislikeSeed($user_id,$seed_id){
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
		$user = $this->db->Prouser->findOne (array ('_id' => new MongoId ($user_id)));
		$cursor = $this->db->Proseed->findOne (array ('_id'=> new MongoId($seed_id)));
		$existWorth = $this->db->Proworth->findOne (array ('like_user'=>$user_id,'like_seed'=>$seed_id));
		//$source = $this->db->Prosource->findOne (array ('_id' => new MongoId($cursor['seed_sourceID'])));

		if ($existWorth != null) {
			$existWorth['like_status'] = 'inactive';
			$this->db->Proworth->save($existWorth);
		}
		return 1;

	}

	function likeSeedWithout($user_id,$seed_id){
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
		$user = $this->db->Prouser->findOne (array ('_id' => new MongoId ($user_id)));
		$cursor = $this->db->Proseed->findOne (array ('_id'=> new MongoId($seed_id)));
		$existWorth = $this->db->Proworth->findOne (array ('like_user'=>$user_id,'like_seed'=>$seed_id));
		$source = $this->db->Prosource->findOne (array ('_id' => new MongoId($cursor['seed_sourceID'])));

		$cursor['seed_hotness'] += (int)$user['user_weight']*10;
		if(isset($cursor['seed_agreeCount'])){
			$cursor['seed_agreeCount'] ++;
		}else{
			$cursor['seed_agreeCount'] = 1;
		}
		$this->db->Proseed->save($cursor);
		$source['agree_count'] ++;
		$this->db->Prosource->save($source);
		
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
		$cursor = $this->db->Proworth->find(array ('like_user'=> $user_id,'like_status'=> 'active','like_time'=> array('$lt' => $time)))->sort(array('like_time'=> -1))->limit(10);
		$myLikedSeeds = array ();
		foreach ($cursor as $key => $value) {
			$seed = $this->db->Proseed->find(array ('_id'=> new MongoId($value['like_seed'])));

			foreach ($seed as $key => $item) {
				$item['like_comment'] = $value['like_comment'];
				if ($value['seed_text'] == '') {
					$item['seed_textStatus'] = '0';
				}else{
					unset($item['seed_text']);	
				}
					
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
			array_shift($user['user_searchWords']);
		}
		$this->db->Prouser->save($user);

		$time = (int)$time;
		$cursor = $this->db->Proworth->find(array ('like_user'=> $user_id,'like_time'=> array('$lt' => $time)))->sort(array('seed_time'=> -1));
		$myLikedSeeds = array ();
		
		foreach ($cursor as $key => $value) {
			$seed = $this->db->Proseed->findOne(array ('_id'=> new MongoId($value['like_seed'])));
			$combinedString = $seed['seed_titleLower'].$seed['seed_sourceLower'].strtolower($value['like_comment']);
			$keywordPos = strpos($combinedString, $keyword);
			if ($keywordPos>0 && $keywordPos < mb_strlen($combinedString)) {
				$seed['like_comment'] = $value['like_comment'];
				if ($seed['seed_text'] == '') {
					$seed['seed_textStatus'] = '0';
				}else{
					unset($seed['seed_text']);	
				}
				array_push($myLikedSeeds,$seed);
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

		$seed = $this->db->Proseed->findOne(array('_id' => new MongoId($seed_id)));
		$result = array ();
		$likes = $this->db->Proworth->find (array ('like_seed'=> $seed_id,'like_comment' => array ('$ne' => ''),'like_public' => '1'));
		foreach ($likes as $key => $value) {
			$users = $this->db->Prouser->find (array ('_id' => new MongoId($value['like_user'])));
			foreach ($users as $key => $user) {
				$value['user_id'] = (string)$user['_id'];
				$value['user_name'] = $user['user_nickname'];
				//$value['user_company'] = $user['current']['user_company'];
				//$value['user_title'] = $user['current']['user_title'];
			}

			array_push ($result,$value);
		}
		return json_encode($result);

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

		$cursor = $this->db->Promessage->find (array ('message_type' => 'system'))->sort(array ('message_postTime'=> -1))->limit (10);
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
			)->sort (array ('message_postTime'=> -1))->limit (10);
		$feedbackMessages = array();
		foreach ($cursor as $key => $value){
			if ($value['message_senderID'] != 'system') {
				$user = $this->db->Prouser->findOne(array('_id'=> new MongoId($value['message_senderID'])));
				$value['sender_name'] = $user['user_nickname'];	
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

	function uploadReadtime($user_id,$seed_id,$read_time){
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}

		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}

		$read_time = (int)$read_time;
		//记录时间
		$readSeed = $this->db->Proread->findOne(array('seed_id' => $seed_id));
		$readSeed['read_duration'] = $read_time;
		$this->db->Proread->save($readSeed);

		//找到这条Seed
		$seed = $this->db->Proseed->findOne(array('_id' => new MongoId($seed_id)));
		$user = $this->db->Prouser->findOne(array('_id'=> new MongoId($user_id)));

		//判断是否热度加1

		$highReadTime = $seed['seed_textLen']*60*0.5/500;
		
		if ($read_time > $highReadTime ) {
			$seed['seed_hotness'] += (int)$user['user_weight']*1;
			if(isset($seed['seed_agreeCount'])){
				$seed['seed_agreeCount'] += ceil($user['user_weight']/5);
			}else{
				$seed['seed_agreeCount'] = ceil($user['user_weight']/5);
			}
		}else if($read_time > 60){
			$seed['seed_hotness'] += 1;
			$this->db->Proseed->save($seed);	
		}
		/*
		if ($read_time > $highReadTime ) {
			
			if (in_array($user['current']['user_industry'],$seed['seed_industry'])) {
				$seed['seed_industryHotness'][$user['current']['user_industry']] += (int)$user['current']['user_weight']*1;
				if(isset($seed['seed_agreeCount'])){
					$seed['seed_agreeCount'] += ceil($user['current']['user_weight']/5);
				}else{
					$seed['seed_agreeCount'] = ceil($user['current']['user_weight']/5);
				}
			}else{
				$seed['seed_industryHotness'][$user['current']['user_industry']] += 1;//(int)$user['current']['user_weight']*1;
				if(isset($seed['seed_agreeCount'])){
					$seed['seed_agreeCount'] += 0;//ceil($user['current']['user_weight']/5);
				}else{
					$seed['seed_agreeCount'] = 0;//ceil($user['current']['user_weight']/5);
				}
			}
			$this->db->Proseed->save($seed);
		}else{
			if ($read_time > 60 ) {
				if (in_array($user['current']['user_industry'],$seed['seed_industry'])) {
					$seed['seed_industryHotness'][$user['current']['user_industry']] += 1;
					$this->db->Proseed->save($seed);
				}
			}
		}
		*/
	}

	function reportFormatBug($seed_id){
		$seed = $this->db->Proseed->findOne(array('_id'=> new MongoId($seed_id)));
		$seed['seed_completeStatus'] = 'formatBug';
		$this->db->Proseed->save($seed);
	}

	function reportAdContent($seed_id){
		$seed = $this->db->Proseed->findOne(array('_id'=> new MongoId($seed_id)));
		$seed['seed_completeStatus'] = 'adContent';
		$this->db->Proseed->save($seed);
	}


	function queryMyWordsToSearch($user_id){
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}

		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}

		//找到User
		$user = $this->db->Prouser->findOne(array('_id'=> new MongoId($user_id)));
		$result = array();
		$userSearchWords= array();
		$circleSearchWords = array();
		//获得自己搜索的词
		$wordsCount = count($user['user_searchWords'])-1;
		for ($i = $wordsCount; $i >= 0; --$i){
			if (!in_array($user['user_searchWords'][$i],$userSearchWords)){
				array_push($userSearchWords,$user['user_searchWords'][$i]);
			}
		}
		$result[0] = $userSearchWords;
		//获得业内搜索的词
		$circleWords = $this->db->Prowords->find(array('word_industry'=> $user['user_industry']))->sort(array('word_hotness' => -1))->limit(10);
		foreach ($circleWords as $key => $value) {
			array_push($circleSearchWords, $value['word_name']);
		}
		$result[1] = $circleSearchWords;

		return json_encode($result);

	}


	function queryMediaGroups($user_id){
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkToken () == 0) {
			return - 3;
		}
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}
		
		$user = $this->db->Prouser->findOne(array('_id' => new MongoId($user_id)));
		/*
		$followedGroupIDs= array();
		foreach ($user['user_mediaGroups'] as $key => $myGroup) {
			array_push($followedGroupIDs,new MongoId($myGroup));
		}

		$follower_count = (int)$follower_count;
		if ($follower_count == 0) {
			$groups = $this->db->ProMediaGroup->find(array('_id' =>array('$nin' => $followedGroupIDs)))->sort(array ('mediaGroup_counts.follower_count' => -1))->limit(30);	
		}else{
			$groups = $this->db->ProMediaGroup->find(array('_id' =>array('$nin' => $followedGroupIDs),'mediaGroup_counts.follower_count' => array ('$lt' => $follower_count)))->sort(array ('mediaGroup_counts.follower_count' => -1))->limit(30);
		}
		*/
		$bizGroups = $this->db->ProMediaGroup->find(array('group_type' => 'business'))->sort(array('group_rank' => 1));
		$lifeGroups = $this->db->ProMediaGroup->find(array('group_type' => 'life'))->sort(array('group_rank' => 1));
		$bizGroupsToShow = array();
		foreach ($bizGroups as $key => $value) {
			$value['user_mediaGroupStatus'] = '0';
			
			if (isset($user['user_industryInterested'])) {
				if (isset($user['user_industryInterested'][(string)$value['_id']])) {
		 			$value['user_mediaGroupStatus'] = '1';
		 		}	
			}
			array_push($bizGroupsToShow,$value);
		}

		$lifeGroupsToShow = array();
		foreach ($lifeGroups as $key1 => $value1) {
			$value1['user_mediaGroupStatus'] = '0';
			
			if (isset($user['user_lifeInterested'])) {
				if (isset($user['user_lifeInterested'][(string)$value1['_id']])) {
		 			$value1['user_mediaGroupStatus'] = '1';
		 		}	
			}
			array_push($lifeGroupsToShow,$value1);
		}

		$results = array();
		array_push($results, $bizGroupsToShow);
		array_push($results, $lifeGroupsToShow);

		return json_encode($results);
	}

	function followMediaGroup($user_id,$group_id){
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkToken () == 0) {
			return - 3;
		}
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}

		$user = $this->db->Prouser->findOne(array('_id' => new MongoId($user_id)));
		$group = $this->db->ProMediaGroup->findOne(array('_id' => new MongoId($group_id)));
		if ($group['group_type'] == 'business') {
			if (!isset($user['user_industryInterested'][$group_id])) {
				$user['user_industryInterested'][$group_id] = $group_id;
				$this->db->Prouser->save($user);
			}
		}

		if ($group['group_type'] == 'life') {
			if (!isset($user['user_lifeInterested'][$group_id])) {
				$user['user_lifeInterested'][$group_id] = $group_id;
				$this->db->Prouser->save($user);
			}
		}
		$group['mediaGroup_counts']['follower_count'] ++;
		$this->db->ProMediaGroup->save($group);
		return 1;
	}

	function disfollowMediaGroup ($user_id,$group_id){
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		if ($this->checkToken () == 0) {
			return - 3;
		}
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}

		$user = $this->db->Prouser->findOne(array('_id' => new MongoId($user_id)));
		$group = $this->db->ProMediaGroup->findOne(array('_id' => new MongoId($group_id)));

		if ($group['group_type'] == 'business') {
			if (isset($user['user_industryInterested'][$group_id])) {
				unset($user['user_industryInterested'][$group_id]);
				$this->db->Prouser->save($user);
			}
		}

		if ($group['group_type'] == 'life') {
			if (isset($user['user_lifeInterested'][$group_id])) {
				unset($user['user_lifeInterested'][$group_id]);
				$this->db->Prouser->save($user);
			}
		}

		
		$group['mediaGroup_counts']['follower_count'] --;
		$this->db->ProMediaGroup->save($group);

		return 1;
	}

	function queryMediaList($user_id,$group_id){
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
		$group = $this->db->ProMediaGroup->findOne (array ('_id' => new MongoId($group_id)));
		$mediaList = array();
		foreach ($group['mediaGroup_sourceList'] as $key => $value) {
			$source = $this->db->Prosource->findOne(array('_id' => new MongoId($value['source_id'])));
			$value['source_name'] = $source['source_name'];
			$value['source_description'] = $source['source_description'];
			$value['source_image'] = $source['source_image'];
			$value['source_imageName'] = $source['source_imageName'];
 			array_push($mediaList, $value);
		}


		/*
		$cursor = $this->db->Prosource->find(array(
			'source_industry' => $group_id
			));*/
		/*
		$cursor = $this->db->Prosource->find(array ('$or' => array(
							array ('source_industry' => $user['current']['user_industry']),
							array ('source_industry' => $user['current']['user_interestA']),
							array ('source_industry' => $user['current']['user_interestB'])
							)));
		
		
		foreach ($cursor as $key => $value) {
			array_push ($mediaList, $value);
		}*/
		return json_encode($mediaList);
	}

	function querySeedsBySource($user_id,$source_id,$time){
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

		$result = array();
		$seeds = $this->db->Proseed->find(array('seed_sourceID' => $source_id,'seed_editorRating' => array('$gte' => 0),'seed_time' => array('$lt' => $time)))->sort(array('seed_time' => -1))->limit(10);
		foreach ($seeds as $key => $value) {
			array_push($result,$value);
		}
		return json_encode($result);
	}

	function queryMyMediaGroups($user_id){
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkQuoteToken () != 1) {
			return - 3;
		}
		
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}
		$user = $this->db->Prouser->findOne(array('_id' => new MongoId($user_id)));
		$result = array();
		foreach ($user['user_mediaGroups'] as $key => $value) {
			//计算最新一周未读的文章数量
			$mediaGroup = $this->db->ProMediaGroup->findOne(array('_id' => new MongoId($value)));
			$mediaGroup['user_mediaGroupStatus'] = '1';
			//$item = array();
			//$item['group_id'] = $value; 
			//$totalCount = $this->db->Proseed->count(array('seed_sourceID' => array('$in' => $mediaGroup['mediaGroup_sourceList']),'seed_time' => array('$gt' => (time()-86400*7))));
			//$readCount = $this->db->Proread->count(array('user_id' => $user_id, 'source_id' => array('$in' => $mediaGroup['mediaGroup_sourceList']), 'seed_time' => array('$gt' => (time()-86400*7))));
			//$mediaGroup['unread_count'] = $totalCount-$readCount;
			//$item['group_name'] = $mediaGroup['mediaGroup_title'];
			array_push($result, $mediaGroup);
		}	
		return json_encode($result);
	}
	
	function queryLikedChannel($user_id){
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkQuoteToken () != 1) {
			return - 3;
		}
		
		if (! isset ( $_COOKIE ['user_id'] ) || $_COOKIE ['user_id'] != $user_id) {
			return - 4;
		}

		$results = array();
		$user = $this->db->Prouser->findOne(array('_id' =>new MongoId($user_id)));
		foreach ($user['user_industryInterested'] as $key => $value) {
			array_push($results, $value);
		}
		foreach ($user['user_lifeInterested'] as $key1 => $value1) {
			array_push($results, $value1);
		}
		return json_encode($results);
	}
	



}
?>
