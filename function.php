<?php

	session_start();
	header('Content-Type: text/html; charset=UTF-8');
	require("topsdk/TopClient.php");
	require("topsdk/RequestCheckUtil.php");
	require("topsdk/request/UserBuyerGetRequest.php");
	require("topsdk/request/UserSellerGetRequest.php");
	require("topsdk/request/ShopGetRequest.php");
	require("topsdk/request/ShopcatsListGetRequest.php");
	require("topsdk/request/ItemGetRequest.php");
	require("topsdk/request/ItemcatsGetRequest.php");
	require("topsdk/request/ItemAddRequest.php");
	require("topsdk/request/PictureUploadRequest.php");
	#include 'dump.php';

	function connectMysql()
	{
		$con = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS); 
		if (!$con){die('Could not connect: ' . mysql_error());}
		mysql_select_db("app_diyikuai", $con);
		return $con;
	}
	
	function buyerCheckSession()
	{
		if(!isset($_SESSION['buyerSessionKey']))
		{
			echo "<script>window.location.href='http://container.open.taobao.com/container?appkey=21622131'</script>";
			exit;
		}
	}

	function sellerCheckSession()
	{	
		if(!isset($_SESSION['sellerSessionKey']))
		{
			echo "<script>window.location.href='http://container.open.taobao.com/container?appkey=21619497'</script>";
			exit;
		}
	}
	
	function getUid($type)
	{
		if($type == "buyer")
		{
			$appkey = 21622131;
			$secret = "4a6ca589bdc65a729c1fce1550cdea0e";
			$c = new TopClient;
			$c->appkey = $appkey;
			$c->secretKey = $secret;
			$req = new UserBuyerGetRequest;
			$req->setFields("nick");
			$resp = $c->execute($req, $_SESSION['buyerSessionKey']);
			return $resp->user->nick;
		}
		else if($type == "seller")
		{
			$appkey = 21619497;
			$secret = "aec014a6a6f42d91c6086834e83cd024";
			$c = new TopClient;
			$c->appkey = $appkey;
			$c->secretKey = $secret;
			$req = new UserSellerGetRequest;
			$req->setFields("nick");
			$resp = $c->execute($req, $_SESSION['sellerSessionKey']);
			return $resp->user->nick;
		}
	}
	
	function getCid($uid)
	{
		$appkey = 21622131;
		$secret = "4a6ca589bdc65a729c1fce1550cdea0e";
		$c = new TopClient;
		$c->appkey = $appkey;
		$c->secretKey = $secret;
		$req = new ShopGetRequest;
		$req->setFields("cid");
		$req->setNick($uid);
		$resp = $c->execute($req);
		return $resp->shop->cid;
	}
	
	function getShopcats($cid)
	{
		$con = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS); 
		if (!$con){die('Could not connect: ' . mysql_error());}
		mysql_select_db("app_diyikuai", $con);
		
		$result = mysql_query("SELECT * FROM shopcats where `cid` = '$cid';");
		while($row = mysql_fetch_array($result))
		{
			$name = $row['name'];
		}
		mysql_close($con);
		return $name;

		#鄙视淘宝两套标准
		/*
		$appkey = 21622131;
		$secret = "4a6ca589bdc65a729c1fce1550cdea0e";
		$c = new TopClient;
		$c->appkey = $appkey;
		$c->secretKey = $secret;
		$req = new ShopcatsListGetRequest;
		$req->setFields("cid, name");
		$resp = $c->execute($req);
		$shop_cats = $resp->shop_cats->shop_cat;
		
		dump($resp);
		
		#返回的数组
		$result = array();
		foreach ($shop_cats as $k=>$v)
		{
			$key = (string)$v->cid;
			$value = (string)$v->name;
			$result[$key] = $value;
		}
		ksort($result);
		return $result;*/
	}
	
	function getItemcats($iid = "19985581781")
	{
		$appkey = 21622131;
		$secret = "4a6ca589bdc65a729c1fce1550cdea0e";
		$c = new TopClient;
		$c->appkey = $appkey;
		$c->secretKey = $secret;	
		$req = new ItemGetRequest;
		$req->setFields("cid, title, price, pic_url");
		$req->setNumIid($iid);
		$resp = $c->execute($req, $_SESSION['buyerSessionKey']);
		$cid = $resp->item->cid;
		
		do
		{
			$req2 = new ItemcatsGetRequest;
			$req2->setFields("cid,parent_cid,name,is_parent");
			$req2->setCids($cid);
			$resp2 = $c->execute($req2);
			$cid = $resp2->item_cats->item_cat->parent_cid;
		} while($cid != 0);
		$cid_name = $resp2->item_cats->item_cat->name;

		$result = array();
		$result['cid_name'] = (string)$cid_name;
		$result['title'] = (string)$resp->item->title;
		$result['pic_url'] = (string)$resp->item->pic_url;
		$result['price'] = (string)$resp->item->price;
		
		return $result;
	}
?>