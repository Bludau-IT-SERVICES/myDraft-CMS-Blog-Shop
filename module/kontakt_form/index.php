<?php 
	

####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_kontakt_form($config) {

		$dataTextHTML = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_kontakt_form WHERE id=".$config['modul_id']));
		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='modul_kontakt_form'"));
		#echo "IN";
		
		$dataTextHTML['typ'] = 'modul_kontakt_form';
		
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
			$text .= '<strong>Vielen Dank für Ihre Anfrage bei Shopste.com! <br/>Sie erhalten in K&uuml;rze eine Email</strong><br/>';
		} else {
		
			$text .= '<form name="frmKontaktForm" id="kontaktform_content" action="/api.php" method="POST" onSubmit="return setKontaktForm(\'frmKontaktForm\',\''.$config['typ'].'\',\''.$config['modul_id'].'\');">';		
			
			$text .= '<h2>Kontaktformular</h2>';
			$text .= '<div class="label">Firma</div>';
			$text .= '<div><input type="text" value="'.$_SESSION['order_firma'].'" name="txtFirma" id="txtFirma"/></div>';
			$text .= '<div style="clear:both"></div>';
			$text .= '<div class="label">Email*</div>';
			$text .= '<div><input type="text" value="'.$_SESSION['order_email'].'" name="txtEmail" id="txtEmail"/><span class="frm_error" id="txtEmail_err"></span></div>';
			$text .= '<div style="clear:both"></div>';		
			$text .= '<div class="label">Vorname*</div>';
			$text .= '<div><input type="text" value="'.$_SESSION['order_vorname'].'" name="txtVorname" id="txtVorname"/><span class="frm_error" id="txtVorname_err"></span></div>';
			$text .= '<div style="clear:both"></div>';
			$text .= '<div class="label">Nachname*</div>';
			$text .= '<div><input type="text" value="'.$_SESSION['order_nachname'].'" name="txtNachname" id="txtNachname"/><span class="frm_error" id="txtNachname_err"></span></div>';
			$text .= '<div style="clear:both"></div>';
			$text .= '<div class="label">Telefon</div>';
			$text .= '<div><input type="text" value="'.$_SESSION['order_telefon'].'" name="txtTelefon" id="txtTelefon"/><span class="frm_error" id="txtTelefon_err"></span></div><br/>';
			$text .= '<div style="clear:both"></div>';
		 

			$text .= '<div class="label">Ihre Anfrage oder Mitteilung</div>';
			$text .= '<div id="kontakt_nachricht'.$config['modul_id'].'"></div>';
			$text .= '<div style="clear:both"></div>';
		
			$text .= '<input type="hidden" name="kontakt_modul_id" value="'.$config['modul_id'].'"/>';
			$text .= '<input type="hidden" name="modus" value="kontakt_send_mail"/>';
			$text .= '<script>getKontaktFormModul(\''.$config['typ'].'\',\''.$config['modul_id'].'\');</script>';
			$text .= '<br/><div><input type="submit" class="button" id="submit_kontaktform" value="Kontaktformular abschicken" name="submit_editTitle"/></div><br/>';
			$text .= '<div style="clear:both"></div>';
			$text .= '</form>';
		}
		$text .= '</div>'; // config modus 

		
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 } 
 ?>