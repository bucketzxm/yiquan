<?php
require_once 'YqBase.php';
class Label extends YqBase {
	function getLabels() {
		$row = $this->db->generalSettings->findOne ( array (
				'name' => 'labels' 
		) );
		
		return json_encode ( $row ['value'] );
	}
	function checkLabelUpdate($localtime) {
		$row = $this->db->generalSettings->findOne ( array (
				'name' => 'labels' 
		) );
		$yourDate = $row ['value'] ['updatetime'];
		if ($yourDate->sec != (( integer ) $localtime)) {
			return 1;
		} else {
			return 0;
		}
	}
	function checkLabelUpdateAndDown($localtime) {
		$row = $this->db->generalSettings->findOne ( array (
				'name' => 'labels'
		) );
		$yourDate = $row ['value'] ['updatetime'];
		if ($yourDate->sec != (( integer ) $localtime)) {
			return $this->getLabels();
		} else {
			return 0;
		}
	}
	function updateLabel($label_type, $label_name, $label_pic) {
		$row = $this->db->generalSettings->findOne ( array (
				'name' => 'labels' 
		) );
		$row ['value'] ['labels'] [$label_type] [$label_name] = $label_pic;
		$row ['value'] ['updatetime'] = new MongoDate ();
		$this->db->generalSettings->save ( $row );
		return 1;
	}
}

?>