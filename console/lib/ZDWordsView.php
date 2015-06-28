<?php
require_once 'ZDWords.php';
require_once 'ZDMedia.php';
require_once 'ZDMediaView.php';



use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

####################
#    combiners     #
####################




#####################################
class WordsView extends Words {
	// private $dbname = 'test';
	private $table = 'Group';


	function listAllWords_table($arr, $start, $len){

		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		th_combiner('编辑操作');

		th_combiner('行业名称');

		echo '<tr></thead>';


		for($i = $start; $i < min ( $start + $len, count ( $arr ) ); $i ++) {
			echo '<tr>';
			$uid = $arr [$i] ['_id']->{'$id'};
			echo '<td><a href="?action=editWords&mindex=' . $arr [$i] ['_id']->{'$id'} . '">编辑</a></td>';
			
			td_combiner((isset($arr[$i]['industry_name'])? $arr[$i]['industry_name']:''));
		

			echo '</tr>';

		}
		echo '</table></div>';
	}








	function showOneWords_form($arr) {
		echo '<div><form method="post" action="?action=editWords">';
		echo '<input type="hidden" class="form-control" name="id" value="' . $arr ['_id']->{'$id'} . '"/>';
		echo '<div class="form-group"><h2>行业名称</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="name">' . (isset($arr ['industry_name'])?$arr ['industry_name']:'') . '</textarea></div>';

		echo '<div class="form-group"><h2>中文关键词编辑<br>用英文逗号隔开，最末不要有逗号</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="words">' . (isset($arr ['industry_words'])?implode(',',$arr ['industry_words']):'') . '</textarea></div>';
		echo '<div class="form-group"><h2>英文关键词编辑<br>用英文逗号隔开，最末不要有逗号</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="ENDict">' . (isset($arr ['industry_ENDict'])?implode(',',$arr ['industry_ENDict']):'') . '</textarea></div>';



		


		echo '<div class="form-group"><input type="submit" value="提交" /></div>';
		echo '</form></div>';
	}




















	function showNewWords_form($arr=[]) {
		echo '<div><form method="post" action="?action=添加新信息组">';

		echo '<div class="form-group"><h2>行业名称</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="name">' . '' . '</textarea></div>';
	echo '<div class="form-group"><h2>中文关键词编辑，逗号隔开</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="words">' . ''. '</textarea></div>';
		echo '<div class="form-group"><h2>英文关键词编辑，逗号隔开</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="ENDict">' . ''. '</textarea></div>';

				
	
	


		echo '<div class="form-group"><input type="submit" value="提交" /></div>';
		echo '</form></div>';
	}


	
}






?>
