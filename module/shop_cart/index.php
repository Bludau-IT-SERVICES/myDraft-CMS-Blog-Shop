<?php 


####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_shop_cart($config) {

		$dataTextHTML = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_shop_cart WHERE id=".$config['modul_id']));
		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='shop_cart'"));
		#echo "IN";
		
		$dataTextHTML['typ'] = 'shop_cart';
		
		$text = '<div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
		
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
		$html = '<table width="100%"><tr><td><h3>Bild</h3></td><td><h3>Name</h3></td><td><h3>Menge</h3></td><td><h3>Einzelpreis</h3></td><td><h3>Artikelpreis</h3></td><td><h3>Aktion</h3></td></tr>';
		
		// Alle Artikel durchlaufen
		$tmpVersandkosten = '';
		for($i=0; $i < count($strAry) -1; $i++) {
			$shop_artikel_anzahl++;
			
			// Artikel Details holen 
			$strItemDetailAry = explode("-",$strAry[$i]);
			
			// SQL Injection vorbeugen
			$strItemDetailAry[0] = mysql_real_escape_string($strItemDetailAry[0]);
			$strItemDetailAry[1] = mysql_real_escape_string($strItemDetailAry[1]);
			$strItemDetailAry[2] = mysql_real_escape_string($strItemDetailAry[2]);
			
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
			$var2 = ($Cartdata['item_mwst'] / 100) + 1;
			$shop_artikel_mwst[$Cartdata['item_mwst']] = round((($shop_artikel_preisGes * $var2) -$shop_artikel_preisGes),2);
			
			$domain_id = $Cartdata['domain_id'];
			
			# Bild laden im Warenkorb
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
			}
		
			// Seite wo Artikel liegt abrufen
			$pathItem = getPathUrl($_SESSION['language'],$Cartdata['menue_id']);
			$preisGes = $strItemDetailAry[1] * $shop_artikel_preis;
			#echo $strItemDetailAry[1];
			$html .= '<tr>';
			$html .= '<td>'.$strBild.'</td>';
			$html .= '<td><a href="'.$pathItem.'">'.$Cartdata['shop_name'].'</a></td>';
			$html .= '<td>'.$strItemDetailAry[1].'</td>';
			$html .= '<td>'.number_format($shop_artikel_preis, 2, ',', '.').' EUR</td>';
			$html .= '<td>'.number_format($preisGes, 2, ',', '.').' EUR</td>';
			$html .= '<td><a class="link" onClick="shop_cart_delete(\''.$Cartdata['shop_item_id'].'\',\'shop_cart\',\''.$config['modul_id'].'\')">Löschen</a></td></tr>';
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
				
				
				$strVersandHTML .= '<tr><td colspan="4" style="text-align:right">Versandkosten '.$strVersandKostenName['name_de'].'</td><td>'.str_replace('.',',',$strVersandKosten['versandkosten']).' EUR</td><td>&nbsp;</td></tr>';
				$shop_artikel_preisVersand = $strVersandKosten['versandkosten'];
				$shop_artikel_preisVersand_mwst = $strVersandKostenName['mwst'];
				$bVersandartGefunden = true;
			}
			
			$strVersandHTML .= '<tr><td colspan="4" style="text-align:right"><strong>Zwischensumme</strong></td><td colspan="2">'.str_replace('.',',',$shop_artikel_preisGes).' EUR</td></tr>';
			
		
				
		if($bVersandartGefunden == false) {
			if($Cartdata['versandkosten'] != '') {
				$shop_artikel_preisVersand = $Cartdata['versandkosten'];
				if(!empty($Cartdata['versandkosten_name'])) {
			
					$strVersandHTML .= '<tr><td colspan="4" style="text-align:right"><strong>Gewicht</strong></td><td colspan="2">'.str_replace('.',',',$shop_artikel_gewicht).' KG</td></tr>';	
					$strVersandHTML .= '<tr><td colspan="4" style="text-align:right"><strong>Versandkosten</strong></td><td colspan="2">'.$Cartdata['versandkosten_name'].' '.str_replace('.',',',$Cartdata['versandkosten']).' EUR</td></tr>';
				} else {
					$strVersandHTML = '<tr><td colspan="4" style="text-align:right"><strong>Zwischensumme</strong></td><td colspan="2">'.number_format($shop_artikel_preisGes, 2, ',', '.').' EUR</td></tr>';
					$strVersandHTML .= '<tr><td colspan="4" style="text-align:right"><strong>Versandkosten</strong></td><td colspan="2">'.number_format($Cartdata['versandkosten'], 2, ',', '.').' EUR</td></tr>';
				}
			} else {				
				
				$strVersandHTML = '<tr><td colspan="4" style="text-align:right"><strong>Zwischensumme</strong></td><td colspan="2">'.number_format($shop_artikel_preisGes, 2, ',', '.').' EUR</td></tr>';
				$strVersandHTML .= '<tr><td colspan="4" style="text-align:right"><strong>Versandkosten</strong></td><td colspan="2">werden nach Bestellung berechnet</td></tr>';
			}
		} else {
			if($Cartdata['versandkosten'] != '') {
				$shop_artikel_preisVersand = $Cartdata['versandkosten'];
				if(!empty($Cartdata['versandkosten_name'])) {
					$strVersandHTML = '<tr><td colspan="4" style="text-align:right"><strong>Zwischensumme</strong></td><td colspan="2">'.number_format($shop_artikel_preisGes, 2, ',', '.').' EUR</td></tr>';
			
					$strVersandHTML .= '<tr><td colspan="4" style="text-align:right"><strong>Versandkosten</strong></td><td colspan="2">'.$Cartdata['versandkosten_name'].' '.str_replace('.',',',$Cartdata['versandkosten']).' EUR</td></tr>';
				} else {
					$strVersandHTML = '<tr><td colspan="4" style="text-align:right"><strong>Zwischensumme</strong></td><td colspan="2">'.number_format($shop_artikel_preisGes, 2, ',', '.').' EUR</td></tr>';
				
					$strVersandHTML .= '<tr><td colspan="4" style="text-align:right"><strong>Versandkosten</strong></td><td colspan="2">'.number_format($Cartdata['versandkosten'], 2, ',', '.').' EUR</td></tr>';
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
							$html .= '<tr><td colspan="4" style="text-align:right"><strong>Mehrwertsteuer '.$strMWSTText.' '.$key.'%</strong></td><td colspan="2">'.str_replace('.',',',$value).' EUR</td></tr>';
							$cart_mwst_ges = 0;
						}
					}
					break;
				case "MwSt_exkl":
					$strMWSTText = 'exkl. MwSt.';
					foreach ($shop_artikel_mwst as $key => $value) {
						if($key != '') {
							$html .= '<tr><td colspan="4" style="text-align:right"><strong>Mehrwertsteuer '.$strMWSTText.' '.$key.'%</strong></td><td colspan="2">'.str_replace('.',',',$value).' EUR</td></tr>';
							$cart_mwst_ges += $value;
						}
					}
					break;
				case "MwSt_befreit":
					$strMWSTText = '';
					#$html .= '<tr><td colspan="4" style="text-align:right">Mehrwertsteuer befreit </td><td>&nbsp;</td></tr>';
					$html .= '<tr><td colspan="4" style="text-align:right"><strong>Mehrwertsteuer</strong></td><td colspan="2">Kleinunternehmer &sect; 19 Mehrwertsteuer befreit</td></tr>';
					$cart_mwst_ges = 0;
					break;
				case "MwSt_privatverkauf":
					$strMWSTText = '';
					#$html .= '<tr><td colspan="4" style="text-align:right">Mehrwertsteuer befreit </td><td>&nbsp;</td></tr>';
					$html .= '<tr><td colspan="4" style="text-align:right"><strong>Keine Mehrwertsteuer</strong></td><td colspan="2">Privatverkauf</td></tr>';
					$cart_mwst_ges = 0;
					break;
			}
			

			$html .= '<tr><td colspan="4" style="text-align:right"><h2>Gesamtpreis</h2></td><td colspan="2"><h2>'.number_format(($shop_artikel_preisGes + $shop_artikel_preisVersand + $cart_mwst_ges), 2, ',', '.').' EUR</h2></td><td>&nbsp;</td></tr>
			</table>';
			#echo $pathZurKasse.'-'.$_SERVER['REQUEST_URI'];
			if('/'.$pathZurKasse  != $_SERVER['REQUEST_URI']) {
				$html .='<div id="shop_cart_info_button" style="float:right">
				<form id="shop_order_kasse" action="'.$pathZurKasse.'" method="POST">
					<input type="submit" class="button" name="shop_car_order" value="Zur Kasse"/>
				</form>
				</div><div style="clear:both"></div>';
			}
		}

		
		$text .= $html.'</div>'; // config modus 

		
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 } 
 ?>