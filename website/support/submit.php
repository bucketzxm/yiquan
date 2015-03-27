<?php

	$counter=1;
	if(file_exists("mycounter.txt")){
		$fp=fopen("mycounter.txt","r");
		$counter=fgets($fp,9);
		$counter++;
		fclose($fp);
	}
	$fp=fopen("mycounter.txt","w");
	fputs($fp,$counter);
	fclose($fp);


	$fp2=fopen("./ticket/".$counter.".txt","w");
	fputs($fp2,$_POST['contact']."\n".$_POST['content']);
	fclose($fp2);
	
?>


<html>
<head>
<title>用户支持</title>
</head>
<body>
<div class="container">
		<div class="title">已提交，我们将尽快联系你</div>
</div>


</body>
</html>