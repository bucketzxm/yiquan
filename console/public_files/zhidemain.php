<?php
// include_once shared code
include_once '../lib/common.php';
include_once '../lib/functions.php';
include_once '../lib/User.php';

// 401 file referenced since user should be logged in to view this page
include_once '401.php';

// generate user information form
$user = User::getById($_SESSION['userId']);
ob_start();


<?php
$GLOBALS['TEMPLATE']['content'] = ob_get_contents();
ob_end_clean();
ob_start();
?>
<link href="css/navbar-fixed-top.css" rel="stylesheet">
<?php
$GLOBALS['TEMPLATE']['extra_head'] = ob_get_contents();
ob_end_clean();


ob_start();
?>


<div class="jumbotron">
      <div class="container">
        <h1>这里是值得后台！</h1>
        <p><a class="btn btn-primary btn-lg" role="button" href="main.php">一圈后台</a></p>
      </div>
    </div>


<?php
$GLOBALS['TEMPLATE']['title'] = ob_get_contents();
ob_end_clean();


// display the page
include_once '../templates/template-page-zhidemain.php';
?>


