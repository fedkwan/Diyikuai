<?php

	/*
		本页要完成的功能有：
			1：将数据库中买家提交的宝贝数据删除
	*/

	session_start();
	header('Content-Type: text/html; charset=UTF-8');
	include 'dump.php';
	include 'function.php';

	#获取UID
	$uid = $_SESSION['buyer_uid'];
	
	#获取$num_iid
	$num_iid = $_GET['id'];

	#更新数据库
	$con = connectMysql();
	mysql_query("DELETE FROM records WHERE `buyer` = '$uid' and `id` = '$num_iid'");
	mysql_close($con);

	#提示提交完成
	echo "<script>/*alert('删除成功，请等待买家发布宝贝！');*/ window.location.href = 'index.php'</script>";
	
?>