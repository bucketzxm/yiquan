<?php
include_once '../lib/common.php';
include_once '../lib/functions.php';
include_once '../lib/YqUser.php';
include_once '../lib/User.php';
include_once '../lib/YqPlatformView.php';
include_once '../lib/MyQuoteuserView.php';
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
				<li><a href="?action=viewUser">查看所有用户 <span class="sr-only">(current)</span></a></li>
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
					$a = new MyQuoteuserView ();
					switch ($_GET ['action']) {
						
						case 'viewUser' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								$a->getAllUsersInfo ();
							}
							break;
						
						case 'delete' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								if (isset ( $_GET ['mindex'] )) {
									$a->showDeleteView ( $_GET ['mindex'] );
								}
							} else {
								if ($a->deleteQuotes ( $_POST ['qid'] )) {
									echo '成功';
								} else {
									echo '失败';
								}
							}
							break;
						case 'edit' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								$a->showOneQuote_form ( $a->queryQuotes ( array (
										'type' => 'findone',
										'value' => $_GET ['mindex'] 
								) ) );
							} else if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
								if ($a->updateQuote ( $_POST )) {
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
include_once '../templates/template-page-meiyanmain.php';
?>