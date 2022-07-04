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
	
	if(isset($_POST['page_layout'])) {
		
		if($_POST['modus'] == 'new') {
			// Page Einstellugen Speichern
			$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id,created_at,template_file) VALUES ('".$_POST['page_url_name']."', '".$_POST['page_title']."', '".$_POST['menue_sortierung']."', 'sichtbar', '".$_POST['page_layout']."','".$_SESSION['domain_id']."','".date("Y-m-d H:i:s")."','".$_POST['page_template_layout']."');";
			$resInsert = DBi::$conn->query($query) or die(mysqli_error());
			$iPageID = mysqli_insert_id(DBi::$conn);
			
			$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID.", ".$_POST['page_menue_id'].");";
			$resInsert = DBi::$conn->query($query) or die(mysqli_error());
			
			$path = getPathUrl($_SESSION['language'],$iPageID);			
			$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
			echo $strLink;
			
		} else {
			$query = "UPDATE `menue` SET `name_de`='".$_POST['page_url_name']."', `titel_de`='".$_POST['page_title']."', sortierung= '".$_POST['menue_sortierung']."',status_de='".$_POST['page_template_sichtbarkeit']."',template_file='".$_POST['page_template_layout']."' WHERE  `id`=".$_SESSION['page_id']."; ";
			DBi::$conn->query($query) or die(mysqli_error());
		
			$query ="SELECT * FROM menue_parent WHERE menue_id=".$_POST['page_menue_id'];
			$resQuery = DBi::$conn->query($query) or die(mysqli_error());
			$strMenueParrent = mysqli_fetch_assoc($resQuery);
						
			$query = "UPDATE `menue_parent` SET `parent_id`=".$_POST['page_menue_id']." WHERE  `menue_id`=".$_SESSION['page_id'].";";
			DBi::$conn->query($query) or die(mysqli_error());			
			
			$path = getPathUrl($_SESSION['language'],$_SESSION['page_id']);			
			$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
			echo $strLink;
			
		}
		
		
		// Email verschicken 
		$path = realpath($_SERVER["DOCUMENT_ROOT"]);
		require_once $path.'/framework/phpmailer/PHPMailerAutoload.php';

		$html = 'Shopste Systemnachricht, <br/>
		Neue Seite angelegt.<br/>
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
		$mail->addAddress("jbludau@cubss.net","Shopste Service");
		#$mail->AddBCC();
		//Set the subject line
		if($_POST['modus'] == 'new') {
			$mail->Subject = 'Shopste Portal Neue Seite  '.$_POST['page_url_name'];
		} else {
			$mail->Subject = 'Shopste Portal Update Seite '.$_POST['page_url_name'];
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
	} else {	
		
		if($_GET['modus'] == 'edit') {
			echo '<h2>Webseite editieren '.$strMenueData['titel_de'].'</h2>';
			$strButtonName = 'Webseite editieren speichern';
			
			$query = "SELECT * FROM menue_parent WHERE menue_id='".$_SESSION['page_id']."'";
			$resMenueData = DBi::$conn->query($query) or die(mysqli_error());
			$strMenueData_parent = mysqli_fetch_assoc($resMenueData);	
			
			$strOptMenueSelekt = menue_generator(0,0,'',0,0,'select',$strMenueData_parent['parent_id']);

			$query = "SELECT * FROM menue WHERE id='".$_SESSION['page_id']."'";
			$resMenueData = DBi::$conn->query($query) or die(mysqli_error());
			$strMenueData = mysqli_fetch_assoc($resMenueData);		
			
		} else {
			echo '<h2>Neue Webseite '.$strMenueData['titel_de'].' anlegen</h2>';
			$strButtonName = 'Neue Webseite erstellen';
			$query = "SELECT * FROM menue_parent WHERE menue_id='".$_SESSION['page_id']."'";
			$resMenueData = DBi::$conn->query($query) or die(mysqli_error());
			$strMenueData_parent = mysqli_fetch_assoc($resMenueData);	
			
			$strOptMenueSelekt = menue_generator(0,0,'',0,0,'select',$strMenueData_parent['parent_id']);

		}
	
?>
<div id="acp_main_new_page_form">
	<form name="frmPageSetting" id="page_save" action="/ACP/acp_form_page_settings.php" method="POST" onSubmit="return page_save_form('page_save');">
		<div id="acp_new_page_form">
			<?php if($strMenueData['created_at'] != '') { ?> 
			<div class="label" style="float:left;">Meta Daten</div>
			<?php echo getDateDE($strMenueData['created_at']) ?><br/>
			<?php echo getDateDE($strMenueData['updated_at']) ?><br/>
			<?php } ?>
			<input type="hidden" id="acp_get_modus" name="modus" value="<?php echo $_GET['modus']; ?>"/>
			<div class="label" style="float:left;">Seiten&uuml;berschrift:</div>
			<div id="page_title">
				<input type="text" name="page_title" id="page_title" value="<?php echo $strMenueData['titel_de']; ?>">			
			</div>
			<div style="clear:both"></div>
	
			<div class="label" style="float:left;">Men&uuml;punkt Name*</div>
			<div id="module_url_path">
				<input type="text" name="page_url_name"  id="page_url_name" value="<?php echo $strMenueData['name_de']; ?>"><span class="error" id="page_url_name_err"></span>
			</div>
			<div style="clear:both"></div>
			<div class="label" style="float:left;">Template Datei:*</div>
			<div id="page_template_layout">
				<select name="page_template_layout" size="1">
					<option value="index.tpl" <?php if($strMenueData['template_file'] == 'index.tpl') echo "selected=true"?>>Standardtemplate</option>
				</select>			
			</div>			
			<div style="clear:both"></div>
			<div class="label" style="float:left;">Sichtbarkeit:*</div>
			<div id="page_template_layout">
				<select name="page_template_sichtbarkeit" size="3">
					<option value="sichtbar" <?php if($strMenueData['status_de'] == 'sichtbar') echo "selected=true"?>>Sichtbar</option>
					<option value="unsichtbar" <?php if($strMenueData['status_de'] == 'unsichtbar') echo "selected=true"?>>Unsichtbar</option>
					<option value="produktdetailseite" <?php if($strMenueData['status_de'] == 'produktdetailseite') echo "selected=true"?>>Produktdetailseite</option>
				</select>			
			</div>			
			<div style="clear:both"></div>				
			<div class="label" style="float:left;">Layout Seite:</div>
			<div id="page_layout">
				<select id="page_layout" name="page_layout" size="4">
					<option value="col2-left-layout" <?php if($strMenueData['container'] == 'col2-left-layout') echo "selected=true"?>>LINKS + MITTE</option>
					<option value="col2-right-layout" <?php if($strMenueData['layout'] == 'col2-right-layout') echo "selected=true"?>>MITTE + MENÜ RECHTS</option>
					<option value="col3-layout" <?php if($strMenueData['layout'] == 'col3-layout') echo "selected=true"?>>MENÜ LINKS + MITTE + RECHTS Spalte</option>
					<option value="col1-layout" <?php if($strMenueData['layout'] == 'col1-layout') echo "selected=true"?>>NUR MITTE</option>
				</select>			
			</div>
			<div class="label" style="float:left;">&Uuml;bergeordneter Men&uuml;punkt*</div><span class="error" id="module_url_path_err"></span>
			<div id="module_url_path">
				<select id="page_menue_id" name="page_menue_id" size="1">
					<option value="KEINE-AUSWAHL">Keine Auswahl</option>
					<option value="0">Hauptkategorie (Men&uuml;ebene 0)</option>
					<?php 
					 echo $strOptMenueSelekt;
					?>
				</select>
			</div>
			<div style="clear:both"></div>
			<div class="label" style="float:left;">Men&uuml;position</div>
			<div id="module_menuposition">
				<input type="text" name="menue_sortierung" id="menue_sortierung" value="<?php echo $strMenueData['sortierung']; ?>">
			</div>
			<div style="clear:both"></div>			
			<div style="clear:both"></div>
			<div class="label" style="float:left;">Eintragen:</div>
			<div id="module_submit">
				<input type="submit" class="button module_form_submit" id="module_form_submit" name="module_submit" value="<?php echo $strButtonName; ?>">
			</div>		
		</div>
	</form>
</div>
<?php 
}
?>