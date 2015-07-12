<?php

	/*
		本页要完成的功能有：
			1：卖家授权，获取SESSION_KEY
			2：卖家获取数据库中同类别的宝贝
			3：卖家一键在自己店铺里发布宝贝，并更新到数据库
			4：对以上2、3的交互操作
	*/

	session_start();
	header('Content-Type: text/html; charset=UTF-8');
	include 'dump.php';
	include 'function.php';
	
	#获取sellerSession
	sellerCheckSession();
	#dump($_SESSION);
	
	#卖家UID
	$uid = (string)getUid("seller");
	$_SESSION['seller_uid'] = $uid;
	
	#卖家店铺分类CID、中文分类名
	$cid = getCid($uid);
	$shop_cat = getShopcats($cid);

	$con = connectMysql();
	$result = mysql_query("SELECT * FROM records where `status` = 0 and `cid` = '$shop_cat';");
	$result2 = mysql_query("SELECT * FROM records where `status` = 1 and `seller` = '$uid';");

?>

<!DOCTYPE HTML>
<html>
<head>
	<META http-equiv=Content-Type content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="css/orange-button.css" />
	<style type="text/css">
		body { font-family: "微软雅黑";}
		.center { width:982px; height:370x; margin:0 auto; position: absolute; left:50%; top:50%; margin-left:-490px; margin-top:-200px;}
		.searchDiv { width:982px; height:42px; line-height:42px; color:#555; font-size:15px; text-align:center;}
		#item_url { width:840px; height:26px; line-height:26px; text-indent:5px;}
		.footerDiv { width:982px; height:42px; line-height:42px; color:#555; font-size:15px; text-align:center;}
		.headerDiv { width:982px; height:40px; line-height:40px; border:1px solid #CCC; background-color:#CCC; color:#555; font-size:15px;}
		.middleInnerDiv { width:982px; height:40px; line-height:40px; border-left:1px solid #CCC; border-right:1px solid #CCC; border-bottom:1px solid #CCC; color:#555; font-size:13px; clear:both;}
		.iDa a { color:#555; text-decoration:none;}
		.iDa a:hover { color:#999;}
		.innerDiv { height:38px; line-height:38px; float:left; text-align:center; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;}
	</style>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.0/jquery.min.js"></script>
	<script> 
	/*$(document).ready(function () { 
		setInterval("startRequest()",5000); 
	}); 
	function startRequest() 
	{
		var str = '';
		$.getJSON("get.php",function(result){
			$.each(result, function(i, field){
				$("#date").append(field + " ");
			});
		});
	}*/
	</script> 
</head>
<body>
<div class="center">
	<div class="footerDiv">
		<div class="innerDiv" style="width:780px;"></div>
		<div class="innerDiv" style="width:200px;">今日剩余下单机会：<span style="color:#F00;"><?php echo 3;?></span>次</div>
	</div>
	<?php
		if(mysql_num_rows($result) > 0 || mysql_num_rows($result2) > 0)
		{
			echo '<div class="headerDiv">
				<div class="innerDiv" style="width:430px; padding:0 10px;">宝贝名称</div>
				<div class="innerDiv" style="width:80px;">宝贝价格</div>
				<div class="innerDiv" style="width:180px;">宝贝分类</div>
				<div class="innerDiv" style="width:150px;">创建时间</div>
				<div class="innerDiv" style="width:100px;">操作</div>
				</div>';
		}
		while($row = mysql_fetch_array($result2))
		{
			echo '<div class="middleInnerDiv">';
            echo '<div class="innerDiv iDa" style="width:430px; padding:0 10px;"><a href="http://item.taobao.com/item.htm?id='.$row['newnumiid'].'" target="_blank">'.$row['title'].'</a></div>';
            echo '<div class="innerDiv" style="width:80px">'.$row['price'].'</div>';
            echo '<div class="innerDiv" style="width:180px">'.$row['cid'].'</div>';
            echo '<div class="innerDiv" style="width:150px">'.date("m月d日H点i分", $row['timestamp']).'</div>';
			echo '<div class="innerDiv" style="width:100px"><a class="button blue small" href="seller_create.php?num_iid='.$row['numiid'].'" target="_self">拍下宝贝</a></div>';
			echo '</div>';
		}
		while($row = mysql_fetch_array($result))
		{
			echo '<div class="middleInnerDiv">';
            echo '<div class="innerDiv iDa" style="width:430px; padding:0 10px;"><a href="http://item.taobao.com/item.htm?id='.$row['numiid'].'" target="_blank">'.$row['title'].'</a></div>';
            echo '<div class="innerDiv" style="width:80px">'.$row['price'].'</div>';
            echo '<div class="innerDiv" style="width:180px">'.$row['cid'].'</div>';
            echo '<div class="innerDiv" style="width:150px">'.date("m月d日H点i分", $row['timestamp']).'</div>';
			echo '<div class="innerDiv" style="width:100px"><a class="button orange small" href="seller_create.php?num_iid='.$row['numiid'].'" target="_self">发布宝贝</a></div>';
			echo '</div>';
		}
		mysql_close($con);
	?>
</div>
</body>
</html>










