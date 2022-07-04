<?php 
	@session_start();
	include_once('../include/inc_config-data.php');
	include_once('../include/inc_basic-functions.php');
	
	$_GET = mysql_real_escape_array($_GET);
	$_POST = mysql_real_escape_array($_POST);
	$_SESSION = mysql_real_escape_array($_SESSION);
	
	
	if($_SESSION['portal_login'] == 1) {
		$query = "SELECT * FROM benutzer WHERE username='".$_SESSION['portal_user']."'";
		$resUser = DBi::$conn->query($query) or die(mysqli_error());
		$strData = mysqli_fetch_assoc($resUser);
		
		$_POST['txtVorname'] = $strData['vorname'];
		$_POST['txtNachname'] = $strData['nachname'];
		$_POST['txtStrasse'] = $strData['strasse_hnr'];
		$_POST['txtPLZ'] = $strData['plz'];
		$_POST['txtOrt'] = $strData['stadt'];
		$_POST['txtLand'] = $strData['land'];
		$_POST['txtEmail'] = $strData['email'];
		$_POST['txtFirma'] = $strData['firma'];
	}
	
	$_SESSION['order_vorname'] = $_POST['txtVorname'];
	$_SESSION['order_nachname'] = $_POST['txtNachname'];
	$_SESSION['order_strasse'] = $_POST['txtStrasse'];
	$_SESSION['order_plz'] = $_POST['txtPLZ'];
	$_SESSION['order_ort'] = $_POST['txtOrt'];
	$_SESSION['order_land'] = $_POST['txtLand'];
	$_SESSION['order_email'] = $_POST['txtEmail'];
	$_SESSION['order_firma'] = $_POST['txtFirma'];
	$_SESSION['order_liefer_datum'] = $_POST['liefer_datum'];
	$_SESSION['order_liefer_zeit'] = $_POST['liefer_zeit'];
	
	// Datenbankverbindung
	/*$path = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once($path.'/include/inc_config-data.php');
	require_once($path.'/include/inc_basic-functions.php');*/
	
	###########################################################
	# >> Gibt es Benutzerspezifische Vorlagen?
	###########################################################
	
	$query ="SELECT count(*) as anzahl FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='BESTELLBESTÄTIGUNG'";
	#echo $query;
	$resEmailCount = DBi::$conn->query($query) or die(mysqli_error());
	$strEmailCount = mysqli_fetch_assoc($resEmailCount);
	if($strEmailCount['anzahl'] > 0) {
		# Lade Benutzervorlage
		$query ="SELECT * FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='BESTELLBESTÄTIGUNG'";
		#echo $query;
		$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error());
		$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
	} else {
		# Lade Defaultvorlage
		$query ="SELECT * FROM email_vorlage WHERE domain_id='0' AND standard='Y' AND typ='BESTELLBESTÄTIGUNG'";
		#echo $query;
		$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error());
		$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
	}
	
	$query ="SELECT count(*) as anzahl FROM email_vorlage_settings WHERE vorlagen_id='".$strEmailVorlage['email_vorlage_id']."'";
	$resEmailSettings = DBi::$conn->query($query) or die(mysqli_error());
	$strSettings = mysqli_fetch_assoc($resEmailSettings);
	if($strSettings['anzahl'] > 0) {
		$query ="SELECT * FROM email_vorlage_settings WHERE vorlagen_id='".$strEmailVorlage['email_vorlage_id']."'";
		$resEmailSettings = DBi::$conn->query($query) or die(mysqli_error());
		$strSettings = mysqli_fetch_assoc($resEmailSettings);
		$bEmailSettings = true;
		$iColspan = 5;
	} else {
		$bEmailSettings = false;
		$iColspan = 5;		
	}
	// Warenkorb + Kunden übertragen
	if(isset($_POST['txtEmail'])) {
	

		$strAry = explode("|",$_SESSION['shop_cart_ids']);
		// Gesamtsumme berechnen
		$tmpVersandkosten = '';
		for($i=0; $i < count($strAry) -1; $i++) {
			// Artikel Details holen 
			$strItemDetailAry = explode("-",$strAry[$i]);
			if($i==0) {
				$query = "SELECT * FROM shop_item JOIN shop_info ON shop_item.domain_id = shop_info.domain_id WHERE shop_item.shop_item_id='".$strItemDetailAry[0]."'";
				$resShopInfo = DBi::$conn->query($query) or die(mysqli_error());
				$ShopInfoData = mysqli_fetch_assoc($resShopInfo);
				
			}
			$query ="SELECT *,shop_item.name_de as shop_name FROM shop_item JOIN menue ON shop_item.menue_id = menue.id WHERE shop_item.shop_item_id ='".$strItemDetailAry[0]."'";
			$resItem = DBi::$conn->query($query) or die(mysqli_error());
			$Cartdata = mysqli_fetch_assoc($resItem);
			
			# Höchste Versandkosten finden
			if($tmpVersandkosten < $Cartdata['versandkosten']) {
				$tmpVersandkosten = $Cartdata['versandkosten'];
			}
			
			$shop_artikel_preis = str_replace(",",".",$strItemDetailAry[2]);
			$shop_artikel_preisGes += $shop_artikel_preis * $strItemDetailAry[1];
			$shop_artikel_gewicht += $Cartdata['gewicht'] * $strItemDetailAry[1];
			$shop_artikel_anzahl += $strItemDetailAry[1];
		}
		$Cartdata['versandkosten'] = $tmpVersandkosten;
		
		$query = "SELECT count(email) as anzahl,shop_order_customer_id  FROM shop_order_customer WHERE email='".$_POST['txtEmail']."'";
		$resKundeVorhanden = DBi::$conn->query($query) or die(mysqli_error());
		$strKundeVorhanden = mysqli_fetch_assoc($resKundeVorhanden);
		
		if($strKundeVorhanden['anzahl'] == 0) {
			// Kunden einfügen
			$query = "INSERT INTO shop_order_customer (anrede,email,firma,vorname,nachname,strasse_hnr,plz,stadt,land,domain_id,telefon) VALUES ('".$_POST['txtEmail']."','".$_POST['txtEmail']."','".$_POST['txtFirma']."','".$_POST['txtVorname']."','".$_POST['txtNachname']."','".$_POST['txtStrasse']."','".$_POST['txtPLZ']."','".$_POST['txtOrt']."','".$_POST['txtLand']."','".$ShopInfoData['domain_id']."','".$_POST['txtTelefon']."');";
			DBi::$conn->query($query) or die(mysqli_error());
			$iLastCustomerID = mysqli_insert_id(DBi::$conn);
		} else {
			$query = "UPDATE shop_order_customer SET firma='".$_POST['txtFirma']."',vorname='".$_POST['txtVorname']."',nachname='".$_POST['txtNachname']."',strasse_hnr='".$_POST['txtStrasse']."',plz='".$_POST['txtPLZ']."',stadt='".$_POST['txtOrt']."',land='".$_POST['txtLand']."',telefon='".$_POST['txtTelefon']."' WHERE email='".$_POST['txtEmail']."'";
			DBi::$conn->query($query) or die(mysqli_error());
			$iLastCustomerID = $strKundeVorhanden['shop_order_customer_id'];
		}
		
		if($strSettings['hasShipping'] == 'N') {
			$strVersandHTML .= '<tr><td colspan="4" style="text-align:right"><strong>Lieferkosten</strong></td><td>'.$strSettings['Shipping_text'].'</td><td>&nbsp;</td></tr>';			
		} else {
			
			// Versandkosten berechnen
			$query = "SELECT * FROM shop_shippment_detail WHERE shop_shippment_detail.gewicht_von <= '".$shop_artikel_gewicht."' AND shop_shippment_detail.gewicht_bis>= '".$shop_artikel_gewicht."' AND domain_id='".$ShopInfoData['domain_id']."'";
			$resVersandkostenPreis = DBi::$conn->query($query) or die(mysqli_error());
			$shop_artikel_preisVersand = 0;
			$bVersandartGefunden = false;
			while($strVersandKosten = mysqli_fetch_assoc($resVersandkostenPreis)) {
				$strVersandHTML .= '<tr><td colspan="5" style="text-align:right">Gewicht</td><td>'.str_replace('.',',',$shop_artikel_gewicht).' KG</td><td>&nbsp;</td></tr>';
				
				$query ="SELECT * FROM shop_shippment WHERE shop_shippment_id='".$strVersandKosten['shop_shippment_id']."'";
				$resVersandArt = DBi::$conn->query($query);
				$strVersandKostenName = mysqli_fetch_assoc($resVersandArt);
					
				$strVersandHTML .= '<tr><td colspan="5" style="text-align:right">Versandkosten '.$strVersandKostenName['name_de'].'</td><td>'.number_format($strVersandKosten['versandkosten'], 2, ',', '.').' EUR</td><td>&nbsp;</td></tr>';
				$shop_artikel_preisVersand = $strVersandKosten['versandkosten'];
				$shop_artikel_preisVersand_mwst = $strVersandKostenName['mwst'];
				$bVersandartGefunden = true;
			}
			
			if($bVersandartGefunden == false) {
				if($Cartdata['versandkosten'] != '') {
					$shop_artikel_preisVersand = $Cartdata['versandkosten'];
					if(!empty($Cartdata['versandkosten_name'])) {
						$strVersandHTML = '<tr><td colspan="'.$iColspan.'" style="text-align:right"><strong>Zwischensumme</strong></td><td colspan="2">'.number_format($shop_artikel_preisGes, 2, ',', '.').' EUR</td></tr>';
						$strVersandHTML .= '<tr><td colspan="5" style="text-align:right"><strong>Gewicht</strong></td><td colspan="2">'.str_replace('.',',',$shop_artikel_gewicht).' KG</td></tr>';	
						$strVersandHTML .= '<tr><td colspan="5" style="text-align:right"><strong>Versandkosten</strong></td><td colspan="2">'.$Cartdata['versandkosten_name'].' '.number_format($Cartdata['versandkosten'], 2, ',', '.').' EUR</td></tr>';
					} else {
						$strVersandHTML = '<tr><td colspan="'.$iColspan.'" style="text-align:right"><strong>Zwischensumme</strong></td><td colspan="2">'.number_format($shop_artikel_preisGes, 2, ',', '.').' EUR</td></tr>';
						$strVersandHTML .= '<tr><td colspan="5" style="text-align:right"><strong>Versandkosten</strong></td><td colspan="2">'.number_format($Cartdata['versandkosten'], 2, ',', '.').' EUR</td></tr>';
					}
				} else {				
					
					$strVersandHTML = '<tr><td colspan="'.$iColspan.'" style="text-align:right"><strong>Zwischensumme</strong></td><td colspan="2">'.number_format($shop_artikel_preisGes, 2, ',', '.').' EUR</td></tr>';
					$strVersandHTML .= '<tr><td colspan="'.$iColspan.'" style="text-align:right"><strong>Versandkosten</strong></td><td colspan="2">werden nach Bestellung berechnet</td></tr>';
				}
			} else {
				if($Cartdata['versandkosten'] != '') {
					$shop_artikel_preisVersand = $Cartdata['versandkosten'];
					if(!empty($Cartdata['versandkosten_name'])) {
						$strVersandHTML = '<tr><td colspan="'.$iColspan.'" style="text-align:right"><strong>Zwischensumme</strong></td><td colspan="2">'.number_format($shop_artikel_preisGes, 2, ',', '.').' EUR</td></tr>';
				
						$strVersandHTML .= '<tr><td colspan="'.$iColspan.'" style="text-align:right"><strong>Versandkosten</strong></td><td colspan="2">'.$Cartdata['versandkosten_name'].' '.number_format($Cartdata['versandkosten'], 2, ',', '.').' EUR</td></tr>';
					} else {
						$strVersandHTML = '<tr><td colspan="'.$iColspan.'" style="text-align:right"><strong>Zwischensumme</strong></td><td colspan="2">'.number_format($shop_artikel_preisGes, 2, ',', '.').' EUR</td></tr>';
					
						$strVersandHTML .= '<tr><td colspan="'.$iColspan.'" style="text-align:right"><strong>Versandkosten</strong></td><td colspan="2">'.number_format($Cartdata['versandkosten'], 2, ',', '.').' EUR</td></tr>';
					}
				} else {
						$strVersandHTML = '<tr><td colspan="'.$iColspan.'" style="text-align:right"><strong>Zwischensumme</strong></td><td colspan="2">'.number_format($shop_artikel_preisGes, 2, ',', '.').' EUR</td></tr>';
				}			
			}
		} # has Shipping
 
		if($_SESSION['domain_id'] == "1") {
			$bIsShopste = 'Y';
		} else {
			$bIsShopste = 'N';		
		}

		$query = "SELECT count(*) as anzahl FROM shop_order_nr WHERE domain_id='".$_SESSION['domain_id']."'";
		$resOrderID = DBi::$conn->query($query);
		$strOrderAnzahl = mysqli_fetch_assoc($resOrderID);
		$strOrderAnzahl['anzahl'] +=1;
		
		$query = "INSERT INTO shop_order_nr(nr,domain_id) VALUES('".$strOrderAnzahl['anzahl']."','".$_SESSION['domain_id']."')";
		$resOrderID = DBi::$conn->query($query);
		$strOrderNrID = mysqli_insert_id(DBi::$conn);
		
		if(!empty($_POST['liefer_datum'])) {
			if(!empty($_POST['liefer_zeit'])) {
				$d    =    explode(" ",$_POST['liefer_datum'].' '.$_POST['liefer_zeit'].':00');
				$strDatum = $d[0];
				$strDatum_mysql = explode('.',$strDatum);
				$strDatum_mysql_ges = $strDatum_mysql[2].'-'.$strDatum_mysql[1].'-'.$strDatum_mysql[0].' '.$d[1];
			} else {
				$d    =    explode(" ",$_POST['liefer_datum']);
				$strDatum = $d[0];
				$strDatum_mysql = explode('.',$strDatum);
				$strDatum_mysql_ges = $strDatum_mysql[2].'-'.$strDatum_mysql[1].'-'.$strDatum_mysql[0].' 00:00:00';
			}
			$strLieferzeitpunk = $strDatum_mysql_ges;
			$strLieferZeitGesetzt = 'Y';
    	} else {
			$strLieferzeitpunk = date("Y-m-d H:i:s");
			$strLieferZeitGesetzt = 'N';
		}
		
		$query = "INSERT INTO shop_order (ges_order_status,ges_order_versandkosten,ges_order_endsumme,ges_order_customer_id,ges_order_gewicht,ges_order_artikelsumme,created_at,ges_order_anzahl,domain_id,bIsShopste,shop_order_nr_id,order_comment,lieferzeit_datum,lieferzeit_gesetzt) VALUES ('bestellt','".$shop_artikel_preisVersand."','".($shop_artikel_preisGes + $shop_artikel_preisVersand)."','".$iLastCustomerID."','".$shop_artikel_gewicht."','".$shop_artikel_preisGes."','".date('Y-m-d H:i:s')."','".$shop_artikel_anzahl."','".$ShopInfoData['domain_id']."','".$bIsShopste."','".$strOrderNrID."','".$_POST['txtLieferbemerkung']."','".$strLieferzeitpunk."','".$strLieferZeitGesetzt."');";
		DBi::$conn->query($query) or die(mysqli_error());
		$iLastOrderID = mysqli_insert_id(DBi::$conn);
		
		// Alle Artikel einfügen
		for($i=0; $i < count($strAry) -1; $i++) {
			$strItemDetailAry = explode("-",$strAry[$i]);


			#### EXTRA Eigenschaften aus der Bestellung ermitteln
			if(count($strItemDetailAry) > 3) { 			
				#echo print_r($strItemDetailAry).'<br/>';
				$strItemDetailAry[3] = mysql_real_escape_string($strItemDetailAry[3]);
				$shop_item_additional_types = explode("/",$strItemDetailAry[3]);
				if(count($strItemDetailAry) > 4) { 			
					$strBemerkung = ',Bemerkung: '.$strItemDetailAry[4];
				} else {
					$strBemerkung = '';
				}
			} else {
				#echo "a";
				$shop_item_additional_types = '';
				$strBemerkung ='';
			}
		
			# Gibt es zusätzliche Felder
			if(empty($shop_item_additional_types) == false) {			
				$strExtraName = ' ';
				if(is_array($shop_item_additional_types)) {
					for($zLoop=0; $zLoop < count($shop_item_additional_types); $zLoop++) {
						#echo $shop_item_additional_types[$zLoop].'<br/>';
						$query = "SELECT * FROM shop_item_additional_types WHERE shop_item_additional_types_id='".$shop_item_additional_types[$zLoop]."'";
						$resAdditionalTypes = DBi::$conn->query($query) or die(mysqli_error());
						$strAdditionalData = mysqli_fetch_assoc($resAdditionalTypes);
						$strExtraName .= $strAdditionalData['email_text'].',';
					}
				}
				
				if(strlen($strExtraName) > 1) {
					$strExtraName = substr($strExtraName,0,strlen($strExtraName) -1);
				} 
			} else {
				$strExtraName = '';
			}
		
			# Abrufen der Artikelinformationen
			$query = "SELECT * FROM shop_item JOIN shop_info ON shop_item.domain_id = shop_info.domain_id WHERE shop_item.shop_item_id='".$strItemDetailAry[0]."'";
			$resShopInfo = DBi::$conn->query($query) or die(mysqli_error());
			$ShopInfoData = mysqli_fetch_assoc($resShopInfo);
				
			$query = "INSERT INTO shop_order_list (shop_item_id,id_shop_order,order_menge,order_status,eiso_export,created_at,id_domain,name_de,preis,gewicht,shop_item_additional_info,shop_item_comment) VALUES ('".$strItemDetailAry[0]."','".$iLastOrderID."','".$strItemDetailAry[1]."','bestellt','N','".date("Y-m-d H:i:s")."','".$ShopInfoData['domain_id']."','".$ShopInfoData['name_de']."','".$ShopInfoData['preis']."','".$ShopInfoData['gewicht']."','".$strExtraName."','".$strBemerkung."');";
			DBi::$conn->query($query) or die(mysqli_error());
		}
	}
	
	// Email abschicken
	$strAry = explode("|",$_SESSION['shop_cart_ids']);
	$shop_artikel_anzahl = 0;
	
	// ###BESTELL_FIRMA###
	// ###BESTELL_STRASSE###
	$strEmailVorlage['content'] = str_replace('###SHOP_NAME###',$ShopInfoData['shop_name'],$strEmailVorlage['content']);
	$strEmailVorlage['content'] = str_replace('###BESTELL_VORNAME###',$_POST['txtVorname'],$strEmailVorlage['content']);
	$strEmailVorlage['content'] = str_replace('###BESTELL_NACHNAME###',$_POST['txtNachname'],$strEmailVorlage['content']);

	$strEmailVorlage['content'] = str_replace('###BESTELL_STRASSE###',$_POST['txtStrasse'],$strEmailVorlage['content']);
	$strEmailVorlage['content'] = str_replace('###BESTELL_PLZ###',$_POST['txtPLZ'],$strEmailVorlage['content']);
	$strEmailVorlage['content'] = str_replace('###BESTELL_ORT###',$_POST['txtOrt'],$strEmailVorlage['content']);
	$strEmailVorlage['content'] = str_replace('###BESTELL_LAND###',$_POST['txtLand'],$strEmailVorlage['content']);
	
	if($_POST['liefer_datum'] == '' && $_POST['liefer_zeit'] == '') {
		$strEmailVorlage['content'] = str_replace('###BESTELL_LIEFERZEIT###',"schnellst­m&ouml;glich",$strEmailVorlage['content']);
	} else {
		$strEmailVorlage['content'] = str_replace('###BESTELL_LIEFERZEIT###',$_POST['liefer_datum'].' '.$_POST['liefer_zeit'],$strEmailVorlage['content']);		
	}
	
	if($_POST['txtLieferbemerkung'] != '') {
		$strEmailVorlage['content'] = str_replace('###BESTELL_BEMERKUNG###','<strong>BEMERKUNG ZUR LIEFERUNG:</strong> '.$_POST['txtLieferbemerkung'].'<br/><br/>',$strEmailVorlage['content']);				
	} else {		
		$strEmailVorlage['content'] = str_replace('###BESTELL_BEMERKUNG###',"",$strEmailVorlage['content']);				
	}
	
	if($_POST['txtFirma'] != '') {
		
		$html_firma = '<tr>
		<td>Firma</td>
		<td>'.$_POST['txtFirma'].'</td>
	</tr>';	
	$strEmailVorlage['content'] = str_replace('###BESTELL_FIRMA###',$html_firma,$strEmailVorlage['content']);
	
	} else {
		$strEmailVorlage['content'] = str_replace('###BESTELL_FIRMA###','',$strEmailVorlage['content']);
	}
	
	# Telefon vorhanden
	if(!empty($_POST['txtTelefon'])) {
		$html_tele = '<tr>
			<td>Telefon</td><td>'.$_POST['txtTelefon'].'</td>
		</tr>';
		$strEmailVorlage['content'] = str_replace('###TELEFON###',$html_tele,$strEmailVorlage['content']);
	} else {
		$strEmailVorlage['content'] = str_replace('###TELEFON###','',$strEmailVorlage['content']);		
	}
	
	$shop_artikel_preis = 0.0;

	if($strSettings['isGericht'] == 'Y' || !empty($strSettings['isGericht'])) {
		$strTblName = 'Gericht';
	} else {
		$strTblName = 'Name';		
	}
	
	if($strSettings['hasPicture'] == 'N') {
		$html_bestellliste .= '<table width="100%"><tr><td><h3>Nr.</h3></td><td><h3>'.$strTblName.'</h3></td><td><h3>Menge</h3></td><td><h3>Einzelpreis</h3></td><td><h3>Gesamt</h3></td></tr>';
		$iColspan = 4;		
	} else {		
		$html_bestellliste .= '<table width="100%"><tr><td><h3>Nr.</h3></td><td><h3>Bild</h3></td><td><h3>'.$strTblName.'</h3></td><td><h3>Menge</h3></td><td><h3>Einzelpreis</h3></td><td><h3>Gesamt</h3></td></tr>';
		$iColspan = 5;
	}
	
	// Alle Artikel durchlaufen
	$shop_artikel_preisGes = 0;
	$shop_artikel_gewicht = 0;
	for($i=0; $i < count($strAry) -1; $i++) {
		$shop_artikel_anzahl++;
		
		// Artikel Details holen 
		$strItemDetailAry = explode("-",$strAry[$i]);
		
		//Artikel Menge Reduzieren
		$query = "UPDATE shop_item SET menge = (menge - ".$strItemDetailAry[1].") WHERE shop_item_id='".$strItemDetailAry[0]."'";
		DBi::$conn->query($query) or die(mysqli_error());
		
		$query ="SELECT *,shop_item.name_de as shop_name FROM shop_item JOIN menue ON shop_item.menue_id = menue.id WHERE shop_item.shop_item_id ='".$strItemDetailAry[0]."'";
		$resItem = DBi::$conn->query($query) or die(mysqli_error());
		$Cartdata = mysqli_fetch_assoc($resItem);
		
		$shop_artikel_preis = str_replace(",",".",$strItemDetailAry[2]);
		$shop_artikel_preisGes += $shop_artikel_preis * $strItemDetailAry[1];
		$shop_artikel_gewicht += $Cartdata['gewicht'] * $strItemDetailAry[1];	
		
		# Verschiedene Mehrwertsteuer speichern
		$var2 = ($Cartdata['item_mwst'] / 100) + 1;
		$shop_artikel_mwst[$Cartdata['item_mwst']] = (($shop_artikel_preisGes * $var2) - $shop_artikel_preisGes);
		
		
		#$shop_artikel_mwst[$Cartdata['item_mwst']] += (($shop_artikel_preisGes / 100) * $Cartdata['item_mwst']);
		
		$domain_id = $Cartdata['domain_id'];
		// Seite wo Artikel liegt abrufen
		$pathItem = 'http://'.$_SERVER['SERVER_NAME'].'/'.getPathUrl($_SESSION['language'],$Cartdata['menue_id']);
		
		$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$strItemDetailAry[0]."'";
		$strBild = mysqli_fetch_assoc(DBi::$conn->query($query));
		$strBild['picture_url'] = 'http://'.$_SERVER['SERVER_NAME'].'/'.$strBild['picture_url'];
		
		#$preis = str_replace(",",".",$strItemDetailAry[2]);
		$preisGes = $strItemDetailAry[1] * $shop_artikel_preis;
		
		#### EXTRA Eigenschaften aus der Bestellung ermitteln
		if(count($strItemDetailAry) > 3) { 			
			#echo print_r($strItemDetailAry).'<br/>';
			$strItemDetailAry[3] = mysql_real_escape_string($strItemDetailAry[3]);
			$shop_item_additional_types = explode("/",$strItemDetailAry[3]);
			if(count($strItemDetailAry) > 4) { 			
				$strBemerkung = ',Bemerkung: '.$strItemDetailAry[4];
			} else {
				$strBemerkung = '';
			}
		} else {
			#echo "a";
			$shop_item_additional_types = '';
			$strBemerkung ='';
		}
		
		# Gibt es zusätzliche Felder
		if(empty($shop_item_additional_types) == false) {			
			$strExtraName = ' ';
			if(is_array($shop_item_additional_types)) {
				for($zLoop=0; $zLoop < count($shop_item_additional_types); $zLoop++) {
					#echo $shop_item_additional_types[$zLoop].'<br/>';
					$query = "SELECT * FROM shop_item_additional_types WHERE shop_item_additional_types_id='".$shop_item_additional_types[$zLoop]."'";
					$resAdditionalTypes = DBi::$conn->query($query) or die(mysqli_error());
					$strAdditionalData = mysqli_fetch_assoc($resAdditionalTypes);
					$strExtraName .= $strAdditionalData['email_text'].',';
				}
			}
			
			if(strlen($strExtraName) > 1) {
				$strExtraName = substr($strExtraName,0,strlen($strExtraName) -1);
			} 
		} else {
			$strExtraName = '';
		}
		
		$html_bestellliste .= '<tr>
		<td>'.$Cartdata['item_number'].'</td>';
		if($strSettings['hasPicture'] == 'N') {
			
			$html_bestellliste .= '<td>'.$Cartdata['shop_name'].'<font size="1">'.$strExtraName.''.$strBemerkung.'</font></td>';
		} else {
			$html_bestellliste .= '<td><img src="'.$strBild['picture_url'].'" width="50px" Height="50px"/></a></td>';
			$html_bestellliste .= '<td><a href="'.$pathItem.'">'.$Cartdata['shop_name'].'</a><font size="1">'.$strExtraName.'</font></td>';
		}
		
		$html_bestellliste .= '<td>'.$strItemDetailAry[1].'</td>';
		$html_bestellliste .= '<td>'.number_format($shop_artikel_preis, 2, ',', '.').' EUR</td>';
		$html_bestellliste .= '<td>'.number_format($preisGes, 2, ',', '.').' EUR</td>';
		$html_bestellliste .= '<tr>';
	}
	#$html_bestellliste .= '<tr><td colspan="5" style="text-align:right">Zwischensumme</td><td>'.str_replace(".",",",$shop_artikel_preisGes).' EUR</td><td>&nbsp;</td></tr>';
	$html_bestellliste .= $strVersandHTML;
	$shop_artikel_gesamt = $shop_artikel_preisGes + $shop_artikel_preisVersand;
			
	$query ="SELECT * FROM domains WHERE domain_id = $domain_id";
	$res = DBi::$conn->query($query);
	$domain_data = mysqli_fetch_assoc($res);
	#Mehrwertsteuer vom Versand
	$shop_artikel_mwst[$shop_artikel_preisVersand_mwst] += (($shop_artikel_preisVersand / 100) * $shop_artikel_preisVersand_mwst);
	
	if(empty($strSettings['cart_show_mwst']) || $strSettings['cart_show_mwst'] == 'Y') {
		#'MwSt_inkl','MwSt_exkl','MwSt_befreit'
		switch($domain_data['shop_mwst_setting']) {
			case "MwSt_inkl":
				$strMWSTText = 'inkl. MwSt.';
				foreach ($shop_artikel_mwst as $key => $value) {
					if($key != '') {
						$html_bestellliste .= '<tr><td colspan="'.$iColspan.'" style="text-align:right"><strong>Mehrwertsteuer '.$strMWSTText.' '.$key.'%</strong></td><td>'.number_format($value, 2, ',', '.').' EUR</td><td>&nbsp;</td></tr>';
						$cart_mwst_ges = 0;
					}
				}
				break;
			case "MwSt_exkl":
				$strMWSTText = 'exkl. MwSt.';
				foreach ($shop_artikel_mwst as $key => $value) {
					if($key != '') {
						$html_bestellliste .= '<tr><td colspan="'.$iColspan.'" style="text-align:right"><strong>Mehrwertsteuer '.$strMWSTText.' '.$key.'%</strong></td><td>'.number_format($value, 2, ',', '.').' EUR</td><td>&nbsp;</td></tr>';
						$cart_mwst_ges += $value;
					}
				}
				break;
			case "MwSt_befreit":
				$strMWSTText = '';
				$html_bestellliste .= '<tr><td colspan="'.$iColspan.'" style="text-align:right"><strong>Kleinunternehmer</strong></td><td>Mehrwertsteuer befreit</td></tr>';
				$cart_mwst_ges = 0;
				break;
			case "MwSt_privatverkauf":
				$strMWSTText = '';
				#$html .= '<tr><td colspan="4" style="text-align:right">Mehrwertsteuer befreit </td><td>&nbsp;</td></tr>';
				$html .= '<tr><td colspan="'.$iColspan.'" style="text-align:right"><strong>Keine Mehrwertsteuer</strong></td><td colspan="2">Privatverkauf</td></tr>';
				$cart_mwst_ges = 0;
				break;					
		}	
	}	 
	
	if(strlen($strSettings['cart_show_endsumme_text']) > 0 || !empty($strSettings['cart_show_endsumme_text'])) {
		$strCartEndsummeText = $strSettings['cart_show_endsumme_text'];
	} else {
		$strCartEndsummeText = 'Endsumme';		
	}
	$html_bestellliste .= '<tr><td colspan="'.$iColspan.'" style="text-align:right"><h2>'.$strCartEndsummeText.'</h2></td><td><h2>'.number_format(($shop_artikel_preisGes + $shop_artikel_preisVersand + $cart_mwst_ges), 2, ',', '.').' EUR</h2></td><td>&nbsp;</td></tr></table>';
	
	$strEmailVorlage['content'] = str_replace('###BESTELLUNG_AUFLISTUNG####',$html_bestellliste,$strEmailVorlage['content']);
	
	
	$path = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once $path.'/framework/phpmailer/PHPMailerAutoload.php';

	//Create a new PHPMailer instance
	$mail = new PHPMailer();
	$mail->CharSet = 'utf-8'; 
	// Set PHPMailer to use the sendmail transport
	$mail->isSendmail();
	//Set who the message is to be sent from
	$mail->setFrom($ShopInfoData['email_shop_main'],$ShopInfoData['email_shop_main_form_name']);
	//Set an alternative reply-to address
	$mail->addReplyTo($ShopInfoData['email_shop_main'],$ShopInfoData['email_shop_main_form_name']);
	//Set who the message is to be sent to
	$mail->addAddress($_POST['txtEmail'], $_POST['txtVorname'].' '.$_POST['txtNachname']);
	
	$mail->AddBCC($ShopInfoData['email_shop_main'],$ShopInfoData['email_shop_main_form_name']);
	$mail->AddBCC("shopste-dev@shopste.com","Shopste (Kopie) Shop Bestellung");

	//###BESTELL_NACHNAME###
	//###BESTELL_VORNAME###
	//###SHOPNAME###
	//
	$strEmailVorlage['betreff'] = str_replace('###SHOPNAME###',$ShopInfoData['shop_name'],$strEmailVorlage['betreff']);
	$strEmailVorlage['betreff'] = str_replace('###BESTELL_VORNAME###',$_POST['txtVorname'],$strEmailVorlage['betreff']);
	$strEmailVorlage['betreff'] = str_replace('###BESTELL_NACHNAME###',$_POST['txtNachname'],$strEmailVorlage['betreff']);
	
	$mail->Subject = htmlspecialchars($strEmailVorlage['betreff']);
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	$html = $strEmailVorlage['content'];
	#echo $html;
	$mail->msgHTML($html, dirname(__FILE__));   
	//Replace the plain text body with one created manually
	$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';
	//Attach an image file
	//$mail->addAttachment('images/phpmailer_mini.png');

	//send the message, check for errors
	if (!$mail->send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo '<div id="shop_footer"><div id="frame_detail_info">Bestellung abgeschickt!</div></div>';
	
	$query = "SELECT * from domains WHERE domain_id='".$_SESSION['domain_id']."'";			
	$resDomainData = DBi::$conn->query($query) or die(mysqli_error());
	$domain_pages = mysqli_fetch_assoc($resDomainData);
			
	if($domain_pages['isRestaurant'] == 'Y' && $domain_pages['isSendRechnung_at_order'] == 'Y' ) {
		$query ="SELECT count(*) as anzahl FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='RECHNUNG'";
		#echo $query;
		$resEmailCount = DBi::$conn->query($query) or die(mysqli_error());
		$strEmailCount = mysqli_fetch_assoc($resEmailCount);
		if($strEmailCount['anzahl'] > 0) {
			# Lade Benutzervorlage
			$query ="SELECT * FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='RECHNUNG'";
			#echo $query;
			$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error());
			$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
		} else {
			# Lade Defaultvorlage
			$query ="SELECT * FROM email_vorlage WHERE domain_id='0' AND standard='Y' AND typ='RECHNUNG'";
			#echo $query;
			$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error());
			$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
		}
		
		$query ="SELECT count(*) as anzahl FROM email_vorlage_settings WHERE vorlagen_id='".$strEmailVorlage['email_vorlage_id']."'";
		$resEmailSettings = DBi::$conn->query($query) or die(mysqli_error());
		$strSettings = mysqli_fetch_assoc($resEmailSettings);
		if($strSettings['anzahl'] > 0) {
			$query ="SELECT * FROM email_vorlage_settings WHERE vorlagen_id='".$strEmailVorlage['email_vorlage_id']."'";
			$resEmailSettings = DBi::$conn->query($query) or die(mysqli_error());
			$strSettings = mysqli_fetch_assoc($resEmailSettings);
			$bEmailSettings = true;
			$iColspan = 5;
		} else {
			$bEmailSettings = false;
			$iColspan = 5;		
		}
		
		$html_rechnung = $strOrderAnzahl['anzahl'];
		
			$strEmailVorlage['content'] = str_replace('###SHOP_NAME###',$ShopInfoData['shop_name'],$strEmailVorlage['content']);
	$strEmailVorlage['content'] = str_replace('###BESTELL_VORNAME###',$_POST['txtVorname'],$strEmailVorlage['content']);
	$strEmailVorlage['content'] = str_replace('###BESTELL_NACHNAME###',$_POST['txtNachname'],$strEmailVorlage['content']);
	$strEmailVorlage['content'] = str_replace('###RDATUM###',date("d.m.Y"),$strEmailVorlage['content']);

	$strEmailVorlage['content'] = str_replace('###BESTELL_STRASSE###',$_POST['txtStrasse'],$strEmailVorlage['content']);
	$strEmailVorlage['content'] = str_replace('###BESTELL_PLZ###',$_POST['txtPLZ'],$strEmailVorlage['content']);
	$strEmailVorlage['content'] = str_replace('###BESTELL_ORT###',$_POST['txtOrt'],$strEmailVorlage['content']);
	$strEmailVorlage['content'] = str_replace('###BESTELL_LAND###',$_POST['txtLand'],$strEmailVorlage['content']);
		if($_POST['txtFirma'] != '') {
		
		$html_firma = $_POST['txtFirma'].'<br/>';	
		$strEmailVorlage['content'] = str_replace('###BESTELL_FIRMA###',$html_firma,$strEmailVorlage['content']);
	
	} else {
		$strEmailVorlage['content'] = str_replace('###BESTELL_FIRMA###','',$strEmailVorlage['content']);
	}
	if($_POST['liefer_datum'] == '' && $_POST['liefer_zeit'] == '') {
		$strEmailVorlage['content'] = str_replace('###BESTELL_LIEFERZEIT###',"schnellst­m&ouml;glich",$strEmailVorlage['content']);
	} else {
		$strEmailVorlage['content'] = str_replace('###BESTELL_LIEFERZEIT###',$_POST['liefer_datum'].' '.$_POST['liefer_zeit'],$strEmailVorlage['content']);		
	}
	
	if($_POST['txtLieferbemerkung'] != '') {
		$strEmailVorlage['content'] = str_replace('###BESTELL_BEMERKUNG###','<strong>BEMERKUNG ZUR LIEFERUNG:</strong> '.$_POST['txtLieferbemerkung'].'<br/>',$strEmailVorlage['content']);				
	} else {		
		$strEmailVorlage['content'] = str_replace('###BESTELL_BEMERKUNG###',"",$strEmailVorlage['content']);				
	}
	if(!empty($_POST['txtTelefon'])) {
		$html_tele = $_POST['txtTelefon'].'<br/>';
		$strEmailVorlage['content'] = str_replace('###TELEFON###',$html_tele,$strEmailVorlage['content']);
	} else {
		$strEmailVorlage['content'] = str_replace('###TELEFON###','',$strEmailVorlage['content']);		
	}

		$strEmailVorlage['content'] = str_replace('###RNR###',$html_rechnung,$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###BESTELLUNG_AUFLISTUNG####',$html_bestellliste,$strEmailVorlage['content']);
	
		//Create a new PHPMailer instance
		$mail = new PHPMailer();
		$mail->CharSet = 'utf-8'; 
		// Set PHPMailer to use the sendmail transport
		$mail->isSendmail();
		//Set who the message is to be sent from
		$mail->setFrom($ShopInfoData['email_shop_main'],$ShopInfoData['email_shop_main_form_name']);
		//Set an alternative reply-to address
		$mail->addReplyTo($ShopInfoData['email_shop_main'],$ShopInfoData['email_shop_main_form_name']);
		//Set who the message is to be sent to
		#$mail->addAddress("jbludau@cubss.net",$ShopInfoData['email_shop_main_form_name']);
		$mail->addAddress($ShopInfoData['email_shop_main'],$ShopInfoData['email_shop_main_form_name']);
		#$mail->addAddress($_POST['txtEmail'], $_POST['txtVorname'].' '.$_POST['txtNachname']);
		
		$mail->AddBCC("jbludau@shopste.com","Shopste (Kopie) Shop Bestellung");

		//###BESTELL_NACHNAME###
		//###BESTELL_VORNAME###
		//###SHOPNAME###
		//###RNR###
		$strEmailVorlage['betreff'] = str_replace('###RNR###',$html_rechnung,$strEmailVorlage['betreff']);
		$strEmailVorlage['betreff'] = str_replace('###SHOPNAME###',$ShopInfoData['shop_name'],$strEmailVorlage['betreff']);
		$strEmailVorlage['betreff'] = str_replace('###BESTELL_VORNAME###',$_POST['txtVorname'],$strEmailVorlage['betreff']);
		$strEmailVorlage['betreff'] = str_replace('###BESTELL_NACHNAME###',$_POST['txtNachname'],$strEmailVorlage['betreff']);
		
		$mail->Subject = htmlspecialchars($strEmailVorlage['betreff']);
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$html = $strEmailVorlage['content'];
		#echo $html;
		$mail->msgHTML($html, dirname(__FILE__));   
		//Replace the plain text body with one created manually
		$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';
		//Attach an image file
		//$mail->addAttachment('images/phpmailer_mini.png');

		//send the message, check for errors
		if (!$mail->send()) {
			
		}
	}
		if($domain_pages['isRestaurant'] == 'Y') {
			$strDiaglog = "dialog-bestellung-ende";
			$strBR = "<br/><br/><br/>";
		} else {
			$strDiaglog = "dialog-form";			
			$strBR = "<br/>";
		}
		
		echo '<div id="'.$strDiaglog.'" title="Bestellung abgeschlossen">';
		
		
			if(!empty($_POST['liefer_datum'])  && !empty($_POST['liefer_zeit'])) {
				$strDate = $_POST['liefer_datum'].' '.$_POST['liefer_zeit']. ' Uhr ';
			} else {
				$strDate = '';			
			}
			echo $strBR.'Vielen Dank f&uuml;r Ihre Bestellung!<br/>Ihre abgeschlossene '.$strDate.' Bestellung wurde per Email an Sie weitergeleitet.'; 
		
		echo '</div>';
		
		echo "<script>$(document).ready(function() {	 
 dialog = $( \"#".$strDiaglog."\" ).dialog({
	    show: {
                 effect: \"fade\",
                 duration: 500
        },
		height: 300,
        width: 500,
 });
 dialog.dialog( \"open\" );
 });</script>";
		
		/*echo '<script>';
		echo '$("#footer").css("overflow-x", "visible");
				var scrollTop = parseInt($(\'html\').css(\'top\'));
		$(\'html\').removeClass(\'noscroll\');
		$(\'html,body\').scrollTop(-scrollTop);';
		echo '</script>';*/
		// $bClearSession = true;
		
		// $query = "SELECT * FROM domain_settings WHERE domain_id='".$domain_id."'";
		// $resSetting = DBi::$conn->query($query) or die(mysqli_error());
		// while($strDomainSetting = mysqli_fetch_assoc($resSetting)) {
			// if($strDomainSetting['EISO_IMPORT'] == 'Y') {
				// $bClearSession = false;
			// }
		// }
		// if($bClearSession == true) {
			$_SESSION['shop_cart_ids'] = '';
		//}
	}
 ?>