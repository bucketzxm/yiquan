<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<!-- Bootstrap -->
<link rel="stylesheet"
	href="http://cdn.bootcss.com/bootstrap/3.3.2/css/bootstrap.min.css" />


<title>
	<?php
	if (! empty ( $GLOBALS ['TEMPLATE'] ['page-title'] )) {
		echo $GLOBALS ['TEMPLATE'] ['page-title'];
	}
	?>
</title>
<?php
if (! empty ( $GLOBALS ['TEMPLATE'] ['extra_head'] )) {
	echo $GLOBALS ['TEMPLATE'] ['extra_head'];
}
?>
</head>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse"
					data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span> <span
						class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">值得一读后台</a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li class="active"><a href="zhidemain.php">主页</a></li>
					
					<li><a href="ZDMediaAdmin.php">媒体管理</a></li>
				
					<li><a href="login.php?logout">退出</a></li>

				</ul>

			</div>
			<!--/.nav-collapse -->
		</div>
	</div>
	<div id="header">
<?php
if (! empty ( $GLOBALS ['TEMPLATE'] ['title'] )) {
	echo $GLOBALS ['TEMPLATE'] ['title'];
}
?>
  </div>
	<div id="content" class="container">
<?php
if (! empty ( $GLOBALS ['TEMPLATE'] ['content'] )) {
	echo $GLOBALS ['TEMPLATE'] ['content'];
}
?>
  </div>
	<hr>
		<div id="footer" class="footer">Copyright &copy;<?php echo date('Y'); ?>
  </div>
		<!-- Bootstrap core JavaScript================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="http://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
		<script
			src="http://cdn.bootcss.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

</body>
</html>