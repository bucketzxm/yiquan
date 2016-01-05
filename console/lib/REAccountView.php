<?php
require_once 'REAccount.php';

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
	function listChinaProvinces(){

		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		
		th_combiner('省份');
		echo '<tr></thead>';
			echo '<td><a href="?action=该省学校名单&mindex=浙江">浙江</a></td>';	
		echo '<tr>';
		echo '</table></div>';
	}

	function showAccountsByRegion($arr){

		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		
		th_combiner('省份');
		th_combiner('城市');
		th_combiner('学校名称');
		th_combiner('学校类型');
		th_combiner('每届人数');
		th_combiner('开发状态');
		for ($i=0; $i < count($arr); $i++) { 
			echo '<tr></thead>';
				echo '<td>'.$arr[$i]['account_province'].'</td>';	
				echo '<td>'.$arr[$i]['account_city'].'</td>';	
				echo '<td><a href="?action=学校明细&mindex='.$arr[$i]['account_name'].'">'.$arr[$i]['account_name'].'</a></td>';
				echo '<td>'.$arr[$i]['account_type'].'</td>';
				echo '<td>'.$arr[$i]['account_size'].'</td>';
				echo '<td>'.$arr[$i]['account_status'].'</td>';
			echo '<tr>';	
		}
		echo '</table></div>';
	}


	function showDetailsByAccount ($results){

		$profileArr = $results[0];

		//学校类型
		echo '<p>学校类型</p>';
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		
		th_combiner('学校名称');
		th_combiner('学校类别');
		th_combiner('学校类型');
		th_combiner('课程类型');
		th_combiner('每届人数');
		th_combiner('开发状态');
		
			echo '<tr></thead>';
				echo '<td>'.$profileArr['account_name'].'</td>';
				echo '<td>'.$profileArr['account_category'].'</td>';
				echo '<td>'.$profileArr['account_type'].'</td>';
				echo '<td>'.$profileArr['account_curriculum'].'</td>';
				echo '<td>'.$profileArr['account_size'].'</td>';
				echo '<td>'.$profileArr['account_status'].'</td>';
			echo '<tr>';	
		
		echo '</table></div>';		

		//学校地点
		echo '<p>学校地点</p>';
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		
		
		th_combiner('省份');
		th_combiner('城市');
		th_combiner('学校地址');
		
			echo '<tr></thead>';
				echo '<td>'.$profileArr['account_province'].'</td>';	
				echo '<td>'.$profileArr['account_city'].'</td>';	
				echo '<td>'.$profileArr['account_address'].'</td>';
	
			echo '<tr>';	
		
		echo '</table></div>';	


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