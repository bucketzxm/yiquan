<?php
// include shared code

include '../lib/common.php';
include '../lib/db.php';
include '../lib/functions.php';
include '../lib/User.php';
include '401.php';


$user=User::getById($_SESSION['userId']);

if(!($user->permission & User::CREATE_FORUM))
{
	
	die('<p>Sorry, you do not have sufficient privileges to create new ' .
        'forums.</p>');
}



// validate incoming values
$forum_name = (isset($_POST['forum_name'])) ? trim($_POST['forum_name']) : '';
$forum_desc = (isset($_POST['forum_desc'])) ? trim($_POST['forum_desc']) : '';

// add entry to the database if the form was submitted and the necessary
// values were supplied in the form
if (isset($_POST['submitted']) && $forum_name && $forum_desc)
{
    $query = sprintf('INSERT INTO %sFORUM (FORUM_NAME, DESCRIPTION) ' .
        'VALUES ("%s", "%s")', DB_TBL_PREFIX,
        mysql_real_escape_string($forum_name, $GLOBALS['DB']),
        mysql_real_escape_string($forum_desc, $GLOBALS['DB']));
    mysql_query($query, $GLOBALS['DB']);

    // redirect user to list of forums after new record has been stored
    //header('Location: view.php');
}


// form was submitted but not all the information was correctly filled in
else if (isset($_POST['submitted']))
{
    $message = '<p>Not all information was provided.  Please correct ' .
        'and resubmit.</p>';
}

// validate incoming values
$edforum_id = (isset($_POST['fid'])) ? trim($_POST['fid']) : '';
$edforum_name = (isset($_POST['fname'])) ? trim($_POST['fname']) : '';
$edforum_desc = (isset($_POST['fdisc'])) ? trim($_POST['fdisc']) : '';

//print($edforum_id);
//print($edforum_name);
//print($edforum_desc);
if (isset($_POST['edforumsubmitted']) && $edforum_id && $edforum_name && $edforum_desc)
{
    
    $query = sprintf('UPDATE %sFORUM SET FORUM_NAME = "%s", ' .
                'DESCRIPTION = "%s" WHERE FORUM_ID = %d', DB_TBL_PREFIX,
                mysql_real_escape_string($edforum_name, $GLOBALS['DB']),
                mysql_real_escape_string($edforum_desc, $GLOBALS['DB']),
                (int)$edforum_id);
            
    
    mysql_query($query, $GLOBALS['DB']);

    // redirect user to list of forums after new record has been stored
    //header('Location: view.php');
}

// validate incoming values
$delforum_id = (isset($_GET['delforumid'])) ? $_GET['delforumid'] : '';
print($delforum_id);
if (isset($_GET['delforumid']))
{
     $query = sprintf('DELETE FROM %sFORUM WHERE FORUM_ID = %d', DB_TBL_PREFIX,(int)$delforum_id);
            
    
    mysql_query($query, $GLOBALS['DB']);
}
//{
    
   
//}
// generate the form
ob_start();
if (isset($message))
{
    echo $message;
}
?>
<form action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>"
 method="post">
 <div>
  <label for="forum_name">Forum Name:</label>
  <input type="input" id="forum_name" name="forum_name" value="<?php
   echo htmlspecialchars($forum_name); ?>"/><br/>
  <label for="forum_desc">Description:</label>
  <input type="input" id="forum_desc" name="forum_desc" value="<?php
   echo htmlspecialchars($forum_desc); ?>"/>
  <br/>
  <input type="hidden" name="submitted" value="true"/>
  <input type="submit" value="Create"/>
 </div>
</form>
<hr/>

<?php
	$query = sprintf('SELECT * FROM %sFORUM',
        DB_TBL_PREFIX);
    $result = mysql_query($query, $GLOBALS['DB']);
	echo '<table  class="table table-hover">';
	echo '<tr><th>ID</th><th>名字</th><th>描述</th><th></th><th></th></tr>';
	while ($row = mysql_fetch_array($result))
    {
        echo '<tr><td>'.$row['FORUM_ID'].'</td><td>'.$row['FORUM_NAME'].'</td><td>'.$row['DESCRIPTION'].'</td><td>'.'<a href="adminforum.php?editforumid='.$row['FORUM_ID'].'">编辑</a></td><td>'.'<a href="adminforum.php?delforumid='.$row['FORUM_ID'].'">删除</a></td></tr>';
    }
	echo '</table>';
	      
?>



<?php
	if(isset($_GET['editforumid']))
    {
        $query = sprintf('SELECT * FROM %sFORUM WHERE FORUM_ID=%d',
        DB_TBL_PREFIX,(int)$_GET['editforumid']);
    	$result = mysql_query($query, $GLOBALS['DB']);
        $row = mysql_fetch_array($result);
?>
	<div class="highlight">
    <h2>修改版块名称</h2>
    <hr/>
<form action="adminforum.php" method="post">
 <table>
  <tr>
   <td><label>ID</label></td>
   <td><input type="text" name="nofid" disabled="disabled"  
    readonly="readonly" value="<?php echo $row['FORUM_ID']; ?>"/></td>
  </tr><tr>
   <td><label for="fname">论坛名称</label></td>
   <td><input type="text" name="fname" id="fname"
    value="<?php echo $row['FORUM_NAME']; ?>"/></td>
  </tr><tr>
   <td><label for="fdisc">论坛描述</label></td>
   <td><textarea row="3" name="fdisc" id="fdisc"><?php
        echo htmlspecialchars($row['DESCRIPTION']); ?></textarea></td>
  </tr>
     <tr>
  <td> </td>
   <td><input type="submit" value="Save"/></td>
   <td><input type="hidden" name="edforumsubmitted" value="1"/><input type="hidden" name="fid" value="<?php echo $row['FORUM_ID']; ?>"/></td>
  </tr>
 </table>
</form>
</div>
<?php
   	}

?>


<?php
$GLOBALS['TEMPLATE']['content'] = ob_get_clean();

// display the page
include '../templates/template-page.php';
?>
