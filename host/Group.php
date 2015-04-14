<?php
require_once 'YqBase.php';

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
class Group extends YqBase {
	// private $dbname = 'test';
	private $table = 'Group';
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
	function addGroup($group_founder, $group_name, $group_intro, $group_publicType, $group_avatar) {
		if ($this->yiquan_version == 0) {
			return - 2;
		}
		
		if ($this->checkToken () == 0) {
			return - 3;
		}
		
		if (! isset ( $_COOKIE ['user'] ) || $_COOKIE ['user'] != $group_founder) {
			return - 4;
		}
		$this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
        
        $group_foundTime = time ();
        
        $former = $this->db->group->findOne (
            array (
                'group_founder' =>$group_founder,
                'group_name' => $group_name,
                'group_foundTime' => array (
                   '$gt' => group_foundTime - 60
                   )
                                             )
                                             );
        
        if ($former == null){

            $group_memberList = array ();
            array_push ($group_memberList,$group_founder);
            //Upload the group_avatar:
            $rawpic = base64_decode ( $group_avatar );
            
            $im = new Imagick ();
            $im->readImageBlob ( $rawpic );
            $geo = $im->getImageGeometry ();
            $w = $geo ['width'];
            $h = $geo ['height'];
            $maxWidth = $maxHeight = 160;
            $fitbyWidth = (($maxWidth / $w) < ($maxHeight / $h)) ? true : false;
            
            if ($fitbyWidth) {
                $im->thumbnailImage ( $maxWidth, 0, false );
            } else {
                $im->thumbnailImage ( 0, $maxHeight, false );
            }
            
            // save to qiniu
            $auth = new Auth ( $this->qiniuAK, $this->qiniuSK );
            $bucket = 'yiquanhost-avatar';
            $uploadMgr = new UploadManager ();
            $bucketMgr = new BucketManager ( $auth );
            
            $token = $auth->uploadToken ( $bucket );
            list ( $ret, $err ) = $uploadMgr->put ( $token, null, $rawpic );
            if ($err == null) {
                 $bigAvatar = $this->userpicbucketUrl . '/' . $ret ['key'];
            } else {
                return $err;
            }
            
            list ( $ret, $err ) = $uploadMgr->put ( $token, null, $im );
            if ($err == null) {
                $smallAvatar = $this->userpicbucketUrl . '/' . $ret ['key'];
            } else {
                return $err;
            }

            
            $data = array (
                    "group_founder" => $group_founder,
                    "group_name" => $group_name,
                    "group_intro" => $group_intro,
                    "group_publicType" => $group_publicType,
                    "group_smallAvatar" => $smallAvatar,
                    "group_bigAvatar" => $bigAvatar,
                    "group_foundTime" => $group_foundTime,
                    "group_memberList" => $group_memberList,
                    "group_latestUpdate" =>$group_foundTime
            );

            
            
            $user = $this->db->user->findOne(array('user_name'=>$group_founder));
                try {
                    $result = $this->db->group->insert ( $data );
                    return $this->addGroupInFounder($group_founder,$group_foundTime,$group_name);

                } catch ( Exception $e ) {
                    return - 1;
                }
        }else{
            return 1;
        }
	}
    
