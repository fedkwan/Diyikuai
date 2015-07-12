<?php

	$con = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS); 
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db("app_diyikuai", $con);
	
	#直接删除缓存区订单
	mysql_query("DELETE FROM records WHERE `status` = 2;");
	/*$result = mysql_query("SELECT * FROM records WHERE `status` = '2';");
	while($row = mysql_fetch_array($result))
	{
		$id = $row['id'];
		mysql_query("DELETE FROM records WHERE `id` = '$id'");
	}*/
	
	#将冗余订单放入缓存区
	$time = time();
	$result2 = mysql_query("SELECT * FROM records WHERE `status` = 0;");
	while($row2 = mysql_fetch_array($result2))
	{
		$id2 = $row2['id'];
		$time2 = $row2['timestamp'];
		if($time - $time2 > 1200)
		{
			mysql_query("UPDATE records SET `status` = 2 WHERE `id` = '$id2'");
		}
	}	
	
	mysql_close($con);
	
?>