<?php
	session_start();
	include_once('../include/inc_config-data.php');
	include_once('../include/inc_basic-functions.php');

####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_shop_cart1_order1($config) {

		$dataTextHTML = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_shop_cart_order WHERE id=".$config['modul_id']));
		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='shop_cart_order'"));
		#echo "IN";
		
		$dataTextHTML['typ'] = 'shop_cart_order';
		
		#$text = '<div class="zur_kasse content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
		$text = '<div class="zur_kasse content" id="modul_oder_frame_unten">';
		
		$text .= convertUmlaute($dataTextHTML["content_".$_SESSION['language']]);
		$titel = convertUmlaute($dataTextHTML["title_".$_SESSION['language']]);
		
			$query = "SELECT * from domains WHERE domain_id='".$_SESSION['domain_id']."'";			
			$resDomainData = DBi::$conn->query($query) or die(mysqli_error());
			$domain_pages = mysqli_fetch_assoc($resDomainData);
		
		if($text == '') {   
			$text = convertUmlaute($dataTextHTML["content_de"]); 
		} 
		
		if($titel == '') { 
			$titel = convertUmlaute($dataTextHTML["title_de"]); 
		} 
		
		// && $config["container"]
		if($_SESSION['login'] == '1'  AND $module_in_menue['container'] == 'col-main') {
			$strReturn = getMember($dataTextHTML['last_usr']);
			if(!empty($strReturn)) {
				$ary = explode(" ",$dataTextHTML['lastchange']);
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
		$strAry = explode("|",$_SESSION['shop_cart_ids']);
		 
		
		if (count($strAry) == 0) {
			$text .= '<b>Bitte erst Artikel sammeln!</b>';
		} else {
		
			if($_GET['status'] == 'sended') {
				$text .= '<div id="popup_bestellung_done">Vielen Dank f&uuml;r Ihre Bestellung!<br/>Ihr Warenkorb wurde per Email abgeschickt und die Bestellung ist abgeschlossen.</div>';
				$text .= "<script> $('#popup_bestellung_done').bPopup();</script>";
			} else {
				$text .= '<form name="frmCartOrder" id="frmCartOrder" action="/cart/cart_order_adress.php" method="POST" onSubmit="return cart_order_send(\'frmCartOrder\',\''.$config['typ'].'\',\''.$config['modul_id'].'\');" style="margin-left: 13px;">';
				
				
				
				//if($dataTextHTML['option_lieferzeit'] == 'Y') {
					#$text .= '<div class="label">Lieferzeit</div>';
					#$text .= '<div><input type="text" placeholder="Lieferzeit dd.mm.yyyy std:min" data-constraints="@Required" value="'.$_SESSION['order_lieferzeit'].'" name="txtLieferzeit" id="txtLieferzeit" class="input reg"/></div>';
					$text .= '<div style="clear:both"></div>';				
				//}
				
				$text .= '<div class="row"><div class="grid_6"><input type="text" placeholder="Vorname*" data-constraints="@Required" value="'.$_SESSION['order_vorname'].'" name="txtVorname" id="txtVorname" class="input reg"/><span class="frm_error" id="txtVorname_err"></span>';
				$text .= '</div>';
				
				#$text .= '<div class="label">Nachname*</div>';
				$text .= '<div class="grid_6"><input type="text" placeholder="Nachname*" data-constraints="@Required" value="'.$_SESSION['order_nachname'].'" name="txtNachname" id="txtNachname" class="input reg"/><span class="frm_error" id="txtNachname_err"></span>';
				$text .= '</div></div>';
				
				#$text .= '<div class="label">Firma</div>';
				$text .= '<div class="row"><div class="grid_6"><input type="text" placeholder="Firma" data-constraints="@Required" value="'.$_SESSION['order_firma'].'" name="txtFirma" id="txtFirma" class="input reg"/>';
				$text .= '</div>
				<div class="grid_6">';
				$text .= '<input type="text" placeholder="Email*" data-constraints="@Required" value="'.$_SESSION['order_email'].'" name="txtEmail" id="txtEmail" class="input reg"/><span class="frm_error" id="txtEmail_err"></span>';
				$text .= '</div>
				</div>';
				$text .= '<div class="row"><div class="grid_6">';
				$text .= '<input type="text" placeholder="Telefon" data-constraints="@Required" value="'.$_SESSION['order_telefon'].'" name="txtTelefon" id="txtTelefon" class="input reg"/><span class="frm_error" id="txtTelefon_err"></span>';
				$text .= '</div><div class="grid_6"><input type="text" placeholder="Stra&szlig;e + Hausnummer*" data-constraints="@Required" value="'.$_SESSION['order_strasse'].'" name="txtStrasse" id="txtStrasse" class="input reg"/><span class="frm_error" id="txtStrasse_err"></span></div></div>';
 
				$text .= '<div class="row">';
 
				#$text .= '<div class="label">PLZ*</div>';
				$text .= '<div class="grid_6">
				<input type="text" placeholder="Postleitzahl*" data-constraints="@Required" value="'.$_SESSION['order_plz'].'" name="txtPLZ" id="txtPLZ" class="input reg"/><span class="frm_error" id="txtPLZ_err"></span></div><div  class="grid_6"><input type="text" placeholder="Ort*" data-constraints="@Required" value="'.$_SESSION['order_ort'].'" name="txtOrt" id="txtOrt" class="input reg"/><span class="frm_error" id="txtOrt_err"></span></div>';
				$text .= '</div>';
				#$text .= '<div class="label">Ort*</div>';
				$text .= '<div class="row">';
				#$text .= '<div style="clear:both"></div>';
				#$text .= '<div class="label">Staat*</div>';
				$text .= '<div  class="grid_6"><input type="hidden" name="txtLand" id="txtLand" value="Deutschland" class="input reg"/><span class="frm_error" id="txtLand_err"></span></div></div>';
				#$text .= '<div style="clear:both"></div>';
				#$text .= '<div class="label">AGB</div>';
	
				#$text .= '<div class="label">Widerruf</div>';
				#$pathWiderruf = getPathUrl($_SESSION['language'],$domain_pages['widerruf']);
				
				#$text .= '<div  style="float:left;"><label><input type="checkbox" value="widerruf_ok" name="chkWiderruf" id="chkWiderruf"> Widerruf akzeptieren</label> <a href="#" target="_blank">lesen</a>* <span class="frm_error" id="chkWiderruf_err"></span></div>'; 
				#$text .= '<div style="clear:both"></div><br/>';						
				
				$text .= '<div class="row"><p id="basicExample">
    <div class="grid_6"><input type="text" id="datepicker" placeholder="Lieferdatum (leer = sofort)" name="liefer_datum"></div> 
	<div class="grid_6"><input type="text" id="timepicker1" placeholder="Lieferuhrzeit (leer = sofort)" name="liefer_zeit"></div>
</p></div>';
				$text .="               <script>
                 $(function() {
                     $( \"#datepicker\" ).datepicker({
       prevText: '&#x3c;zurück', prevStatus: '',
        prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
        nextText: 'Vor&#x3e;', nextStatus: '',
        nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
        currentText: 'heute', currentStatus: '',
        todayText: 'heute', todayStatus: '',
        clearText: '-', clearStatus: '',
        closeText: 'schließen', closeStatus: '',
        monthNames: ['Januar','Februar','März','April','Mai','Juni',
        'Juli','August','September','Oktober','November','Dezember'],
        monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun',
        'Jul','Aug','Sep','Okt','Nov','Dez'],
        dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
        dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
        dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
      showMonthAfterYear: false,
      showOn: 'focus',
	  showWeek: false,
	  showButtonPanel:  false,
	  changeMonth: false,
	  changeYear: false,
setDate:new Date(),
      dateFormat:'d.m.yy'
    } );    
                 }); 
                 $(function() {
$('#timepicker1').timepicker({ 'timeFormat': 'G:i','scrollDefault': 'now','showDuration': false,'step': 15,'maxTime': '22:30','minTime':'12:00','useSelect':false });
$('#timepicker1').on(\"selectTime\", function() {
	var time = $('#timepicker1').val();
	var timeparts = time.split(':');
	if((timeparts[0] >= '15') && (timeparts[0] < '18')) {
		alert('Restaurant Mekong ist um ' + time + ' von 15 Uhr - 18 Uhr in der Mittagspause, bitte andere Uhrzeit selektieren!\\n ');	
		$('#timepicker1').val('');		
	}
});
                 });
                </script>";
				$text .= '<div class="row">
							<div class="grid_6"><input style="width:465px" type="text" id="lieferbemerkung" placeholder="Bemerkung zur Lieferung..." name="txtLieferbemerkung"></div> 
					</div>';

				$pathAGB = getPathUrl($_SESSION['language'],$domain_pages['agb_id']);
				
				$text .= '<div style="float:left;padding-top: 6px;padding-bottom: 6px;"><label><input type="checkbox" value="agb_ok" name="chkAGB" id="chkAGB">AGB akzeptieren</label> <a href="http://www.restaurantmekong.de/de/36804/AGB/" target="_blank">lesen</a>* <span class="frm_error" id="chkAGB_err"></span><br/></div>';
				$text .= '<div style="clear:both"></div>';		
				
				#$text .= '<div><input type="button" class="button" name="btnWeiterShoppen" onClick="mini_frame_close()" value="weitere Gerichte"/></div>';
				$text .= '<input type="submit" class="button" name="btnSenden" value="zahlungspflichtig bestellen"/><div style="clear:both"></div>';
				$text .= '</form>';
			}
		}
		$text .= '</div>'; // config modus 

		
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 } 
 
