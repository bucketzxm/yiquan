<?php
include_once '../lib/common.php';
include_once '../lib/functions.php';
include_once '../lib/YqUser.php';
include_once '../lib/User.php';

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
				<li><a href="?action=view&page=0">查看所有用户 <span class="sr-only">(current)</span></a></li>
				<li><a href="?action=static">用户统计 <span class="sr-only">(current)</span></a></li>
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
							$a = new YqUser ();
							if (! isset ( $_SESSION ['pagelimit'] ) || ! isset ( $_SESSION ['pagearrs'] )) {
								$_SESSION ['pagelimit'] = 20;
								$_SESSION ['pagearrs'] = $a->htlistallUserPages ( $_SESSION ['pagelimit'] );
							}
							$a->htListAllUsers_table ( $a->htListAllUsers ( $_SESSION ['pagearrs'] [$_GET ['page']], $_SESSION ['pagelimit'] ) );
							$a->showPageNumbers_table ( $_SESSION ['pagearrs'], 0, 10, $_SESSION ['pagelimit'] );
							break;
						case 'edit' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET' && isset ( $_GET ['uid'] )) {
								$a = new YqUser ();
								$a->htEditUserById_form ( $a->getUserByID ( $_GET ['uid'] ), 1 );
							}
							break;
						case 'delete' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET' && isset ( $_GET ['uid'] )) {
								$a = new YqUser ();
								$a->htDeleteUserById_form ( $a->getUserByID ( $_GET ['uid'] ) );
							} else if ($_SERVER ['REQUEST_METHOD'] == 'POST' && isset ( $_POST ['uid'] )) {
								$a = new YqUser ();
								if ($a->htDeleteUserById ( $_POST ['uid'] )) {
									echo 'Delete Success!';
								} else {
									echo 'Delete Failed!';
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
include_once '../templates/template-page-main.php';
?>