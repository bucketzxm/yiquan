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

class MediaView extends Media{
	function listAllMedia_table($arr, $start, $len){

		echo '<div class="table-responsive"><table class="table table-striped">';
		echo '<thead><tr>';

		th_combiner('媒体名称');
		th_combiner('媒体描述');
		th_combiner('行业或标签');
		th_combiner('网址/RSS');
		th_combiner( 'sourceTag');
		th_combiner('openingTag');
		th_combiner('closingTag');
		echo '<tr></thead>';


		for($i = $start; $i < min ( $start + $len, count ( $arr ) ); $i ++) {
			echo '<tr>';
			$uid = $arr [$i] ['_id']->{'$id'};
			td_combiner((isset($arr[$i]['source_name'])? $arr[$i]['source_name']:''));
			td_combiner((isset($arr[$i]['source_description']) ? $arr[$i]['source_description']: ''));
			td_combiner((isset($arr[$i]['source_industry']) ? implode(',',$arr[$i]['source_industry']): ''));
			td_combiner((isset($arr[$i]['source_rssURL'])? implode(',',$arr[$i]['source_rssURL']);
			td_combiner((isset($arr[$i]['source_tag'] )? implode(',',$arr[$i]['source_tag']): '');
			td_combiner((isset($arr[$i]['text_openingTag']) ? $arr[$i]['text_openingTag']: ''));
			td_combiner((isset($arr[$i]['text_closingTag'] )? $arr[$i]['text_closingTag']: ''));
			echo '</tr>';





		}
		echo '</table></div>';
	}
}