<?php 
	session_start();
	// Datenbankverbindung
	require_once('../include/inc_config-data.php');
	require_once('../include/inc_basic-functions.php');
	$strButtonName = 'News Inhalt anlegen';
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
		
		if($_POST['modus'] == 'new') {

			// Page Einstellugen Speichern
			$_SESSION['news_username'] = $_POST['module_author'];
			$_SESSION['news_email'] = $_POST['module_email'];
			
			$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id,content_type) VALUES ('".$_POST['module_title']."', '".$_POST['module_title']."', '".$_POST['module_position']."', 'unsichtbar', 'col2-left-layout','".$_SESSION['domain_id']."','news_content');";
			$resInsert = DBi::$conn->query($query) or die('ERR0001: '.mysqli_error());
			$iPageID = mysqli_insert_id(DBi::$conn);
			
 			// Page Einstellugen Speichern   
			if(empty($_POST['module_erstelltam'])){
				$_POST['module_erstelltam'] = date("Y-m-d H:i:s");
			}
			$strDatumDE = explode(" ",$_POST['module_erstelltam']);
			$datum = explode(".",$strDatumDE[0]);
			$eng_datum = $datum[2].'-'.$datum[1].'-'.$datum[0].' '.$strDatumDE[1];
			#echo $eng_datum; 
			$query = "INSERT INTO `modul_news_content` (`AddTitel`,Bereich,AddText,domain_id,page_id,Author,Webseite,AddDatum,eMail,news_cat) VALUES ('".$_POST['module_title']."','".$_POST['shop_cat_id']."','".$_POST['module_texthtml_content']."','".$_SESSION['domain_id']."','".$iPageID."','".$_POST['module_author']."','".$_POST['module_webseite']."','".$eng_datum."','".$_POST['module_email']."','".$_POST['shop_cat_id']."');"; 
			$resInsert = DBi::$conn->query($query) or die('ERR0002: '.mysqli_error());
			$iPageID2 = mysqli_insert_id(DBi::$conn);
			$icat = $iPageID2;
			#echo $iPageID2;
			#print_r($query);
			#print_r($_POST);
			$_SESSION['system_shop_last_cat'] = $_POST['shop_cat_id'];
			
			if(empty($_POST['shop_cat_id'])) {
				$_POST['shop_cat_id'] = '0';
			}
 
			$query = "SELECT * FROM modul_news_category WHERE news_cat_id='".$_POST['shop_cat_id']."'";
			$resNewsCat = DBi::$conn->query($query);
			$strNewsCat = mysqli_fetch_assoc($resNewsCat);
			
			
			$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID.", ".$strNewsCat['page_id'].");";
			$resInsert = DBi::$conn->query($query) or die('ERR0003: '.mysqli_error());
			
	 
			
			// Modul Einstellugen Speichern
			$query = "INSERT INTO `modul_news_content_view` (`title_de`, `menue_id`, `last_usr`,news_cat) VALUES ('".$_POST['module_title']."', ".$iPageID.", 0,'".$icat."');";
			$resInsert = DBi::$conn->query($query) or die('ERR0004: '.mysqli_error());
			$iModulID = mysqli_insert_id(DBi::$conn);
		
			// Modul auf einer Seite bekannt machen
			$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'news_content_view', 'col-main', '".$_POST['module_position']."');";
			$resInsert = DBi::$conn->query($query) or die('ERR0005: '.mysqli_error());
			
			// Modul Einstellugen Speichern
			$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('".$_POST['module_title']."', ".$iPageID.", 0, 'submenue', 'Y');";
			$resInsert = DBi::$conn->query($query) or die('ERR0006:'.mysqli_error());
			$iModulID = mysqli_insert_id(DBi::$conn);
		
			// Modul auf einer Seite bekannt machen
			$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'menue', 'col-left', '".$_POST['module_position']."');";
	
			$resInsert = DBi::$conn->query($query) or die('ERR0007: '.mysqli_error());
			
			$path = getPathUrl($_SESSION['language'],$iPageID);			
			$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
			#@mail("info@shopste.com","News Kategorie angelegt: '".$_POST['module_title']."'",'LINK: '.$strLink);
			echo $strLink;
			#exit;
		}	
		
		$path = getPathUrl($_SESSION['language'],$iPageID);			
		$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
		
		// Email verschicken 
		$path = realpath($_SERVER["DOCUMENT_ROOT"]);
		require_once $path.'/framework/phpmailer/PHPMailerAutoload.php';

		$query ="SELECT count(*) as anzahl FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_NEWS_ADD'";
		#echo $query;
		$resEmailCount = DBi::$conn->query($query) or die('ERR0008: '.mysqli_error());
		$strEmailCount = mysqli_fetch_assoc($resEmailCount);
		if($strEmailCount['anzahl'] > 0) {
			# Lade Benutzervorlage
			$query ="SELECT * FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_NEWS_ADD'";
			#echo $query;
			$resEmailVorlage = DBi::$conn->query($query) or die('ERR0009: '.mysqli_error());
			$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
		} else {
			# Lade Defaultvorlage
			$query ="SELECT * FROM email_vorlage WHERE domain_id='0' AND standard='Y' AND typ='CORE_NEWS_ADD'";
			#echo $query;
			$resEmailVorlage = DBi::$conn->query($query) or die('ERR0010: '.mysqli_error());
			$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
		}
		
		$strURL_shop = getPathUrl($_SESSION['language'],$ShopInfoData['menue_id']);
 
		#
		#$_POST['module_title']
		$html = $strEmailVorlage['content'];
		$betreff = $strEmailVorlage['betreff'];
		
		$betreff = str_replace('###NEWS_TITLE###',$_POST['module_title'],$betreff);
		if($_POST['modus'] == 'new') {
			$betreff = str_replace('###NEWS_MODUS###','hinzugefügt',$betreff);
		} else {
			$betreff = str_replace('###NEWS_MODUS###','aktuallisiert',$betreff);
		}
		$html = str_replace('###NEWS_TITLE###',$_POST['module_title'],$html);
		$html = str_replace('###NEWS_LINK###',CORE_SERVER_DOMAIN.$strURL_shop,$html);
		$html = str_replace('###CORE_MAIN_PLATTFORM###',CORE_SERVER_PLATTFORM_NAME,$html);
		
		//Create a new PHPMailer instance
		$mail = new PHPMailer();
		// Set PHPMailer to use the sendmail transport
		$mail->isSendmail();
		//Set who the message is to be sent from
		$mail->setFrom(CORE_MAIL_FROM_NEWS, CORE_MAIL_FROM_NEWS_NAME);
		//Set an alternative reply-to address
		$mail->addReplyTo(CORE_MAIL_FROM_NEWS, CORE_MAIL_FROM_NEWS_NAME);
		//Set who the message is to be sent to
		$mail->addAddress(CORE_MAIL_FROM_NEWS_BCC,CORE_MAIL_FROM_NEWS_BCC_NAME);
 
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
		#$resParent = DBi::$conn->query($query) or die(mysqli_error());
		#$aryParrent = mysqli_fetch_assoc($resParent);
		#$iParrent = $aryParrent['parent_id'];
	} else {
		$iParrent = 0;
	}
	$strOptMenueSelekt = news_category(0,0,'',0,0,'select',$_SESSION['system_shop_last_cat']);
	$strOptMenueSelekt2 = menue_generator(0,0,'',0,0,'select',$_SESSION['page_id']);
