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
		th_combiner('媒体所在行业或所属标签');
		th_combiner('网址或RSS地址');
		th_combiner( 'sourceTag');
		th_combiner('openingTag');
		th_combiner('closingTag');


		for($i = $start; $i < min ( $start + $len, count ( $arr ) ); $i ++) {
			echo '<tr>';
			$uid = $arr [$i] ['_id']->{'$id'};
			td_combiner(is_notempty($arr[$i]['source_name']));
			td_combiner(is_notempty($arr[$i]['source_description']));
			td_combiner(is_notempty($arr[$i]['source_rssURL']));
			td_combiner(is_notempty($arr[$i]['soure_tag']));
			td_combiner(is_notempty($arr[$i]['text_openingTag']));
			td_combiner(is_notempty($arr[$i]['tag_closingTag']));
			echo '</tr>';








		}
	}
}