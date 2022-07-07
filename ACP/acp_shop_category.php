<?php 
	session_start();
	// Datenbankverbindung
	require_once('../include/inc_config-data.php');
	require_once('../include/inc_basic-functions.php');
	$strButtonName = 'Kategorie anlegen';
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
			if(!is_numeric($_POST['shop_cat_position'])) {
				$_POST['shop_cat_position'] = 0;
			}
			$query = "INSERT INTO `shop_category` (`name_de`,created_at,sortierung,domain_id) VALUES ('".$_POST['shop_cat_title']."','".date("Y-m-d H:i:s")."','".$_POST['shop_cat_position']."','".$_SESSION['domain_id']."');";
			$resInsert = DBi::$conn->query($query) or die('ERR01:'.mysqli_error());
			$iPageID = mysqli_insert_id(DBi::$conn);
			$icat = $iPageID;
			$_SESSION['system_shop_last_cat'] = $_POST['shop_cat_id'];
			
			if($_POST['shop_cat_id'] == '') {
				$_POST['shop_cat_id'] = 0;
			}
			$query = "INSERT INTO `shop_category_parent` (`shop_cat_id`, `shop_cat_parent`) VALUES (".$iPageID.", ".$_POST['shop_cat_id'].");";
			#echo $query;
			$resInsert = DBi::$conn->query($query) or die('ERR02:'.mysqli_error());
			
			// Page Einstellugen Speichern
			if(empty($_POST['shop_page_sort'])) {
				$_POST['shop_page_sort'] = 0;			
			}
			
			$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id,content_type) VALUES ('".$_POST['page_url_name']."', '".$_POST['shop_page_titel']."', '".$_POST['shop_page_sort']."', 'sichtbar', '".$_POST['page_layout']."','".$_SESSION['domain_id']."','kategorie_seite');";
			$resInsert = DBi::$conn->query($query) or die('ERR03:'.mysqli_error());
			$iPageID = mysqli_insert_id(DBi::$conn);
			
			$query = "UPDATE shop_category SET page_id='".$iPageID."' WHERE shop_cat_id='".$icat."'";
			DBi::$conn->query($query);
			
			$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID.", ".$_POST['page_menue_id'].");";
			$resInsert = DBi::$conn->query($query) or die('ERR04:'.mysqli_error());
			
	 
			
			// Modul Einstellugen Speichern
			$query = "INSERT INTO `modul_shop_cat_list` (`title_de`, `menue_id`, `last_usr`,shop_cat_id) VALUES ('".$_POST['shop_page_titel']."', ".$iPageID.", 0,'".$icat."');";
			$resInsert = DBi::$conn->query($query) or die('ERR05:'.mysqli_error());
			$iModulID = mysqli_insert_id(DBi::$conn);
		
			// Modul auf einer Seite bekannt machen
			if(!isset($_POST['module_position'])) {
				$_POST['module_position'] = 0;
			}
			$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'shop_cat_list', 'col-main', '".$_POST['module_position']."');";
			$resInsert = DBi::$conn->query($query) or die('ERR06:'.mysqli_error());
			
			// Modul Einstellugen Speichern
			$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('".$_POST['shop_page_titel']."', ".$_POST['page_menue_id'].", 0, 'submenue', 'Y');";
			$resInsert = DBi::$conn->query($query) or die('ERR07:'.mysqli_error());
			$iModulID = mysqli_insert_id(DBi::$conn);
		
			// Modul auf einer Seite bekannt machen
			$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'menue', '".$strModuleColum."', '".$_POST['module_position']."');";
	
			$resInsert = DBi::$conn->query($query) or die('ERR08:'.mysqli_error());
			
			$path = getPathUrl($_SESSION['language'],$iPageID);			
			$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
			@mail("info@shopste.com","Kategorie angelegt: '".$_POST['shop_cat_title']."'","");
			echo $strLink;
			exit;
		}	
		
		$path = getPathUrl($_SESSION['language'],$iPageID);			
		$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
		
		// Email verschicken 
		$path = realpath($_SERVER["DOCUMENT_ROOT"]);
		require_once $path.'/framework/phpmailer/PHPMailerAutoload.php';

		$html = 'Shopste Systemnachricht, <br/>
		Neue Kategorie angelegt.<br/>
		<br/>
		<strong>Seite: </strong><a href="'.$strLink.'">'.$_POST['page_url_name'].'</a><br/>';
		
		//Create a new PHPMailer instance
		$mail = new PHPMailer();
		// Set PHPMailer to use the sendmail transport
		$mail->isSendmail();
		//Set who the message is to be sent from
		$mail->setFrom(CORE_MAIL_FROM_EMAIL,CORE_MAIL_FROM_EMAIL_NAME);
		//Set an alternative reply-to address
		$mail->addReplyTo(CORE_MAIL_FROM_EMAIL, CORE_MAIL_FROM_EMAIL_NAME);
		//Set who the message is to be sent to
		$mail->addAddress(CORE_MAIL_SEND_BCC,CORE_MAIL_SEND_BCC_NAME);
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
	if(isset($_SESSION['page_id'])) {
		#$query = "SELECT * FROM menue_parent WHERE menue_id='".$_SESSION['page_id']."'";
		#$resParent = DBi::$conn->query($query) or die(mysqli_error());
		#$aryParrent = mysqli_fetch_assoc($resParent);
		#$iParrent = $aryParrent['parent_id'];
	} else {
		$iParrent = 0;
	}
	$strOptMenueSelekt = shop_category(0,0,'',0,0,'select',$_SESSION['system_shop_last_cat']);
	$strOptMenueSelekt2 = menue_generator(0,0,'',0,0,'select',$_SESSION['page_id']);
		
	$strDomain_ary = getDomainInfo();
	
