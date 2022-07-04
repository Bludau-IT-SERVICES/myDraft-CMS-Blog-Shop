<?php
	session_start();
	// Datenbankverbindung
	require_once('../include/inc_config-data.php');
	require_once('../include/inc_basic-functions.php');
	
	$strButtonName = 'RSS-Quelle anlegen';
	$_POST = mysql_real_escape_array($_POST);
	$_GET = mysql_real_escape_array($_GET);
	 
	
	$query = "SELECT count(*) as anzahl FROM newsletter WHERE email = '".$_POST['modul_email_adresse']."'";
	$res = DBi::$conn->query($query) or die('ERR001:'.mysqli_error());
	$Count = mysqli_fetch_assoc($res);
	
	if($Count['anzahl'] == 0) {

		$strCRC = md5(date("Y-m-d H:i:s").'SeCa');
		
		// Page Einstellugen Speichern
		$query = "INSERT INTO `newsletter` (`email`,`email_user_name`, `crc`, `enabled`) VALUES('".$_POST['modul_email_adresse']."','".$_POST['modul_newsletter_name']."','".$strCRC."','N');";
		$resInsert = DBi::$conn->query($query) or die(mysqli_error());
		$iPageID = mysqli_insert_id(DBi::$conn);
		
		$strEmailAnmeldeLink = utf8_decode('Aktivierung: <a href="'.CORE_SERVER_DOMAIN.'newsletter.php?modus=activate&email='.$_POST['modul_email_adresse'].'&crc='.$strCRC.'">SHOPSTE NEWSLETTER AKTIVIERUNG</a>');  
		
		$recipient = $_POST['modul_email_adresse']; //empfänger
		$mail_body = utf8_decode('Bitte bestätigen Sie Ihre Newsletteranmeldung durch einen Klick auf diesen Link '.$strEmailAnmeldeLink);
		$subject = utf8_decode("Newsletter Anmeldung bestätigen bei '".CORE_SERVER_PLATTFORM_NAME."' von ".$_POST['modul_newsletter_name'].' ('.$_POST['modul_email_adresse'].')'); //betreff
		
		$headers = "From: Shopste <info@shopste.com>\r\n"; //optional headerfields 
  "X-Mailer: php\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$headers .= "Bcc: jbludau@bludau-media.de\r\n";
		
		mail($recipient,$subject,$mail_body,$headers);
		
		echo "Sie wurden angemeldet es wird eine Bestätigungsemail an Ihre Email gesendet.";		 
	} else {
		echo "Sie sind bereits angemeldet.";		 		
	}
?>