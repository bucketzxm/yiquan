<?php
include_once '../lib/common.php';
include_once '../lib/functions.php';
include_once '../lib/YqUser.php';
include_once '../lib/User.php';
include_once '../lib/YqPlatformView.php';
include_once '../lib/REAccountView.php';
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
				<li><a href="?action=学校信息查询">学校信息查询<span class="sr-only">(current)</span></a></li>
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

						case '学校信息查询' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
						
									$a->listChinaProvinces();
						
							}
							break;
						
						case '该省学校名单' :

							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
						
									$a->showAccountsByRegion($a->getAccountsByRegion($_GET ['mindex']));
						
							}
							break;

						case '添加学校' :

							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
						
									$a->addNewAccount();
						
							}
							break;

						case '提交学校信息' :

							if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
						
									$a->updateNewAccount($_POST);
						
							}
							break;

						case '学校明细' :

							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
						
									$a->showDetailsByAccount($a->getDetailsByAccount($_GET ['mindex']));
						
							}
							break;


						case '联系人明细' :

							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
						
									$a->showDetailsByContact($a->getDetailsByContact($_GET ['mindex']));
						
							}
							break;


						case '添加学校联系人' :

							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
						
									$a->addContactByAccount($_GET ['mindex']);
						
							}
							break;

						case '提交学校联系人' :

							if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
								if ($a->updateContactByAccount($_POST)) {
									echo '添加学校联系人成功';
								}
									
							}
							break;


						case '添加学校交互记录' :

							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {

								$contacts = $a->getContactsByAccount($_GET ['mindex']);
								$projects = $a->getProjectsByAccount($_GET ['mindex']);
								$a->addActionByAccount($_GET ['mindex'],$contacts,$projects);
						
							}
							break;

						case '提交学校交互记录' :

							if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
								if ($a->updateActionByAccount($_POST)) {
									echo '添加学校联系人成功';
								}
									
							}
							break;

						case '修改交互笔记' :

							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								$action = $a->getActionByID($_GET['actionID']);
								$a->changeActionByID($action);
							}
							break;

						case '提交新的交互笔记' :

							if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
						
								$a->updateActionNoteByID($_POST);
							}
							break;	

						case '完成交互状态' :

							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
						
								$a->completeActionByID($_GET['mindex']);
							}
							break;	
						}

						case '更改学校补充说明' :

							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
						
								$a->changeAccountSupplementByID($_GET['accountID'],$_GET['supplement']);
							}
							break;	

						case '提交新的学校补充说明' :

							if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
						
								$a->updateAccountSupplementByID($_POST['account_id'],$_POST['supplement']);
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
include_once '../templates/template-page-reachableedumain.php';
?>
								