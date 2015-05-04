<?php
require_once 'MyPlatform.php';

/* Report all errors except E_NOTICE */
// error_reporting ( E_ALL & ~ E_NOTICE );
class MyPlatformView extends MyPlatform {
	private $dit = array (
			'activeusercount' => '活跃用户数量',
			'Quoteaddcount' => '每言添加数量',
			'Quotecount' => '每言总数',
			'regcount' => '用户注册总数',
			'regcounttoday' => '当日用户注册数',
			'contactscount' => '通讯录导入数量',
			'activeandregratio'=>'活跃/注册量比值'
	);
	function tochinese($word) {
		//var_dump($this->dit);
		return $this->dit ["$word"];
	}
	function getmyDailyReport($arr) {
		// var_dump($arr);
		foreach ( $arr as $key => $v ) {
			// var_dump($key);
			echo '<div class="table-responsive"><h2>' . date ( 'Y-m-d', $key ) . '</h2><hr/>';
			echo '<h3>活跃度</h3><hr/>';
			echo '<table class="table table-striped">';
			echo '<thead><tr><th>内容</th><th>值</th></tr></thead>';
			foreach ( $v ['active'] as $key2 => $v2 ) {
				echo '<tr><td>' . $this->tochinese ( $key2 ) . '</td><td>' . $v2 . '</td></tr>';
			}
			echo '</table><hr/>';
			
			echo '<h3>用户行为</h3><hr/>';
			echo '<table class="table table-striped">';
			echo '<thead><tr><th>内容</th><th>值</th></tr></thead>';
			foreach ( $v ['behaviour'] as $key2 => $v2 ) {
				echo '<tr><td>' . $this->tochinese ( $key2 ) . '</td><td>' . $v2 . '</td></tr>';
			}
			echo '</table><hr/>';
			
			echo '<h3>用户数量</h3><hr/>';
			echo '<table class="table table-striped">';
			echo '<thead><tr><th>内容</th><th>值</th></tr></thead>';
			foreach ( $v ['user'] as $key2 => $v2 ) {
				echo '<tr><td>' . $this->tochinese ( $key2 ) . '</td><td>' . $v2 . '</td></tr>';
			}
			echo '</table><hr/>';
			
			echo '</div>';
		}
	}
	
