<?php 
	session_start();
	// Datenbankverbindung
	require_once('../include/inc_config-data.php');
	require_once('../include/inc_basic-functions.php');
	$strButtonName = 'RSS-Feed Kategorie Bereich anlegen';
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
		
		if($_POST['modus'] == 'new') {
			// Page Einstellugen Speichern
			$query = "INSERT INTO `modul_rss_category` (`name_de`,created_at,sortierung,domain_id) VALUES ('".$_POST['shop_cat_title']."','".date("Y-m-d H:i:s")."','".$_POST['shop_cat_position']."','".$_SESSION['domain_id']."');";
			$resInsert = DBi::$conn->query($query) or die(mysqli_error());
			$iPageID = mysqli_insert_id(DBi::$conn);
			$icat = $iPageID;
			#echo $icat;
			
			#echo $query;
			
			$_SESSION['system_shop_last_cat'] = $_POST['shop_cat_id'];
			
			if(empty($_POST['shop_cat_id'])) {
				$_POST['shop_cat_id'] = '0';
			}
			
			$query = "INSERT INTO `modul_rss_category_parent` (`news_cat_id`, `news_cat_parent`) VALUES (".$icat.", ".$_POST['shop_cat_id'].");";
			$resInsert = DBi::$conn->query($query) or die(mysqli_error());
			
			// Page Einstellugen Speichern
			$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id,content_type) VALUES ('".$_POST['page_url_name']."', '".$_POST['shop_page_titel']."', '".$_POST['shop_page_sort']."', 'sichtbar', '".$_POST['page_layout']."','".$_SESSION['domain_id']."','rss_kategorie');";
			$resInsert = DBi::$conn->query($query) or die(mysqli_error());
			$iPageID = mysqli_insert_id(DBi::$conn);
			
			$query = "UPDATE modul_rss_category SET page_id='".$iPageID."' WHERE news_cat_id='".$icat."'";
			DBi::$conn->query($query);
			
			$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID.", ".$_POST['page_menue_id'].");";
			$resInsert = DBi::$conn->query($query) or die(mysqli_error());
			
	 
			
			// Modul Einstellugen Speichern
			$query = "INSERT INTO `modul_rss_categoryview` (`title_de`, `menue_id`, `last_usr`,news_cat) VALUES ('".$_POST['shop_page_titel']."', ".$iPageID.", 0,'".$icat."');";
			$resInsert = DBi::$conn->query($query) or die(mysqli_error());
			$iModulID = mysqli_insert_id(DBi::$conn);
		
			// Modul auf einer Seite bekannt machen
			$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'rss_categoryview', 'col-main', '".$_POST['module_position']."');";
			$resInsert = DBi::$conn->query($query) or die(mysqli_error());
			
			// Modul Einstellugen Speichern
			$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('".$_POST['shop_page_titel']."', ".$_POST['page_menue_id'].", 0, 'submenue', 'Y');";
			$resInsert = DBi::$conn->query($query) or die(mysqli_error());
			$iModulID = mysqli_insert_id(DBi::$conn);
		
			// Modul auf einer Seite bekannt machen
			$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'menue', '".$strModuleColum."', '".$_POST['module_position']."');";
	
			$resInsert = DBi::$conn->query($query) or die(mysqli_error());
			
			$path = getPathUrl($_SESSION['language'],$iPageID);			
			$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
			#@mail("info@shopste.com","RSS-Feed Kategorie angelegt: '".$_POST['shop_cat_title']."'",'LINK: '.$strLink);
			echo $strLink;
			exit;
		}	
		
		$query ="SELECT count(*) as anzahl FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_RSS_ADD'";
		#echo $query;
		$resEmailCount = DBi::$conn->query($query) or die(mysqli_error());
		$strEmailCount = mysqli_fetch_assoc($resEmailCount);
		if($strEmailCount['anzahl'] > 0) {
			# Lade Benutzervorlage
			$query ="SELECT * FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_RSS_ADD'";
			#echo $query;
			$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error());
			$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
		} else {
			# Lade Defaultvorlage
			$query ="SELECT * FROM email_vorlage WHERE domain_id='0' AND standard='Y' AND typ='CORE_RSS_ADD'";
			#echo $query;
			$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error());
			$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
		}
		
		$strURL_shop = getPathUrl($_SESSION['language'],$ShopInfoData['menue_id']);
 
		#
		#$_POST['module_title']
		$html = $strEmailVorlage['content'];
		$betreff = $strEmailVorlage['betreff'];
		
		$betreff = str_replace('###RSS_CAT_TITLE###',$_POST['module_title'],$betreff);
		if($_POST['modus'] == 'new') {
			$betreff = str_replace('###RSS_MODUS###','hinzugefügt',$betreff);
		} else {
			$betreff = str_replace('###RSS_MODUS###','aktuallisiert',$betreff);
		}
		$html = str_replace('###RSS_CAT_TITLE###',$_POST['module_title'],$html);
		$html = str_replace('###RSS_LINK###',CORE_SERVER_DOMAIN.$strURL_shop,$html);
		$html = str_replace('###CORE_MAIN_PLATTFORM###',CORE_SERVER_PLATTFORM_NAME,$html);
		
		// Email verschicken 
		$path = realpath($_SERVER["DOCUMENT_ROOT"]);
		require_once $path.'/framework/phpmailer/PHPMailerAutoload.php';
 
		
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
		$mail->Subject = $utf8_decode($betreff);
	
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
		exit;
	}
	if(isset($_SESSION['page_id'])) {
		#$query = "SELECT * FROM menue_parent WHERE menue_id='".$_SESSION['page_id']."'";
		#$resParent = DBi::$conn->query($query) or die(mysqli_error());
		#$aryParrent = mysqli_fetch_assoc($resParent);
		#$iParrent = $aryParrent['parent_id'];
	} else {
		$iParrent = 0;
	}
	$strOptMenueSelekt = rss_category(0,0,'',0,0,'select',$_SESSION['system_shop_last_cat']);
	$strOptMenueSelekt2 = menue_generator(0,0,'',0,0,'select',$_SESSION['page_id']);
