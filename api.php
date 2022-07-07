<?php 
@session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$path = realpath($_SERVER["DOCUMENT_ROOT"]);
require $path.'/framework/phpmailer/src/Exception.php';
require $path.'/framework/phpmailer/src/PHPMailer.php';
require $path.'/framework/phpmailer/src/SMTP.php';

setlocale(LC_MONETARY, 'de_DE');
$curGesArtikelSumme  = '';
$bezahlt  = '';


function getListMonthOrdersByDate($strDomain) {
	$query ="SELECT *,shop_order_list.created_at as sol_created FROM shop_order_list LEFT JOIN shop_order ON shop_order_list.id_shop_order = shop_order.shop_order_id LEFT JOIN shop_order_customer ON shop_order_customer.shop_order_customer_id = shop_order.ges_order_customer_id WHERE MONTH(shop_order_list.created_at) = ".$_GET['month']." AND YEAR(shop_order_list.created_at) = ".$_GET['year']." AND id_domain=".$_GET['domain_id'].' ORDER BY shop_order_list.created_at DESC';

	#$_GET['output_modus'] == 'ListByMonth';
	
	$resOrderSelect = DBi::$conn->query($query) or die('ERR00001:'.$query.mysqli_error(DBi::$conn));


		
	$html_head = '<h1>'.$strDomain['shop_name'].' - '.$strDomain['name'].' ('.$strDomain['domain_id'].')</h1>';
	$html_head .= '<h2>Rechnungszeitraum '.getMonthName($_GET['month']).'/'.$_GET['year'].'</h2>';

	$query = "SELECT * FROM portal_abrechnung WHERE domain_id='".$strDomain['domain_id']."' AND abrechnung_monat='".$_GET['month']."' AND abrechnung_jahr='".$_GET['year']."'";
	$resPortalAbrechnung = DBi::$conn->query($query);
	$strRechnung = mysqli_fetch_assoc($resPortalAbrechnung);
		## RECHNUNG BEZAHLT setzen
	$html_aktion ='<br/><br/><strong>Aktion:</strong> <a href="/api.php?modus=portal_abrechnung_bezahlt&abrechnung_id='.$strRechnung['portal_abrechnung_id'].'&bezahlt=Y&domain_crc='.$strDomain['domain_crc'].'&domain_id='.$strDomain['domain_id'].'#action_pos_'.$iCount.'">Rechnung bezahlt setzten</a> <br/>';			

		
	$html_head .='<table cellspacing="5" cellpadding="5">';
	$html_head .='<tr>';
		$html_head .='<td><strong>Art.Nr.</strong></td>';
		$html_head .='<td><strong>Verkauf am</strong></td>';
		$html_head .='<td><strong>Verkauf an</strong></td>';
		$html_head .='<td><strong>Name</strong></td>';
		$html_head .='<td><strong>Menge</strong></td>';
		$html_head .='<td><strong>Einzelpreis</strong></td>';
		$html_head .='<td><strong>Gesamtpreis</strong></td>';
		$html_head .='<td><strong>Aktion</strong></td>';
	$html_head .='</tr>';				
	
	$iActionPos = 1;
	while($strOrderSelect = mysqli_fetch_assoc($resOrderSelect)) {
			
			if($strOrderSelect['bstorniert'] == 'N') {
				$curArtikelsumme = $strOrderSelect['preis'] * $strOrderSelect['order_menge']; 
				$curGesArtikelSumme += $curArtikelsumme;
			}
			
			if($strOrderSelect['bstorniert'] == 'Y') {
				$strStorniertText = 'Wieder in Rechnung aufnehmen';
				$bStorniert = 'N';
				$strStrike_open = '<s>';
				$strStrike_close = '<s>';
				$strBgColor = '#EEE';
				
			} else {
				$strStorniertText = 'Rechnungsposition stornieren';								
				$bStorniert = 'Y';
				$strStrike_open = '';
				$strStrike_close = '';
				$strBgColor = '#CCC';
			}						
			
			$html_table_content .= 
			'<tr bgcolor="'.$strBgColor.'">
				<td>'.$strStrike_open.$strOrderSelect['shop_item_id'].$strStrike_close.'</td>
				<td>'.getDateDE($strOrderSelect['sol_created']).'</td>
				<td>'.$strOrderSelect['vorname'].' '.$strOrderSelect['nachname'].' aus '.$strOrderSelect['stadt'].'</td>
				<td>'.$strOrderSelect['name_de'].'</td>
				<td>'.$strOrderSelect['order_menge'].'x</td>
				<td>'.money_format('%2i', $strOrderSelect['preis']).'</td>
				<td>'.money_format('%2i', ($strOrderSelect['order_menge'] * $strOrderSelect['preis'])).'</td>
				<td><a id="aktion_pos_'.$iActionPos.'"><a href="'.CORE_SERVER_DOMAIN.'/api.php?modus=portal_abrechnung_bezahlt&submodus=setStornoItem&domain_id='.$_GET['domain_id'].'&shop_order_list_id='.$strOrderSelect['shop_order_list_id'].'&month='.$_GET['month'].'&year='.$_GET['year'].'&domain_crc='.$strDomain['domain_crc'].'&bstorniert='.$bStorniert.'#aktion_pos_'.$iActionPos.'">'.$strStorniertText.'</a></td>
			</tr>';
			$iActionPos++;
				# Mindestens eine Bestellposition gefunden
	
	}
	 
	


	# Mindestens eine Bestellposition gefunden
	if($iActionPos != 1) {	
		$html .= '<strong>Aktion:</strong> <a href="/api.php?modus=portal_abrechnung_bezahlt&submodus=action_send_invoice_again&renew_send_email=1&domain_id='.$strDomain['domain_id'].'&domain_crc='.$strDomain['domain_crc'].'&month='.$_GET['month'].'&year='.$_GET['year'].'#action_pos_'.$iCount.'">Rechnung korrigiert abschicken </a> ';

	
	}
	
	if(empty($curGesArtikelSumme)) {
		$curGesArtikelSumme = 0;
	}
	$iGebuehren = getGebuehrenTotal($curGesArtikelSumme);
	
	# UPDATE RECHNUNG Monatsumsatz
	$query ="UPDATE portal_abrechnung SET endsumme_gebuehr_ueberweisung='".$iGebuehren['gebuehr']."',Umsatz_ohne_versand='".$curGesArtikelSumme."' WHERE domain_id='".$strDomain['domain_id']."' AND abrechnung_monat='".$_GET['month']."' AND abrechnung_jahr='".$_GET['year']."'";
	DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
	
	if($iActionPos != 1) {	
	#print_r($iGebuehren);
		$html_summe .= '<strong>Shop Umsatz im Monat:</strong> <u>'.money_format('%2i',$curGesArtikelSumme).' </u> <strong>Shopste Gebühr:</strong> <u>'.money_format('%2i',$iGebuehren['gebuehr']).' </u> <strong>Prozent:</strong> <u>'.$iGebuehren['prozent'].'% </u>';
	} else {
		$html_summe .= '<h2>Keine Bestellungen für diesen Abrechnungsmonat gefunden</h2>';
	}
	
	if($iActionPos != 1) {
		$html_tmp .= $html;
		$html = $html_head;
		$html .= $html_table_content;
		$html .= $html_summe;
		$html .= $html_aktion;
		$html .= $html_tmp;
		$html .='</table><br/>';
	} else {
	
		
	}

	echo $html;
}

function setCustomerInvoiceEmail($strDomain) {
	#echo "OK";
	if(!isset($strDomain['name'])) {
				$strDomain['name'] = '';
	}
	$curGesArtikelSumme = 0;
	$bDezemberPrevious = 0;
	
	echo '<strong>'.$strDomain['name']."</strong><br/> \n";
	
	###################################################
	# BEI CRON JOB - 1 Monat rechnen
	###################################################
	if($_POST['cron'] == 'Y') {
		$strDomainToFetchWhere = '';
		$month = date("m");
		$month--;
		echo "| $month | <br/>";
		if($month == '0') {
			$month = 12;
			$bDezemberPrevious = 1;
		}
		$year = date("Y");
		
		if($bDezemberPrevious == 1) {
			$year--;
		}
	} else {
		if(isset($_GET['month']) && isset($_GET['year']) && isset($_GET['domain_id'])) {
			$month = $_GET['month'];
			$year  = $_GET['year'];
			$strDomainToFetchWhere = " AND domain_id ='".$_GET['domain_id']."'";
		} else {
			$month = date("m"); 
			$year  = date("Y");
			$strDomainToFetchWhere = '';
		}
	}
				
	
	
	$query ="SELECT * FROM domains WHERE email_freischaltung='Y' AND email_send_invoice='Y' ".$strDomainToFetchWhere;
	
	$resDomains = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));

	while($strDomain = mysqli_fetch_assoc($resDomains)) {
		
		# HTML Tabelle für Email einbinden
		$html = '<h2>Rechnungspositionen</h2>';
		$html .='<table cellspacing="5" cellpadding="5">';
		$html .='<tr>';
			$html .='<td><strong>Art.Nr.</strong></td>';
			$html .='<td><strong>Verkauf am</strong></td>';
			$html .='<td><strong>Verkauf an</strong></td>';
			$html .='<td><strong>Name</strong></td>';
			$html .='<td><strong>Menge</strong></td>';
			$html .='<td><strong>Einzelpreis</strong></td>';
			$html .='<td><strong>Gesamtpreis</strong></td>';
			$html .='<td><strong>Aktion</strong></td>';
		$html .='</tr>';
				
		$query ="SELECT *,shop_order_list.created_at as sol_created FROM shop_order_list LEFT JOIN shop_order ON shop_order_list.id_shop_order = shop_order.shop_order_id LEFT JOIN shop_order_customer ON shop_order_customer.shop_order_customer_id = shop_order.ges_order_customer_id WHERE MONTH(shop_order_list.created_at) = ".$month." AND YEAR(shop_order_list.created_at) = ".$year." AND shop_order_list.id_domain=".$strDomain['domain_id'];
		$resOrderSelect = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		$iActionPos = 0;
		while($strOrderSelect = mysqli_fetch_assoc($resOrderSelect)) {
 
				 $iActionPos++;
				if($strOrderSelect['bstorniert'] == 'N') {
					$curArtikelsumme = $strOrderSelect['preis'] * $strOrderSelect['order_menge']; 
					$curGesArtikelSumme += $curArtikelsumme;
				}
				
				if($strOrderSelect['bstorniert'] == 'Y') {
					$strStorniertText = 'Wieder in Rechnung aufnehmen';
					$bStorniert = 'N';
					$strStrike_open = '<s>';
					$strStrike_close = '</s>';
					$strBgColor = '#EEE';
				} else {
					$strStorniertText = 'Rechnungsposition stornieren';								
					$bStorniert = 'Y';
					$strStrike_open = '';
					$strStrike_close = '';
					$strBgColor = '#CCC';
				}	
				$html .= 
				'<tr bgcolor="'.$strBgColor.'">
					<td>'.$strStrike_open.$strOrderSelect['shop_item_id'].$strStrike_close.'</td>
					<td>'.getDateDE($strOrderSelect['sol_created']).'</td>
					<td>'.$strOrderSelect['vorname'].' '.$strOrderSelect['nachname'].' aus '.$strOrderSelect['stadt'].'</td>
					<td>'.$strOrderSelect['name_de'].'</td>
					<td>'.$strOrderSelect['order_menge'].'x</td>
					<td>'.money_format('%2i', $strOrderSelect['preis']).'</td>
					<td>'.money_format('%2i', ($strOrderSelect['order_menge'] * $strOrderSelect['preis'])).'</td>
					<td><a id="aktion_pos_'.$iActionPos.'"><a href="'.CORE_SERVER_DOMAIN.'/api.php?modus=portal_abrechnung_bezahlt&submodus=setStornoItem&domain_id='.$strDomain['domain_id'].'&shop_order_list_id='.$strOrderSelect['shop_order_list_id'].'&domain_crc='.$strDomain['domain_crc'].'&output_modus=ListByMonth&month='.$month.'&year='.$year.'&bstorniert='.$strOrderSelect['bstorniert'].'#aktion_pos_'.$iActionPos.'">'.$strStorniertText.'</a></td>
				</tr>';
				$last_domain_crc = $strDomain['domain_crc'];
				$last_domain_id = $strDomain['domain_id'];
				$last_storniert = $strOrderSelect['bstorniert'];
				$last_orderlist_id = $strOrderSelect['shop_order_list_id'];
		}
		$html .='</table>';
		
		if(isset($last_domain_id)) {
			$html .= '<a href="'.CORE_SERVER_DOMAIN.'/api.php?modus=portal_abrechnung_bezahlt&submodus=setStornoItem&domain_id='.$last_domain_id.'&shop_order_list_id='.$last_orderlist_id.'&domain_crc='.$last_domain_crc.'&output_modus=ListByMonth&month='.$month.'&year='.$year.'&bstorniert='.$last_storniert.'#aktion_pos_'.$iActionPos.'"><h3>Rechnung des Monat verwalten</h3></a>';
		}
		
		$iGebuehren = getGebuehrenTotal($curGesArtikelSumme);

		# Kontakt Daten abrufen 
		if($iGebuehren['gebuehr'] != 0) {
			
			# Erzeugte Rechnung in Datenbank speichern 
			$query = "SELECT count(*) as anzahl FROM portal_abrechnung WHERE domain_id='".$strDomain['domain_id']."' AND abrechnung_monat='".$month."' AND abrechnung_jahr='".$year."'";
			$resRechnungExists = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$iRechnungVorhanden = mysqli_fetch_assoc($resRechnungExists);
			
			# Rechnung vorhanden?
			if($iRechnungVorhanden['anzahl'] == 0) {
				echo "=================================================\n";
				echo ">> RECHNUNG erstellen ".$strDomain['name']."\n";
				echo "=================================================\n";
				$query ="INSERT INTO portal_abrechnung(abrechnung_monat,abrechnung_jahr,domain_id,endsumme_gebuehr_ueberweisung,endsumme_gebuehr_paypal,in_rechnung_gestellt_am,created_at,Umsatz_ohne_versand) VALUES('".$month."','".$year."','".$strDomain['domain_id']."','".$iGebuehren['gebuehr']."','0','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."','".$curGesArtikelSumme."')";
			
				DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 
			} else {
				echo "=================================================\n";
				echo ">> RECHNUNG aktuallisieren ".$strDomain['name']."\n";
				echo "=================================================\n";
				
				# UPDATE RECHNUNG Monatsumsatz
				$query ="UPDATE portal_abrechnung SET endsumme_gebuehr_ueberweisung='".$iGebuehren['gebuehr']."',Umsatz_ohne_versand='".$curGesArtikelSumme."' WHERE domain_id='".$strDomain['domain_id']."' AND abrechnung_monat='".$month."' AND abrechnung_jahr='".$year."'";
				DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				
				# Erzeugte Rechnung in Datenbank speichern 
				$query = "SELECT * FROM portal_abrechnung WHERE domain_id='".$strDomain['domain_id']."' AND abrechnung_monat='".$month."' AND abrechnung_jahr='".$year."'";
				$resRechnungExists = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$strRechnungVorhanden = mysqli_fetch_assoc($resRechnungExists);
				
				# Gibt es den Schalter renew_send_email, also Email erneut senden beim Aktuallisieren
				if(!isset($_GET['renew_send_email'])) {
					#$_POST['email_versenden'] = $strRechnungVorhanden['abrechnung_monat'];	
				} else {
					#echo "IN";
					unset($_POST['email_versenden']);
				}
				
			}
			
			
			
			$query ="SELECT * FROM shop_info WHERE domain_id=".$strDomain['domain_id'];
			$resShopInhaber = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strShopInhaber = mysqli_fetch_assoc($resShopInhaber);
			
			
			// Email verschicken 
			if(!isset($_POST['email_versenden'])) {
				
				echo "Email an: ".$strShopInhaber['email_shop_main']." | Summe: ".$curGesArtikelSumme ." EUR | Gebühr ".$iGebuehren['gebuehr']." EUR \n";				
				

				if(isset($strShopInhaber['firma'])) {
					$strFirma = '('.$strShopInhaber['firma'].')';
				} else {
					$strFirma = '';
				}
				
				$html_email = '<h1>Ihre Shopste Rechnung für '.$strShopInhaber['shop_mitgliedsname'].' '.getMonthName($month).'/'.$year.'</h1><br/>
				Hallo '.$strShopInhaber['vorname'].' '.$strShopInhaber['nachname'].' '.$strFirma.', <br/>
				 
				Sie hatten einen Monatsumsatz in Ihrem Shop '.$strShopInhaber['shop_name'].' '.$strDomain['name'].' und Shopste Marktplatz von '.money_format('%2i', $curGesArtikelSumme).' EUR (ohne Versandkosten).<br/>Ihr Monatsumsatz wird mit '.money_format('%2i', $iGebuehren['prozent']).'% Verkaufsprovisionen belegt.
				<br/>
				<br/>
				Bitte überweisen Sie den folgenden Betrag '.money_format('%2i', $iGebuehren['gebuehr']).' EUR innerhalb von 14 Tagen bis spätestens '.date('d.m.Y', strtotime("+14 days")).' auf dieses Konto:<br/>'.$html.'
				<br/>
				<h2>Zahlungsinformationen</h2>
				<strong>Bank:</strong> Volksbank Oldenburg<br/>
	<strong>Inhaber:</strong> Jan Bludau<br/>
	<strong>Kontonummer:</strong> 3430885700<br/>
	<strong>Bankleitzahl:</strong> 28061822<br/>
	<strong>IBAN:</strong> DE94280618223430885700<br/>
	<strong>BIC:</strong> GEN0DEF1EDE <br/>
	<strong>Betrag:</strong> '.money_format('%2i', $iGebuehren['gebuehr']).'<br/>
	<strong>Verwendungszweck:</strong> '.getMonthName($month).'/'.$year.' - '.$strShopInhaber['shop_mitgliedsname'].'<br/>
	
	<br/>Die Umsatz ist nach Umsatzsteuergesetz §19 nicht steuerpflichtig<br/><br/>
	
	Bei telefonischen Rückfragen bitte unter: 0441-30974996 anrufen.<br/>
	
	Viel Spaß dein <a href="https://shopste.com">Shopste.com</a>';
				
				//Create a new PHPMailer instance
				$mail = new PHPMailer();
				// Set PHPMailer to use the sendmail transport
				$mail->isSendmail();
				//Set who the message is to be sent from
				$mail->setFrom('payment@shopste.com', 'Shopste Abrechnung');
				//Set an alternative reply-to address
				$mail->addReplyTo('payment@shopste.com', 'Shopste Abrechnung');
				//Set who the message is to be sent to
				$mail->addAddress($strShopInhaber['email_shop_main'],utf8_decode($strShopInhaber['vorname'].' '.$strShopInhaber['nachname']));
				$mail->AddBCC(CORE_MAIL_SEND_API_BCC,CORE_MAIL_SEND_API_BCC_NAME);
				#$mail->AddBCC();
				//Set the subject line
				$mail->Subject = utf8_decode('Shopste Abrechnung '.getMonthName($month).' '.$year.' für '.$strDomain['name']);
				//Read an HTML message body from an external file, convert referenced images to embedded,
				//convert HTML into a basic plain-text alternative body
				$mail->msgHTML(utf8_decode($html_email), dirname(__FILE__));
				//Replace the plain text body with one created manually
				$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';
				//Attach an image file
				//$mail->addAttachment('images/phpmailer_mini.png');

				//send the message, check for errors
				if (!$mail->send()) {
					#echo "Mailer Error: " . $mail->ErrorInfo;
				} else {
					#echo "Message sent!";
					# UPDATE RECHNUNG Monatsumsatz
					$query ="UPDATE portal_abrechnung SET abrechnung_gesendet='Y' WHERE domain_id='".$strDomain['domain_id']."' AND abrechnung_monat='".$month."' AND abrechnung_jahr='".$year."'";
					DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));	
				}	
			}
		}
		echo "================================================================================================================================\n\n";
		$curGesArtikelSumme=0;
	}
	echo $curGesArtikelSumme.' EUR';
}

function getSubKategorie($iParrentCat,$strIDs,$level) {

	$query = "SELECT * FROM shop_category_parent LEFT JOIN shop_category ON shop_category_parent.shop_cat_id=shop_category.shop_cat_id	WHERE shop_category_parent.shop_cat_parent=".$iParrentCat." AND shop_category.domain_id='".$_SESSION['domain_id']."'  ORDER BY name_".$_SESSION['language']." ASC"; 
 
	$resCat = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
	
	while($strCatMenue = mysqli_fetch_assoc($resCat)) {	
		$strIDs .= " OR shop_cat_id=".$strCatMenue['shop_cat_id'];
		#echo $strIDs; 
		$strIDs = getSubKategorie($strCatMenue['shop_cat_id'],$strIDs,$level+1);
		#echo $strCatMenue['shop_cat_id'];
	}
	
	return $strIDs;	
}	

function isLoggedIn($username, $password,$bLoggedIn) {
	#echo "IN";
	if(isset($username)) {	

			#$domain = DBi::mysql_escape($_SERVER['HTTP_HOST']);
			#$domain = str_replace("www.", "", $domain);
			#$query = "SELECT * from domains WHERE name='$domain'";
			#$domain_res = mysqli_fetch_assoc(DBi::$conn->query($query));
			#print_r($domain_res.$domain);

# AND domain_id='".$domain_res['domain_id']."'
			$query = "SELECT count(*) as anzahl FROM benutzer WHERE username='".$username."' AND bISBlowfish='N'";
			
	#		echo $query;
			$resLogin = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$UserDataCount = mysqli_fetch_assoc($resLogin);
			
			if($UserDataCount['anzahl'] > 0) {
					# AND domain_id='".$domain_res['domain_id']."'"
					$query = "SELECT * FROM benutzer WHERE username='".$username."' AND password='".md5($password)."'";
				
					$resLogin = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$UserData = mysqli_fetch_assoc($resLogin);
 

					
					if(isset($UserData['username']) AND isset($UserData['password'])) {
						# Shop Alle Kategorien ermitteln
						$parent = 0;
						$query = "SELECT * FROM shop_category_parent LEFT JOIN shop_category ON shop_category_parent.shop_cat_id=shop_category.shop_cat_id	WHERE shop_category_parent.shop_cat_parent=$parent AND shop_category.domain_id='".$UserData['domain_id']."'  ORDER BY name_".$_SESSION['language']." ASC";   
						
						$result = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 
						$strShopKategorie = mysqli_fetch_array($result);
						
						#echo "LOGIN_OK~".$UserData['domain_id'].'~'.$strDomain['name'].'~'.$strShopKategorie['shop_cat_id']; 
						$_SESSION['portal_login'] = 1;
						$_SESSION['portal_user'] = $UserData['username'];
						$_SESSION['portal_pwd'] = md5($UserData['password']);
						$_SESSION['portal_domain_id'] = $UserData['domain_id'];
						$_SESSION['portal_eingeloggt_bleiben'] = "Y";
						
						// Cookie setzten - doppelt MD5 
						$res = setcookie("portal_pwd",md5($UserData['password']), time() + 2592000,"/", $_SERVER['SERVER_NAME']);
						$res = setcookie("portal_user",$UserData['username'], time() + 2592000,"/", $_SERVER['SERVER_NAME']);	
						$res = setcookie("portal_eingeloggt",$bLoggedIn, time() + 2592000,"/", $_SERVER['SERVER_NAME']);
						
						$query = "SELECT * FROM domains WHERE domain_id='".$UserData['domain_id']."'";
						$resDomain = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
						$strDomain = mysqli_fetch_assoc($resDomain);
					
						echo "LOGIN_OK~".$UserData['domain_id'].'~'.$strDomain['name'].'~'.$strShopKategorie['shop_cat_id']; 
						return true;
					} else  {
						return false;
					}
						
				return true;
				
			} else {
				# BLOWFISH
				# echo "# AND domain_id='".$domain_res['domain_id']."' --- ".$username;
				 
				$query = "SELECT * FROM benutzer WHERE username='".$username."' AND bISBlowfish='Y'";	

				$resLogin = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 
				while($UserDataCount = mysqli_fetch_assoc($resLogin)) {
					
				#echo "Blow:".$query;
					if(encrypt_decrypt('encrypt',$password) == $UserDataCount['password']) {		


						# Shop Alle Kategorien ermitteln
						$parent = 0;
						$query = "SELECT * FROM shop_category_parent LEFT JOIN shop_category ON shop_category_parent.shop_cat_id=shop_category.shop_cat_id	WHERE shop_category_parent.shop_cat_parent=$parent AND shop_category.domain_id='".$UserDataCount['domain_id']."'  ORDER BY name_".$_SESSION['language']." ASC";   
						
						$result = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 
						$strShopKategorie = mysqli_fetch_array($result);
						
						#echo "LOGIN_OK~".$UserData['domain_id'].'~'.$strDomain['name'].'~'.$strShopKategorie['shop_cat_id']; 
						$_SESSION['portal_login'] = 1;
						$_SESSION['portal_user'] = $UserDataCount['username'];
						$_SESSION['portal_pwd'] = md5($UserDataCount['password']);
						$_SESSION['portal_domain_id'] = $UserDataCount['domain_id'];
						$_SESSION['portal_eingeloggt_bleiben'] = "Y";
						
						// Cookie setzten - doppelt MD5 
						$res = setcookie("portal_pwd",md5($UserDataCount['password']), time() + 2592000,"/", $_SERVER['SERVER_NAME']);
						$res = setcookie("portal_user",$UserDataCount['username'], time() + 2592000,"/", $_SERVER['SERVER_NAME']);	
						$res = setcookie("portal_eingeloggt",$bLoggedIn, time() + 2592000,"/", $_SERVER['SERVER_NAME']);
						
						$query = "SELECT * FROM domains WHERE domain_id='".$UserDataCount['domain_id']."'";
						$resDomain = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
						$strDomain = mysqli_fetch_assoc($resDomain);
					
						echo "LOGIN_OK~".$UserDataCount['domain_id'].'~'.$strDomain['name'].'~'.$strShopKategorie['shop_cat_id']; 
					
						return true;
					}	else {
						return false;
					}			
				}
				return false;
			} 
 
		}
	return false;
	}
	

