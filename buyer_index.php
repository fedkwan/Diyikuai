<?php

	/*
		本页要完成的功能有：
			1：买家授权，获取SESSION_KEY
			2：买家提交宝贝，存入数据库
			3：选取数据库中当前买家提交成功的宝贝，并展示
			4：对以上2、3的交互操作
	*/
	
	session_start();
	header('Content-Type: text/html; charset=UTF-8');
	include 'dump.php';
	include 'function.php';
	
	#获取 BuyerSession
	buyerCheckSession();
	#dump($_SESSION);
	
	#买家UID
	$uid = (string)getUid("buyer");
	$_SESSION['buyer_uid'] = $uid;
?>

<!DOCTYPE HTML>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/orange-button.css" />
<style type="text/css">
	body { font-family: "微软雅黑";}
	
	.total { width:980px; height:500x; position: absolute; left:50%; top:50%; margin-left:-490px; margin-top:-280px;}

		.logo_div { width:880px; height:100px; margin:0 auto 10px auto; text-align:center;}
			.logo { width:350px; height:100px;}	

		.search_div { width:880px; height:42px; margin:0 auto; line-height:42px; color:#555;}
			.item_url { width:730px; height:26px; margin-left:10px; line-height:26px; text-indent:5px; margin-right:10px;}

		.tips_div { width:880px; height:42px; margin:0 auto; line-height:42px; color:#555; font-size:12px;}
			.tips_inner_div { height:42px; line-height:42px; float:left; text-align:center;}
		
		.items_div { width: 880px; height:290px; margin:0 auto;}
			.item { width:180px; height:280px; border:5px solid #EEE; margin:0 5px; padding:8px; -webkit-border-radius: 10px; float:left; text-align:center;}
			.item_pic_div { width:180px; height:180px;}
				.item_pic { width:180px; height:180px;}
			.item_title_div { width:170px; height:32px; line-height:16px; margin:10px auto; font-family:"新宋体"; font-size:12px; overflow: hidden; text-overflow: ellipsis;}
				.item_title_div a { text-decoration:none; color:#666;}
				.item_title_div a:hover { color:#999;}
			.item_price_div { width:170px; height:32px; line-height:16px; font-size:12px; margin:0px 5px 11px 5px;}
				.item_price_gray { width:80px; height:16px; margin:0 auto; color:#999; text-decoration:line-through;}
				.item_price_red { width:80px; height:16px; margin:0 auto; color:#F00; font-weight:bold;}
			.item_control_div { width:120px; height:30px; line-height:30px; margin:0 auto; cursor:pointer;}

		.desktop_pic { display:block; position:fixed; bottom:10px; right:8px; width:365px; height:85;}
</style>
	<title>低一块，淘宝买卖，买什么都给你低一块！</title>
</head>
<body>
	<a href="seller_index.php" target="_blank">seller.php</a>
	<img class="desktop_pic" src="desktop.png" width="364" height="84" style="display:none;" onclick="init()">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
	<script type='text/javascript'>
		function init() { 
			if (window.webkitNotifications) { 
				window.webkitNotifications.requestPermission();
				$(".desktop_pic").hide();
			} 
		} 

		function pop() {
			$.get("buyer_check_new.php", function(result){
				if(result > 0){
					var notification_test = window.webkitNotifications.createNotification("http://images.cnblogs.com/cnblogs_com/flyingzl/268702/r_1.jpg", '卖家给你优惠，发布了新宝贝！', '这样就省了一块，赶紧点进来看看吧！');
					notification_test.display = function() {}
					notification_test.onerror = function() {}
					notification_test.onclose = function() {}
					notification_test.onclick = function() {this.cancel();}
					//notification_test.onclick = function() {window.location.href = "http://diyikuai.sinaapp.com/";}
					notification_test.replaceId = 'Meteoric';
					notification_test.show();
				}
			});
		}

		$(document).ready(function(){
			if (window.webkitNotifications) {
				if (window.webkitNotifications.checkPermission() == 0) {
					var int = self.setInterval("pop()", 300000);
				}
				else
				{
					//显示桌面通知允许tips
					$(".desktop_pic").show();
				}
			}
		});
	</script>
    <div class="total">
        <div class="logo_div">
            <img src="logo.png" class="logo">
        </div>
        <div class="search_div">
            <form action="buyer_create.php" method="post">
                <input type="text" name="item_url" id="item_url" class="item_url" autofocus="autofocus" placeholder="填写宝贝地址，按 Enter 创建订单！" />
                <input type="submit" name="submit" id="submit" class="button orange small" style="font-size:15px;" value="创建订单" />
            </form>
        </div>
        
        <div class="tips_div">
            <div class="tips_inner_div" style="width:680px;"></div>
            <div class="tips_inner_div" style="width:200px;">今日剩余下单机会：<span style="color:#F00;"><?php echo 3;?></span>次</div>
        </div>
        
        <div class="items_div">
            <?php
			
				#链接数据库取出相应的买家下过的订单
				$con = connectMysql();
				$result = mysql_query("SELECT * FROM records where `buyer` = '$uid';");

                while($row = mysql_fetch_array($result))
                {
                    echo '<div class="item">';
                    
                    echo '<div class="item_pic_div">
                        <a href="http://item.taobao.com/item.htm?id='.$row['numiid'].'" target="_blank">
                            <img src="'.$row['pic'].'_180x180.jpg" class="item_pic">
                        </a>
                    </div>';
                    
                    echo '<div class="item_title_div">
                        <a href="http://item.taobao.com/item.htm?id='.$row['numiid'].'" target="_blank">'.$row['title'].'</a>
                    </div>';
                    
                    echo '<div class="item_price_div">
                              <span class="item_price_gray">原价：¥'.sprintf("%01.2f",$row['price']).'</span><br>
                              <span class="item_price_red">现价：¥'.sprintf("%01.2f",($row['price'] - 1)).'</span>
                          </div>';
    
                    if(strlen($row['newnumiid']) > 0)
                    {
                        echo '<div class="item_control_div">
                            <a title="你要拍下，卖家才知道你想要什么噢！" class="button orange small" href="http://item.taobao.com/item.htm?id='.$row['newnumiid'].'" target="_blank">
                            拍下新宝贝</a>
                        </div>';
                    }
                    else
                    {
                        echo '<div class="item_control_div">
                            <a class="clean-gray" href="buyer_cancel.php?id='.$row['id'].'" target="_self">
                            取消订单</a>
                        </div>';
                    }
                    echo '</div>';
                }
                mysql_close($con);
            ?>
        </div>
        
    </div>
</body>
</html>























