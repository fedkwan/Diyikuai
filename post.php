<?php

	session_start();
	header('Content-Type: text/html; charset=UTF-8');
	include 'dump.php';
	
	#获取POST['ID']
	$id = $_POST['num_iid'];
	$buyer = $_POST['buyer'];
	$timestamp = time();
	if(!isset($id) || strpos($id, '&id=') < 0){echo "<script>window.location.href = 'index.php'</script>"; exit;}else{$num_iid = substr($id, strpos($id, '&id=') + 4, 11) + 0;}
	
	#存进数据库
	$con = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS); 
	if (!$con){die('Could not connect: ' . mysql_error());}
	mysql_select_db("app_diyikuai", $con);
	mysql_query("INSERT INTO records (`buyer`,`numiid`,`timestamp`,`status`) VALUES ('$buyer','$num_iid','$timestamp',0);");
	mysql_close($con);
	
	echo "<script>alert('购物单提交成功，请等待买家发布宝贝！'); window.location.href = 'index.php'</script>";
	
?>