?>
<div id="acp_main_new_domain_form">
	<h2>RSS-Feed Kategorie anlegen</h2>
	<form name="frmPageSetting" id="rss_category" action="/ACP/acp_rss_category.php" method="POST" onSubmit="return shop_save_form('rss_category');">
		<div id="acp_new_page_form">
			<input type="hidden" id="acp_get_modus" name="modus" value="<?php echo $_GET['modus']; ?>"/>
			<div class="label" style="float:left;">RSS-Feed Bereich Bezeichnung*</div>
			<div id="shop_cat_title_head">
				<input type="text" id="shop_cat_title" onChange="setTextboxValue('page_url_name','shop_cat_title');setTextboxValue('shop_page_titel','shop_cat_title');" name="shop_cat_title" value="<?php echo $strMenueData['titel_de']; ?>"><span class="error" id="shop_cat_title_err"></span>			
			</div>
			<div style="clear:both"></div>
			<div class="label" style="float:left;">RSS-Feed position</div>
			<div id="shop_cat_position">
				<input type="text" name="shop_cat_position" value="<?php echo $strMenueData['sortierung']; ?>">
			</div>	
			<div style="clear:both"></div>
			<div class="label" style="float:left;">&Uuml;bergeordnete News Kategorie</div>
			<div id="shop_path">
				<select name="shop_cat_id" size="1">
					<?php 
					 echo $strOptMenueSelekt;
					?>
				</select>
			</div>	
			<h2>RSS-Feed Webseite anlegen</h2>
			<div class="label" style="float:left;">&Uuml;bergeordnete Men&uuml; Kategorie*</div><span class="error" id="module_url_path_err"></span>
			<div id="shop_path">
				<select id="page_menue_id" name="page_menue_id" size="1">
					<option value="KEINE-AUSWAHL">Keine Auswahl</option>
					<option value="0">Hauptkategorie (Men&uuml;ebene 0)</option>
					<?php 
					 echo $strOptMenueSelekt2;
					?>
				</select>
			</div>				
			<div style="clear:both"></div>	
			<div class="label" style="float:left;">Layout Seite</div>
			<div id="page_layout">
				<select name="page_layout" size="1">
					<option value="col2-left-layout" <?php if($strMenueData['container'] == 'col2-left-layout') echo "selected=true"?>>LINKS + MITTE</option>
					<option value="col2-right-layout" <?php if($strMenueData['layout'] == 'col2-right-layout') echo "selected=true"?>>MITTE + MENÜ RECHTS</option>
					<option value="col3-layout" <?php if($strMenueData['layout'] == 'col3-layout') echo "selected=true"?>>MENÜ LINKS + MITTE + RECHTS Spalte</option>
				</select>			
			</div>			
			<div style="clear:both"></div>
			<div class="label" style="float:left;">Seiten&uuml;berschriftt</div>
			<div id="shop_page_titel_header">
				<input type="text" id="shop_page_titel" name="shop_page_titel" value="<?php echo $strMenueData['titel_de']; ?>">			
			</div>
			<div style="clear:both"></div>
			<div class="label" style="float:left;">Men&uuml;punkt Name*</div>
			<div id="module_url_path">
				<input type="text" id="page_url_name" name="page_url_name" value="<?php echo $strMenueData['name_de']; ?>"><span class="error" id="page_url_name_err"></span>
			</div>
			<div style="clear:both"></div>			
			<div class="label" style="float:left;">Sortierung</div>
			<div id="shop_page_sort">
				<input type="text" name="shop_page_sort" value="<?php echo $strMenueData['sortierung']; ?>">
			</div>	
						
			<div style="clear:both"></div>
			 
			<div id="module_submit"><br/>
				<input type="submit" class="module_form_submit button" id="module_form_submit" name="module_submit" value="<?php echo $strButtonName; ?>">
			</div>		
		</div>
	</form>
</div>