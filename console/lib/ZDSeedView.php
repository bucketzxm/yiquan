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
	function listAllSeed_table($arr){

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


		for($i = 0; $i < count ( $arr ); $i ++) {
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

			if ($arr[$i]['seed_editorRating'] == -1) {
				echo '<td><a href="?action=passSeed&mindex=' . $arr [$i] ['_id']->{'$id'} . '" target="_blank">通过</a></td>';	
			}else {
				echo '<td><a href="?action=editSeed&mindex=' . $arr [$i] ['_id']->{'$id'} . '" target="_blank">编辑</a></td>';	
			}
			echo '<td><a href="?action=deleteSeed&mindex=' . $arr [$i] ['_id']->{'$id'} . '" target="_blank">枪毙</a></td>';

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
		$sources = $this->db->Prosource->find(array('source_domain'=>'business', 'source_status' => 'active'));
		foreach ($sources as $key => $value) {


			if (isset($value['source_industry'][1])) {
				$firstCategory = $value['source_industry'][0];
				$secondCategory = $value['source_industry'][1];	
				if (isset($mediaChannels[$firstCategory][$secondCategory])) {
					$mediaChannels[$firstCategory][$secondCategory] ++;
				}else{
					$mediaChannels[$firstCategory][$secondCategory] = 1;

				}	
			}else{
				if (isset($mediaChannels['blank']['blank'])) {
					$mediaChannels['blank']['blank'] ++;
				}else{
					$mediaChannels['blank']['blank'] = 1;

				}	
			}
			
		}



		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		echo '<tr></thead>';
		
						//th_combiner('类别');
		th_combiner('大类');
		//th_combiner( '近三天·文章数目');
		th_combiner( '小类');
		th_combiner( '数量');
		th_combiner( '操作');

		foreach ($mediaChannels as $keys => $values) {
			foreach ($values as $keyss => $valuess) {
				echo '<tr>';
				td_combiner($keys);
				td_combiner($keyss);
				td_combiner($valuess);
				if ($keyss == 'blank') {
					echo '<td><a href="?action=mediabychannel&channel=空白">'.'查看'.'</a></td>';
				}else{
					echo '<td><a href="?action=mediabychannel&channel='.$keyss.'">'.'查看'.'</a></td>';	
				}
				
				echo '</tr>';
			}

		}
		echo '</table></div>';
		


	}

	function listAllSeedStat_table($channel){

		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';

		$ans = [ ];
		
		$cus=$this->db->Prosource->find(array('source_industry' => $channel,'source_domain' => 'business','source_status' => 'active'));
		while ( $cus->hasNext () ) {
			$doc = $cus->getNext ();
			$ans [] = $doc;
		}
		
	
		//th_combiner('类别');
		th_combiner('名称');
		//th_combiner( '近三天·文章数目');
		th_combiner('主页');
		th_combiner( '更新时间');
		th_combiner( '更新状态');
		th_combiner( '更新数量');
		th_combiner( '操作');
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
		
		for($i = 0; $i < count($ans); $i ++) {
			echo '<tr>';
			//td_combiner( $ans[$i]['source_industry'][0]);
			
			echo '<td><a href="?action=seedbysource&source='.(string)$ans[$i]['_id'].'">'.$ans[$i]['source_name'].'</a></td>';
			echo '<td><a href="'.$ans[$i]['source_rssURL'][0].'" target="_blank">'.'前往'.'</a></td>';
			echo '<td>'.date('m-d H:i',$ans[$i]['check_time']).'</td>';
			echo '<td>'.$ans[$i]['loading_status'].'</td>';
			echo '<td>'.(isset($ans[$i]['lastLoadedCount'])? $ans[$i]['lastLoadedCount']:'').'</td>';
			echo '<td><a href="?action=loadSingleSource&source='.(string)$ans[$i]['_id'].'">'.'读取'.'</a></td>';
			//td_combiner( $ans[$i]['source_name']);
			//$count=$this->db->Proseed->count( array('seed_sourceID' => (string)$ans[$i]['_id'],'seed_dbWriteTime'=>array('$gt'=>time()-259200)));
			//td_combiner($count);

			$count_d0e=$this->db->Proseed->count( array('seed_sourceID' =>  (string)$ans[$i]['_id'],'seed_editorRating' => -1,'seed_time'=>array('$gt'=>time()-86400)));
			$count_d0=$this->db->Proseed->count( array('seed_sourceID' =>  (string)$ans[$i]['_id'],'seed_time'=>array('$gt'=>time()-86400)));
			$stat_d0 = $count_d0e.' / '. $count_d0; 
			td_combiner($stat_d0);
			$count_d1e=$this->db->Proseed->count( array('seed_sourceID' =>  (string)$ans[$i]['_id'],'seed_editorRating' => -1,'seed_time'=>array('$lt'=>time()-86400,'$gt'=>time()-172800)));
			$count_d1=$this->db->Proseed->count( array('seed_sourceID' =>  (string)$ans[$i]['_id'],'seed_time'=>array('$lt'=>time()-86400,'$gt'=>time()-172800)));
			$stat_d1 = $count_d1e.' / '. $count_d1; 
			td_combiner($stat_d1);
			$count_d2e=$this->db->Proseed->count( array('seed_sourceID' =>  (string)$ans[$i]['_id'],'seed_editorRating' => -1,'seed_time'=>array('$lt'=>time()-172800,'$gt'=>time()-259200)));
			$count_d2=$this->db->Proseed->count( array('seed_sourceID' =>  (string)$ans[$i]['_id'],'seed_time'=>array('$lt'=>time()-172800,'$gt'=>time()-259200)));
			$stat_d2 = $count_d2e.' / '. $count_d2; 
			td_combiner($stat_d2);
			$count_d3e=$this->db->Proseed->count( array('seed_sourceID' =>  (string)$ans[$i]['_id'],'seed_editorRating' => -1,'seed_time'=>array('$lt'=>time()-259200)));
			$count_d3=$this->db->Proseed->count( array('seed_sourceID' =>  (string)$ans[$i]['_id'],'seed_time'=>array('$lt'=>time()-259200)));
			$stat_d3 = $count_d3e.' / '. $count_d3; 
			td_combiner($stat_d3);
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
		//echo '<h3>文章题图</h3>';
				

		//echo '<div>'.$arr['seed_text'].'</div>';

		echo '<div><form method="post" action="?action=editSeed">';
		echo '<input type="hidden" class="form-control" name="id" value="' . $arr ['_id']->{'$id'} . '"/>';
		echo '<div class="form-group"><h2>文章名称</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="title" >' . $arr ['seed_title'] . '</textarea></div>';
		echo '<div class="form-group"><h2>文章题图</h2>';		
		echo '<img src="'.$arr['seed_imageLink'].'" width= 50% />';
		echo '<textarea class="form-control" rows="3" cols="80" name="imageLink">' . (isset($arr ['seed_imageLink']) ? $arr ['seed_imageLink']:'' ). '</textarea></div>';
		//echo '<div class="form-group"><h2>值得一读数量</h2>';
		//echo '<textarea class="form-control" rows="3" cols="80" name="agreeCount">' . (isset($arr ['seed_agreeCount']) ? $arr ['seed_agreeCount']:'' ). '</textarea></div>';
		
		echo '<div class="form-group"><h2>文章所属领域</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="seedDomain">' . (isset($arr ['seed_domain']) ? $arr ['seed_domain']:'' ). '</textarea></div>';

		//echo '<div class="form-group"><h2>热度</h2>';
		//echo '<textarea class="form-control" rows="3" cols="80" name="hotness">' . (isset($arr ['seed_hotness']) ? $arr ['seed_hotness']:'') . '</textarea></div>';
		echo '<div class="form-group"><h2>小编指数</h2>';
		if ($arr['seed_editorRating' == 0]) {
			echo '<h4>1：一般；2推荐：；3: 极力推荐</h4>';	
		}
		
		echo '<textarea class="form-control" rows="3" cols="80" name="rating">' . 0 . '</textarea></div>';//(isset($arr ['seed_editorRating']) ? $arr ['seed_editorRating']:'-1')

		echo '<div class="form-group"><h2>行业或标签,逗号隔开</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="industry">' . (isset($arr ['seed_industry']) ? implode(',', $arr ['seed_industry']) :''). '</textarea></div>';

		//Label System
		if ($arr['seed_domain'] == 'business') {
			if ($arr['seed_editorRating'] == 0 && !isset($arr['seed_industry'][0])) {
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

			if ($arr['seed_editorRating'] == 0 && isset($arr['seed_industry'][0])) {
				//推荐标签
				$modeList = $this->db->Prosystem->findOne(array('para_name' => 'business_recommendation'));

			
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


				//专业程度标签
				$modeList = $this->db->Prosystem->findOne(array('para_name' => 'business_seniority'));
			
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