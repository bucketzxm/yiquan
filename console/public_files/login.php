<?php
// include_once shared code
include_once '../lib/common.php';
include_once '../lib/functions.php';
include_once '../lib/User.php';

if (! isset ( $_SESSION ))
	session_start ();
header ( 'Cache-control: private' );

if (isset ( $_GET ['login'] )) {
	if (isset ( $_POST ['username'] ) && isset ( $_POST ['password'] )) {
		$user = (User::validateUsername ( $_POST ['username'] )) ? User::getByUsername ( $_POST ['username'] ) : new User ();
		if ($user->userId && $user->password == $_POST ['password']) {
			$_SESSION ['access'] = true;
			$_SESSION ['userId'] = $user->userId;
			$_SESSION ['username'] = $user->username;
			header ( 'Location:meiyanmain.php' );
		} else {
			$_SESSION ['access'] = false;
			$_SESSION ['username'] = null;
			header ( 'Location:401.php' );
		}
	} else {
		$_SESSION ['access'] = false;
		$_SESSION ['username'] = null;
		header ( 'Location:401.php' );
	}
	exit ();
} else if (isset ( $_GET ['logout'] )) {
	if (isset ( $_COOKIE [session_name ()] )) {
		setcookie ( session_name (), '', time () - 42000, '/' );
	}
	
	$_SESSION = array ();
	session_unset ();
	// $_SESSION['access']=false;
	// session_destory();
	header ( 'Location:logged_out.php' );
	exit ();
}

ob_start ();
?>

<?php
if (isset ( $_GET ['logout'] )) {
	?>
<p>logout successful</p>
<p>
	<a href="login.php">login again</a>
</p>
<?php
} else {
	?>

<div class="container">
	<form
		action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?login"
		class="form-signin" role="form" method="post">
		<h2 class="form-signin-heading">请登录</h2>
		<table>
			<tr>
				<td><label for="username">用户名</label></td>
				<td><input class="form-control" type="text" name="username"
					id="username" /></td>
			</tr>
			<tr>
				<td><label for="password">密码</label></td>
				<td><input class="form-control" type="password" name="password"
					id="password" /></td>
			</tr>
			<tr>
				<td></td>
				<td><input class="btn btn-lg btn-primary btn-block" type="submit"
					value="Log In" /></td>

			</tr>

			<tr>
				<td></td>
				<td><p class="mylogin">
						<a href="forgotpass.php">忘记密码</a>
					</p></td>
			</tr>
		</table>
	</form>
</div>
<?php
}
?>

<?php
$GLOBALS ['TEMPLATE'] ['content'] = ob_get_clean ();

?>
<?php

ob_start ();
?>
<link rel="stylesheet" type="text/css" href="css/signin.css"></link>
<?php
$GLOBALS ['TEMPLATE'] ['extra_head'] = ob_get_clean ();
// display the page
include_once '../templates/template-page.php';
?>
