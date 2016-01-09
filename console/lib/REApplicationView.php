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
			echo '<td><a href="?action=显示学生明细&studentID='.$arr[$j]['applicant_id'].'">'.$arr[$j]['applicant_name'].'</a></td>';
			echo '<td>'.$arr[$j]['applicant_accountName'].'</td>';
			echo '<td>'.$arr[$j]['application_agreement'].'</td>';
			echo '<td>'.$arr[$j]['application_payment'].'</td>';
			echo '<tr>';	
		}



	}

	function showApplicantDetailsByID($detail){

		//学校类型
		echo '<h4>基本信息</h4>';
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		
		th_combiner('姓');
		th_combiner('名');
		th_combiner('性别');
		th_combiner('生日');
		th_combiner('身份证号码');
		th_combiner('护照号码');
		
			echo '<tr></thead>';
			echo '<td>'.$detail['student_lastName'].'</td>';
			echo '<td>'.$detail['student_givenName'].'</td>';
			echo '<td>'.$detail['student_gender'].'</td>';
			echo '<td>'.$detail['student_birthDate'].'</td>';
			echo '<td>'.$detail['student_nationalID'].'</td>';
			echo '<td>'.$detail['student_passportID'].'</td>';
			echo '<tr>';	
		
		echo '</table></div>';		

		//学校地点
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		
		
		th_combiner('学校');
		th_combiner('高中毕业年份');
		th_combiner('班级');
		th_combiner('学校职务');
		
			echo '<tr></thead>';
				echo '<td>'.$detail['student_accountName'].'</td>';	
				echo '<td>'.$detail['student_highSchoolGraduationYear'].'</td>';	
				echo '<td>'.$detail['student_accountClass'].'</td>';
				echo '<td>'.$detail['student_position'].'</td>';
	
			echo '<tr>';	
		
		echo '</table></div>';


		//联系方式
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		
		
		th_combiner('QQ');
		th_combiner('手机');
		th_combiner('邮箱');
		th_combiner('家庭住址');
		
			echo '<tr></thead>';
				echo '<td>'.$detail['student_qq'].'</td>';	
				echo '<td>'.$detail['student_mobile'].'</td>';	
				echo '<td>'.$detail['student_email'].'</td>';
				echo '<td>'.$detail['student_homeAddress'].'</td>';
			echo '<tr>';	
		
		echo '</table></div>';


		//父母联系方式
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		
		
		th_combiner('监护人');
		th_combiner('姓名');
		th_combiner('手机');
		th_combiner('邮箱');
		th_combiner('身份证号码');
		
			echo '<tr></thead>';
				echo '<td>'.'父亲'.'</td>';	
				echo '<td>'.$detail['student_fatherName'].'</td>';	
				echo '<td>'.$detail['student_fatherMobile'].'</td>';
				echo '<td>'.$detail['student_fatherEmail'].'</td>';
				echo '<td>'.$detail['student_fatherID'].'</td>';
			echo '<tr>';	
			echo '<tr></thead>';
				echo '<td>'.'母亲'.'</td>';	
				echo '<td>'.$detail['student_motherName'].'</td>';	
				echo '<td>'.$detail['student_motherMobile'].'</td>';
				echo '<td>'.$detail['student_motherEmail'].'</td>';
				echo '<td>'.$detail['student_motherID'].'</td>';
			echo '<tr>';	
		
		echo '</table></div>';








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