	// ==================================================================
	function getWeihuButton() {
		echo '<div><form method="post" action="?action=weihu">';
		echo '<input type="submit" value="点击维护" />';
		echo '</form></div>';
	}
	function getLastestVersion_showform($arr) {
		echo '<div><form method="post" action="?action=version">';
		echo '<div class="form-group"><h2>最新' . $arr ['platform'] . '版本号</h2></div>';
		echo '<input type="hidden" name="plat" value="' . $arr ['platform'] . '" />';
		echo '<input type="text" class="form-control" name="lastestVersion" value="' . $arr ['value'] . '"/>';
		
		echo '<input type="submit" value="修改最新版本号" />';
		echo '</form></div>';
	}
	function getPlatformStatistic_showtable($arr) {
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr><th>内容</th><th>值</th></tr></thead>';
		foreach ( $arr as $key => $v ) {
			echo '<tr><td>' . $key . '</td><td>' . $v . '</td></tr>';
		}
		echo '</table></div>';
	}
	function getUserStatistic_showtable($arr) {
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr><th>内容</th><th>值</th></tr></thead>';
		foreach ( $arr [1] as $key => $v ) {
			echo '<tr><td>' . $key . '</td><td>' . $v . '</td></tr>';
		}
		echo '</table></div>';
	}
	function getActiveUserStatSearchform() {
		echo '<div><form method="post" action="?action=statisticforActiveuser">';
		echo '<div class="form-group"><h2>选择查询方式</h2></div>';
		echo '<input name="searchtype" type="radio" value="days" checked="checked" />天<input type="radio" name="searchtype" value="weeks" />周';
		echo '<div class="form-group"><h2>选择量</h2></div>';
		echo '<input name="value" id="sttime" type="text" />';
		echo '<input type="submit" value="查询" />';
		echo '</form></div>';
		// echo date ( "Y-m-d", strtotime ( "-1 week Monday" ) ), ""; // 离现在最近的周一
	}
	function getUserRegStatSearchform() {
		echo '<div><form method="post" action="?action=statisticforuser">';
		echo '<div class="form-group"><h2>选择查询方式</h2></div>';
		echo '<input name="searchtype" type="radio" value="days" checked="checked" />天<input type="radio" name="searchtype" value="weeks" />周';
		echo '<div class="form-group"><h2>选择量</h2></div>';
		echo '<input name="value" id="sttime" type="text" />';
		echo '<input type="submit" value="查询" />';
		echo '</form></div>';
		// echo date ( "Y-m-d", strtotime ( "-1 week Monday" ) ), ""; // 离现在最近的周一
	}
	function getstatisticforActiveuser_showtable($arr) {
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr><th>内容</th><th>活跃数</th><th>用户总数</th><th>活跃比</th></tr></thead>';
		foreach ( $arr as $key => $v ) {
			$pp = '??%';
			if ($v ['user_count'] > 0) {
				$pp = round ( $v ['activecount'] / $v ['user_count'] * 100, 2 ) . "%";
			}
			echo '<tr><td>' . $key . '</td><td>' . $v ['activecount'] . '</td><td>' . $v ['user_count'] . '</td><td>' . $pp . '</td></tr>';
		}
		echo '</table></div>';
	}
	function getUserStatistic_showsearchform() {
		echo '<div class="table-responsive"><table class="table table-striped">';
		
		echo '</table></div>';
	}
	function searchingRegex_showlist() {
		echo '<div class="table-responsive"><ul>';
		echo '<li><a href="?action=report&type=day&value=7">7天内</a></li>';
		echo '<li><a href="?action=report&type=day&value=14">14天内</a></li>';
		echo '<li><a href="?action=report&type=month&value=1">本月内</a></li>';
		echo '<li><a href="?action=report&type=month&value=2">前二月内</a></li>';
		
		echo '</ul></div>';
	}
	function getdailyreport_showtable($arr) {
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr><th>内容</th><th>值</th></tr></thead>';
		foreach ( $arr as $key => $v ) {
			echo '<tr><td>' . $key . '</td><td>' . $v . '</td></tr>';
		}
		echo '</table></div>';
	}
	function getMethodsCallStatSearchform() {
		echo '<div><form method="post" action="?action=reportOfInterfaceCount">';
		echo '<script language=javascript src="images/DatePicker.js"></script>';
		echo '<div class="form-group"><h2>选择开始时间</h2></div>';
		echo '<input name="starttime" id="sttime" type="text" onfocus="setday(this)" readonly="readonly" />';
		echo '<input type="submit" value="查询" />';
		echo '</form></div>';
	}
	function getDailyReportSearchform() {
		echo '<div><form method="post" action="?action=report">';
		echo '<script language=javascript src="images/DatePicker.js"></script>';
		echo '<div class="form-group"><h2>选择开始时间</h2></div>';
		echo '<input name="starttime" id="sttime" type="text" onfocus="setday(this)" readonly="readonly" />';
		echo '<input type="submit" value="查询" />';
		echo '</form></div>';
	}
	function showMethodsCallStatTable($arr, $level) {
		if ($level == 1) {
			echo '<div class="table-responsive"><table class="table table-striped">';
			echo '<thead><tr><th>类</th><th>具体</th></tr></thead>';
			foreach ( $arr as $key => $v ) {
				echo '<tr><td>' . $key . '</td><td>';
				$this->showMethodsCallStatTable ( $v, $level + 1 );
				echo '</td></tr>';
			}
			echo '</table></div>';
		} else if ($level == 2) {
			echo '<table class="table table-striped">';
			echo '<thead><tr><th>方法名</th><th>次数</th></tr></thead>';
			foreach ( $arr as $key => $v ) {
				echo '<tr><td>' . $key . '</td><td>' . $v . '</td></tr>';
			}
			echo '</table>';
		}
	}
	function showDailyReportsByntimes($time) {
		$start = strtotime ( date ( "Y-m-d ", $time ) );
		$end = time ();
		while ( $start <= $end ) {
			echo '<h2>' . date ( "Y-m-d ", $end ) . '</h2>';
			echo '<h4>' . '行为的统计' . '</h4>';
			$this->getdailyreport_showtable ( $this->getDailyBehavierReport ( $end ) );
			echo '<h4>' . '数量的统计' . '</h4>';
			$this->getdailyreport_showtable ( $this->getDailyCountReport ( $end ) );
			echo '<hr/>';
			$end -= 86400;
			$end = strtotime ( date ( "Y-m-d ", $end ) );
		}
	}
}

?>
