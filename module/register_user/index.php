<?php 


####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_register_user($config) {

		$dataTextHTML = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_register_user WHERE id=".$config['modul_id']));
		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='register_user'"));
		#echo "IN";
		
		$dataTextHTML['typ'] = 'texthtml';
		
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
		

		if($_GET['status'] == 'sended') {
			$text .= 'Vielen Dank für die Anmeldung.';
		} else {
		
			$text .= '<form name="frmRegistrationBenutzer" id="frmRegistrationBenutzer" action="/api.php" method="POST" onSubmit="return mydraft_registierung(\'frmRegistrationBenutzer\',\''.$config['typ'].'\',\''.$config['modul_id'].'\');">';
			$text .= '<h2>Blog Subdomain sichern</h2>';			
			$text .= 'Ihre Daten werden vertraulich behandelt. Die erhobenen Daten werden zum generieren Ihres Subdomain-Blogs benötigt. Sie erhalten eine Email mit der Freischaltung, sowie eine Freischaltbestätigung per Email.';
			
			$text .= '<div class="label">Blog-Domain von freie-welt.eu*</div>';
			$text .= '<div><input onKeyUp="registrieren_mydraft_domain_check()" type="text"value="'.$_SESSION['order_shop_domain'].'" name="txtRegDomainName" id="txtRegDomainName" value=""/><span class="frm_error" id="txtRegDomainName_err"></span> <span class="frm_error" id="txtRegDomainName_status"></span> <span class="frm_error" id="txtRegDomainName_check_err"></span></div>';
			$text .= '<div style="clear:both"></div>';
			
			$text .= '<div class="label">Admin-Benutzername*</div>';
			$text .= '<div><input onKeyUp="registrieren_mydraft_username_check()" type="text" value="'.$_SESSION['order_username'].'" name="txtRegUsername" id="txtRegUsername"/><span class="frm_error" id="txtRegUsername_err"></span> <span class="frm_error" id="txtRegUsername_status"></span> <span class="frm_error" id="txtRegUsername_check_err"></span></div>';
			$text .= '<div style="clear:both"></div>';
			
			$text .= '<div class="label">Admin-Email*</div>';
			$text .= '<div><input type="text" value="'.$_SESSION['order_email'].'" name="txtEmail" id="txtEmail"/><span class="frm_error" id="txtEmail_err"></span></div>';
			$text .= '<div style="clear:both"></div>';		
			
			$text .= '<div class="label">Admin-Passwort*</div>';
			$text .= '<div><input type="password" value="'.$_SESSION['order_password'].'" name="txtRegPasswort" id="txtRegPasswort"/><span class="frm_error" id="txtRegPasswort_err"></span></div>';
			$text .= '<div style="clear:both"></div>';
			 
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
			$text .= '<div><input type="submit" class="button" name="btnSenden" value="Blog erstellen"/></div>';
			$text .= '<div style="clear:both"></div>';
			$text .= '</form>';
		}
		#$text .= '</div>'; // config modus 

		##############################
		# >> Inline suche
		##############################
		#$text = stringToFunction($text);
	
		if($dataTextHTML["content_de"] == '') {
			$text .= 'Bitte doppelt anklicken zum editieren';
		}
		
		###############
		# >> Eingelogt 
		###############
		
		if (@$_SESSION["login"] == '1')  { 			
			$text = '<div ondblclick="getTexthtmlEdit('.$dataTextHTML['id'].');" id="texthtml_'.$dataTextHTML['id'].'">'.$text.'</div>'; 
		} 
		
		$text .= '</div>'; // config modus 

	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 } 
 ?>