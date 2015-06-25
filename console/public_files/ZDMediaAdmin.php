<?php
include_once '../lib/common.php';
include_once '../lib/functions.php';
include_once '../lib/YqUser.php';
include_once '../lib/User.php';
include_once '../lib/YqPlatformView.php';
include_once '../lib/ZDMediaView.php';
// 401 file referenced since user should be logged in to view this page
include_once '401.php';

###############################33
#generate media information form

#
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-3 col-md-2 sidebar">
			<ul class="nav nav-sidebar">
				<li><a href="?action=查看编辑基本信息">查看编辑基本信息 <span class="sr-only">(current)</span></a></li>
				<li><a href="?action=查看编辑标签正则">查看编辑标签正则 <span class="sr-only">(current)</span></a></li>
				<li><a href="?action=查看媒体数据">查看媒体数据 <span class="sr-only">(current)</span></a></li>
				<li><a href="?action=添加新媒体">添加新媒体 <span class="sr-only">(current)</span></a></li>

			</ul>
		</div>
		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <?php
										if (isset ( $_GET ['action'] )) {
											echo '<h1 class="page-header">' . $_GET ['action'] . '</h1>';
										} else {
											echo '<h1 class="page-header">请选择操作</h1>';
										}
										?>
          

          <div class="row">
				<?php
				if (isset ( $_GET ['action'] )) {
					$a = new MediaView();
					switch ($_GET ['action']) {

						case '查看编辑基本信息' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
									$a->listAllMediaBasic_table($a->queryMedia(),0,1000);
								}
							}
							break;
						
						

						case '查看编辑标签正则' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
									$a->listAllMediaTag_table($a->queryMedia(),0,1000);
								}
							}
							break;
						
							
						case 'editTag' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								$a->showOneMediaTag_form ( $a->queryMedia ( array (
									'type' => 'findone',
									'value' => $_GET ['mindex'] 
							) ) );
							} else if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
								if ($a->updateMediaTag ( $_POST )) {
									echo '编辑成功';
								} else {
									echo '编辑异常';
								}
							}
							break;
						case 'editBasic' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								$a->showOneMediaBasic_form ( $a->queryMedia ( array (
									'type' => 'findone',
									'value' => $_GET ['mindex'] 
							) ) );
							} else if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
								if ($a->updateMediaBasic ( $_POST )) {
									echo '编辑成功';
								} else {
									echo '编辑异常';
								}
							}
							break;
						case '查看媒体数据':
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
									$a->listAllMediaStat_table($a->queryMedia(),0,1000);
								}
							}


						case '添加新媒体':
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
									$a->showNewMedia_form();
								}
							} else if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
								if ($a->createMedia ( $_POST )) {
									echo '添加成功';
								} else {
									echo '添加异常';
								}
							}






						}
					}
					?>
			
			</div>
		</div>
	</div>
<?php
$GLOBALS ['TEMPLATE'] ['content'] = ob_get_contents ();
ob_end_clean ();
ob_start ();
?>

<link href="css/navbar-fixed-top.css" rel="stylesheet">
	<link href="css/dashboard.css" rel="stylesheet">
<?php
$GLOBALS ['TEMPLATE'] ['extra_head'] = ob_get_contents ();
ob_end_clean ();
?>

<?php
// display the page
include_once '../templates/template-page-zhidemain.php';
?>
								