    function updateGroup($group_founder, $group_name, $group_intro, $group_publicType, $group_avatar,$group_id) {
        if ($this->yiquan_version == 0) {
            return - 2;
        }
        
        if ($this->checkToken () == 0) {
            return - 3;
        }
        
        if (! isset ( $_COOKIE ['user'] ) || $_COOKIE ['user'] != $group_founder) {
            return - 4;
        }
        $this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
        
        $group_foundTime = time ();
        
        $former = $this->db->group->findOne (
                                             array (
                                                    '_id' => new MongoId ($group_id)
                                                    )
                                             );
        if  ($former == null){
            return -5;
        }

        
        if ($group_founder != $former['group_founder']){
            return -6;
        }
        
        try {
            
            $former['group_name'] = $group_name;
            $former['group_intro'] = $group_intro;
            $former['group_publicType'] = $group_publicType;
            
            if  ($group_avatar != ''){
                $rawpic = base64_decode ( $group_avatar );
                
                $im = new Imagick ();
                $im->readImageBlob ( $rawpic );
                $geo = $im->getImageGeometry ();
                $w = $geo ['width'];
                $h = $geo ['height'];
                $maxWidth = $maxHeight = 160;
                $fitbyWidth = (($maxWidth / $w) < ($maxHeight / $h)) ? true : false;
                
                if ($fitbyWidth) {
                    $im->thumbnailImage ( $maxWidth, 0, false );
                } else {
                    $im->thumbnailImage ( 0, $maxHeight, false );
                }
                
                // save to qiniu
                $auth = new Auth ( $this->qiniuAK, $this->qiniuSK );
                $bucket = 'yiquanhost-avatar';
                $uploadMgr = new UploadManager ();
                $bucketMgr = new BucketManager ( $auth );
                
                $token = $auth->uploadToken ( $bucket );
                list ( $ret, $err ) = $uploadMgr->put ( $token, null, $rawpic );
                if ($err == null) {
                    $bigAvatar = $this->userpicbucketUrl . '/' . $ret ['key'];
                    $former['group_bigAvatar'] = $bigAvatar;
                } else {
                    return $err;
                }
                
                list ( $ret, $err ) = $uploadMgr->put ( $token, null, $im );
                if ($err == null) {
                    $smallAvatar = $this->userpicbucketUrl . '/' . $ret ['key'];
                    $former['group_smallAvatar'] = $smallAvatar;
                } else {
                    return $err;
                }
            }
            
            $this->db->group->save ($former);
            return 1;
        
            
        } catch ( Exception $e ) {
            return - 1;
        }
 
    }
    
    
    function addGroupInFounder ($group_founder,$group_foundTime,$group_name){
        $user = $this->db->user->findOne(
                                         array ( 'user_name' => $group_founder)
                                         );
        $group = $this->db->group->findOne (
                                            array (
                                                   'group_founder' => $group_founder,
                                                   'group_foundTime' => $group_foundTime,
                                                   'group_name' =>$group_name
                                                   )
                                            );
        if ($group != null){
            array_push ($user['user_groups'],$group['_id']);
            $this->db->user->save ($user);
            return 1;
        }else{
            return -2;
        }
    }
    
    function queryMyGroups ($user_name,$update_time){
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
        $update_int = (int) $update_time;
        
        try {
            $user = $this->db->user->findOne (array ('user_name'=> $user_name));
            //$groups = $user['user_groups'];
            
            $res = $this->db->group->find (
                                           
                                           array(
                                                 '_id'=> array (
                                                                '$in' =>$user['user_groups']
                                                                ),
                                                 'group_latestUpdate'=> array(
                                                                              '$lt' => $update_int
                                                                              )
                                                 )
                                        )->sort ( array ('group_latestUpdate' => -1));
            $res_array = array ();
            
            $user = new User ();
            $firstCount = $user->countFirstFriendsByName ( $user_name );
            $secondCount = $user->countAllFriendsByName ( $user_name );
            //增加一度好友圈和二度好友圈的数据
            $first = array (
                            "_id" => 'first',
                            "group_founder" => $user_name,
                            "group_name" => '我的好友圈',
                            "group_intro" => '我和所有好友交流的场所',
                            "group_memberCount" => $firstCount
                            );
            array_push ($res_array, $first);
            $second = array (
                            "_id" => 'second',
                            "group_founder" => $user_name,
                            "group_name" => '朋友和他们的朋友们',
                            "group_intro" => '朋友的朋友最靠谱，找人帮忙认识新朋友的好去处',
                             "group_memberCount" => $secondCount
                            );
            array_push ($res_array, $second);
            
            foreach ($res as $key => $value){
                array_push ($res_array, $value);
            }
            
            
            return json_encode ($res_array);
        }catch (Exception $e){
            return $e;
        }
    }
    
    function queryMemberByGroup ($user_name,$group_id){
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

        try {
            $group = $this->db->group->findOne (array ('_id' => new MongoId ($group_id)));
            $memberList = $group['group_memberList'];
            
            $res = array ();
            foreach ($memberList as $member){
                $user = $this->db->user->findOne ( array ('user_name' => $member),array (
                                                                                         '_id' => 1,
                                                                                         'user_name' => 1,
                                                                                         'user_mobile' => 1,
                                                                                         'user_nickname' => 1,
                                                                                         // 'user_pic' => 1,
                                                                                         'user_smallavatar' => 1 ));
                array_push ($res, $user);
            }
            return json_encode ($res);
        }catch (Exception $e)
        {
            return $e;
        }}
    
    
    function inviteToGroup ($group_inviter,$group_member, $group_id){
        if ($this->yiquan_version == 0) {
            return - 2;
        }
        
        if ($this->checkToken () == 0) {
            return - 3;
        }
        
        if (! isset ( $_COOKIE ['user'] ) || $_COOKIE ['user'] != $group_inviter) {
            return - 4;
        }
        $this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );

