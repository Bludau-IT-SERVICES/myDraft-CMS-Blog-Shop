<?php 
	function getFirmenEintragSuche($suchanfrage) {
	
 
	$ErgQuery = DBi::$conn->query("SELECT * FROM firma_shopinfo WHERE firma LIKE '".$suchanfrage."%'");
 
	$iCount = 0;
	while($dataFirma = mysqli_fetch_array($ErgQuery)) {
		if($iCount == 1) {
			$strHTML .='<br/><br/><hr/>';
		} 
		$iCount++;
		$strHTML .= '<h1 id="headercms">'.$dataFirma['firma'].'</h1>';
		$strHTML .= 'aus '.$dataFirma['plz'].' '.$dataFirma['stadt'].'<br/><br/>';
		$strHTML .= '<strong>Beschreibung</strong><br/>';
		$strHTML .= $dataFirma['shop_beschreibung'].'<br/><br/>';
	}
	if($iCount == 1) {
		$strHTML .= '<br/>';
	}
	
	return $strHTML;
}		
	

####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_portal_firmenprofil($config) {

		$dataTextHTML = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_portal_firmenprofil WHERE id=".$config['modul_id']));
		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='portal_firmenprofil'"));
		#echo "IN";
		
		$dataTextHTML['typ'] = 'portal_firmenprofil';
		
		$text = '<a name="kontaktanfrage"/></a><div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
		
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
		if($_GET['firma'] != '' or $_POST['firma'] != '') {
			if($_POST['firma'] != '') {
				$text .= getFirmenEintragSuche($_POST['firma']);
			} else {
				$text .= getFirmenEintragSuche($_GET['firma']);
			}
		}
		if($_GET['status'] == 'sended') {
			$text .= '<strong>Vielen Dank für Ihre Anfrage auf Schlemmertal.de! <br/>Sie erhalten in K&uuml;rze eine Kopie dieser Email.</strong><br/>';
		} else {
		$text .= '<form name="frmKontaktForm" id="frmKontaktForm" action="/api.php" method="POST" onSubmit="return setFirmaKontaktformular(\'frmKontaktForm\',\'portal_firmenprofil\',\'3\',\''.$_GET['firma'].'\');">';		
			
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
			
			$text .= '<div class="label"></div>';
			$text .= '<div><br/><input type="hidden" id="modus" name="modus" value="firma_kontakt"/><input type="hidden" id="firma" name="firma" value="'.$_GET['firma'].'"/></div><br/>';
			$text .= '<script>getKontaktFirma(\''.$config['typ'].'\',\''.$config['modul_id'].'\');</script>';
			$text .= '<div><input type="button" id="submit_kontaktform" value="Kontaktformular abschicken" name="submit_editTitle"/></div>';
			$text .= '<div style="clear:both"></div>';
			$text .= '</form>';
		}
		$text .= '</div>'; // config modus 

		
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 } 
 ?>