function getMonthName($month) {
		
		switch($month) {
			case '01':
				return 'Januar';
				break;
			case '02':
				return 'Februar';
				break;
			case '03':
				return 'März';
				break;
			case '04':
				return 'April';
				break;
			case '05':
				return 'Mai';
				break;
			case '06':
				return 'Juni';
				break;
			case '07':
				return 'Juli';
				break;
			case '08':
				return 'August';
				break;
			case '09':
				return 'September';
				break;
			case '10':
				return 'Oktober';
				break;
			case '11':
				return 'November';
				break;
			case '12':
				return 'Dezember';
				break;
		}
	}
	// Datenbankverbindung
	require_once('include/inc_config-data.php');
	require_once('include/inc_basic-functions.php');
	
	#error_reporting(E_ALL);
	#ini_set('display_errors', TRUE); // evtl. hilfreich
	
	#echo $_POST['modus'];
	$_SESSION['domainLanguage'] = 'de';
	$_SESSION['language'] = 'de';
	
	
	// BUG jb domain switcher in Produktverwaltung auf domain_id = 1
	//if(!isset($_GET['domain_id']) && !isset($_POST['domain_id'])) {
	//	$_SESSION['domain_id'] = 1;		
	//}
	
	$_POST = mysql_real_escape_array($_POST);
	$_GET  = mysql_real_escape_array($_GET);
	$_SESSION  = mysql_real_escape_array($_SESSION);
	
	# Commandline Argumente auswerten
	#var_dump($argv);
	#$strCLI = parse_str($argv[1]);
	#var_dump($strCLI);
	if(isset($argv[1])) {
		switch($argv[1]) { 
			case 'cron_portal_abrechnung':
				$_POST['modus'] = 'cron_portal_abrechnung';
				$_POST['cron'] = 'Y';
				break;
			case 'portal_abrechnung_mahnung':
				$_POST['modus'] = 'portal_abrechnung_mahnung';
				break;
			case 'check_all_feeds':
				$_POST['modus'] = 'check_all_feeds';
				break;				
		}
	}
	if(!isset($_POST['modus'])) {
		$_POST['modus'] = $_GET['modus'];
		
	}
	# Domaindaten + Shop_info abrufen
	if(!empty($_GET['domain_id'])) {								
		$strDomain = getDomainAry($_GET['domain_id']);
		#print_r($strDomain);
	}
	switch($_POST['modus']) {
			case 'check_all_feeds':							
				# SimplePie Include!
				$ServerPathComplete = $_SERVER['DOCUMENT_ROOT']; 
				require_once($ServerPathComplete.'/framework/simplepie/autoloader.php');
			
				$feed = new SimplePie();
				$feed->set_cache_location($ServerPathComplete.'/cache/');
				#$feed->enable_order_by_date(true);
			
				$query = "SELECT * FROM modul_rss_quelle WHERE enabled='Y'";
				$resQuelleCheck = DBi::$conn->query($query);
				while($strQuellen = mysqli_fetch_assoc($resQuelleCheck)) {
					ob_flush(); 
					flush();
					#$rssFeedMulti[] = $strQuellen['rss_quelle'];
					$feed->set_feed_url($strQuellen['rss_quelle']);
					$feed->handle_content_type();

					try {
						$feed->init();
					} catch(Exception $ex) {  
						echo "<strong>ERR:Beim abrufen </strong> - ".$strQuellen['rss_quelle']." (".$strQuellen['id'].") - ".$ex.'<br/>';						
					}
					if ($feed->error()) {
						echo "<strong>ERR:Beim verarbeiten </strong> - ".$strQuellen['rss_quelle']." (".$strQuellen['id'].") - ".print_r($feed->error()).'<br/>';
					} else {							
						echo "Alles OK: -".$strQuellen['title_de']." vom ".getDateDE($strQuellen['title_de'])." - ".$strQuellen['rss_quelle'].'<br/>';
					}  

				}			
			break;		
	case 'show_invoice':
		// Login überprüfen
		$chkCookie = admin_cookie_check();

		#echo $chkCookie;
		if($_SESSION['login'] == 1) {		
			$_SESSION['login'] = 1;
		} else {
			exit(0);
		}
	 
		
		$query ="SELECT count(*) as anzahl FROM email_vorlage WHERE domain_id='".$_GET['domain_id']."' AND standard='N' AND typ='RECHNUNG'";
		#echo $query;
		$resEmailCount = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		$strEmailCount = mysqli_fetch_assoc($resEmailCount);
		if($strEmailCount['anzahl'] > 0) {
			# Lade Benutzervorlage
			$query ="SELECT * FROM email_vorlage WHERE domain_id='".$_GET['domain_id']."' AND standard='N' AND typ='RECHNUNG'";
			#echo $query;
			$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
		} else {
			# Lade Defaultvorlage
			$query ="SELECT * FROM email_vorlage WHERE domain_id='0' AND standard='Y' AND typ='RECHNUNG'";
			#echo $query;
			$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
		}
		
		$query ="SELECT count(*) as anzahl FROM email_vorlage_settings WHERE vorlagen_id='".$strEmailVorlage['email_vorlage_id']."'";
		$resEmailSettings = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		$strSettings = mysqli_fetch_assoc($resEmailSettings);
		if($strSettings['anzahl'] > 0) {
			$query ="SELECT * FROM email_vorlage_settings WHERE vorlagen_id='".$strEmailVorlage['email_vorlage_id']."'";
			$resEmailSettings = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strSettings = mysqli_fetch_assoc($resEmailSettings);
			$bEmailSettings = true;
			$iColspan = 5;
		} else {
			$bEmailSettings = false;
			$iColspan = 5;		
		}
		if($strSettings['hasPicture'] == 'N') {
		$html_bestellliste .= '<table width="100%"><tr><td><h3>Nr.</h3></td><td><h3>'.$strTblName.'</h3></td><td><h3>Menge</h3></td><td><h3>Einzelpreis</h3></td><td><h3>Gesamt</h3></td></tr>';
		$iColspan = 4;		
	} else {		
		$html_bestellliste .= '<table width="100%"><tr><td><h3>Nr.</h3></td><td><h3>Bild</h3></td><td><h3>'.$strTblName.'</h3></td><td><h3>Menge</h3></td><td><h3>Einzelpreis</h3></td><td><h3>Gesamt</h3></td></tr>';
		$iColspan = 5;
	}
#	echo "IN";
	// Alle Artikel durchlaufen
	$shop_artikel_preisGes = 0;
	$shop_artikel_gewicht = 0;
	
	$query = "SELECT * FROM shop_order_list LEFT JOIN shop_order ON shop_order_list.id_shop_order = shop_order.shop_order_id  WHERE id_shop_order='".$_GET['order_id']."'";
	$resItems = DBi::$conn->query($query);
	while($strItem = mysqli_fetch_assoc($resItems)) {
		$shop_artikel_anzahl++;
		
		$query ="SELECT *,shop_item.name_de as shop_name FROM shop_item JOIN menue ON shop_item.menue_id = menue.id WHERE shop_item.shop_item_id ='".$strItem['shop_item_id']."'";
		$resItem = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		$Cartdata = mysqli_fetch_assoc($resItem);
		
		$shop_artikel_preis = str_replace(",",".",$Cartdata['preis']);
		$shop_artikel_preisGes += $shop_artikel_preis * $strItem['order_menge'];
		$shop_artikel_gewicht += $Cartdata['gewicht'] * $strItem['order_menge'];	
		
		# Verschiedene Mehrwertsteuer speichern
		$var2 = ($Cartdata['item_mwst'] / 100) + 1;
		#echo $var2.'<br/>';
		#$var = floatval($var2);
		#echo ($var);
		$shop_artikel_mwst[$Cartdata['item_mwst']] = (($shop_artikel_preisGes * $var2) - $shop_artikel_preisGes);
		#echo $shop_artikel_mwst[$Cartdata['item_mwst']].' - '.$Cartdata['item_mwst'].'<br/>';
		$domain_id = $Cartdata['domain_id'];
		// Seite wo Artikel liegt abrufen
		#$pathItem = 'http://'.$_SERVER['SERVER_NAME'].'/'.getPathUrl($_SESSION['language'],$Cartdata['menue_id']);
		
		$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$strItem['shop_item_id']."'";
		$strBild = mysqli_fetch_assoc(DBi::$conn->query($query));
		$strBild['picture_url'] = 'http://'.$_SERVER['SERVER_NAME'].'/'.$strBild['picture_url'];
		
		#$preis = str_replace(",",".",$strItemDetailAry[2]);
		$preisGes = $strItem['order_menge'] * $shop_artikel_preis;
		
		#### EXTRA Eigenschaften aus der Bestellung ermitteln
		if(count($strItemDetailAry) > 3) { 			
			#echo print_r($strItemDetailAry).'<br/>';
			$strItemDetailAry[3] = DBi::mysql_escape($strItemDetailAry[3],DBi::$conn);
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
					$resAdditionalTypes = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
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
			
			$html_bestellliste .= '<td>'.$Cartdata['shop_name'].'<font size="1">'.$strItem['shop_item_additional_info'].''.$strItem['shop_item_comment'].'</font></td>';
		} else {
			$html_bestellliste .= '<td><img src="'.$strBild['picture_url'].'" width="50px" Height="50px"/></a></td>';
			$html_bestellliste .= '<td>'.$Cartdata['shop_name'].'<font size="1">'.$strExtraName.'</font></td>';
		}
		
		$html_bestellliste .= '<td stlye="text-align: right;">'.$strItem['order_menge'].'x</td>';
		$html_bestellliste .= '<td stlye="text-align: right;">'.number_format($shop_artikel_preis, 2, ',', '.').' EUR</td>';
		$html_bestellliste .= '<td stlye="text-align: right;">'.number_format($preisGes, 2, ',', '.').' EUR</td>';
		$html_bestellliste .= '<tr>';
		$strKundenID = $strItem['ges_order_customer_id'];
	}
	#$html_bestellliste .= '<tr><td colspan="5" style="text-align:right">Zwischensumme</td><td>'.str_replace(".",",",$shop_artikel_preisGes).' EUR</td><td>&nbsp;</td></tr>';
	$html_bestellliste .= $strVersandHTML;
	$shop_artikel_gesamt = $shop_artikel_preisGes + $shop_artikel_preisVersand;
			
	$query ="SELECT * FROM domains WHERE domain_id ='".$_GET['domain_id']."'";
	$res = DBi::$conn->query($query);
	$domain_data = mysqli_fetch_assoc($res);
	#Mehrwertsteuer vom Versand
	$shop_artikel_mwst[$shop_artikel_preisVersand_mwst] += (($shop_artikel_preisVersand / 100) * $shop_artikel_preisVersand_mwst);
	
	#print_r($shop_artikel_mwst);
	if(empty($strSettings['cart_show_mwst']) || $strSettings['cart_show_mwst'] == 'Y') {
		#'MwSt_inkl','MwSt_exkl','MwSt_befreit'
		switch($domain_data['shop_mwst_setting']) {
			case "MwSt_inkl":
				$strMWSTText = 'inkl. MwSt.';
				foreach ($shop_artikel_mwst as $key => $value) {
				#echo $value;
					if($key != '') {
						$html_bestellliste .= '<tr><td colspan="'.$iColspan.'" style="text-align:right">inkl.'.$key.'% MwSt</td><td>'.number_format($value, 2, ',', '.').' EUR</td><td>&nbsp;</td></tr>';
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
	#echo $cart_mwst_ges;
	$html_bestellliste .= '<tr><td colspan="'.$iColspan.'" style="text-align:right"><strong>'.$strCartEndsummeText.'</strong></td><td><strong>'.number_format(($shop_artikel_preisGes + $shop_artikel_preisVersand + $cart_mwst_ges), 2, ',', '.').' EUR<strong></td><td>&nbsp;</td></tr></table>';
	
	$strEmailVorlage['content'] = str_replace('###BESTELLUNG_AUFLISTUNG####',$html_bestellliste,$strEmailVorlage['content']);
	
		$html_rechnung = $strOrderAnzahl['anzahl'];
		
		$query = "SELECT * FROM shop_order_customer WHERE shop_order_customer_id='".$strKundenID."'";
		#echo $query;
		$resCustomer = DBi::$conn->query($query);
		$strCustomer = mysqli_fetch_assoc($resCustomer);
		
		$query = "SELECT * FROM shop_info WHERE domain_id='".$_GET['domain_id']."'";
		$resShopData = DBi::$conn->query($query);
		$strShopData = mysqli_fetch_assoc($resShopData);
		
		$strEmailVorlage['content'] = str_replace('###SHOP_NAME###',$ShopInfoData['shop_name'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###BESTELL_VORNAME###',$strCustomer['vorname'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###BESTELL_NACHNAME###',$strCustomer['nachname'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###RDATUM###',date("d.m.Y"),$strEmailVorlage['content']);

		$strEmailVorlage['content'] = str_replace('###BESTELL_STRASSE###',$strCustomer['strasse_hnr'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###BESTELL_PLZ###',$strCustomer['plz'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###BESTELL_ORT###',$strCustomer['stadt'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###BESTELL_LAND###',$strCustomer['land'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###SHOP_LAND###',$strShopData['land'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###SHOP_ORT###',$strShopData['land'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###SHOP_ORT###',$strShopData['stadt'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###SHOP_PLZ###',$strShopData['plz'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###SHOP_STRASSE###',$strShopData['strasse_hnr'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###SHOP_TELEFON###',$strShopData['telefon'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###SHOP_VORNAME###',$strShopData['vorname'],$strEmailVorlage['content']);

		$strEmailVorlage['content'] = str_replace('###SHOP_NACHNAME##',$strShopData['nachname'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###SHOP_FIRMA###',$strShopData['nachname'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###SHOP_EMAIL###',$strShopData['email_shop_main'],$strEmailVorlage['content']);
 		
		if($strCustomer['firma'] != '') {
			
			$html_firma = $strCustomer['firma'].'<br/>';	
			$strEmailVorlage['content'] = str_replace('###BESTELL_FIRMA###',$html_firma,$strEmailVorlage['content']);
		
		} else {
			$strEmailVorlage['content'] = str_replace('###BESTELL_FIRMA###','',$strEmailVorlage['content']);
		}
		
		$query = "SELECT * FROM shop_order LEFT JOIN shop_order_nr ON shop_order.shop_order_nr_id = shop_order_nr.shop_order_nr_id WHERE shop_order_id='".$_GET['order_id']."'";
		#echo $query;
		$resOrder = DBi::$conn->query($query);
		$strOrder = mysqli_fetch_assoc($resOrder);
		
		if(!empty($strOrder['lieferzeit_datum'])) {
			$strEmailVorlage['content'] = str_replace('###BESTELL_LIEFERZEIT###',"schnellst­m&ouml;glich <br/>Bestellzeit: ".getDateDE($strOrder['lieferzeit_datum']),$strEmailVorlage['content']);
		} else {
			$strEmailVorlage['content'] = str_replace('###BESTELL_LIEFERZEIT###',getDateDE($strOrder['lieferzeit_datum']),$strEmailVorlage['content']);		
		}
		
		if(!empty($strOrder['order_comment'])) {
			$strEmailVorlage['content'] = str_replace('###BESTELL_BEMERKUNG###','<strong>Bemerkung zur Lieferung:</strong> '.$strOrder['order_comment'].'<br/>',$strEmailVorlage['content']);				
		} else {		
			$strEmailVorlage['content'] = str_replace('###BESTELL_BEMERKUNG###',"",$strEmailVorlage['content']);				
		}
		if(!empty($strCustomer['telefon'])) {
			$html_tele = $strCustomer['telefon'].'<br/>';
			$strEmailVorlage['content'] = str_replace('###TELEFON###',$html_tele,$strEmailVorlage['content']);
		} else {
			$strEmailVorlage['content'] = str_replace('###TELEFON###','',$strEmailVorlage['content']);		
		}

		$strEmailVorlage['content'] = str_replace('###ADDRESSE###',$strCustomer['strasse_hnr']." ".$strCustomer['plz']." ".$strCustomer['stadt'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###RNR###',$strOrder['nr'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###BESTELLUNG_AUFLISTUNG####',$html_bestellliste,$strEmailVorlage['content']);
		echo '<link rel="stylesheet" type="text/css" href="/print.css" media="print" />';
		echo '<input class="NavButton" id="btnPrint" type="button" value="Diese Seite Ausdrucken" onClick="window.print();">';
		echo $strEmailVorlage['content'];
		#exit(0);
		break;
case 'acp_show_hits':
		
			if(isset($_GET['api-key'])) {
				if($_GET['api-key'] != CORE_CRON_API_KEY) {
					exit(0);
				}	
			} else {
				exit(0);
			}
			echo 'Listen: <a href="/api.php?modus=acp_show_hits&modus_sub=DAY">Tag</a> | <a href="/api.php?modus=acp_show_hits&modus_sub=WEEK">Woche</a> | <a href="/api.php?modus=acp_show_hits&modus_sub=MONTH">Monat</a> | <a href="/api.php?modus=acp_show_hits&modus_sub=YEAR">Jahr</a><br/<br/>';
			
			echo 'Hits Gesammt: <a href="/api.php?modus=acp_show_hits&modus_sub=DAY_HIT">Tag</a> | <a href="/api.php?modus=acp_show_hits&modus_sub=WEEK_HIT">Woche</a> | <a href="/api.php?modus=acp_show_hits&modus_sub=MONTH_HIT">Monat</a> | <a href="/api.php?modus=acp_show_hits&modus_sub=YEAR_HIT">Jahr</a><br/<br/><br/>';
		
			echo 'Heute: '.date("d.m.Y H:i:s").'<br/>';
		
			switch($_GET['modus_sub']) {
				case 'MONTH':
					$query = "SELECT count(*) anzahl,page_id,monthname(menue_visitors.created_at),menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id group by page_id,YEAR(menue_visitors.created_at),month(menue_visitors.created_at) ORDER BY YEAR(menue_visitors.created_at) DESC , month(menue_visitors.created_at) DESC, anzahl DESC ,menue.created_at DESC LIMIT 0,50";
					break;
				case 'DAY':
					#$query = "SELECT count(*) anzahl,page_id,dayname(menue_visitors.created_at),menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id WHERE content_type='rss_content' group by page_id,YEAR(menue_visitors.created_at),DAY(menue_visitors.created_at) ORDER BY anzahl DESC, YEAR(menue_visitors.created_at) ASC LIMIT 0,10";
					$query = "SELECT DATE(menue_visitors.created_at), count(*) anzahl,page_id,menue.* FROM menue_visitors LEFT JOIN menue ON menue.id = menue_visitors.page_id WHERE menue_visitors.created_at >= CURDATE() GROUP BY page_id ORDER BY  anzahl DESC LIMIT 0,50"; 
					break;			
				case 'WEEK':
					$query = "SELECT count(*) anzahl,page_id,CONCAT(YEAR(menue_visitors.created_at), '/', WEEK(menue_visitors.created_at)) AS week_name, YEAR(menue_visitors.created_at), WEEK(menue_visitors.created_at),menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id group by week_name,page_id ORDER BY YEAR(menue_visitors.created_at) DESC, WEEK(menue_visitors.created_at) DESC,anzahl DESC,menue.created_at DESC  LIMIT 0,50";
					break;
				case 'YEAR':
					$query = "SELECT count(*) anzahl,page_id,YEAR(menue_visitors.created_at) AS jahr,menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id  group by page_id ORDER BY anzahl DESC,YEAR(menue_visitors.created_at) ASC,menue.created_at DESC LIMIT 0,50";
					break;
				case 'DAY_HIT':
					$query = "SELECT count(*) anzahl FROM menue_visitors LEFT JOIN menue ON menue.id = menue_visitors.page_id WHERE menue_visitors.created_at >= CURDATE() ORDER BY anzahl DESC";
					break;
				case 'WEEK_HIT':
					$query = "SELECT count(*) anzahl,page_id,CONCAT(YEAR(menue_visitors.created_at), '/', WEEK(menue_visitors.created_at)) AS week_name, YEAR(menue_visitors.created_at), WEEK(menue_visitors.created_at),menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id group by week_name ORDER BY YEAR(menue_visitors.created_at) DESC, WEEK(menue_visitors.created_at) DESC,anzahl DESC";
					break;
				case 'MONTH_HIT':
					$query = "SELECT count(*) anzahl,page_id,monthname(menue_visitors.created_at) as month_name,menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id  group by YEAR(menue_visitors.created_at),month(menue_visitors.created_at) ORDER BY YEAR(menue_visitors.created_at) DESC , month(menue_visitors.created_at) DESC, anzahl DESC";
					break;
				case 'YEAR_HIT':
					$query = "SELECT count(*) anzahl,page_id,YEAR(menue_visitors.created_at) AS jahr,menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id group by YEAR(menue_visitors.created_at)  ORDER BY YEAR(menue_visitors.created_at) DESC,anzahl DESC";
					break;					
			} 
		$resTop = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		
		$dataTextHTML['typ'] = 'rss_content_popular';
		$text = '<div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'"><ul class="modul_menue_normal">';
		$iCount = 1;
		$iHitsGesamt = 0;
		   
		while($strContent = mysqli_fetch_assoc($resTop)) {
			
			switch($_GET['modus_sub']) {
				case 'MONTH':
					#$query = "SELECT count(*) anzahl,page_id,monthname(menue_visitors.created_at),menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id group by page_id,YEAR(menue_visitors.created_at),month(menue_visitors.created_at) ORDER BY YEAR(menue_visitors.created_at) DESC , month(menue_visitors.created_at) DESC, anzahl DESC ,menue.created_at DESC LIMIT 0,50";
					
					$path = getPathUrl($_SESSION['language'],$strContent['id']);
					$strLink = 'https://'.$_SERVER['SERVER_NAME'].'/'.$path;
					
					if(!isset($strContent['created_at'])) {
						$strContent['created_at'] = date("Y-m-d H:i:s");
					}
					# 1. 0 = Bewertung
					
					$query = "SELECT count(*) as anzahl FROM statistik WHERE menue_id='".$strContent['id']."' AND content_group_by='".date("m/Y")."' AND content_modul='analyse_by_hits_all'";
					$strVorhanden = mysqli_fetch_assoc(DBi::$conn->query($query));
					
					if($strVorhanden['anzahl'] == 0) {
						#
						$query = "INSERT INTO statistik(name,http_link,content_created_at,content_hits,content_modul,content_bewertung,content_group_typ,content_group_by,menue_id,menue_typ) VALUES('".DBi::mysql_escape($strContent['name_de'],DBi::$conn)."','".DBi::mysql_escape($strLink,DBi::$conn)."','".$strContent['created_at']."','".$strContent['anzahl']."','analyse_by_hits_all','0','MONTH','".date("m/Y")."','".$strContent['id']."','".$strContent['content_type']."')";
						#echo $query;
						$res = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 						
					} else {
						$query = "UPDATE statistik SET content_hits='".$strContent['anzahl']."' WHERE menue_id='".$strContent['id']."' AND content_group_by='".date("m/Y")."' AND content_modul='analyse_by_hits_all'";
						#echo $query;
						DBi::$conn->query($query);
					}
					
					break;
				case 'DAY':
					#$query = "SELECT count(*) anzahl,page_id,dayname(menue_visitors.created_at),menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id WHERE content_type='rss_content' group by page_id,YEAR(menue_visitors.created_at),DAY(menue_visitors.created_at) ORDER BY anzahl DESC, YEAR(menue_visitors.created_at) ASC LIMIT 0,10";
					#$query = "SELECT DATE(menue_visitors.created_at), count(*) anzahl,page_id,menue.* FROM menue_visitors LEFT JOIN menue ON menue.id = menue_visitors.page_id WHERE menue_visitors.created_at >= CURDATE() GROUP BY page_id ORDER BY  anzahl DESC LIMIT 0,50"; 
					
		 			$path = getPathUrl($_SESSION['language'],$strContent['id']);
					$strLink = 'https://'.$_SERVER['SERVER_NAME'].'/'.$path;
					
					if(!isset($strContent['created_at'])) {
						$strContent['created_at'] = date("Y-m-d H:i:s");
					}
					# 1. 0 = Bewertung
					
					$query = "SELECT count(*) as anzahl FROM statistik WHERE menue_id='".$strContent['id']."' AND content_group_by='".date("Y-m-d")."' AND content_modul='analyse_by_hits_all'";
					$strVorhanden = mysqli_fetch_assoc(DBi::$conn->query($query));
					
					if($strVorhanden['anzahl'] == 0) {
						$query = "INSERT INTO statistik(name,http_link,content_created_at,content_hits,content_modul,content_bewertung,content_group_typ,content_group_by,menue_id,menue_typ) VALUES('".DBi::mysql_escape($strContent['name_de'],DBi::$conn)."','".DBi::mysql_escape($strLink,DBi::$conn)."','".$strContent['created_at']."','".$strContent['anzahl']."','analyse_by_hits_all','0','DAY','".date("Y-m-d")."','".$strContent['id']."','".$strContent['content_type']."')";						
						$res = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 						
					} else {
						$query = "UPDATE statistik SET content_hits='".$strContent['anzahl']."' WHERE menue_id='".$strContent['id']."' AND content_group_by='".date("Y-m-d")."' AND content_modul='analyse_by_hits_all'";
						DBi::$conn->query($query);
					}

					
					#$strContent['anzahl']
					
					break;			
				case 'WEEK':
					#$query = "SELECT count(*) anzahl,page_id,CONCAT(YEAR(menue_visitors.created_at), '/', WEEK(menue_visitors.created_at)) AS week_name, YEAR(menue_visitors.created_at), WEEK(menue_visitors.created_at),menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id group by week_name,page_id ORDER BY YEAR(menue_visitors.created_at) DESC, WEEK(menue_visitors.created_at) DESC,anzahl DESC,menue.created_at DESC  LIMIT 0,50";
					
					$path = getPathUrl($_SESSION['language'],$strContent['id']);
					$strLink = 'https://'.$_SERVER['SERVER_NAME'].'/'.$path;
					
					if(!isset($strContent['created_at'])) {
						$strContent['created_at'] = date("Y-m-d H:i:s");
					}
					# 1. 0 = Bewertung
					
					$query = "SELECT count(*) as anzahl FROM statistik WHERE menue_id='".$strContent['id']."' AND content_group_by='".date("W/Y")."' AND content_modul='analyse_by_hits_all'";
					$strVorhanden = mysqli_fetch_assoc(DBi::$conn->query($query));
					
					if($strVorhanden['anzahl'] == 0) {
						$query = "INSERT INTO statistik(name,http_link,content_created_at,content_hits,content_modul,content_bewertung,content_group_typ,content_group_by,menue_id,menue_typ) VALUES('".DBi::mysql_escape($strContent['name_de'],DBi::$conn)."','".DBi::mysql_escape($strLink,DBi::$conn)."','".$strContent['created_at']."','".$strContent['anzahl']."','analyse_by_hits_all','0','WEEK','".date("W/Y")."','".$strContent['id']."','".$strContent['content_type']."')";						
						$res = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 						
					} else {
						$query = "UPDATE statistik SET content_hits='".$strContent['anzahl']."' WHERE menue_id='".$strContent['id']."' AND content_group_by='".date("W/Y")."' AND content_modul='analyse_by_hits_all'";  
						DBi::$conn->query($query);
					}
					
					break;
				case 'YEAR':
					#$query = "SELECT count(*) anzahl,page_id,YEAR(menue_visitors.created_at) AS jahr,menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id  group by page_id ORDER BY anzahl DESC,YEAR(menue_visitors.created_at) ASC,menue.created_at DESC LIMIT 0,50";
					$path = getPathUrl($_SESSION['language'],$strContent['id']);
					$strLink = 'https://'.$_SERVER['SERVER_NAME'].'/'.$path;
					
					if(!isset($strContent['created_at'])) {
						$strContent['created_at'] = date("Y-m-d H:i:s");
					}
					# 1. 0 = Bewertung
					
					$query = "SELECT count(*) as anzahl FROM statistik WHERE menue_id='".$strContent['id']."' AND content_group_by='".date("Y")."' AND content_modul='analyse_by_hits_all'";
					$strVorhanden = mysqli_fetch_assoc(DBi::$conn->query($query));
					
					if($strVorhanden['anzahl'] == 0) {
						$query = "INSERT INTO statistik(name,http_link,content_created_at,content_hits,content_modul,content_bewertung,content_group_typ,content_group_by,menue_id,menue_typ) VALUES('".DBi::mysql_escape($strContent['name_de'],DBi::$conn)."','".DBi::mysql_escape($strLink,DBi::$conn)."','".$strContent['created_at']."','".$strContent['anzahl']."','analyse_by_hits_all','0','YEAR','".date("Y")."','".$strContent['id']."','".$strContent['content_type']."')";						
						#echo $query;
						$res = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 						
					} else {
						$query = "UPDATE statistik SET content_hits='".$strContent['anzahl']."' WHERE menue_id='".$strContent['id']."' AND content_group_by='".date("Y")."' AND content_modul='analyse_by_hits_all'";
						#echo $query;
						DBi::$conn->query($query);
					}					
					break;
				case 'DAY_HIT':
					$query = "SELECT count(*) anzahl FROM menue_visitors LEFT JOIN menue ON menue.id = menue_visitors.page_id WHERE menue_visitors.created_at >= CURDATE() ORDER BY anzahl DESC";
					break;
				case 'WEEK_HIT':
					$query = "SELECT count(*) anzahl,page_id,CONCAT(YEAR(menue_visitors.created_at), '/', WEEK(menue_visitors.created_at)) AS week_name, YEAR(menue_visitors.created_at), WEEK(menue_visitors.created_at),menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id group by week_name ORDER BY YEAR(menue_visitors.created_at) DESC, WEEK(menue_visitors.created_at) DESC,anzahl DESC";
					break;
				case 'MONTH_HIT':
					$query = "SELECT count(*) anzahl,page_id,monthname(menue_visitors.created_at) as month_name,menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id  group by YEAR(menue_visitors.created_at),month(menue_visitors.created_at) ORDER BY YEAR(menue_visitors.created_at) DESC , month(menue_visitors.created_at) DESC, anzahl DESC";
					break;
				case 'YEAR_HIT':
					$query = "SELECT count(*) anzahl,page_id,YEAR(menue_visitors.created_at) AS jahr,menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id group by YEAR(menue_visitors.created_at)  ORDER BY YEAR(menue_visitors.created_at) DESC,anzahl DESC";
					break;					
			} 
			
			$strExtend ='';
			switch($_GET['modus_sub']) {
				case 'MONTH':
					break;
				case 'DAY':
					break;			
				case 'WEEK':
					break;
				case 'YEAR':
					break;
				case 'DAY_HIT':

					
					break;
				case 'WEEK_HIT':
				
					if(!isset($iOldAnzahl)) {
						$iOldAnzahl = 0;
					} else {
						$iProzent = 100 - ((100 / $iOldAnzahl) * $strContent['anzahl']);
						$strExtend = " Änderung: ".round($iProzent,2)."%";
						$iOldAnzahl = $strContent['anzahl'];
					}
						
					break;
				case 'MONTH_HIT':
					if(!isset($iOldAnzahl)) {
						$iOldAnzahl = 0;
					} else {
						$iProzent = 100 - ((100 / $iOldAnzahl) * $strContent['anzahl']);
						$strExtend = " Änderung: ".round($iProzent,2)."%";
						$iOldAnzahl = $strContent['anzahl'];
					}				
					break;
				case 'YEAR_HIT':
					if(!isset($iOldAnzahl)) {
						$iOldAnzahl = 0;
					} else {
						$iProzent = 100 - ((100 / $iOldAnzahl) * $strContent['anzahl']);
						$strExtend = " Änderung: ".round($iProzent,2)."%";
						$iOldAnzahl = $strContent['anzahl'];
					}				
					break;					
			} 
			
			if(strpos($_GET['modus_sub'],"_") === false) {		
				$path = getPathUrl($_SESSION['language'],$strContent['id']);
				$strLink = 'https://'.$_SERVER['SERVER_NAME'].'/'.$path;
				
				$d=explode(" ",$strContent['created_at']);
				$d2=explode("-",$d[0]);
				
				if(count($d2) > 1) {
					$unix=mktime(0,0,0,$d2[1],$d2[2],$d2[0]);
					#echo $unix;
					#print_r($d2);
					$stamp=time();
					$diff=$stamp-$unix;
					$diff=$diff/86400;
					$strVergangen = 'Tage vergangen: '.floor($diff);
				} else {
					$strVergangen = '';
				}
				$iHitsGesamt += $strContent['anzahl'];				
				
				#echo 'Es sind '.floor($diff).' Tage seit dem '.$datum.' vergangen';				
				# Mit Link 
				if(!empty($strContent['name_de'])) {
					$text .= '<li class="menue_side_item">'.$iCount.' | '.$strVergangen.' | <a href="'.DBi::mysql_escape($strLink,DBi::$conn).'" title="'.$titel_link.'">'.$strContent['name_de'].'</a> ('.number_format($strContent['anzahl'],0,',','.').') '.$strExtend.'</li>'; 
				}
			} else {
				echo $strContent['jahr'].$strContent['month_name'].$strContent['week_name'].' - '.number_format($strContent['anzahl'],0,',','.').' '.$strExtend.'<br/>';
			}
			$iCount++;
		}
		
		$strEndText = 'Hits: '.$iHitsGesamt.'x';
		$strEndText .= $text;
		$strEndText .= '</ul></div>';
			
		echo $strEndText;
			break;			
	 			
		# domain_id
		# config_name
		# config_value
		case 'setDomainSetting':
		
			$query ="SELECT count(*) as anzahl FROM domain_settings WHERE domain_id='".$_POST['domain_id']."' AND name='".$_POST['config_name']."'";
			$strAnzahl = mysqli_fetch_assoc(DBi::$conn->query($query));
			if($strAnzahl['anzahl'] > 0) {
				$query = "UPDATE domain_settings SET value='".$_POST['config_value']."' WHERE domain_id='".$_POST['domain_id']."' AND name='".$_POST['config_name']."'";
				DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			} else {
				$query = "INSERT INTO domain_settings(domain_id,name,value) VALUES('".$_POST['domain_id']."','".$_POST['config_name']."','".$_POST['config_value']."')";
				DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			}
			
			break;
			
		case 'shopste_shop_item_delete':
			$query = "SELECT * FROM shop_item WHERE shop_item_id='".$_GET['shop_item_id']."'";
			$strShopIDData = mysqli_fetch_assoc(DBi::$conn->query($query));
			
			$query = "SELECT count(*) as anzahl FROM menue_parent WHERE parent_id='".$strShopIDData['menue_id']."'";
			$strParentAnzahl = mysqli_fetch_assoc(DBi::$conn->query($query));
			if($strParentAnzahl['anzahl'] > 0) {
				echo "<h2>Seite NICHT gel&ouml;scht - Unterkategorie Problem</h2>
				<strong>Bitte löschen Sie zuerst die Unterkategorie, um eine Zerstörrung des Menüs zu verhindern.</strong><br/><br/>";
				exit;
			} else {
				# MODULE IN SEITE HOLEN
				$query = "SELECT * FROM module_in_menue WHERE menue_id='".$strShopIDData['menue_id']."'";
				$resModule = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				while($strModule = mysqli_fetch_assoc($resModule)) {
					# MODULE AUS EIGENER MODULTABELLE LÖSCHEN
					$query = "DELETE FROM modul_".$strModule['typ']." WHERE id='".$strModule['modul_id']."'";
					DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));			
				}
				
				$query = "DELETE FROM module_in_menue WHERE menue_id='".$strShopIDData['menue_id']."'";
				#echo $query.'<br/>';
				DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));		

				$query = "DELETE FROM menue_parent WHERE menue_id='".$strShopIDData['menue_id']."'";
				DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));		
				#echo $query.'<br/>';
				$query = "DELETE FROM menue WHERE id='".$strShopIDData['menue_id']."'";
				DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));	

				$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$strShopIDData['shop_item_id']."'";
				$resPicture = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$path = realpath($_SERVER["DOCUMENT_ROOT"]);
				while($strPicture = mysqli_fetch_assoc($resPicture)) {
					// Existiert Datei 
					if (file_exists($path.$strPicture['picture_url'])) {
						// Orginal
						echo "L&ouml;sche Orginalbild: '".$strPicture['picture_url']."'<br/>";
						unlink($path.$strPicture['picture_url']);
						
						$strBild = str_replace("/produkte/orginal/","/produkte/kategorie/",$strPicture['picture_url']);
						if (file_exists($path.$strBild)) {	
							echo "L&ouml;sche Kategoriebild: '".$strPicture['picture_url']."'<br/>";
							unlink($path.$strBild);	
						}
						$strBild = str_replace("/produkte/orginal/","/produkte/detail/",$strPicture['picture_url']);
						if (file_exists($path.$strBild)) {
							echo "L&ouml;sche Detailansicht: '".$strPicture['picture_url']."'<br/>";
							unlink($path.$strBild);
						}
						
					}
				}
				
				$query = "DELETE FROM shop_item WHERE shop_item_id='".$strShopIDData['shop_item_id']."'";
				DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				#echo $query.'<br/>';
				echo "<h2>Shop Artikel und CMS Seite erfolgreich gel&ouml;scht!</h2>";
				
				# Shop Info abrufen
				$query = "SELECT * FROM shop_info WHERE domain_id='".$strShopIDData['domain_id']."'";
				$resShopInfo = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$strBenutzer = mysqli_fetch_assoc($resShopInfo);
				
				# Domain Info abrufen
				$query = "SELECT * FROM domains WHERE domain_id='".$strShopIDData['domain_id']."'";
				$resShopInfo = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$strDomain = mysqli_fetch_assoc($resShopInfo);
				
				
				// Email verschicken 
				
				$strURL_shop = getPathUrl($_SESSION['language'],$strShopIDData['menue_id']);
				
				$html = utf8_decode('<h1>Artikel über API gel&ouml;scht auf Shopste.com</h1>
				
				Hallo '.$strBenutzer['vorname'].' '.$strBenutzer['nachname'].',<br/>
				<br/>'.
				$strShopIDData['name_de'].' - '.$strShopIDData['preis'].' EUR | Menge: '.$strShopIDData['menge'].' - Artikelnummer: '.$strShopIDData['item_number'].'<br/><br/><a href="'.$strDomain['name'].'/'.$strURL_shop.'"/>'.$strShopIDData['name_de'].'</a>;
				<br/><br/>
				Ihr Shopste Team');
				
				//Create a new PHPMailer instance
				$mail = new PHPMailer();
				// Set PHPMailer to use the sendmail transport
				$mail->isSendmail();
				//Set who the message is to be sent from
				$mail->setFrom(CORE_MAIL_FROM_API, CORE_MAIL_FROM_API_NAME);
				//Set an alternative reply-to address
				$mail->addReplyTo(CORE_MAIL_FROM_API, CORE_MAIL_FROM_API_NAME);
				//Set who the message is to be sent to
				$mail->addAddress($strBenutzer['email_shop_main'],utf8_decode($strBenutzer['vorname'].' '.$strBenutzer['nachname']));
				$mail->AddBCC(CORE_MAIL_SEND_API_BCC,CORE_MAIL_SEND_API_BCC_NAME);
				#$mail->AddBCC();
				//Set the subject line
				$mail->Subject = utf8_decode('Shopste Artikel API gelöscht: '.$strShopIDData['name_de']);
				//Read an HTML message body from an external file, convert referenced images to embedded,
				//convert HTML into a basic plain-text alternative body
				$mail->msgHTML(utf8_decode($html), dirname(__FILE__));
				//Replace the plain text body with one created manually
				$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';
				//Attach an image file
				//$mail->addAttachment('images/phpmailer_mini.png');

				//send the message, check for errors
				if (!$mail->send()) {
					#echo "Mailer Error: " . $mail->ErrorInfo;
				} else {
					#echo "Message sent!";
				}
			}
			break;
		case 'shopste_pathid2url':
			$query = "SELECT * FROM domains WHERE domain_id='".$_GET['domain_id']."'";
			$resDomain = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strDomain = mysqli_fetch_assoc($resDomain);
			$strLink = 'http://'.$strDomain['name'].'/'.getPathUrl($_SESSION['language'],$_GET['page_id']);			
			echo $strLink;
			break;
		case 'portal_umkreis_plz':
			ini_set('display_errors', 1); ini_set('html_errors', 1);
			#require_once('framework/ogdbDistance/ogdbDistance.lib.php');			
			#var_dump("Umkreis:\n".var_export(ogdbRadius($_POST['portal_plz'],$_POST['portal_umkreis_km']),TRUE));
			#$strPLZArray = ogdbRadius($_POST['portal_plz'],$_POST['portal_umkreis_km']);
			if(empty($_POST['portal_plz'])) {
				$_POST['portal_plz'] = $_POST['txtPortalUmkreisPLZ'];
			}
			
			if(is_numeric($_POST['portal_plz'])) {
					$strQu = "src.zc_zip ='".$_POST['portal_plz']."'";
			} else {
				if(strlen($_POST['portal_plz']) > 3) {
					$strQu = "src.zc_location_name LIKE '".$_POST['portal_plz']."%'";
				} else {
					exit(0);
				}
			}
			
			$query = "	SELECT dest.zc_id, dest.zc_zip , dest.zc_location_name, ACOS( SIN(RADIANS(src.zc_lat)) * SIN(RADIANS(dest.zc_lat)) + COS(RADIANS(src.zc_lat)) * COS(RADIANS(dest.zc_lat)) * COS(RADIANS(src.zc_lon) - RADIANS(dest.zc_lon)) ) * 6380 AS distance FROM zip_coordinates dest CROSS JOIN zip_coordinates src WHERE ".$strQu." AND dest.zc_id <> src.zc_id HAVING distance < ".$_POST['portal_umkreis_km']." ORDER BY distance;";
			#echo $query;
			$resUmkreisErg = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$bfound = false;
			$bCSV =true;
			$strTmpName ='';
			$strOutput ='';
			while($strUmkreisErg = mysqli_fetch_assoc($resUmkreisErg)) {
				$bfound = true;
				if($strUmkreisErg['zc_location_name'] == $strTmpName) {
					$strOutput .= $strUmkreisErg['zc_zip']."|";
				} else {
					if($bCSV  == true) {
						$strOutput .= "  \n ";
						$strOutput .= $strUmkreisErg['zc_location_name']."|".$strUmkreisErg['zc_zip']."|".round($strUmkreisErg['distance'],2)."|";
					}else {
						$strOutput .="<br/><strong>Stadt:</strong>".$strUmkreisErg['zc_location_name'].'<font size="1">('.$strUmkreisErg['zc_zip'].')</font> - <strong>Entfernung:</strong>'.round($strUmkreisErg['distance'],2).' KM<br/>';
					}
					$strTmpName = $strUmkreisErg['zc_location_name'];
				}
					
			}
			if($bfound == false) {
				echo "Keine Suchtreffer";
			}
			echo $strOutput;
			break;
		case 'shop_info_edit':
			$domain = $_SERVER['HTTP_HOST'];
			$domain = str_replace("www.", "", $domain);
			$query = "SELECT * from domains WHERE name='$domain'";
			$domain_res = mysqli_fetch_assoc(DBi::$conn->query($query));
			
			$query ="UPDATE shop_info SET vorname='".$_POST['txtVorname']."',nachname='".$_POST['txtNachname']."',strasse_hnr='".$_POST['txtStrasse']."',plz='".$_POST['txtPLZ']."',stadt='".$_POST['txtOrt']."',land='".$_POST['txtLand']."',email_shop_main='".$_POST['txtEmail']."',firma='".$_POST['txtFirma']."',shop_name='".$_POST['txtShopName']."' WHERE domain_id='".$domain_res['domain_id']."'";
			DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			break;
		case 'get_shopste_category':
			#echo "IN";
			if(isset($_GET['domain_id'])) {
				$_SESSION['domain_id'] = $_GET['domain_id'];
			}
			echo shop_category(0, 0,'',0,0,'api');
			#exit(0);
			break;
		case 'chk_shopste_eiso_category':
			$query ="SELECT count(*) as anzahl FROM shop_category WHERE eiso_shop_catid='".$_GET['eiso_shop_catid']."'";
			$resEiSoKat = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strEiSoKat = mysqli_fetch_assoc($resEiSoKat);

			$query ="SELECT * FROM shop_category WHERE eiso_shop_catid='".$_GET['eiso_shop_catid']."'";
			$resEiSoKat2 = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strEiSoKat2 = mysqli_fetch_assoc($resEiSoKat2);
			
			echo $strEiSoKat['anzahl'].'~'.$strEiSoKat2['shop_cat_id'];
			break;
		case 'chk_shopste_jtl_category':
			$query ="SELECT count(*) as anzahl FROM shop_category WHERE jtl_shop_catid='".$_GET['jtl_shop_catid']."'";
			$resEiSoKat = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strEiSoKat = mysqli_fetch_assoc($resEiSoKat);

			$query ="SELECT * FROM shop_category WHERE jtl_shop_catid='".$_GET['jtl_shop_catid']."'";
			$resEiSoKat2 = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strEiSoKat2 = mysqli_fetch_assoc($resEiSoKat2);
			
			echo $strEiSoKat['anzahl'].'~'.$strEiSoKat2['shop_cat_id'];
			break;
		case 'ListByMonth':
			getListMonthOrdersByDate($strDomain);
			break;
			
		case 'set_shopste_category_jtl':
			// Page Einstellugen Speichern
			if(isset($_GET['domain_id'])) {
				$_SESSION['domain_id'] = $_GET['domain_id'];
			}
			  
			$query = "INSERT INTO `shop_category` (`name_de`,created_at,sortierung,domain_id,jtl_shop_catid) VALUES ('".$_GET['shop_cat_title']."','".date("Y-m-d H:i:s")."','0','".$_SESSION['domain_id']."','".$_GET['jtl_shop_catid']."');";
			$resInsert = DBi::$conn->query($query) or die('ER:000-4: '.mysqli_error(DBi::$conn));
			$iPageID = mysqli_insert_id(DBi::$conn);
			$icat = $iPageID;
			
			# Kategorie Seiten ID auslesen
			$query ="SELECT * FROM shop_category WHERE shop_cat_id='".$_GET['shop_cat_id']."'";
			#echo $query;
			$resPageIDRoot = DBi::$conn->query($query) or die('ER:000-3: '.mysqli_error(DBi::$conn));
			$strPageID = mysqli_fetch_assoc($resPageIDRoot);
			
			$_SESSION['system_shop_last_cat'] = $_GET['shop_cat_id'];
			
			$query = "INSERT INTO `shop_category_parent` (`shop_cat_id`, `shop_cat_parent`) VALUES (".$iPageID.", ".$_GET['shop_cat_id'].");";
			$resInsert = DBi::$conn->query($query) or die('ER:000-2: '.mysqli_error(DBi::$conn));
			
			// Page Einstellugen Speichern
			$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id,content_type) VALUES ('".$_GET['shop_cat_title']."', '".$_GET['shop_page_titel']."', '0', 'sichtbar', 'col2-left-layout','".$_SESSION['domain_id']."','kategorie_seite');";
			$resInsert = DBi::$conn->query($query) or die('ER:000-1: '.mysqli_error(DBi::$conn));
			$iPageID = mysqli_insert_id(DBi::$conn);
			
			$query = "UPDATE shop_category SET page_id='".$iPageID."' WHERE shop_cat_id='".$icat."'";
			DBi::$conn->query($query);
			#echo $_GET['page_menue_id'].'--';
			$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID.", ".$strPageID['page_id'].");";
			$resInsert = DBi::$conn->query($query) or die('ER:0000: '.mysqli_error(DBi::$conn));

			
			// Modul Einstellugen Speichern
			$query = "INSERT INTO `modul_shop_cat_list` (`title_de`, `menue_id`, `last_usr`,shop_cat_id) VALUES ('".$_GET['shop_page_titel']."', ".$iPageID.", 0,'".$icat."');";
			$resInsert = DBi::$conn->query($query) or die('ER:0001: '.mysqli_error(DBi::$conn));
			$iModulID = mysqli_insert_id(DBi::$conn);
					#echo "I";
	 
			// Modul auf einer Seite bekannt machen
			$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'shop_cat_list', 'col-main', '".$_GET['module_position']."');";
			$resInsert = DBi::$conn->query($query) or die('ER:0002: '.mysqli_error(DBi::$conn));
						#echo "N";
	 
			// Modul Einstellugen Speichern
			#$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('".$_GET['shop_page_titel']."', '', 0, 'submenue', 'Y');";
			#$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			#$iModulID = mysqli_insert_id(DBi::$conn);
		
			// Modul auf einer Seite bekannt machen
			#$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'menue', '".$strModuleColum."', '0');";
	
			#$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$query = "INSERT INTO `modul_menue_shopcategory` (`title_de`, `menue_id`, `last_usr`,content_de,created_at,shopste_cat_id) VALUES ('Shop Menü', ".$iPageID.", 0, ' ','".date("Y-m-d H:i:s")."','".$icat."');";
			$resInsert = DBi::$conn->query($query) or die('ER:0003: '.$query.' '.mysqli_error(DBi::$conn));
			$iModulID = mysqli_insert_id(DBi::$conn);
				
			$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'menue_shopcategory', 'col-left', '0');";
				
			$resInsert = DBi::$conn->query($query) or die('ER:0004'.mysqli_error(DBi::$conn));
				
			
			$path = getPathUrl($_SESSION['language'],$iPageID);			
			$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
			@mail("info@shopste.com","Kategorie angelegt: '".$_GET['shop_cat_title']."'","");
			echo $icat.'~'.$strLink;
			exit;
			break;			
		case 'set_shopste_category':
			// Page Einstellugen Speichern
			if(isset($_GET['domain_id'])) {
				$_SESSION['domain_id'] = $_GET['domain_id'];
			}
			
			$query = "INSERT INTO `shop_category` (`name_de`,created_at,sortierung,domain_id,eiso_shop_catid) VALUES ('".$_GET['shop_cat_title']."','".date("Y-m-d H:i:s")."','0','".$_SESSION['domain_id']."','".$_GET['eiso_shop_catid']."');";
			$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$iPageID = mysqli_insert_id(DBi::$conn);
			$icat = $iPageID;
			
			# Kategorie Seiten ID auslesen
			$query ="SELECT * FROM shop_category WHERE shop_cat_id='".$_GET['shop_cat_id']."'";
			$resPageIDRoot = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strPageID = mysqli_fetch_assoc($resPageIDRoot);
			
			$_SESSION['system_shop_last_cat'] = $_GET['shop_cat_id'];
			
			$query = "INSERT INTO `shop_category_parent` (`shop_cat_id`, `shop_cat_parent`) VALUES (".$iPageID.", ".$_GET['shop_cat_id'].");";
			$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			
			// Page Einstellugen Speichern
			$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id,content_type) VALUES ('".$_GET['shop_cat_title']."', '".$_GET['shop_page_titel']."', '0', 'sichtbar', 'col2-left-layout','".$_SESSION['domain_id']."','kategorie_seite');";
			$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$iPageID = mysqli_insert_id(DBi::$conn);
			
			$query = "UPDATE shop_category SET page_id='".$iPageID."' WHERE shop_cat_id='".$icat."'";
			DBi::$conn->query($query);
			#echo $_GET['page_menue_id'].'--';
			$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID.", ".$strPageID['page_id'].");";
			$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));

			
			// Modul Einstellugen Speichern
			$query = "INSERT INTO `modul_shop_cat_list` (`title_de`, `menue_id`, `last_usr`,shop_cat_id) VALUES ('".$_GET['shop_page_titel']."', ".$iPageID.", 0,'".$icat."');";
			$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$iModulID = mysqli_insert_id(DBi::$conn);
					#echo "I";
	 
			// Modul auf einer Seite bekannt machen
			$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'shop_cat_list', 'col-main', '".$_GET['module_position']."');";
			$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
						#echo "N";
	 
			// Modul Einstellugen Speichern
			#$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('".$_GET['shop_page_titel']."', '', 0, 'submenue', 'Y');";
			#$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			#$iModulID = mysqli_insert_id(DBi::$conn);
		
			// Modul auf einer Seite bekannt machen
			#$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'menue', '".$strModuleColum."', '0');";
	
			#$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$query = "INSERT INTO `modul_menue_shopcategory` (`title_de`, `menue_id`, `last_usr`,content_de,created_at,shopste_cat_id) VALUES ('Shop Menü', ".$iPageID.", 0, ' ','".date("Y-m-d H:i:s")."','".$icat."');";
			$resInsert = DBi::$conn->query($query) or die('ER:0003: '.$query.' '.mysqli_error(DBi::$conn));
			$iModulID = mysqli_insert_id(DBi::$conn);
				
			$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'menue_shopcategory', 'col-left', '0');";
				
			$resInsert = DBi::$conn->query($query) or die('ER:0004'.mysqli_error(DBi::$conn));
				
			
			$path = getPathUrl($_SESSION['language'],$iPageID);			
			$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
			@mail("info@shopste.com","Kategorie angelegt: '".$_GET['shop_cat_title']."'","");
			echo $icat.'~'.$strLink;
			exit;
			break;
		case 'get_category_item':
			if(isset($_GET['domain_id'])) {
				$_SESSION['domain_id'] = $_GET['domain_id'];
			}
			
			if($_GET['shop_cat_id'] != 0) {
				$ids = getSubKategorie($_GET['shop_cat_id'],"",0);
			}
			
			#print_r($_GET);
			# SUCHEN
			if(empty($_GET['suche']) == false) {
				$strWords = explode(" ",$_GET['suche']);
				for($i=0; $i < count($strWords); $i++) {
					$LikeSuche .= "name_de LIKE '%".$strWords[$i]."%' OR item_number LIKE '%".$strWords[$i]."%'";
					if($i < count($strWords) -1){
						$LikeSuche .= ' OR ';
					} else {
						$LikeSuche .= ' AND ';
					}
				}
			}
			
			#jb fix 27.06.2015 - enabled nicht in bedingung
			#$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." (shop_cat_id='".$_GET['shop_cat_id']."' ".$ids.") AND system_closed_shop='N' AND item_enabled='Y' ORDER BY updated_at DESC";
			if($_GET['shop_cat_id'] ==0) {
				$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." system_closed_shop='N' AND domain_id='".$_GET['domain_id']."' ORDER BY updated_at DESC";
			} else {
				$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." (shop_cat_id='".$_GET['shop_cat_id']."' ".$ids.") AND system_closed_shop='N' ORDER BY updated_at DESC";
			}
			#echo $query;
			$resShopItem = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			#echo $query;
			while($data = mysqli_fetch_assoc($resShopItem)) {
				
				$query ="SELECT * FROM shop_item_picture WHERE shop_item_id='".$data['shop_item_id']."'";
				$resBild = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$strBild = mysqli_fetch_assoc($resBild);
				
				echo $strBild['picture_url'].'~'.$data['name_de'].'~'.$data['preis'].'~'.$data['menge'].'~'.$data['item_number'].'~'.$data['menue_id'].'~'.$data['shop_item_id'].'~'.$data['item_enabled'].'<br>';
			}
			
			break;
		case 'setShopItem_eigenschaft_artibuteset':
			$query = "INSERT INTO `shop_attribute_set` (`set_name_de`, `domain_id`) VALUES ('".$_GET['eigenschaftname']."', '".$_GET['domain_id']."');";
			$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$attribute_set_id = mysqli_insert_id(DBi::$conn);				
			echo $attribute_set_id;
			break;
		case 'setShopItem_eigenschaft_name':
			$query = "INSERT INTO `shop_attribute` (`attribute_set_id`, `domain_id`, `name_de`) VALUES ('".$_GET['attribute_set_id']."', '".$_GET['domain_id']."','".$_GET['eigenschaftname']."');";
			$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$shop_attribute_id = mysqli_insert_id(DBi::$conn);				
			echo $shop_attribute_id;
			break;	
		case 'setShopItem_eigenschaft_value':
			$query = "INSERT INTO `shop_attribute_value` (`shop_attribute_id`, `domain_id`, `value_de`) VALUES ('".$_GET['shop_attribute_id']."', '".$_GET['domain_id']."','".$_GET['value_de']."');";
			$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$shop_attribute_wert = mysqli_insert_id(DBi::$conn);				
			echo $shop_attribute_wert;
			break;	
		case 'shop_item_eigenschaft':
			$query = "INSERT INTO `shop_item_eigenschaft` (`shop_attribut_id`, `domain_id`, `eigenschaft_name_de`, `id_shop_item`) VALUES ('".$_GET['shop_attribute_id']."', '".$_GET['domain_id']."','".$_GET['eigenschaft_name_de']."','".$_GET['id_shop_item']."');";
			#echo $query;
			$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$shop_attribute_wert = mysqli_insert_id(DBi::$conn);			 	
			echo $shop_attribute_wert;
			break;		
		case 'setShopItem_eigenschaftwert':
			$query = "INSERT INTO `shop_item_eigenschaftwert` (`id_shop_item_eigenschaft`, `domain_id`, `name_de`, `id_item_shop`, `shop_attribut_value_id`) VALUES ('".$_GET['shop_attribute_id']."', '".$_GET['domain_id']."','".$_GET['eigenschaft_name_de']."','".$_GET['id_shop_item']."','".$_GET['shop_attribut_value_id']."');";
			echo	$query;		
			$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$shop_attribute_wert = mysqli_insert_id(DBi::$conn);
			echo $shop_attribute_wert;
			break;				
		case 'get_shop_item':
			$query = "SELECT * FROM shop_item WHERE item_number='".$_POST['item_number']."'";
			$resShopItem = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strShopItem = mysqli_fetch_assoc($resShopItem);
		
			echo $strShopItem['shop_item_id'].'~'.$strShopItem['name_de'].'~'.$strShopItem['preis'].'~'.$strShopItem['shop_cat_id'].'~'.$strShopItem['menue_id'].'~'.$strShopItem['menge'].'~'.$strShopItem['beschreibung'].'~'.$strShopItem['gewicht'].'~'.$strShopItem['parrent_shop_item_id'].'~'.$strShopItem['item_number'];
			break;
		case 'kontakt_send_mail':
			$_SESSION['order_vorname'] = $_POST['txtVorname'];
			$_SESSION['order_nachname'] = $_POST['txtNachname'];
			$_SESSION['order_telefon'] = $_POST['txtTelefon'];
			$_SESSION['order_email'] = $_POST['txtEmail'];
			$_SESSION['order_firma'] = $_POST['txtFirma'];
			
			#echo $_POST['kontakt_modul_id']."--";
			$query ="SELECT * FROM modul_kontakt_form JOIN menue on modul_kontakt_form.menue_id = menue.id WHERE modul_kontakt_form.id='".$_POST['kontakt_modul_id']."'";
			#echo $query;
			$resKontaktModul = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strMenueModul = mysqli_fetch_assoc($resKontaktModul); 
			
			$query = "SELECT * FROM shop_info WHERE domain_id='".$strMenueModul['domain_id']."'";
			#echo $query;
			$resShopInfo = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strShopInfo = mysqli_fetch_assoc($resShopInfo); 
			
			#print_r($strShopInfo);
			
			$query ="SELECT count(*) as anzahl FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='KONTAKTFORMULAR'";
			#echo $query;
			$resEmailCount = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strEmailCount = mysqli_fetch_assoc($resEmailCount);
			if($strEmailCount['anzahl'] > 0) {
				# Lade Benutzervorlage
				$query ="SELECT * FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='KONTAKTFORMULAR'";
				#echo $query;
				$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
			} else {
				# Lade Defaultvorlage
				$query ="SELECT * FROM email_vorlage WHERE domain_id='0' AND standard='Y' AND typ='KONTAKTFORMULAR'";
				#echo $query;
				$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
			}
			
			// Email verschicken 
			
		$strEmailVorlage['content'] = str_replace('###SHOP_NAME###',$ShopInfoData['shop_name'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###KONTAKT_VORNAME###',$_POST['txtVorname'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###KONTAKT_NACHNAME###',$_POST['txtNachname'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###KONTAKT_FIRMA###',$_POST['txtFirma'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###KONTAKT_STRASSE###',$_POST['txtStrasse'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###KONTAKT_PLZ###',$_POST['txtPLZ'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###KONTAKT_ORT###',$_POST['txtOrt'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###KONTAKT_TELEFON###',$_POST['txtTelefon'],$strEmailVorlage['content']);
		$strEmailVorlage['content'] = str_replace('###KONTAKT_NACHRICHT###',$_POST['txtNachricht'],$strEmailVorlage['content']);
		
		$query = "SELECT * FROM domains WHERE domain_id='".$strMenueModul['domain_id']."'";
		$resDomain = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		$strDomain = mysqli_fetch_assoc($resDomain);
		
		$strEmailVorlage['content'] = str_replace('###SHOP_URL###','http://'.$strDomain['name'].'/',$strEmailVorlage['content']);
	
		/*$html = 'Shopste Systemnachricht, <br/>
			vielen Dank f&uuml;r Ihre Anfrage!<br/> Sie erhalten in K&uuml;rze eine Antwort auf Ihre Anfrage.<br/>
			<br/>
			<strong>Firma:</strong>'.$_POST['txtFirma'].'<br/>
			<strong>Name:</strong> '.$_POST['txtVorname'].' '.$_POST['txtNachname'].'<br/>
			<strong>Telefon:</strong> '.$_POST['txtTelefon'].'<br/>
			<strong>Nachricht:</strong> '.$_POST['txtNachricht'].'<br/>
			<br/>

			<strong>Sollten Sie noch Fragen bezüglich der Einrichtung haben so rufen Sie uns kostenlos an:</strong><br/><br/>
			Bludau Media<br/>
			0441 - 2 33 33 05
			<br/><br/>
			Viel Spa&szlig; w&uuml;nscht Ihr <a href="http://shopste.com" title="Shopste Team">Shopste Team</a>';
			*/
		$html = $strEmailVorlage['content'];
			//Create a new PHPMailer instance
			$mail = new PHPMailer();
			// Set PHPMailer to use the sendmail transport
			$mail->isSendmail();
			//Set who the message is to be sent from
			$mail->setFrom($strShopInfo['email_shop_main'], $strShopInfo['shop_name']);
			//Set an alternative reply-to address
			$mail->addReplyTo($strShopInfo['email_shop_main'], $strShopInfo['shop_name']);
			//Set who the message is to be sent to
			$mail->addAddress($_POST['txtEmail'], $_POST['txtVorname'].' '.$_POST['txtNachname']);
			$mail->AddBCC(CORE_MAIL_SEND_API_BCC,CORE_MAIL_SEND_API_BCC_NAME);
			
			//Set the subject line
			$strEmailVorlage['betreff'] = str_replace('###SHOPNAME###',$strShopInfo['shop_name'],$strEmailVorlage['betreff']);
			$strEmailVorlage['betreff'] = str_replace('###KONTAKT_VORNAME###',$_POST['txtVorname'],$strEmailVorlage['betreff']);
			$strEmailVorlage['betreff'] = str_replace('###KONTAKT_NACHNAME###',$_POST['txtNachname'],$strEmailVorlage['betreff']);
			$strEmailVorlage['betreff'] = str_replace('###KONTAKT_FIRMA###',$_POST['txtFirma'],$strEmailVorlage['betreff']);
	
			$mail->Subject = $strEmailVorlage['betreff'];
			//Read an HTML message body from an external file, convert referenced images to embedded,
			//convert HTML into a basic plain-text alternative body
			$mail->msgHTML(utf8_decode($html), dirname(__FILE__));
			//Replace the plain text body with one created manually
			$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';
			//Attach an image file
			//$mail->addAttachment('images/phpmailer_mini.png');

			//send the message, check for errors
			if (!$mail->send()) {
				echo "Mailer Error: " . $mail->ErrorInfo;
			} else {
				echo "Nachricht gesendet!";
			}	
			break;
		case 'api_login_user':
			#$_GET = mysql_real_escape_array($_GET);
			#echo "IN"; 
			if(!empty($_POST['txtUsername'])) {
			 
					#echo isLoggedIn($_POST['txtUsername'],$_POST['txtPasswort'],true).'A';
					if (isLoggedIn($_POST['txtUsername'],$_POST['txtPasswort'],true)) {

						$query ="SELECT count(*) as anzahl FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='API_PORTAL_USER_LOGIN'";
						#echo $query;
						$resEmailCount = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
						$strEmailCount = mysqli_fetch_assoc($resEmailCount);
						if($strEmailCount['anzahl'] > 0) {
							# Lade Benutzervorlage
							$query ="SELECT * FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='API_PORTAL_USER_LOGIN'";
							#echo $query;
							$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
							$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
						} else {
							# Lade Defaultvorlage
							$query ="SELECT * FROM email_vorlage WHERE domain_id='0' AND standard='Y' AND typ='API_PORTAL_USER_LOGIN'";
							#echo $query;
							$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
							$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
						}				
						
						// Email verschicken 
						$strEmailVorlage['content'] = str_replace('###ADMIN_USERNAME###',$_POST['txtUsername'],$strEmailVorlage['content']);
						$strEmailVorlage['content'] = str_replace('###ADMIN_DOMAIN_ID###',$strDomain['domain_id'],$strEmailVorlage['content']);
						$strEmailVorlage['content'] = str_replace('###ADMIN_DOMAIN_NAME###',$strDomain['name'],$strEmailVorlage['content']);
						
						$strEmailVorlage['betreff'] = str_replace('###ADMIN_USERNAME###',$_POST['txtUsername'],$strEmailVorlage['betreff']);
						$strEmailVorlage['betreff'] = str_replace('###ADMIN_DOMAIN_NAME###',$strDomain['name'],$strEmailVorlage['betreff']);
						
						//Create a new PHPMailer instance
						$mail = new PHPMailer();
						// Set PHPMailer to use the sendmail transport
						$mail->isSendmail();
						//Set who the message is to be sent from
						$mail->setFrom(CORE_MAIL_FROM_API, CORE_MAIL_FROM_API_NAME);
						//Set an alternative reply-to address
						$mail->addReplyTo(CORE_MAIL_FROM_API, CORE_MAIL_FROM_API_NAME);
						//Set who the message is to be sent to
						$mail->addAddress(CORE_MAIL_SEND_API_BCC,CORE_MAIL_SEND_API_BCC_NAME);
						#$mail->AddBCC();
						//Set the subject line
						$mail->Subject = utf8_decode($strEmailVorlage['betreff']);
						//Read an HTML message body from an external file, convert referenced images to embedded,
						//convert HTML into a basic plain-text alternative body
						$mail->msgHTML(utf8_decode($strEmailVorlage['content']), dirname(__FILE__));
						//Replace the plain text body with one created manually
						$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';
						//Attach an image file
						//$mail->addAttachment('images/phpmailer_mini.png');

						//send the message, check for errors
						if (!$mail->send()) {
							#echo "Mailer Error: " . $mail->ErrorInfo;
						} else {
							#echo "Message sent!";
						}		
					}						
				}
	 
			break;
		case 'register_change_passwort':
			echo '<html>
				<head>
					<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
					<meta content="INDEX,FOLLOW" name="robots">
					<link media="all" href="/css/template_master.css" type="text/css" rel="stylesheet">
					<title>Shopste.com Passwortänderung</title>
				</head>
				<body>
					<div class="page">
					<div class="block block-cart" id="box_texthtml_21"><div class="block-title"> <h1>Shopste Passwort Update / Änderung</h1></div>
					<div class="content" id="modul_texthtml_21">';
					if(!empty($_POST['txtRegPasswort'])) {
						$query = "SELECT *,domains.created_at as created_domain,domains.updated_at as updated_domain FROM benutzer JOIN domains ON benutzer.domain_id = domains.domain_id WHERE email_crc='".$_POST['email_crc']."'";
						#echo $query;
						$resUser = DBi::$conn->query($query);
						$strData = mysqli_fetch_assoc($resUser);
						
						$query = "UPDATE benutzer SET password='".encrypt_decrypt('encrypt',$_POST['txtRegPasswort'])."' WHERE email_crc='".$_POST['email_crc']."'";
						#echo $query;
						DBi::$conn->query($query);
						echo "Datenaktuallisierung für ".$_POST['txtRegUsername']." erfolgreich vorgenommen.<br/><br/>";
						echo 'Melden Sie sich an unter <a href="http://'.$strData['name'].'/admin">'.$strData['name'],'/admin/</a>';
						mail("jbludau@bludau-media.de","Datenänderung '".$strData['name']."'",$strData['username']);
						
					} else {
						$query = "SELECT *,domains.created_at as created_domain,domains.updated_at as updated_domain FROM benutzer JOIN domains ON benutzer.domain_id = domains.domain_id WHERE email_crc='".$_GET['email_crc']."'";
						#echo $query;
						$resUser = DBi::$conn->query($query);
						$strData = mysqli_fetch_assoc($resUser);
						
						echo '<form action="/api.php" name="frmShopstePWD_neu" method="POST">
						Aufgrund einer moderneren Programmierung PHP 7.2 müssen alle Ihre Passwörter erneut gespeichert werden.<br/>
						<p>';
						echo '<h3>Shop-Informationen</h3>';
						echo '<strong>Onlineshop:</strong> '.$strData['name'].'<br/>';
						echo '<strong>Onlineshop erstellt am:</strong> '.$strData['created_domain'].'<br/>';
						
						echo '<br/><strong>Benutzernamen</strong><br/>
						<input type="text" name="txtRegUsername" value="'.$_GET['user'].'"><br/><br/>
						<strong>Passwort-Update</strong><br/>
						<input type="password" name="txtRegPasswort">
						<input type="hidden" name="modus" value="register_change_passwort"><br/><br/>
						<input type="hidden" name="email_crc" value="'.$strData['email_crc'].'">
						<input class="button frmRegisterButton" type="submit" name="btnSenden" value="Shopste Passwort ändern">
						</form>';
					}
					echo '</div></div>
					</div>
				</body>
			</html>';
			break;
case 'register_email_validate_blog':
		#echo "IN";
			
			
		
			$query = "SELECT * FROM benutzer WHERE email_crc='".$_GET['email_crc']."'";
			$resEmailCRC = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strBenutzer = mysqli_fetch_assoc($resEmailCRC);
			if(count($strBenutzer) != '') {
				
				$query = "SELECT count(*) as anzahl FROM domains WHERE email_freischaltung='N' AND domain_id=".$strBenutzer['domain_id'];
				#echo $query;
				$resDomainOK = DBi::$conn->query($query)or die(mysqli_error(DBi::$conn));
				$strDomainOK = mysqli_fetch_assoc($resDomainOK);
				#print_r($strDomainOK);
				if($strDomainOK['anzahl'] == 1) {
					$query ="UPDATE domains SET email_freischaltung='Y',email_freischaltung_datum='".date("Y-m-d H:i:s")."' WHERE domain_id=".$strBenutzer['domain_id'];
					DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));

					$query ="UPDATE benutzer SET email_validate='Y',email_freischaltung_datum='".date("Y-m-d H:i:s")."' WHERE domain_id=".$strBenutzer['domain_id'];
					DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					$query="SELECT * FROM domains JOIN shop_info ON domains.domain_id = shop_info.domain_id WHERE domains.domain_id='".$strBenutzer['domain_id']."'";
					$strDomain = mysqli_fetch_assoc(DBi::$conn->query($query));
					
					$domain_id = $strBenutzer['domain_id']; 
	// Startseite anlegen
					$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id) VALUES ('Startseite', '".$_POST['txtRegShopname']."', '0', 'sichtbar', 'col2-right-layout','".$domain_id."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iPageID = mysqli_insert_id(DBi::$conn);				
					$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES ('".$iPageID."','0');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
	 
					
					$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('', ".$iPageID.", 0, 'menue', 'Y');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'menue', 'col-right', '0');";	
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));

					$query = "INSERT INTO `modul_texthtml` (`title_de`, `menue_id`, `last_usr`,content_de,created_at) VALUES ('Startseite', ".$iPageID.", 0, 'Hier entsteht ein neuer Online Blog.','".date("Y-m-d H:i:s")."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'texthtml', 'col-main', '0');";		
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));

					// Blog Startseite anlegen
					$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id) VALUES ('Blog', '', '0', 'sichtbar', 'col2-right-layout','".$domain_id."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iPageIDShop = mysqli_insert_id(DBi::$conn);				
					$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES ('".$iPageIDShop."','0');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
										 
					$query = "INSERT INTO `modul_news_category` (`name_de`, `domain_id`, `page_id`) VALUES ('Alle Kategorien', ".$strDomain['domain_id'].", '".$iPageIDShop."');";
					
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					$query = "INSERT INTO `modul_menue_shopcategory` (`title_de`, `menue_id`, `last_usr`,content_de,created_at) VALUES ('Blog Menü', ".$iPageIDShop.", 0, '','".date("Y-m-d H:i:s")."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);


					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageIDShop.", ".$iModulID.", 'menue_shopcategory', 'col-right', '0');";					
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					$query = "INSERT INTO `modul_news_categoryview` (`title_de`, `menue_id`, `last_usr`,content_de,created_at) VALUES ('Blog - Alle Kategorien', ".$iPageIDShop.", 0, '','".date("Y-m-d H:i:s")."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					 
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageIDShop.", ".$iModulID.", 'news_categoryview', 'col-main', '0');";
					
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					//
					// >> Über uns Seite anlegen 
					//
					$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id) VALUES ('Über uns', '', '1', 'sichtbar', 'col2-right-layout','".$domain_id."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iPageUeberUns = mysqli_insert_id(DBi::$conn);			
					$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES ('".$iPageUeberUns."','0');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					# Texthtml
					$query = "INSERT INTO `modul_texthtml` (`title_de`, `menue_id`, `last_usr`,content_de,created_at) VALUES ('Über uns', ".$iPageUeberUns.", 0, 'Hier entsteht die &Uuml;ber uns Seite','".date("Y-m-d H:i:s")."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageUeberUns.", ".$iModulID.", 'texthtml', 'col-main', '0');";		
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					# Menue Modul 
					$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('', ".$iPageUeberUns.", 0, 'menue', 'Y');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageUeberUns.", ".$iModulID.", 'menue', 'col-right', '0');";	
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					//
					// >> AGB 
					//
					$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id) VALUES ('AGB', '', '1', 'sichtbar', 'col2-right-layout','".$domain_id."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iAGBPageID = mysqli_insert_id(DBi::$conn);			
					$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES ('".$iAGBPageID."','".$iPageUeberUns."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					# Texthtml
					$query = "INSERT INTO `modul_texthtml` (`title_de`, `menue_id`, `last_usr`,content_de,created_at) VALUES ('AGB', ".$iAGBPageID.", 0, 'Hier entsteht die AGB Seite','".date("Y-m-d H:i:s")."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iAGBPageID.", ".$iModulID.", 'texthtml', 'col-main', '0');";		
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					# Menue Modul 
					$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('', ".$iAGBPageID.", 0, 'menue', 'Y');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iAGBPageID.", ".$iModulID.", 'menue', 'col-right', '0');";	
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					//
					// >> Impressum 
					//
					$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id) VALUES ('Impressum', '', '0', 'sichtbar', 'col2-right-layout','".$domain_id."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iImpressumPageID = mysqli_insert_id(DBi::$conn);			
					$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES ('".$iImpressumPageID."','".$iPageUeberUns."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					# Texthtml
					$query = "INSERT INTO `modul_texthtml` (`title_de`, `menue_id`, `last_usr`,content_de,created_at) VALUES ('Impressum', ".$iImpressumPageID.", 0, 'Hier entsteht die Impressum Seite','".date("Y-m-d H:i:s")."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iImpressumPageID.", ".$iModulID.", 'texthtml', 'col-main', '0');";		
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					# Menue Modul 
					$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('', ".$iImpressumPageID.", 0, 'menue', 'Y');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iImpressumPageID.", ".$iModulID.", 'menue', 'col-right', '0');";	
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					 
					
					if($strDomain['bIsSSL'] == 'Y') {
						$strHTTPType = 'https://';
					} else {
						$strHTTPType = 'http://';					
					}
					
 
					# Email vorlage laden
					$query ="SELECT count(*) as anzahl FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_FREISCHALTUNG'";
					$resEmailCount = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$strEmailCount = mysqli_fetch_assoc($resEmailCount);
					if($strEmailCount['anzahl'] > 0) {
						# Lade Benutzervorlage
						$query ="SELECT * FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_FREISCHALTUNG'";
						#echo $query;
						$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
						$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
					} else {
						# Lade Defaultvorlage
						$query ="SELECT * FROM email_vorlage WHERE domain_id='0' AND standard='Y' AND typ='CORE_FREISCHALTUNG'";
						#echo $query;
						$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
						$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
					}
					
					// Email verschicken 

					$strEmailVorlage['content'] = str_replace('###CORE_PLATTFORM_NAME###',$strDomain['shop_name'],$strEmailVorlage['content']);
					$strEmailVorlage['content'] = str_replace('###ADMIN_VORNAME###',$strBenutzer['vorname'],$strEmailVorlage['content']);
					$strEmailVorlage['content'] = str_replace('###ADMIN_NACHNAME###',$strBenutzer['nachname'],$strEmailVorlage['content']);

					$strEmailVorlage['content'] = str_replace('###ADMIN_USERNAME###',$strBenutzer['username'],$strEmailVorlage['content']);
					$strEmailVorlage['content'] = str_replace('###ADMIN_DOMAINNAME###',$strHTTPType.$strDomain['name'],$strEmailVorlage['content']);
					$strEmailVorlage['content'] = str_replace('###ADMIN_EMAIL###',$strBenutzer['email'],$strEmailVorlage['content']);
					
					// Email verschicken 
					
					//Create a new PHPMailer instance
					$mail = new PHPMailer();
					// Set PHPMailer to use the sendmail transport
					$mail->isSendmail();
					//Set who the message is to be sent from
					$mail->setFrom(CORE_MAIL_FROM_API, CORE_MAIL_FROM_API_NAME);
					//Set an alternative reply-to address
					$mail->addReplyTo(CORE_MAIL_FROM_API, CORE_MAIL_FROM_API_NAME);
					//Set who the message is to be sent to
					$mail->addAddress($strBenutzer['email'],utf8_decode($strBenutzer['vorname'].' '.$strBenutzer['nachname']));
					$mail->AddBCC(CORE_MAIL_SEND_API_BCC,CORE_MAIL_SEND_API_BCC_NAME);
					#$mail->AddBCC();
					//Set the subject line
					
					$strEmailVorlage['betreff'] = str_replace('###SHOP_NAME###',$strDomain['shop_name'],$strEmailVorlage['betreff']);
					
					$mail->Subject = utf8_decode($strEmailVorlage['betreff']);
					//Read an HTML message body from an external file, convert referenced images to embedded,
					//convert HTML into a basic plain-text alternative body
					$mail->msgHTML(utf8_decode($strEmailVorlage['content']), dirname(__FILE__));
					//Replace the plain text body with one created manually
					$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';
					//Attach an image file
					//$mail->addAttachment('images/phpmailer_mini.png');

					//send the message, check for errors
					if (!$mail->send()) {
						#echo "Mailer Error: " . $mail->ErrorInfo;
					} else {
						#echo "Message sent!";
					}	
				} else {
				# Bereits Freigeschaltet
			#echo "IN";
			$query = "SELECT * FROM benutzer WHERE email_crc='".$_GET['email_crc']."'";
			
			$resEmailCRC = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strBenutzer = mysqli_fetch_assoc($resEmailCRC);
			
			$query="SELECT * FROM domains JOIN shop_info ON domains.domain_id = shop_info.domain_id WHERE domains.domain_id='".$strBenutzer['domain_id']."'";
			$strDomain = mysqli_fetch_assoc(DBi::$conn->query($query));
			
			if($strDomain['bIsSSL'] == 'Y') {
						$strHTTPType = 'https://';
			} else {
						$strHTTPType = 'http://';					
			}
					
					echo '<html>
					<head>
						<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
						<meta content="INDEX,FOLLOW" name="robots">
						<link media="all" href="/css/template_master.css" type="text/css" rel="stylesheet">
						<title>Shop Freischaltung</title>
					</head>
					<body>
						<div class="page">
						<div class="block block-cart" id="box_texthtml_21"><div class="block-title"> <h1>Shop Freischaltung</h1></div>
						<div class="content" id="modul_texthtml_21"><p>
						<h2>Ihr Onlineshop ist bereits freigeschaltet.</h2>
						<a href="'.$strHTTPType.str_replace("http//","",$strDomain['name']).'/admin/">Anmeldung im Adminbereich von '.$strDomain['name'].'</a><br/><br/>
						<a href="'.$strHTTPType.str_replace("http//","",$strDomain['name']).'/">Online Shop ansehen</a>
						<a href="">
						</div></div>
						</div>
					</body>
				</html>';
				}
			}
			
			break;					
		case 'register_email_validate':
		#echo "IN";
			
			
		
			$query = "SELECT * FROM benutzer WHERE email_crc='".$_GET['email_crc']."'";
			$resEmailCRC = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strBenutzer = mysqli_fetch_assoc($resEmailCRC);
			if(count($strBenutzer) != '') {
				
				$query = "SELECT count(*) as anzahl FROM domains WHERE email_freischaltung='N' AND domain_id=".$strBenutzer['domain_id'];
				#echo $query;
				$resDomainOK = DBi::$conn->query($query)or die(mysqli_error(DBi::$conn));
				$strDomainOK = mysqli_fetch_assoc($resDomainOK);
				#print_r($strDomainOK);
				if($strDomainOK['anzahl'] == 1) {
					$query ="UPDATE domains SET email_freischaltung='Y',email_freischaltung_datum='".date("Y-m-d H:i:s")."' WHERE domain_id=".$strBenutzer['domain_id'];
					DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));

					$query ="UPDATE benutzer SET email_validate='Y',email_freischaltung_datum='".date("Y-m-d H:i:s")."' WHERE domain_id=".$strBenutzer['domain_id'];
					DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					$query="SELECT * FROM domains JOIN shop_info ON domains.domain_id = shop_info.domain_id WHERE domains.domain_id='".$strBenutzer['domain_id']."'";
					$strDomain = mysqli_fetch_assoc(DBi::$conn->query($query));
					
					$domain_id = $strBenutzer['domain_id']; 
	// Startseite anlegen
					$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id) VALUES ('Startseite', '".$_POST['txtRegShopname']."', '0', 'sichtbar', 'col2-left-layout','".$domain_id."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iPageID = mysqli_insert_id(DBi::$conn);				
					$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES ('".$iPageID."','0');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
	 
					
					$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('', ".$iPageID.", 0, 'menue', 'Y');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'menue', 'col-left', '0');";	
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));

					$query = "INSERT INTO `modul_texthtml` (`title_de`, `menue_id`, `last_usr`,content_de,created_at) VALUES ('Startseite', ".$iPageID.", 0, 'Hier entsteht ein neuer Online Shop.','".date("Y-m-d H:i:s")."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'texthtml', 'col-main', '0');";		
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));

					// Shop Seite anlegen
					$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id) VALUES ('Shop', '', '0', 'sichtbar', 'col2-left-layout','".$domain_id."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iPageIDShop = mysqli_insert_id(DBi::$conn);				
					$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES ('".$iPageIDShop."','0');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					$query = "INSERT INTO `modul_menue_shopcategory` (`title_de`, `menue_id`, `last_usr`,content_de,created_at) VALUES ('Shop Menü', ".$iPageIDShop.", 0, '','".date("Y-m-d H:i:s")."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageIDShop.", ".$iModulID.", 'menue_shopcategory', 'col-left', '0');";
					
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					//
					// >> Über uns Seite anlegen 
					//
					$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id) VALUES ('Über uns', '', '1', 'sichtbar', 'col2-left-layout','".$domain_id."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iPageUeberUns = mysqli_insert_id(DBi::$conn);			
					$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES ('".$iPageUeberUns."','0');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					# Texthtml
					$query = "INSERT INTO `modul_texthtml` (`title_de`, `menue_id`, `last_usr`,content_de,created_at) VALUES ('Über uns', ".$iPageUeberUns.", 0, 'Hier entsteht die &Uuml;ber uns Seite','".date("Y-m-d H:i:s")."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageUeberUns.", ".$iModulID.", 'texthtml', 'col-main', '0');";		
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					# Menue Modul 
					$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('', ".$iPageUeberUns.", 0, 'menue', 'Y');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageUeberUns.", ".$iModulID.", 'menue', 'col-left', '0');";	
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					//
					// >> AGB 
					//
					$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id) VALUES ('AGB', '', '1', 'sichtbar', 'col2-left-layout','".$domain_id."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iAGBPageID = mysqli_insert_id(DBi::$conn);			
					$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES ('".$iAGBPageID."','".$iPageUeberUns."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					# Texthtml
					$query = "INSERT INTO `modul_texthtml` (`title_de`, `menue_id`, `last_usr`,content_de,created_at) VALUES ('AGB', ".$iAGBPageID.", 0, 'Hier entsteht die AGB Seite','".date("Y-m-d H:i:s")."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iAGBPageID.", ".$iModulID.", 'texthtml', 'col-main', '0');";		
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					# Menue Modul 
					$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('', ".$iAGBPageID.", 0, 'menue', 'Y');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iAGBPageID.", ".$iModulID.", 'menue', 'col-left', '0');";	
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					//
					// >> Impressum 
					//
					$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id) VALUES ('Impressum', '', '0', 'sichtbar', 'col2-left-layout','".$domain_id."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iImpressumPageID = mysqli_insert_id(DBi::$conn);			
					$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES ('".$iImpressumPageID."','".$iPageUeberUns."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					# Texthtml
					$query = "INSERT INTO `modul_texthtml` (`title_de`, `menue_id`, `last_usr`,content_de,created_at) VALUES ('Impressum', ".$iImpressumPageID.", 0, 'Hier entsteht die Impressum Seite','".date("Y-m-d H:i:s")."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iImpressumPageID.", ".$iModulID.", 'texthtml', 'col-main', '0');";		
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					# Menue Modul 
					$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('', ".$iImpressumPageID.", 0, 'menue', 'Y');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iImpressumPageID.", ".$iModulID.", 'menue', 'col-left', '0');";	
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					//
					// >> Versandkosten 
					//
					$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id) VALUES ('Versandkosten', '', '1', 'sichtbar', 'col2-left-layout','".$domain_id."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iVersandkostenID = mysqli_insert_id(DBi::$conn);			
					$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES ('".$iVersandkostenID."','".$iPageUeberUns."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					# Texthtml
					$query = "INSERT INTO `modul_texthtml` (`title_de`, `menue_id`, `last_usr`,content_de,created_at) VALUES ('AGB', ".$iVersandkostenID.", 0, 'Es wurden keine Versandkosten bis jetzt eingegeben.','".date("Y-m-d H:i:s")."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iVersandkostenID.", ".$iModulID.", 'texthtml', 'col-main', '0');";		
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					# Menue Modul 
					$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('', ".$iVersandkostenID.", 0, 'menue', 'Y');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iVersandkostenID.", ".$iModulID.", 'menue', 'col-left', '0');";	
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					//
					// >> Widerrufsbelehrung
					//
					$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id) VALUES ('Widerrufsbelehrung', '', '0', 'sichtbar', 'col2-left-layout','".$domain_id."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iWiederrufPageID= mysqli_insert_id(DBi::$conn);			
					$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES ('".$iWiederrufPageID."','".$iPageUeberUns."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					# Texthtml
					$query = "INSERT INTO `modul_texthtml` (`title_de`, `menue_id`, `last_usr`,content_de,created_at) VALUES ('Widerrufsbelehrung', ".$iWiederrufPageID.", 0, 'Hier entsteht die Impressum Seite','".date("Y-m-d H:i:s")."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iWiederrufPageID.", ".$iModulID.", 'texthtml', 'col-main', '0');";		
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					# Menue Modul 
					$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('', ".$iWiederrufPageID.", 0, 'menue', 'Y');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iWiederrufPageID.", ".$iModulID.", 'menue', 'col-left', '0');";	
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					//
					// >> Warenkorb Seite anlegen 
					//
					$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id) VALUES ('Warenkorb', '', '1', 'sichtbar', 'col2-left-layout','".$domain_id."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iPageIDWarenkorb = mysqli_insert_id(DBi::$conn);				
					$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES ('".$iPageIDWarenkorb."','0');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					$query = "INSERT INTO `modul_shop_cart` (`title_de`, `menue_id`, `last_usr`) VALUES ('Warenkorb', ".$iPageIDWarenkorb.", 0);";
					#echo $query;
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
			
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageIDWarenkorb.", ".$iModulID.", 'shop_cart', 'col-main', '0');";
					#echo $query;
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('', ".$iPageIDWarenkorb.", 0, 'menue', 'Y');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					
					// Modul auf einer Seite bekannt machen
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageIDWarenkorb.", ".$iModulID.", 'menue', 'col-left', '0');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			
					// Zur Kasse Seite anlegen 
					$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id) VALUES ('Zur Kasse', '', '2', 'sichtbar', 'col2-left-layout','".$domain_id."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iPageIDZurKasse = mysqli_insert_id(DBi::$conn);				
					$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES ('".$iPageIDZurKasse."','".$iPageIDWarenkorb."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					$query = "INSERT INTO `modul_shop_cart` (`title_de`, `menue_id`, `last_usr`) VALUES ('Warenkorb', ".$iPageIDZurKasse.", 0);";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
			
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageIDZurKasse.", ".$iModulID.", 'shop_cart', 'col-main', '0');";
			
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					$query = "INSERT INTO `modul_shop_cart_order` (`title_de`, `menue_id`, `last_usr`) VALUES ('Zur Kasse', ".$iPageIDZurKasse.", 0);";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageIDZurKasse.", ".$iModulID.", 'shop_cart_order', 'col-main', '1');";
					
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			
					$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('', ".$iPageIDZurKasse.", 0, 'menue', 'Y');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					
	 
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageIDZurKasse.", ".$iModulID.", 'menue', 'col-left', '0');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					// Domaineinstellungen => Startseite
					$query = "UPDATE `domains` SET startseite='".$iPageID."', warenkorb_id='".$iPageIDWarenkorb."', zurkasse_id='".$iPageIDZurKasse."', shipping_id='".$iVersandkostenID."', agb_id='".$iAGBPageID."' , widerruf='".$iWiederrufPageID."',shop_mwst_setting='".$_POST['optGewerbeArt']."' WHERE  domain_id='".$domain_id."';";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));	

					// Kategorie "Alle Kategorien anlegen" 
					$query = "INSERT INTO `shop_category` (`name_de`,created_at,sortierung,domain_id,page_id) VALUES ('Alle Kategorien','".date("Y-m-d H:i:s")."','0','".$domain_id."','".$iPageIDShop."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iPageID = mysqli_insert_id(DBi::$conn);
					$icat = $iPageID;
					$_SESSION['system_shop_last_cat'] = $_POST['shop_cat_id'];
					
					$query = "INSERT INTO `shop_category_parent` (`shop_cat_id`, `shop_cat_parent`) VALUES (".$iPageID.", 0);";
					DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					// Shop Kategorie hinzufügen
					$query = "INSERT INTO `modul_shop_cat_list` (`title_de`, `menue_id`, `last_usr`,shop_cat_id) VALUES ('Alle Kategorien', ".$iPageIDShop.", 0,'".$icat."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
					
					// Modul auf einer Shop Seite bekannt machen
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageIDShop.", ".$iModulID.", 'shop_cat_list', 'col-main', '0');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					if($strDomain['bIsSSL'] == 'Y') {
						$strHTTPType = 'https://';
					} else {
						$strHTTPType = 'http://';					
					}
					
 
					# Email vorlage laden
					$query ="SELECT count(*) as anzahl FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_FREISCHALTUNG'";
					$resEmailCount = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$strEmailCount = mysqli_fetch_assoc($resEmailCount);
					if($strEmailCount['anzahl'] > 0) {
						# Lade Benutzervorlage
						$query ="SELECT * FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_FREISCHALTUNG'";
						#echo $query;
						$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
						$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
					} else {
						# Lade Defaultvorlage
						$query ="SELECT * FROM email_vorlage WHERE domain_id='0' AND standard='Y' AND typ='CORE_FREISCHALTUNG'";
						#echo $query;
						$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
						$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
					}
					
					// Email verschicken 

					$strEmailVorlage['content'] = str_replace('###CORE_PLATTFORM_NAME###',$strDomain['shop_name'],$strEmailVorlage['content']);
					$strEmailVorlage['content'] = str_replace('###ADMIN_VORNAME###',$strBenutzer['vorname'],$strEmailVorlage['content']);
					$strEmailVorlage['content'] = str_replace('###ADMIN_NACHNAME###',$strBenutzer['nachname'],$strEmailVorlage['content']);

					$strEmailVorlage['content'] = str_replace('###ADMIN_USERNAME###',$strBenutzer['username'],$strEmailVorlage['content']);
					$strEmailVorlage['content'] = str_replace('###ADMIN_DOMAINNAME###',$strHTTPType.$strDomain['name'],$strEmailVorlage['content']);
					$strEmailVorlage['content'] = str_replace('###ADMIN_EMAIL###',$strBenutzer['email'],$strEmailVorlage['content']);
					
					// Email verschicken 
				
					//Create a new PHPMailer instance
					$mail = new PHPMailer();
					// Set PHPMailer to use the sendmail transport
					$mail->isSendmail();
					//Set who the message is to be sent from
					$mail->setFrom(CORE_MAIL_FROM_API, CORE_MAIL_FROM_API_NAME);
					//Set an alternative reply-to address
					$mail->addReplyTo(CORE_MAIL_FROM_API, CORE_MAIL_FROM_API_NAME);
					//Set who the message is to be sent to
					$mail->addAddress($strBenutzer['email'],utf8_decode($strBenutzer['vorname'].' '.$strBenutzer['nachname']));
					$mail->AddBCC(CORE_MAIL_SEND_API_BCC,CORE_MAIL_SEND_API_BCC_NAME);
					#$mail->AddBCC();
					//Set the subject line
					
					$strEmailVorlage['betreff'] = str_replace('###SHOP_NAME###',$strDomain['shop_name'],$strEmailVorlage['betreff']);
					
					$mail->Subject = utf8_decode($strEmailVorlage['betreff']);
					//Read an HTML message body from an external file, convert referenced images to embedded,
					//convert HTML into a basic plain-text alternative body
					$mail->msgHTML(utf8_decode($strEmailVorlage['content']), dirname(__FILE__));
					//Replace the plain text body with one created manually
					$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';
					//Attach an image file
					//$mail->addAttachment('images/phpmailer_mini.png');

					//send the message, check for errors
					if (!$mail->send()) {
						#echo "Mailer Error: " . $mail->ErrorInfo;
					} else {
						#echo "Message sent!";
					}	
				} else {
				# Bereits Freigeschaltet
			#echo "IN";
			$query = "SELECT * FROM benutzer WHERE email_crc='".$_GET['email_crc']."'";
			
			$resEmailCRC = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strBenutzer = mysqli_fetch_assoc($resEmailCRC);
			
			$query="SELECT * FROM domains JOIN shop_info ON domains.domain_id = shop_info.domain_id WHERE domains.domain_id='".$strBenutzer['domain_id']."'";
			$strDomain = mysqli_fetch_assoc(DBi::$conn->query($query));
			
			if($strDomain['bIsSSL'] == 'Y') {
						$strHTTPType = 'https://';
			} else {
						$strHTTPType = 'http://';					
			}
					
					echo '<html>
					<head>
						<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
						<meta content="INDEX,FOLLOW" name="robots">
						<link media="all" href="/css/template_master.css" type="text/css" rel="stylesheet">
						<title>Shop Freischaltung</title>
					</head>
					<body>
						<div class="page">
						<div class="block block-cart" id="box_texthtml_21"><div class="block-title"> <h1>Shop Freischaltung</h1></div>
						<div class="content" id="modul_texthtml_21"><p>
						<h2>Ihr Onlineshop ist bereits freigeschaltet.</h2>
						<a href="'.$strHTTPType.str_replace("http//","",$strDomain['name']).'/admin/">Anmeldung im Adminbereich von '.$strDomain['name'].'</a><br/><br/>
						<a href="'.$strHTTPType.str_replace("http//","",$strDomain['name']).'/">Online Shop ansehen</a>
						<a href="">
						</div></div>
						</div>
					</body>
				</html>';
				}
			}
			
			break;
		case 'get_shopste_kategorie_by_eisocatid':
			$query = "SELECT * FROM shop_category WHERE eiso_shop_catid='".$_GET['eiso_cat_id']."'";
			$resEiSoKat = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strShopsteCat = mysqli_fetch_assoc($resEiSoKat);
			if($strShopsteCat['shop_cat_id'] == '') {
				echo "keine-kategorie-vorhanden";
			} else {
				echo $strShopsteCat['shop_cat_id'];
			}
			exit(0);
			break;

		case 'get_shopste_kategorie_by_jtlcatid':
			$query = "SELECT * FROM shop_category WHERE jtl_shop_catid='".$_GET['jtl_cat_id']."'";
			$resEiSoKat = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strShopsteCat = mysqli_fetch_assoc($resEiSoKat);
			if($strShopsteCat['jtl_shop_catid'] == '') {
				echo "keine-kategorie-vorhanden";
			} else {
				echo $strShopsteCat['shop_cat_id']; 
			}
			exit(0);  
			break;
			case 'set_shopste_item_activator':
		
			if(isset($_POST['shop_item_id'])) {
				$_GET['shop_item_id'] = $_POST['shop_item_id'];
				$_GET['enabled'] = $_POST['enabled'];
			}
			$_GET['shop_item_id'] = str_replace("-Deutsch","",$_GET['shop_item_id']);
			$_GET['shop_item_id'] = str_replace("-Englisch","",$_GET['shop_item_id']);
			
			if($_GET['enabled'] == 'N') {
				# Artikel auf neue Menge einstellen
				$query ="UPDATE shop_item SET item_enabled='Y' WHERE shop_item_id='".$_GET['shop_item_id']."'";
				$strStatus = "Artikel ist jetzt wieder aktiv<br/><br/>";
				$strStatusPlain =  "AKTIV";
			} else {
				$query ="UPDATE shop_item SET item_enabled='N' WHERE shop_item_id='".$_GET['shop_item_id']."'";
				$strStatus = "Artikel ist jetzt wieder inaktiv<br/><br/>";
				$strStatusPlain =  "INAKTIV";
			}
			#echo $query;
			DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			
			
			# Artikeldaten abrufen
			$query = "SELECT * FROM shop_item WHERE shop_item_id ='".$_GET['shop_item_id']."'";
			#echo $query;
			$resItem = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strItem = mysqli_fetch_assoc($resItem);
			if(isset($strItem['domain_id'])) {
				echo "Status geändert.";
				# Shop Info abrufen
				$query = "SELECT * FROM shop_info WHERE domain_id='".$strItem['domain_id']."'";
				$resShopInfo = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$strBenutzer = mysqli_fetch_assoc($resShopInfo);
				
				# Domain Info abrufen
				$query = "SELECT * FROM domains WHERE domain_id='".$strItem['domain_id']."'";
				$resShopInfo = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$strDomain = mysqli_fetch_assoc($resShopInfo);
				
				
				// Email verschicken 
				
				$strURL_shop = getPathUrl($_SESSION['language'],$strItem['menue_id']);
				
				$html = utf8_decode('<h1>Artikelstatus auf Shopste.com</h1>, 
				
				Hallo '.$strBenutzer['vorname'].' '.$strBenutzer['nachname'].',<br/>
				'.$strStatus.'
				<br/>'.
				$strItem['name_de'].' - '.$strItem['preis'].' EUR | Menge: '.$strItem['menge'].'<br/><br/>
				Link: <a href="http://'.$strDomain['name'].'/'.$strURL_shop.'"/>'.$strItem['name_de'].' &ouml;ffnen</a><br/><br/>
				Ihr Shopste Team');
				
				//Create a new PHPMailer instance
				$mail = new PHPMailer();
				// Set PHPMailer to use the sendmail transport
				$mail->isSendmail();
				//Set who the message is to be sent from
				$mail->setFrom(CORE_MAIL_FROM_API, CORE_MAIL_FROM_API_NAME);
				//Set an alternative reply-to address
				$mail->addReplyTo(CORE_MAIL_FROM_API, CORE_MAIL_FROM_API_NAME);
				//Set who the message is to be sent to
				$mail->addAddress($strBenutzer['email_shop_main'],utf8_decode($strBenutzer['vorname'].' '.$strBenutzer['nachname']));
				$mail->AddBCC(CORE_MAIL_SEND_API_BCC,CORE_MAIL_SEND_API_BCC_NAME);
				#$mail->AddBCC();
				//Set the subject line
				$mail->Subject = utf8_decode('Shopste Artikelstatus '.$strStatusPlain.' für '.$strItem['name_de']);
				//Read an HTML message body from an external file, convert referenced images to embedded,
				//convert HTML into a basic plain-text alternative body
				$mail->msgHTML(utf8_decode($html), dirname(__FILE__));
				//Replace the plain text body with one created manually
				$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';
				//Attach an image file
				//$mail->addAttachment('images/phpmailer_mini.png');

				//send the message, check for errors
				if (!$mail->send()) {
					#echo "Mailer Error: " . $mail->ErrorInfo;
				} else {
					#echo "Message sent!";
				}
			}
			exit(0);
			break;
		case 'set_shopste_item_ordered_byID':
		
			if(isset($_POST['shop_item_id'])) {
				$_GET['shop_item_id'] = $_POST['shop_item_id'];
				$_GET['SetMenge'] = $_POST['SetMenge'];
			}
			
			echo $_SESSION['domain_id'];
			
			# EISO SHOP IDS (eng,de)
			$_GET['shop_item_id'] = str_replace("-Deutsch","",$_GET['shop_item_id']);
			$_GET['shop_item_id'] = str_replace("-Englisch","",$_GET['shop_item_id']);
			
			if(isset($_GET['SetMenge'])) {
				# Artikel auf neue Menge einstellen
				$query ="UPDATE shop_item SET menge=".$_GET['SetMenge']." WHERE shop_item_id='".$_GET['shop_item_id']."'";
			} else {
				$query ="UPDATE shop_item SET menge=menge -".$_GET['menge']." WHERE shop_item_id='".$_GET['shop_item_id']."'";
			}
			#echo $query;
			DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			
			
			# Artikeldaten abrufen
			$query = "SELECT * FROM shop_item WHERE shop_item_id ='".$_GET['shop_item_id']."'";
			#echo $query;
			$resItem = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strItem = mysqli_fetch_assoc($resItem);
			if(isset($strItem['domain_id'])) {
				echo "OK";
				# Shop Info abrufen
				$query = "SELECT * FROM shop_info WHERE domain_id='".$strItem['domain_id']."'";
				$resShopInfo = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$strBenutzer = mysqli_fetch_assoc($resShopInfo);
				
				# Domain Info abrufen
				$query = "SELECT * FROM domains WHERE domain_id='".$strItem['domain_id']."'";
				$resShopInfo = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$strDomain = mysqli_fetch_assoc($resShopInfo);
				
				
				// Email verschicken 
				
				$strURL_shop = getPathUrl($_SESSION['language'],$strItem['menue_id']);
 
					
				###########################################################
				# >> Gibt es Benutzerspezifische Vorlagen?
				###########################################################
				
				$query ="SELECT count(*) as anzahl FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='API_LAGERBESTAND'";
				#echo $query;
				$resEmailCount = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$strEmailCount = mysqli_fetch_assoc($resEmailCount);
				if($strEmailCount['anzahl'] > 0) {
					# Lade Benutzervorlage
					$query ="SELECT * FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='API_LAGERBESTAND'";
					#echo $query;
					$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
				} else {
					# Lade Defaultvorlage
					$query ="SELECT * FROM email_vorlage WHERE domain_id='0' AND standard='Y' AND typ='API_LAGERBESTAND'";
					#echo $query;
					$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
				}
				
			 
				$strURL_shop = getPathUrl($_SESSION['language'],$strItem['menue_id']);
				
				
				
				$html = $strEmailVorlage['content'];
 #echo $html;
				$html = str_replace('###SHOP_VORNAME###',$strBenutzer['vorname'],$html);
				$html = str_replace('###SHOP_NACHNAME###',$strBenutzer['nachname'],$html);
				
				$html = str_replace('###SHOP_ARTIKEL_LINK###','http://'.$strDomain['name'].'/'.$strURL_shop.'/',$html);
				
				
				$html = str_replace('###SHOP_MITGLIEDSNAME###',	$strItem['shop_mitgliedsname'],$html);
				$html = str_replace('###SHOP_ARTIKEL_NAME###',	$strItem['name_de'],$html);
				$html = str_replace('###SHOP_ARTIKEL_MENGE###',$strItem['menge'],$html);
				$html = str_replace('###SHOP_ARTIKEL_GEWICHT###',$strItem['gewicht'],$html);
				$html = str_replace('###SHOP_ARTIKEL_PREIS###',number_format($strItem['preis'], 2, ",", "."),$html);
				$html = str_replace('###SHOP_ARTIKEL_BESCHREIBUNG###',$strItem['beschreibung'],$html);
				$html = str_replace('###SHOP_ARTIKEL_GEWICHT###',$strItem['gewicht'],$html);
				$html = str_replace('###SHOP_ARTIKEL_NUMMER###',$strItem['item_number'],$html);
				$html = str_replace('###SHOP_ARTIKEL_MWST###',$strItem['item_mwst'],$html);
				$html = str_replace('###SHOP_ADMIN_LOGIN###','http://'.$strDomain['name'].'/admin/',$html);
				$html = str_replace("###SHOP_ADD_TYPE###",$strSubjectAdd,$html);
				
				
				$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$strItem['shop_item_id']."'";
				$res = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$strBild = mysqli_fetch_assoc($res);
				
				$html = str_replace("###SHOP_ARTIKEL_BILD###","<img src=\"https://shopste.com".$strBild['picture_url']."\">",$html);
				
				# Nur wenn Shopste.com aktiv 
				if($strItem['shopste_marktplatz_menue_id'] != '0') {
					####SHOP_ARTIKEL_LINK_Marktplatz###
					
					$strURL_shop = getPathUrl($_SESSION['language'],$strItem['shopste_marktplatz_menue_id']);
					
					$html = str_replace('###SHOP_ARTIKEL_MAKRTPLATZ_LINK###','Marktplatz: <a href="https://shopste.com/'.$strURL_shop.'">###SHOP_ARTIKEL_NAME###</a>',$html);
					$html = str_replace('###SHOP_ARTIKEL_NAME###',	$strItem['name_de'],$html);
				} else {
					$html =  str_replace('###SHOP_ARTIKEL_MAKRTPLATZ_LINK###','',$html);
				}	
				$html = str_replace('###SHOP_ARTIKEL_LINK###','http://'.$strDomain['name'].'/'.$strURL_shop.'/',$html);
				 ###SHOP_NACHNAME###
				
				//Create a new PHPMailer instance
				$mail = new PHPMailer();
				// Set PHPMailer to use the sendmail transport
				$mail->isSendmail();
				//Set who the message is to be sent from
				$mail->setFrom(CORE_MAIL_FROM_API, CORE_MAIL_FROM_API_NAME);
				//Set an alternative reply-to address
				$mail->addReplyTo(CORE_MAIL_FROM_API, CORE_MAIL_FROM_API_NAME);
				//Set who the message is to be sent to
				$mail->addAddress($strBenutzer['email_shop_main'],utf8_decode($strBenutzer['vorname'].' '.$strBenutzer['nachname']));
				$mail->AddBCC(CORE_MAIL_SEND_API_BCC,CORE_MAIL_SEND_API_BCC_NAME);
				#$mail->AddBCC();
				//Set the subject line
				
				#'Shopste Lagerbestand Menge: Neuer Lagebestand ###SHOP_ARTIKEL_MENGE###x Meldung für ###SHOP_ARTIKEL_NAME###
				$betreff = $strEmailVorlage['betreff'];
				#Lagebestand ###SHOP_ARTIKEL_MENGE###x Meldung für ###SHOP_ARTIKEL_NAME###
				$betreff = str_replace('###SHOP_ARTIKEL_MENGE###',$strItem['menge'],$betreff);
				$betreff = str_replace('###SHOP_ARTIKEL_NAME###',$strItem['name_de'],$betreff);
				
				$mail->Subject = utf8_decode($betreff);
				//Read an HTML message body from an external file, convert referenced images to embedded,
				//convert HTML into a basic plain-text alternative body
				$mail->msgHTML($html, dirname(__FILE__));
				//Replace the plain text body with one created manually
				$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';
				//Attach an image file
				//$mail->addAttachment('images/phpmailer_mini.png');

				//send the message, check for errors
				if (!$mail->send()) {
					#echo "Mailer Error: " . $mail->ErrorInfo;
				} else {
					#echo "Message sent!";
				}
			}
			exit(0);
			break;			
		case 'set_shopste_item_ordered':
		
			$_GET['item_id'] = str_replace("-Deutsch","",$_GET['item_id']);
			$_GET['item_id'] = str_replace("-Englisch","",$_GET['item_id']);
			
			if(isset($_GET['SetMenge'])) {
				# Artikel auf neue Menge einstellen
				$query ="UPDATE shop_item SET menge=".$_GET['SetMenge']." WHERE item_number='".$_GET['item_id']."' AND domain_id='".$_GET['domain_id']."'";
			} else {
				$query ="UPDATE shop_item SET menge=menge -".$_GET['menge']." WHERE item_number='".$_GET['item_id']."' AND domain_id='".$_GET['domain_id']."'";
			}
			#echo $query;
			DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			
			
			# Artikeldaten abrufen
			$query = "SELECT * FROM shop_item WHERE item_number ='".$_GET['item_id']."' AND domain_id='".$_GET['domain_id']."'";
			#echo $query;
			$resItem = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strItem = mysqli_fetch_assoc($resItem);
			if(isset($strItem['domain_id'])) {
				echo "OK";
				# Shop Info abrufen
				$query = "SELECT * FROM shop_info WHERE domain_id='".$strItem['domain_id']."'";
				$resShopInfo = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$strBenutzer = mysqli_fetch_assoc($resShopInfo);
				
				# Domain Info abrufen
				$query = "SELECT * FROM domains WHERE domain_id='".$strItem['domain_id']."'";
				$resShopInfo = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$strDomain = mysqli_fetch_assoc($resShopInfo);
				
				
				// Email verschicken 
				
				###########################################################
				# >> Gibt es Benutzerspezifische Vorlagen?
				###########################################################
				
				$query ="SELECT count(*) as anzahl FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='API_LAGERBESTAND'";
				#echo $query;
				$resEmailCount = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$strEmailCount = mysqli_fetch_assoc($resEmailCount);
				if($strEmailCount['anzahl'] > 0) {
					# Lade Benutzervorlage
					$query ="SELECT * FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='API_LAGERBESTAND'";
					#echo $query;
					$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
				} else {
					# Lade Defaultvorlage
					$query ="SELECT * FROM email_vorlage WHERE domain_id='0' AND standard='Y' AND typ='API_LAGERBESTAND'";
					#echo $query;
					$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
				}
				
			 
				$strURL_shop = getPathUrl($_SESSION['language'],$strItem['menue_id']);
				
				
				
				$html = $strEmailVorlage['content'];
				
				$html = str_replace('###SHOP_VORNAME###',$strBenutzer['vorname'],$html);
				$html = str_replace('###SHOP_NACHNAME###',$strBenutzer['nachname'],$html);
				
				$html = str_replace('###SHOP_ARTIKEL_LINK###','http://'.$strDomain['name'].'/'.$strURL_shop.'/',$html);
				
				$html = str_replace('###SHOP_MITGLIEDSNAME###',	$strItem['shop_mitgliedsname'],$html);
				$html = str_replace('###SHOP_ARTIKEL_NAME###',	$strItem['name_de'],$html);
				$html = str_replace('###SHOP_ARTIKEL_MENGE###',$strItem['menge'],$html);
				$html = str_replace('###SHOP_ARTIKEL_GEWICHT###',$strItem['gewicht'],$html);
				$html = str_replace('###SHOP_ARTIKEL_PREIS###',number_format($strItem['preis'], 2, ",", "."),$html);
				$html = str_replace('###SHOP_ARTIKEL_BESCHREIBUNG###',$strItem['beschreibung'],$html);
				$html = str_replace('###SHOP_ARTIKEL_GEWICHT###',$strItem['gewicht'],$html);
				$html = str_replace('###SHOP_ARTIKEL_NUMMER###',$strItem['item_number'],$html);
				$html = str_replace('###SHOP_ARTIKEL_MWST###',$strItem['item_mwst'],$html);
				$html = str_replace('###SHOP_ADMIN_LOGIN###','http://'.$strDomain['name'].'/admin/',$html);
				$html = str_replace("###SHOP_ADD_TYPE###",$strSubjectAdd,$html);
				
				$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$strItem['shop_item_id']."'";
				$res = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$strBild = mysqli_fetch_assoc($res);
				
				$html = str_replace("###SHOP_ARTIKEL_BILD###","<img src=\"https://shopste.com".$strBild['picture_url']."\">",$html);

				# Nur wenn Shopste.com aktiv 
				if($strItem['shopste_marktplatz_menue_id'] != '0') {
					####SHOP_ARTIKEL_LINK_Marktplatz###
					
					$strURL_shop = getPathUrl($_SESSION['language'],$strItem['shopste_marktplatz_menue_id']);
					
					$html = str_replace('###SHOP_ARTIKEL_MAKRTPLATZ_LINK###','Marktplatz: <a href="https://shopste.com/'.$strURL_shop.'">###SHOP_ARTIKEL_NAME###</a>',$html);
					$html = str_replace('###SHOP_ARTIKEL_NAME###',	$strItem['name_de'],$html);
				} else {
					$html =  str_replace('###SHOP_ARTIKEL_MAKRTPLATZ_LINK###','',$html);
				}	
				
				 ###SHOP_NACHNAME###
				
				//Create a new PHPMailer instance
				$mail = new PHPMailer();
				// Set PHPMailer to use the sendmail transport
				$mail->isSendmail();
				//Set who the message is to be sent from
				$mail->setFrom(CORE_MAIL_FROM_API, CORE_MAIL_FROM_API_NAME);
				//Set an alternative reply-to address
				$mail->addReplyTo(CORE_MAIL_FROM_API, CORE_MAIL_FROM_API_NAME);
				//Set who the message is to be sent to
				$mail->addAddress($strBenutzer['email_shop_main'],utf8_decode($strBenutzer['vorname'].' '.$strBenutzer['nachname']));
				$mail->AddBCC(CORE_MAIL_SEND_API_BCC,CORE_MAIL_SEND_API_BCC_NAME);
				#$mail->AddBCC();
				//Set the subject line
				
				#'Shopste Lagerbestand Menge: Neuer Lagebestand ###SHOP_ARTIKEL_MENGE###x Meldung für ###SHOP_ARTIKEL_NAME###
				
				$betreff = $strEmailVorlage['betreff'];
				#Lagebestand ###SHOP_ARTIKEL_MENGE###x Meldung für ###SHOP_ARTIKEL_NAME###
				$betreff = str_replace('###SHOP_ARTIKEL_MENGE###',$strItem['menge'],$betreff);
				$betreff = str_replace('###SHOP_ARTIKEL_NAME###',$strItem['name_de'],$betreff);
				
				$mail->Subject = utf8_decode($betreff);
				//Read an HTML message body from an external file, convert referenced images to embedded,
				//convert HTML into a basic plain-text alternative body
				$mail->msgHTML($html, dirname(__FILE__));
				//Replace the plain text body with one created manually
				$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';
				//Attach an image file
				//$mail->addAttachment('images/phpmailer_mini.png');

				//send the message, check for errors
				if (!$mail->send()) {
					#echo "Mailer Error: " . $mail->ErrorInfo;
				} else {
					echo "Nachricht gesendet";
				}
			} else {
				
				# Shop Info abrufen
				/*$query = "SELECT * FROM shop_info WHERE domain_id='".$_GET['domain_id']."'";
				$resShopInfo = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$strBenutzer = mysqli_fetch_assoc($resShopInfo);
				
				// Email verschicken 
				$path = realpath($_SERVER["DOCUMENT_ROOT"]);
				require_once $path.'/framework/phpmailer/PHPMailerAutoload.php';
				echo "0 gefunden";
				$strURL_shop = getPathUrl($_SESSION['language'],$strItem['menue_id']);
				
				$html = 'Kein Artikel gefunden!';
				
				//Create a new PHPMailer instance
				$mail = new PHPMailer();
				// Set PHPMailer to use the sendmail transport
				$mail->isSendmail();
				//Set who the message is to be sent from
				$mail->setFrom('info@shopste.com', 'Shopste Service');
				//Set an alternative reply-to address
				$mail->addReplyTo('info@shopste.com', 'Shopste Service');
				//Set who the message is to be sent to
				$mail->addAddress($strBenutzer['email_shop_main'],utf8_decode($strBenutzer['vorname'].' '.$strBenutzer['nachname']));
				$mail->AddBCC("jbludau@bludau-media.de","Shopste Service");
				#$mail->AddBCC();
				//Set the subject line
				$mail->Subject = utf8_decode('Shopste Lagerbestand nicht vorhanden: '.$_GET['item_id']);
				//Read an HTML message body from an external file, convert referenced images to embedded,
				//convert HTML into a basic plain-text alternative body
				$mail->msgHTML(utf8_decode($html), dirname(__FILE__));
				//Replace the plain text body with one created manually
				$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';
				//Attach an image file
				//$mail->addAttachment('images/phpmailer_mini.png');

				//send the message, check for errors
				if (!$mail->send()) {
					#echo "Mailer Error: " . $mail->ErrorInfo;
				} else {
					#echo "Message sent!";
				}*/
			}
			exit(0);
			break;
		case 'set_shopste_item_delete':
			$query="";
			break;
		case 'portal_abrechnung_mahnung':
			
			if(isset($_GET['domain_id'])) {
				$whereand = 'AND domain_id='.$_GET['domain_id'];
			} else {
				$whereand = '';
			}
			$query ="SELECT * FROM domains WHERE email_freischaltung='Y' AND email_send_invoice='Y' ".$whereand;
			$resDomains = DBi::$conn->query($query) or die('ERR00001: '.mysqli_error(DBi::$conn));
			
			# RECHNUNG Fälligkeitsdatum setzen
			$query = "UPDATE portal_abrechnung SET bezahlt_mahnung_senden = DATE_ADD(`in_rechnung_gestellt_am` , INTERVAL 16 DAY)
 WHERE bezahlt_am is Null";
			$resUpdate = DBi::$conn->query($query) or die('ERR00002: '.mysqli_error(DBi::$conn));
			
			while($strDomains = mysqli_fetch_assoc($resDomains)) {
				
				if(isset($_GET['portal_abrechnung_id'])) {
					$whereand = " AND portal_abrechnung_id='".$_GET['portal_abrechnung_id']."'";
				} else {
					$whereand ='';
				}
				$query ="SELECT * FROM portal_abrechnung WHERE bezahlt_mahnung_senden < '".date("Y-m-d H:i:s")."' AND domain_id=".$strDomains['domain_id']." AND mahnung_gesendet='N' AND bezahlt_am ='0000-00-00 00:00:00'".$whereand;
				#echo $query;
				$resMahnung = DBi::$conn->query($query) or die('ERR00003: '.mysqli_error(DBi::$conn));
				
				

				while($strMahnung = mysqli_fetch_assoc($resMahnung)) {
					
					$iGebuehren = getGebuehrenTotal($strMahnung['Umsatz_ohne_versand']);
					
					$query ="UPDATE portal_abrechnung SET mahnung_gesendet='Y' WHERE portal_abrechnung_id=".$strMahnung['portal_abrechnung_id'];
					DBi::$conn->query($query);
					
					$query ="SELECT * FROM shop_info WHERE domain_id=".$strDomains['domain_id'];
					$resShopInhaber = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					echo "Mahnung an ".$strShopInhaber['email_shop_main']." | Summe: ".round($strMahnung['Umsatz_ohne_versand'],2)." EUR | Gebühr ".$iGebuehren['gebuehr']." EUR<br/><br/>";
					$strShopInhaber = mysqli_fetch_assoc($resShopInhaber);
				 
					$html = '<h1>Mahnung: Für Shopste Rechnung  '.getMonthName($strMahnung['abrechnung_monat']).' '.$strMahnung['abrechnung_jahr'].'</h1><br/>
					Hallo '.$strShopInhaber['vorname'].' '.$strShopInhaber['nachname'].', <br/>
					<br/>
					Sie haben Ihre fällige Rechnung vom '.getDateDE($strMahnung['in_rechnung_gestellt_am']).' noch nicht bezahlt, bitte überweisen Sie die Rechnung.<br/>
					Sollten Sie die Rechnung nicht demnächst bezahlen wird Ihr Shop geschlossen und Ihr Nutzername deaktiviert.<br/><br/>
					Sollten Sie die Rechnung bezahlt haben ignorieren Sie diese bitte z.B. Geldeingang am Tag der Fälligkeit.<br/>
					<br/><br/>
					Sie hatten einen Monatsumsatz in Ihrem Shop '.$strDomains['name'].' und Shopste Marktplatz von '.str_replace(".",",",round($strMahnung['Umsatz_ohne_versand'],2)).' EUR (ohne Versandkosten).<br/>Ihr Monatsumsatz wird mit '.str_replace(".",",",$iGebuehren['prozent']).'% Verkaufsprovisionen belegt.
					<br/><br/>
					Bitte überweisen Sie den folgenden Betrag '.str_replace(".",",",round($iGebuehren['gebuehr'],2)).' EUR innerhalb von 14 Tagen bis spätestens '.date('d.m.Y', strtotime("+14 days")).' auf dieses Konto:<br/>
					<br/>
					Bank: Volksbank Oldenburg<br/>
		Inhaber: Jan Bludau<br/>
		Kontonummer: 3430885700<br/>
		Bankleitzahl: 28061822<br/>
		IBAN: DE94280618223430885700<br/>
		BIC: GEN0DEF1EDE <br/>
		IDNr. Stpfl. 70 326 581 145<br/><br/>Die Umsatz ist nach Umsatzsteuergesetz §19 nicht steuerpflichtig<br/><br/>
		Viel Spaß dein <a href="https://shopste.com">Shopste.com</a> Team';
					
					//Create a new PHPMailer instance
					$mail = new PHPMailer();
					// Set PHPMailer to use the sendmail transport
					$mail->isSendmail();
					//Set who the message is to be sent from
					$mail->setFrom('payment@shopste.com', 'Shopste Abrechnung');
					//Set an alternative reply-to address
					$mail->addReplyTo('payment@shopste.com', 'Shopste Abrechnung');
					//Set who the message is to be sent to
					$mail->addAddress($strShopInhaber['email_shop_main'],utf8_decode($strShopInhaber['vorname'].' '.$strShopInhaber['nachname']));
					$mail->AddBCC("jbludau@bludau-media.de","Shopste Abrechnung");
					#$mail->AddBCC();
					//Set the subject line
					$mail->Subject = utf8_decode('Shopste Portal Mahnung für Rechnung '.getMonthName($strMahnung['abrechnung_monat']).' '.$strMahnung['abrechnung_jahr'].' für '.$strDomains['name']);
					//Read an HTML message body from an external file, convert referenced images to embedded,
					//convert HTML into a basic plain-text alternative body
					$mail->msgHTML(utf8_decode($html), dirname(__FILE__));
					//Replace the plain text body with one created manually
					$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';
					//Attach an image file
					//$mail->addAttachment('images/phpmailer_mini.png');

					//send the message, check for errors
					if (!$mail->send()) {
						echo "Mailer ".$$strDomains['name']." Error: " . $mail->ErrorInfo;
					} else {
						echo "Nachricht ".$$strDomains['name']." geschickt!";
					}	
					
				}
				
			} # end while 
			 
			
			break;
		case 'portal_abrechnung_bezahlt':
		
		
			################################################
			# RECHNUNG auf bezahlt stellen + Email senden
			################################################
			if($_GET['bezahlt'] == 'Y') {
				echo "============================================================\n";
				echo "RECHNUNG bezahlt für Abrechnung-Nr ".$_GET['abrechnung_id']."\n";
				echo "============================================================\n";
				# UPDATE RECHNUNG Monatsumsatz
				$query ="UPDATE portal_abrechnung SET bezahlt_am='".date("Y-m-d H:i:s")."' WHERE portal_abrechnung_id='".$_GET['abrechnung_id']."'";
				DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				
				##################
				# EMAIL
				##################

				$query = "SELECT * FROM portal_abrechnung WHERE portal_abrechnung_id='".$_GET['abrechnung_id']."'";
				$resRechnungExists = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$strRechnung = mysqli_fetch_assoc($resRechnungExists);
				
				# SHOP INHABER auslesen
				$query ="SELECT * FROM shop_info WHERE domain_id=".$strRechnung['domain_id'];
				$resShopInhaber = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$strShopInhaber = mysqli_fetch_assoc($resShopInhaber);
				
				$query ="SELECT * FROM domains WHERE  domain_id='".$strRechnung['domain_id']."' AND email_freischaltung='Y'";
				$resDomains = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$strDomain = mysqli_fetch_assoc($resDomains);
				
				$html = '<h1>Ihre Zahlungseingang für Rechnung '.getMonthName($strRechnung['abrechnung_monat']).' '.$strRechnung['abrechnung_jahr'].' wurde festgestellt</h1><br/>
				Hallo '.$strShopInhaber['vorname'].' '.$strShopInhaber['nachname'].', <br/>
				<br>
				Ihre Rechnung in höhe von '.str_replace(".",",",round($strRechnung['endsumme_gebuehr_ueberweisung'],2)).' EUR ist beglichen worden.
				<br/><br/>
	Viel Spaß dein <a href="https://shopste.com">Shopste.com</a> Team';
				
				//Create a new PHPMailer instance
				$mail = new PHPMailer();
				// Set PHPMailer to use the sendmail transport
				$mail->isSendmail();
				//Set who the message is to be sent from
				$mail->setFrom('payment@shopste.com', 'Shopste Abrechnung');
				//Set an alternative reply-to address
				$mail->addReplyTo('payment@shopste.com', 'Shopste Abrechnung');
				//Set who the message is to be sent to
				$mail->addAddress($strShopInhaber['email_shop_main'],utf8_decode($strShopInhaber['vorname'].' '.$strShopInhaber['nachname']));
				$mail->AddBCC(CORE_MAIL_SEND_API_BCC,CORE_MAIL_SEND_API_BCC_NAME);
				#$mail->AddBCC();
				//Set the subject line
				$mail->Subject = utf8_decode('Shopste Portal-Rechnung '.getMonthName($strRechnung['abrechnung_monat']).' '.$strRechnung['abrechnung_jahr'].' bezahlt  für '.$strDomain['name']);
				//Read an HTML message body from an external file, convert referenced images to embedded,
				//convert HTML into a basic plain-text alternative body
				$mail->msgHTML(utf8_decode($html), dirname(__FILE__));
				//Replace the plain text body with one created manually
				$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';
				//Attach an image file
				//$mail->addAttachment('images/phpmailer_mini.png');

				//send the message, check for errors
				if (!$mail->send()) {
					echo "Mailer Error: " . $mail->ErrorInfo;
				} else {
					echo "Rechnung bezahlt gesendet!";
				}	
				echo "Neue Rechnung abgeschickt";
				exit(0);
			}	
			
 
			
			switch($_GET['submodus']) {
				case 'list_month_orders':
					
					
					#$query = "SELECT * FROM domains WHERE domain_id='".$_GET['domain_id']."'";
					#$resDomains = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					#$strDomains = mysqli_fetch_assoc($resDomains);
					
					if($_GET['domain_crc'] == $strDomain['domain_crc'] && $_GET['domain_id'] == $strDomain['domain_id']) {						
							getListMonthOrdersByDate($strDomain);
					}
					
					break;
				case 'setStornoItem':
 
					
					if($_GET['domain_crc'] == $strDomain['domain_crc'] && $_GET['domain_id'] == $strDomain['domain_id']) {
					
						$query = "UPDATE shop_order_list SET bstorniert='".$_GET['bstorniert']."' WHERE shop_order_list_id='".$_GET['shop_order_list_id']."'";
						$resUpdate = DBi::$conn->query($query) or die('ERR0001:'.mysqli_error(DBi::$conn));;
						
						getListMonthOrdersByDate($strDomain);
						
					}
					

					
					break;
				case 'list_by_domain_id':
					 
						
					$query ="SELECT *,shop_order_list.created_at as sol_created FROM shop_order_list LEFT JOIN shop_order ON shop_order_list.id_shop_order = shop_order.shop_order_id LEFT JOIN shop_order_customer ON shop_order_customer.shop_order_customer_id = shop_order.ges_order_customer_id  WHERE id_domain='".$_GET['domain_id']."' ORDER BY shop_order_list.created_at DESC";
					$resOrderSelect = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					$html = '<h2>'.$strDomain['shop_name'].' - '.$strDomain['name'].' ('.$strDomain['domain_id'].')</h2>';
					$html .= '<a href="javascript:history.go(-1)">Zur Übersichtseite</a>';
					$html .='<table cellspacing="5" cellpadding="5">';
					$html .='<tr>';
						$html .='<td><strong>Art.Nr.</strong></td>';
						$html .='<td><strong>Verkauf am</strong></td>';
						$html .='<td><strong>Verkauf an</strong></td>';
						$html .='<td><strong>Name</strong></td>';
						$html .='<td><strong>Menge</strong></td>';
						$html .='<td><strong>Einzelpreis</strong></td>';
						$html .='<td><strong>Gesamtpreis</strong></td>';
						$html .='<td><strong>Aktion</strong></td>';
					$html .='</tr>';				
					
					
					while($strOrderSelect = mysqli_fetch_assoc($resOrderSelect)) {
						
							if($strOrderSelect['bstorniert'] == 'N') {
								$curArtikelsumme = $strOrderSelect['preis'] * $strOrderSelect['order_menge']; 
								$curGesArtikelSumme += $curArtikelsumme;
							}
							
							if($strOrderSelect['bstorniert'] == 'Y') {
								$strStorniertText = 'Wieder in Rechnung aufnehmen';
								$bStorniert = 'N';
							} else {
								$strStorniertText = 'Rechnungsposition stornieren';								
								$bStorniert = 'Y';
							}
							
							$html .= 
							'<tr>
								<td>'.$strOrderSelect['shop_item_id'].'</td>
								<td>'.getDateDE($strOrderSelect['sol_created']).'</td>
								<td>'.$strOrderSelect['vorname'].' '.$strOrderSelect['nachname'].' aus '.$strOrderSelect['stadt'].'</td>
								<td>'.$strOrderSelect['name_de'].'</td>
								<td>'.$strOrderSelect['order_menge'].'x</td>
								<td>'.money_format('%2i', $strOrderSelect['preis']).'</td>
								<td>'.money_format('%2i', ($strOrderSelect['order_menge'] * $strOrderSelect['preis'])).'</td>
								<td><a href="/api.php?modus=portal_abrechnung_bezahlt&submodus=setStornoItem&domain_id='.$_GET['domain_id'].'&shop_order_list_id='.$strOrderSelect['shop_order_list_id'].'&month='.$_GET['month'].'&year='.$_GET['year'].'&domain_crc='.$strDomain['domain_crc'].'&bstorniert='.$bStorniert.'">'.$strStorniertText.'</a></td>
							</tr>';
							
					}
					
					$html .='</table>';
					
					$iGebuehren = getGebuehrenTotal($curGesArtikelSumme);
					
					#print_r($iGebuehren);
					$html .= '<br/><strong>Total Verkauf:</strong> <u>'.money_format('%2i',$curGesArtikelSumme).'</u> <strong>Gebühr:</strong> <u>'.money_format('%2i',$iGebuehren['gebuehr']).'</u> <strong>Prozent:</strong> <u>'.$iGebuehren['prozent'].'</u> %';
					
					echo $html;
					break;
				case 'action_send_invoice_again':
					# Email an Kunden erneut verschicken
					#&domain_id='.$strRechnung['domain_id'].'&month='.$strRechnung['abrechnung_monat'].'&year='.$strRechnung['abrechnung_jahr'].'"
					
 
					setCustomerInvoiceEmail($strDomain);
					break;
				default:
					
					if($_GET['pass'] != 'krone') {
						exit(0);
					}
					$query = "SELECT * FROM portal_abrechnung ORDER BY abrechnung_jahr DESC, abrechnung_monat DESC";
					$resRechnungExists = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					
					$curArtikelSummeMonth = 0;
					$curGebührSummeMonth = 0;
					$curCountMonth = 1;
					echo "<h1>Shopste.com Rechnungswesen</h1>";
					#$strDomain = array();
					while($strRechnung = mysqli_fetch_assoc($resRechnungExists)) {
									
						$strDomain = getDomainAry($strRechnung['domain_id']);


						
						if($strRechnung['abrechnung_monat'] == $curMonth) {
							$curArtikelSummeMonth += $strRechnung['Umsatz_ohne_versand'];
							$curGebührSummeMonth += $strRechnung['endsumme_gebuehr_ueberweisung'];		
							$html_content_head_sub ='';							
						} else {
							
							$html_content_header_stat_panel .= " # <strong>Nr. ".$curCountMonth." Zeitraum:</strong> ".getMonthName($strRechnung['abrechnung_monat']).'/'.$strRechnung['abrechnung_jahr'].'  <strong>Umsatz:</strong> <u>'.money_format('%2i',$curArtikelSummeMonth).'</u> - <strong>Gebühren:</strong> <u>'.money_format('%2i',$curGebührSummeMonth).'</u><br/>';
							
							$html_content_head_sub .= "<h2>Rechnungszeitraum ".getMonthName($strRechnung['abrechnung_monat']).'/'.$strRechnung['abrechnung_jahr'].'</h2> <strong>Umsätze aller Shops:</strong> '.money_format('%2i',$curArtikelSummeMonth).' <strong>Shopste Gebühren:</strong> '.money_format('%2i',$curGebührSummeMonth).'<br/>';
							
							$curMonth = $strRechnung['abrechnung_monat'];
							$curArtikelSummeMonth = 0;
							$curGebührSummeMonth = 0;
							$curCountMonth++;
							
							$curArtikelSummeMonth += $strRechnung['Umsatz_ohne_versand'];
							$curGebührSummeMonth += $strRechnung['endsumme_gebuehr_ueberweisung'];
						}
						
						
						#echo $strRechnung['bezahlt_am'];
						
						$strBezahlt =  '<strong>Rechnung gesendet am:</strong> '.getDateDE($strRechnung['in_rechnung_gestellt_am']).'<br/>';
						
						if(empty($strRechnung['bezahlt_am']) != 1) {
							$strColorBezahlt = '#DD11AA';
							$strBezahlt .= '<font color="'.$strColorBezahlt.'"><strong>Bezahlt am:</strong> '.getDateDE($strRechnung['bezahlt_am']).'</font><br/>';
						} else {
							$strColorBezahlt = 'white';
						}						

						if(empty($strRechnung['bezahlt_mahnung_senden']) != 1) {
							$strBezahlt .= '<strong>Mahnung gesendet am:</strong> '.getDateDE($strRechnung['bezahlt_mahnung_senden']).'<br/>';
						}										
						
						# HTML Template für Webpage
						if(!empty($html_content_head_sub)) {
							$html_content .= $html_content_head_sub;
						}
						
						if(!empty($strRechnung['bezahlt_mahnung_senden'])) {
							$strMahnungsdatum = ' am: '.getDateDE($strRechnung['bezahlt_mahnung_senden']);
						}
						
						
						if($strDomain['bIsSSL'] == 'Y') {
							$strSchema = 'https';
						} else {
							$strSchema = 'http';
						}
						$iCount++;
						$html_content .= '<br/>
						<strong>Shopname:</strong> '.$strDomain['shop_name'].' | <a href="'.$strSchema.'://'.$strDomain['name'].'/" target="_blank">Webshop öffnen</a><br/>
						<strong>Rechnungsdatum:</strong> '.getDateDE($strRechnung['in_rechnung_gestellt_am']).'<br/>
						<strong>Shop-Umsatz:</strong> <u>'.money_format('%2i',$strRechnung['Umsatz_ohne_versand']).'</u> <strong>Shopste Gebühren:</strong> <u>'.money_format('%2i',$strRechnung['endsumme_gebuehr_ueberweisung']).'</u><br/>
						<strong>Rechnung:</strong> <a href="/api.php?modus=portal_abrechnung_bezahlt&submodus=list_month_orders&domain_id='.$strRechnung['domain_id'].'&month='.$strRechnung['abrechnung_monat'].'&year='.$strRechnung['abrechnung_jahr'].'&domain_crc='.$strDomain['domain_crc'].'">'.$strRechnung['abrechnung_jahr'].'/'.$strRechnung['abrechnung_monat'].' ansehen</a><br/>
<a id="action_pos_'.$iCount.'"></a>						
						<strong>Bestellverlauf für Domain:</strong> <a href="/api.php?modus=portal_abrechnung_bezahlt&submodus=list_by_domain_id&domain_id='.$strDomain['domain_id'].'&domain_crc='.$strDomain['domain_crc'].'">'.$strDomain['name'].' (DID: '.$strDomain['domain_id'].')</a></strong><br/>						
						'.$strBezahlt.'
						<strong>Aktion:</strong> <a href="/api.php?modus=portal_abrechnung_bezahlt&abrechnung_id='.$strRechnung['portal_abrechnung_id'].'&bezahlt=Y&domain_crc='.$strDomain['domain_crc'].'&domain_id='.$strDomain['domain_id'].'#action_pos_'.$iCount.'">Rechnung bezahlt setzten</a> 
						| <a href="/api.php?modus=portal_abrechnung_mahnung&domain_id='.$strRechnung['domain_id'].'&portal_abrechnung_id='.$strRechnung['portal_abrechnung_id'].'&domain_crc='.$strDomain['domain_crc'].'&domain_id='.$strDomain['domain_id'].'#action_pos_'.$iCount.'">Mahnung erneut versenden</a> 
						| <a href="/api.php?modus=portal_abrechnung_bezahlt&submodus=action_send_invoice_again&renew_send_email=1&domain_id='.$strRechnung['domain_id'].'&domain_crc='.$strDomain['domain_crc'].'&month='.$strRechnung['abrechnung_monat'].'&year='.$strRechnung['abrechnung_jahr'].'#action_pos_'.$iCount.'">Rechnung erneut verschicken</a> <br/><br/>
						<hr>';
						
						# Gesammtsummen Brechnung
						$gebuehr_ges += $strRechnung['endsumme_gebuehr_ueberweisung'];
						$umsatz_ges  += $strRechnung['Umsatz_ohne_versand'];
						
					}
					
					# Gebühren Ausgeben
					echo "<h2>Überblicksdaten von Shopste.com</h2>";
					echo '<strong>Total Umsatz:</strong> <u>'.money_format('%2i',$umsatz_ges).'</u> - <strong>Total Gebühr:</strong> <u>'.money_format('%2i',$gebuehr_ges).'</u><br/><br/>';	
					echo $html_content_header_stat_panel;				
					echo $html_content;
					
					break;
			}
			
			
			break;
		case 'core_gen_domain_id':
			$query = "SELECT * FROM domains";
			$resDomains = DBi::$conn->query($query) or die('ERR00001:'.mysqli_error(DBi::$conn));
			while($strDomains = mysqli_fetch_assoc($resDomains)) {
				
				$strQuery = "UPDATE domains SET domain_crc='".crc32($strDomains['created_at'])."' WHERE domain_id='".$strDomains['domain_id']."'";
				DBi::$conn->query($strQuery) or die('ERR00002:'.mysqli_error(DBi::$conn));;
			}
			break;			
		case 'cron_portal_abrechnung':			
			
			if(isset($_GET['api-key'])) {
				if($_GET['api-key'] != CORE_CRON_API_KEY) {
					exit(0);
				}	
			} else {
				exit(0);
			} 
			
			$strDomain = '';
			setCustomerInvoiceEmail($strDomain);
			break;
		case 'get_page_url':
			echo "http://".$_SERVER['SERVER_NAME'].'/'.getPathUrl($_SESSION['language'],$_POST['pageid']);
			exit;
			break;
		case 'system_upload_file':
		
			echo "Name: " . $_FILES["system_upload"]["name"] . "<br>";
			echo "Type: " . $_FILES["system_upload"]["type"] . "<br>";
			echo "Groese: " . ($_FILES["system_upload"]["size"] / 1024) . " kB<br>";
			echo "tmp name: " . $_FILES["system_upload"]["tmp_name"] . "<br>";
			echo "domain: " . $_POST['domain_pfad'] . "<br>";
			
			require_once('include/inc_thumbnails.php');
			
			if(file_exists("portals/".$_POST['domain_pfad']."image/") == false) {
				mkdir("portals/".$_POST['domain_pfad']."image/",0777);
			}
			if(file_exists("portals/".$_POST['domain_pfad']."image/produkte/") == false) {
				mkdir("portals/".$_POST['domain_pfad']."image/produkte/",0777);
			}
			if(file_exists("portals/".$_POST['domain_pfad']."image/produkte/orginal/") == false) {
				mkdir("portals/".$_POST['domain_pfad']."image/produkte/orginal/",0777);
			}
			if(file_exists("portals/".$_POST['domain_pfad']."image/produkte/kategorie/") == false) {
				mkdir("portals/".$_POST['domain_pfad']."image/produkte/kategorie/",0777);
			}
			if(file_exists("portals/".$_POST['domain_pfad']."image/produkte/detail/") == false) {
				mkdir("portals/".$_POST['domain_pfad']."image/produkte/detail/",0777);
			}
			
			# Orginalbild abspeichern
			#$FileExt = "jpg";
			#$_FILES['system_upload']['name']
			$strBildname = $_POST["picture_name"].".".$_POST["picture_ext"];
			move_uploaded_file($_FILES["system_upload"]["tmp_name"],"portals/".$_POST['domain_pfad']."image/produkte/orginal/" . $strBildname);		

			$query = "SELECT count(*) as anzahl FROM shop_item_picture WHERE shop_item_id='".$_POST['shop_id']."'";
			$strHauptBildDa = mysqli_fetch_assoc(DBi::$conn->query($query)); 
			$iNr = $strHauptBildDa['anzahl'];
	
			$query = "INSERT INTO shop_item_picture (shop_item_id,picture_url,modus,picture_nr) VALUES ('".$_POST['shop_id']."','/portals/".$_POST['domain_pfad']."image/produkte/orginal/".$strBildname."','orginal-picture','".($iNr +1)."')";		
			DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			
			$path = realpath($_SERVER["DOCUMENT_ROOT"]);
			
			$strMainPicture = $path."/portals/".$_POST['domain_pfad']."image/produkte/orginal/".$strBildname;
			
			echo "Main Picture: " . $strMainPicture.'<br/>';
			
			$strFilePath = explode(".",$strMainPicture);
			$type = strtolower($strFilePath[count($strFilePath)-1]);
			
			if($type == 'jpeg') $type = 'jpg';
			switch($type){
				case 'bmp': $img = imagecreatefromwbmp($strMainPicture); break;
				case 'gif': $img = imagecreatefromgif($strMainPicture); break;
				case 'jpg': $img = imagecreatefromjpeg($strMainPicture); break;
				case 'png': $img = imagecreatefrompng($strMainPicture); break;
				default : $img = imagecreatefromjpeg($strMainPicture); echo "Unsupported picture type!"; break; 
			}
			#$im = imagecreatefromjpeg($strMainPicture);
			$im = image_resample($img, 230, 200, "get", "000000");
			switch($type){
				case 'bmp': $img = imagewbmp($im,$path.'/portals/'.$_POST['domain_pfad'].'image/produkte/kategorie/'.$strBildname); break;
				case 'gif': $img = imagegif($im,$path.'/portals/'.$_POST['domain_pfad'].'image/produkte/kategorie/'.$strBildname); break;
				case 'jpg': $img = imagejpeg($im,$path.'/portals/'.$_POST['domain_pfad'].'image/produkte/kategorie/'.$strBildname); break;
				case 'png': $img = imagepng($im,$path.'/portals/'.$_POST['domain_pfad'].'image/produkte/kategorie/'.$strBildname); break;
				default : $img = imagejpeg($im,$path.'/portals/'.$_POST['domain_pfad'].'image/produkte/kategorie/'.$strBildname); echo "Nicht unterstützt!";break;
			}
			#imagejpeg($im,$path.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/kategorie/'.$_FILES['file1']['name']);
			#exit;
			echo "Kat Picture: " . $path.'/portals/'.$_POST['domain_pfad'].'image/produkte/kategorie/'.$strBildname.'<br/>';
			imagedestroy($img);
			
			#########################################################
			# Detailansicht Bild erzeugen
			#########################################################
			#$path = realpath($_SERVER["DOCUMENT_ROOT"]);
			$strMainPicture = $path."/portals/".$_POST['domain_pfad']."image/produkte/orginal/".$strBildname;
			# $type = strtolower(substr(strrchr($strMainPicture,"."),1));
			 if($type == 'jpeg') $type = 'jpg';
			  switch($type){
				case 'bmp': $img = imagecreatefromwbmp($strMainPicture); break;
				case 'gif': $img = imagecreatefromgif($strMainPicture); break;
				case 'jpg': $img = imagecreatefromjpeg($strMainPicture); break;
				case 'png': $img = imagecreatefrompng($strMainPicture); break;
				default :  $img = imagecreatefromjpeg($strMainPicture);  echo "Nicht unterstützt!";break;
			  }
			  
			#$im = imagecreatefromjpeg($path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/orginal/".$_FILES['file1']['name']);
			$im = image_resample($img, 350, 350, "get", "000000");
			
			#$type = strtolower(substr(strrchr($strMainPicture,"."),1));
			if($type == 'jpeg') $type = 'jpg';
			  switch($type){
				case 'bmp': $img = imagewbmp($im,$path.'/portals/'.$_POST['domain_pfad'].'image/produkte/detail/'.$strBildname); break;
				case 'gif': $img = imagegif($im,$path.'/portals/'.$_POST['domain_pfad'].'image/produkte/detail/'.$strBildname); break;
				case 'jpg': $img = imagejpeg($im,$path.'/portals/'.$_POST['domain_pfad'].'image/produkte/detail/'.$strBildname); break;
				case 'png': $img = imagepng($im,$path.'/portals/'.$_POST['domain_pfad'].'image/produkte/detail/'.$strBildname); break;
				default : $img = imagejpeg($im,$path.'/portals/'.$_POST['domain_pfad'].'image/produkte/detail/'.$strBildname); echo "Unsupported picture type!"; break; 
			}
			#imagejpeg($im,$path.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/detail/'.$_FILES['file1']['name']);
			#exit;
			imagedestroy($im);
			
			#mail("jbludau@bludau-media.de","Datei hochgeladen ".$_FILES["system_upload"]["name"].' für '.$_POST['benutzername'],$_FILES["system_upload"]["name"].$_FILES["system_upload"]["type"]);
			
			break;
			case 'system_shop_item_add_shopste_only':
			
			if(!empty($_POST['shopste_cat'])) {
				######################################
				# EiSo Import mit Kategorie 
				######################################
				
				$_POST['shop_item_name'] = trim($_POST['shop_item_name']);
				
				# Artikel anlegen!
				$query = "SELECT * FROM shop_item WHERE item_number='".$_POST['shop_item_artikelnummer']."' AND domain_id='".$_POST['domain_id']."'";
				
				#mail("jbludau@bludau-media.de","Datei hochgeladen ".$_POST['shop_item_name'],$_POST['shop_item_beschreibung']);
				$resInsert = DBi::$conn->query($query) or die('ER:0005'.mysqli_error(DBi::$conn)); 
				$shop_item_id_ary = mysqli_fetch_assoc($resInsert);
				#print_r($shop_item_id_ary);
				if($shop_item_id_ary['shopste_marktplatz_menue_id'] == 0) {
					$shop_item_id = $shop_item_id_ary['shop_item_id'];
					echo 'shopid:'.$shop_item_id;
					//
					// SHOPSTE.COM SEITE + MODULE anlegen
					//
					$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id,created_at,template_file) VALUES ('".$_POST['shop_item_name']."', '".$_POST['shop_item_name']."', '0', 'produktunsichtbar', 'col2-left-layout','".$_POST['domain_id']."','".date("Y-m-d H:i:s")."','index.tpl')";
					#echo $query;
					$resInsert = DBi::$conn->query($query) or die('ER:0001'.mysqli_error(DBi::$conn));
					$iPageID_shopste = mysqli_insert_id(DBi::$conn);
					
					# Die Seiten ID der Kategorie ermitteln
					$query1 ="SELECT * FROM shop_category WHERE shop_cat_id = '".$_POST['shopste_cat']."'";
					#echo $query;
					$resCatTbl = DBi::$conn->query($query1) or die('ER:0001-1'.$query1.mysqli_error(DBi::$conn));
					$strMenueTbl = mysqli_fetch_assoc($resCatTbl);
				
					#$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID.", ".$_POST['page_menue_id'].");";
					# Wird automatisch in dem "Pfad" des Menü gespeichert (übergeordnet ist die Kategorie id)
					$query2 = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID_shopste.", ".$strMenueTbl['page_id'].");";
					#echo $query;
					$resInsert = DBi::$conn->query($query2) or die('ER:0002'.$query1.' '.$query2.mysqli_error(DBi::$conn));
					
					# Menü Path ebene davor
					$query = "SELECT * FROM menue_parent WHERE menue_id='".$iPageID_shopste."'";
					$res3 = mysqli_fetch_array(DBi::$conn->query($query));
					
					if(!empty($res3['parent_id'])) {
						$strSubMenue = $res3['parent_id'];
					} else {
						$strSubMenue = $iPageID_shopste;
					}
		
					// Modul Einstellugen Speichern
					$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('".$_POST['shop_item_name']."', ".$strSubMenue.", 0, 'submenue', 'Y');";
					$resInsert = DBi::$conn->query($query) or die('0005:'.mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
				
					// Modul auf einer Seite bekannt machen
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID_shopste.", ".$iModulID.", 'menue', 'col-left', '0');";
					$resInsert = DBi::$conn->query($query) or die('0006:'.mysqli_error);
					
					#$query = "INSERT INTO `modul_menue_shopcategory` (`title_de`, `menue_id`, `last_usr`,content_de,created_at) VALUES ('Shop Themenwelten', ".$iPageID_shopste.", 0, ' ','".date("Y-m-d H:i:s")."');";
					#$resInsert = DBi::$conn->query($query) or die('ER:0003: '.$query.' '.mysqli_error(DBi::$conn));
					#$iModulID = mysqli_insert_id(DBi::$conn);
					
					#$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID_shopste.", ".$iModulID.", 'menue_shopcategory', 'col-left', '0');";
					
					#$resInsert = DBi::$conn->query($query) or die('ER:0004'.mysqli_error(DBi::$conn));

					// Modul Einstellugen Speichern
					$query = "INSERT INTO `modul_portal_shop_item_detail` (`title_de`, `menue_id`, `last_usr`,shop_item_id) VALUES ('".$_POST['shop_page_titel']."', ".$iPageID_shopste.", 0,'".$shop_item_id."');";
					$resInsert = DBi::$conn->query($query) or die('0003:'.mysqli_error(DBi::$conn));
					$iModulID = mysqli_insert_id(DBi::$conn);
				
					// Modul auf einer Seite bekannt machen
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID_shopste.", ".$iModulID.", 'portal_shop_item_detail', 'col-main', '0');";
					$resInsert = DBi::$conn->query($query) or die('0004:'.mysqli_error(DBi::$conn));
					
					# In Marktplatz einstellen nur wenn Shopste.com angelegt wurde
					#echo $iPageID_shopste;
					if($iPageID_shopste != '') {
						$query = "UPDATE shop_item SET shopste_marktplatz_cat='".$_POST['shopste_cat']."',shopste_marktplatz_menue_id='".$iPageID_shopste."' WHERE shop_item_id='".$shop_item_id."'";
						DBi::$conn->query($query) or die('ER:0005'.mysqli_error(DBi::$conn));
					}
				}
			}
			break;
		case 'system_shop_item_add':	
		
		$bLogedIn = isLoggedIn($_POST['txtUsername'],$_POST['txtPasswort'],true);
			
		if($bLogedIn == true) {
				
				$_POST['shop_item_name'] = trim($_POST['shop_item_name']);
				$query = "SELECT count(*) as anzahl FROM shop_item WHERE item_number='".$_POST['shop_item_artikelnummer']."' AND domain_id='".$_POST['domain_id']."'";
				$resShopItem = DBi::$conn->query($query);
				$strShopInfoCount = mysqli_fetch_assoc($resShopItem); 
				if($strShopInfoCount['anzahl'] > 0) {
					
					# JTL2Shopste Modus
					if($_POST['submodus'] == 'jtl2shopste') {
						
						
						$query = "SELECT * FROM shop_item WHERE item_number='".$_POST['shop_item_artikelnummer']."' AND domain_id='".$_POST['domain_id']."'";
						$resShopItem = DBi::$conn->query($query);
						$strShopInfo = mysqli_fetch_assoc($resShopItem); 
				
						if($strShopInfo['shopste_marktplatz_cat'] == 0 && $_POST['shopste_cat'] != '') {
							$query1 ="SELECT * FROM shop_category WHERE shop_cat_id = '".$_POST['shopste_cat']."'";
							#echo $query;
							$resCatTbl = DBi::$conn->query($query1) or die('ER:0001-1'.$query1.mysqli_error(DBi::$conn));
							$strMenueTbl = mysqli_fetch_assoc($resCatTbl);
						 
							$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id,created_at,template_file) VALUES ('".$_POST['shop_item_name']."', '".$_POST['shop_item_name']."', '0', 'produktunsichtbar', 'col2-left-layout','".$_POST['domain_id']."','".date("Y-m-d H:i:s")."','index.tpl')";
							#echo $query;
							$resInsert = DBi::$conn->query($query) or die('ER:0001'.mysqli_error(DBi::$conn));
							$iPageID_shopste = mysqli_insert_id(DBi::$conn);
							
							#echo "shopste_markplatz- ".$iPageID_shopste;
							 
							#$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID.", ".$_POST['page_menue_id'].");";
							# Wird automatisch in dem "Pfad" des Menü gespeichert (übergeordnet ist die Kategorie id)
							$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID_shopste.", ".$strMenueTbl['page_id'].");";
							#echo $query;
							$resInsert = DBi::$conn->query($query) or die('ER:0002'.$query1.mysqli_error(DBi::$conn));
							
							# Menü Path ebene davor
							$query = "SELECT * FROM menue_parent WHERE menue_id='".$iPageID_shopste."'";
							$res3 = mysqli_fetch_array(DBi::$conn->query($query));
							
							if(!empty($res3['parent_id'])) {
								$strSubMenue = $res3['parent_id'];
							} else {
								$strSubMenue = $iPageID_shopste;
							}
				
							// Modul Einstellugen Speichern
							$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('".$_POST['shop_item_name']."', ".$strSubMenue.", 0, 'submenue', 'Y');";
							$resInsert = DBi::$conn->query($query) or die('0005:'.mysqli_error(DBi::$conn));
							$iModulID = mysqli_insert_id(DBi::$conn);
						
							// Modul auf einer Seite bekannt machen
							$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID_shopste.", ".$iModulID.", 'menue', 'col-left', '0');";
							$resInsert = DBi::$conn->query($query) or die('0006:'.mysqli_error);
							
							#$query = "INSERT INTO `modul_menue_shopcategory` (`title_de`, `menue_id`, `last_usr`,content_de,created_at) VALUES ('Shop Themenwelten', ".$iPageID_shopste.", 0, ' ','".date("Y-m-d H:i:s")."');";
							#$resInsert = DBi::$conn->query($query) or die('ER:0003: '.$query.' '.mysqli_error(DBi::$conn));
							#$iModulID = mysqli_insert_id(DBi::$conn);
							
							#$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID_shopste.", ".$iModulID.", 'menue_shopcategory', 'col-left', '0');";
							
							#$resInsert = DBi::$conn->query($query) or die('ER:0004'.mysqli_error(DBi::$conn));

							// Modul Einstellugen Speichern
							$query = "INSERT INTO `modul_portal_shop_item_detail` (`title_de`, `menue_id`, `last_usr`,shop_item_id) VALUES ('".$_POST['shop_page_titel']."', ".$iPageID_shopste.", 0,'".$strShopInfo['shop_item_id']."');";
							$resInsert = DBi::$conn->query($query) or die('0003:'.mysqli_error(DBi::$conn));
							$iModulID = mysqli_insert_id(DBi::$conn);
						
							// Modul auf einer Seite bekannt machen
							$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID_shopste.", ".$iModulID.", 'portal_shop_item_detail', 'col-main', '0');";
							$resInsert = DBi::$conn->query($query) or die('0004:'.mysqli_error(DBi::$conn));
							
							# In Marktplatz einstellen nur wenn Shopste.com angelegt wurde
							#echo $iPageID_shopste;

							if($iPageID_shopste != '') {
								
								$query = "UPDATE shop_item SET shopste_marktplatz_cat='".$_POST['shopste_cat']."',shopste_marktplatz_menue_id='".$iPageID_shopste."' WHERE shop_item_id='".$strShopInfo['shop_item_id']."'";
								DBi::$conn->query($query) or die('ER:0005'.mysqli_error(DBi::$conn));
							}
						}
							
						
				
							#echo "IN"; 
						if($_POST['shop_item_name'] != '') {
							$strSQL = "name_de='".html_entity_decode($_POST['shop_item_name'])."',";
						} 
						
						if(empty($_POST['gewicht'])) {
								$_POST['gewicht'] = "0.0";
						}
						
						if(!empty($_POST['gewicht'])) { 
							$strSQL .= "gewicht='".$_POST['gewicht']."',";
						}
						if($_POST['shop_item_menge'] != '') {
							$strSQL .= "menge='".$_POST['shop_item_menge']."',";
						}
						if($_POST['domain_id'] != '') {
							$strSQL .= "domain_id='".$_POST['domain_id']."',";
						}
						if($_POST['shop_item_duration'] != '') {
							$strSQL .= "item_dauer='".$_POST['shop_item_duration']."',";
						}
						if($_POST['shop_item_mwst'] != '') {
							$strSQL .= "item_mwst='".$_POST['shop_item_mwst']."',";
						}
						if($_POST['shop_item_price'] != '') {
							$strSQL .= "ean='".$_POST['ean']."',";
						}
						if($_POST['lieferzeit'] != '') {
							$strSQL .= "lieferzeit='".$_POST['lieferzeit']."',";
						}
						 
						if(empty($_POST['versandkosten'])) {
								$_POST['versandkosten'] = "0.0";
						}
							
						if($_POST['versandkosten'] != '') {
							$strSQL .= "versandkosten='".$_POST['versandkosten']."',";
						}
						
						if(!empty($_POST['shopste_cat'])) {
							$strSQL .= "shopste_marktplatz_cat='".$_POST['shopste_cat']."',";
						}

						if(!empty($_POST['subshop_cat'])) {
							// Shop Kategorie aus jtl_shop_catid holen
							$strQuery = "SELECT * FROM shop_category WHERE shop_cat_id = '".$_POST['subshop_cat']."'";
							$res = DBi::$conn->query($strQuery);
							$strData = mysqli_fetch_assoc($res);
							#print_r($strData);
							$strSQL .= "shop_cat_id='".$strData['shop_cat_id']."',";
						} 
						
						if(strlen($strSQL) > 0) {
							$strSQL = substr($strSQL,0,strlen($strSQL) -1);
						}
						
					} else {
						#echo "IN";
						if($_POST['shop_item_name'] != '') {
							$strSQL = "name_de='".html_entity_decode($_POST['shop_item_name'])."',";
						} 
						
						if(empty($_POST['gewicht'])) {
								$_POST['gewicht'] = "0.0";
						}
						
						if(!empty($_POST['gewicht'])) { 
							$strSQL .= "gewicht='".$_POST['gewicht']."',";
						}
						if($_POST['shop_item_menge'] != '') {
							$strSQL .= "menge='".$_POST['shop_item_menge']."',";
						}
						if($_POST['domain_id'] != '') {
							$strSQL .= "domain_id='".$_POST['domain_id']."',";
						}
						if($_POST['shop_item_duration'] != '') {
							$strSQL .= "item_dauer='".$_POST['shop_item_duration']."',";
						}
						if($_POST['shop_item_mwst'] != '') {
							$strSQL .= "item_mwst='".$_POST['shop_item_mwst']."',";
						}
						if($_POST['shop_item_price'] != '') {
							$strSQL .= "ean='".$_POST['ean']."',";
						}
						if($_POST['lieferzeit'] != '') {
							$strSQL .= "lieferzeit='".$_POST['lieferzeit']."',";
						}
						
						if(empty($_POST['versandkosten'])) {
								$_POST['versandkosten'] = "0.0";
						}
							
						if($_POST['versandkosten'] != '') {
							$strSQL .= "versandkosten='".$_POST['versandkosten']."',";
						}
						
						if(!empty($_POST['shopste_cat'])) {
							$strSQL .= "shopste_marktplatz_cat='".$_POST['shopste_cat']."',";
						}

						if(!empty($_POST['subshop_cat'])) {
							$strSQL .= "shop_cat_id='".$_POST['subshop_cat']."',";
						}
						
						if(strlen($strSQL) > 0) {
							$strSQL = substr($strSQL,0,strlen($strSQL) -1);
						}
					
					}
					
 					#echo $strSQL;
					# Artikel updaten!
					$query = "UPDATE `shop_item` SET ".$strSQL." WHERE item_number='".$_POST['shop_item_artikelnummer']."'";
					
					#mail("jbludau@bludau-media.de","Datei hochgeladen ".$_POST['shop_item_name'],$_POST['shop_item_beschreibung']);
					$resInsert = DBi::$conn->query($query) or die($query.'ER:0005'.mysqli_error(DBi::$conn)); 
					
					if($_POST['shop_item_artikelnummer'] == "0") {
						echo "ERR-API: Kein Artikel mit dieser Artikelnummer '0' gefunden.";				
					} else {						
						echo "shopid:".$_POST['shop_item_artikelnummer'];
					}
					
				} else {
					
					if(!empty($_POST['shopste_cat']) && !empty($_POST['subshop_cat'])) {
						######################################
						# EiSo Import mit Kategorie 
						######################################
						
						# Artikel anlegen!
						
						if(empty($_POST['gewicht'])) {
							$_POST['gewicht'] = "0.0";							
						} 
						if(empty($_POST['versandkosten'])) {
							$_POST['versandkosten'] = "0.0";
						}
						
						if($_POST['submodus'] == 'jtl2shopste') {
							
						#	echo "IN008";
							
							
							if(!empty($_POST['subshop_cat'])) {
								// Shop Kategorie aus jtl_shop_catid holen
								$strQuery = "SELECT * FROM shop_category WHERE shop_cat_id = '".$_POST['subshop_cat']."'";
								$res = DBi::$conn->query($strQuery);
								$strData = mysqli_fetch_assoc($res);
								#print_r($strData); 
								$strJTLShopsteKategorie = $strData['shop_cat_id'];
							#echo "IN008  - ".$strJTLShopsteKategorie;
							}  
						
						}
						
						if($_POST['parrent_shop_item_id'] != "") {
							$parrent_shop_item_id_col = ",parrent_shop_item_id";
							$parrent_shop_item_id_val = "',".$_POST['parrent_shop_item_id']."'";
						}
						  
						$query = "INSERT INTO `shop_item` (`name_de`, `preis`, `shop_cat_id`, `menue_id`,menge,beschreibung,gewicht,item_number,domain_id,status_de,system_closed_shop,item_enabled,item_dauer,item_mwst,ean,lieferzeit,created_at,versandkosten $parrent_shop_item_id_col) VALUES ('".html_entity_decode($_POST['shop_item_name'])."', '".$_POST['shop_item_price']."', '0', '0', '".$_POST['shop_item_menge']."', '".html_entity_decode($_POST['shop_item_beschreibung'])."', '".$_POST['gewicht']."','".$_POST['shop_item_artikelnummer']."','".$_POST['domain_id']."','verkaufsbereit','N','Y','".$_POST['shop_item_duration']."','".$_POST['shop_item_mwst']."','".$_POST['ean']."','".$_POST['lieferzeit']."','".date("Y-m-d H:i:s")."','".$_POST['versandkosten']."' $parrent_shop_item_id_val);";
						
						#mail("jbludau@bludau-media.de","Datei hochgeladen ".$_POST['shop_item_name'],$_POST['shop_item_beschreibung']);
						$resInsert = DBi::$conn->query($query) or die('ER:0055'.mysqli_error(DBi::$conn)); 
						$shop_item_id = mysqli_insert_id(DBi::$conn);
									
						$query = "INSERT INTO `shop_item_category2items`(shopste_item_cat,shopste_item) VALUES('".$_POST['subshop_cat']."','".$shop_item_id."')";
						#echo $query;
						$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 
						//
						// SHOPSTE.COM SEITE + MODULE anlegen
						//
							
						# Wurde Shopste Marktplatz ID übergeben
						if(!empty($_POST['shopste_cat'])) {
						#	echo "Shopste Adden";
							$query ="SELECT * FROM shop_category WHERE shop_cat_id = '".$_POST['shopste_cat']."'";
							#echo $query;
							$resCatTbl = DBi::$conn->query($query) or die('ER:0001-1'.$query.mysqli_error(DBi::$conn));
							$strMenueTbl = mysqli_fetch_assoc($resCatTbl);
						 
							$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id,created_at,template_file) VALUES ('".$_POST['shop_item_name']."', '".$_POST['shop_item_name']."', '0', 'produktunsichtbar', 'col2-left-layout','".$_POST['domain_id']."','".date("Y-m-d H:i:s")."','index.tpl')";
							#echo $query;
							$resInsert = DBi::$conn->query($query) or die('ER:0001'.mysqli_error(DBi::$conn));
							$iPageID_shopste = mysqli_insert_id(DBi::$conn);
							
							#$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID.", ".$_POST['page_menue_id'].");";
							# Wird automatisch in dem "Pfad" des Menü gespeichert (übergeordnet ist die Kategorie id)
							$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID_shopste.", ".$strMenueTbl['page_id'].");";
							#echo $query;
							$resInsert = DBi::$conn->query($query) or die('ER:0002-1'.$query.mysqli_error(DBi::$conn));
							
							# Menü Path ebene davor
							$query = "SELECT * FROM menue_parent WHERE menue_id='".$iPageID_shopste."'";
							$res3 = mysqli_fetch_array(DBi::$conn->query($query));
							
							if(!empty($res3['parent_id'])) {
								$strSubMenue = $res3['parent_id'];
							} else {
								$strSubMenue = $iPageID_shopste;
							}
				
							// Modul Einstellugen Speichern
							$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('".$_POST['shop_item_name']."', ".$strSubMenue.", 0, 'submenue', 'Y');";
							$resInsert = DBi::$conn->query($query) or die('0005:'.mysqli_error(DBi::$conn));
							$iModulID = mysqli_insert_id(DBi::$conn);
						
							// Modul auf einer Seite bekannt machen
							$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID_shopste.", ".$iModulID.", 'menue', 'col-left', '0');";
							$resInsert = DBi::$conn->query($query) or die('0006:'.mysqli_error);
							
							#$query = "INSERT INTO `modul_menue_shopcategory` (`title_de`, `menue_id`, `last_usr`,content_de,created_at) VALUES ('Shop Themenwelten', ".$iPageID_shopste.", 0, ' ','".date("Y-m-d H:i:s")."');";
							#$resInsert = DBi::$conn->query($query) or die('ER:0003: '.$query.' '.mysqli_error(DBi::$conn));
							#$iModulID = mysqli_insert_id(DBi::$conn);
							
							#$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID_shopste.", ".$iModulID.", 'menue_shopcategory', 'col-left', '0');";
							
							#$resInsert = DBi::$conn->query($query) or die('ER:0004'.mysqli_error(DBi::$conn));

							// Modul Einstellugen Speichern
							$query = "INSERT INTO `modul_portal_shop_item_detail` (`title_de`, `menue_id`, `last_usr`,shop_item_id) VALUES ('".$_POST['shop_page_titel']."', ".$iPageID_shopste.", 0,'".$shop_item_id."');";
							$resInsert = DBi::$conn->query($query) or die('0003:'.mysqli_error(DBi::$conn));
							$iModulID = mysqli_insert_id(DBi::$conn);
						
							// Modul auf einer Seite bekannt machen
							$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID_shopste.", ".$iModulID.", 'portal_shop_item_detail', 'col-main', '0');";
							$resInsert = DBi::$conn->query($query) or die('0004:'.mysqli_error(DBi::$conn));
							
							# In Marktplatz einstellen nur wenn Shopste.com angelegt wurde
							#echo $iPageID_shopste;
							if($iPageID_shopste != '') {
								
								$query = "UPDATE shop_item SET shopste_marktplatz_cat='".$_POST['shopste_cat']."',shopste_marktplatz_menue_id='".$iPageID_shopste."' WHERE shop_item_id='".$shop_item_id."'";
								DBi::$conn->query($query) or die('ER:0005'.mysqli_error(DBi::$conn));

																
								$query ="SELECT count(*) as anzahl FROM shop_item_category2items WHERE shopste_item='".$shop_item_id."' AND shopste_item_cat='".$_POST['shopste_cat']."'";
								$resModMenue = DBi::$conn->query($query) or die('ERR:008-0:'.$query.mysqli_error(DBi::$conn));
								$strCount= mysqli_fetch_assoc($resModMenue);
								
								if($strCount['anzahl'] == 0) {
									$query = "INSERT INTO `shop_item_category2items`(shopste_item_cat,shopste_item) VALUES('".$_POST['shopste_cat']."','".$shop_item_id."')";
									#echo $query;
									$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 							
								}								 
							}
						}
						//
						// SUBSHOP SEITE + MODULE anlegen
						// 
						$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id,created_at,template_file) VALUES ('".$_POST['shop_item_name']."', '".$_POST['shop_item_name']."', '0', 'produktunsichtbar', 'col2-left-layout','".$_POST['domain_id']."','".date("Y-m-d H:i:s")."','index.tpl')";
						#echo $query;
						$resInsert = DBi::$conn->query($query) or die('ER:0006'.mysqli_error(DBi::$conn));
						$iPageID_subshop = mysqli_insert_id(DBi::$conn);
						#echo "INS:".$iPageID_subshop;
						
						# Die Seiten ID der Kategorie ermitteln
					 							
							$query ="SELECT * FROM shop_category WHERE shop_cat_id = '".$_POST['subshop_cat']."'";
							#echo $query;
							$resCatTbl = DBi::$conn->query($query) or die('ER:0007'.mysqli_error(DBi::$conn));
							$strMenueTbl = mysqli_fetch_assoc($resCatTbl);
					# print_r($strMenueTbl);
						#$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID.", ".$_POST['page_menue_id'].");";
						# Wird automatisch in dem "Pfad" des Menü gespeichert (übergeordnet ist die Kategorie id)
						$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID_subshop.", ".$strMenueTbl['page_id'].");";
						#echo $query;
						$resInsert = DBi::$conn->query($query) or die('ER:0008'.$query.mysqli_error(DBi::$conn));
						 
							
						$query = "INSERT INTO `modul_menue_shopcategory` (`title_de`, `menue_id`, `last_usr`,content_de,created_at) VALUES ('Shop Themenwelten', ".$iPageID_subshop.", 0, ' ','".date("Y-m-d H:i:s")."');";
						$resInsert = DBi::$conn->query($query) or die('ER:0009: '.$query.' '.mysqli_error(DBi::$conn));
						$iModulID = mysqli_insert_id(DBi::$conn);
						
						$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID_subshop.", ".$iModulID.", 'menue_shopcategory', 'col-left', '0');";
						
						$resInsert = DBi::$conn->query($query) or die('ER:0010'.mysqli_error(DBi::$conn));
						

						
						#$query = "INSERT INTO `shop_item` (`name_de`, `preis`, `shop_cat_id`, `menue_id`,menge,beschreibung,gewicht,item_number,domain_id,status_de,system_closed_shop,item_enabled,item_dauer,item_mwst,created_at) VALUES ('".html_entity_decode($_POST['shop_item_name'])."', '".$_POST['shop_item_price']."', '".$_POST['shopste_cat']."', '".$iPageID."', '".$_POST['shop_item_menge']."', '".$_POST['shop_item_beschreibung']."', '0','".$_POST['shop_item_artikelnummer']."','".$_POST['domain_id']."','verkaufsbereit','N','Y','".$_POST['shop_item_duration']."','".$_POST['shop_item_mwst']."','".date("Y-m-d H:i:s")."');";
						
						#mail("jbludau@bludau-media.de","Datei hochgeladen ".$_POST['shop_item_name'],$_POST['shop_item_beschreibung']);
						#$resInsert = DBi::$conn->query($query) or die('ER:0005'.mysqli_error(DBi::$conn)); 
						#$shop_item_id = mysqli_insert_id(DBi::$conn);				
						
						// Modul Einstellugen Speichern
						$query = "INSERT INTO `modul_shop_item_detail` (`title_de`, `menue_id`, `last_usr`,shop_item_id) VALUES ('".$_POST['shop_item_name']."', ".$iPageID_subshop.", 0,'".$shop_item_id."');";
						$resInsert = DBi::$conn->query($query) or die('ER:0006'.mysqli_error(DBi::$conn));
						$iModulID = mysqli_insert_id(DBi::$conn);
					
						// Modul auf einer Seite bekannt machen
						$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID_subshop.", ".$iModulID.", 'shop_item_detail', 'col-main', '0');";
						$resInsert = DBi::$conn->query($query) or die('ER:0007:'.mysqli_error(DBi::$conn));
						#echo "ID-".$iPageID_subshop;
						if($iPageID_subshop != '') {
							if($_POST['submodus'] == 'jtl2shopste') {
								echo "--".$strJTLShopsteKategorie;
								$query = "UPDATE shop_item SET shop_cat_id='".$strJTLShopsteKategorie."',menue_id='".$iPageID_subshop."' WHERE shop_item_id='".$shop_item_id."'";
								DBi::$conn->query($query) or die('ER:0005'.mysqli_error(DBi::$conn));
								
							} else {
								
								$query = "UPDATE shop_item SET shop_cat_id='".$_POST['subshop_cat']."',menue_id='".$iPageID_subshop."' WHERE shop_item_id='".$shop_item_id."'";
								DBi::$conn->query($query) or die('ER:0005'.mysqli_error(DBi::$conn));
							}
						}
						
						echo '>> shopid:'.$shop_item_id;
						$_SESSION['acp_imported_ids'] .= $_SESSION['acp_imported_ids']."|".$shop_item_id."|";
						
					} else {
						######################################
						# Shopste Importer Eintragung 
						######################################
						
						if(empty($_POST['gewicht'])) {
							$_POST['gewicht'] = "0.0";
						}		
						
						if(empty($_POST['versandkosten'])) {
							$_POST['versandkosten'] = "0.0";
						}
						
						if(empty($_POST['lieferzeit'])) {
							$_POST['lieferzeit'] = "2-3 Tage";
						}
						
						if($_POST['submodus'] == 'jtl2shopste') {
						
						#echo " SHOP-ID:".$shop_item_id;
						$query = "INSERT INTO `shop_item` (`name_de`, `preis`, `shop_cat_id`, `menue_id`,menge,beschreibung,gewicht,item_number,domain_id,status_de,system_closed_shop,item_enabled,item_dauer,item_mwst,ean,lieferzeit,created_at,versandkosten) VALUES ('".html_entity_decode($_POST['shop_item_name'])."', '".$_POST['shop_item_price']."', '0', '0', '".$_POST['shop_item_menge']."', '".html_entity_decode($_POST['shop_item_beschreibung'])."', '".$_POST['gewicht']."','".$_POST['shop_item_artikelnummer']."','".$_POST['domain_id']."','verkaufsbereit','N','Y','".$_POST['shop_item_duration']."','".$_POST['shop_item_mwst']."','".$_POST['ean']."','".$_POST['lieferzeit']."','".date("Y-m-d H:i:s")."','".$_POST['versandkosten']."');";
						
						#mail("jbludau@bludau-media.de","Datei hochgeladen ".$_POST['shop_item_name'],$_POST['shop_item_beschreibung']);
						$resInsert = DBi::$conn->query($query) or die('ER:0055'.mysqli_error(DBi::$conn)); 
						$shop_item_id = mysqli_insert_id(DBi::$conn);
						
						
						$query ="SELECT count(*) as anzahl FROM shop_item_category2items WHERE shopste_item='".$shop_item_id."' AND shopste_item_cat='".$_POST['subshop_cat']."'";
						$resModMenue = DBi::$conn->query($query) or die('ERR:008-1:'.$query.mysqli_error(DBi::$conn));
						$strCount= mysqli_fetch_assoc($resModMenue);
						
						if($strCount['anzahl'] == 0) {
							$query = "INSERT INTO `shop_item_category2items`(shopste_item_cat,shopste_item) VALUES('".$_POST['subshop_cat']."','".$shop_item_id."')";
							#echo $query;
							$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 							
						}		
						  
						
						//
						// SUBSHOP SEITE + MODULE anlegen
						//
						$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id,created_at,template_file) VALUES ('".$_POST['shop_item_name']."', '".$_POST['shop_item_name']."', '0', 'produktunsichtbar', 'col2-left-layout','".$_POST['domain_id']."','".date("Y-m-d H:i:s")."','index.tpl')";
						#echo $query;
						$resInsert = DBi::$conn->query($query) or die('ER:0006'.mysqli_error(DBi::$conn));
						$iPageID_subshop = mysqli_insert_id(DBi::$conn);
						#echo "INS:".$iPageID_subshop;
						
							if(!empty($_POST['subshop_cat'])) {
								// Shop Kategorie aus jtl_shop_catid holen
								$strQuery = "SELECT * FROM shop_category WHERE shop_cat_id = '".$_POST['subshop_cat']."'";
								$res = DBi::$conn->query($strQuery);
								$strData = mysqli_fetch_assoc($res);
								#print_r($strData); 
								$strJTLShopsteKategorie = $strData['shop_cat_id'];
							#echo "IN008  - ".$strJTLShopsteKategorie;
							}  
							
						# Die Seiten ID der Kategorie ermitteln
					 							
							$query ="SELECT * FROM shop_category WHERE shop_cat_id = '".$_POST['subshop_cat']."'";
							#echo $query;
							$resCatTbl = DBi::$conn->query($query) or die('ER:0007'.mysqli_error(DBi::$conn));
							$strMenueTbl = mysqli_fetch_assoc($resCatTbl);
					  
						#$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID.", ".$_POST['page_menue_id'].");";
						# Wird automatisch in dem "Pfad" des Menü gespeichert (übergeordnet ist die Kategorie id)
						$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID_subshop.", ".$strMenueTbl['page_id'].");";
						#echo $query;
						$resInsert = DBi::$conn->query($query) or die('ER:0008'.$query.mysqli_error(DBi::$conn));
						 
							
						$query = "INSERT INTO `modul_menue_shopcategory` (`title_de`, `menue_id`, `last_usr`,content_de,created_at) VALUES ('Shop Themenwelten', ".$iPageID_subshop.", 0, ' ','".date("Y-m-d H:i:s")."');";
						$resInsert = DBi::$conn->query($query) or die('ER:0009: '.$query.' '.mysqli_error(DBi::$conn));
						$iModulID = mysqli_insert_id(DBi::$conn);
						
						$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID_subshop.", ".$iModulID.", 'menue_shopcategory', 'col-left', '0');";
						
						$resInsert = DBi::$conn->query($query) or die('ER:0010'.mysqli_error(DBi::$conn));
						

						
						#$query = "INSERT INTO `shop_item` (`name_de`, `preis`, `shop_cat_id`, `menue_id`,menge,beschreibung,gewicht,item_number,domain_id,status_de,system_closed_shop,item_enabled,item_dauer,item_mwst,created_at) VALUES ('".html_entity_decode($_POST['shop_item_name'])."', '".$_POST['shop_item_price']."', '".$_POST['shopste_cat']."', '".$iPageID."', '".$_POST['shop_item_menge']."', '".$_POST['shop_item_beschreibung']."', '0','".$_POST['shop_item_artikelnummer']."','".$_POST['domain_id']."','verkaufsbereit','N','Y','".$_POST['shop_item_duration']."','".$_POST['shop_item_mwst']."','".date("Y-m-d H:i:s")."');";
						
						#mail("jbludau@bludau-media.de","Datei hochgeladen ".$_POST['shop_item_name'],$_POST['shop_item_beschreibung']);
						#$resInsert = DBi::$conn->query($query) or die('ER:0005'.mysqli_error(DBi::$conn)); 
						#$shop_item_id = mysqli_insert_id(DBi::$conn);				
						
						// Modul Einstellugen Speichern
						$query = "INSERT INTO `modul_shop_item_detail` (`title_de`, `menue_id`, `last_usr`,shop_item_id) VALUES ('".$_POST['shop_item_name']."', ".$iPageID_subshop.", 0,'".$shop_item_id."');";
						$resInsert = DBi::$conn->query($query) or die('ER:0006'.mysqli_error(DBi::$conn));
						$iModulID = mysqli_insert_id(DBi::$conn);
					
						// Modul auf einer Seite bekannt machen
						$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID_subshop.", ".$iModulID.", 'shop_item_detail', 'col-main', '0');";
						$resInsert = DBi::$conn->query($query) or die('ER:0007:'.mysqli_error(DBi::$conn));
						
						#echo "iPageID_subshop ".$iPageID_subshop;
						
						
						if($iPageID_subshop != '') {
							if($_POST['submodus'] == 'jtl2shopste') {
								$query = "UPDATE shop_item SET shop_cat_id='".$strJTLShopsteKategorie."',menue_id='".$iPageID_subshop."' WHERE shop_item_id='".$shop_item_id."'";
								DBi::$conn->query($query) or die('ER:0005'.mysqli_error(DBi::$conn));
								
							} else {
								
								$query = "UPDATE shop_item SET shop_cat_id='".$_POST['subshop_cat']."',menue_id='".$iPageID_subshop."' WHERE shop_item_id='".$shop_item_id."'";
								DBi::$conn->query($query) or die('ER:0005'.mysqli_error(DBi::$conn));
							}
						}
						
						echo 'shopid:'.$shop_item_id;
						$_SESSION['acp_imported_ids'] .= $_SESSION['acp_imported_ids']."|".$shop_item_id."|";
						
						} else {
							$query = "INSERT INTO `shop_item` (`name_de`, `preis`, `shop_cat_id`, `menue_id`,menge,beschreibung,gewicht,item_number,domain_id,status_de,system_closed_shop,item_enabled,item_dauer,item_mwst,ean,lieferzeit,created_at) VALUES ('".html_entity_decode($_POST['shop_item_name'])."', '".$_POST['shop_item_price']."', '0', '0', '".$_POST['shop_item_menge']."', '".$_POST['shop_item_beschreibung']."', '".$_POST['gewicht']."','".$_POST['shop_item_artikelnummer']."','".$_POST['domain_id']."','API-importiert','N','N','".$_POST['shop_item_duration']."','".$_POST['shop_item_mwst']."','".$_POST['ean']."','".$_POST['lieferzeit']."','".date("Y-m-d H:i:s")."');";
							
							#mail("jbludau@bludau-media.de","Datei hochgeladen ".$_POST['shop_item_name'],$_POST['shop_item_beschreibung']);
							$resInsert = DBi::$conn->query($query) or die('ER:1'.mysqli_error(DBi::$conn)); 
							$shop_item_id = mysqli_insert_id(DBi::$conn);
							
							echo 'shopid:'.$shop_item_id;
							$_SESSION['acp_imported_ids'] .= $_SESSION['acp_imported_ids']."|".$shop_item_id."|";
						}
					}
				}

				
				
				
				if($_POST['bLastItem'] == "true") {
					$query = "SELECT * FROM shop_item JOIN shop_info ON shop_item.domain_id = shop_info.domain_id WHERE shop_item.shop_item_id='".$shop_item_id."'";
					$resShopInfo = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$ShopInfoData = mysqli_fetch_assoc($resShopInfo);
					
					$strIds = explode("|",$_SESSION['acp_imported_ids']);
					
					for($i=0; $i < count($strIds); $i++) {
						$query = "SELECT * FROM shop_item WHERE shop_item_id='".$strIds[$i]."'";
						$resShopItem1 = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
						$strShopData = mysqli_fetch_assoc($resShopItem1);
						
						$strContent .= html_entity_decode($strShopData['name_de']).' f&uuml;r Preis: '.$strShopData['preis'].' EUR<br/>';
					}
					
					// Email verschicken 
					
					$query = "SELECT * FROM domains WHERE domain_id='".$_POST['domain_id']."'";
					$resDomain = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$strDomain = mysqli_fetch_assoc($resDomain);
					
					$html = "Hallo, <br/>
				Sie erhalten diese Email, weil ein Artikel importiert wurde.<br/><br/>";
					$html .= $strContent;
					$html .= "<br/><br/>Sie m&uuml;ssen sich einloggen und die Bearbeitung fortsetzen (Kategorie-Zuordnung) zum den Artikel letztendlich zu aktivieren.<br/>";
					$html .= '<a href="https://shopste.com">Shopste.com</a> | <a href="http://'.$strDomain['name'].'/admin/">Ihr Administrationsbereich</a>';
					
					//Create a new PHPMailer instance
					$mail = new PHPMailer();
					// Set PHPMailer to use the sendmail transport
					$mail->isSendmail();
					$mail->setFrom("info@shopste.com","Shopste Service");
					//Set an alternative reply-to address
					$mail->addReplyTo("info@shopste.com","Shopste Service");
					//Set who the message is to be sent to
					$mail->addAddress($ShopInfoData['email_shop_main'],$ShopInfoData['email_shop_main_form_name']);
					
					//Set who the message is to be sent to
					$mail->AddBCC(CORE_MAIL_SEND_API_BCC,CORE_MAIL_SEND_API_BCC_NAME);
					//Set the subject line
					$mail->Subject = utf8_decode('Shopste Importer Bericht für '.$_POST['user']);
					//Read an HTML message body from an external file, convert referenced images to embedded,
					//convert HTML into a basic plain-text alternative body
					$mail->msgHTML($html, dirname(__FILE__));
					//Replace the plain text body with one created manually
					$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';
					//Attach an image file
					//$mail->addAttachment('images/phpmailer_mini.png');

					//$_SESSION['acp_imported_ids'] = '';
					//send the message, check for errors
					if (!$mail->send()) {
						echo "Mailer Error: " . $mail->ErrorInfo;
					} else {
						#echo "Email geschickt!";
					}	
				} else {
					$_SESSION['acp_imported_ids'] .= $shop_item_id.'|';
				}
			}
				#echo "shopid:".$shop_item_id.'|'.$_SESSION['acp_imported_ids'];
			break;
		case 'getSyncStatus_save':
			$query = "UPDATE shop_item SET menge='".$_GET['menge']."',preis='".str_replace(",",".",$_GET['price'])."' WHERE item_number = '".$_GET['item_number']."' AND domain_id='".$_GET['domain_id']."'";
 
			$resShopInfo = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 
			echo "OK".$_GET['price'];
			break;
		case 'getSyncStatus':
			$query = "SELECT * FROM shop_item WHERE domain_id='".$_GET['domain_id']."'";

			$resShopInfo = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 
			while($ShopInfoData = mysqli_fetch_assoc($resShopInfo)) {
				echo $ShopInfoData['shop_item_id'].';'.$ShopInfoData['menge'].';'.$ShopInfoData['name_de'].';'.$ShopInfoData['item_number']."\n";
			} 
	
			break;
		case 'register_add_subdomain':
			
			$_POST['reg_domain_name'] = str_replace(" ","-",$_POST['reg_domain_name']);
			$_POST['reg_domain_name'] = str_replace("\\","",$_POST['reg_domain_name']);
			$_POST['reg_domain_name'] = str_replace("/","",$_POST['reg_domain_name']);
			$_POST['reg_domain_name'] = str_replace("ä","ae",$_POST['reg_domain_name']);
			$_POST['reg_domain_name'] = str_replace("Ä","Ae",$_POST['reg_domain_name']);
			$_POST['reg_domain_name'] = str_replace("ü","ue",$_POST['reg_domain_name']);
			$_POST['reg_domain_name'] = str_replace("Ü","Ue",$_POST['reg_domain_name']);
			$_POST['reg_domain_name'] = str_replace("ö","oe",$_POST['reg_domain_name']);
			$_POST['reg_domain_name'] = str_replace("Ö","Oe",$_POST['reg_domain_name']);
			$_POST['reg_domain_name'] = str_replace("ß","ss",$_POST['reg_domain_name']);
			
			$query = "SELECT count(*) as anzahl FROM domains WHERE name='".$_POST['reg_domain_name'].".shopste.com'";
			$resShopItem = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strShopItem = mysqli_fetch_assoc($resShopItem);
			
			if($strShopItem['anzahl'] > 0) {
				echo 'Domain bereits belegt';
			} else {
				echo 'Domain ist frei';
			}
			break;
		case 'register_add_benutzer':
			$query = "SELECT count(*) as anzahl FROM benutzer WHERE username='".$_POST['reg_username_name']."'";
			$resShopItem = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strShopItem = mysqli_fetch_assoc($resShopItem);
			
			if($strShopItem['anzahl'] > 0) {
				echo 'Benutzername bereits belegt';
			} else {
				echo 'Benutzername ist frei';
			}
			break;
		case 'system_category_add':
		
					#echo "IN...";
				if(isset($_POST['shop_cat_title'])) {
					
				switch($_POST['page_layout']) {			
					case 'col2-left-layout':
						$strModuleColum = 'col-left';
						break;
					case 'col2-right-layout':
						$strModuleColum = 'col-right';
						break;
					case 'col3-layout':
						$strModuleColum = 'col-left';
						break;
				}
				
				switch($_POST['subtyp']) {
				
					case 'new':
						$_POST['shop_page_titel'] = $_POST['shop_cat_title'];
						$_POST['page_url_name'] = $_POST['shop_cat_title'];
	 
						// Page Einstellugen Speichern
						$query = "INSERT INTO `shop_category` (`name_de`,created_at,sortierung,domain_id) VALUES ('".$_POST['shop_cat_title']."','".date("Y-m-d H:i:s")."','".$_POST['shop_cat_position']."','".$_POST['domain_id']."');";
						$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
						$iPageID = mysqli_insert_id(DBi::$conn);
						$icat = $iPageID;
						$_SESSION['system_shop_last_cat'] = $_POST['shop_cat_id'];
						
						$query = "INSERT INTO `shop_category_parent` (`shop_cat_id`, `shop_cat_parent`) VALUES (".$iPageID.", ".$_POST['shop_cat_id'].");";
						$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
						
						// Page Einstellugen Speichern
						$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id,content_type) VALUES ('".$_POST['page_url_name']."', '".$_POST['shop_page_titel']."', '".$_POST['shop_page_sort']."', 'sichtbar', '".$_POST['page_layout']."','".$_POST['domain_id']."','kategorie_seite');";
						$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
						$iPageID = mysqli_insert_id(DBi::$conn);
						
						$query = "UPDATE shop_category SET page_id='".$iPageID."' WHERE shop_cat_id='".$icat."'";
						DBi::$conn->query($query);
						
						$query ="SELECT * FROM shop_category WHERE shop_cat_id='".$_POST['shop_cat_id']."'";
						$resCategory = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
						$strCatAry = mysqli_fetch_assoc($resCategory);
						$_POST['page_menue_id'] = $strCatAry['page_id'];
						
						$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID.", ".$_POST['page_menue_id'].");";
						$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
						
				 
						
						// Modul Einstellugen Speichern
						$query = "INSERT INTO `modul_shop_cat_list` (`title_de`, `menue_id`, `last_usr`,shop_cat_id) VALUES ('".$_POST['shop_page_titel']."', ".$iPageID.", 0,'".$icat."');";
						$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
						$iModulID = mysqli_insert_id(DBi::$conn);
					
						// Modul auf einer Seite bekannt machen
						$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'shop_cat_list', 'col-main', '".$_POST['module_position']."');";
						$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
						
						// Modul Einstellugen Speichern
						$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('".$_POST['shop_page_titel']."', ".$_POST['page_menue_id'].", 0, 'submenue', 'Y');";
						$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
						$iModulID = mysqli_insert_id(DBi::$conn);
					
						// Modul auf einer Seite bekannt machen
						$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'menue', '".$strModuleColum."', '".$_POST['module_position']."');";
				
						$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
						
						$path = getPathUrl($_SESSION['language'],$iPageID);			
						$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
						@mail("info@shopste.com","Kategorie angelegt: '".$_POST['shop_cat_title']."'","");
						echo $strLink;
						exit;
						break;
					case 'rename':
						$_POST['shop_page_titel'] = $_POST['shop_cat_title'];
						$_POST['page_url_name'] = $_POST['shop_cat_title'];
	 
						
						$query = "SELECT * FROM shop_category WHERE shop_cat_id='".$_POST['shop_cat_id']."'";
						$resShopCat = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
						$strCatAry = mysqli_fetch_assoc($resShopCat);
						
						
						// Page Einstellugen Speichern
						$query = "UPDATE `menue` SET name_de='".$_POST['page_url_name']."', `titel_de`='".$_POST['shop_page_titel']."' WHERE id='".$strCatAry['page_id']."'";
						$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
						$iPageID = mysqli_insert_id(DBi::$conn);
						
						$query = "UPDATE shop_category SET name_de='".$_POST['shop_cat_title']."' WHERE shop_cat_id='".$_POST['shop_cat_id']."'";
						DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
 
						
						$path = getPathUrl($_SESSION['language'],$iPageID);			
						$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
						@mail("info@shopste.com","Kategorie angelegt: '".$_POST['shop_cat_title']."'","");
						echo $strLink;
						exit;
						break;
						
					break;
				}
				
				$path = getPathUrl($_SESSION['language'],$iPageID);			
				$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
				
				// Email verschicken 

				$html = 'Shopste Systemnachricht, <br/>
				Neue Kategorie angelegt.<br/>
				<br/>
				<strong>Seite: </strong><a href="'.$strLink.'">'.$_POST['page_url_name'].'</a><br/>';
				
				//Create a new PHPMailer instance
				$mail = new PHPMailer();
				// Set PHPMailer to use the sendmail transport
				$mail->isSendmail();
				//Set who the message is to be sent from
				$mail->setFrom('info@shopste.com', 'Shopste Service');
				//Set an alternative reply-to address
				$mail->addReplyTo('info@shopste.com', 'Shopste Service');
				//Set who the message is to be sent to
				$mail->AddBCC(CORE_MAIL_SEND_API_BCC,CORE_MAIL_SEND_API_BCC_NAME);
				#$mail->AddBCC();
				//Set the subject line
				if($_POST['modus'] == 'new') {
					$mail->Subject = 'Shopste Portal Neue Kategorie  '.$_POST['page_url_name'];
				} else {
					$mail->Subject = 'Shopste Portal Update Kategorie '.$_POST['page_url_name'];
				}
				//Read an HTML message body from an external file, convert referenced images to embedded,
				//convert HTML into a basic plain-text alternative body
				$mail->msgHTML(utf8_decode($html), dirname(__FILE__));
				//Replace the plain text body with one created manually
				$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';
				//Attach an image file
				//$mail->addAttachment('images/phpmailer_mini.png');

				//send the message, check for errors
				if (!$mail->send()) {
					#echo "Mailer Error: " . $mail->ErrorInfo;
				} else {
					#echo "Message sent!";
				}			
				
			}
			break;
		case 'gen_user_pwd':
				$options = [
					'cost' => 11,
					'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)
				];				
				$hash =  password_hash($_POST['txtRegPasswort'], PASSWORD_BCRYPT, $options);
				echo $hash;
			break;