        try {
            $group = $this->db->group->findOne (array ('_id' => new MongoId ($group_id)));
            $user = $this->db->user->findOne (array ('user_name' => $group_member));

            $groupName = $group['group_name'];
            $msgTitle = '我已邀请你加入圈子： ' . $groupName . '。';

            if  (in_array ($group_member,$group['group_memberList'])){
                return 3;
            }else{
                array_push ($group['group_memberList'], $group_member);
                $this->db->group->save ($group);
                
                array_push ($user['user_groups'],new MongoId ($group_id));
                $this->db->user->save ($user);
                
                $message = new Message ();
                $state = $message->addMessage($group_inviter, $group_member, 'userMessage', $msgTitle, '私密消息', '','');
                return $state;
            }
        }catch (Exception $e){
            return $e;
        }
        
        
    }
    function removeFromGroup ($group_remover,$group_member, $group_id){
        if ($this->yiquan_version == 0) {
            return - 2;
        }
        
        if ($this->checkToken () == 0) {
            return - 3;
        }
        
        if (! isset ( $_COOKIE ['user'] ) || $_COOKIE ['user'] != $group_remover) {
            return - 4;
        }
        $this->logCallMethod ( $this->getCurrentUsername (), __METHOD__ );
        
        
        $group = $this->db->group->findOne (array ('_id' => new MongoId ($group_id)));
        
        if ($group_remover != $group['group_founder']){
            return -6;
        }
        
        try {
            if  (in_array ($group_member,$group['group_memberList'])){
                $where = array ('_id' => new MongoId ($group_id));
                $param = array ("\$pull" => array ( 'group_memberList' => $group_member));
                
                $result = $this->db->group->update ($where, $param);
                
                $where1 = array ('user_name' => $group_member);
                $param1 = array ("\$pull" => array ( 'user_groups' => new MongoId ($group_id)));
                $result1 = $this->db->user->update ($where1,$param1);
                
                return 1;
            }
        }catch (Exception $e){
            return $e;
        }
        
        
    }
    
    
    /*
    protected function QiniuUploadpic(&$arr, $bigdata, $smalldata) {
        $auth = new Auth ( $this->qiniuAK, $this->qiniuSK );
        $bucket = 'yiquanhost-avatar';
        $uploadMgr = new UploadManager ();
        $bucketMgr = new BucketManager ( $auth );
        
        if (isset ( $arr ['user_bigavatarname'] )) {
            list ( $ret, $err ) = $bucketMgr->delete ( $bucket, $arr ['user_bigavatarname'] );
        }
        if (isset ( $arr ['user_smallavatarname'] )) {
            list ( $ret, $err ) = $bucketMgr->delete ( $bucket, $arr ['user_smallavatarname'] );
        }
        
        $token = $auth->uploadToken ( $bucket );
        list ( $ret, $err ) = $uploadMgr->put ( $token, null, $bigdata );
        if ($err == null) {
            $arr ['user_bigavatarname'] = $ret ['key'];
            $arr ['user_bigavatar'] = $this->userpicbucketUrl . '/' . $ret ['key'];
        } else {
            return $err;
        }
        
        list ( $ret, $err ) = $uploadMgr->put ( $token, null, $smalldata );
        if ($err == null) {
            $arr ['user_smallavatarname'] = $ret ['key'];
            $arr ['user_smallavatar'] = $this->userpicbucketUrl . '/' . $ret ['key'];
        } else {
            return $err;
        }
        
    */
    
	// $a = new Topic ();
