<?php
include_once '../lib/common.php';
include_once '../lib/functions.php';
include_once '../lib/YqUser.php';
include_once '../lib/User.php';
include_once '../lib/YqSystemTopicView.php';

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
				<li><a href="?action=view&page=0">查看系统话题 <span class="sr-only"></span></a></li>
				<li><a href="?action=addsystemtopic">新建系统话题 <span class="sr-only"></span></a></li>
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
						case 'view' :
							
							break;
						case 'edit' :
							
							break;
						case 'delete' :
							
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