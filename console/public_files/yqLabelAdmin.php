<?php
include_once '../lib/common.php';
include_once '../lib/functions.php';
include_once '../lib/YqUser.php';
include_once '../lib/User.php';
include_once '../lib/YqLabel.php';

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
				<li><a href="?action=edit">一圈标签控制 <span class="sr-only">(current)</span></a></li>

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
				$a = new YqLabel ();
				if (isset ( $_GET ['action'] )) {
					switch ($_GET ['action']) {
						case 'edit' :
							if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
								$a->getLabels_showtable ();
								
								if (isset ( $_GET ['label_type'] ) && isset ( $_GET ['label_name'] )) {
									$a->addoreditLabel_showform ( $a->getLabelByName ( $_GET ['label_type'], $_GET ['label_name'] ) );
								} else {
									$a->addoreditLabel_showform ();
								}
							}
							break;
						case 'update' :
							if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
								$arr = array (
										'type' => 'unknown',
										'name' => 'unkonw',
										'pic' => '' 
								);
								var_dump($_FILES);
								if (isset ( $_FILES ['lpic'] ) && $_FILES ['lpic'] ['error'] == 0 && isset ( $_FILES ['lpic'] ['tmp_name'] )) {
									$arr ['pic'] = base64_encode ( file_get_contents ( $_FILES ['lpic'] ["tmp_name"] ) );
								}
								$arr ['type'] = $_POST ['ltype'];
								$arr ['name'] = $_POST ['lname'];
								
								$a->htupdateLabel ( $arr );
							}
							$a->getLabels_showtable ();
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