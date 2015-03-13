<?php

// include shared code
include '../lib/common.php';
include '../lib/db.php';
include '../lib/functions.php';
include '../lib/User.php';

// 401 file referenced since user should be logged in to view this page
include '401.php';


// start or continue session
session_start();


// generate user information form
$user = User::getById($_SESSION['userId']);
ob_start();
?>
<div class="highlight">
    <h2>修改我的信息</h2>
    <hr/>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
 method="post">
 <table>
  <tr>
   <td><label>Username</label></td>
   <td><input type="text" name="username"  disabled="disabled"
    readonly="readonly"value="<?php echo $user->username; ?>"/></td>
  </tr><tr>
   <td><label for="email">Email Address</label></td>
   <td><input type="text" name="email" id="email"
    value="<?php echo (isset($_POST['email']))? htmlspecialchars(
$_POST['email']) : $user->emailAddr; ?>"/></td>
  </tr><tr>
   <td><label for="password">New Password</label></td>
   <td><input type="password" name="password1" id="password1"/></td>
  </tr><tr>
   <td><label for="password2">Password Again</label></td>
   <td><input type="password" name="password2" id="password2"/></td>
  </tr><tr>
  <td> </td>
   <td><input type="submit" value="Save"/></td>
   <td><input type="hidden" name="submitted" value="1"/></td>
  </tr>
 </table>
</form>
    <h2>修改我的头像</h2>
    <hr/>
    <div class="row">
        <div class="col-md-4">
            <img src="<?php 

			$s2 = new SaeStorage();
			echo $s2->getUrl('smallform','avatars/' . $user->username . '.jpg');
			?>"/>
        
        
        </div>
      <div  class="col-md-8">
    <form action="upload_avatar.php" method="post" enctype="multipart/form-data">
        <input  type="file"  name="avatar" />
        <input type="submit" value="upload" />
    </form>
        </div>
        </div>
</div>
<?php
$form = ob_get_clean();

// show the form if this is the first time the page is viewed
if (!isset($_POST['submitted']))
{
    $GLOBALS['TEMPLATE']['content'] = $form;
}
else
{
     // validate password
    $password1 = (isset($_POST['password1']) && $_POST['password1']) ?
        sha1($_POST['password1']) : $user->password;
    $password2 = (isset($_POST['password2']) && $_POST['password2']) ?
        sha1($_POST['password2']) : $user->password;
    $password = ($password1 == $password2) ? $password1 : '';

    // update the record if the input validates
    if (User::validateEmailAddr($_POST['email']) && $password)
    {
        $user->emailAddr = $_POST['email'];
        $user->password = $password;
        $user->save();

        $GLOBALS['TEMPLATE']['content'] = '<p><strong>Information ' .
            'in your record has been updated.</strong></p>';
		$GLOBALS['TEMPLATE']['content'] .= $form;
    }
    // there was invalid data
    else
    {
        $GLOBALS['TEMPLATE']['content'] .= '<p><strong>You provided some ' .
            'invalid data.</strong></p>';
        $GLOBALS['TEMPLATE']['content'] .= $form;
    }
}

ob_start();
?>


<link href="css/navbar-fixed-top.css" rel="stylesheet">


<?php
$GLOBALS['TEMPLATE']['extra_head'] = ob_get_contents();
ob_end_clean();



// display the page
include '../templates/template-page-main.php';
?>



