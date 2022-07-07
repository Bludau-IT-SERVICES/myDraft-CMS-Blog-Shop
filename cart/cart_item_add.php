<?php 
	session_start();
	require_once('../include/inc_config-data.php');
	require_once('../include/inc_basic-functions.php');
	
	##############################################
	# >> Warenkorb updaten
	# 
	##############################################
	function setCartNewItem($dataPost) {
		#echo $dataPost['shop_item_id'];

		$strAry = explode("|",$_SESSION['shop_cart_ids']);
		$_SESSION['shop_cart_ids'] = '';
		$bAdded = false;
		
		for($i=0; $i < count($strAry) -1; $i++) {
			
			$strItemDetailAry = explode("-",$strAry[$i]);
			
			if($strItemDetailAry[0] == $dataPost['shop_item_id']) {
				if(count($strItemDetailAry) > 3) {
					if(count($strItemDetailAry) > 4) {
						$_SESSION['shop_cart_ids'] .= $dataPost['shop_item_id'].'-'.($dataPost['shop_item_count'] +$strItemDetailAry[1]).'-'.$dataPost['shop_item_price'].'-'.$strItemDetailAry[3].'-'.$strItemDetailAry[4].'|';	
					} else {
						$_SESSION['shop_cart_ids'] .= $dataPost['shop_item_id'].'-'.($dataPost['shop_item_count'] +$strItemDetailAry[1]).'-'.$dataPost['shop_item_price'].'-'.$strItemDetailAry[3].'|';	
					}
				} else {
					$_SESSION['shop_cart_ids'] .= $dataPost['shop_item_id'].'-'.($dataPost['shop_item_count'] +$strItemDetailAry[1]).'-'.$dataPost['shop_item_price'].'|';	
				}
				$bAdded = true;
			} else {
				if(count($strItemDetailAry) > 3) {
					if(count($strItemDetailAry) > 4) {
						$_SESSION['shop_cart_ids'] .= $strItemDetailAry[0].'-'.$strItemDetailAry[1].'-'.$strItemDetailAry[2].'-'.$strItemDetailAry[3].'-'.$strItemDetailAry[4].'|';
					} else {						
						$_SESSION['shop_cart_ids'] .= $strItemDetailAry[0].'-'.$strItemDetailAry[1].'-'.$strItemDetailAry[2].'-'.$strItemDetailAry[3].'|';
					}
				} else {
					$_SESSION['shop_cart_ids'] .= $strItemDetailAry[0].'-'.$strItemDetailAry[1].'-'.$strItemDetailAry[2].'|';
				}
					
			}
		}
		if($bAdded == false) {
			$_SESSION['shop_cart_ids'] .= $dataPost['shop_item_id'].'-'.$dataPost['shop_item_count'].'-'.$dataPost['shop_item_price'].'|';
		}
		
		$strAry = explode("|",$_SESSION['shop_cart_ids']);
		$shop_artikel_anzahl = 0;
		$shop_artikel_preis = 0.0;
		
		// Alle Artikel durchlaufen
		for($i=0; $i < count($strAry) -1; $i++) {
			#$shop_artikel_anzahl++;
			
			// Artikel Details holen 
			$strItemDetailAry = explode("-",$strAry[$i]);			
			$shop_artikel_anzahl += $strItemDetailAry[1];
			$shop_artikel_preis += (str_replace(",",".",$strItemDetailAry[2]) * $strItemDetailAry[1]);
			
			
		}
		$query = "SELECT * from domains WHERE domain_id='".$_SESSION['domain_id']."'";			
		$resDomainData = DBi::$conn->query($query) or die(mysqli_error());
		$domain_pages = mysqli_fetch_assoc($resDomainData);
			
		$pathWarenkorb = getPathUrl($_SESSION['language'],$domain_pages['warenkorb_id']);
		$pathZurKasse = getPathUrl($_SESSION['language'],$domain_pages['zurkasse_id']);
		if($domain_pages['isRestaurant'] == 'Y') {
				$html =  '<span class="spanlink" onclick="javascript:mini_warenkorb_frame()"><div class="overlap"><img src="/templates/mekong/images/warenkorb_symbol.png"/></div></span>
				
				<span class="spanlink" onclick="javascript:mini_zur_kasse_frame(\'OK\')"><div class="overlap_right"><img src="/templates/mekong/images/bestellen_symbol.png"/></div></span><div id="frame_detail_info"></div><span class="spanlink_frame" onclick="javascript:mini_zur_kasse_frame(\'OK\')">'.$shop_artikel_anzahl.' Gericht(e) f&uuml;r '.number_format($shop_artikel_preis, 2, ',', '.').' &euro; im Warenkorb (anklicken)</span>';
			
		} else {
			$html = '<a href="'.$pathWarenkorb.'">'.$shop_artikel_anzahl.' Artikel f&uuml;r '.number_format($shop_artikel_preis, 2, ',', '.').' &euro; im Warenkorb</a> | <a href="'.$pathZurKasse.'">Zur Kasse</a>';
		}
			
		#$html = '<a href="'.$pathWarenkorb.'">'.$shop_artikel_anzahl.' Produkte f&uuml;r '.str_replace(".",",",$shop_artikel_preis).' EUR im Warenkorb</a> | <a href="'.$pathZurKasse.'">Zur Kasse</a>';
		$_SESSION['shop_cart_ids_eiso'] = $_SESSION['shop_cart_ids'];
		return $html;
	}
	echo setCartNewItem($_POST);
	
 ?>