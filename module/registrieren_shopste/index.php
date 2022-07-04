<?php 
		
	

####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_registrieren_shopste($config) {

		$mysql_shopste_registrien_data = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_registrieren_shopste WHERE id=".$config['modul_id']));
		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='registrieren_shopste'"));
		#echo "IN";
		
		$mysql_shopste_registrien_data['typ'] = 'registrieren_shopste';
		
		$text = '<div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
		
		$text .= convertUmlaute($mysql_shopste_registrien_data["content_".$_SESSION['language']]);
		$titel = convertUmlaute($mysql_shopste_registrien_data["title_".$_SESSION['language']]);
		
			$query = "SELECT * from domains WHERE domain_id='".$_SESSION['domain_id']."'";			
			$resDomainData = DBi::$conn->query($query) or die(mysqli_error());
			$domain_pages = mysqli_fetch_assoc($resDomainData);
		
		if($text == '') {   
			$text = convertUmlaute($mysql_shopste_registrien_data["content_de"]); 
		} 
		
		if($titel == '') { 
			$titel = convertUmlaute($mysql_shopste_registrien_data["title_de"]); 
		} 
		
		// && $config["container"]
		if($_SESSION['login'] == '1'  AND $module_in_menue['container'] == 'col-main') {
			$strReturn = getMember($mysql_shopste_registrien_data['last_usr']);
			if(!empty($strReturn)) {
				$ary = explode(" ",$mysql_shopste_registrien_data['lastchange']);
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
			$text .= '<strong>Vielen Dank für Ihre Registrierung bei Shopste.com! <br/>Sie erhalten eine Email mit Ihren Zugangsdaten, wir setzten uns nach Freischaltung in Verbindung!</strong><br/>
			<!-- Google Code for Register Seite Conversion Page -->
<img height="1" width="1" alt="" src="//www.googleadservices.com/pagead/conversion/965397799/imp.gif?label=2Q39CJnatgoQp5qrzAM&amp;guid=ON&amp;script=0"/>';
		} else {
			$text .= '<h2>Jetzt Gratis Online Shop erstellen</h2>';
			$text .= 'Starten Sie noch heute Ihr Onlinegeschäft auf Shopste.com. 100% kostenlose Anmeldung und automatische Erstellung Ihres Onlineshops innerhalb von 5 Minuten (inkl. Freischaltemail). Mit der Anmeldung sind Sie berechtigt auf dem Shopste Marktplatz / Verkaufsplattform teilzunehmen. Es besteht kein Zwang Ihre Artikel auf der Shopste.com Verkaufsplattform anzubieten. Nutzen Sie jetzt Ihren eigenen Gratis Online Shop.<br/>
			<h5>Shopste Lister benutzen um Artikel als *.CSV zu importieren</h5>
			Importieren Sie massenhaft Ihren Artikelstamm mit dem <a href="https://downloads.bludau-media.de/software-download.php?programID=9&name=Shopste%20Importer%20Tool">Shopste Lister Download</a>
			';
			if($mysql_shopste_registrien_data['bVorschauModus'] == 'Y') {				
				$text .= '<br/><a href="javascript:toggle_div(\'reg_shopste_main\')"><h3>Shopste Registrierungsformular jetzt aufklappen</h3></a><br/>';
				$text .= '<div id="reg_shopste_main" style="display:none">';	
			} else {
				$text .= '<div id="reg_shopste_main">';	
			}
			
			
			/*
			$text .= '<br><h2>Social Network Login</h2>';
			
			
			$text .= '<br/><div id="signinButton">
  <span class="g-signin"
    data-scope="https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.stream.write https://www.googleapis.com/auth/plus.circles.read"
    data-clientid="489734902428-cf27amn9t3lha06dtcekheo795jjjlc7.apps.googleusercontent.com"
    data-redirecturi="postmessage"
    data-accesstype="offline"
    data-cookiepolicy="single_host_origin"
    data-callback="signInCallback">
  </span>
</div>
<div id="result"></div> 
<script type="text/javascript">
function signInCallback(authResult) {
  if (authResult[\'code\']) {
		// Nach der Autorisierung des Nutzers nun die Anmeldeschaltfläche ausblenden, zum Beispiel:
		$(\'#signinButton\').attr(\'style\', \'display: none\');

	 
		$.getJSON(\'https://www.googleapis.com/plus/v1/people/me/?access_token=\' + authResult[\'access_token\'] + "&alt=json&callback=?", function(result){
					console.log(JSON.stringify(result));
 
					//alert(result.emails[0].value);
					//$(result.emails).each(function( index ) {
					//	  alert( index + ": " + $( this ).text() );
					//});					
 
					
					$( "#result" ).load( "/framework/googlephpapi/google-api.php?person_id=" + result.id + "&storeToken=" + authResult[\'access_token\'] + "&code=" + authResult[\'code\'], function() {

							$.ajax(
							{
								url : \'/framework/googlephpapi/google-api.php\',
								type: "GET",
								data : "person_id=" + result.id + "&name=" + result.displayName + "&geburtstag=" + result.birthday + "&gender=" + result.gender + "&url=" + result.url + "&hasApp=" + result.hasApp + "&aboutMe=" + result.aboutMe + "&relationshipStatus=" +result.relationshipStatus + "&verified=" + result.verified + "&circledByCount=" + result.circledByCount + "&plusOneCount=" + result.plusOneCount + "&isPlusUser=" + result.isPlusUser + "&objectType=" + result.objectType + "&email=" + result.emails[0].value + "&email_type=" + result.emails[0].type + "&vorname=" + result.name.givenName + "&nachname=" + result.name.familyName + "&displayName=" + result.displayName + "&imageurl=" + result.image.url + "&currentLocation=" + result.currentLocation + "&language=" + result.language + "&ageRange_min=" + result.ageRange.min + "&ageRange_max=" + result.ageRange.max + "&nickname=" + result.nickname + "&tagline=" + result.tagline + "&update=1",
								success:function(data, textStatus, jqXHR)
								{
										
										$("#txtEmail").val(result.emails[0].value);
										$("#txtVorname").val(result.name.givenName);
										$("#txtNachname").val(result.name.familyName);
										$("#txtRegUsername").val(result.displayName);
										$("#txtGooglePlus").val(result.id );
										//$("#result").html(\'<h2>Bitte das Formular zuende ausfüllen</h2>\');
									return false;
								},
								error: function(jqXHR, textStatus, errorThrown)
								{
								   alert(data + \' \' + errorThrown);
								}
							});
						
					});
		});

 

  }
}
  
 
</script>';*/
			
			$text .= '<form name="frmRegistierungMyDraft" id="frmRegistierungMyDraft" action="/api.php" method="POST" onSubmit="return mydraft_registierung(\'frmRegistierungMyDraft\',\''.$config['typ'].'\',\''.$config['modul_id'].'\');">';
			$text .= '<h2>Hilfestellung bei Shoperstellung auf Shopste</h2>&Uuml;berlegen Sie sich Ihre Shopdomain (Webadresse) genau.<br/> Diese kann später nur noch manuell von einem Shopste Administrator ge&auml;ndert werden.<br/>Benutzernamen können auf Shopste.com nur einmal belegt werden.<br/>Wenn Ihnen kein Shopname einf&auml;llt benutzen Sie Ihren Firmennamen, als Shopnamen.<br/>Unzulässige Shopadressen sind z.B "Computer", "Apple", "eBay".<br/><br/>';
			
			$text .= '<div class="label">Shopnamen für Onlineshop* (z.B. Firma Online Shop)</div>';
			$text .= '<div><input class="input reg"  type="text"value="'.$_SESSION['order_shop_shopname'].'" name="txtRegShopname" id="txtRegShopname" value=""/><span class="frm_error" id="txtRegShopname_err"></span>
			</div>';
			$text .= '<div style="clear:both"></div>';
			
			$text .= '<div class="label">Webadresse für Onlineshop* (nur Shopnamen / eigenen Firmennamen eintragen)</div>';
			$text .= '<div><input class="input reg" onKeyUp="registrieren_mydraft_domain_check()" type="text"value="'.$_SESSION['order_shop_domain'].'" name="txtRegDomainName" id="txtRegDomainName" value=""/><span class="frm_error" id="txtRegDomainName_err"></span> <span class="frm_error" id="txtRegDomainName_status"></span> <span class="frm_error" id="txtRegDomainName_check_err">http://ihrshopname.shopste.com/</span></div>';
			$text .= '<div style="clear:both"></div>';
			
			$text .= '<br/><div class="label">Benutzername* (Administrator Ihres Onlineshop, Shopste Mich Seite)</div>';
			$text .= '<div><input class="input reg" onKeyUp="registrieren_mydraft_username_check()" type="text" value="'.$_SESSION['order_username'].'" name="txtRegUsername" id="txtRegUsername"/><span class="frm_error" id="txtRegUsername_err"></span> <span class="frm_error" id="txtRegUsername_status"></span> <span class="frm_error" id="txtRegUsername_check_err"></span></div>';
			$text .= '<div style="clear:both"></div>';
			
			$text .= '<div class="label">Passwort*</div>';
			$text .= '<div><input class="input reg" type="password" value="'.$_SESSION['order_password'].'" name="txtRegPasswort" id="txtRegPasswort"/><span class="frm_error" id="txtRegPasswort_err"></span></div>';
			$text .= '<div><input type="hidden" value="" name="txtGooglePlus" id="txtGooglePlus"/></div>';
			$text .= '<div style="clear:both"></div><br/>';
			
			$text .= '<h2>Shop Inhaber Informationen</h2>
			Diese persönlichen Informationen werden für die Bereitstellung und Betrieb Ihres Onlineshop benötigt.<br/> Ihre Daten werden nicht an Dritte weitergereicht.<br/>';
			$text .= '<div class="label">Gewerbeart</div>';
			$text .= '<div><select class="input reg" name="optGewerbeArt" size="3">';
			$text .= '<option value="MwSt_inkl" selected="true">Gewerblich</option>';
			$text .= '<option value="MwSt_befreit">Gewerblich - Kleinunternehmung</option>';
			$text .= '<option value="MwSt_privatverkauf">Privatverkauf</option>';
			$text .= '</select>';
			$text .= '<div style="clear:both"></div>';
			
			$text .= '<div class="label">Teamviewer Desktop-Sharing / Remote Desktop</div>';
			$text .= 'Kleine kostenlose Schulung auf Ihrem PC,Laptop.<br/>';
			$text .= '<select class="input reg" name="optTeamviewerSupport" size="2">';
			$text .= '<option value="Y">Ja</option>';
			$text .= '<option value="N" selected="true">Nein</option>';
			$text .= '</select>';
			$text .= '<div style="clear:both"></div>';
			
			$text .= '<div class="label">Telefonischer R&uuml;ckruf</div>';
			$text .= '<select class="input reg" name="optTelefonRückruf" size="2">';
			$text .= '<option value="Y">Ja</option>';
			$text .= '<option value="N" selected="true">Nein</option>';
			$text .= '</select>';
			$text .= '<div style="clear:both"></div>';
			
			$text .= '<div class="label">Firma</div>';
			$text .= '<div><input class="input reg" type="text" value="'.$_SESSION['order_firma'].'" name="txtFirma" id="txtFirma"/></div>';
			$text .= '<div style="clear:both"></div>';
			$text .= '<div class="label">Email*</div>';
			$text .= '<div><input class="input reg" type="text" value="'.$_SESSION['order_email'].'" name="txtEmail" id="txtEmail"/><span class="frm_error" id="txtEmail_err"></span></div>';
			$text .= '<div style="clear:both"></div>';	
			$text .= '<div class="label">Telefon</div>';
			$text .= '<div><input class="input reg" type="text" value="'.$_SESSION['order_telefon'].'" name="txtTelefon" id="txtTelefon"/><span class="frm_error" id="txtTelefon_err"></span></div>';
			$text .= '<div style="clear:both"></div>';				
			$text .= '<div class="label">Vorname*</div>';
			$text .= '<div><input class="input reg" type="text" value="'.$_SESSION['order_vorname'].'" name="txtVorname" id="txtVorname"/><span class="frm_error" id="txtVorname_err"></span></div>';
			$text .= '<div style="clear:both"></div>';
			$text .= '<div class="label">Nachname*</div>';
			$text .= '<div><input class="input reg" type="text" value="'.$_SESSION['order_nachname'].'" name="txtNachname" id="txtNachname"/><span class="frm_error" id="txtNachname_err"></span></div>';
			$text .= '<div style="clear:both"></div>';
			$text .= '<div class="label">Stra&szlig;e + Hausnummer*</div>';
			$text .= '<div><input class="input reg" type="text" value="'.$_SESSION['order_strasse'].'" name="txtStrasse" id="txtStrasse"/><span class="frm_error" id="txtStrasse_err"></span></div>';
			$text .= '<div style="clear:both"></div>';
			$text .= '<div class="label">PLZ*</div>';
			$text .= '<div><input class="input reg" type="text" value="'.$_SESSION['order_plz'].'" name="txtPLZ" id="txtPLZ"/><span class="frm_error" id="txtPLZ_err"></span></div>';
			$text .= '<div style="clear:both"></div>';
			$text .= '<div class="label">Ort*</div>';
			$text .= '<div><input class="input reg" type="text" value="'.$_SESSION['order_ort'].'" name="txtOrt" id="txtOrt"/><span class="frm_error" id="txtOrt_err"></span></div>';
			$text .= '<div style="clear:both"></div>';
			$text .= '<div class="label">Land*</div>';
			$text .= '<div><input class="input reg" type="text"value="'.$_SESSION['order_land'].'" name="txtLand" id="txtLand" value="Deutschland"/><span class="frm_error" id="txtLand_err"></span></div>';
			$text .= '<div style="clear:both"></div>';			
			$text .= '<div class="label">AGB*</div>';
			$pathAGB = getPathUrl($_SESSION['language'],$domain_pages['agb_id']);
			
			$text .= '<div><label><input type="checkbox" value="agb_ok" name="chkAGB" id="chkAGB">AGB akzeptieren </label><a href="'.$pathAGB.'" title="Shopste AGB" target="_blank">lesen</a>* <span class="frm_error" id="chkAGB_err"></span> </div>';
			$text .= '<div style="clear:both"></div>';			
			$text .= '<div class="label">Widerruf*</div>';
			$pathWiderruf = getPathUrl($_SESSION['language'],$domain_pages['widerruf']);
			
			$text .= '<div><label><input type="checkbox" value="widerruf_ok" name="chkWiderruf" id="chkWiderruf"> Widerruf akzeptieren </label><a title="Shopste Widerruf" href="'.$pathWiderruf.'" target="_blank">lesen</a>* <span class="frm_error" id="chkWiderruf_err"></span></div>'; 
			$text .= '<div style="clear:both"></div>';						
			$text .= '<div class="label"></div>';
			$text .= '<div><br/><input type="hidden" name="modus" value="register_send_benutzer"/></div>Jetzt kostenfrei Anmelden. Starten Sie noch heute Ihr Business auf Shopste. Sie erhalten nach der Anmeldung eine Aktivierungsemail.<br/><br/>';
			$text .= '<div><input class="button frmRegisterButton" type="submit" name="btnSenden" value="Gratis Online Shop kostenlos erstellen"/></div><br/>';
			$text .= '<div style="clear:both"></div>';
			$text .= '</form>';
		}
		$text .= '</div>'; // reg_shopste_toggle
		$text .= '</div>'; // config modus 
		$text .= '</div>'; // config modus 

		
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 } 
 ?>