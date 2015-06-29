<?php
require_once 'ZDSeed.php';

####################
#    combiners     #
####################



function th_combiner($content){
	echo '<th>'.$content.'</th>';
}

function td_combiner($content){
	echo '<td>'.$content.'</td>';
}#####################################

###########
function is_notempty($name){
	(isset($name) ? $name : '');
}
###########

class SeedView extends Seed{
	function listAllSeed_table($arr, $start, $len){

		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		th_combiner('编辑操作');
		th_combiner('删除操作');

		th_combiner('文章名称');
		th_combiner( '所属媒体');
		th_combiner('行业或标签');
		th_combiner('有无文章');

		th_combiner('值得一读数');
		th_combiner('热度');

		echo '<tr></thead>';


		for($i = $start; $i < min ( $start + $len, count ( $arr ) ); $i ++) {
			echo '<tr>';
			$uid = $arr [$i] ['_id']->{'$id'};
			echo '<td><a href="?action=editSeed&mindex=' . $arr [$i] ['_id']->{'$id'} . '">编辑</a></td>';
			echo '<td><a href="?action=deleteSeed&mindex=' . $arr [$i] ['_id']->{'$id'} . '">删除</a></td>';
			td_combiner((isset($arr[$i]['seed_title'])? $arr[$i]['seed_title']:''));
			td_combiner((isset($arr[$i]['seed_source'])? $arr[$i]['seed_source']:''));


			$industry=(isset($arr[$i]['seed_industry'])? implode(',',$arr[$i]['seed_industry']): '');
			td_combiner($industry);
			if (strlen($arr[$i]['seed_text'])<=1) {
				td_combiner("没有文章");
			}else{
				td_combiner("有文章");
			}
			td_combiner((isset($arr[$i]['seed_agreeCount']) ? $arr[$i]['seed_agreeCount']: ''));
			td_combiner((isset($arr[$i]['seed_hotness']) ? $arr[$i]['seed_hotness']: ''));

			echo '</tr>';

		}
		echo '</table></div>';
	}



	function listAllSeedStat_table($arr, $start, $len){

		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		$cus=$this->db->Prosource->find( array('source_status' => $active));


		th_combiner('媒体名称');
		th_combiner( '近三天·文章数目');


		echo '<tr></thead>';
		if ($cus!=null){
			for($i = $start; $i < min ( $start + $len, count ( $cus ) ); $i ++) {
				echo '<tr>';
				td_combiner((isset($cus[$i]['source_name'])? $cus[$i]['source_name']:''));
				$count=$this->db->Proseed->count( array('seed_source' => $cus[$i]['source_name']));
				td_combiner($count);

			}


		

	






			echo '</tr>';

		}
		echo '</table></div>';
	}

	function showOneSeed_form($arr) {
		echo '<div><form method="post" action="?action=editSeed">';
		echo '<input type="hidden" class="form-control" name="id" value="' . $arr ['_id']->{'$id'} . '"/>';
		echo '<div class="form-group"><h2>文章名称</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="title">' . $arr ['seed_title'] . '</textarea></div>';
		echo '<div class="form-group"><h2>值得一读数量</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="agreeCount">' . (isset($arr ['seed_agreeCount']) ? $arr ['seed_agreeCount']:'' ). '</textarea></div>';
		echo '<div class="form-group"><h2>热度</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="hotness">' . (isset($arr ['seed_hotness']) ? $arr ['seed_hotness']:'') . '</textarea></div>';

		echo '<div class="form-group"><h2>行业或标签,逗号隔开</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="industry">' . (isset($arr ['seed_industry']) ? implode(',', $arr ['seed_industry']) :''). '</textarea></div>';



		echo '<div class="form-group"><input type="submit" value="提交" /></div>';
		echo '</form></div>';
	}

	function showDeleteSeedView($id) {
		echo '<form method="post" action="?action=deleteSeed">';
		echo '确定删除该吗？';
		echo $id;
		echo '<input type="hidden" name="qid" value="' . $id . '" />';
		echo '<div class="form-group"><input type="submit" value="确定" /></div>';
		echo '</form>';
	}
















}