<?php 
		
	

####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_registrieren_mydraft($config) {

		$dataTextHTML = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_registrieren_mydraft WHERE id=".$config['modul_id']));
		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='registrieren_mydraft'"));
		#echo "IN";
		
		$dataTextHTML['typ'] = 'registrieren_mydraft';
		
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
		if($_GET['status'] == 'sended') {
			$text .= 'Vielen Dank für die Anmeldung.';
		} else {
			$text .= '<h2>MyDraft Registrierung</h2>';
			$text .= 'Ihre Daten werden vertraulich behandelt. Die erhobenen Daten werden zum generieren der MyDraft Webseite / Online Shop ben&ouml;tigt.';
			
			$text .= '<form name="frmRegistierungMyDraft" id="frmRegistierungMyDraft" action="/api.php" method="POST" onSubmit="return mydraft_registierung(\'frmRegistierungMyDraft\',\''.$config['typ'].'\',\''.$config['modul_id'].'\');">';
			$text .= '<h2>Shop Namen sichern</h2>';
			$text .= '<div class="label">Shop-Domain</div>';
			$text .= '<div><input onKeyUp="registrieren_mydraft_domain_check()" type="text"value="'.$_SESSION['order_shop_domain'].'" name="txtRegDomainName" id="txtRegDomainName" value=""/><span class="frm_error" id="txtRegDomainName_err"></span> <span class="frm_error" id="txtRegDomainName_status"></span> <span class="frm_error" id="txtRegDomainName_check_err"></span></div>';
			$text .= '<div style="clear:both"></div>';
			
			$text .= '<div class="label">Benutzername</div>';
			$text .= '<div><input onKeyUp="registrieren_mydraft_username_check()" type="text" value="'.$_SESSION['order_username'].'" name="txtRegUsername" id="txtRegUsername"/><span class="frm_error" id="txtRegUsername_err"></span> <span class="frm_error" id="txtRegUsername_status"></span> <span class="frm_error" id="txtRegUsername_check_err"></span></div>';
			$text .= '<div style="clear:both"></div>';
			
			$text .= '<div class="label">Passwort</div>';
			$text .= '<div><input type="password" value="'.$_SESSION['order_password'].'" name="txtRegPasswort" id="txtRegPasswort"/><span class="frm_error" id="txtRegPasswort_err"></span></div>';
			$text .= '<div style="clear:both"></div>';
			
			$text .= '<h2>Pers&ouml;nliche Daten</h2>';
			$text .= '<div class="label">Firma</div>';
			$text .= '<div><input type="text" value="'.$_SESSION['order_firma'].'" name="txtFirma" id="txtFirma"/></div>';
			$text .= '<div style="clear:both"></div>';
			$text .= '<div class="label">Email</div>';
			$text .= '<div><input type="text" value="'.$_SESSION['order_email'].'" name="txtEmail" id="txtEmail"/><span class="frm_error" id="txtEmail_err"></span></div>';
			$text .= '<div style="clear:both"></div>';		
			$text .= '<div class="label">Vorname</div>';
			$text .= '<div><input type="text" value="'.$_SESSION['order_vorname'].'" name="txtVorname" id="txtVorname"/><span class="frm_error" id="txtVorname_err"></span></div>';
			$text .= '<div style="clear:both"></div>';
			$text .= '<div class="label">Nachname</div>';
			$text .= '<div><input type="text" value="'.$_SESSION['order_nachname'].'" name="txtNachname" id="txtNachname"/><span class="frm_error" id="txtNachname_err"></span></div>';
			$text .= '<div style="clear:both"></div>';
			$text .= '<div class="label">Stra&szlig;e + Hausnummer</div>';
			$text .= '<div><input type="text" value="'.$_SESSION['order_strasse'].'" name="txtStrasse" id="txtStrasse"/><span class="frm_error" id="txtStrasse_err"></span></div>';
			$text .= '<div style="clear:both"></div>';
			$text .= '<div class="label">PLZ</div>';
			$text .= '<div><input type="text" value="'.$_SESSION['order_plz'].'" name="txtPLZ" id="txtPLZ"/><span class="frm_error" id="txtPLZ_err"></span></div>';
			$text .= '<div style="clear:both"></div>';
			$text .= '<div class="label">Ort</div>';
			$text .= '<div><input type="text" value="'.$_SESSION['order_ort'].'" name="txtOrt" id="txtOrt"/><span class="frm_error" id="txtOrt_err"></span></div>';
			$text .= '<div style="clear:both"></div>';
			$text .= '<div class="label">Land</div>';
			$text .= '<div><input type="text"value="'.$_SESSION['order_land'].'" name="txtLand" id="txtLand" value="Deutschland"/><span class="frm_error" id="txtLand_err"></span></div>';
			$text .= '<div style="clear:both"></div>';			
			$text .= '<div class="label">AGB</div>';
			$pathAGB = getPathUrl($_SESSION['language'],$domain_pages['agb_id']);
			
			$text .= '<div><input type="checkbox" value="agb_ok" name="chkAGB" id="chkAGB"> <a href="'.$pathAGB.'" target="_blank">AGB akzeptieren</a>* <span class="frm_error" id="chkAGB_err"></span> </div>';
			$text .= '<div style="clear:both"></div>';			
			$text .= '<div class="label">Widerruf</div>';
			$pathWiderruf = getPathUrl($_SESSION['language'],$domain_pages['widerruf']);
			
			$text .= '<div><input type="checkbox" value="widerruf_ok" name="chkWiderruf" id="chkWiderruf"> <a href="'.$pathWiderruf.'" target="_blank">Widerruf akzeptieren</a>* <span class="frm_error" id="chkWiderruf_err"></span></div>'; 
			$text .= '<div style="clear:both"></div>';						
			$text .= '<div class="label">Kostenlos Registieren</div>';
			$text .= '<div><input type="hidden" name="modus" value="register_send_benutzer"/></div>';
			$text .= '<div><input type="submit" name="btnSenden" value="Kostenlos anmelden"/></div>';
			$text .= '<div style="clear:both"></div>';
			$text .= '</form>';
		}
		$text .= '</div>'; // config modus 

		
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 } 
 ?>