<?php

	session_start();
	header('Content-Type: text/html; charset=UTF-8');
	include 'dump.php';
	include 'uid.php';
	
	if(!isset($_SESSION['sellerSessionKey']))
	{
		echo "<script>window.location.href='http://container.open.taobao.com/container?appkey=21619497'</script>";
		exit;
	}
	else
	{
		dump(getUid("seller"));
	/*
		$con = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS); 
		if (!$con)
		{
			die('Could not connect: ' . mysql_error());
		}
		$sessionKey = $_SESSION['sessionKey'];
		mysql_select_db("app_diyikuai", $con);
		$result = mysql_query("SELECT * FROM records where `buy` = '$sessionKey';");
		while($row = mysql_fetch_array($result))
		{
			dump($row);
		}
		mysql_close($con);
	*/
	}
?>

<!DOCTYPE HTML>
<html>
<head>
	<META http-equiv=Content-Type content="text/html; charset=utf-8">
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.0/jquery.min.js"></script>
	<script> 
	$(document).ready(function () { 
	}); 
	</script> 
</head>
<div id="date"></div>
</html>