<?php
require_once 'YqBase.php';
require_once 'getui/IGt.Push.php';
require_once 'getui/igetui/IGt.AppMessage.php';
require_once 'getui/igetui/template/IGt.BaseTemplate.php';

define ( 'APPKEY', 'Fswp6NteiyAgshqIjY4UTA' );
define ( 'APPID', 'ksCvaUMV9D7rhBA1vMydXA' );
define ( 'MASTERSECRET', 'Ava39nShg88cpX8DydftJ3' );

define ( 'DEVICETOKEN', '' );
define ( 'HOST', 'http://sdk.open.api.igexin.com/apiex.htm' );

    
class Message extends YqBase {
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
	function addMessage($message_senderId, $message_receiverId, $message_type, $message_title, $message_labels, $message_topicID,$message_topicTitle) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
		$message_postTime = time ();
		
		$m_labels = explode ( ',', $message_labels );
		
		$data = array (
				'message_senderId' => $message_senderId,
				'message_receiverId' => $message_receiverId,
				'message_type' => $message_type,
				'message_title' => $message_title,
				'message_life' => 1,
				'message_postTime' => $message_postTime,
				'message_labels' => $m_labels,
				'message_topicID' => $message_topicID,
				'message_topicTitle' => $message_topicTitle
				
				
		);

		
		try {
			$cursor = $this->db->message->findOne ( 
					array (
						'message_receiverId' => $message_receiverId,
						'message_topicID'	 => $message_topicID,
						'message_type' => 'newReply',
						'message_life'		 => 1
					)
				);
			if ( ($cursor == NULL) || ($message_type != 'newReply')) {
				$result = $this->db->message->insert ( $data );
                
                $cursor = $this->db->getuiClientID->findOne(array ('user_name' => $message_receiverId));
                if ($cursor != null) {

                    $platform = $cursor ['platform'];
                    $clientID = $cursor ['getui_clientID'];
                    $user = $this->db->user->findOne (array('user_name'=>$message_senderId));
                    $nickname = $user ['user_nickname'];
                    $unreadCount = $this->db->message->find (array(
                                                             'message_receiverId' => $message_receiverId,
                                                             'message_life' => 1
                                                                   ))->count ();
                    if ($platform == 'iOS'){
                        $this->pushiOSMessage($clientID,$nickname,$message_title,$unreadCount);
                    }
                    if ($platform == 'Android'){
						$this->pushMessageToSingle($clientID,$message_title,$unreadCount);
					}
                }
                
				return 1;
			}
			else
				return 0;
		
		} catch ( Exception $e ) {
			return - 1;
		}
	}
    
    
    //发送新消息通知给相应的人
    protected function pushiOSMessage($clientID,$senderName,$message_Title,$unreadCount){
        
        $ctx = stream_context_create();
        stream_context_set_option($ctx,'ssl','local_cert','yqProAPNS.pem');
        stream_context_set_option($ctx,'ssl','passphrase','2015oneto');
        $fp = stream_socket_client('ssl://gateway.push.apple.com:2195',$err,$errstr,60,STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
        
        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        
        ECHO 'Connected to APNS' . PHP_EOL;
        
        $body['aps'] = array (
            'alert' => $senderName . ': ' . $message_Title,
            'sound' => 'default',
            'badge' => $unreadCount
        );
        
        $payload = json_encode($body);
        
        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $clientID) . pack('n', strlen($payload)) . $payload;
        
        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));
        
        if (!$result)
            echo 'Message not delivered' . PHP_EOL;
        else
            echo 'Message successfully delivered' . PHP_EOL;
        
        // Close the connection to the server
        fclose($fp);
        
        /*
        $igt = new IGeTui("",APPKEY,MASTERSECRET);
        
        $template =  new IGtTransmissionTemplate();
        //应用appid
        $template->set_appId(APPID);
        //应用appkey
        $template->set_appkey(APPKEY);
        //透传消息类型
        $template->set_transmissionType(1);
        //透传内容
        $template->set_transmissionContent("新的消息");
        $template->set_pushInfo("actionLocKey","0","message",
           "sound","payload","locKey","locArgs","launchImage");
        
        $begin = "2015-03-06 13:18:00";
        $end = "2015-03-06 13:24:00";
        $template ->set_duration($begin,$end);
        
        $message = new IGtSingleMessage();
        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600*12*1000);//离线时间
        $message->set_data($template);//设置推送消息类型
        //接收方
        $target = new IGtTarget();
        $target->set_appId(APPID);
        $target->set_clientId($clientID);
        
        $rep = $igt->pushMessageToSingle($message,$target);
        var_dump($rep);
         */
        
    }

    protected function pushMessageToSingle($CID,$title,$count){
        $igt = new IGeTui(HOST,APPKEY,MASTERSECRET);
     
        $template = $this->IGtNotificationTemplate($title,$count);

	    $message = new IGtSingleMessage();
	    $message->set_isOffline(true);//是否离线
		$message->set_offlineExpireTime(3600*12*1000);//离线时间
	    $message->set_data($template);//设置推送消息类型
	    $message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
	    //接收方
	    $target = new IGtTarget();
	    $target->set_appId(APPID);
	    $target->set_clientId($CID);
	    $rep = $igt->pushMessageToSingle($message,$target);
	}

	function IGtNotyPopLoadTemplate($title,$count){
        $template =  new IGtNotyPopLoadTemplate();
        $template ->set_appId(APPID);//应用appid
        $template ->set_appkey(APPKEY);//应用appkey
        //通知栏
        $template ->set_notyTitle("你有".$count."条未读消息");//通知栏标题
        $template ->set_notyContent($title);//通知栏内容
        $template ->set_notyIcon("https://yiquanhost.oneto-tech.com/Yiquan_logo.png");//通知栏logo
        $template ->set_isBelled(true);//是否响铃
        $template ->set_isVibrationed(true);//是否震动
        $template ->set_isCleared(true);//通知栏是否可清除
 		
 		$begin = "2015-02-28 15:26:22";
        $end = "2015-02-28 15:31:24";
        $template->set_duration($begin,$end);
        return $template;
	}


	function IGtNotificationTemplate($title,$count){
	    $template =  new IGtNotificationTemplate();
	    $template->set_appId(APPID);//应用appid
	    $template->set_appkey(APPKEY);//应用appkey
	    $template->set_transmissionType(1);//透传消息类型
	    $template->set_transmissionContent("");//透传内容
	    $template->set_title("你有".$count."条未读消息");//通知栏标题
	    $template->set_text($title);//通知栏内容
	    $template->set_logo("");//通知栏logo
	    $template->set_isRing(true);//是否响铃
	    $template->set_isVibrate(true);//是否震动
	    $template->set_isClearable(true);//通知栏是否可清除
	    return $template;
	}
    
	
	// 查询用户收到的
	function queryMessageByName($message_receiverId,$time) {
		try {
			if ($this->yiquan_version == 0) {
				return - 2;
			}
			
			if ($this->checkToken () == 0) {
				return - 3;
			}
			$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
			$time = ( int ) $time; 
			$result = $this->db->message->find ( array (
					'message_receiverId' => $message_receiverId,
					'message_postTime' => array (
							'$lt' => $time
					),
					'message_life' => 1
					
			) )->sort ( array (
					'message_postTime' => - 1 
			) );
			$count = 0;
			$res = array ();
            $receiver = $this->db->user->findOne ( array(
                        'user_name' => $message_receiverId
            ) );
			foreach ( $result as $key => $value ) {
                if (isset ($receiver ['user_blocklist'][$value ['message_senderId']])){
                }else{
                    $user = $this->db->user->findOne ( array (
                            'user_name' => $value ['message_senderId'] 
                    ) );
                    $value ['sender_nickname'] = $user ['user_nickname'];
                    $value ['sender_smallavatar'] = $user ['user_smallavatar'];
                    array_push ( $res, $value );
                    if ($count >= 30) {
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
	// 表示用户收到了数据（就是查看了）
	function readMessage($message_id) {
		try {
			if ($this->yiquan_version == 0) {
				return - 2;
			}
			
			if ($this->checkToken () == 0) {
				return - 3;
			}
			$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
			$result = $this->db->message->update ( array (
					'_id' => new MongoId ( $message_id )
			), 			// 条件
			array (
					'$set' => array (
							'message_life' => 0 
					) 
			) ); // 把life set为0
			
            $theMessage = $this->db->message->findOne( array (
                                                              '_id' => new MongoId ( $message_id )
                                                              ));
            $this->db->oldMessage->save ( $theMessage );
            $this->db->message->remove ( array (
                                                '_id' => new MongoId ( $message_id )
                                                ));
            
			return 1;
		} catch ( Exception $e ) {
			return - 1;
		}
	}
}
?>
