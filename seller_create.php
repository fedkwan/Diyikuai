<?php
	
	session_start();
	header('Content-Type: text/html; charset=UTF-8');

	$appkey  = 21619497;
	$secret = "aec014a6a6f42d91c6086834e83cd024";

	#获取商品信息	
	$c = new TopClient;
	$c->appkey = $appkey;
	$c->secretKey = $secret;
	$req = new ItemGetRequest;
	$req->setFields("approve_status,auction_point,cid,desc,ems_fee,express_fee,freight_payer,has_discount,has_invoice,has_showcase,has_warranty,increment,input_pids,input_str,is_virtual,item_img,list_time,location,modified,nick,num,num_iid,outer_id,pic_url,post_fee,postage_id,price,product_id,prop_img,property_alias,props,seller_cids,sku,stuff_status,title,type,valid_thru,video");
	$req->setNumIid($num_iid);
	$resp = $c->execute($req, $sessionKey);
	
	#保存主图
	$master_image_url = $resp->item->item_imgs->item_img->url;
	$master_image_name = rand().substr($master_image_url, -4, 4);
	$master_image_content = file_get_contents($master_image_url);
	file_put_contents( SAE_TMP_PATH.$master_image_name, $master_image_content);
	
	#替换并保存商品描述的图片
	preg_match_all('/<img(.*?)src="(.*?)">/i', $resp->item->desc, $desc_image_array);
	
	$desc_image_sae_array = array();
	foreach($desc_image_array[2] as $desc_image_url)
	{
		#保存描述图片
		$desc_image_name = rand().substr($desc_image_url, -4, 4);
		$desc_image_content = file_get_contents($desc_image_url);
		file_put_contents( SAE_TMP_PATH.$desc_image_name, $desc_image_content);
		
		#放到自己的淘宝相册
		$req2 = new PictureUploadRequest;
		$req2->setPictureCategoryId(0);
		$req2->setImageInputTitle($desc_image_name);
		$req2->setImg("@".SAE_TMP_PATH.$desc_image_name);
		$resp2 = $c->execute($req2, $sessionKey);

		#替换
		$resp->item->desc = str_replace($desc_image_url, $resp2->picture->picture_path, $resp->item->desc);
	}

	#组装数据，上传宝贝
	$req3 = new ItemAddRequest;
	$req3->setApproveStatus($resp->item->approve_status);
	$req3->setAuctionPoint($resp->item->auction_point);
	$req3->setCid($resp->item->cid + 0);
	#$req3->setEmsFee($resp->item->ems_fee + 0);
	$req3->setEmsFee(10.00);
	#$req3->setExpressFee($resp->item->express_fee + 0);
	$req3->setExpressFee(10.00);
	$req3->setFreightPayer($resp->item->freight_payer);
	$req3->setHasInvoice($resp->item->has_invoice);
	$req3->setHasWarranty($resp->item->has_warranty);
	$req3->setHasShowcase($resp->item->has_showcase);
	$req3->setHasDiscount($resp->item->has_discount);
	$req3->setInputPids($resp->item->input_pids);
	$req3->setInputStr($resp->item->input_str);
	$req3->setLocationState($resp->item->location->state);
	$req3->setLocationCity($resp->item->location->city);
	$req3->setNum($resp->item->num + 0);
	#$req3->setPostFee($resp->item->post_fee + 0);
	$req3->setPostFee(10.00);
	$req3->setPrice($resp->item->price + 0);
	$req3->setPropertyAlias($resp->item->property_alias);
	$req3->setProps($resp->item->props);
	$req3->setStuffStatus($resp->item->stuff_status);
	$req3->setTitle($resp->item->title);
	$req3->setType($resp->item->type);
	
	$sku = $resp->item->skus->sku;
	$p = array(); $q = array(); $pr = array(); $sk = array(); $so = array();
	foreach ($sku as $s)
	{
		$p[] = $s->properties;
		$q[] = $s->quantity;
		$pr[] = $s->price;
		$sk[] = $s->sku_id;
		$so[] = time() + rand();
	}
	
	$req3->setSkuProperties(implode(",", $p));
	$req3->setSkuQuantities(implode(",", $q));
	$req3->setSkuPrices(implode(",", $pr));
	$req3->setSkuOuterIds(implode(",", $so));
	$req3->setSkuSpecIds(implode(",", $sk));
	
	#复杂的几块最后放
	$req3->setDesc($resp->item->desc);
	$req3->setImage("@".SAE_TMP_PATH.$master_image_name);
	$resp3 = $c->execute($req3, $sessionKey);
	
	dump($resp3);
	
	$newnumiid = (string)$resp3->item->iid;
	if(strlen($newnumiid) > 0)
	{
	#发布宝贝
		$con = connectMysql();
		$result = mysql_query("UPDATE records set `newnumiid` = '$newnumiid', `seller` = '$seller_id', `status` = 2 where `numiid` = '$num_iid';");
		mysql_close($con);
	}
	#提示提交完成
	echo "<script>/*alert('购物单提交成功，请等待买家发布宝贝！');*/ window.location.href = 'seller_index.php'</script>";
?>