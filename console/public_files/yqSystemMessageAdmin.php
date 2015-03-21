<?php
include_once '../lib/common.php';
include_once '../lib/functions.php';
include_once '../lib/YqUser.php';
include_once '../lib/User.php';
include_once '../lib/YqSystemMessageView.php';

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
				<li><a href="?action=view&page=0&limit=30">查看发给我的消息 <span
						class="sr-only"></span></a></li>
				<li><a href="?action=viewsend&page=0&limit=30">查看已发送的系统消息 <span
						class="sr-only"></span></a></li>
				<li><a href="?action=addSystemMessage">新建系统消息 <span class="sr-only"></span></a></li>
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
					$a = new YqSystemMessageView ();
					switch ($_GET ['action']) {
						case 'view' :
							if (isset ( $_GET ['page'] )) {
								if ($_GET ['page'] == 0) {
									$_SESSION ['systemMessage'] = $a->getSystemMessage ( 'recive', '', strtotime ( '-1000 days' ), time () );
									$_SESSION ['systemMessagePages'] = $a->getSystemMessagePages ( $_SESSION ['systemMessage'], 30 );
									// var_dump ( $_SESSION ['systemMessage'] );
								}
								if (! empty ( $_SESSION ['systemMessage'] )) {
									$a->showMessage_table ( $_SESSION ['systemMessage'], $_SESSION ['systemMessagePages'] [$_GET ['page']], 30 );
									$a->showMessagePages_div ( $_SESSION ['systemMessagePages'], 'view' );
								}
							}
							break;
						case 'edit' :
							
							break;
						case 'detail' :
							if (isset ( $_GET ['mindex'] ) && isset ( $_GET ['messageid'] )) {
								$a->readMessage ( $_GET ['messageid'] );
								$a->showMessageDetail ( $_SESSION ['systemMessage'], $_GET ['mindex'] );
							}
							break;
						case 'viewsend' :
							if (isset ( $_GET ['page'] )) {
								if ($_GET ['page'] == 0) {
									$_SESSION ['systemMessage'] = $a->getSystemMessage ( 'send', '', strtotime ( '-1000 days' ), time () );
									$_SESSION ['systemMessagePages'] = $a->getSystemMessagePages ( $_SESSION ['systemMessage'], 30 );
									// var_dump ( $_SESSION ['systemMessage'] );
								}
								if (! empty ( $_SESSION ['systemMessage'] )) {
									$a->showMessage_table ( $_SESSION ['systemMessage'], $_SESSION ['systemMessagePages'] [$_GET ['page']], 30 );
									$a->showMessagePages_div ( $_SESSION ['systemMessagePages'], 'viewsend' );
								}
							}
							break;
						case 'delete' :
							
							break;
						case 'addSystemMessage' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								$a->addSystemMessage_form ();
							} else {
									// var_dump ( $_POST );
								$res = $a->addSystemMessage ( isset ( $_POST ['forall'] ), $_POST ['reciver'], $_POST ['type'], $_POST ['title'], $_POST ['labels'], $_POST ['detail'], $_POST ['message_webViewHeader'], $_POST ['message_webViewURL'] );
								echo '已经发送给' . $res . '人';
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