<?php 
	session_start();
	// Datenbankverbindung
	require_once('../include/inc_config-data.php');
	require_once('../include/inc_basic-functions.php');
	 
	$_POST = mysql_real_escape_array($_POST);
	$_GET = mysql_real_escape_array($_GET);

	// Login überprüfen
	$chkCookie = admin_cookie_check();

	#echo $chkCookie;
	if($_SESSION['login'] == 1) {		
		$_SESSION['login'] = 1;
	} else {
		exit(0);
	}
	
	if($_POST['modus'] == 'shop_item_page_delete') {
	
		
		#	$query = "DELETE FROM shop_item WHERE shop_item_id='".$_POST['shop_item_id']."'";
		#	DBi::$conn->query($query) or die(mysqli_error());	
		$query = "SELECT * FROM shop_item WHERE shop_item_id='".$_POST['shop_item_id']."'";
		$strShopIDData = mysqli_fetch_assoc(DBi::$conn->query($query));
		
		#$query = "SELECT count(*) as anzahl FROM menue_parent WHERE parent_id='".$strShopIDData['menue_id']."'";
		#$strParentAnzahl = mysqli_fetch_assoc(DBi::$conn->query($query));
		#if($strParentAnzahl['anzahl'] > 0) {
		#	echo "<h2>Seite NICHT gel&ouml;scht - Unterkategorie Problem</h2>
		#	<strong>Bitte löschen Sie zuerst die Unterkategorie, um eine Zerstörrung des Menüs zu verhindern.</strong><br/><br/>";
		#	exit;
		#} else {
			# MODULE IN SEITE HOLEN 
			$query = "SELECT * FROM module_in_menue WHERE menue_id='".$strShopIDData['menue_id']."'";
			$resModule = DBi::$conn->query($query) or die(mysqli_error());
			while($strModule = mysqli_fetch_assoc($resModule)) {
				# MODULE AUS EIGENER MODULTABELLE LÖSCHEN
				$query = "DELETE FROM modul_".$strModule['typ']." WHERE id='".$strModule['modul_id']."'";
				DBi::$conn->query($query) or die(mysqli_error());			
			}
 
			
			$query ="SELECT count(*) as anzahl FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_ARTIKEL_DELETE'";
			#echo $query;
			$resEmailCount = DBi::$conn->query($query) or die(mysqli_error());
			$strEmailCount = mysqli_fetch_assoc($resEmailCount);
			if($strEmailCount['anzahl'] > 0) {
				# Lade Benutzervorlage
				$query ="SELECT * FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_ARTIKEL_DELETE'";
				#echo $query;
				$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error());
				$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
			} else {
				# Lade Defaultvorlage
				$query ="SELECT * FROM email_vorlage WHERE domain_id='0' AND standard='Y' AND typ='CORE_ARTIKEL_DELETE'";
				#echo $query;
				$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error());
				$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
			}
			
		 
			$strURL_shop = getPathUrl($_SESSION['language'],$ShopInfoData['menue_id']);
			
 
			
			$query = "DELETE FROM module_in_menue WHERE menue_id='".$strShopIDData['menue_id']."'";
			#echo $query.'<br/>';
			DBi::$conn->query($query) or die(mysqli_error());		

			$query = "DELETE FROM menue_parent WHERE menue_id='".$strShopIDData['menue_id']."'";
			DBi::$conn->query($query) or die(mysqli_error());		
			#echo $query.'<br/>';
			$query = "DELETE FROM menue WHERE id='".$strShopIDData['menue_id']."'";
			DBi::$conn->query($query) or die(mysqli_error());	

			$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$strShopIDData['shop_item_id']."'";
			$resPicture = DBi::$conn->query($query) or die(mysqli_error());
			$path = realpath($_SERVER["DOCUMENT_ROOT"]);
			
			# EMAIL VORLAGE
			$html = $strEmailVorlage['content'];
			$betreff = $strEmailVorlage['betreff'];
			$strPicture = mysqli_fetch_assoc($resPicture);
			$html = str_replace("###SHOP_ARTIKEL_BILD###",'<img src="http://shopste.com/'.$strPicture['picture_url'].'"/>',$html);
			$html = str_replace("###SHOP_ARTIKEL_STATUS###",$strArtikelModus,$html);
			$html = str_replace('###SHOP_MITGLIEDSNAME###',	$strShopIDData['shop_mitgliedsname'],$html);
			$html = str_replace('###SHOP_ARTIKEL_NAME###',	$strShopIDData['name_de'],$html);
			$html = str_replace('###SHOP_ARTIKEL_MENGE###',$strShopIDData['menge'],$html);
			$html = str_replace('###SHOP_ARTIKEL_GEWICHT###',$strShopIDData['gewicht'],$html);
			$html = str_replace('###SHOP_ARTIKEL_PREIS###',number_format($strShopIDData['preis'], 2, ",", "."),$html);
			$html = str_replace('###SHOP_ARTIKEL_BESCHREIBUNG###',$strShopIDData['beschreibung'],$html);
			$html = str_replace('###SHOP_ARTIKEL_GEWICHT###',$strShopIDData['gewicht'],$html);
			$html = str_replace('###SHOP_ARTIKEL_NUMMER###',$strShopIDData['item_number'],$html);
			$html = str_replace('###SHOP_ARTIKEL_MWST###',$strShopIDData['item_mwst'],$html);
			$html = str_replace('###SHOP_ARTIKEL_LINK###','http://'.$strDomain['name'].'/'.$strURL_shop.'/',$html);
			$html = str_replace('###SHOP_ADMIN_LOGIN###','http://'.$strDomain['name'].'/admin/',$html);
			
			$betreff = str_replace("###SHOP_ARTIKEL_NAME###",$strShopIDData['name_de'],$betreff);
			$betreff = str_replace("###SHOP_ARTIKEL_NUMMER###",$strShopIDData['item_number'],$betreff);
			$betreff = str_replace("###SHOP_ARTIKEL_PREIS###",number_format($strShopIDData['preis'], 2, ",", "."),$betreff);
			
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
			DBi::$conn->query($query) or die(mysqli_error());
			#echo $query.'<br/>';
			echo "<h2>Shop Artikel und CMS Seite erfolgreich gel&ouml;scht!</h2>";
			
			$query = "DELETE FROM menue WHERE id='".$strShopIDData['menue_id']."'";
			#echo $query.'<br/>';
			DBi::$conn->query($query) or die(mysqli_error());	

			$query = "DELETE FROM menue WHERE id='".$strShopIDData['shopste_marktplatz_menue_id']."'";
			#echo $query.'<br/>';
			DBi::$conn->query($query) or die(mysqli_error());	
			
			
			# Shop Info abrufen
			$query = "SELECT * FROM shop_info WHERE domain_id='".$strShopIDData['domain_id']."'";
			$resShopInfo = DBi::$conn->query($query) or die(mysqli_error());
			$strBenutzer = mysqli_fetch_assoc($resShopInfo);
			
			# Domain Info abrufen
			$query = "SELECT * FROM domains WHERE domain_id='".$strShopIDData['domain_id']."'";
			$resShopInfo = DBi::$conn->query($query) or die(mysqli_error());
			$strDomain = mysqli_fetch_assoc($resShopInfo);
			
			
			// Email verschicken 
			$path = realpath($_SERVER["DOCUMENT_ROOT"]);
			require_once $path.'/framework/phpmailer/PHPMailerAutoload.php';
			
			#$strURL_shop = getPathUrl($_SESSION['language'],$strShopIDData['menue_id']);
 
			
			//Create a new PHPMailer instance
			$mail = new PHPMailer();
			// Set PHPMailer to use the sendmail transport
			$mail->isSendmail();
			//Set who the message is to be sent from
			$mail->setFrom(CORE_MAIL_FROM_EMAIL, CORE_MAIL_FROM_EMAIL_NAME);
			//Set an alternative reply-to address
			$mail->addReplyTo(CORE_MAIL_FROM_EMAIL, CORE_MAIL_FROM_EMAIL_NAME);
			//Set who the message is to be sent to
			$mail->addAddress($strBenutzer['email_shop_main'],utf8_decode($strBenutzer['vorname'].' '.$strBenutzer['nachname']));
			$mail->AddBCC(CORE_MAIL_SEND_BCC,CORE_MAIL_SEND_BCC_NAME);
			#$mail->AddBCC();
			//Set the subject line
			$mail->Subject = utf8_decode($betreff);
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
		#}
	}
?>