<?php
require_once 'YqBase.php';
class YqLabel extends YqBase {
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
			return $this->getLabels ();
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
	
	/*
	 * =================================================================
	 * =================================================================
	 * ht
	 * =================================================================
	 * */
	function getLabelByName($ltype, $label_name) {
		$row = $this->db->generalSettings->findOne ( array (
				'name' => 'labels' 
		) );
		
		if ($row ['value'] ['labels'] [$ltype] [$label_name] == null) {
			return 0;
		} else {
			$ans = [ ];
			$ans ['type'] = $ltype;
			$ans ['name'] = $label_name;
			$ans ['pic'] = $row ['value'] ['labels'] [$ltype] [$label_name];
			return $ans;
		}
	}
	function htupdateLabel($arr) {
		$row = $this->db->generalSettings->findOne ( array (
				'name' => 'labels' 
		) );
		// var_dump ( $arr );
		
		if ($row == null) {
			$row = [ ];
		}
		$row ['value'] ['labels'] [$arr ['type']] [$arr ['name']] = $arr ['pic'];
		
		$this->db->generalSettings->save ( $row );
		return 1;
	}
}

?>