####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_shop_cart1($config) {

		$dataTextHTML = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_shop_cart WHERE id=".$config['modul_id']));
		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='shop_cart'"));
		#echo "IN";
		
		$dataTextHTML['typ'] = 'shop_cart';
		
		#$text = '<div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
		$text = '<div class="content" id="modul_cart_frame_unten">';
		
		$text .= convertUmlaute($dataTextHTML["content_".$_SESSION['language']]);
		$titel = convertUmlaute($dataTextHTML["title_".$_SESSION['language']]);
		

		
		if($text == '') {   
			$text = convertUmlaute($dataTextHTML["content_de"]); 
		} 
		
		if($titel == '') { 
			$titel = convertUmlaute($dataTextHTML["title_de"]); 
		} 
		
		// && $config["container"]
		if($_SESSION['login'] == '1'  AND $module_in_menue['container'] == 'col-main') {
			$strReturn = getMember($dataTextHTML['last_usr']);
			if(!empty($strReturn)) {
				$ary = explode(" ",$dataTextHTML['lastchange']);
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
		
		##############################
		# >> Inline suche
		##############################
		#$text = stringToFunction($text);
 
		
		$strAry = explode("|",$_SESSION['shop_cart_ids']);
		$shop_artikel_anzahl = 0;
		
		$shop_artikel_preis = 0.0;
		#$html .=  '<span id="frame_close" class="spanlink_frame" onClick="mini_frame_close()" style="float:left;">Fenster Schlie&szlig;en</span>';
		$html .= '<table cellspacing="10" cellpadding="10" class="warenkorb_frame_tbl" width="100%">';
		
		$html .= '<tr>
		<td width="7%"><h3>Nr.</h3></td>
		<td width="41%"><h3>Gericht</h3></td>
		<td width="7%" class="td_center"><h3>Menge</h3></td>
		<td width="25%" style="padding-left:20px"><h3>Aktion</h3></td>
		<td width="10%" class="td_right"><h3>Preis</h3></td>
		<td width="10%" class="td_right"><h3>Gesamt</h3></td></tr>';
		
		// Alle Artikel durchlaufen
		$tmpVersandkosten = '';
		#echo count($strAry);
		for($i=0; $i < count($strAry) -1; $i++) {
			$shop_artikel_anzahl++;
			
			// Artikel Details holen 
			$strItemDetailAry = explode("-",$strAry[$i]);
			#echo $strItemDetailAry[0].'-----';
			// SQL Injection vorbeugen
			$strItemDetailAry[0] = mysql_real_escape_string($strItemDetailAry[0]);
			$strItemDetailAry[1] = mysql_real_escape_string($strItemDetailAry[1]);
			$strItemDetailAry[2] = mysql_real_escape_string($strItemDetailAry[2]);
			if(count($strItemDetailAry) > 3) { 			
				#echo print_r($strItemDetailAry).'<br/>';
				$strItemDetailAry[3] = mysql_real_escape_string($strItemDetailAry[3]);
				$shop_item_additional_types = explode("/",$strItemDetailAry[3]);
				if(count($strItemDetailAry) > 4) { 
					$strBemerkung = ', Bemerkung: '.$strItemDetailAry[4];
				}  else {
					$strBemerkung = '';
				}
			} else {
				#echo "a";
				$shop_item_additional_types = '';
				$strBemerkung = '';
			}
			   
			
			$query ="SELECT *,shop_item.name_de as shop_name FROM shop_item JOIN menue ON shop_item.menue_id = menue.id WHERE shop_item.shop_item_id ='".$strItemDetailAry[0]."'";
			$resItem = DBi::$conn->query($query) or die(mysqli_error());
			$Cartdata = mysqli_fetch_assoc($resItem);
			
			$shop_artikel_preis = str_replace(",",".",$strItemDetailAry[2]);
			$shop_artikel_preisGes += $shop_artikel_preis * $strItemDetailAry[1];
			$shop_artikel_gewicht += $Cartdata['gewicht'] * $strItemDetailAry[1];
			
			# Höchste Versandkosten finden
			if($tmpVersandkosten < $Cartdata['versandkosten']) {
				$tmpVersandkosten = $Cartdata['versandkosten'];
			}
			# Verschiedene Mehrwertsteuer speichern
			$shop_artikel_mwst[$Cartdata['item_mwst']] += (($shop_artikel_preisGes / 100) * $Cartdata['item_mwst']);
			
			$domain_id = $Cartdata['domain_id'];
			
			/* # Bild laden im Warenkorb
			$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$strItemDetailAry[0]."' AND picture_nr=1";
			#echo $query;
			$resPictures = DBi::$conn->query($query) or die(mysqli_error());
			$iCount = 0;
			#$text .= '<h1>'.$ShopItem['name_de'].'</h1>';
			#$text .= '<div id="shop_item_picture_box">';			
			while($strPic = mysqli_fetch_assoc($resPictures)) {
				if($iCount ==0) {
					if($strPic['picture_url'] != '') {
						$strBild = '<img height="75x" width="75px" src="'.$strPic['picture_url'].'"/>';
						#echo $strBild;
					} else {
						$strBild = "&nbsp;";
					}
				}  
				$iCount++;
			} */
		
			// Seite wo Artikel liegt abrufen
			$pathItem = getPathUrl($_SESSION['language'],$Cartdata['menue_id']);
			$preisGes = $strItemDetailAry[1] * $shop_artikel_preis;
			#echo $strItemDetailAry[1];

			## Additional Eigenschaften laden
			if(empty($shop_item_additional_types) == false) {			
				$strExtraName = '<br/>';
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
			
			$html .= '<tr>';
			#$html .= '<td>'.$strBild.'</td>';
			$html .= '<td>'.$Cartdata['item_number'].'</td>';
			$html .= '<td>'.$Cartdata['shop_name'].'<font size="1">'.$strExtraName.''.$strBemerkung.'</font></td>';			
			$html .= '<td class="td_center">x<span id="warenkorb_frame_menge_'.$strItemDetailAry[0].'">'.$strItemDetailAry[1].'</span></td>';
				
			$html .= '<td style="padding-left:20px"><a class="spanlink_frame_plus_minus" onClick="shop_cart_change_menge(\''.$strItemDetailAry[0].'\',\'minus\',\''.$_POST['bWarenkorbOnly'].'\')">
			<img height="20" width="20" src="/templates/mekong/images/Mekong_Web_Symbole_--02.png"/></a> 
			<a class="spanlink_frame_plus_minus" onClick="shop_cart_change_menge(\''.$strItemDetailAry[0].'\',\'plus\',\''.$_POST['bWarenkorbOnly'].'\')">
			<img height="20" width="20" src="/templates/mekong/images/Mekong_Web_Symbole_+-01.png"/></a> 
			<a class="spanlink_frame" onClick="shop_cart_delete_frame(\''.$strItemDetailAry[0].'\',\'shop_cart\',\''.$config['modul_id'].'\',\''.$_POST['bWarenkorbOnly'].'\')">
			<img height="20" width="20" src="/templates/mekong/images/Mekong_Web_Symbole_X-03.png"/></a>
			<a class="spanlink_frame" onClick="showPopup_shop_item_customize(\''.$strItemDetailAry[0].'\',\'shop_cart\',\''.$config['modul_id'].'\',\''.$_POST['bWarenkorbOnly'].'\')"><img height="20" width="75" src="/templates/mekong/images/Mekong_Web_Symbole_Extrawunsch-04.png"/></a>
			</td>';

			
			$html .= '<td class="td_right">'.number_format($shop_artikel_preis, 2, ',', '.').' &euro;</td>';
			$html .= '<td class="td_right">'.number_format($preisGes, 2, ',', '.').' &euro;</td></tr>';
			
		}
		$Cartdata['versandkosten'] = $tmpVersandkosten;
		
		if ($shop_artikel_anzahl == 0) {
			$html = '<b>Warenkorb ist leer!</b>';
		} else {
			$html .= '<tr><td colspan="6" style="text-align:right">&nbsp;</td></tr>';
			$query = "SELECT * from domains WHERE domain_id='".$_SESSION['domain_id']."'";			
			$resDomainData = DBi::$conn->query($query) or die(mysqli_error());
			$domain_pages = mysqli_fetch_assoc($resDomainData);
			
			$query = "SELECT * FROM shop_shippment_detail WHERE shop_shippment_detail.gewicht_von <= '".$shop_artikel_gewicht."' AND shop_shippment_detail.gewicht_bis>= '".$shop_artikel_gewicht."' AND  domain_id='".$_SESSION['domain_id']."'";
			$resVersandkostenPreis = DBi::$conn->query($query) or die(mysqli_error());
			$shop_artikel_preisVersand = 0;
			$bVersandartGefunden = false;
			while($strVersandKosten = mysqli_fetch_assoc($resVersandkostenPreis)) {
				$strVersandHTML .= '<tr><td colspan="4" style="text-align:right">Gewicht</td><td>'.str_replace('.',',',$shop_artikel_gewicht).' KG</td><td>&nbsp;</td></tr>';
				
				$query ="SELECT * FROM shop_shippment WHERE shop_shippment_id='".$strVersandKosten['shop_shippment_id']."'";
				$resVersandArt = DBi::$conn->query($query);
				$strVersandKostenName = mysqli_fetch_assoc($resVersandArt);
				
				
				$strVersandHTML .= '<tr><td colspan="4" style="text-align:right">Versandkosten '.$strVersandKostenName['name_de'].'</td><td>'.str_replace('.',',',$strVersandKosten['versandkosten']).' &euro;</td><td>&nbsp;</td></tr>';
				$shop_artikel_preisVersand = $strVersandKosten['versandkosten'];
				$shop_artikel_preisVersand_mwst = $strVersandKostenName['mwst'];
				$bVersandartGefunden = true;
			}
			
			#$strVersandHTML .= '<tr><td colspan="4" style="text-align:right"><strong>Zwischensumme</strong></td><td colspan="2">'.str_replace('.',',',$shop_artikel_preisGes).' EUR</td></tr>';
			
		
				
		if($bVersandartGefunden == false) {
			if($Cartdata['versandkosten'] != '') {
				$shop_artikel_preisVersand = $Cartdata['versandkosten'];
				if(!empty($Cartdata['versandkosten_name'])) {
			
					$strVersandHTML .= '<tr><td colspan="5" style="text-align:right"><strong>Gewicht</strong></td><td colspan="2">'.str_replace('.',',',$shop_artikel_gewicht).' KG</td></tr>';	
					$strVersandHTML .= '<tr><td colspan="2" style="text-align:right"><strong>Lieferkosten</strong></td><td class="td_right">0,00 &euro;</td></tr>';
				} else {
					#$strVersandHTML = '<tr><td colspan="5" style="text-align:right"><strong>Zwischensumme</strong></td><td colspan="2">'.number_format($shop_artikel_preisGes, 2, ',', '.').' EUR</td></tr>';
					$strVersandHTML .= '<tr><td colspan="5" style="text-align:right"><strong>Lieferkosten</strong></td><td class="td_right">0,00 &euro;</td></tr>';
				}
			} else {				
				
				#$strVersandHTML = '<tr><td colspan="5" style="text-align:right"><strong>Zwischensumme</strong></td><td colspan="2">'.number_format($shop_artikel_preisGes, 2, ',', '.').' EUR</td></tr>';
				$strVersandHTML .= '<tr><td colspan="5" style="text-align:right"><strong>Lieferkosten</strong></td><td class="td_right">0,00 &euro;</td></tr>';
			}
		} else {
			if($Cartdata['versandkosten'] != '') {
				$shop_artikel_preisVersand = $Cartdata['versandkosten'];
				if(!empty($Cartdata['versandkosten_name'])) {
					#$strVersandHTML = '<tr><td colspan="5" style="text-align:right"><strong>Zwischensumme</strong></td><td colspan="2">'.number_format($shop_artikel_preisGes, 2, ',', '.').' EUR</td></tr>';
			
					$strVersandHTML .= '<tr><td colspan="5" style="text-align:right"><strong>Lieferkosten</strong></td><td class="td_right">0,00 &euro;</td></tr>';
				} else {
					#$strVersandHTML = '<tr><td colspan="5" style="text-align:right"><strong>Zwischensumme</strong></td><td colspan="2">'.number_format($shop_artikel_preisGes, 2, ',', '.').' EUR</td></tr>';
				
					$strVersandHTML .= '<tr><td colspan="5" style="text-align:right"><strong>Lieferkosten</strong></td><td class="td_right">0,00 &euro;</td></tr>';
				}
			} 
		}
		
			$pathZurKasse = getPathUrl($_SESSION['language'],$domain_pages['zurkasse_id']);
			$html .= $strVersandHTML;
			$shop_artikel_gesamt = $shop_artikel_preisGes + $shop_artikel_preisVersand;
			
			$query ="SELECT * FROM domains WHERE domain_id = $domain_id";
			$res = DBi::$conn->query($query);
			$domain_data = mysqli_fetch_assoc($res);
			
			#Mehrwertsteuer vom Versand
			$shop_artikel_mwst[$shop_artikel_preisVersand_mwst] += (($shop_artikel_preisVersand / 100) * $shop_artikel_preisVersand_mwst);
			
			
			#'MwSt_inkl','MwSt_exkl','MwSt_befreit'
			switch($domain_data['shop_mwst_setting']) {
				case "MwSt_inkl":
					$strMWSTText = 'inkl. MwSt.';
					foreach ($shop_artikel_mwst as $key => $value) {
						if($key != '') {
							#$html .= '<tr><td colspan="4" style="text-align:right"><strong>Mehrwertsteuer '.$strMWSTText.' '.$key.'%</strong></td><td colspan="2">'.str_replace('.',',',$value).' EUR</td></tr>';
							$cart_mwst_ges = 0;
						}
					}
					break;
				case "MwSt_exkl":
					$strMWSTText = 'exkl. MwSt.';
					foreach ($shop_artikel_mwst as $key => $value) {
						if($key != '') {
							$html .= '<tr><td colspan="5" style="text-align:right"><strong>Mehrwertsteuer '.$strMWSTText.' '.$key.'%</strong></td><td colspan="2">'.str_replace('.',',',$value).' &euro;</td></tr>';
							$cart_mwst_ges += $value;
						}
					}
					break;
				case "MwSt_befreit":
					$strMWSTText = '';
					#$html .= '<tr><td colspan="4" style="text-align:right">Mehrwertsteuer befreit </td><td>&nbsp;</td></tr>';
					$html .= '<tr><td colspan="5" style="text-align:right"><strong>Mehrwertsteuer</strong></td><td colspan="2">Kleinunternehmer &sect; 19 Mehrwertsteuer befreit</td></tr>';
					$cart_mwst_ges = 0;
					break;
				case "MwSt_privatverkauf":
					$strMWSTText = '';
					#$html .= '<tr><td colspan="4" style="text-align:right">Mehrwertsteuer befreit </td><td>&nbsp;</td></tr>';
					$html .= '<tr><td colspan="5" style="text-align:right"><strong>Keine Mehrwertsteuer</strong></td><td colspan="2">Privatverkauf</td></tr>';
					$cart_mwst_ges = 0;
					break;
			}
			

			$html .= '<tr><td colspan="5" style="text-align:right"><h2>Gesamt inkl. 7% Mwst.</h2></td><td class="td_right"><h2>'.number_format(($shop_artikel_preisGes + $shop_artikel_preisVersand + $cart_mwst_ges), 2, ',', '.').' &euro;</h2></td><td>&nbsp;</td></tr>
			<tr><td colspan="5" style="text-align:right">&nbsp;</td><td colspan="2"><h2>';
#echo $pathZurKasse.'-'.$_SERVER['REQUEST_URI'];
			if($_POST['bWarenkorbOnly'] == "true") {
				if('/'.$pathZurKasse  != $_SERVER['REQUEST_URI']) {
					$html .='<div id="shop_cart_info_button" style="float:left">
						<button onClick="mini_zur_kasse_frame(\'NO_RESIZE\')" class="button" name="shop_car_order">Zur Kasse</button>
					</div><div style="clear:both"></div>';
				}
			}
			#<input type="button" class="button" name="btnWeiterShoppen" onClick="mini_frame_close_del()" value="Warenkorb leeren"/>
			#<tr><td colspan="5"><input type="button" class="button" name="btnWeiterShoppen" onClick="mini_frame_close()" value="weitere Gerichte"/></td></tr>
			$html .='</h2></td><td>&nbsp;</td></tr>
			
			</table>';
			#$html .= '<input type="button" class="button" name="btnWeiterShoppen" onClick="mini_frame_close()" value="Weiter Shoppen"/>';
		}

		
		$text .= $html.'</div>'; // config modus 

		
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 }
if($_POST['bWarenkorbOnly'] == "true") {
	echo '<div class="frame_rahmen">';
	echo "<h1>Ihre Bestellung</h1>";
	$strContent = LoadModul_shop_cart1(); 
	echo $strContent['content'];
	echo "</div>"; 
} else {
echo '<div class="frame_footer_center">';
echo '<span class="spanlink" onclick="javascript:mini_frame_close()"><div class="overlap_middle"><img src="/templates/mekong/images/BestellfensterSchliessen.png"/></div></span>';

	echo '<div class="frame_rahmen">';
	#block-title-frame
		echo '<div class=""> <h1 align="center">Ihre Bestellung</h1></div>';
		$strContent = LoadModul_shop_cart1(); 	
		echo $strContent['content'];
	echo "</div>"; 
	
	echo '<div class="frame_rahmen2">';	
	#block-title-frame
		echo '<div class=""> <h1 align="center">Ihre Lieferadresse</h1></div>';
		$strContent = LoadModul_shop_cart1_order1(); 
		echo $strContent['content'];
	echo '<span style="clear:both"></span>';
	echo "</div>"; 
echo '</div>';	
}
?>