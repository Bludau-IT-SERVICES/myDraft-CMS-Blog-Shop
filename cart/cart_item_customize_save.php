<?php
	session_start();

#print_r($_SESSION);

// Attribute abspeichern
$txtBemerkung ='';
$strExtraData = '';
if($_POST['hasAdditionalValues'] == true) {
	foreach ($_POST as $key => $value) {
		#echo strpos("additional_",$key).'<br/>';
		
		if(strpos($key,"additional_") === false) {
		} else {
			#$query ="INSERT INTO shop_item_additional(shop_item_id,shop_item_additional_types_id,domain_id) VALUES('".$_POST['shop_id']."','".$value."','".$_SESSION['domain_id']."')";		
			#echo $query;
			#DBi::$conn->query($query) or die(mysqli_error());
			if(strpos($key,"additional_opt_bemerkung") === false) {
				$strExtraData .= $value.'/';				
			} else {
				$txtBemerkung .= $value;
			}
		}
	}
}

if(strlen($strExtraData) > 0) {
	$strExtraData = substr($strExtraData,0,(strlen($strExtraData) -1));
}
	# Einzelposition des Warenkorbs holen
	$strAry = explode("|",$_SESSION['shop_cart_ids']);
	
	$strCart = '';
	
	# Alle Positionen durchlaufen
	for($i=0; $i < count($strAry) -1; $i++) {
		
		# Werte aus der Bestellposition auslesen
		$strItemDetailAry = explode("-",$strAry[$i]);
		
		
		# Ist der entsprechende Artikel gefunden?
		if($strItemDetailAry[0] == $_POST['shop_item_id']) {
			
			$micro_date = microtime();
			echo $micro_date;
			$date_array = explode(" ",$micro_date);
			# Extra Werte in den Warenkorb speichern
			if(!empty($_POST['item_cart_change_custom_options_amount'])) {
			
				if($strItemDetailAry[1] != $_POST['item_cart_change_custom_options_amount']) {

				# date("Y-m-d H:i:s",$date_array[1])
					$strCart .= $strItemDetailAry[0].'_'.md5(date("H:i:s").$micro_date).'-'.$_POST['item_cart_change_custom_options_amount'].'-'.$strItemDetailAry[2].'-'.$strExtraData.'-'.$txtBemerkung.'|';	
					
					#for($zLoop = (int)$_POST['item_cart_change_custom_options_amount']; $zLoop < $strItemDetailAry[1]; $zLoop++) {
						$micro_date = microtime();
						#$date_array = explode(" ",$micro_date);
							#$strItemDetailAry[1]
					# mit for
					#	$strCart .= $strItemDetailAry[0].'_'.md5($micro_date).'-1-'.$strItemDetailAry[2].'|';						# mit id md5					
						#$strCart .= $strItemDetailAry[0].'_'.md5($micro_date).'-'.($strItemDetailAry[1] - $_POST['item_cart_change_custom_options_amount']).'-'.$strItemDetailAry[2].'|';											
						$strCart .= $strItemDetailAry[0].'-'.($strItemDetailAry[1] - $_POST['item_cart_change_custom_options_amount']).'-'.$strItemDetailAry[2].'|';											
					#}
					
				} else {
					$strCart .= $strItemDetailAry[0].'-'.$strItemDetailAry[1].'-'.$strItemDetailAry[2].'-'.$strExtraData.'-'.$txtBemerkung.'|';											
				}
			} else {				
				$strCart .= $strItemDetailAry[0].'-'.$strItemDetailAry[1].'-'.$strItemDetailAry[2].'-'.$strExtraData.'-'.$txtBemerkung.'|';
			}
		} else {
			# Alle Werte zum Gesamten Warenkorb anhängen
			$strCart .= $strAry[$i].'|';
		}
	}
	#echo $_POST['shop_item_id'].'---'.$strCart;
	#print_r($_SESSION);
	$_SESSION['shop_cart_ids'] = $strCart;
?>