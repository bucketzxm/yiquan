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
				<li><a href="?action=static">批量操作 <span class="sr-only">(current)</span></a></li>
				<li><a href="?action=statistics">用户分布统计 <span class="sr-only">(current)</span></a></li>
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
								$a->htEditUserById_form ( $a->getUserByID ( $_GET ['uid'] ) );
							}
							if ($_SERVER ['REQUEST_METHOD'] == 'POST' && isset ( $_POST ['_id'] ) && isset ( $_POST ['edittype'] )) {
								$a = new YqUser ();
								switch ($_POST ['edittype']) {
									case 'basic' :
										$a->hteditUserBasicInfo ( $_POST );
										break;
									case 'profile' :
										$a->hteditUserProfileInfo ( $_POST );
										break;
									case 'password' :
										$a->htchangePasswordByID ( $_POST ['_id'], $_POST ['user_pin'] );
										break;
									case 'userpic' :
										// echo $_FILES ['user_pic'] ["tmp_name"];
										if ($_FILES ['user_pic'] ['error'] == 0)
											$a->htupdateUserpicByUserId ( file_get_contents ( $_FILES ['user_pic'] ["tmp_name"] ), $_POST ['_id'] );
										break;
								}
								$a->htEditUserById_form ( $a->getUserByID ( $_POST ['_id'] ) );
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
						
						case 'behavior' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET' && isset ( $_GET ['uid'] ) && isset ( $_GET ['type'] ) && isset ( $_GET ['value'] )) {
								$a = new YqUser ();
								$t1 = $a->htgetBehaviorsByid ( $_GET ['uid'], array (
										'type' => $_GET ['type'],
										'value' => $_GET ['value'] 
								) );
								// var_dump ( $t1 );
								$a->htgetBehaviorsByid_showtable ( $t1, 1 );
							}
							break;
						
						case 'statistics' :
							$a = new YqUser ();
							$t1 = $a->htgetAllUserState ();
							$a->htgetAllUserState_showtable ( $t1, 1 );
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