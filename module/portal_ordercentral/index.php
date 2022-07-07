<?php 
	session_start();
	$path = realpath($_SERVER["DOCUMENT_ROOT"]);	
	require_once($path.'/include/inc_config-data.php');
	require_once($path.'/include/inc_basic-functions.php');		
	#ini_set('display_errors', true) 
	#error_reporting(E_ALL);
	$_POST = mysql_real_escape_array($_POST);
	$_GET  = mysql_real_escape_array($_GET);
	
# Modus = RECHNUNG_ERSTELLEN
function get_ordered_items_by_customer($kundenid,$modus,$Invoice_id='') {

	if($kundenid != 'ALLE') {
		$strFilterByKunde = " AND ges_order_customer_id='".$kundenid."'";
	} else {
		$strFilterByKunde = '';
	}
	
	#echo $modus.' ABC';
	switch($modus) {
		case 'RECHNUNG_ERSTELLEN':
			$strOrderStatus = "AND ges_order_status='bestellt'";
			break;
		case 'RECHNUNG_BEZAHLEN':
			$strOrderStatus = "AND ges_order_status='Rechnung gestellt' AND invoice_id='".$_GET['invoice_id']."'";
			#echo $strOrderStatus;
			break;
	}
	
	#echo $modus;
	#echo $_SESSION['portal_domain_id'];
	$query = "SELECT count(*) as anzahl FROM shop_order JOIN shop_order_list ON shop_order.shop_order_id = shop_order_list.id_shop_order WHERE domain_id='".$_SESSION['portal_domain_id']."' ".$strFilterByKunde." ".$strOrderStatus."   ORDER by  ges_order_customer_id DESC,invoice_id DESC, shop_order.updated_at ASC "; 
	$resItems = DBi::$conn->query($query) or die(mysqli_error());
	$strOrderAnzahl = mysqli_fetch_assoc($resItems);
		
	$query = "SELECT * FROM shop_order JOIN shop_order_list ON shop_order.shop_order_id = shop_order_list.id_shop_order WHERE domain_id='".$_SESSION['portal_domain_id']."' ".$strFilterByKunde." ".$strOrderStatus." ORDER by invoice_id DESC, ges_order_customer_id ASC, shop_order.updated_at DESC "; 
	
	#echo $query.' - '.$modus.'<br/>';
	$resItems = DBi::$conn->query($query) or die(mysqli_error());
	$iCount = 0;
	$iCountInvoice =0;
	$text .= '<form name="frmOrderStatus_'.$kundenid.'_'.$Invoice_id.'" id="frmOrderStatus_'.$kundenid.'_'.$Invoice_id.'" action="/ACP/acp_order_status.php" onSubmit="return abrechnung_save_form(\'frmOrderStatus_'.$kundenid.'_'.$Invoice_id.'\');" method="POST">';
	$iCountGesamt = 0;
	while($strShopItem = mysqli_fetch_assoc($resItems)) {
		$query ="SELECT *,shop_item.name_de as shop_name FROM shop_item JOIN menue ON shop_item.menue_id = menue.id WHERE shop_item.shop_item_id ='".$strShopItem['shop_item_id']."'";
		$resItem = DBi::$conn->query($query) or die(mysqli_error());
		$Cartdata = mysqli_fetch_assoc($resItem);
		$iCountGesamt++;
		# Gleicher Kunde aber zwei verschiedene Rechnungen 
		# invoice_id gleich!
		if($strShopItem['invoice_id'] != $strInvoiceID) {
			$bInvoiceEqual = 0;
			#$iCount = 0;
			$strInvoiceID = $strShopItem['invoice_id'];
			$strFilterByKunde = " AND ges_order_customer_id='".$kundenid."'";
			$query = "SELECT count(*) as anzahl FROM shop_order_list WHERE id_invoice_no='".$strInvoiceID."'";
			#echo $query.'<br/>	';
			$strInvoiceCount =  mysqli_fetch_assoc(DBi::$conn->query($query));
			$iCount=0;
			
		} else {
			
			if($strInvoiceID == '') {
				
				$bInvoiceEqual = 1;
			} else {
				$bInvoiceEqual = 1;
			}
		}
		
		
		
		#echo $strInvoiceCount['anzahl'].'-'.$strInvoiceID.'<br/>';
		#$query = "SELECT * FROM shop_info WHERE domain_id='".$Cartdata['domain_id']."'";
		
		
		$shop_artikel_preis = str_replace(".",",",$strShopItem['preis']);			
		$shop_artikel_preisGes += $shop_artikel_preis * $strShopItem['order_menge'];
		#echo $Cartdata['ges_order_anzahl'];
		$shop_artikel_gewicht += $strShopItem['gewicht'] * $strShopItem['order_menge'];	
			
		// Seite wo Artikel liegt abrufen
		$pathItem = 'http://'.$_SERVER['SERVER_NAME'].'/'.getPathUrl($_SESSION['language'],$Cartdata['menue_id']);
		
		$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$strShopItem['shop_item_id']."'";
		$strBild = mysqli_fetch_assoc(DBi::$conn->query($query));
		$strBild['picture_url'] = 'http://'.$_SERVER['SERVER_NAME'].'/'.$strBild['picture_url'];
		
		# Bestellstatus: Aktionen anzeigen
		if($modus == 'BESTELlUNGEN') {
			#onClick="javascript:set_rechnung_erstellen(\''.$strShopItem['ges_order_customer_id'].'\')"
			$strOrderOptions = '<div class="order_options"> <span class="spanlink" id="rechnung_'.$strShopItem['ges_order_customer_id'].'_'.$strInvoiceID.'" >Rechnung erstellen</span> | <span class="spanlink" id="zahlungerhalten_'.$strShopItem['ges_order_customer_id'].'_'.$strInvoiceID.'" >Zahlung erhalten</span> | <span class="spanlink" id="versandstarten_'.$strShopItem['ges_order_customer_id'].'_'.$strInvoiceID.'">Versand starten</span> | <span class="spanlink" id="bewerten_'.$strShopItem['ges_order_customer_id'].'_'.$strInvoiceID.'">Bewerten</span> | <span class="spanlink" id="komplett_'.$strShopItem['ges_order_customer_id'].'_'.$strInvoiceID.'">Bestellung Komplett</span></div><br/><br/>';
		} 
		
		# Artikelauflistung
		$strKundenID = $strShopItem['ges_order_customer_id'];
		#echo $strShopItem['ges_order_customer_id'].'<br/>';
		#if($iCount == 0 || $bInvoiceEqual == 0) {
		/*if($bInvoiceEqual == 0) {
			$query = "SELECT * FROM shop_order_customer WHERE shop_order_customer_id='".$strShopItem['ges_order_customer_id']."'";
			$resKunde = DBi::$conn->query($query) or die(mysqli_error());
			$strKunde = mysqli_fetch_assoc($resKunde);
			$text .= '<h2>K&auml;ufer '.$iCount.': <a href="http://shopste.com/mitglied/de/'.$strKunde['mitgliedsname'].'">'.$strKunde['mitgliedsname'].'</a> - '.$strKunde['vorname'].' '.$strKunde['nachname'].' aus '.$strKunde['stadt'].' <span style="font-size:10px">Bestellid '.$strShopItem['shop_order_id'].' vom '.getDateDE($strShopItem['created_at']).'</span></h2> ('.$strShopItem['ges_order_status'].')'.$strOrderOptions.'
			'.$tmpKundenID.'
			<table width="100%"><tr><td>Bild</td><td>Name</td><td>Menge</td><td>Preis</td></tr>';			
			$tmpKundenID = $strShopItem['ges_order_customer_id'];
		}*/
		
		# Bestellpositionen zählen
		$query = "SELECT count(*) as anzahl FROM shop_order JOIN shop_order_list ON shop_order.shop_order_id = shop_order_list.id_shop_order WHERE domain_id='".$_SESSION['portal_domain_id']."' AND ges_order_customer_id='".$strKunde['shop_order_customer_id']."' ".$strOrderStatus."   ORDER by invoice_id DESC, ges_order_customer_id DESC, shop_order.updated_at ASC "; 
		$resItemstmp2 = DBi::$conn->query($query) or die(mysqli_error());
		$strOrderMenge = mysqli_fetch_assoc($resItemstmp2);
		
		# GLEICHE Kundennummer
		if($strKundenID == $tmpKundenID ) {
					
			# Artikeldetail
			$tmpKundenID = $strKundenID;
			$text .= '<tr>
			<td>'.($iCount +1).'</td><td><img src="'.$strBild['picture_url'].'" width="50px" Height="50px"/></a></td>
			<td>'.$shop_artikel_preisGes.'<a href="'.$pathItem.'">'.$strShopItem['name_de'].' ('.$strInvoiceID.'-'.$bInvoiceEqual.' '.$iCount.' '.($strInvoiceCount['anzahl'] -1).'..'.$strOrderAnzahl['anzahl'].')</a></td>';
			$text .= '<td>'.$strShopItem['order_menge'].'</td>';

			# Status senden
			$text .= '<input type="hidden" name="cart_id['.$iCount.']" value="'.$strShopItem['shop_item_id'].'"/>';
			$text .= '<input type="hidden" name="cart_amount['.$iCount.']" value="'.$strShopItem['order_menge'].'"/>';
			$text .= '<input type="hidden" name="cart_price['.$iCount.']" value="'.$strShopItem['preis'].'"/>';
			$text .= '<input type="hidden" name="cart_orderid['.$iCount.']" value="'.$strShopItem['shop_order_id'].'"/>';
			
			$text .= '<td>'.$strShopItem['preis'].' EUR</td>';
			$text .= '<td>'.$strShopItem['order_menge'] * $strShopItem['preis'].' EUR</td>';
			$text .= '</tr>'; #jb fix tr oder /tr?
			

			
			if($iCount == $strInvoiceCount['anzahl'] ||  $strOrderMenge['anzahl'] - 1 == $iCount) {
				
				$text .= '<tr><td colspan="5" style="text-align:right">- invoice anzahl'.$strInvoiceCount['anzahl'].'-'.$strOrderAnzahl['anzahl'].' Artikel Summe</td><td>'.$shop_artikel_preisGes.' EUR</td><td>&nbsp;</td></tr>';
				
				# Versandkosten
				$query = "SELECT * FROM shop_shippment_detail WHERE shop_shippment_detail.gewicht_von <= '".$shop_artikel_gewicht."' AND shop_shippment_detail.gewicht_bis>= '".$shop_artikel_gewicht."' AND domain_id='".$strShopItem['domain_id']."'";
				$resVersandkostenPreis = DBi::$conn->query($query) or die(mysqli_error());
				$shop_artikel_preisVersand = 0;
				$bVersandartGefunden = false;
				while($strVersandKosten = mysqli_fetch_assoc($resVersandkostenPreis)) {
					$strVersandHTML = '<tr><td colspan="5" style="text-align:right">Gewicht</td><td>'.str_replace('.',',',$shop_artikel_gewicht).' KG</td><td>&nbsp;</td></tr>';
					
					if($modus == 'RECHNUNG_ERSTELLEN') {
						$strVersandHTML .= '<tr><td colspan="5" style="text-align:right">Versandkosten</td><td><input type="text" style="width:30px" name="order_versandkosten" value="'.str_replace('.',',',$strVersandKosten['versandkosten']).'"/> EUR</td><td>&nbsp;</td></tr>';
					} else {
						$strVersandHTML .= '<tr><td colspan="5" style="text-align:right">Versandkosten</td><td>'.str_replace('.',',',
						$strVersandKosten['versandkosten']).' EUR</td><td>&nbsp;</td></tr>';					
					}
					$shop_artikel_preisVersand = $strVersandKosten['versandkosten'];
					$bVersandartGefunden = true;
				}
				
				$text .= $strVersandHTML;
				$text .= '<tr><td colspan="5" style="text-align:right"><h2>Endsumme</h2></td><td><h2>'.($shop_artikel_preisGes + $shop_artikel_preisVersand).' EUR</h2></td><td>&nbsp;</td></tr>';
 

			
				$text .= '</table>';
			} # Footer
			#echo $iCount.'-'..' '.$text;
			#exit;
		} else {
#exit;
			
			####################################
			# ABSCHLUSS Bestellliste 1 Eintrag
			####################################
			#if( $strOrderMenge['anzahl'] - 1 == $iCount) {
			#if($iCount == $strInvoiceCount['anzahl'] ||  $strOrderMenge['anzahl'] - 1 == $iCount) {
				$text .= '<tr><td colspan="5" style="text-align:right">'.$iCount.' Artikel Summe</td><td>'.$shop_artikel_preisGes.' EUR</td><td>&nbsp;</td></tr>';
				# Versandkosten
				// Versandkosten berechnen
				$query = "SELECT * FROM shop_shippment_detail WHERE shop_shippment_detail.gewicht_von <= '".$shop_artikel_gewicht."' AND shop_shippment_detail.gewicht_bis>= '".$shop_artikel_gewicht."' AND domain_id='".$strShopItem['domain_id']."'";
				$resVersandkostenPreis = DBi::$conn->query($query) or die(mysqli_error());
				$shop_artikel_preisVersand = 0;
				$bVersandartGefunden = false;
				while($strVersandKosten = mysqli_fetch_assoc($resVersandkostenPreis)) {
					$strVersandHTML = '<tr><td colspan="5" style="text-align:right">Gewicht</td><td>'.str_replace('.',',',$shop_artikel_gewicht).' KG</td><td>&nbsp;</td></tr>';
					
					if($modus == 'RECHNUNG_ERSTELLEN') {
						$strVersandHTML .= '<tr><td colspan="5" style="text-align:right">Versandkosten</td><td><input type="text" name="order_versandkosten"  style="width:30px" value="'.str_replace('.',',',
						$strVersandKosten['versandkosten']).'"/> EUR</td><td>&nbsp;</td></tr>';
					} else {
						$strVersandHTML .= '<tr><td colspan="5" style="text-align:right">Versandkosten</td><td>'.str_replace('.',',',
						$strVersandKosten['versandkosten']).' EUR</td><td>&nbsp;</td></tr>';					
					}
					
					$shop_artikel_preisVersand = $strVersandKosten['versandkosten'];
					$bVersandartGefunden = true;
				}
				
				$text .= $strVersandHTML;
				$text .= '<tr><td colspan="5" style="text-align:right"><h2>Endsumme</h2></td><td width="20%"><h2>'.($shop_artikel_preisGes + $shop_artikel_preisVersand).' EUR</h2></td><td>&nbsp;</td></tr>';
	 
				$text .= '</table>';
				$iCount =0;
				$shop_artikel_preisGes = 0;
				#echo $Cartdata['ges_order_anzahl'];
				$shop_artikel_gewicht = 0;
			#}
			#}
			
			######################################################
			# Neuer Bestellliste eintrag - Kundendaten auslesen
			######################################################
			$query = "SELECT * FROM shop_order_customer WHERE shop_order_customer_id='".$strShopItem['ges_order_customer_id']."'";
			$resKunde = DBi::$conn->query($query) or die(mysqli_error());
			$strKunde = mysqli_fetch_assoc($resKunde);
			
			## HEADER PRO Bestellung und Rechnung
			$text .= '<h2>'.$iCountGesamt.' K&auml;ufer: <a href="http://shopste.com/mitglied/de/'.$strKunde['mitgliedsname'].'">'.$strKunde['mitgliedsname'].'</a> - '.$strKunde['vorname'].' '.$strKunde['nachname'].' <font size="1">('.$strKunde['email'].')</font> aus '.$strKunde['stadt'].' <span style="font-size:10px">Bestellid '.$strShopItem['shop_order_id'].' vom '.getDateDE($strShopItem['created_at']).'</span></h2>('.$strShopItem['ges_order_status'].') '.$strOrderOptions;
			
			
			#echo $modus;
			$query = "SELECT count(*) as anzahl FROM shop_order JOIN shop_order_list ON shop_order.shop_order_id = shop_order_list.id_shop_order WHERE domain_id='".$_SESSION['portal_domain_id']."' AND ges_order_customer_id='".$strKunde['shop_order_customer_id']."' ".$strOrderStatus."   ORDER by invoice_id DESC, ges_order_customer_id DESC, shop_order.updated_at ASC "; 
			$resItemstmp = DBi::$conn->query($query) or die(mysqli_error());
			$strOrderMenge = mysqli_fetch_assoc($resItemstmp);
			
			#$query ="SELECT count(*) as anzahl FROM shop_order_list WHERE id_shop_order='".$strShopItem['shop_order_id']."'";
			#$strOrderMenge = mysqli_fetch_assoc(DBi::$conn->query($query));
			
			# Neue Bestellung fängt an
			$text .= '<table width="100%"><tr><td><h3>Nr.</h3></td><td><h3>Bild</h3></td><td><h3>Name</h3></td><td><h3>Menge</h3></td><td><h3>Einzelpreis</h3></td><td><h3>Summe</h3></td></tr>';			
			$tmpKundenID = $strShopItem['ges_order_customer_id'];
			$text .= '<tr>
			<td>'.($iCount + 1).'</td><td><img src="'.$strBild['picture_url'].'" width="50px" Height="50px"/></a></td>
			<td><a href="'.$pathItem.'">'.$strShopItem['name_de'].' ('.$strInvoiceID.'-'.$bInvoiceEqual.' ->'.$iCount.' ->'.($strInvoiceCount['anzahl'] -1).'..'.$strOrderAnzahl['anzahl'].')</a></td>';
			$text .= '<td>'.$strShopItem['order_menge'].'</td>';
			
			# Status senden
			$text .= '<input type="hidden" name="cart_id['.$iCount.']" value="'.$strShopItem['shop_item_id'].'"/>';
			$text .= '<input type="hidden" name="cart_amount['.$iCount.']" value="'.$strShopItem['order_menge'].'"/>';
			$text .= '<input type="hidden" name="cart_price['.$iCount.']" value="'.$strShopItem['preis'].'"/>';
			$text .= '<input type="hidden" name="cart_orderid['.$iCount.']" value="'.$strShopItem['shop_order_id'].'"/>';
			
			$text .= '<td>'.$strShopItem['preis'].' EUR</td>';
			$text .= '<td>'.$strShopItem['order_menge'] * $strShopItem['preis'].' EUR</td>';
			$text .= '</tr>';
			
			$shop_artikel_preis = str_replace(".",",",$strShopItem['preis']);			
			$shop_artikel_preisGes += $shop_artikel_preis * $strShopItem['order_menge'];
			#echo $Cartdata['ges_order_anzahl'];
			$shop_artikel_gewicht += $strShopItem['gewicht'] * $strShopItem['order_menge'];	
			
			if($iCount == $strInvoiceCount['anzahl'] -1 ||   $strOrderMenge['anzahl'] - 1 == $iCount) {
				
				$text .= '<tr><td colspan="5" style="text-align:right">'.$strOrderMenge['anzahl'].'--Artikel Summe</td><td>'.$shop_artikel_preisGes.' EUR</td><td>&nbsp;</td></tr>';
				
				# Versandkosten
				$query = "SELECT * FROM shop_shippment_detail WHERE shop_shippment_detail.gewicht_von <= '".$shop_artikel_gewicht."' AND shop_shippment_detail.gewicht_bis>= '".$shop_artikel_gewicht."' AND domain_id='".$strShopItem['domain_id']."'";
				$resVersandkostenPreis = DBi::$conn->query($query) or die(mysqli_error());
				$shop_artikel_preisVersand = 0;
				$bVersandartGefunden = false;
				while($strVersandKosten = mysqli_fetch_assoc($resVersandkostenPreis)) {
					$strVersandHTML = '<tr><td colspan="5" style="text-align:right">Gewicht</td><td>'.str_replace('.',',',$shop_artikel_gewicht).' KG</td><td>&nbsp;</td></tr>';
					
					if($modus == 'RECHNUNG_ERSTELLEN') {
						$strVersandHTML .= '<tr><td colspan="5" style="text-align:right">Versandkosten</td><td><input type="text" style="width:30px" name="order_versandkosten" value="'.str_replace('.',',',$strVersandKosten['versandkosten']).'"/> EUR</td><td>&nbsp;</td></tr>';
					} else {
						$strVersandHTML .= '<tr><td colspan="5" style="text-align:right">Versandkosten</td><td>'.str_replace('.',',',
						$strVersandKosten['versandkosten']).' EUR</td><td>&nbsp;</td></tr>';					
					}
					$shop_artikel_preisVersand = $strVersandKosten['versandkosten'];
					$bVersandartGefunden = true;
				}
				
				$text .= $strVersandHTML;
				$text .= '<tr><td colspan="5" style="text-align:right"><h2>Endsumme</h2></td><td><h2>'.($shop_artikel_preisGes + $shop_artikel_preisVersand).' EUR</h2></td><td>&nbsp;</td></tr>';
 
				$text .= '</table>';
				$iCount = 0;
				$shop_artikel_preisGes = 0;
				$shop_artikel_gewicht = 0;
			} # Gleicher Kunde

		}
		$iCount++;
	}
	switch($modus) {
		case 'RECHNUNG_ERSTELLEN':
			$text .= '<input type="hidden" name="cart_customerid" value="'.$kundenid.'"/>';	
			$text .= '<input type="hidden" name="modus" value="'.$modus.'"/>';	
			$text .= '<input type="hidden" name="domain_id" value="'.$_SESSION['portal_domain_id'].'"/>';	
			$text .= '<input type="submit" name="btnRechnung_senden" id="btnRechnung_senden" value="Rechnung erstellen" class="button" /><br/><br/>';
			break;
		case 'RECHNUNG_VERSAND_STARTEN':
			$text .= '<input type="hidden" name="cart_customerid" value="'.$kundenid.'"/>';	
			$text .= '<input type="hidden" name="modus" value="'.$modus.'"/>';	
			$text .= '<input type="hidden" name="domain_id" value="'.$_SESSION['portal_domain_id'].'"/>';	
			$text .= '<input type="submit" name="btnRechnung_senden" id="btnRechnung_senden" value="Versand starten" class="button" /><br/><br/>';			
			break;
		case 'RECHNUNG_KOMPLETT':
			$text .= '<input type="hidden" name="cart_customerid" value="'.$kundenid.'"/>';	
			$text .= '<input type="hidden" name="modus" value="'.$modus.'"/>';	
			$text .= '<input type="hidden" name="domain_id" value="'.$_SESSION['portal_domain_id'].'"/>';	
			$text .= '<input type="submit" name="btnRechnung_senden" id="btnRechnung_senden" value="Transaktionen abschließen" class="button" /><br/><br/>';break;
		case 'RECHNUNG_BEWERTEN':
			$text .= '<input type="hidden" name="cart_customerid" value="'.$kundenid.'"/>';	
			$text .= '<input type="hidden" name="modus" value="'.$modus.'"/>';	
			$text .= '<input type="hidden" name="domain_id" value="'.$_SESSION['portal_domain_id'].'"/>';	
			$text .= '<input type="submit" name="btnRechnung_senden" id="btnRechnung_senden" value="Transaktionen bewerten" class="button" /><br/><br/>';break;
		case 'RECHNUNG_BEZAHLEN':
			$text .= '<input type="hidden" name="cart_customerid" value="'.$kundenid.'"/>';	
			$text .= '<input type="hidden" name="modus" value="'.$modus.'"/>';	
			$text .= '<input type="hidden" name="domain_id" value="'.$_SESSION['portal_domain_id'].'"/>';	
			$text .= '<input type="submit" name="btnRechnung_senden" id="btnRechnung_senden" value="Zahlung erhalten" class="button" /><br/><br/>';			
			break;
	}
	$text .= '</form>';
	$text .= '</table><br/><br/></div>'; // config modus 
	return $text;
}
####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_portal_ordercentral($config) {

		$dataTextCentral = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_portal_ordercentral WHERE id=".$config['modul_id']));
		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='portal_ordercentral'"));
		#echo "IN";
		
		$dataTextHTML['typ'] = 'portal_ordercentral';
		
		$text = '<div class="contentborderless" id="order_buynow">';
		
		#$text .= convertUmlaute($dataTextHTML["content_".$_SESSION['language']]);
		$titel = convertUmlaute($dataTextCentral["title_".$_SESSION['language']]);
		

			
		if($titel == '') { 
			$titel = 'Produkt Sofortkaufen';
		} 
		
		// && $config["container"]
		if($_SESSION['login'] == '1'  AND $module_in_menue['container'] == 'col-main') {
			$strReturn = getMember($dataTextCentral['last_usr']);
			if(!empty($strReturn)) {
				$ary = explode(" ",$dataTextCentral['lastchange']);
				$german_de = getDateDE($ary[0]);
				$titel .= '</h1> - '.$strReturn.' - '.$german_de.' '.$ary[1];
			}
		}
		
		# Eingeloggt 
		if (@$_SESSION['login'] == '1')  { 
			if($titel == '') { 
				$titel = "Kein Titel"; 
			} 
		} 
		
	
		$text = get_ordered_items_by_customer('ALLE','BESTELlUNGEN');
		
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 } 
if($_GET['bAjax'] == 'true') {
	switch($_GET['modus']) {
		case 'rechnung':
			$text = get_ordered_items_by_customer($_GET['kundenid'],'RECHNUNG_ERSTELLEN');
			break;
		case 'versandstarten':
			$text = get_ordered_items_by_customer($_GET['kundenid'],'RECHNUNG_VERSAND_STARTEN',$_GET['invoice_id']);
			break;
		case 'komplett':
			$text = get_ordered_items_by_customer($_GET['kundenid'],'RECHNUNG_KOMPLETT',$_GET['invoice_id']);
			break;
		case 'rechnungbezahlen':
			$text = get_ordered_items_by_customer($_GET['kundenid'],'RECHNUNG_BEZAHLEN',$_GET['invoice_id']);
			break;
		case 'bewerten':
			$text = get_ordered_items_by_customer($_GET['kundenid'],'RECHNUNG_BEWERTEN',$_GET['invoice_id']);
			break;
		
	}
	echo $text;
}
 ?>