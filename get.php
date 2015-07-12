<?php
	
	session_start();
	$sessionKey = $_SESSION['sessionKey'];

	$con = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS); 
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db("app_diyikuai", $con);
	
	#
	$result = mysql_query("SELECT * FROM records WHERE `status` = 1 and `buy` = '$sessionKey';");
	while($row = mysql_fetch_array($result))
	{
		echo json_encode($row);
	}
	
	mysql_close($con);
	
?>