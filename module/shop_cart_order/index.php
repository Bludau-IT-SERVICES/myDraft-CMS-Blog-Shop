<?php 
		
	

####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_shop_cart_order($config) {

		$dataTextHTML = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_shop_cart_order WHERE id=".$config['modul_id']));
		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='shop_cart_order'"));
		#echo "IN";
		
		$dataTextHTML['typ'] = 'shop_cart_order';
		
		$text = '<div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
		
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
				$text .= 'Vielen Dank f&uuml;r Ihre Bestellung!<br/>Ihr Warenkorb wurde per Email abgeschickt und die Bestellung ist abgeschlossen.';
			} else {
				$text .= '<form name="frmCartOrder" id="frmCartOrder" action="/cart/cart_order_adress.php" method="POST" onSubmit="return cart_order_send(\'frmCartOrder\',\''.$config['typ'].'\',\''.$config['modul_id'].'\');">';
				
				if($dataTextHTML['option_lieferzeit'] == 'Y') {
					$text .= '<div class="label">Lieferzeit</div>';
					$text .= '<div><input type="text" value="'.$_SESSION['order_lieferzeit'].'" name="txtLieferzeit" id="txtLieferzeit" class="input reg"/> dd.mm.yyyy std:min</div>';
					$text .= '<div style="clear:both"></div>';				
				}
				
				$text .= '<div class="label">Firma</div>';
				$text .= '<div><input type="text" value="'.$_SESSION['order_firma'].'" name="txtFirma" id="txtFirma" class="input reg"/></div>';
				$text .= '<div style="clear:both"></div>';
				$text .= '<div class="label">Email</div>';
				$text .= '<div><input type="text" value="'.$_SESSION['order_email'].'" name="txtEmail" id="txtEmail" class="input reg"/><span class="frm_error" id="txtEmail_err"></span></div>';
				$text .= '<div style="clear:both"></div>';		
				$text .= '<div class="label">Vorname</div>';
				$text .= '<div><input type="text" value="'.$_SESSION['order_vorname'].'" name="txtVorname" id="txtVorname" class="input reg"/><span class="frm_error" id="txtVorname_err"></span></div>';
				$text .= '<div style="clear:both"></div>';
				$text .= '<div class="label">Nachname</div>';
				$text .= '<div><input type="text" value="'.$_SESSION['order_nachname'].'" name="txtNachname" id="txtNachname" class="input reg"/><span class="frm_error" id="txtNachname_err"></span></div>';
				$text .= '<div style="clear:both"></div>';
				$text .= '<div class="label">Stra&szlig;e + Hausnummer</div>';
				$text .= '<div><input type="text" value="'.$_SESSION['order_strasse'].'" name="txtStrasse" id="txtStrasse" class="input reg"/><span class="frm_error" id="txtStrasse_err"></span></div>';
				$text .= '<div style="clear:both"></div>';
				$text .= '<div class="label">PLZ</div>';
				$text .= '<div><input type="text" value="'.$_SESSION['order_plz'].'" name="txtPLZ" id="txtPLZ" class="input reg"/><span class="frm_error" id="txtPLZ_err"></span></div>';
				$text .= '<div style="clear:both"></div>';
				$text .= '<div class="label">Ort</div>';
				$text .= '<div><input type="text" value="'.$_SESSION['order_ort'].'" name="txtOrt" id="txtOrt" class="input reg"/><span class="frm_error" id="txtOrt_err"></span></div>';
				$text .= '<div style="clear:both"></div>';
				$text .= '<div class="label">Land</div>';
				$text .= '<div><input type="text"value="'.$_SESSION['order_land'].'" name="txtLand" id="txtLand" value="Deutschland" class="input reg"/><span class="frm_error" id="txtLand_err"></span></div>';
				$text .= '<div style="clear:both"></div>';
				$text .= '<div class="label">AGB</div>';
				$pathAGB = getPathUrl($_SESSION['language'],$domain_pages['agb_id']);
				
				$text .= '<div><label><input type="checkbox" value="agb_ok" name="chkAGB" id="chkAGB">AGB akzeptieren</label> <a href="'.$pathAGB.'" target="_blank">lesen</a>* <span class="frm_error" id="chkAGB_err"></span> </div>';
				$text .= '<div style="clear:both"></div>';			
				$text .= '<div class="label">Widerruf</div>';
				$pathWiderruf = getPathUrl($_SESSION['language'],$domain_pages['widerruf']);
				
				$text .= '<div><label><input type="checkbox" value="widerruf_ok" name="chkWiderruf" id="chkWiderruf"> Widerruf akzeptieren</label> <a href="'.$pathWiderruf.'" target="_blank">lesen</a>* <span class="frm_error" id="chkWiderruf_err"></span></div>'; 
				$text .= '<div style="clear:both"></div><br/>';						
				
				$text .= '<div><input type="submit" class="button" name="btnSenden" value="Zahlungspflichtig bestellen"/></div>';
				$text .= '<div style="clear:both"></div>';
				$text .= '</form>';
			}
		}
		$text .= '</div>'; // config modus 

		
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 } 
 ?>