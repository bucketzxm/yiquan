<html>
	<body>
		<?php
    	$text = $_POST['text'];
    	$fp=fopen("1.html","w");
    		fwrite($fp,'<html xmlns=http://www.w3.org/1999/xhtml><head><meta http-equiv=Content-Type content="text/html;charset=utf-8"><link href="http://7xid8v.com2.z0.glb.qiniucdn.com/style.css" rel="stylesheet"></head><body>');
            fwrite($fp,$text);
            fwrite($fp,'</body></html>');
            fclose($fp);
		?>
	</body>
</html>