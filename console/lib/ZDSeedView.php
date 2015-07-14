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
		
		th_combiner('序号');
		th_combiner('标题');
		th_combiner( '来源');
		th_combiner('行业或标签');
		//th_combiner('正文');
		th_combiner('时间');
		th_combiner('值得');
		th_combiner('热度');
		th_combiner('推荐');

		th_combiner('编辑');
		th_combiner('枪毙');

		echo '<tr></thead>';


		for($i = $start; $i < min ( $start + $len, count ( $arr ) ); $i ++) {
			echo '<tr>';
			$uid = $arr [$i] ['_id']->{'$id'};
			th_combiner($i+1);
			echo '<td><a href="'.$arr[$i]['seed_link'].'" target="_blank">'.(isset($arr[$i]['seed_title'])? $arr[$i]['seed_title']:'').'</a></td>';
			//td_combiner((isset($arr[$i]['seed_title'])? $arr[$i]['seed_title']:''));
			td_combiner((isset($arr[$i]['seed_source'])? $arr[$i]['seed_source']:''));

			$industry=(isset($arr[$i]['seed_industry'])? implode(',',$arr[$i]['seed_industry']): '');
			td_combiner($industry);
			/*
			if (strlen($arr[$i]['seed_text'])<=1) {
				td_combiner("无");
			}else{
				td_combiner("有");
			}*/

			td_combiner((isset($arr[$i]['seed_time']) ? date("m-d",$arr[$i]['seed_time']): ''));
			td_combiner((isset($arr[$i]['seed_agreeCount']) ? $arr[$i]['seed_agreeCount']: ''));
			td_combiner((isset($arr[$i]['seed_hotness']) ? floor($arr[$i]['seed_hotness']): ''));
			td_combiner((isset($arr[$i]['seed_editorRating']) ? $arr[$i]['seed_editorRating']: '-1'));

			echo '<td><a href="?action=editSeed&mindex=' . $arr [$i] ['_id']->{'$id'} . '">编辑</a></td>';
			echo '<td><a href="?action=deleteSeed&mindex=' . $arr [$i] ['_id']->{'$id'} . '">枪毙</a></td>';

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

	function listAllMediaCategories(){
		$mediaChannels = array();
		$sources = $this->db->Prosource->find();
		foreach ($sources as $key => $value) {
			if (isset($value['source_industry'][0])) {
				if (!in_array($value['source_industry'][0], $mediaChannels)) {
					array_push($mediaChannels, $value['source_industry'][0]);
				}	
			}
			
		}

		foreach ($mediaChannels as $keys => $values) {
			echo '<h3><a href="?action=媒体分类查看&channel='.$values.'">'.$values."</a></h3>";
		}
		echo '<h3><a href="?action=媒体分类查看&channel='.'空白'.'">'.$values."</a></h3>";


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
		
	
		

		th_combiner('类别');
		th_combiner('名称');
		//th_combiner( '近三天·文章数目');
		th_combiner( '24小时内·文章数目');
		th_combiner( '昨天·文章数目');
		th_combiner( '前天·文章数目');
		th_combiner( '三天前·文章数目');
		//th_combiner( '四天前·文章数目');
		//th_combiner( '五天前·文章数目');
		//th_combiner( '六天前·文章数目');
		th_combiner( '没文章总数');
		th_combiner( '没文章总率');



		echo '<tr></thead>';
		
		for($i = $start; $i < min ( $start + $len, count ( $ans ) ); $i ++) {
			echo '<tr>';
			td_combiner( $ans[$i]['source_industry'][0]);
			td_combiner( $ans[$i]['source_name']);
			//$count=$this->db->Proseed->count( array('seed_sourceID' => (string)$ans[$i]['_id'],'seed_dbWriteTime'=>array('$gt'=>time()-259200)));
			//td_combiner($count);
			$count_d0=$this->db->Proseed->count( array('seed_sourceID' =>  (string)$ans[$i]['_id'],'seed_dbWriteTime'=>array('$gt'=>time()-86400)));
			td_combiner($count_d0);
			$count_d1=$this->db->Proseed->count( array('seed_sourceID' =>  (string)$ans[$i]['_id'],'seed_dbWriteTime'=>array('$lt'=>time()-86400,'$gt'=>time()-172800)));
			td_combiner($count_d1);
			$count_d2=$this->db->Proseed->count( array('seed_sourceID' =>  (string)$ans[$i]['_id'],'seed_dbWriteTime'=>array('$lt'=>time()-172800,'$gt'=>time()-259200)));
			td_combiner($count_d2);
			$count_d3=$this->db->Proseed->count( array('seed_sourceID' =>  (string)$ans[$i]['_id'],'seed_dbWriteTime'=>array('$lt'=>time()-259200,'$gt'=>time()-345600)));
			td_combiner($count_d3);
			/*
			$count_d4=$this->db->Proseed->count( array('seed_sourceID' => (string)$ans[$i]['_id'],'seed_dbWriteTime'=>array('$lt'=>time()-345600,'$gt'=>time()-432000)));
			td_combiner($count_d4);
			$count_d5=$this->db->Proseed->count( array('seed_sourceID' =>  (string)$ans[$i]['_id'],'seed_dbWriteTime'=>array('$lt'=>time()-432000,'$gt'=>time()-518400)));
			td_combiner($count_d5);
			$count_d6=$this->db->Proseed->count( array('seed_sourceID' =>  (string)$ans[$i]['_id'],'seed_dbWriteTime'=>array('$lt'=>time()-518400,'$gt'=>time()-604800)));
			td_combiner($count_d6);
			*/
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

		echo '<h3><a href="'.$arr['seed_link'].'" target="_blank">查看原文</a></h3>';
		echo '<h3>文章题图</h3>';
				

		echo '<div>'.$arr['seed_text'].'</div>';

		echo '<div><form method="post" action="?action=editSeed">';
		echo '<input type="hidden" class="form-control" name="id" value="' . $arr ['_id']->{'$id'} . '"/>';
		echo '<div class="form-group"><h2>文章名称</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="title" >' . $arr ['seed_title'] . '</textarea></div>';
		echo '<div class="form-group"><h2>文章题图</h2>';		
		echo '<img src="'.$arr['seed_imageLink'].'" width= 50% />';
		echo '<textarea class="form-control" rows="3" cols="80" name="imageLink">' . (isset($arr ['seed_imageLink']) ? $arr ['seed_imageLink']:'' ). '</textarea></div>';
		echo '<div class="form-group"><h2>值得一读数量</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="agreeCount">' . (isset($arr ['seed_agreeCount']) ? $arr ['seed_agreeCount']:'' ). '</textarea></div>';
		
		echo '<div class="form-group"><h2>文章所属领域</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="seedDomain">' . (isset($arr ['seed_domain']) ? $arr ['seed_domain']:'' ). '</textarea></div>';

		echo '<div class="form-group"><h2>热度</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="hotness">' . (isset($arr ['seed_hotness']) ? $arr ['seed_hotness']:'') . '</textarea></div>';
		echo '<div class="form-group"><h2>小编指数</h2>';
		echo '<h4>-1：未编辑；</h4><h4>0：一般收入；1：推荐；2：极力推荐；</h4><h4>-2：营销；-3：质量太差</h4>';
		echo '<textarea class="form-control" rows="3" cols="80" name="rating">' . (isset($arr ['seed_editorRating']) ? $arr ['seed_editorRating']:'-1') . '</textarea></div>';

		echo '<div class="form-group"><h2>行业或标签,逗号隔开</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="industry">' . (isset($arr ['seed_industry']) ? implode(',', $arr ['seed_industry']) :''). '</textarea></div>';

		//Label System
		if ($arr['seed_domain'] == 'business') {
			$all_labels = $this->db->ProMediaGroup->find(array('group_type' => 'business'))->sort(array('group_rank' => 1));
			$counter = 0;
			echo '<div class="table-responsive"><table class="table table-striped">';
			echo '<thead><tr>';
			echo '<th>商业栏目(必填)</th><th></th><th></th><th></th><th></th></tr></thead>';
			foreach ($all_labels as $key => $source_cur) {
				
				$source_name=$source_cur['mediaGroup_title'];//['source_name'];
				if ($counter%5==0) {
					echo '<tr>';
				}
				echo td_combiner("$source_name: ".'<input type="checkbox" name="source_box[]" value='."$source_name".'>') ;
				if ($counter%5==4) {
					echo '</tr>';
				}
				$counter+=1;
			}
			echo '</table></div>';


			$modeList = $this->db->Prosystem->findOne(array('para_name' => 'business_functions'));

		
			$counter = 0;
			echo '<div class="table-responsive"><table class="table table-striped">';
			echo '<thead><tr>';
			echo '<th>商业职能</th><th></th><th></th><th></th><th></th></tr></thead>';
			foreach ($modeList['mode_list'] as $key => $source_cur) {
				
				$source_name=$source_cur;///['mediaGroup_title'];//['source_name'];
				if ($counter%5==0) {
					echo '<tr>';
				}
				echo td_combiner("$source_name: ".'<input type="checkbox" name="source_box[]" value='."$source_name".'>') ;
				if ($counter%5==4) {
					echo '</tr>';
				}
				$counter+=1;
			}	
		
			echo '</table></div>';

			$modeList = $this->db->Prosystem->findOne(array('para_name' => 'business_modes'));

		
			$counter = 0;
			echo '<div class="table-responsive"><table class="table table-striped">';
			echo '<thead><tr>';
			echo '<th>商业内容模式</th><th></th><th></th><th></th><th></th></tr></thead>';
			foreach ($modeList['mode_list'] as $key => $source_cur) {
				
				$source_name=$source_cur;///['mediaGroup_title'];//['source_name'];
				if ($counter%5==0) {
					echo '<tr>';
				}
				echo td_combiner("$source_name: ".'<input type="checkbox" name="source_box[]" value='."$source_name".'>') ;
				if ($counter%5==4) {
					echo '</tr>';
				}
				$counter+=1;
			}	
		
			echo '</table></div>';

			$mediaGroups = $this->db->ProMediaGroup->find(array('group_type' => 'business'))->sort(array('group_rank' => 1));
			echo '<h2>'.'行业细分'.'</h2>';
			foreach ($mediaGroups as $keyGroup => $valueGroup) {
				$counter = 0;
				
				echo '<div class="table-responsive"><table class="table table-striped">';
				echo '<thead><tr>';
				echo '<th>'.$valueGroup['mediaGroup_title'].'</th><th></th><th></th><th></th><th></th></tr></thead>';
				foreach ($valueGroup['mediaGroup_segments'] as $key => $source_cur) {
					
					$source_name=$source_cur;///['mediaGroup_title'];//['source_name'];
					if ($counter%5==0) {
						echo '<tr>';
					}
					echo td_combiner("$source_name: ".'<input type="checkbox" name="source_box[]" value='."$source_name".'>') ;
					if ($counter%5==4) {
						echo '</tr>';
					}
					$counter+=1;
				}	
			
				echo '</table></div>';
			}

		}
		
		if ($arr['seed_domain'] == 'life') {
			$all_labels = $this->db->ProMediaGroup->find(array('group_type' => 'life'));
			$counter = 0;
			echo '<div class="table-responsive"><table class="table table-striped">';
			echo '<thead><tr>';
			echo '<th>生活栏目（必填）</th><th></th><th></th><th></th><th></th></tr></thead>';
			foreach ($all_labels as $key => $source_cur) {
				
				$source_name=$source_cur['mediaGroup_title'];//['source_name'];
				if ($counter%5==0) {
					echo '<tr>';
				}
				echo td_combiner("$source_name: ".'<input type="checkbox" name="source_box[]" value='."$source_name".'>') ;
				if ($counter%5==4) {
					echo '</tr>';
				}
				$counter+=1;
			}
			echo '</table></div>';


			$modeList = $this->db->Prosystem->findOne(array('para_name' => 'life_modes'));

			
				$counter = 0;
				echo '<div class="table-responsive"><table class="table table-striped">';
				echo '<thead><tr>';
				echo '<th>生活内容模式</th><th></th><th></th><th></th><th></th></tr></thead>';
				foreach ($modeList['mode_list'] as $key => $source_cur) {
					
					$source_name=$source_cur;///['mediaGroup_title'];//['source_name'];
					if ($counter%5==0) {
						echo '<tr>';
					}
					echo td_combiner("$source_name: ".'<input type="checkbox" name="source_box[]" value='."$source_name".'>') ;
					if ($counter%5==4) {
						echo '</tr>';
					}
					$counter+=1;
				}	
			
			echo '</table></div>';

			$modeList = $this->db->Prosystem->findOne(array('para_name' => 'life_segments'));

			
				$counter = 0;
				echo '<div class="table-responsive"><table class="table table-striped">';
				echo '<thead><tr>';
				echo '<th>生活细分</th><th></th><th></th><th></th><th></th></tr></thead>';
				foreach ($modeList['mode_list'] as $key => $source_cur) {
					
					$source_name=$source_cur;///['mediaGroup_title'];//['source_name'];
					if ($counter%5==0) {
						echo '<tr>';
					}
					echo td_combiner("$source_name: ".'<input type="checkbox" name="source_box[]" value='."$source_name".'>') ;
					if ($counter%5==4) {
						echo '</tr>';
					}
					$counter+=1;
				}	
			
			echo '</table></div>';
		}

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