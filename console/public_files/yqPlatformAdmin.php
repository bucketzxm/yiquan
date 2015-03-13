<?php
include_once '../lib/common.php';
include_once '../lib/functions.php';
include_once '../lib/YqUser.php';
include_once '../lib/User.php';
include_once '../lib/YqPlatform.php';

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
				<li><a href="?action=version">一圈版本控制 <span class="sr-only">(current)</span></a></li>
				<li><a href="?action=statistic">一圈平台信息 <span class="sr-only">(current)</span></a></li>
				<li><a href="?action=statisticforuser">一圈用户注册数量统计 <span
						class="sr-only">(current)</span></a></li>
				<li><a href="?action=statisticforActiveuser">一圈用户活跃数量统计 <span
						class="sr-only">(current)</span></a></li>
				<li><a href="?action=report">一圈统计报告 <span class="sr-only">(current)</span></a></li>

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
					switch ($_GET ['action']) {
						case 'version' :
							$a = new YqPlatform ();
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								
								$a->getLastestVersion_showform ( $a->getLastestVersion ( 'Android' ) );
								$a->getLastestVersion_showform ( $a->getLastestVersion ( 'IOS' ) );
							}
							if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
								$a->updateLastestVersion ( $_POST ['plat'], $_POST ['lastestVersion'] );
								$a->getLastestVersion_showform ( $a->getLastestVersion ( 'Android' ) );
								$a->getLastestVersion_showform ( $a->getLastestVersion ( 'IOS' ) );
							}
							break;
						case 'statistic' :
							
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								$a->getPlatformStatistic_showtable ( $a->getPlatformStatistic () );
							}
							break;
						case 'statisticforuser' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								$a->getUserStatistic_showtable ( $a->getUserStatistic () );
							}
							break;
						case 'report' :
							
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
include_once '../templates/template-page-main.php';
?>