<?php
require_once 'REApplication.php';

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


		


#################################################################################
	function listAllProjects($arr) {
		// var_dump($arr);
		foreach ( $arr as $key => $v ) {
			
			echo '<h4><a href="?action=显示项目报名表&projectID='.(string)$v['_id'].'">'.$v['project_name'].'</a></h4>';
		}
	}
	

	function listApplicationsByProject ($arr){


		echo '<h4>项目报名表</h4>';	
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		
		th_combiner('姓名');
		th_combiner('学校');
		th_combiner('协议状态');
		th_combiner('缴费状态');
		


		for ($j=0; $j < count($arr); $j++) { 
		
			
			echo '<tr></thead>';
			echo '<td>'.$arr[$j]['applicant_name'].'</td>';
			echo '<td>'.$arr[$j]['applicant_accountName'].'</td>';
			echo '<td>'.$arr[$j]['application_agreement'].'</td>';
			echo '<td>'.$arr[$j]['application_paymnet'].'</td>';
			echo '<tr>';	
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