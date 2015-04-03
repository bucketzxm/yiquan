<?php
require_once 'YqPlatform.php';

/* Report all errors except E_NOTICE */
// error_reporting ( E_ALL & ~ E_NOTICE );
class YqPlatformView extends YqPlatform {
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
	function getstatisticforActiveuser_showtable($arr) {
		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr><th>内容</th><th>值</th></tr></thead>';
		foreach ( $arr as $key => $v ) {
			echo '<tr><td>' . $key . '</td><td>' . $v . '</td></tr>';
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
		}
		else if($level==2)
		{
			echo '<table class="table table-striped">';
			echo '<thead><tr><th>方法名</th><th>次数</th></tr></thead>';
			foreach ( $arr as $key => $v ) {
				echo '<tr><td>' . $key . '</td><td>' . $v . '</td></tr>';
			}
			echo '</table>';
		}
		
	}
}

?>
