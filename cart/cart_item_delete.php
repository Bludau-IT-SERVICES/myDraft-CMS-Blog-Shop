<?php
	session_start();
	
	$strAry = explode("|",$_SESSION['shop_cart_ids']);
	for($i=0; $i < count($strAry) -1; $i++) {
		$strItemDetailAry = explode("-",$strAry[$i]);
		if($strItemDetailAry[0] == $_GET['shop_item_id']) {
		
		} else {
			$strCart .= $strAry[$i].'|';
		}
	}
	#echo $strCart;
	$_SESSION['shop_cart_ids'] = $strCart;
?>