<?php
require_once 'YqBase.php';
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
/* Report all errors except E_NOTICE */
// error_reporting ( E_ALL & ~ E_NOTICE );
class ZDUser extends YqBase {
	protected $bcs_host = 'bcs.duapp.com';

	function getAllUsersInfo($configs = []) {
		$ans = [ ];
		if (empty ( $configs )) {
			$cus = $this->db->Prouser->find (  );
			while ( $cus->hasNext () ) {
				$doc = $cus->getNext ();
				#S$this->getUserDetailInfo ( $doc );
				$ans [] = $doc;
			}
		}
		else
		{
			$cus = $this->db->Prouser->find ();
			while ( $cus->hasNext () ) {
				$doc = $cus->getNext ();
				#$this->getUserDetailInfo ( $doc );
				$ans [] = $doc;
			}
		}
		
		//if (isset ( $configs ['sortby'] ) && isset ( $configs ['sorttype'] )) {
			$newans = $this->array_sort ( $ans, $configs ['sortby'], $configs ['sorttype'] );
		//}
		//var_dump ( $newans );
		return $newans;
	}

	function getUserDetailInfo(&$arr) {
		$arr ['QuoteCount'] = $this->db->Quote->count ( array (
				'quote_ownerID' => $arr ['_id']->{'$id'} 
		) );
		$intvalday = round ( abs ( time () - $arr ['user_regdate']->sec ) / 3600 / 24 ) + 1;
		$arr ['QuotePerday'] = round ( ($arr ['QuoteCount'] / $intvalday) * 100 ) / 100;
		// var_dump ( $arr );
		$cus = $this->db->Quote->find ( array (
				'quote_ownerID' => $arr ['_id']->{'$id'} 
		) );
		$beizan = 0;
		while ( $cus->hasNext () ) {
			$doc = $cus->getNext ();
			$beizan += $doc ['quote_likeCount'];
		}
		
		$arr ['totalbeliked'] = $beizan;
		
		if ($arr ['QuoteCount'] == 0) {
			$arr ['likedperQuote'] = 0;
		} else {
			$arr ['likedperQuote'] = round ( $beizan / $arr ['QuoteCount'], 3 );
		}
		$dianzan = 0;
		$cus = $this->db->callmethodlog->find ( array (
				'user_name' => $arr ['_id']->{'$id'} 
		) );
		while ( $cus->hasNext () ) {
			$doc = $cus->getNext ();
			if (isset ( $doc ['methods'] ['likeQuotes'] )) {
				$dianzan += $doc ['methods'] ['likeQuotes'];
			}
		}
		$arr ['totalLikeQuotes'] = $dianzan;
		$arr ['totalLikeQuotesPerday'] = round ( $dianzan / $intvalday, 2 );
	}
	function array_sort($arr, $keys, $type = 'asc') {
	}

}

/*
 * $a = new User (); // $t = json_decode ( $a->getRegisterCode ( '13564957795', 30 ), true ); // echo $t['msg']; $r=$a->checkRegisterCode('13564957795','uw89'); echo $r; $a = new User (); echo $a->enhanceRelationshipByName ( 'abc1', 'abc2', 100 ); $a = new User (); echo $a->weihu (); echo $a->changeSecondName ( 'abc0', 'kkmmjj2222', 'abc2' ); var_dump ( $a->getSecondFriendStats ( 'abc0' ) ); $a->reg ( 'abc1', '110', '110' ); $a->reg ( 'abc2', '112', '110' ); $a->reg ( 'abc3', '110', '110' ); $a->reg ( 'abc4', '110', '110' ); $a->reg ( 'abc5', '110', '110' ); $a->reg ( 'abc6', '110', '110' ); $a->reg ( 'abc7', '110', '110' ); $a->addProfileByName ( 'abc0', '{"profile_city":"shanghai"}' ); $a->addProfileByName ( 'abc1', '{"profile_city":"shanghai"}' ); $a->addProfileByName ( 'abc2', '{"profile_city":"shanghai"}' ); $a->addProfileByName ( 'abc3', '{"profile_city":"shanghai"}' ); $a->addProfileByName ( 'abc4', '{"profile_city":"shanghai"}' ); $a->addProfileByName ( 'abc5', '{"profile_city":"shanghai"}' ); $a->addProfileByName ( 'abc6', '{"profile_city":"shanghai"}' ); $a->addProfileByName ( 'abc7', '{"profile_city":"山东"}' ); $a->addFriendByName ( 'abc0', 'abc2' ); $a->addFriendByName ( 'abc1', 'abc2' ); $a->addFriendByName ( 'abc1', 'abc5' ); $a->addFriendByName ( 'abc5', 'abc3' ); $a->addFriendByName ( 'abc3', 'abc4' ); $a->addFriendByName ( 'abc5', 'abc6' ); $a->addFriendByName ( 'abc6', 'abc7' ); $a->addFriendByName ( 'abc4', 'abc7' ); $a->addFriendByName ( 'abc4', 'abc1' ); $a->addFriendByName ( 'abc2', 'abc4' ); $a->addFriendByName ( 'abc2', 'abc5' ); $a->addFriendByName ( 'abc0', 'abc2' ); echo $a->queryFirstFriendsByName ( 'abc0' ); echo $a-> ( 'abc0' ); echo $a->queryAllFriendsByName ( 'abc0' ); echo $a->countAllFriendsByName ( 'abc0' ); echo $a->querySecondFriendsByName ( 'abc0' ); echo $a->countSecondFriendsByName ( 'abc0' ); echo $a->listSecondFriendsByName ( 'abc0' ); echo $a->listAllFriendsByName ( 'abc0' ); echo $a->queryCommonFriendsByName ( 'abc0', 'abc1' ); $a = new User (); echo $a->deleteFriendByName ( 'abc0', 'abc1' ); echo $a->queryCommonFriendsByName ( 'abc0', 'abc1' );
 */

?>