?>
<div id="acp_main_new_page_form">
	<?php 
			if($strDomain_ary['isShopste'] == 'Y') {
			$strTitelPage = 'Neue Produktkateogrie erstellen';
			$str_gui_header_category = 'Übergeordnete Shop-Kategorie ';
			$str_gui_header_product = 'Produktkategorie Name';
			$str_gui_footer_button_send = 'Shop Produkt erstellen';
			$strHTML = '<br/>Denken Sie sich einen Aussagekräftigen Produktnamen für Ihre Artikel aus. Denken Sie an "Keywords", das sind wichtige Schlagworte.<br/>
			Bitte füllen Sie das Formular aus. Die mit * gekennzeichten Felder sind auszufüllen.<br/>';
		} else {
			if($strDomain_ary['isRestaurant'] == 'Y') {
				$strTitelPage = 'Neue Speisekategorie erstellen';
				$str_gui_header_category = 'Übergeordnete Speisekategorie ';
				$str_gui_header_product = 'Speisekategorie Name';
				$str_gui_footer_button_send = 'Kategorie der Speisekarte hinzufügen';
				$strHTML =  '<br/>Bitte denken Sie sich einen Speisenamen aus.<br/>
				Bitte füllen Sie das Formular aus. Die mit * gekennzeichten Felder sind auszufüllen.<br/>';
			} else {
				$strTitelPage = 'Neue Produktkateogrie erstellen';
				$str_gui_header_category = 'Übergeordnete Shop-Kategorie ';
				$str_gui_header_product = 'Produktkategorie Name';
				$str_gui_footer_button_send = 'Shop Kategorie erstellen';
				$strHTML =  '<br/>Bitte füllen Sie das Formular aus. Die mit * gekennzeichten Felder sind auszufüllen.<br/>';				
			}
		}
			
	echo '<h1>'.$strTitelPage.'</h1>';
	
	if($strMenueData['created_at'] != '') { ?> 
	<div class="label" style="float:left;">Meta Daten</div>
	<?php echo getDateDE($strMenueData['created_at']) ?><br/>
	<?php echo getDateDE($strMenueData['updated_at']) ?><br/>
	<?php } ?>
	<form name="frmPageSetting" id="shop_category" action="/ACP/acp_shop_category.php" method="POST" onSubmit="return shop_save_form('shop_category');">
		<div id="acp_new_page_form">
			
			<input type="hidden" id="acp_get_modus" name="modus" value="<?php echo $_GET['modus']; ?>"/>
			<div class="label" style="float:left;"><?php echo $str_gui_header_category; ?>
				<div id="shop_path">
					<select name="shop_cat_id" size="1">
						<?php 
						 echo $strOptMenueSelekt;
						?>
					</select>
				</div>	
			</div>
			
			<div class="label" style="float:left;"><?php echo $str_gui_header_product; ?>
				<div id="shop_cat_title_head">
					<input type="text" id="shop_cat_title" onKeyUp="setTextboxValue('page_url_name','shop_cat_title');setTextboxValue('shop_page_titel','shop_cat_title');" name="shop_cat_title" value="<?php echo $strMenueData['titel_de']; ?>"><span class="error" id="shop_cat_title_err"></span>			
				</div>
			</div>
 			
			<div class="label" style="float:left;">Position im Men&uuml;-Baum*<span class="error" id="module_url_path_err"></span>
				<div id="shop_path">
					<select id="page_menue_id" name="page_menue_id" size="1">
						<option value="KEINE-AUSWAHL">Keine Auswahl</option>
						<option value="0">Hauptkategorie (Men&uuml;ebene 1)</option>
						<?php 
						 echo $strOptMenueSelekt2;
						?>
					</select>
				</div>				
			</div>
			<?php 
			if($strDomain_ary['isRestaurant'] == 'N') {						
			?>
			<div class="label" style="float:left;">Seitenlayout
				<div id="page_layout">
					<select name="page_layout" size="1">
						<option value="col2-left-layout" <?php if($strMenueData['container'] == 'col2-left-layout') echo "selected=true"?>>LINKS + MITTE</option>
						<option value="col2-right-layout" <?php if($strMenueData['layout'] == 'col2-right-layout') echo "selected=true"?>>MITTE + MENÜ RECHTS</option>
						<option value="col3-layout" <?php if($strMenueData['layout'] == 'col3-layout') echo "selected=true"?>>MENÜ LINKS + MITTE + RECHTS Spalte</option>
					</select>			
				</div>			
			</div>
			<?php 
			}
			?>			
			<div class="label" style="float:left;">Kategorie position
				<div id="shop_cat_position">
					<input type="text" name="shop_cat_position" value="<?php echo $strMenueData['sortierung']; ?>">
				</div>	
			</div>


			<div class="label" style="float:left;">Seiten&uuml;berschriftt
				<div id="shop_page_titel_header">
					<input type="text" id="shop_page_titel" name="shop_page_titel" value="<?php echo $strMenueData['titel_de']; ?>">			
				</div>
			</div>
			
			
			<div class="label" style="float:left;">Men&uuml;punkt Name*
				<div id="module_url_path">
					<input type="text" id="page_url_name" name="page_url_name" value="<?php echo $strMenueData['name_de']; ?>"><span class="error" id="page_url_name_err"></span>
				</div>
			</div>

			<div class="label" style="float:left;">Sortierung
				<div id="shop_page_sort">
					<input type="text" name="shop_page_sort" value="<?php echo $strMenueData['sortierung']; ?>">
				</div>	
			</div>
			 
			<div id="module_submit"><br/>
				<input type="submit" class="module_form_submit button" id="module_form_submit" name="module_submit" value="<?php echo $str_gui_footer_button_send; ?>">
			</div>		
		</div>
	</form>
</div>