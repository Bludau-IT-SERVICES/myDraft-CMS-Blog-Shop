<?php 
	session_start();
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	$path = realpath($_SERVER["DOCUMENT_ROOT"]);
	require $path.'/framework/phpmailer/src/Exception.php';
	require $path.'/framework/phpmailer/src/PHPMailer.php';
	require $path.'/framework/phpmailer/src/SMTP.php';

	// Datenbankverbindung
	require_once('../include/inc_config-data.php');
	require_once('../include/inc_basic-functions.php');
	
	$strButtonName = 'RSS-Quelle anlegen';
	$_POST = mysql_real_escape_array($_POST);
	$_GET = mysql_real_escape_array($_GET);
 
	if(isset($_POST['module_title'])) {
		
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
		
		$query = "SELECT count(*) as anzahl FROM `modul_rss_quelle` WHERE rss_quelle = '".$_POST['module_rss_quelle']."'";
		$resSELECT = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		$strQuelleCount = mysqli_fetch_assoc($resSELECT);
		
		if($strQuelleCount['anzahl'] != 0) {
			echo "<h2>RSS-Quelle ist bereits vorhanden.<h2>
			Vielen Dank für die Teilnahme an der Community.";
			
		} else {
			
		if($_POST['modus'] == 'new') {

			// Page Einstellugen Speichern
			$query = "INSERT INTO `modul_rss_quelle` (`title_de`, `rss_quelle`, `rss_cat`) VALUES('".$_POST['module_title']."','".$_POST['module_rss_quelle']."','".$_POST['shop_cat_id']."');";
			$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		}	

		# EMAIL VORLAGE FÜR RSS POSTEN RAUSSUCHEN
		$query ="SELECT count(*) as anzahl FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_RSS_QUELLE_ADD'";
		$resEmailCount = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		$strEmailCount = mysqli_fetch_assoc($resEmailCount);
		
		if($strEmailCount['anzahl'] > 0) {
			# Lade Benutzervorlage
			$query ="SELECT * FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_RSS_QUELLE_ADD'";
			#echo $query;
			$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
		} else {
			# Lade Defaultvorlage
			$query ="SELECT * FROM email_vorlage WHERE domain_id='0' AND standard='Y' AND typ='CORE_RSS_QUELLE_ADD'";
			#echo $query;
			$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
		}
		
		$html = $strEmailVorlage['content'];
		$betreff = $strEmailVorlage['betreff'];
		
		$betreff = str_replace('###RSS_TITLE###',$_POST['module_title'],$betreff);
		$betreff = str_replace('###RSS_QUELLE###',$_POST['module_rss_quelle'],$betreff);
		if($_POST['modus'] == 'new') {
			$betreff = str_replace('###RSS_MODUS###','hinzugefügt',$betreff);
		} else {
			$betreff = str_replace('###RSS_MODUS###','aktuallisiert',$betreff);
		}
		$html = str_replace('###RSS_TITLE###',$_POST['module_title'],$html);
		$html = str_replace('###RSS_LINK###',$_POST['module_rss_quelle'],$html);
		$html = str_replace('###CORE_MAIN_PLATTFORM###',CORE_SERVER_PLATTFORM_NAME,$html);
		$html = str_replace('###RSS_QUELLE###',$_POST['module_rss_quelle'],$html);
		
	
		//Create a new PHPMailer instance
		$mail = new PHPMailer();
		// Set PHPMailer to use the sendmail transport
		$mail->isSendmail();
		//Set who the message is to be sent from
		$mail->setFrom(CORE_MAIL_FROM_RSS, CORE_MAIL_FROM_RSS_NAME);
		//Set an alternative reply-to address
		$mail->addReplyTo(CORE_MAIL_FROM_RSS, CORE_MAIL_FROM_RSS_NAME);
		//Set who the message is to be sent to
		$mail->addAddress(CORE_MAIL_FROM_RSS_BCC,CORE_MAIL_FROM_RSS_BCC_NAME);
		#$mail->AddBCC();
		
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
		
	}
	if(isset($_SESSION['page_id'])) {
		#$query = "SELECT * FROM menue_parent WHERE menue_id='".$_SESSION['page_id']."'";
		#$resParent = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		#$aryParrent = mysqli_fetch_assoc($resParent);
		#$iParrent = $aryParrent['parent_id'];
	} else {
		$iParrent = 0;
	}
#	echo "M";
	$strOptMenueSelekt = rss_category(0,0,'',0,0,'select',$_SESSION['system_shop_last_cat']);
	$strOptMenueSelekt2 = menue_generator(0,0,'',0,0,'select',$_SESSION['page_id']);
	
	#$strURL =  $_SESSION['domain_name'].'/'.getPathUrl($_SESSION['page_id'],$_SESSION['language']);
	
	#header('Location: '.$strURL);
	echo "Vielen Dank für das hinzufügen von ".$_POST['module_title']." - ".$_POST['module_rss_quelle'].".";
}

	
	
?>
<div id="acp_main_new_page_form">
	<h2>RSS-Feed Inhalt anlegen</h2>
	<form name="frmModulAdd" id="rss_content" action="/ACP/acp_rss_quelle.php" method="POST" onSubmit="return shop_save_form('rss_content');">
			<div class="label" style="float:left;">&Uuml;bergeordnete RSS-Feed Kategorie</div>
			<div id="shop_path">
				<select name="shop_cat_id" size="1">
					<?php 
					 echo $strOptMenueSelekt;
					?>
				</select>
			</div>	
			
				<div class="label" style="float:left;">
					<input type="hidden" id="acp_get_modul_name" name="optModul" value="rss_content"/>
					<input type="hidden" id="acp_get_modus" name="modus" value="new"/>
					<input type="hidden" id="acp_get_modul_id" name="module_id" value="<?php echo $_GET['id']; ?>"/>
					<?php 
					if($_GET['modus'] == 'new') {
					?>
						<input type="hidden" id="acp_get_page_id" name="page_id" value="<?php echo $_SESSION['page_id']; ?>"/>
					<?php 
					}
					?>
				</div>			
				<div style="clear:both"></div>				
				<div class="label" style="float:left;">&Uuml;berschrift</div>
				<div id="module_title">
					<input type="text" name="module_title" value="<?php echo $dataMenue['title_de']; ?>">
				</div>
				<div style="clear:both"></div>
				<div class="label" style="float:left;">RSS-Feed Quell URL</div>
				<div id="module_rss_quelle">
					<input type="text" name="module_rss_quelle" value="<?php echo $dataMenue['rss_quelle']; ?>">
				</div>
				<div style="clear:both"></div>				
				<div id="module_submit"><br/>
					<input type="submit" class="button" name="module_submit" value="<?php echo $strButtonName; ?>">
				</div>		
			</form>
</div>