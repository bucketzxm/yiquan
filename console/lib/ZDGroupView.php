<?php
require_once 'ZDGroup.php';

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

####################
#    combiners     #
####################



function th_combiner($content){
	echo '<th>'.$content.'</th>';
}

function td_combiner($content){
	echo '<td>'.$content.'</td>';
}#####################################
class GroupView extends Group {
	// private $dbname = 'test';
	private $table = 'Group';
	function listAllGroupStat_table($arr, $start, $len){

		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';

		th_combiner('Group名称');
		th_combiner( '收入媒体数');
		th_combiner('关注人数');
		th_combiner('值得一读数量');

		echo '<tr></thead>';


		for($i = $start; $i < min ( $start + $len, count ( $arr ) ); $i ++) {
			echo '<tr>';
			$uid = $arr [$i] ['_id']->{'$id'};

			td_combiner((isset($arr[$i]['mediaGroup_title'])? $arr[$i]['mediaGroup_title']: ''));
			td_combiner((isset($arr[$i]['mediaGroup_counts']['media_count'])? $arr[$i]['mediaGroup_counts']['media_count']:''));
			td_combiner((isset($arr[$i]['mediaGroup_counts']['follower_count'])? $arr[$i]['mediaGroup_counts']['follower_count']:''));
			td_combiner((isset($arr[$i]['mediaGroup_counts']['worth_count'])? $arr[$i]['mediaGroup_counts']['worth_count']:''));
			
			

			echo '</tr>';

		}
		echo '</table></div>';
	}
	
}






?>