case 'register_send_benutzer':
		
			$_SESSION['order_vorname'] = $_POST['txtVorname'];
			$_SESSION['order_nachname'] = $_POST['txtNachname'];
			$_SESSION['order_strasse'] = $_POST['txtStrasse'];
			$_SESSION['order_plz'] = $_POST['txtPLZ'];
			$_SESSION['order_ort'] = $_POST['txtOrt'];
			$_SESSION['order_land'] = $_POST['txtLand'];
			$_SESSION['order_email'] = $_POST['txtEmail'];
			$_SESSION['order_firma'] = $_POST['txtFirma'];
			$_SESSION['order_telefon'] = $_POST['txtTelefon'];
			$_SESSION['order_shop_shopname'] = $_POST['txtRegShopname'];
			$_SESSION['order_shop_gewerblich'] = $_POST['optGewerbeArt'];
			$_SESSION['order_shop_rueckruf'] = $_POST['optTelefonRückruf'];
			$_SESSION['order_shop_teamviewer'] = $_POST['optTeamviewerSupport'];
			
			$_POST['txtRegDomainName'] = str_replace(" ","-",$_POST['txtRegDomainName']);
			$_POST['txtRegDomainName'] = str_replace("ä","ae",$_POST['txtRegDomainName']);
			$_POST['txtRegDomainName'] = str_replace("Ä","Ae",$_POST['txtRegDomainName']);
			$_POST['txtRegDomainName'] = str_replace("ü","ue",$_POST['txtRegDomainName']);
			$_POST['txtRegDomainName'] = str_replace("Ü","Ue",$_POST['txtRegDomainName']);
			$_POST['txtRegDomainName'] = str_replace("ö","oe",$_POST['txtRegDomainName']);
			$_POST['txtRegDomainName'] = str_replace("Ö","Oe",$_POST['txtRegDomainName']);
			$_POST['txtRegDomainName'] = str_replace("ß","ss",$_POST['txtRegDomainName']);
			$_POST['txtRegDomainName'] = str_replace("\\","",$_POST['txtRegDomainName']);
			$_POST['txtRegDomainName'] = str_replace("/","",$_POST['txtRegDomainName']);
			
			
			
			$query = "SELECT count(*) as anzahl FROM domains WHERE name='".$_POST['txtRegDomainName'].".".CORE_API_DOMAIN."'";
			$resShopItem = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strShopItem = mysqli_fetch_assoc($resShopItem);
			
			if($strShopItem['anzahl'] > 0) {
				$strCheckDomain =  'REGISTERIEREN_VERBOTEN';
			} else {
				$strCheckDomain = 'REGISTERIEREN_ERLAUBT';
			}
			
			$query = "SELECT count(*) as anzahl FROM benutzer WHERE username='".$_POST['txtRegUsername']."'";
			$resShopItem2 = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strShopItem2 = mysqli_fetch_assoc($resShopItem2);
			
			if($strShopItem2['anzahl'] > 0) {
				$strCheckBenutzer = 'REGISTERIEREN_VERBOTEN';
			} else {
				$strCheckBenutzer = 'REGISTERIEREN_ERLAUBT';
			} 
			
			echo $strCheckBenutzer.'-'.$strCheckDomain;
			
			
			if(($strCheckBenutzer == 'REGISTERIEREN_ERLAUBT') AND ($strCheckDomain == 'REGISTERIEREN_ERLAUBT')) {
				
				$_POST['txtRegDomainName'] = strtolower($_POST['txtRegDomainName']);
				
				$query = "INSERT INTO domains(name,startseite) VALUES('".strtolower($_POST['txtRegDomainName']).".".CORE_API_DOMAIN."','1')";
				DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$domain_id = mysqli_insert_id(DBi::$conn);
				
				$strCRC = crc32(date("Y-m-d H:i:s"));
				
				// Benutzerdaten speichern
				//$strPasswort = md5($_POST['txtRegPasswort']);
				
			/* 	$options = [
					'cost' => 11,
					'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
				];
				$hash =  password_hash($_POST['txtRegPasswort'], PASSWORD_BCRYPT, $options);
			 */	
				
				$hash = encrypt_decrypt('encrypt', $_POST['txtRegPasswort']);
				
				
				$query = "INSERT INTO benutzer(username,password,erstellt_am,domain_id,email,firma,vorname,nachname,strasse_hnr,plz,stadt,land,profile_id,telefon,email_crc,bISBlowfish) VALUES('".$_POST['txtRegUsername']."','".$hash."','".date("Y-m-d H:i:s")."','".$domain_id."','".$_POST['txtEmail']."','".$_POST['txtFirma']."','".$_POST['txtVorname']."','".$_POST['txtNachname']."','".$_POST['txtStrasse']."','".$_POST['txtPLZ']."','".$_POST['txtOrt']."','".$_POST['txtLand']."','1','".$_POST['txtTelefon']."','".$strCRC."','Y')";
				DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$iUserID = mysqli_insert_id(DBi::$conn);
				
				// Google ID speichern
				if(isset($_POST['txtGooglePlus'])) {
						$query ="UPDATE benutzer  SET googleid='".$_POST['txtGooglePlus']."' WHERE id='".$iUserID."'";
						DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				}
				
				
				// Shop Info anlegen 
				if($_POST['optGewerbeArt'] == 'MwSt_privatverkauf') {
					$strGewerbe = 'N';
				} else {
					$strGewerbe = 'Y';
				}
				
				if(empty($_POST['optTelefonRückruf'])) {					
					$_POST['optTelefonRückruf'] = 'N';
				}
				
				if(empty($_POST['optTeamviewerSupport'])) {					
					$_POST['optTeamviewerSupport'] = 'N';
				}				
				
				
				$query = "INSERT INTO shop_info(firma,vorname,nachname,strasse_hnr,plz,stadt,land,domain_id,shop_name,email_shop_main,created_at,telefon,gewerblich,telefon_rueckruf,teamviewer_support,shop_mitgliedsname) VALUES('".$_POST['txtFirma']."','".$_POST['txtVorname']."','".$_POST['txtNachname']."','".$_POST['txtStrasse']."','".$_POST['txtPLZ']."','".$_POST['txtOrt']."','".$_POST['txtLand']."','".$domain_id."','".$_POST['txtRegShopname']."','".$_POST['txtEmail']."','".date("Y-m-d H:i:s")."','".$_POST['txtTelefon']."','".$strGewerbe."','".$_POST['optTelefonRückruf']."','".$_POST['optTeamviewerSupport']."','".$_POST['txtRegUsername']."')";
				#echo $query;
				DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				
				
				
				# Email vorlage laden
				$query ="SELECT count(*) as anzahl FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_REGISTRIERUNG'";
				$resEmailCount = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$strEmailCount = mysqli_fetch_assoc($resEmailCount);
				if($strEmailCount['anzahl'] > 0) {
					# Lade Benutzervorlage
					$query ="SELECT * FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_REGISTRIERUNG'";
					#echo $query;
					$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
				} else {
					# Lade Defaultvorlage
					$query ="SELECT * FROM email_vorlage WHERE domain_id='0' AND standard='Y' AND typ='CORE_REGISTRIERUNG'";
					#echo $query;
					$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
				}
				 
				if($_POST['optTelefonRückruf'] == 'Y') {
					$strTelefon .= 'Ja, Anrufe folgen Wochentags in der Zeit zwischen 9:00 Uhr und 17:00 Uhr.';
				} else {
					$strTelefon .= 'Nein';				
				}
				
				if($_POST['optGewerbeArt'] == 'Y') {
					$strGewerbe .= 'Ja';
				} else {
					$strGewerbe .= 'Nein';
				}
				
				if($_POST['optTeamviewerSupport'] == 'Y') {
					$strTeamviewer .= 'Ja, <a href="http://get.teamviewer.com/bludau-media">Teamviewer Quicksupport-Download</a>.';
				} else {
					$strTeamviewer .= 'Nein';
				}				
				
				$query = "SELECT * FROM domains WHERE domain_id='".$domain_id."'";
				$resDomain = DBi::$conn->query($query)or die(mysqli_error(DBi::$conn));
				$strDomain = mysqli_fetch_assoc($resDomain);
				
				if($strDomain['bIsSSL'] == 'Y') {
					$strHTTPType = 'https://';
				} else {
					$strHTTPType = 'http://';					
				}
				
				$strEmailVorlage['content'] = str_replace('###ADMIN_VORNAME###',$_POST['txtVorname'],$strEmailVorlage['content']);
				$strEmailVorlage['content'] = str_replace('###ADMIN_NACHNAME###',$_POST['txtNachname'],$strEmailVorlage['content']);
				$strEmailVorlage['content'] = str_replace('###ADMIN_PLZ###',$_POST['txtPLZ'],$strEmailVorlage['content']);
				$strEmailVorlage['content'] = str_replace('###ADMIN_ORT###',$_POST['txtOrt'],$strEmailVorlage['content']);
				$strEmailVorlage['content'] = str_replace('###ADMIN_LAND###',$_POST['txtLand'],$strEmailVorlage['content']);

				$strEmailVorlage['content'] = str_replace('###ADMIN_USERNAME###',$_POST['txtRegUsername'],$strEmailVorlage['content']);
				$strEmailVorlage['content'] = str_replace('###ADMIN_DOMAINNAME###',$strHTTPType.$_POST['txtRegDomainName'],$strEmailVorlage['content']);
				$strEmailVorlage['content'] = str_replace('###ADMIN_CRC###',$strCRC,$strEmailVorlage['content']);
				$strEmailVorlage['content'] = str_replace('###ADMIN_DOMAINNAME_REGNAME_ONLY###',$_POST['txtRegDomainName'],$strEmailVorlage['content']);
				$strEmailVorlage['content'] = str_replace('###REG_DOMAINNAME_REGNAME_ONLY###',$_POST['txtRegDomainName'],$strEmailVorlage['content']);
				$strEmailVorlage['content'] = str_replace('###ADMIN_DOMAINNAME_REGNAME###',$strHTTPType.$_POST['txtRegDomainName'],$strEmailVorlage['content']);
				$strEmailVorlage['content'] = str_replace('###ADMIN_PASSWORT###',$_POST['txtRegPasswort'],$strEmailVorlage['content']);
				$strEmailVorlage['content'] = str_replace('###REG_GEWERBLICH###',$strGewerbe,$strEmailVorlage['content']);
				$strEmailVorlage['content'] = str_replace('###REG_RUECKRUF###',$strTelefon,$strEmailVorlage['content']);
				$strEmailVorlage['content'] = str_replace('###REG_TEAMVIEWER###',$strTeamviewer,$strEmailVorlage['content']);
				$strEmailVorlage['content'] = str_replace('###ADMIN_TELEFON###',$_POST['txtTelefon'],$strEmailVorlage['content']);
				$strEmailVorlage['content'] = str_replace('###ADMIN_EMAIL###',$_POST['txtEmail'],$strEmailVorlage['content']);
				
					
				// Email verschicken 

				
				$html = utf8_decode($html); 
				//Create a new PHPMailer instance
				$mail = new PHPMailer();
				// Set PHPMailer to use the sendmail transport
				$mail->isSendmail();
				//Set who the message is to be sent from
				$mail->setFrom(CORE_MAIL_FROM_API_REGISTER, CORE_MAIL_FROM_API_REGISTER_NAME);
				//Set an alternative reply-to address
				$mail->addReplyTo(CORE_MAIL_FROM_API_REGISTER, CORE_MAIL_FROM_API_REGISTER_NAME);
				//Set who the message is to be sent to
				$mail->addAddress($_POST['txtEmail'], utf8_decode($_POST['txtVorname'].' '.$_POST['txtNachname']));
				$mail->AddBCC(CORE_MAIL_SEND_API_BCC,CORE_MAIL_SEND_API_BCC_NAME);
				//Set the subject line
				$strEmailVorlage['betreff'] = str_replace('###ADMIN_USERNAME###',$_POST['txtRegUsername'],$strEmailVorlage['betreff']);
				$strEmailVorlage['betreff'] = str_replace('###ADMIN_DOMAINNAME_REGNAME_ONLY###',$_POST['txtRegDomainName'].'.'.CORE_API_DOMAIN,$strEmailVorlage['betreff']);
				$strEmailVorlage['betreff'] = str_replace('###REG_DOMAINNAME_REGNAME_ONLY###',$_POST['txtRegDomainName'].'.'.CORE_API_DOMAIN,$strEmailVorlage['betreff']);
				$strEmailVorlage['betreff'] = str_replace('###REG_DOMAINNAME_ONLY###',$_POST['txtRegDomainName'].'.'.CORE_API_DOMAIN,$strEmailVorlage['betreff']);
				
				$mail->Subject = utf8_decode($strEmailVorlage['betreff']);
				//Read an HTML message body from an external file, convert referenced images to embedded,
				//convert HTML into a basic plain-text alternative body
				$html = utf8_decode($strEmailVorlage['content']);
				$mail->msgHTML($html, dirname(__FILE__));
				//Replace the plain text body with one created manually
				$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';
				//Attach an image file
				//$mail->addAttachment('images/phpmailer_mini.png');

				//send the message, check for errors
				if (!$mail->send()) {
					echo "Mailer Error: " . $mail->ErrorInfo;
				} else {
					echo "Message sent!";
				}			
				
			}
			break;
	}
	mysqli_close(DBi::$conn); 
?>