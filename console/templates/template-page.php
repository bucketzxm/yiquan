<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
 <meta http-equiv='Content-Type' content='text/html; charset=utf-8' /> 
 <!-- Bootstrap -->
    <link rel="stylesheet" href="http://cdn.bootcss.com/twitter-bootstrap/3.0.3/css/bootstrap.min.css">
     
  <title>
<?php
if (!empty($GLOBALS['TEMPLATE']['page-title']))
{
    echo $GLOBALS['TEMPLATE']['page-title'];
}
?>
</title>
  
<?php
if (!empty($GLOBALS['TEMPLATE']['extra_head']))
{
    echo $GLOBALS['TEMPLATE']['extra_head'];
}
?>
 </head>
 <body>
  <div id="header">
<?php
if (!empty($GLOBALS['TEMPLATE']['title']))
{
    echo $GLOBALS['TEMPLATE']['title'];
}
?>
  </div>
  <div id="content">
<?php
if (!empty($GLOBALS['TEMPLATE']['content']))
{
    echo $GLOBALS['TEMPLATE']['content'];
}
?>
  </div>
  <div id="footer">Copyright &copy;<?php echo date('Y'); ?></div>
   <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="http://lib.sinaapp.com/js/jquery/2.0.3/jquery-2.0.3.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="http://lib.sinaapp.com/js/bootstrap/v3.0.0/js/bootstrap.min.js"></script>
 </body>
</html>
