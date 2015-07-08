<?php
include_once '../lib/common.php';
include_once '../lib/functions.php';
include_once '../lib/YqUser.php';
include_once '../lib/User.php';
include_once '../lib/YqPlatformView.php';
include_once '../lib/ZDSeedView.php';
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
				<li><a href="?action=文章列表">文章列表 <span class="sr-only">(current)</span></a></li>
				<li><a href="?action=生活文章-待审核">生活文章-待审核 <span class="sr-only">(current)</span></a></li>
				<li><a href="?action=媒体文章数据">媒体文章数据 <span class="sr-only">(current)</span></a></li>
				<li><a href="?action=文章统计数据">文章统计数据 <span class="sr-only">(current)</span></a></li>
				<li><a href="?action=查询">按日期查询 <span class="sr-only">(current)</span></a></li>


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
					$a = new SeedView();
					switch ($_GET ['action']) {

						case '文章列表' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
									$a->listAllSeed_table($a->querySeed(),0,5000);
								}
							}
							break;
						
						case '生活文章-待审核' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
									$a->listAllSeed_table($a->queryLifeSeedToReview(),0,5000);
								}
							}
							break;

						case '媒体文章数据' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
									$a->listAllSeedStat_table($a->querySeed(),0,5000);
								}
							}
							break;
						case '文章统计数据' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
									$a->listSeedStat_table();
								}
							}
							break;
												


							
						case 'editSeed' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								$a->showOneSeed_form ( $a->querySeed ( array (
									'type' => 'findone',
									'value' => $_GET ['mindex'] 
							) ) );
							} else if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
								if ($a->updateSeed ( $_POST )) {
									echo '编辑成功';
								} else {
									echo '编辑异常';
								}
							}
							break;
						case 'deleteSeed' :
							/*
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								if (isset ( $_GET ['mindex'] )) {
									$a->showDeleteSeedView ( $_GET ['mindex'] );
								}
							} else {
								if ($a->deleteSeed ( $_POST ['qid'] ) == 1) {
									echo '成功';
								} else {
									echo '失败';
								}
							}
							*/
							$a->deleteSeed ( $_GET ['mindex'] );

							echo '枪毙成功';
							//echo "<script> {window.alert('枪毙成功');} </script>";


							break;















						case '查询' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								$a->getDailyReportSearchform ();
							}
							if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
								$a->getDailyReport ( $a->getReport ( array (
										'startday' => strtotime ( $_POST ['starttime'] ) 
								) ) );
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
								