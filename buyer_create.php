<?php

	/*
		本页要完成的功能有：
			1：将买家提交的宝贝数据，存入数据库
	*/

	session_start();
	header('Content-Type: text/html; charset=UTF-8');
	#include 'dump.php';
	include 'function.php';

	#获取UID
	$uid = $_SESSION['buyer_uid'];
	
	#获取POST['ID'] => $num_iid
	$item_url = $_POST['item_url'];
	if(!strpos($item_url, 'id=')){echo "<script>alert('错误：提交的地址并不包含宝贝ID！请检查！'); window.location.href = 'index.php'</script>"; exit;}else{$num_iid = substr($item_url, strpos($item_url, 'id=') + 3, 11) + 0;}

	#已经提交了多少订单
	$con = connectMysql();
	$result = mysql_query("SELECT * FROM records where `buyer` = '$uid';");
	if(mysql_num_rows($result) >= 4)
	{
		echo "<script>/*alert('每位买家最多只能同时发布8个订单，请耐心等候或取消已提交的订单！');*/ window.location.href = 'index.php'</script>";
		exit;
	}
	
	#获取宝贝信息
	$getItemData = getItemcats($num_iid);
		#宝贝分类
		$cid = $getItemData['cid_name'];
		#宝贝标题
		$title = $getItemData['title'];
		#宝贝主图
		$pic = (string)$getItemData['pic_url'];
		#宝贝价格
		$price = (string)$getItemData['price'];

	#时间戳
	$timestamp = time();
	
	#存进数据库
	$sql = "INSERT INTO records (`buyer`, `numiid`, `title`, `pic`, `price`, `cid`, `timestamp`, `status`) VALUES ('$uid', '$num_iid', '$title', '$pic', '$price', '$cid', '$timestamp', 0);";
	mysql_query($sql);
	mysql_close($con);

	#提示提交完成
	echo "<script>/*alert('购物单提交成功，请等待买家发布宝贝！');*/ window.location.href = 'index.php'</script>";
	
?>