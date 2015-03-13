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
		//var_dump ( $arr );
		
		if ($row == null) {
			$row = [ ];
		}
		$row ['value'] ['labels'] [$arr ['type']] [$arr ['name']] = $arr ['pic'];
		
		$this->db->generalSettings->save ( $row );
		return 1;
	}
	function addoreditLabel_showform($arr = []) {
		if (empty ( $arr )) {
			echo '<div><form method="post" action="?action=update">';
			echo '<div class="form-group"><label for="ltype">标签类型</label>';
			echo '<input type="text" class="form-control" name="ltype" value=""/></div>';
			echo '<div class="form-group"><label for="lname">标签名称</label>';
			echo '<input type="text" class="form-control" name="lname" value=""/></div>';
			echo '<div class="form-group"><label for="lpic">标签照片</label>';
			echo '<input type="file" name="lpic" /></div>';
			echo '<input type="submit" value="添加或修改" />';
			echo '</form></div>';
		} else {
			echo '<div><form method="post" action="?action=update">';
			echo '<div class="form-group"><label for="ltype">标签类型</label>';
			echo '<input type="text" class="form-control" name="ltype" value="' . $arr ['type'] . '"/></div>';
			echo '<div class="form-group"><label for="lname">标签名称</label>';
			echo '<input type="text" class="form-control" name="lname" value="' . $arr ['name'] . '"/></div>';
			echo '<div class="form-group"><label for="lpic">标签照片</label>';
			echo '<input type="file"  name="lpic"/ ></div>';
			echo '<input type="submit" value="修改" />';
			echo '</form></div>';
		}
	}
	function getLabels_showtable() {
		$row = $this->db->generalSettings->findOne ( array (
				'name' => 'labels' 
		) );
		if (isset ( $row ['value'] ['labels'] )) {
			foreach ( $row ['value'] ['labels'] as $key => $v ) {
				echo '<div class="table-responsive">';
				echo '<div><h1>' . $key . '</h1></div>';
				echo '<table class="table table-striped">';
				foreach ( $v as $k => $q ) {
					echo '<tr><td>' . $k . '</td><td>' . '<img class="img-responsive" src="data:image/jpeg;base64,' . $q . '"data-holder-rendered="true"/></td></tr>';
				}
				echo '</table>';
			}
		}
	}
}

?>