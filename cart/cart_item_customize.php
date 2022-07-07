<?php 
	session_start();
	include_once('../include/inc_config-data.php');
	
	$query ="SELECT * FROM shop_item WHERE domain_id='".$_SESSION['domain_id']."' and shop_item_id='".mysql_real_escape_string($_POST['item_id'])."'";
	$resAtr = DBi::$conn->query($query) or die(mysqli_error());
	$dataShop = mysqli_fetch_assoc($resAtr);
?>

<div id="dialog-shop-item-customize_<?php echo $_POST['item_id'] ?>" title="<?php echo $dataShop['name_de'] ?>">
<?php 
#print_r($_SESSION);
?>
<form name="frmShopItemCustomize" id="shop_item_custom_<?php echo $_POST['item_id'] ?>" action="/cart/cart_item_customize_save.php" method="POST" onSubmit="return shop_item_save_customize('shop_item_custom_<?php echo $_POST['item_id'] ?>');">
<?php 
	echo 'Passen Sie das Gericht nach Ihrem Geschmack an.<br/><br/>';
	echo '<img src="/templates/mekong/media/Schwarzer-Pfeffer_kl2.png" style="margin-right:10px"/> <img src="/templates/mekong/media/redchillipepper_kl2.png" style="margin-right:10px"/> 
	<img src="/templates/mekong/media/Knoblauch_kl.png"/><br/>'; 
	
	$strAry = explode("|",$_SESSION['shop_cart_ids']);
		
	$strCart = '';
	$strMenge = '';
			# Alle Positionen durchlaufen
	$strMenge = '';
	for($i=0; $i < count($strAry) -1; $i++) {
			
		# Werte aus der Bestellposition auslesen
		$strItemDetailAry = explode("-",$strAry[$i]);
		
		# Ist der entsprechende Artikel gefunden?
		if($strItemDetailAry[0] == $_POST['item_id']) {
			if(empty($strMenge)) {
				$strMenge =  $strItemDetailAry[1];
			}
		}
	}
	
	if($strMenge > 1) {
		echo "Wieviele von den ".$strMenge." Gerichten möchten Sie anpassen?";
		echo ' <input size="2" value="1" type="text" name="item_cart_change_custom_options_amount"><br/><br/>';
	}
$query ="SELECT * FROM shop_item_additional_types WHERE domain_id='".$_SESSION['domain_id']."' AND cart_changeable='Y' GROUP BY typ";
$resAtr = DBi::$conn->query($query) or die(mysqli_error());
$bGefunden = false;
$iCount =0;
while($strAtr = mysqli_fetch_assoc($resAtr)) {
	$bGefunden = true;
	$html_atr .= '<strong>'.$strAtr['typ_titel'].'</strong><br/>';

	switch($strAtr['html_typ']) {
		case 'selectbox':
			$html_atr .= '<select name="additional_opt_'.$strAtr['typ'].'">';
			break;
		case 'radio':
			# Kein init
			break;
	}	
 
	$query = "SELECT * FROM shop_item_additional_types WHERE typ='".$strAtr['typ']."' ORDER BY isDefault ASC";
	$resAtrList =  DBi::$conn->query($query) or die(mysqli_error());
	$iCount =0;
	while($strAtrList = mysqli_fetch_assoc($resAtrList)) {
 
		# Einzelposition des Warenkorbs holen
		$strAry = explode("|",$_SESSION['shop_cart_ids']);
		
		$strCart = '';
		$strMenge = '';
		
		# Alle Positionen durchlaufen
		for($i=0; $i < count($strAry) -1; $i++) {
			
			# Werte aus der Bestellposition auslesen
			$strItemDetailAry = explode("-",$strAry[$i]);
			
			# Ist der entsprechende Artikel gefunden?
			if($strItemDetailAry[0] == $_POST['item_id']) {
				# Extra Werte in den Warenkorb speichern
				
				if(count($strItemDetailAry) > 3) {
					$strSelectedValue .= $strItemDetailAry[3];
					$strSelectedValueAry = explode("/",$strSelectedValue);
					if(count($strItemDetailAry) > 4) {
						$strBemerkung = $strItemDetailAry[4];
					}
				}
			}  
		}
		#print_r($strSelectedValueAry).'<br/>';

		
		if(count($strSelectedValueAry) > 0){
			
			switch($strAtrList['html_typ']) {
				case 'selectbox':
					$bIsSelected = false;
					for($zLoop =0; $zLoop < count($strSelectedValueAry); $zLoop++) {
						if($strAtrList['shop_item_additional_types_id'] == $strSelectedValueAry[$zLoop]) {
							$html_atr .= '<option value="'.$strAtrList['shop_item_additional_types_id'].'" selected>'.$strAtrList['typ'].' - '.$strAtrList['value'].'</option>';
							$bIsSelected = true;
						} 
					}
					if($bIsSelected == false) {
						$html_atr .= '<option value="'.$strAtrList['shop_item_additional_types_id'].'">'.$strAtrList['typ'].' - '.$strAtrList['value'].'</option>';
					}
					break;
				case 'radio':
					$bIsSelected = false;
					for($zLoop =0; $zLoop < count($strSelectedValueAry); $zLoop++) {
						if($strAtrList['shop_item_additional_types_id'] == $strSelectedValueAry[$zLoop]) {
							$html_atr .= '<label class="warenkorb_label"><input type="radio" name="additional_opt_'.$strAtrList['typ'].'" value="'.$strAtrList['shop_item_additional_types_id'].'" checked>'.$strAtrList['value'].'</label>';
							$bIsSelected = true;
						} 
					}
					if($bIsSelected == false) {
						$html_atr .= '<label class="warenkorb_label"><input type="radio" name="additional_opt_'.$strAtrList['typ'].'" value="'.$strAtrList['shop_item_additional_types_id'].'">'.$strAtrList['value'].'</label>';
					}
					
					#$html_atr_select .= '<input type="hidden" name="old_'.$strAtrList['typ'].'" value="'.$strAtrList['shop_item_additional_types_id'].'"/>';
					break;
				case 'textbox':
					$html_atr .= '<input type="text" name="additional_opt_'.$strAtrList['typ'].'" placeholder="Bemerkung..." value="'.$strBemerkung.'"/>';
					break;
			}
			
		} else {
			switch($strAtrList['html_typ']) {
				case 'selectbox':
					$html_atr .= '<option value="'.$strAtrList['shop_item_additional_types_id'].'">'.$strAtrList['typ'].' - '.$strAtrList['value'].'</option>';
					break;
				case 'radio':
					$html_atr .= '<label class="warenkorb_label"><input type="radio" name="additional_opt_'.$strAtrList['typ'].'" value="'.$strAtrList['shop_item_additional_types_id'].'">'.$strAtrList['value'].'</label>';
					break;
				case 'textbox':
					$html_atr .= '<input type="text"  value="'.$strBemerkung.'" name="additional_opt_'.$strAtrList['typ'].'" placeholder="Bemerkung..."/>';
					break;					
			}						
		}
	}
	$html_atr .= '</select>'.$html_atr_select; 
	$html_atr .= '<br/><br/>';
}

if($bGefunden == true) {	
	echo '<input type="hidden" name="hasAdditionalValues" value="true"/>';
	echo $html_atr;
} 
?>	
<input type="hidden" value="<?php echo $_POST['item_id']; ?>" name="shop_item_id" id="shop_item_id">
		<input type="submit" class="button" value="Gericht anpassen">
	</form>
</div>