?>
<div id="acp_main_new_page_form">
	<h2>News Inhalt anlegen</h2>
	<form name="frmModulAdd" id="news_content" action="/ACP/acp_news_post.php" method="POST" onSubmit="return shop_save_form('news_content');">
			<div class="label" style="float:left;">&Uuml;bergeordnete News Kategorie</div>
			<div id="shop_path">
				<select name="shop_cat_id" size="1">
					<?php 
					 echo $strOptMenueSelekt;
					?>
				</select>
			</div>	
			
				<div class="label" style="float:left;">
					<input type="hidden" id="acp_get_modul_name" name="optModul" value="news_content"/>
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
				<div class="label" style="float:left;">News &Uuml;berschrift*</div>
				<div id="module_title">
					<input type="text" name="module_title" value="<?php echo $dataMenue['title_de']; ?>">
				</div>				
				<div style="clear:both"></div>
				
				<div class="label" style="float:left;">Einsender / Author*</div>
				<div id="module_author">
					<input type="text" name="module_author" value="<?php echo $_SESSION['news_username']; ?>">
				</div>				
				<div style="clear:both"></div>				

				<div class="label" style="float:left;">Weiterlesen Webseite / Author Webseite</div>
				<div id="module_webseite">
					<input type="text" name="module_webseite" value="<?php echo $dataMenue['Webseite']; ?>">
				</div>				
				<div style="clear:both"></div>	
				
				<div class="label" style="float:left;">Email-Adresse*</div>
				<div id="module_email">
					<input type="text" name="module_email" value="<?php echo $_SESSION['news_email']; ?>">
				</div>				
				<div style="clear:both"></div>	
				
				<div class="label" style="float:left;">Erstellt am</div>
				<div id="module_erstelltam">
					<input type="text" name="module_erstelltam" value="<?php 
					if(empty($dataMenue['AddDatum'])) {												 
						echo date("d.m.Y H:i:s");
					} else {
						echo $dataMenue['AddDatum'];
					}
					?>">
				</div>				
				<div style="clear:both"></div>	
				
				<div class="label" style="float:left;">Layout Position*</div>
				<div id="module_position">
						<select name="page_layout" size="1">        
							<option value="col-main" <?php if($dataPageData['container'] == 'col-main') echo "selected=true"?>>Box Mitte</option>
							<option value="col-left" <?php if($dataPageData['container'] == 'col-left') echo "selected=true"?>>Box Links</option>
							<option value="col-right" <?php if($dataPageData['container'] == 'col-right') echo "selected=true"?>>Box Rechts</option>
						</select>			
				</div>		
				<div style="clear:both"></div>
				<div class="label" style="float:left;">Position</div>
				<div id="module_position">
					<input type="text" name="module_position" value="<?php 
					if($_GET['modus'] == 'new') {
						echo $dataPosition['max_position'] +1;
					} else {
						echo $dataPageData['position']; 
					}
					?>">
				</div>
				<div style="clear:both"></div>
				<div class="label" style="float:left;">Nachrichtentext eingeben*</div>
 
				<div id="shop_item_price">
					<textarea name="module_texthtml_content" id="module_texthtml_content">	<?php echo $dataMenue['content_de']; ?></textarea>
				</div>	
				<script>
					
						var editor = CKEDITOR.replace('module_texthtml_content');

				</script>
				<input type="hidden" id="acp_get_hasHTMLModule" name="acp_get_hasHTMLModule" value="1"/>
				<div id="module_submit"><br/>
					<input type="submit" id="news_submit" class="button" name="module_submit" value="<?php echo $strButtonName; ?>">
				</div>		
			</form>

</div>