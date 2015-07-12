<?php

	/*
		本页要完成的功能有：
			1：选取数据库中当前买家提交并被卖家发布到卖家店铺的宝贝，并以JSON展示
	*/
	
	session_start();
	header('Content-Type: text/html; charset=UTF-8');
	include 'dump.php';
	include 'function.php';

	#买家UID
	$uid = $_SESSION['buyer_uid'];

	$con = connectMysql();
	$result = mysql_query("SELECT * FROM records where `buyer` = '$uid' and `status` = 2;");
	$count = mysql_num_rows($result);
	//mysql_query("UPDATE records set `status` = 1 where `buyer` = '$uid' and `status` = 2;");
	echo json_encode($count);
	
?>