// var_dump($a->addRichTopic ( 'abc2', '11', 'wuliaohuati','', 'qiuzhidao,qiujiaowang', '', '<html xmlns=http://www.w3.org/1999/xhtml><head><meta http-equiv=Content-Type content="text/html;charset=utf-8"><link href="http://7xid8v.com2.z0.glb.qiniucdn.com/style.css" rel="stylesheet"></head><body><div>这个问题其实是相当复杂的。从 Google 的发布会提出 Material Design 的设计规范以来，许多人都为 Material Design 所惊艳到。但事实上，大多数应用都无法很好及时跟进这一设计标准。这一点，尤其是在国内，显得更为的突出。我自己实践 Material Design 也有了一段时间，我觉得至少有三点原因。</div><div><br></div><div>一、Material Design 设计语言非常复杂，学习成本高，实现难度大。</div><div><br></div><div>于 Material Design 复杂的设计语言相比，我敢说，学习难度比你跟进 iOS 的平面化的开发标准要困难十倍以上。Material Design 并不是使用 Google 提供的这些控件、图片设计出来的东西就是 Material Design 了。Material Design 的核心是一个高度抽象化的设计逻辑是对真实事物的逻辑层面的模拟，比起 iOS 以前那种单纯视觉上的拟物比起来，这是一种非常高层次的拟物概念，理解起来确实比较费事。</div><div><br></div><div>举个例子来说，</div><div>当你有一个如同这样的页面布局。</div><div><br></div><div>这样的页面布局下，当用户手指从下向上滚动屏幕的时候，我们先想象一下，这个布局应该如何跟随调整？通常情况下，我们会选择整页内容一起向上滚动。但实际上，这种方法并不是很正确。</div><div><br></div><div>我们仔细观察这个布局，去掉状态栏，这个页面也有五个不同的“色块”组成的独立元素。他们分别是 用来选择操作的顶栏Toolbar，然后是 Featured Image，然后是 Topic，下方的 Detail，和一个标记状态的 Button。</div><div><br></div><div>然后我们把这五个东西想象成五张真实存在的纸片，他们堆叠在一起类似于下图这样的：</div><div><br></div><div>当你移动下面的 Detail 页的时候，其他元素其实应该有着不同的相对运动才对，而不是整体上移。比如 Featured Image 不动，下面的纸片从它上方运动覆盖移动过去，而推到顶时，Topic 页可以成为这页的标题，而下方的 Detail 也继续移动。这个设计来自于 Google I/O 2014 App 的设计。（此应用源代码可以到 GitHub 下载）</div><div><br></div><div>这样的设计逻辑并不是来自于哪个现成的模板，而是针对你应用的不同布局不同考虑的，甚至是像素级的细节考虑，对设计者的要求很高，对程序实现的要求同样也很高。这是 Material Design 中许多细腻的 “激动人心的细节” 背后深藏的设计逻辑。更何况，我只能说，我举的这个例子也是 Material Design 复杂语言的一个很小的部分而已。</div><div><br></div><div>二、Material Design 的设备兼容性不够好。</div><div><br></div><div>Material Design 的设备兼容性是比较差的，当然比起当年 Holo 设计在 Android 2.x 上的完全不兼容不同，Material Design 是可以做到 4.x 的半兼容的。所谓半兼容，指的是使用 Google 提供的控件和兼容包，可以基本显示。但是比如状态栏的颜色的设置、各个控件的 elevation 阴影、selectableBackground 的按钮响应动画都会失效。</div><div><br></div><div>（如上图这样的 Elevation 效果，在 Android 4.x 上会被直接“压扁”显示）</div><div><br></div><div>而与 Android 2.x 更是完全不兼容了。开发者即使愿意忍痛让 4.x 用户看一个不完整的设计，也不能满足 2.x 用户的兼容需求。</div><div><br></div><div>虽然这种苛刻的兼容需求对于大多数应用来说都不是很有关系，但是比如像 QQ（最低兼容至 Android 1.6）、微信（最低兼容至 Android 2.2）这样的应用，他们的市场的广度迫使他们不能尝试这样的事情，毕竟在中国，使用 Android 2.x 的手机的用户依然还是有相当一部分的。</div><div><br></div><div>在这样的背景下，Material Design 在 QQ、微信、淘宝 这样的应用上，短时间是不可能实现的。他们处于兼容性的考量，需要使用系统最基础的控件以及利用这些控件组合的自定义控件，而不能去使用高版本才拥有的特性。这样的应用又卡、又慢、又丑也是有一定客观原因的。</div><div><br></div><div>三、Material Design 的一些其他劣势。</div><div><br></div><div>当然上述的两点原因并不是一些大厂商不使用 Material Design，宁可用自己设计的极丑无比的界面的唯一原因。还有一些细碎的原因，也是左右这个设计普及受阻的砝码。比如像阿里、腾讯、百度这样的企业，他们并不是设计驱动的，而是商业驱动的。如果跟进 Material Design，势必会影响他们的一些商业利益。</div><div><br></div><div>比如说，页面的逻辑会受到牵制，他们再也无法放一些活动、广告的按钮放在用户最易点击的地方，颜色也不能总是整片整片的大红大绿。一些页面的访问频次会随着逻辑层级变深而降低。这也是当时 微信 5.2 测试版刚开始试图 Holo 化又叫停的重要原因。</div><div><br></div><div>（微信 5.2 内测版截图，图源网络）</div><div>更有一些想法是希望 Android 和 iOS 能拥有一样的 UI，使得用户降低学习成本，更快上手，更好赚钱。所以在 Android 上出现底栏两层皮、三层皮什么的就是出于这样的想法。</div><div><br></div><div>在目前中国市场上，用户对设计的品味还处于一个比较初级的阶段，对设计几乎没有要求。而你就算有要求，你为了使用应用也愿意去做这样的妥协。开发者做跟进花费的代价远小于他们的收益。恐怕这是许多公司宁可设计一套奇丑无比的 UI，也不愿意跟进 Material Design 的核心原因。</div></body></html>' ));
}
?>
