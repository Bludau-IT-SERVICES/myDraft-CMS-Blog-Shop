<?php
	session_start();
	
	$strAry = explode("|",$_SESSION['shop_cart_ids']);
	for($i=0; $i < count($strAry) -1; $i++) {
		$strItemDetailAry = explode("-",$strAry[$i]);
		if($strItemDetailAry[0] == $_GET['shop_item_id']) {
			if(count($strItemDetailAry) > 3) {
				if(count($strItemDetailAry) > 4) {
					$strCart .= $strItemDetailAry[0].'-'.$_GET['shop_item_menge'].'-'.$strItemDetailAry[2].'-'.$strItemDetailAry[3].'-'.$strItemDetailAry[3].'|';						
				} else {				
					$strCart .= $strItemDetailAry[0].'-'.$_GET['shop_item_menge'].'-'.$strItemDetailAry[2].'-'.$strItemDetailAry[3].'|';	
				}
			} else {
				$strCart .= $strItemDetailAry[0].'-'.$_GET['shop_item_menge'].'-'.$strItemDetailAry[2].'|';
			}
			
			
			
		} else {
			$strCart .= $strAry[$i].'|';
		}
	}
	#echo $strCart;
	$_SESSION['shop_cart_ids'] = $strCart;
?>