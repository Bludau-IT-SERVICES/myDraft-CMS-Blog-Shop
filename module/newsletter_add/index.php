<?php 
session_start();
$path = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($path.'/include/inc_config-data.php');
require_once($path.'/include/inc_basic-functions.php');

$_POST = mysql_real_escape_array($_POST);
$_GET = mysql_real_escape_array($_GET);
	
	// Login überprüfen
	// $chkCookie = admin_cookie_check();
 
	// #echo $chkCookie;
	// if($_SESSION['login'] == 1) {		
		// $_SESSION['login'] = 1; 
	// } else {
		// exit(0);
//	}
	
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
			$query = "INSERT INTO `modul_rss_quelle` (`title_de`, `rss_quelle`, `rss_cat`) VALUES('".$_POST['module_title']."','".$_POST['module_rss_quelle']."','".$_POST['shop_cat_id']."');";
			$resInsert = DBi::$conn->query($query) or die(mysqli_error());
			$iPageID = mysqli_insert_id(DBi::$conn);
			 
			
			$path = getPathUrl($_SESSION['language'],$iPageID);			
			$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
			@mail("kontakt@freie-welt.eu","RSS-Feed Quelle angelegt: '".$_POST['module_title']."'",'LINK: '.$strLink);
			echo $strLink;
			exit;
		}	
		
		$path = getPathUrl($_SESSION['language'],$iPageID);			
		$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
		
		// Email verschicken 
		$path = realpath($_SERVER["DOCUMENT_ROOT"]);
		require_once $path.'/framework/phpmailer/PHPMailerAutoload.php';

		$html = 'Freie-Welt Systemnachricht, <br/>
		Neue RSS-Feed Nachrichten Seite angelegt.<br/>
		<br/>
		<strong>Seite: </strong><a href="'.$strLink.'">'.$_POST['module_title'].'</a><br/>';
		
		//Create a new PHPMailer instance
		$mail = new PHPMailer();
		// Set PHPMailer to use the sendmail transport
		$mail->isSendmail();
		//Set who the message is to be sent from
		$mail->setFrom('kontakt@tsecurity.de', 'TSecurity.de');
		//Set an alternative reply-to address
		$mail->addReplyTo('kontakt@tsecurity.de', 'TSecurity.de Service');
		//Set who the message is to be sent to
		$mail->addAddress("kontakt@tsecurity.de","TSecurity.de");
		#$mail->AddBCC();
		//Set the subject line
		if($_POST['modus'] == 'new') {
			$mail->Subject = 'RSS-Feed Nachrichten  angelegt'.$_POST['page_url_name'];
		} else {
			$mail->Subject = 'RSS-Feed Nachrichten Update '.$_POST['page_url_name'];
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
####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_newsletter_add($config) {

		$dataTextHTML = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM modul_newsletter_add WHERE id=".$config['modul_id']));
		 $strButtonName = 'In Newsletter eintragen';
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='newsletter_add'"));
		#echo "IN";
		#print_r($dataTextHTML);
		$dataTextHTML['typ'] = 'newsletter_add';
		
		$text = '<div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
		
		$text .= convertUmlaute($dataTextHTML["content_".$_SESSION['language']]);
		$titel = convertUmlaute($dataTextHTML["title_".$_SESSION['language']]);
		

		
		if($text == '') {   
			$text = convertUmlaute($dataTextHTML["content_de"]); 
		} 
		#echo $dataTextHTML["id"];
		if($titel == '') { 
			$titel = convertUmlaute($dataTextHTML["title_de"]); 
		} 
		
		// && $config["container"]
		if($_SESSION['login'] == '1'  AND $module_in_menue['container'] == 'col-main') {
			$strReturn = getMember($dataTextHTML['last_usr']);
			if(!empty($strReturn)) {
				$ary = explode(" ",$dataTextHTML['lastchange']);
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
 
	
		$text .='
<div id="acp_main_new_page_form">
	<h2>Newsletter Anmeldung</h2>
	Sie können sich hier Anmelden um über News von TSecurity.de informiert zu werden.<br/>
<form name="frmModulAdd" id="newsletter" action="/ACP/acp_newsletter_add.php" method="POST" onSubmit="return newsletter_add(\'newsletter\');">
	<div class="label" style="float:left;">Nickname oder Name</div>
 			<div id="shop_path">
				<input type="text" id="modul_newsletter_name" name="modul_newsletter_name" value=""/>
				<span id="modul_newsletter_name_err"></span>
			</div>	
	 
			<input type="hidden" id="acp_get_modul_name" name="optModul" value="newsletter"/>
			<input type="hidden" id="acp_get_modus" name="modus" value="new"/>
			<input type="hidden" id="acp_get_modul_id" name="module_id" value="'.$_GET['id'].'"/>
			<input type="hidden" id="acp_get_hasHTMLModule" name="acp_get_hasHTMLModule" value="0"/>';
			 
			if($_GET['modus'] == 'new') {
		 
				$text .='<input type="hidden" id="acp_get_page_id" name="page_id" value="'.$_SESSION['page_id'].'"/>';
		 
			}
					 
				$text .='			
				<div style="clear:both"></div>				
				<div class="label" style="float:left;">Email-Adresse</div>
				<div id="modul_email">
					<input type="text" id="modul_email_adresse" name="modul_email_adresse" value="'.$dataMenue['email'].'">
					<span id="modul_email_adresse_err"></span>
				</div>
				<div style="clear:both"></div>				
				<div id="module_submit"><br/>
					<input type="submit" class="button" name="module_submit" value="'.$strButtonName.'">
				</div>		
			</form>
</div><br/>';
		
	#	$text .= '</div></div>'; // config modus 

		
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ'],"box_design"=>"plain");

	  return $result;
 } 
 ?>