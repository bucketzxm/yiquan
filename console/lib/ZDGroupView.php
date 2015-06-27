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
			

			if (isset($arr[$i]['mediaGroup_sourceList'])){
				echo '<td>';
				$s_List=$arr[$i]['mediaGroup_sourceList'];
				foreach ($s_List as $key => $value) {
					$media_id=$value['source_id'];
					$cus=$this->db->Prosource->findOne( array('_id' => new MongoId("$media_id") ));
					
					echo $cus['source_name'].',';
					
				}
				
				echo '</td>'	;

				
			}

			echo '</tr>';

		}
		echo '</table></div>';
	}








	function showOneGroupMedia_form($arr) {
		echo '<div><form method="post" action="?action=editGroupMedia">';
		echo '<input type="hidden" class="form-control" name="id" value="' . $arr ['_id']->{'$id'} . '"/>';
		echo '<div class="form-group"><h2>Group名称</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="title">' . $arr ['mediaGroup_title'] . '</textarea></div>';

		echo '<div class="form-group"><h2>Group媒体<br>请从输入框中删除媒体(最末逗号要清除),在下方菜单选择新添媒体</h2>';

		if (isset($arr['mediaGroup_sourceList'])) {


			$media_List= array();
			$s_List=$arr['mediaGroup_sourceList'];
			foreach ($s_List as $key=>$value) {
				$id=$value['source_id'];
				$cus=$this->db->Prosource->findOne( array('_id' => new MongoId("$id") ));
				$media_List[]=$cus;

			}
			$s_names=[];
			foreach ($media_List as $key=>$value) {
				$s_names[]=$value['source_name'];

			}
		
			echo '<textarea class="form-control" rows="3" cols="80" name="source_List">' . implode(',', $s_names). '</textarea></div>';



		}


		$a= new Media();
		$all_source=$a->queryMedia();
	
		#echo '<form action= "" method="post" name="source_List">';
		$counter=0;
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		echo '<th>请</th><th>选</th><th>择</th><th>媒</th><th>体</th></tr></thead>';
		foreach ($all_source as $key => $source_cur) {
			
			
			$source_name=$source_cur['source_name'];

			if ($counter%5==0) {
				echo '<tr>';
			}
		
			
			echo td_combiner("$source_name: ".'<input type="checkbox" name="source_box[]" value='."$source_name".'>') ;
			if ($counter%5==4) {
				echo '</tr>';
			}
		
			$counter+=1;
		}
		


		


		echo '<div class="form-group"><input type="submit" value="提交" /></div>';
		echo '</form></div>';
	}








	function showOneGroupBasic_form($arr) {
		echo '<div><form method="post" action="?action=editGroupBasic">';
		echo '<input type="hidden" class="form-control" name="id" value="' . $arr ['_id']->{'$id'} . '"/>';
		echo '<div class="form-group"><h2>Group名称</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="title">' . $arr ['mediaGroup_title'] . '</textarea></div>';
		echo '<div class="form-group"><h2>Group介绍</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="detail">' . $arr ['mediaGroup_detail']. '</textarea></div>';
		echo '<div class="form-group"><h2>鸣谢</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="thanknote">' . $arr ['mediaGroup_thanknote']. '</textarea></div>';		



		


		echo '<div class="form-group"><input type="submit" value="提交" /></div>';
		echo '</form></div>';
	}



























	function showNewMediaGroup_form($arr=[]) {
		echo '<div><form method="post" action="?action=添加新信息组">';

		echo '<div class="form-group"><h2>Group名称</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="title">' . '' . '</textarea></div>';
	echo '<div class="form-group"><h2>Group介绍</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="detail">' . ''. '</textarea></div>';
		echo '<div class="form-group"><h2>鸣谢</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="thanknote">' . ''. '</textarea></div>';

				
		echo '<div class="form-group"><h2>Group媒体<br>请在下方菜单选择新添媒体</h2>';



		$a= new Media();
		$all_source=$a->queryMedia();
	
		#echo '<form action= "" method="post" name="source_List">';
		$counter=0;
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		echo '<th>请</th><th>选</th><th>择</th><th>媒</th><th>体</th></tr></thead>';
		foreach ($all_source as $key => $source_cur) {
			
			
			$source_name=$source_cur['source_name'];

			if ($counter%5==0) {
				echo '<tr>';
			}
		
			
			echo td_combiner("$source_name: ".'<input type="checkbox" name="source_box[]" value='."$source_name".'>') ;
			if ($counter%5==4) {
				echo '</tr>';
			}
		
			$counter+=1;
		}
	
	
	


		echo '<div class="form-group"><input type="submit" value="提交" /></div>';
		echo '</form></div>';
	}


	
}






?>
