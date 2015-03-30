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
					$a = new YqSystemTopicView ();
					switch ($_GET ['action']) {
						case 'view' :
							if (isset ( $_GET ['page'] )) {
								if ($_GET ['page'] == 0) {
									$_SESSION ['systemTopic'] = $a->getSystemTopics ( 'recive', '', strtotime ( '-10 days' ), time () );
									$_SESSION ['systemTopicPages'] = $a->listallsystemTopicPages ( $_SESSION ['systemTopic'], 30 );
									//var_dump ( $_SESSION ['systemTopic'] );
								}
								if (! empty ( $_SESSION ['systemTopic'] )) {
									$a->showSystemTopic_table ( $_SESSION ['systemTopic'], $_SESSION ['systemTopicPages'] [$_GET ['page']], 30 );
									$a->showTopicPageLink ( $_SESSION ['systemTopicPages'], 'view' );
								}
							}
							break;
						case 'edit' :
							
							break;
						case 'delete' :
							
							break;
						case 'addsystemtopic' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								$a->showaddSystemtopic_form ();
							} else {
								if ($a->addSystemtopic ( 'second', 'system', 'dialogue', $_POST ['title'], $_POST ['labels'] )) {
									echo '添加成功';
								} else {
									echo '粗了点问题';
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