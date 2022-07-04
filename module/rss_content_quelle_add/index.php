<?php 
session_start();
$path = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($path.'/include/inc_config-data.php');
require_once($path.'/include/inc_basic-functions.php');

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
		
		if($_POST['modus'] == 'new') {

		
			# Gibt es die Quelle schon?
			$query = "SELECT count(*) as anzahl FROM modul_rss_quelle WHERE rss_quelle='".$_POST['module_rss_quelle']."'";
			$resCount = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$iAnzahl = mysqli_fetch_assoc($resCount);
			
			# Quelle noch nicht angelegt
			if($iAnzahl['anzahl'] == 0) {
				// Page Einstellugen Speichern
				$query = "INSERT INTO `modul_rss_quelle` (`title_de`, `rss_quelle`, `rss_cat`) VALUES('".$_POST['module_title']."','".$_POST['module_rss_quelle']."','".$_POST['shop_cat_id']."');";
				$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$iPageID = mysqli_insert_id();
				 
				
				$path = getPathUrl($_SESSION['language'],$iPageID);			
				$strLink = 'https://'.$_SERVER['SERVER_NAME'].'/'.$path;
				@mail("info@tsecurity.de","RSS-Feed Quelle angelegt: '".$_POST['module_title']."'",'LINK: '.$strLink);
				echo $strLink;
				# JB
				#exit;				
			} else {
				echo "Quelle war bereits eingef&uuml;gt";
			}
			
		}	
		
		$path = getPathUrl($_SESSION['language'],$iPageID);			
		$strLink = 'https://'.$_SERVER['SERVER_NAME'].'/'.$path;
		
		// Email verschicken 
		$path = realpath($_SERVER["DOCUMENT_ROOT"]);
		require_once $path.'/framework/phpmailer/PHPMailerAutoload.php';

		$html = 'Team Security Systemnachricht, <br/>
		Neue RSS-Feed Nachrichten Seite angelegt.<br/>
		<br/>
		<strong>Seite: </strong><a href="'.$strLink.'">'.$_POST['module_title'].'</a><br/>';
		
		//Create a new PHPMailer instance
		$mail = new PHPMailer();
		// Set PHPMailer to use the sendmail transport
		$mail->isSendmail();
		//Set who the message is to be sent from
		$mail->setFrom('info@tsecurity.de', 'Team Security');
		//Set an alternative reply-to address
		$mail->addReplyTo('info@tsecurity.de', 'Team Security');
		//Set who the message is to be sent to
		$mail->addAddress("info@tsecurity.de","Team Security");
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
######################################
# >> RSS Qzellen hinzufügen Init Modul 
######################################
function LoadModul_rss_content_quelle_add($config) {

		$dataTextHTML = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM modul_rss_content_quelle_add WHERE id=".$config['modul_id']));
		 $strButtonName = 'RSS-Quelle anlegen';
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='rss_content_quelle_add'"));
 
		$dataTextHTML['typ'] = 'rss_content_quelle_add';
		
		$text = '<div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
		
		$text .= $dataTextHTML["content_".$_SESSION['language']];
   
		$strOptMenueSelekt = rss_category(0,0,'',0,0,'select',$_SESSION['system_shop_last_cat']);
		$strOptMenueSelekt2 = menue_generator(0,0,'',0,0,'select',$_SESSION['page_id']);
	
		$text .='
<a name="rss_adding"></a><div id="acp_main_new_page_form">
	<h2>RSS Feed Quelle einfügen (Link Katalog) [Webseiten,Blogs,Quellen]</h2>
	<form name="frmModulAdd" id="rss_content_add" action="/ACP/acp_rss_quelle.php" method="POST" onSubmit="return rss_save_form(\'rss_content_add\');">
				<strong>Information über Youtube RSS Feeds deiner Lieblingsinterpreten</strong><br/>
	Profilname: https://www.youtube.com/feeds/videos.xml?user=Username<br/>
Paylist: https://www.youtube.com/feeds/videos.xml?playlist_id=PlaylistID<br/>
ChannelID https://www.youtube.com/feeds/videos.xml?channel_id=ChannelID<br/><br/>
			<div class="label" style="float:left;">Einsender / Nickname</div>
			<div id="shop_path">
				<input type="text" id="modul_einsender" name="modul_einsender" value=""/>
			</div>	
			<div class="label" style="float:left;">&Uuml;bergeordnete RSS-Feed Kategorie</div>
			<div id="shop_path">
				<select name="shop_cat_id" size="1">'.$strOptMenueSelekt.'
				</select>
			</div>				
				<div class="label" style="float:left;">
					<input type="hidden" id="acp_get_modul_name" name="optModul" value="rss_content"/>
					<input type="hidden" id="acp_get_modus" name="modus" value="new"/>
					<input type="hidden" id="acp_get_modul_id" name="module_id" value="'.$_GET['id'].'"/>';
					 
					if($_GET['modus'] == 'new') {
				 
						$text .='<input type="hidden" id="acp_get_page_id" name="page_id" value="'.$_SESSION['page_id'].'"/>';
				 
					}
					 
				$text .='</div>			
				<div style="clear:both"></div>				
				<div class="label" style="float:left;">&Uuml;berschrift</div>
				<div id="module_title">
					<input type="text" name="module_title" value="'.$dataMenue['title_de'].'">
				</div>
				<div style="clear:both"></div>
				<div class="label" style="float:left;">RSS-Feed Quell URL</div>
				<div id="module_rss_quelle">
					<input type="text" name="module_rss_quelle" value="'.$dataMenue['rss_quelle'].'">
				</div>
				<div style="clear:both"></div>				
				<div id="module_submit"><br/>
					<input type="submit" class="button" name="module_submit" value="'.$strButtonName.'">
				</div>		
			</form>';
		
		$text .= '</div><br></div></div>'; // config modus 

		
	  $result = array("title"=>$dataTextHTML["title_".$_SESSION['language']],"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ'],"box_design"=>"plain");

	  return $result;
 } 
 ?>