<?php

	session_start();

	if($_GET['top_appkey'] == 21622131)
	{
		$_SESSION['buyerSessionKey'] = $_GET['top_session'];
		echo "<script>window.location.href='index.php'</script>";
	}
	if($_GET['top_appkey'] == 21619497)
	{
		$_SESSION['sellerSessionKey'] = $_GET['top_session'];
		echo "<script>window.location.href='seller_index.php'</script>";
	}
	
?>








