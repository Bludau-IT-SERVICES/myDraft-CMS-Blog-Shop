<?php
	session_start();
#	include_once('../include/inc_basic-functions.php');	
	
	$_SESSION['login'] = '0';	
	
	$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	
	$query = "SELECT * FROM benutzer WHERE username='".$_COOKIE['admin_user']."' AND domain_id='".$domain_res['domain_id']."'";
	#echo $query;
	$resLogin = DBi::$conn->query($query) or die(mysqli_error());
	$UserData = mysqli_fetch_assoc($resLogin);
	
	$path = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once $path.'/framework/phpmailer/PHPMailerAutoload.php';

	$path = getPathUrl($_SESSION['domainLanguage'],$_COOKIE['last_page']);
	
	$query ="SELECT count(*) as anzahl FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_ADMIN_LOGIN_ABMELDUNG'";
	#echo $query;
	$resEmailCount = DBi::$conn->query($query) or die(mysqli_error());
	$strEmailCount = mysqli_fetch_assoc($resEmailCount);
	if($strEmailCount['anzahl'] > 0) {
		# Lade Benutzervorlage
		$query ="SELECT * FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_ADMIN_LOGIN_ABMELDUNG'";
		#echo $query;
		$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error());
		$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
	} else {
		# Lade Defaultvorlage
		$query ="SELECT * FROM email_vorlage WHERE domain_id='0' AND standard='Y' AND typ='CORE_ADMIN_LOGIN_ABMELDUNG'";
		#echo $query;
		$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error());
		$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
	}		


	$strEmailVorlage['content'] = str_replace('###ADMIN_USERNAME###',$UserData['username'],$strEmailVorlage['content']);
	$strEmailVorlage['content'] = str_replace('###ADMIN_LOGIN_LAST###',$UserData['updated_at'],$strEmailVorlage['content']);
	$strEmailVorlage['content'] = str_replace('###ADMIN_LOGIN_COUNT###',$UserData['login_count'],$strEmailVorlage['content']);
	$strEmailVorlage['content'] = str_replace('###ADMIN_ANGEMELDET_OK###',$strLogin,$strEmailVorlage['content']);
	$strEmailVorlage['content'] = str_replace('###ADMIN_LOGIN_IP###',$_SERVER['REMOTE_ADDR'],$strEmailVorlage['content']);
	$strEmailVorlage['content'] = str_replace('###ADMIN_HOSTNAME###',$hostname,$strEmailVorlage['content']);
	$strEmailVorlage['content'] = str_replace('###ADMIN_EMAIL###',$UserData['email'],$strEmailVorlage['content']);
	$strEmailVorlage['content'] = str_replace('###ADMIN_VORNAME###',$UserData['vorname'],$strEmailVorlage['content']);
	$strEmailVorlage['content'] = str_replace('###ADMIN_NACHNAME###',$UserData['nachname'],$strEmailVorlage['content']);
	$strEmailVorlage['content'] = str_replace('###ADMIN_PLZ###',$UserData['plz'],$strEmailVorlage['content']);
	$strEmailVorlage['content'] = str_replace('###ADMIN_ORT###',$UserData['stadt'],$strEmailVorlage['content']);
	
	if($domain_res['bIsSSL'] == 'Y') {
		$strTYP = 'https://';
	} else {
		$strTYP = 'http://';					
	}
	
	$strEmailVorlage['content'] = str_replace('###ADMIN_LOGIN_URL###',$strTYP.$_SERVER['SERVER_NAME'],$strEmailVorlage['content']);
	$strEmailVorlage['content'] = str_replace('###ADMIN_DOMAIN_NAME###',$_SERVER['SERVER_NAME'],$strEmailVorlage['content']);
	$strEmailVorlage['content'] = str_replace('###ADMIN_LOGIN_NAME###',$_SERVER['SERVER_NAME'],$strEmailVorlage['content']);
	
	$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
 
	$strEmailVorlage['betreff'] = str_replace('###ADMIN_USERNAME###',$UserData['username'],$strEmailVorlage['betreff']);
	$strEmailVorlage['betreff'] = str_replace('###ADMIN_LOGIN_COUNT###',$UserData['login_count'],$strEmailVorlage['betreff']);
	$strEmailVorlage['betreff'] = str_replace('###ADMIN_DOMAIN_NAME###',$_SERVER['SERVER_NAME'],$strEmailVorlage['betreff']);
	
	$mail = new PHPMailer();
	$mail -> charSet = "UTF-8";
	
	$mail->isSendmail();
	$mail->setFrom(CORE_MAIL_FROM_EMAIL,CORE_MAIL_FROM_EMAIL_NAME);
	
	$mail->addReplyTo(CORE_MAIL_FROM_EMAIL,CORE_MAIL_FROM_EMAIL_NAME);
	
	
	$mail->addAddress($UserData['email'],utf8_decode($UserData['vorname'].' '.$UserData['nachname']));
	
	
	$mail->AddBCC(CORE_MAIL_FROM_EMAIL,CORE_MAIL_FROM_EMAIL_NAME);
	
	$mail->Subject = utf8_decode($strEmailVorlage['betreff']);
	
	
	$mail->msgHTML(utf8_decode($strEmailVorlage['content']), dirname(__FILE__));
	
	$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';

	
	//$_SESSION['acp_imported_ids'] = '';
	//send the message, check for errors
	if (!$mail->send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Shop Abmeldung abgeschlossen!";
	}
	
	// Cookie setzten - doppelt MD5 
	$res = setcookie("admin_UID","" , time() - 2592000,"/", $_SERVER['SERVER_NAME']);
	$res = setcookie("admin_pwd","", time() - 2592000,"/", $_SERVER['SERVER_NAME']);
	$res = setcookie("admin_user","", time() - 2592000,"/", $_SERVER['SERVER_NAME']);	
	$res = setcookie("last_page","", time() - 2592000,"/", $_SERVER['SERVER_NAME']);	
	$_COOKIE['admin_UID'] = '';
	$_COOKIE['admin_pwd'] = '';
	$_COOKIE['admin_user'] = '';
	$_COOKIE['last_page'] = '';

?>