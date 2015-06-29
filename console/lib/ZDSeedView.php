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


	function listSeedStat_table($arr=[]){

		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';

		th_combiner('complete但是没有文章');
		th_combiner('没有文章率');

		th_combiner('uncompleted');

		echo '<tr></thead>';


			echo '<tr>';
			$c_notext = $this->db->Proseed->count (array('seed_completeStatus'=>'completed', 'seed_text'=> ''));
			td_combiner($c_notext);
			$c_all = $this->db->Proseed->count ();
			$notext_ratio=round($c_notext/$c_all*100,2);
			td_combiner($notext_ratio.'%');
		
			

			$c_uncompleted = $this->db->Proseed->count (array('seed_completeStatus'=>'uncompleted'));
			td_combiner($c_uncompleted);

			echo '</tr>';
		
		echo '</table></div>';
	}



	function listAllSeedStat_table($arr, $start, $len){

		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';

		$ans = [ ];
		
		$cus=$this->db->Prosource->find( array('source_status' => 'active'));
		while ( $cus->hasNext () ) {
			$doc = $cus->getNext ();
			$ans [] = $doc;
		}
		
	
		


		th_combiner('媒体名称');
		th_combiner( '近三天·文章数目');
		th_combiner( '24小时内·文章数目');
		th_combiner( '昨天·文章数目');
		th_combiner( '前天·文章数目');
		th_combiner( '三天前·文章数目');
		th_combiner( '四天前·文章数目');
		th_combiner( '五天前·文章数目');
		th_combiner( '六天前·文章数目');
		th_combiner( '没文章总数');
		th_combiner( '没文章总率');



		echo '<tr></thead>';
		
		for($i = $start; $i < min ( $start + $len, count ( $ans ) ); $i ++) {
			echo '<tr>';

			td_combiner( $ans[$i]['source_name']);
			$count=$this->db->Proseed->count( array('seed_sourceID' => (string)$ans[$i]['_id'],'seed_dbWriteTime'=>array('$gt'=>time()-259200)));
			td_combiner($count);
			$count_d0=$this->db->Proseed->count( array('seed_sourceID' =>  (string)$ans[$i]['_id'],'seed_dbWriteTime'=>array('$gt'=>time()-86400)));
			td_combiner($count_d0);
			$count_d1=$this->db->Proseed->count( array('seed_sourceID' =>  (string)$ans[$i]['_id'],'seed_dbWriteTime'=>array('$lt'=>time()-86400,'$gt'=>time()-172800)));
			td_combiner($count_d1);
			$count_d2=$this->db->Proseed->count( array('seed_sourceID' =>  (string)$ans[$i]['_id'],'seed_dbWriteTime'=>array('$lt'=>time()-172800,'$gt'=>time()-259200)));
			td_combiner($count_d2);
			$count_d3=$this->db->Proseed->count( array('seed_sourceID' =>  (string)$ans[$i]['_id'],'seed_dbWriteTime'=>array('$lt'=>time()-259200,'$gt'=>time()-345600)));
			td_combiner($count_d3);
			$count_d4=$this->db->Proseed->count( array('seed_sourceID' => (string)$ans[$i]['_id'],'seed_dbWriteTime'=>array('$lt'=>time()-345600,'$gt'=>time()-432000)));
			td_combiner($count_d4);
			$count_d5=$this->db->Proseed->count( array('seed_sourceID' =>  (string)$ans[$i]['_id'],'seed_dbWriteTime'=>array('$lt'=>time()-432000,'$gt'=>time()-518400)));
			td_combiner($count_d5);
			$count_d6=$this->db->Proseed->count( array('seed_sourceID' =>  (string)$ans[$i]['_id'],'seed_dbWriteTime'=>array('$lt'=>time()-518400,'$gt'=>time()-604800)));
			td_combiner($count_d6);
			$count_notext=$this->db->Proseed->count( array('seed_sourceID' => (string)$ans[$i]['_id'],'seed_text'=>''));
			td_combiner($count_notext);
			$count_all=$this->db->Proseed->count( array('seed_sourceID' => (string)$ans[$i]['_id']));
			if($count_all!=0){
				$notext_ratio=round($count_notext/$count_all*100,2).'%';
			}else{
				$notext_ratio='没有文章';
			}
			td_combiner($notext_ratio);

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


#################################################################################
	function getDailyReport($arr) {
		// var_dump($arr);
		foreach ( $arr as $key => $v ) {
			// var_dump($key);
			echo '<div class="table-responsive"><h2>' . date ( 'Y-m-d', $key ) . '</h2><hr/>';
			echo '<h3>文章统计</h3><hr/>';
			echo '<table class="table table-striped">';
			echo '<thead><tr><th>内容</th><th>值</th></tr></thead>';
			foreach ( $v ['seed'] as $key2 => $v2 ) {
				echo '<tr><td>' .  $key2 . '</td><td>' . $v2 . '</td></tr>';
			}
			echo '</table><hr/>';
			
			
			
			echo '</div>';
		}
	}
	










	/*function getReport($arr) {
		// var_dump($arr);
		foreach ( $arr as $key => $v ) {
			// var_dump($key);
			echo '<div class="table-responsive"><h2>' . date ( 'Y-m-d', $key ) . '</h2><hr/>';
			echo '<h3>文章统计</h3><hr/>';
			echo '<table class="table table-striped">';
			echo '<thead><tr><th>内容</th><th>值</th></tr></thead>';
			foreach ( $v ['active'] as $key2 => $v2 ) {
				echo '<tr><td>' . $this->tochinese ( $key2 ) . '</td><td>' . $v2 . '</td></tr>';
			}
			echo '</table><hr/>';
			
			
			
			echo '</div>';
		}
	}*/




	function getDailyReportSearchform() {
		echo '<div><form method="post" action="?action=查询">';
		echo '<script language=javascript src="images/DatePicker.js"></script>';
		echo '<div class="form-group"><h2>选择开始时间</h2></div>';
		echo '<input name="starttime" id="sttime" type="text" onfocus="setday(this)" readonly="readonly" />';
		echo '<input type="submit" value="查询" />';
		echo '</form></div>';
	}









}