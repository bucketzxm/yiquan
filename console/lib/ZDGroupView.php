<?php
require_once 'ZDGroup.php';
require_once 'ZDMedia.php';
require_once 'ZDMediaView.php';



use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

####################
#    combiners     #
####################




#####################################
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

	function listAllGroupBasic_table($arr, $start, $len){

		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		th_combiner('编辑操作');

		th_combiner('Group名称');
		th_combiner( 'Group介绍');
		th_combiner('鸣谢');

		echo '<tr></thead>';


		for($i = $start; $i < min ( $start + $len, count ( $arr ) ); $i ++) {
			echo '<tr>';
			$uid = $arr [$i] ['_id']->{'$id'};
			echo '<td><a href="?action=editGroupBasic&mindex=' . $arr [$i] ['_id']->{'$id'} . '">编辑</a></td>';
			
			td_combiner((isset($arr[$i]['mediaGroup_title'])? $arr[$i]['mediaGroup_title']:''));
			td_combiner((isset($arr[$i]['mediaGroup_detail'])? $arr[$i]['mediaGroup_detail']:''));
			td_combiner((isset($arr[$i]['mediaGroup_thanknote'])? $arr[$i]['mediaGroup_thanknote']:''));
		

			echo '</tr>';

		}
		echo '</table></div>';
	}





		function listAllGroupMedia_table($arr, $start, $len){

		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		th_combiner('编辑操作');

		th_combiner('Group名称');
		th_combiner( 'Group媒体');

		echo '<tr></thead>';


		for($i = $start; $i < min ( $start + $len, count ( $arr ) ); $i ++) {
			echo '<tr>';
			$uid = $arr [$i] ['_id']->{'$id'};
			echo '<td><a href="?action=editGroupMedia&mindex=' . $arr [$i] ['_id']->{'$id'} . '">编辑</a></td>';
			
			td_combiner((isset($arr[$i]['mediaGroup_title'])? $arr[$i]['mediaGroup_title']:''));
			$media_List=[];

			if (isset($arr[$i]['mediaGroup_sourceList'])){
				echo '<td>';
				$s_List=$arr[$i]['mediaGroup_sourceList'];
				foreach ($s_List as $key => $value) {
					$cus=$this->db->Prosource->find( array('_id' => new MongoId("$value") ));
					$media_List[]=$cus;
					echo $media_List[0]['source_name'].',';
				echo '/td';
				}
					

				
			}

			echo '</tr>';

		}
		echo '</table></div>';
	}
	function showOneGroupBasic_form($arr) {
		echo '<div><form method="post" action="?action=editGroupBasic">';
		echo '<input type="hidden" class="form-control" name="id" value="' . $arr ['_id']->{'$id'} . '"/>';
		echo '<div class="form-group"><h2>Group名称</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="title">' . $arr ['mediaGroup_title'] . '</textarea></div>';
		echo '<div class="form-group"><h2>Group介绍</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="detail">' . (isset($arr ['mediaGroup_detail']) ? $arr ['mediaGroup_detail']:'' ). '</textarea></div>';
		echo '<div class="form-group"><h2>鸣谢</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="thanknote">' . (isset($arr ['mediaGroup_thanknote']) ? $arr ['mediaGroup_thanknote']:''). '</textarea></div>';

		


		echo '<div class="form-group"><input type="submit" value="提交" /></div>';
		echo '</form></div>';
	}



	
}






?>
