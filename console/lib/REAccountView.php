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
		
		th_combiner('华东');
		echo '<tr></thead>';
			echo '<td><a href="?action=该省学校名单&mindex=上海">上海</a></td>';	
			echo '<td><a href="?action=该省学校名单&mindex=浙江">浙江</a></td>';	
			echo '<td><a href="?action=该省学校名单&mindex=江苏">江苏</a></td>';	
			echo '<td><a href="?action=该省学校名单&mindex=安徽">安徽</a></td>';	
		echo '<tr>';

		th_combiner('华北');
		echo '<tr></thead>';
			echo '<td><a href="?action=该省学校名单&mindex=北京">北京</a></td>';	
			echo '<td><a href="?action=该省学校名单&mindex=天津">天津</a></td>';	
			echo '<td><a href="?action=该省学校名单&mindex=山东">山东</a></td>';	
			echo '<td><a href="?action=该省学校名单&mindex=山东">山西</a></td>';	
			echo '<td><a href="?action=该省学校名单&mindex=河北">河北</a></td>';	
			echo '<td><a href="?action=该省学校名单&mindex=河南">河南</a></td>';	
		echo '<tr>';
		th_combiner('东北');
		echo '<tr></thead>';
			echo '<td><a href="?action=该省学校名单&mindex=辽宁">辽宁</a></td>';	
			echo '<td><a href="?action=该省学校名单&mindex=吉林">吉林</a></td>';	
			echo '<td><a href="?action=该省学校名单&mindex=黑龙江">黑龙江</a></td>';	
		echo '<tr>';
		th_combiner('华南');
		echo '<tr></thead>';
			echo '<td><a href="?action=该省学校名单&mindex=广东">广东</a></td>';	
			echo '<td><a href="?action=该省学校名单&mindex=广西">广西</a></td>';	
			echo '<td><a href="?action=该省学校名单&mindex=湖南">湖南</a></td>';	
			echo '<td><a href="?action=该省学校名单&mindex=湖北">湖北</a></td>';	
			echo '<td><a href="?action=该省学校名单&mindex=江西">江西</a></td>';	
			echo '<td><a href="?action=该省学校名单&mindex=福建">福建</a></td>';	
		echo '<tr>';
		th_combiner('西南');
		echo '<tr></thead>';
			echo '<td><a href="?action=该省学校名单&mindex=四川">四川</a></td>';	
			echo '<td><a href="?action=该省学校名单&mindex=重庆">重庆</a></td>';	
			echo '<td><a href="?action=该省学校名单&mindex=云南">云南</a></td>';	
			echo '<td><a href="?action=该省学校名单&mindex=贵州">贵州</a></td>';
			echo '<td><a href="?action=该省学校名单&mindex=西藏">西藏</a></td>';	
		echo '<tr>';
		th_combiner('西北');
		echo '<tr></thead>';
			echo '<td><a href="?action=该省学校名单&mindex=内蒙古">内蒙古</a></td>';	
			echo '<td><a href="?action=该省学校名单&mindex=陕西">陕西</a></td>';	
			echo '<td><a href="?action=该省学校名单&mindex=宁夏">宁夏</a></td>';	
			echo '<td><a href="?action=该省学校名单&mindex=甘肃">甘肃</a></td>';	
			echo '<td><a href="?action=该省学校名单&mindex=青海">青海</a></td>';	
			echo '<td><a href="?action=该省学校名单&mindex=新疆">新疆</a></td>';	
		echo '<tr>';
		echo '</table></div>';
	}

	function showAccountsByRegion($arr){

		echo '<p><a href="?action=添加学校">添加学校</a></p>';
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

	function addNewAccount(){

		echo '<h3>学校信息</h3>';
		echo '<div><form method="post" action="?action=提交学校信息">';
		//echo '<div class="form-group"><h4>基本信息</h4>';
		echo '<input type="text" class="form-control" rows="3" cols="80" name="name" placeholder="学校名称">';
		echo '<p>选择学校所在省份</p>';
		echo '<select name="province">';
			echo '<option value="安徽">安徽</option>';
			echo '<option value="北京">北京</option>';
			echo '<option value="重庆">重庆</option>';
			echo '<option value="福建">福建</option>';
			echo '<option value="甘肃">甘肃</option>';
			echo '<option value="广东">广东</option>';
			echo '<option value="广西">广西</option>';
			echo '<option value="贵州">贵州</option>';
			echo '<option value="海南">海南</option>';
			echo '<option value="河北">河北</option>';
			echo '<option value="河南">河南</option>';
			echo '<option value="黑龙江">黑龙江</option>';
			echo '<option value="湖北">湖北</option>';
			echo '<option value="湖南">湖南</option>';
			echo '<option value="吉林">吉林</option>';
			echo '<option value="江苏">江苏</option>';
			echo '<option value="江西">江西</option>';
			echo '<option value="辽宁">辽宁</option>';
			echo '<option value="内蒙古">内蒙古</option>';
			echo '<option value="宁夏">宁夏</option>';
			echo '<option value="青海">青海</option>';
			echo '<option value="山东">山东</option>';
			echo '<option value="山西">山西</option>';
			echo '<option value="陕西">陕西</option>';
			echo '<option value="上海">上海</option>';
			echo '<option value="四川">四川</option>';
			echo '<option value="天津">天津</option>';
			echo '<option value="西藏">西藏</option>';
			echo '<option value="新疆">新疆</option>';
			echo '<option value="云南">云南</option>';
			echo '<option value="浙江">浙江</option>';
		echo '</select>';
		echo '<p>选择学校类型</p>';
		echo '<select name="type">';
			echo '<option value="普通高中">普通高中</option>';
			echo '<option value="公立高中国际部">公立高中国际部</option>';
			echo '<option value="民办国际学校">民办国际学校</option>';
		echo '</select>';
		echo '<p>选择学校学段</p>';
		echo '<select name="category">';
			echo '<option value="大学">大学</option>';
			echo '<option value="高中">高中</option>';
			echo '<option value="初中">初中</option>';
			echo '<option value="小学">小学</option>';
		echo '</select>';
		echo '<p>选择学校课程体系</p>';
		echo '<select name="curriculum">';
			echo '<option value="国内">国内高中课程</option>';
			echo '<option value="IB">IB</option>';
			echo '<option value="A Level">A Level/剑桥</option>';
			echo '<option value="AP">AP/中美</option>';
			echo '<option value="中加">中加</option>';
		echo '</select>';
		echo '<input type="text" class="form-control" rows="3" cols="80" name="city" placeholder="所在城市">';
		echo '<p>选择学校开发状态</p>';
		echo '<select name="status">';
			echo '<option value="未激活">未激活</option>';
			echo '<option value="激活">激活</option>';
		echo '</select>';
		echo '<input type="text" class="form-control" rows="3" cols="80" name="size" placeholder="招生规模">';
		echo '<input type="text" class="form-control" rows="3" cols="80" name="address" placeholder="学校地址">';
		echo '<input type="text" class="form-control" rows="3" cols="80" name="website" placeholder="学校网址">';
		echo '<input type="text" class="form-control" rows="3" cols="80" name="supplement" placeholder="补充信息">';
		echo '</div>';


		echo '<div class="form-group"><input type="submit" value="提交" /></div>';
		echo '</form></div>';


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
		th_combiner('学校网址');
			echo '<tr></thead>';
				echo '<td>'.$profileArr['account_province'].'</td>';	
				echo '<td>'.$profileArr['account_city'].'</td>';	
				echo '<td>'.$profileArr['account_address'].'</td>';
				echo '<td>'.$profileArr['account_website'].'</td>';
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
		th_combiner('客情关系');
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
			echo '<td>'.$contactArr[$i]['contact_relationship'].'</td>';
			echo '<td><a href="?action=联系人明细&mindex='.(string)$contactArr[$i]['_id'].'">'.'查看'.'</a></td>';
			echo '<tr>';	

		}
		
		
		echo '</table><p><a href="?action=添加学校联系人&mindex='.(string)$profileArr['_id'].'">'.'添加该学校的联系人'.'</a></p></br></div>';	
	

		//联系记录
		$actionArr = $results[2];
		echo '<h4>学校交互记录</h4>';	
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		
		th_combiner('日期');
		th_combiner('类型');
		th_combiner('状态');
		th_combiner('联系人');
		th_combiner('发起人');
		th_combiner('项目');
		th_combiner('目的');
		th_combiner('笔记');
		


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
			echo '<td>'.$actionArr[$j]['action_project'].'</td>';
			echo '<td>'.$actionArr[$j]['action_purpose'].'</td>';
			echo '<td>'.$actionArr[$j]['action_note'].'</td>';
			echo '<tr>';	
		}

		
		echo '</table><p><a href="?action=添加学校交互记录&mindex='.(string)$profileArr['_id'].'">'.'添加该学校的交互记录</a></p></div>';	



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
		th_combiner('客情关系');
		
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
			echo '<td>'.$profileArr['contact_relationship'].'</td>';
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

	function addContactByAccount ($account){

		echo '<h3>联系人信息</h3>';
		echo '<div><form method="post" action="?action=提交学校联系人">';
		echo '<input type="hidden" class="form-control" name="account_id" value="'.$account. '"/>';
		echo '<h4>基本信息</h4>';
		echo '<input type="text" class="form-control" rows="3" cols="80" name="lastName" placeholder="姓">';
		echo '<input type="text" class="form-control" rows="3" cols="80" name="givenName" placeholder="名">';
		echo '<h4>职位</h4>';
		echo '<select name="position">';
			echo '<option value="国际部主任">国际部主任</option>';
			echo '<option value="国际部副主任">国际部副主任</option>';
			echo '<option value="校长">校长</option>';
			echo '<option value="副校长">副校长</option>';
			echo '<option value="学术校长">学术校长</option>';
			echo '<option value="校长助理">校长助理</option>';
			echo '<option value="教务主任">教务主任</option>';
			echo '<option value="团委书记">团委书记</option>';
			echo '<option value="升学指导">升学指导</option>';
			echo '<option value="活动指导">活动指导</option>';
			echo '<option value="任课老师">任课老师</option>';
			echo '<option value="不详">不详</option>';
		echo '</select>';
		echo '<h4>c称谓</h4>';
		echo '<select name="prefix">';
			echo '<option value="女士">女士</option>';
			echo '<option value="先生">先生</option>';
		echo '</select>';
		echo '<h4>角色</h4>';
		echo '<select name="role">';
			echo '<option value="决策者">决策者</option>';
			echo '<option value="传达者">传达者</option>';
			echo '<option value="执行者">执行者</option>';
			echo '<option value="批准者">批准者</option>';
			echo '<option value="推动者">推动者</option>';
			echo '<option value="不详">不详</option>';
		echo '</select>';
		echo '<h4>客情关系</h4>';
		echo '<select name="relationship">';
			echo '<option value="良好">良好</option>';
			echo '<option value="中立">中立</option>';
			echo '<option value="反对">反对</option>';
			echo '<option value="封闭">封闭</option>';
			echo '<option value="不详">不详</option>';
		echo '</select>';
		echo '<div class="form-group"><h4>联系方式</h4>';
		echo '<input type="text" class="form-control" rows="3" cols="80" name="mobile" placeholder="手机">';
		echo '<input type="text" class="form-control" rows="3" cols="80" name="telephone" placeholder="直线座机">';
		echo '<input type="text" class="form-control" rows="3" cols="80" name="email" placeholder="电子邮箱">';
		echo '<input type="text" class="form-control" rows="3" cols="80" name="qqnumber" placeholder="QQ号码">';
		echo '<h4>编制</h4>';
		echo '<select name="employer">';
			echo '<option value="公立学校">公立学校</option>';
			echo '<option value="第三方机构">第三方机构</option>';
		echo '</select>';
		echo '<h4>学科</h4>';
		echo '<select name="discipline">';
			echo '<option value="英语">英语</option>';
			echo '<option value="政治">政治</option>';
			echo '<option value="地理">地理</option>';
			echo '<option value="历史">历史</option>';
			echo '<option value="数理化">数理化</option>';
			echo '<option value="行政">行政</option>';
			echo '<option value="不详>不详</option>';
		echo '</select>';
		echo '<input type="text" class="form-control" rows="3" cols="80" name="interests" placeholder="爱好"></div>';
		echo '<div class="form-group"><input type="submit" value="提交" />';
		echo '</form></div>';

	}


	function addActionByAccount ($account,$contacts,$projects){

		echo '<h3>交互记录信息</h3>';
		echo '<div><form method="post" action="?action=提交学校交互记录">';
		echo '<input type="hidden" class="form-control" name="account_id" value="'.$account. '"/>';
		echo '<div class="form-group">';
		echo '<h4>交互目的</h4>';
		echo '<select name="purpose">';
			echo '<option value="项目宣传">项目宣传</option>';
		echo '</select>';
		echo '<h4>交互形式</h4>';
		echo '<select name="type">';
			echo '<option value="电话">电话</option>';
			echo '<option value="邮件">邮件</option>';
			echo '<option value="面谈">面谈</option>';
			echo '<option value="快递">快递</option>';
		echo '</select>';
		echo '<h4>交互状态</h4>';
		echo '<select name="status">';
			echo '<option value="待完成">未完成</option>';
			echo '<option value="已完成">已完成</option>';
		echo '</select>';
		echo '<h4>交互发起人</h4>';
		echo '<select name="sender">';
			echo '<option value="程艳">程艳</option>';
			echo '<option value="朱伦">朱伦</option>';
		echo '</select></div>';
		echo '<div class="form-group"><h4>交互对象</h4>';
		echo '<select name="contact_id">';

		for ($i=0; $i < count($contacts); $i++) { 
			echo '<option value="'.(string)$contacts[$i]['_id'].'">'.$contacts[$i]['contact_position'].$contacts[$i]['contact_lastName'].$contacts[$i]['contact_givenName'].'</option>';			
		}
		echo '</select></div>';
		echo '<div class="form-group"><h4>涉及项目</h4></div>';
		echo '<select name="project_id">';

		for ($j=0; $j < count($projects); $j++) { 
			echo '<option value="'.(string)$projects[$j]['_id'].'">'.$projects[$j]['project_name'].'</option>';			
		}
		echo '</select></div>';
		echo '<div class="form-group"><h4>交互笔记</h4>';
		echo '<input type="textarea" class="form-control" rows="3" cols="80" name="note" placeholder="交互笔记"></div>';
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