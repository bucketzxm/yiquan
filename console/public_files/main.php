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
?>

<?php
// $query = sprintf('SELECT FORUM_ID, FORUM_NAME, DESCRIPTION FROM %sFORUM ' .
//             'ORDER BY FORUM_NAME ASC, FORUM_ID ASC', DB_TBL_PREFIX);
//     $result = mysql_query($query, $GLOBALS['DB']);
	
//     $a=0;
    
//     while ($row = mysql_fetch_array($result))
//     {
        
//         $a++;
//         echo '<div class="col-lg-4">'; 
//         echo '<h2>' . htmlspecialchars($row['FORUM_NAME']) . '</h2> ';
//         echo '<p>'. htmlspecialchars($row['DESCRIPTION']) . '</p>';
//         echo '<p><a class="btn btn-default" href="view.php?fid='. htmlspecialchars($row['FORUM_ID']).'" role="button">进入班级 »</a></p>';
//         echo '</div>';
        
//     }

//     mysql_free_result($result);
?>

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
        <h1>这里是一圈后台！</h1>
        <p><a class="btn btn-primary btn-lg" role="button" href="meiyanmain.php">每言后台</a></p>
        <p><a class="btn btn-primary btn-lg" role="button" href="zhidemain.php">值得后台</a></p>
      </div>
    </div>


<?php
$GLOBALS['TEMPLATE']['title'] = ob_get_contents();
ob_end_clean();


// display the page
include_once '../templates/template-page-main.php';
?>


