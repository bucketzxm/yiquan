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
				echo '<td><a href="?action=学校明细&mindex='.(string)$arr[$i]['_id'].'">'.$arr[$i]['account_name'].'</a></td>';
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
		echo '<h4>基本信息</h4>';
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

		//补充更说明

		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		
		
		th_combiner('补充说明');
		
		
			echo '<tr></thead>';
				echo '<td>'.$profileArr['account_supplement'].'</td>';	
		
			echo '<tr>';	
		
		echo '</table></div>';


		//学校联系人
		$contactArr = $results[1];
		echo '<h4>学校联系人</h4>';	
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		
		th_combiner('职位');
		th_combiner('姓');
		th_combiner('名');
		th_combiner('称谓');
		th_combiner('角色');
		th_combiner('手机');
		th_combiner('办公室直线');
		th_combiner('电子邮箱');
		th_combiner('QQ号码');
		th_combiner('详细');

		for ($i=0; $i < count($contactArr); $i++) { 
			echo '<tr></thead>';
			echo '<td>'.$contactArr[$i]['contact_position'].'</td>';
			echo '<td>'.$contactArr[$i]['contact_lastName'].'</td>';
			echo '<td>'.$contactArr[$i]['contact_givenName'].'</td>';
			echo '<td>'.$contactArr[$i]['contact_prefix'].'</td>';
			echo '<td>'.$contactArr[$i]['contact_role'].'</td>';
			echo '<td>'.$contactArr[$i]['contact_mobile'].'</td>';
			echo '<td>'.$contactArr[$i]['contact_telephone'].'</td>';
			echo '<td>'.$contactArr[$i]['contact_email'].'</td>';
			echo '<td>'.$contactArr[$i]['contact_qq'].'</td>';
			echo '<td><a href="?action=联系人明细&mindex='.(string)$contactArr[$i]['_id'].'">'.'查看'.'</a></td>';
			echo '<tr>';	

		}
		
		
		echo '</table><p>添加联系人</p></br></div>';	
	

		//联系记录
		$actionArr = $results[2];
		echo '<h4>学校交互记录</h4>';	
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		
		th_combiner('日期');
		th_combiner('交互类型');
		th_combiner('交互状态');
		th_combiner('联系人');
		th_combiner('发起人');
		th_combiner('交互目的');
		th_combiner('交互笔记');
		


		for ($j=0; $j < count($actionArr); $j++) { 
		
			$contactID = $actionArr[$j]['contact_id'];
			$contactCursor = $this->db->REContact->findOne(array('_id' => new MongoId ($contactID)));
			$combinedString = $contactCursor['contact_position'].$contactCursor['contact_lastName'].$contactCursor['contact_givenName'];
			echo '<tr></thead>';
			echo '<td>'.date("Y-m-d",$actionArr[$j]['action_time']).'</td>';
			echo '<td>'.$actionArr[$j]['action_type'].'</td>';
			echo '<td>'.$actionArr[$j]['action_status'].'</td>';
			echo '<td>'.$combinedString.'</td>';
			echo '<td>'.$actionArr[$j]['action_sender'].'</td>';
			echo '<td>'.$actionArr[$j]['action_purpose'].'</td>';
			echo '<td>'.$actionArr[$j]['action_note'].'</td>';
			echo '<tr>';	
		}

		
		echo '</table><p>添加交互记录</p></div>';	



	}

	function showDetailsByContact ($results){

		$profileArr = $results[0];

		//学校类型
		echo '<h4>基本信息</h4>';
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		
		th_combiner('职位');
		th_combiner('姓');
		th_combiner('名');
		th_combiner('称谓');
		th_combiner('角色');
		th_combiner('手机');
		th_combiner('办公室直线');
		th_combiner('电子邮箱');
		th_combiner('QQ号码');
		
			echo '<tr></thead>';
			echo '<td>'.$profileArr['contact_position'].'</td>';
			echo '<td>'.$profileArr['contact_lastName'].'</td>';
			echo '<td>'.$profileArr['contact_givenName'].'</td>';
			echo '<td>'.$profileArr['contact_prefix'].'</td>';
			echo '<td>'.$profileArr['contact_role'].'</td>';
			echo '<td>'.$profileArr['contact_mobile'].'</td>';
			echo '<td>'.$profileArr['contact_telephone'].'</td>';
			echo '<td>'.$profileArr['contact_email'].'</td>';
			echo '<td>'.$profileArr['contact_qq'].'</td>';
			echo '<tr>';	
		
		echo '</table></div>';		

		//学校地点
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		
		
		th_combiner('编制');
		th_combiner('学科');
		th_combiner('爱好');
		
			echo '<tr></thead>';
				echo '<td>'.$profileArr['contact_employer'].'</td>';	
				echo '<td>'.$profileArr['contact_discipline'].'</td>';	
				echo '<td>'.$profileArr['contact_interests'].'</td>';
	
			echo '<tr>';	
		
		echo '</table></div>';




		//联系记录
		$actionArr = $results[1];
		echo '<h4>联系人交互记录</h4>';	
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		
		th_combiner('日期');
		th_combiner('交互类型');
		th_combiner('交互状态');
		
		th_combiner('发起人');
		th_combiner('交互目的');
		th_combiner('交互笔记');
		


		for ($j=0; $j < count($actionArr); $j++) { 
		
			$contactID = $actionArr[$j]['contact_id'];
			$contactCursor = $this->db->REContact->findOne(array('_id' => new MongoId ($contactID)));
			
			echo '<tr></thead>';
			echo '<td>'.date("Y-m-d",$actionArr[$j]['action_time']).'</td>';
			echo '<td>'.$actionArr[$j]['action_type'].'</td>';
			echo '<td>'.$actionArr[$j]['action_status'].'</td>';
			
			echo '<td>'.$actionArr[$j]['action_sender'].'</td>';
			echo '<td>'.$actionArr[$j]['action_purpose'].'</td>';
			echo '<td>'.$actionArr[$j]['action_note'].'</td>';
			echo '<tr>';	
		}

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