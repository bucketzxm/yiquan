<?php
include_once '../lib/common.php';
include_once '../lib/functions.php';
include_once '../lib/YqUser.php';
include_once '../lib/User.php';
include_once '../lib/YqPlatformView.php';
include_once '../lib/ZDGroupView.php';
// 401 file referenced since user should be logged in to view this page
include_once '401.php';

// generate user information form
$user = User::getById ( $_SESSION ['userId'] );
ob_start ();
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-3 col-md-2 sidebar">
			<ul class="nav nav-sidebar">
				<li><a href="?action=查看信息组统计数据">查看信息组统计数据 <span class="sr-only">(current)</span></a></li>
				<li><a href="?action=查看编辑信息组基本信息">查看编辑信息组基本信息 <span class="sr-only">(current)</span></a></li>
				<li><a href="?action=查看编辑Group媒体信息">查看编辑Group媒体信息 <span class="sr-only">(current)</span></a></li>
				<li><a href="?action=添加新信息组">添加新信息组 <span class="sr-only">(current)</span></a></li>
				<li><a href="?action=查看信息组文章">查看信息组文章 <span class="sr-only">(current)</span></a></li>


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
					$a = new GroupView ();
					switch ($_GET ['action']) {
						
						case '查看信息组统计数据' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								$a->listAllGroupStat_table ( $a->queryGroup (), 0, 10000 );
							}
							break;
						case '查看编辑信息组基本信息' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								$a->listAllGroupBasic_table ( $a->queryGroup (), 0, 10000 );
							}
							break;
						case 'editGroupBasic' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								$a->showOneGroupBasic_form ( $a->queryGroup ( array (
									'type' => 'findone',
									'value' => $_GET ['mindex'] 
							) ) );
							} else if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
								if ($a->updateGroupBasic ( $_POST )) {
									echo '编辑成功';
								} else {
									echo '编辑异常';
								}
							}
						case '查看编辑Group媒体信息' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								$a->listAllGroupMedia_table ( $a->queryGroup (), 0, 10000 );
							}
							break;							
						case '查看' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								$a->listAllSeed_table ( $a->queryGroup (), 0, 10000 );
							}
							break;							
						case '热度查看' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								$a->listAllSeedbyhotness_table ( $a->queryGroup (), 0, 10000 );
							}
							break;							



						case '查看信息组文章' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								$a->listAllGroupSeed_table ( $a->queryGroup (), 0, 10000 );
							}
							break;				
						case 'editGroupMedia' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								$a->showOneGroupMedia_form ( $a->queryGroup ( array (
									'type' => 'findone',
									'value' => $_GET ['mindex'] 
								) ) );			
							} else if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
								if ($a->updateGroupMedia ( $_POST )) {

									echo '编辑成功';

								} else {
									echo '编辑异常';
								}
							}
							break;

						case '添加新信息组':
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
									$a->showNewMediaGroup_form();
								}
							} else if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
								if ($a->createMediaGroup ( $_POST )) {
									echo '添加成功';
								} else {
									echo '添加异常';
								}
							}
							break;
						case '编辑媒体行业及推荐理由'	:
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								$a->showMedias_form ( $a->queryGroup ( array (
									'type' => 'findone',
									'value' => $_GET ['mindex'] 
								) ) );			
							} else if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
								if ($a->updateMedias ( $_POST )) {

									echo '编辑成功';

								} else {
									echo '编辑异常';
								}
							}
							break;

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