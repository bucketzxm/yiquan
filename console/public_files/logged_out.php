<?php

ob_start();?>
<div class="container">
<p>logout successful</p>
<p><a href="login.php">login again</a></p>
</div>
<?php
$GLOBALS['TEMPLATE']['content'] = ob_get_clean();
?>



<?php
ob_start();
?>
<link rel="stylesheet" type="text/css" href="css/signin.css"></link>
<?php
$GLOBALS['TEMPLATE']['extra_head'] = ob_get_clean();
// display the page
include '../templates/template-page.php';
?>