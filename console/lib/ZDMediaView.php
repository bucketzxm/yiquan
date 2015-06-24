<?php
require_once 'ZDMedia.php';

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

class MediaView extends Media{
	function listAllMediaTag_table($arr, $start, $len){

		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';
		th_combiner('编辑操作');

		th_combiner('媒体名称');
		th_combiner( 'sourceTag');
		th_combiner('startingTag');
		th_combiner('closingTag');

		echo '<tr></thead>';


		for($i = $start; $i < min ( $start + $len, count ( $arr ) ); $i ++) {
			echo '<tr>';
			$uid = $arr [$i] ['_id']->{'$id'};
			echo '<td><a href="?action=editTag&mindex=' . $arr [$i] ['_id']->{'$id'} . '">编辑</a></td>';
			$tag_content=(isset($arr[$i]['source_tag'])? implode(',',$arr[$i]['source_tag']): '');
			td_combiner((isset($arr[$i]['source_name'])? $arr[$i]['source_name']:''));
			td_combiner(htmlentities($tag_content));
			td_combiner((isset($arr[$i]['text_startingTag']) ? $arr[$i]['text_startingTag']: ''));
			td_combiner(htmlentities((isset($arr[$i]['text_closingTag']) ? $arr[$i]['text_closingTag']: '')));

			echo '</tr>';

		}
		echo '</table></div>';
	}

	function listAllMediaBasic_table($arr, $start, $len){

		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';

		th_combiner('媒体名称');
		th_combiner('媒体描述');
		th_combiner('行业或标签');
		th_combiner('网址/RSS');
		th_combiner('状态');
		th_combiner('编辑操作');

		echo '<tr></thead>';


		for($i = $start; $i < min ( $start + $len, count ( $arr ) ); $i ++) {
			echo '<tr>';
			$uid = $arr [$i] ['_id']->{'$id'};
			td_combiner((isset($arr[$i]['source_name'])? $arr[$i]['source_name']:''));
			td_combiner((isset($arr[$i]['source_description']) ? $arr[$i]['source_description']: ''));
			td_combiner((isset($arr[$i]['source_industry']) ? implode(',',$arr[$i]['source_industry']): ''));
			td_combiner((isset($arr[$i]['source_rssURL'])? implode(',',$arr[$i]['source_rssURL']):''));
			td_combiner((isset($arr[$i]['source_status']) ? $arr[$i]['source_status']: ''));
			echo '<td><a href="?action=editBasic&mindex=' . $arr [$i] ['_id']->{'$id'} . '">编辑</a></td>';

			echo '</tr>';


		}
		echo '</table></div>';
	}







	function listAllMediaStat_table($arr, $start, $len){

		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';

		th_combiner('媒体名称');
		th_combiner('行业或标签');
		th_combiner('值得一读数量');
		th_combiner( '阅读数量');
		th_combiner( '值得一读率');

		

		echo '<tr></thead>';


		for($i = $start; $i < min ( $start + $len, count ( $arr ) ); $i ++) {
			echo '<tr>';
			$uid = $arr [$i] ['_id']->{'$id'};
			td_combiner((isset($arr[$i]['source_name'])? $arr[$i]['source_name']:''));
			td_combiner((isset($arr[$i]['source_industry']) ? implode(',',$arr[$i]['source_industry']): ''));
			td_combiner((isset($arr[$i]['agree_count']) ? $arr[$i]['agree_count']: ''));
			td_combiner((isset($arr[$i]['read_count']) ? $arr[$i]['read_count']: ''));
			if (isset($arr[$i]['agree_count']) && isset($arr[$i]['read_count'] )) {
				$agree=$arr[$i]['agree_count'];
				$read=$arr[$i]['read_count'];
				if ($read==0) {
					td_combiner('还没有人读过');
					
				}else {
					$ratio_persent=$agree/$read*100;
					td_combiner("$ratio_persent".'%');

				}
			}


			echo '</tr>';





		}
		echo '</table></div>';
	}
	function showOneMediaBasic_form($arr) {
		echo '<div><form method="post" action="?action=editBasic">';
		echo '<input type="hidden" class="form-control" name="id" value="' . $arr ['_id']->{'$id'} . '"/>';
		echo '<div class="form-group"><h2>媒体名称</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="name">' . $arr ['source_name'] . '</textarea></div>';
		echo '<div class="form-group"><h2>媒体描述</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="description">' . (isset($arr ['source_description']) ? $arr ['source_description']:'' ). '</textarea></div>';
		echo '<div class="form-group"><h2>行业或标签,逗号隔开</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="industry">' . (isset($arr ['source_industry']) ? implode(',', $arr ['source_industry']) :''). '</textarea></div>';

		echo '<div class="form-group"><h2>状态</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="status">' . (isset($arr ['source_status']) ? $arr ['source_status']:'') . '</textarea></div>';
		echo '<div class="form-group"><h2>网址或RSS</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="rssURL">' . implode(',',$arr ['source_rssURL']) . '</textarea></div>';


		echo '<div class="form-group"><input type="submit" value="提交" /></div>';
		echo '</form></div>';
	}
	function showOneMediaTag_form($arr) {
		echo '<div><form method="post" action="?action=editTag">';
		echo '<input type="hidden" class="form-control" name="id" value="' . $arr ['_id']->{'$id'} . '"/>';
		echo '<div class="form-group"><h2>媒体名称</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="name">' . $arr ['source_name'] . '</textarea></div>';
		echo '<div class="form-group"><h2>sourceTag，逗号隔开</h2>';

		echo '<textarea class="form-control" rows="3" cols="80" name="tag">' . (isset($arr ['source_tag']) ? implode(',',$arr ['source_tag']) :'') . '</textarea></div>';
		echo '<div class="form-group"><h2>startingTag</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="startingTag">' . (isset($arr ['text_startingTag']) ? $arr ['text_startingTag']:'' ). '</textarea></div>';
		echo '<div class="form-group"><h2>closingTag</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="closingTag">' . (isset($arr ['text_closingTag'])?$arr ['text_closingTag']:'') . '</textarea></div>';
		echo '<div class="form-group"><h2>正则</h2>';
		echo '<textarea class="form-control" rows="3" cols="80" name="rexTemplate">' . (isset($arr ['source_rexTemplate'])?$arr ['source_rexTemplate']:'') . '</textarea></div>';


		echo '<div class="form-group"><input type="submit" value="提交" /></div>';
		echo '</form></div>';
	}

	function showDeleteView($id) {
		echo '<form method="post" action="?action=delete">';
		echo '确定删除该吗？';
		echo $id;
		echo '<input type="hidden" name="qid" value="' . $id . '" />';
		echo '<div class="form-group"><input type="submit" value="确定" /></div>';
		echo '</form>';
	}
}