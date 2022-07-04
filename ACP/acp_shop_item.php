<?php

	session_start();
	#header('Content-Type: text/html; charset=UTF-8');
	// Datenbankverbindung
	require_once('../include/inc_config-data.php');
	require_once('../include/inc_basic-functions.php');
	require_once('../include/inc_thumbnails.php');
	
	# Facebook API SDK 
	set_include_path("/var/www/vhosts/shopste.com/httpdocs/framework/Facebook/");

	require_once('../framework/Facebook/HttpClients/FacebookHttpable.php');
	require_once('../framework/Facebook/HttpClients/FacebookCurl.php');
	require_once('../framework/Facebook/HttpClients/FacebookCurlHttpClient.php');
	require_once('../framework/Facebook/FacebookSession.php');
	require_once('../framework/Facebook/FacebookRedirectLoginHelper.php');
	require_once('../framework/Facebook/FacebookRequest.php');
	require_once('../framework/Facebook/FacebookResponse.php');
	require_once('../framework/Facebook/FacebookSDKException.php');
	require_once('../framework/Facebook/FacebookRequestException.php');
	require_once('../framework/Facebook/FacebookOtherException.php');
	require_once('../framework/Facebook/FacebookAuthorizationException.php');
	require_once('../framework/Facebook/GraphObject.php');
	require_once('../framework/Facebook/GraphSessionInfo.php');
	require_once('../framework/Facebook/GraphUser.php');
	require_once( '../framework/Facebook/Entities/AccessToken.php' );
	require_once( '../framework/Facebook/Entities/SignedRequest.php' );
	require_once( '../framework/Facebook/FacebookPermissionException.php' );

	use Facebook\HttpClients\FacebookHttpable;
	use Facebook\HttpClients\FacebookCurl;
	use Facebook\HttpClients\FacebookCurlHttpClient;
	use Facebook\FacebookSession;
	use Facebook\FacebookRedirectLoginHelper;
	use Facebook\FacebookRequest;
	use Facebook\FacebookResponse;
	use Facebook\FacebookSDKException;
	use Facebook\FacebookRequestException;
	use Facebook\FacebookOtherException;
	use Facebook\FacebookAuthorizationException;
	use Facebook\GraphObject;
	use Facebook\GraphSessionInfo;
	use Facebook\GraphUser;
	use Facebook\Entities\AccessToken;
	use Facebook\Entities\SignedRequest;
	
	# Injection FIX 
 	$_POST = mysql_real_escape_array($_POST);
	$_GET = mysql_real_escape_array($_GET);
	
	// Login überprüfen
	$chkCookie = admin_cookie_check();

	#echo $chkCookie;
	if($_SESSION['login'] == 1) {	
		$_SESSION['login'] = 1;
	} else {
		echo "KEIN LOGIN";
		exit(0);
	}

// display on completion of upload:
if ($_POST['UPLOAD_IDENTIFIER']) {

	// Login überprüfen
	$chkCookie = admin_cookie_check();

	#echo $chkCookie;
	if($chkCookie == 1) {		
		$_SESSION['login'] = 1;
		$_SESSION['login'] = 1;
	} else {
		echo "KEIN LOGIN";
		exit(0);
	}
 
	#echo "<pre>";
	#print_r($_FILES);
	#echo "</pre>";
	
	$uploads_dir = 'media';
	
	#mkdir('/media/shop', 0777, true);
	
	#echo $_FILES['file1']['tmp_name'];
	#move_uploaded_file($_FILES['file1']['tmp_name'],"../media/shop/".$_FILES['file1']['name']);
	$path = realpath($_SERVER["DOCUMENT_ROOT"]);
	if(file_exists($path."/portals/".$_SERVER['SERVER_NAME']."image/") == false) {
		mkdir($path."/portals/".$_SERVER['SERVER_NAME']."image/",0777);
	}
	if(file_exists($path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/") == false) {
		mkdir($path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/",0777);
	}
	if(file_exists($path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/orginal/") == false) {
		mkdir($path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/orginal/",0777);
	}
	if(file_exists($path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/kategorie/") == false) {
		mkdir($path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/kategorie/",0777);
	}
	
	#echo $_FILES['file1']['tmp_name'];
	#Bild verschieben
	move_uploaded_file($_FILES['file1']['tmp_name'],$path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/orginal/".$_FILES['file1']['name']);
	

	#exit(0);
#}	
	exit;
}
#$_GET = mysql_real_escape_array($_GET);
#$_POST = mysql_real_escape_array($_POST);
	if(isset($_POST['shop_item_price'])) {	
		
		
		# Formatierung Prozent
		$_POST['shop_item_mwst'] = str_replace(",",".",$_POST['shop_item_mwst']);
		$_POST['shop_item_price'] = str_replace(",",".",$_POST['shop_item_price']);
		$_SESSION['page_layout'] = $_POST['page_layout'];
		
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
		
		#############################################
		# Shop Produkt anlegen
		#############################################
		if($_POST['modus'] == 'new') {
		
			$strArtikelModus = 'hinzugef&uuml;gt';
			# Die Seiten ID der Kategorie ermitteln
			$query ="SELECT * FROM shop_category WHERE shop_cat_id = '".$_POST['shop_cat_id']."'";
			#echo $query;
			$resCatTbl = DBi::$conn->query($query);
			$strMenueTbl = mysqli_fetch_assoc($resCatTbl);
		
			if(empty($_POST['shop_page_sort'])) {
				$_POST['shop_page_sort'] = 0;
			}
		
			// Page Einstellugen Speichern
			$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id,created_at,template_file) VALUES ('".$_POST['shop_item_name']."', '".$_POST['shop_page_titel']."', '".$_POST['shop_page_sort']."', 'unsichtbar', '".$_POST['page_layout']."','".$_SESSION['domain_id']."','".date("Y-m-d H:i:s")."','".$_POST['page_template_layout']."')";
			#echo $query;
			$resInsert = DBi::$conn->query($query) or die(mysqli_error());
			$iPageID = mysqli_insert_id(DBi::$conn);
			
			#$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID.", ".$_POST['page_menue_id'].");";
			# Wird automatisch in dem "Pfad" des Menü gespeichert (übergeordnet ist die Kategorie id)
			$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID.", ".$strMenueTbl['page_id'].");";
			#echo $query;
			$resInsert = DBi::$conn->query($query) or die(mysqli_error());
			
			if(empty($_POST['shop_versandkosten'])) {
				$_POST['shop_versandkosten'] = 0;
			}
			
			$query = "INSERT INTO `shop_item` (`name_de`, `preis`, `shop_cat_id`, `menue_id`,menge,beschreibung,gewicht,item_number,domain_id,status_de,system_closed_shop,item_enabled,created_at,item_mwst,lieferzeit,ean,versandkosten) VALUES ('".$_POST['shop_item_name']."', '".$_POST['shop_item_price']."', '".$_POST['shop_cat_id']."', '".$iPageID."', '".$_POST['shop_item_menge']."', '".$_POST['shop_item_beschreibung']."', '".$_POST['shop_item_gewicht']."','".$_POST['shop_item_artnummer']."','".$_SESSION['domain_id']."','verkaufsbereit','N','".$_POST['shop_item_enable']."','".date("Y-m-d H:i:s")."','".$_POST['shop_item_mwst']."','".$_POST['shop_item_lieferzeit']."','".$_POST['shop_ean']."','".str_replace(',','.',$_POST['shop_versandkosten'])."');";
			#echo $query;
			$resInsert = DBi::$conn->query($query) or die(mysqli_error());
			$shop_item_id = mysqli_insert_id(DBi::$conn); 
			echo $shop_item_id; 
			$strTweetText = $_POST['shop_item_name'];
			// Modul Einstellugen Speichern
			$query = "INSERT INTO `modul_".$_POST['optModul']."` (`title_de`, `menue_id`, `last_usr`,shop_item_id) VALUES ('".$_POST['shop_page_titel']."', ".$iPageID.", 0,'".$shop_item_id."');";
			$resInsert = DBi::$conn->query($query) or die(mysqli_error());
			$iModulID = mysqli_insert_id(DBi::$conn);
		
			// Modul auf einer Seite bekannt machen
			if(empty($_POST['module_position'])) {
				$_POST['module_position'] = 0;
			}
			
			$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", '".$_POST['optModul']."', 'col-main', '".$_POST['module_position']."');";
			$resInsert = DBi::$conn->query($query) or die(mysqli_error());
			
			# Übergeordnete Kategorie holen
			$query ="SELECT * FROM menue_parent WHERE menue_id='".$strMenueTbl['page_id']."'";
			$resModMenue = DBi::$conn->query($query) or die('ERR:008-0:'.mysqli_error());
			$strModMenue = mysqli_fetch_assoc($resModMenue);
				
			// Modul Einstellugen Speichern
			$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('".$_POST['shop_page_titel']."', ".$strModMenue['parent_id'].", 0, 'submenue', 'Y');";
			$resInsert = DBi::$conn->query($query) or die(mysqli_error());
			$iModulID = mysqli_insert_id(DBi::$conn);
		
			// Modul auf einer Seite bekannt machen
			if(empty($_POST['module_position'])) {
				$_POST['module_position'] = 0;
			}
			
			$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'menue', '".$strModuleColum."', '".$_POST['module_position']."');";
	
			$resInsert = DBi::$conn->query($query) or die(mysqli_error());
	 
			$query = "SELECT * FROM shop_item JOIN shop_info ON shop_item.domain_id = shop_info.domain_id WHERE shop_item.shop_item_id='".$shop_item_id."'";
			$resShopInfo = DBi::$conn->query($query) or die(mysqli_error());
			$ShopInfoData = mysqli_fetch_assoc($resShopInfo);
			
			// Attribute abspeichern
			if($_POST['hasAdditionalValues'] == true) {
				foreach ($_POST as $key => $value) {
					#echo strpos("additional_",$key).'<br/>';
					
					if(strpos($key,"additional_") === false) {
					} else {
						$query ="INSERT INTO shop_item_additional(shop_item_id,shop_item_additional_types_id,domain_id) VALUES('".$shop_item_id."','".$value."','".$_SESSION['domain_id']."')";		
						#echo $query;
						DBi::$conn->query($query) or die(mysqli_error());
					}
				}
			}
			
			$page_id_subshop = $iPageID;
			# Neue Menü Seite auf Shopste.com anlegen
			if($ShopInfoData['shopste_marktplatz_menue_id'] == 0 AND $_POST['marktplatz_shop_category'] != 'KEINE-AUSWAHL') {
				
				# Die Seiten ID der Kategorie ermitteln
				$query ="SELECT * FROM shop_category WHERE shop_cat_id = '".$_POST['marktplatz_shop_category']."'";
				$resCatTbl = DBi::$conn->query($query);
				$strMenueTbl = mysqli_fetch_assoc($resCatTbl);
				
				if(empty($_POST['shop_page_sort'])) {
					$_POST['shop_page_sort'] = 0;
				}
				
				$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id,content_type,created_at,template_file) VALUES ('".$_POST['shop_item_name']."', '".$_POST['shop_page_titel']."', '".$_POST['shop_page_sort']."', 'produktunsichtbar', '".$_POST['page_layout']."','".$_SESSION['domain_id']."','produktdetailseite','".date("Y-m-d H:i:s")."','".$_POST['page_template_layout']."');";
				$resInsert = DBi::$conn->query($query) or die('0001:'.mysqli_error());
				$iPageID_shopste = mysqli_insert_id(DBi::$conn);
				
				#$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID.", ".$_POST['marktplatz_shop_category_path'].");";
				$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID_shopste.", ".$strMenueTbl['page_id'].");";
				#echo $query;
				$resInsert = DBi::$conn->query($query) or die('0002:'.mysqli_error());
				
				// Modul Einstellugen Speichern
				$query = "INSERT INTO `modul_portal_shop_item_detail` (`title_de`, `menue_id`, `last_usr`,shop_item_id) VALUES ('".$_POST['shop_page_titel']."', ".$iPageID_shopste.", 0,'".$shop_item_id."');";
				$resInsert = DBi::$conn->query($query) or die('0003:'.mysqli_error());
				$iModulID = mysqli_insert_id(DBi::$conn);
			
				// Modul auf einer Seite bekannt machen
				if(empty($_POST['module_position'])) {
					$_POST['module_position'] = 0;
				}
				$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID_shopste.", ".$iModulID.", 'portal_shop_item_detail', 'col-main', '".$_POST['module_position']."');";
				$resInsert = DBi::$conn->query($query) or die('0004:'.mysqli_error());
				
				# Übergeordnete Kategorie holen
				$query ="SELECT * FROM menue_parent WHERE menue_id='".$strMenueTbl['page_id']."'";
				$resModMenue = DBi::$conn->query($query) or die('ERR:008-0:'.mysqli_error());
				$strModMenue = mysqli_fetch_assoc($resModMenue);
				
				// Modul Einstellugen Speichern
				$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('', ".$strModMenue['parent_id'].", 0, 'submenue', 'Y');";
				$resInsert = DBi::$conn->query($query) or die('0005:'.mysqli_error());
				$iModulID = mysqli_insert_id(DBi::$conn);
			
				// Modul auf einer Seite bekannt machen
				if(empty($_POST['module_position'])) {
					$_POST['module_position'] = 0;
				}
				$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID_shopste.", ".$iModulID.", 'menue', '".$strModuleColum."', '".$_POST['module_position']."');";
				$resInsert = DBi::$conn->query($query) or die('0006:'.mysqli_error);
				$strSubjectAdd = ' | Shopste.com';
				
			}
			
			# In Marktplatz einstellen nur wenn Shopste.com angelegt wurde
			#echo $iPageID_shopste;
			if($iPageID_shopste != '') {
				$query = "UPDATE shop_item SET shopste_marktplatz_cat='".$_POST['marktplatz_shop_category']."',shopste_marktplatz_menue_id='".$iPageID_shopste."' WHERE shop_item_id='".$shop_item_id."'";
				DBi::$conn->query($query) or die(mysqli_error());
			}
			
			
			$path = realpath($_SERVER["DOCUMENT_ROOT"]);
			require_once $path.'/framework/phpmailer/PHPMailerAutoload.php';
			
			$query = "SELECT * FROM domains WHERE domain_id='".$ShopInfoData['domain_id']."'";
			$resDomain = DBi::$conn->query($query) or die(mysqli_error());
			$strDomain = mysqli_fetch_assoc($resDomain);			
			
			$query = "SELECT * FROM shop_item WHERE shop_item_id ='".$shop_item_id."' AND domain_id='".$_GET['domain_id']."'";
			#echo $query;
			$resItem = DBi::$conn->query($query) or die(mysqli_error());
			$strItem = mysqli_fetch_assoc($resItem);
			
			$query ="SELECT count(*) as anzahl FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_ARTIKEL_ADD'";
			#echo $query;
			$resEmailCount = DBi::$conn->query($query) or die(mysqli_error());
			$strEmailCount = mysqli_fetch_assoc($resEmailCount);
			if($strEmailCount['anzahl'] > 0) {
				# Lade Benutzervorlage
				$query ="SELECT * FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_ARTIKEL_ADD'";
				#echo $query;
				$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error());
				$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
			} else {
				# Lade Defaultvorlage
				$query ="SELECT * FROM email_vorlage WHERE domain_id='0' AND standard='Y' AND typ='CORE_ARTIKEL_ADD'";
				#echo $query;
				$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error());
				$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
			}
			
		 
			$strURL_shop = getPathUrl($_SESSION['language'],$ShopInfoData['menue_id']);
			
 
			
			$html = $strEmailVorlage['content'];
			
			$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$ShopInfoData['shop_item_id']."'";
			$resPicture = DBi::$conn->query($query) or die(mysqli_error());
			$strPicture = mysqli_fetch_assoc($resPicture);
			$path = realpath($_SERVER["DOCUMENT_ROOT"]);
			
			$html = str_replace("###SHOP_ARTIKEL_BILD###",'<img src="http://shopste.com'.$strPicture['picture_url'].'"/>',$html);
			$html = str_replace("###SHOP_ARTIKEL_STATUS###",$strArtikelModus,$html);
			$html = str_replace('###SHOP_MITGLIEDSNAME###',	$ShopInfoData['shop_mitgliedsname'],$html);
			$html = str_replace('###SHOP_ARTIKEL_NAME###',	$ShopInfoData['name_de'],$html);
			$html = str_replace('###SHOP_ARTIKEL_MENGE###',$ShopInfoData['menge'],$html);
			$html = str_replace('###SHOP_ARTIKEL_GEWICHT###',$ShopInfoData['gewicht'],$html);
			$html = str_replace('###SHOP_ARTIKEL_PREIS###',number_format($ShopInfoData['preis'], 2, ",", "."),$html);
			$html = str_replace('###SHOP_ARTIKEL_BESCHREIBUNG###',$ShopInfoData['beschreibung'],$html);
			$html = str_replace('###SHOP_ARTIKEL_GEWICHT###',$ShopInfoData['gewicht'],$html);
			$html = str_replace('###SHOP_ARTIKEL_NUMMER###',$ShopInfoData['item_number'],$html);
			$html = str_replace('###SHOP_ARTIKEL_MWST###',$ShopInfoData['item_mwst'],$html);
			$html = str_replace('###SHOP_ARTIKEL_LINK###','http://'.$strDomain['name'].'/'.$strURL_shop.'/',$html);
			$html = str_replace('###SHOP_ADMIN_LOGIN###','http://'.$strDomain['name'].'/admin/',$html);
			$html = str_replace("###SHOP_ADD_TYPE###",$strSubjectAdd,$html);
			
			# Nur wenn Shopste.com aktiv 
			if($iPageID_shopste != '') {
				####SHOP_ARTIKEL_LINK_Marktplatz###
				
				$strURL_shop = getPathUrl($_SESSION['language'],$iPageID_shopste);
				
				$html = str_replace('###SHOP_ARTIKEL_MAKRTPLATZ_LINK###','Marktplatz: <a href="'.CORE_SERVER_DOMAIN.$strURL_shop.'">###SHOP_ARTIKEL_NAME###</a>',$html);
				$html = str_replace('###SHOP_ARTIKEL_NAME###',	$ShopInfoData['name_de'],$html);
			} else {
				$html =  str_replace('###SHOP_ARTIKEL_MAKRTPLATZ_LINK###','',$html);
			}

 
			
			//Create a new PHPMailer instance
			$mail = new PHPMailer();
			$mail -> charSet = "UTF-8";
			// Set PHPMailer to use the sendmail transport
			$mail->isSendmail();
			$mail->setFrom(CORE_MAIL_FROM_EMAIL,CORE_MAIL_FROM_EMAIL_NAME);
			//Set an alternative reply-to address
			$mail->addReplyTo(CORE_MAIL_FROM_EMAIL,CORE_MAIL_FROM_EMAIL_NAME);
			//Set who the message is to be sent to
			$mail->addAddress($ShopInfoData['email_shop_main'],$ShopInfoData['email_shop_main_form_name']);
			
			//Set who the message is to be sent to
			$mail->AddBCC(CORE_MAIL_SEND_BCC,CORE_MAIL_SEND_BCC_NAME);
			//Set the subject line
			
			#'Shopste ###SHOP_ARTIKEL_NAME###: Artikel neu (Shop ###SHOP_ADD_TYPE###) eingestellt: ###SHOP_ARTIKEL_NAME###
			
			$betreff = $strEmailVorlage['betreff'];
			
			$betreff = str_replace("###SHOP_ARTIKEL_STATUS###",$strArtikelModus,$betreff);
			$betreff = str_replace("###SHOP_ARTIKEL_NAME###",$ShopInfoData['name_de'],$betreff);
			$betreff = str_replace("###SHOP_ADD_IMPORT###",$strImportiert,$betreff);
			$betreff = str_replace("###SHOP_ARTIKEL_PREIS###",number_format($ShopInfoData['preis'], 2, ",", "."),$betreff);
			$betreff = str_replace("###SHOP_ADD_TYPE###",$strSubjectAdd,$betreff);
				
			$mail->Subject = utf8_decode(html_entity_decode($betreff));
			//Read an HTML message body from an external file, convert referenced images to embedded,
			//convert HTML into a basic plain-text alternative body
			$mail->msgHTML($html, dirname(__FILE__));
			//Replace the plain text body with one created manually
			$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';
			//Attach an image file
			//$mail->addAttachment('images/phpmailer_mini.png');
			$_SESSION['get_shop_menue'] = $_POST['marktplatz_shop_category'];
			$_SESSION['get_shop_cat'] = $_POST['marktplatz_shop_category_path'];
			$_SESSION['get_page_menue'] = $_POST['page_menue_id'];
			$_SESSION['get_page_cat'] = $_POST['shop_cat_id'];
			
			//$_SESSION['acp_imported_ids'] = '';
			//send the message, check for errors
			if (!$mail->send()) {
				echo "Mailer Error: " . $mail->ErrorInfo;
			} else {
				#echo "Artikel neu angelegt!";
			}	
			
			if(twitter_shop_item_post == 'Y') {
				// Tweet absetzten
				$path = getPathUrl($_SESSION['language'],$iPageID);		 		
				$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
				
				if(twitter_rss_category_post == 'Y') {
					try {
						$strTweet_text_tmp = html_entity_decode($strTweetText); 
						if(strlen($strTweet_text_tmp) > 124) {		 
							$iLänge_url = strlen($strLink);
							$iLänge = strlen($strTweetText);
							$strTweet_text_tmp = substr($strTweet_text_tmp,0,124);
							$strTweet_text = $strTweet_text_tmp.' '.$strLink;
						} else {
							$strTweet_text = $strTweet_text_tmp.' '.$strLink;
						}			 
						$connection = post_tweet($strTweet_text);
					} catch (Exception $e) {
						echo 'Exception abgefangen: ',  $e->getMessage(), "\n";
					}
				}
			}
			
			#echo $shop_item_id;
			#$_POST['schritt'] = 'bilder_upload'; 
		} else {
		
			if($_POST['modus'] == 'API-importiert') {
			
				$strArtikelModus = 'API-importiert';
				# Die Seiten ID der Kategorie ermitteln
				$query ="SELECT * FROM shop_category WHERE shop_cat_id = '".$_POST['shop_cat_id']."'";
				$resCatTbl = DBi::$conn->query($query);
				$strMenueTbl = mysqli_fetch_assoc($resCatTbl);
				
				if(empty($_POST['shop_page_sort'])) {
					$_POST['shop_page_sort'] = 0;
				}				
				
				// Page Einstellugen Speichern
				$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id,content_type,created_at,template_file) VALUES ('".$_POST['shop_item_name']."', '".$_POST['shop_page_titel']."', '".$_POST['shop_page_sort']."', 'produktunsichtbar', '".$_POST['page_layout']."','".$_SESSION['domain_id']."','produktdetailseite','".date("Y-m-d H:i:s")."','".$_POST['page_template_layout']."');";
				$resInsert = DBi::$conn->query($query) or die(mysqli_error());
				$iPageID = mysqli_insert_id(DBi::$conn);
				
				$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID.", ".$strMenueTbl['page_id'].");";
				$resInsert = DBi::$conn->query($query) or die(mysqli_error());
				
				// Modul Einstellugen Speichern
				$query = "INSERT INTO `modul_".$_POST['optModul']."` (`title_de`, `menue_id`, `last_usr`,shop_item_id) VALUES ('".$_POST['shop_page_titel']."', ".$iPageID.", 0,'".$_POST['shop_id']."');";
				$resInsert = DBi::$conn->query($query) or die(mysqli_error());
				$iModulID = mysqli_insert_id(DBi::$conn);
			
				// Modul auf einer Seite bekannt machen
				if(empty($_POST['module_position'])) {
					$_POST['module_position'] = 0;
				}
				$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", '".$_POST['optModul']."', 'col-main', '".$_POST['module_position']."');";
				$resInsert = DBi::$conn->query($query) or die(mysqli_error());
				
				# Übergeordnete Kategorie holen
				$query ="SELECT * FROM menue_parent WHERE menue_id='".$strMenueTbl['page_id']."'";
				$resModMenue = DBi::$conn->query($query) or die('ERR:008-0:'.mysqli_error());
				$strModMenue = mysqli_fetch_assoc($resModMenue);
					
				// Modul Einstellugen Speichern
				$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('', ".$strModMenue['parent_id'].", 0, 'submenue', 'Y');";
				$resInsert = DBi::$conn->query($query) or die(mysqli_error());
				$iModulID = mysqli_insert_id(DBi::$conn);
			
				// Modul auf einer Seite bekannt machen
				if(empty($_POST['module_position'])) {
					$_POST['module_position'] =0;
				}
				$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'menue', '".$strModuleColum."', '".$_POST['module_position']."');";
		
				$resInsert = DBi::$conn->query($query) or die(mysqli_error);
				
				$query = "UPDATE shop_item SET name_de='".$_POST['shop_item_name']."',preis='".$_POST['shop_item_price']."',shop_cat_id='".$_POST['shop_cat_id']."',menge='".$_POST['shop_item_menge']."',beschreibung='".$_POST['shop_item_beschreibung']."',gewicht='".$_POST['shop_item_gewicht']."',item_number='".$_POST['shop_item_artnummer']."',status_de='verkaufsbereit',menue_id='".$iPageID."',item_enabled='".$_POST['shop_item_enable']."',lieferzeit='".$_POST['shop_item_lieferzeit']."', item_mwst='".$_POST['shop_item_mwst']."' WHERE shop_item_id='".$_POST['shop_id']."'";
				DBi::$conn->query($query) or die(mysqli_error());
				
			// Attribute abspeichern
			if($_POST['hasAdditionalValues'] == true) {
				foreach ($_POST as $key => $value) {
					#echo strpos("additional_",$key).'<br/>';
					
					if(strpos($key,"additional_") === false) {
					} else {
						$query ="INSERT INTO shop_item_additional(shop_item_id,shop_item_additional_types_id,domain_id) VALUES('".$_POST['shop_id']."','".$value."','".$_SESSION['domain_id']."')";		
						#echo $query;
						DBi::$conn->query($query) or die(mysqli_error());
					}
				}
			}
			
				#############################
				# >> Marktplatz einstellen 
				#############################
				#$_POST['marktplatz_shop_category']
				#$_POST['marktplatz_shop_category_path'];
				$page_id_subshop = $iPageID; 
				if($_POST['marktplatz_shop_category'] != 'KEINE-AUSWAHL') {
						
					# Die Seiten ID der Kategorie ermitteln
					$query ="SELECT * FROM shop_category WHERE shop_cat_id = '".$_POST['marktplatz_shop_category']."'";
					$resCatTbl = DBi::$conn->query($query);
					$strMenueTbl = mysqli_fetch_assoc($resCatTbl);
					
					if(empty($_POST['shop_page_sort'])) {
						$_POST['shop_page_sort'] = 0;
					}					
					
					$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id,content_type,template_file,created_at) VALUES ('".$_POST['shop_item_name']."', '".$_POST['shop_page_titel']."', '".$_POST['shop_page_sort']."', 'produktunsichtbar', '".$_POST['page_layout']."','".$_SESSION['domain_id']."','produktdetailseite','".$_POST['page_template_layout']."','".date("Y-m-d H:i:s")."');";
					$resInsert = DBi::$conn->query($query) or die('ERR:0001'.mysqli_error());
					$iPageID_shopste = mysqli_insert_id(DBi::$conn);
					
					$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID_shopste.", ".$strMenueTbl['page_id'].");";
					$resInsert = DBi::$conn->query($query) or die('ERR:0002'.mysqli_error());
					
					// Modul Einstellugen Speichern
					$query = "INSERT INTO `modul_portal_shop_item_detail` (`title_de`, `menue_id`, `last_usr`,shop_item_id) VALUES ('".$_POST['shop_page_titel']."', ".$iPageID_shopste.", 0,'".$_POST['shop_id']."');";
					$resInsert = DBi::$conn->query($query) or die('ERR:0003'.mysqli_error());
					$iModulID = mysqli_insert_id(DBi::$conn);
				
					// Modul auf einer Seite bekannt machen
					if(empty($_POST['module_position'])) {
						$_POST['module_position'] =0;
					}
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID_shopste.", ".$iModulID.", 'portal_shop_item_detail', 'col-main', '".$_POST['module_position']."');";
					$resInsert = DBi::$conn->query($query) or die('ERR:0004'.mysqli_error());
					
					# Übergeordnete Kategorie holen
					$query ="SELECT * FROM menue_parent WHERE menue_id='".$strMenueTbl['page_id']."'";
					$resModMenue = DBi::$conn->query($query) or die('ERR:008:'.mysqli_error());
					$strModMenue = mysqli_fetch_assoc($resModMenue);
					
					// Modul Einstellugen Speichern
					$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('', ".$strModMenue['parent_id'].", 0, 'submenue', 'Y');";
					#echo $query;
					$resInsert = DBi::$conn->query($query) or die('ERR:0005'.mysqli_error());
					$iModulID = mysqli_insert_id(DBi::$conn);
				
					// Modul auf einer Seite bekannt machen
					if(empty($_POST['module_position'])) {
						$_POST['module_position'] = 0;
					}
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID_shopste.", ".$iModulID.", 'menue', '".$strModuleColum."', '".$_POST['module_position']."');";
					$resInsert = DBi::$conn->query($query) or die('ERR:0006'.mysqli_error);
					
					$query = "UPDATE shop_item SET shopste_marktplatz_cat='".$_POST['marktplatz_shop_category']."',shopste_marktplatz_menue_id='".$iPageID_shopste."' WHERE shop_item_id='".$_POST['shop_id']."'";
					#echo $query;
					DBi::$conn->query($query) or die(mysqli_error());
					$strSubjectAdd = ' | Shopste.com';
				}
				
				// ################################################
				// # Email Rausschicken - Artikel eingestellt
				// ################################################
				$query = "SELECT * FROM shop_item JOIN shop_info ON shop_item.domain_id = shop_info.domain_id WHERE shop_item.shop_item_id='".$_POST['shop_id']."'";
				$resShopInfo = DBi::$conn->query($query) or die(mysqli_error());
				$ShopInfoData = mysqli_fetch_assoc($resShopInfo);
				
				$path = realpath($_SERVER["DOCUMENT_ROOT"]);
				require_once $path.'/framework/phpmailer/PHPMailerAutoload.php';
				
				$query = "SELECT * FROM domains WHERE domain_id='".$ShopInfoData['domain_id']."'";
				$resDomain = DBi::$conn->query($query) or die('ERR:0007'.mysqli_error());
				$strDomain = mysqli_fetch_assoc($resDomain);
				
				$query ="SELECT count(*) as anzahl FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_ARTIKEL_ADD'";
				#echo $query;
				$resEmailCount = DBi::$conn->query($query) or die(mysqli_error());
				$strEmailCount = mysqli_fetch_assoc($resEmailCount);
				if($strEmailCount['anzahl'] > 0) {
					# Lade Benutzervorlage
					$query ="SELECT * FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_ARTIKEL_ADD'";
					#echo $query;
					$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error());
					$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
				} else {
					# Lade Defaultvorlage
					$query ="SELECT * FROM email_vorlage WHERE domain_id='0' AND standard='Y' AND typ='CORE_ARTIKEL_ADD'";
					#echo $query;
					$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error());
					$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
				}
				
				$strURL_shop = getPathUrl($_SESSION['language'],$ShopInfoData['menue_id']);
				
				$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$ShopInfoData['shop_item_id']."'";
				$resPicture = DBi::$conn->query($query) or die(mysqli_error());
				$strPicture = mysqli_fetch_assoc($resPicture);
				$path = realpath($_SERVER["DOCUMENT_ROOT"]);
			
				$html = $strEmailVorlage['content'];
				$html = str_replace("###SHOP_ARTIKEL_BILD###",'<img src="http://shopste.com'.$strPicture['picture_url'].'"/>',$html);
				$html = str_replace('###SHOP_MITGLIEDSNAME###',	$ShopInfoData['shop_mitgliedsname'],$html);
				$html = str_replace("###SHOP_ARTIKEL_STATUS###",$strArtikelModus,$html);
				$html = str_replace('###SHOP_ARTIKEL_NAME###',	$ShopInfoData['name_de'],$html);
				$html = str_replace('###SHOP_ARTIKEL_MENGE###',$ShopInfoData['menge'],$html);
				$html = str_replace('###SHOP_ARTIKEL_GEWICHT###',$ShopInfoData['gewicht'],$html);
				$html = str_replace('###SHOP_ARTIKEL_PREIS###',number_format($ShopInfoData['preis'], 2, ",", "."),$html);
				$html = str_replace('###SHOP_ARTIKEL_BESCHREIBUNG###',$ShopInfoData['beschreibung'],$html);
				$html = str_replace('###SHOP_ARTIKEL_GEWICHT###',$ShopInfoData['gewicht'],$html);
				$html = str_replace('###SHOP_ARTIKEL_NUMMER###',$ShopInfoData['item_number'],$html);
				$html = str_replace('###SHOP_ARTIKEL_MWST###',$ShopInfoData['item_mwst'],$html);
				$html = str_replace('###SHOP_ADMIN_LOGIN###','http://'.$strDomain['name'].'/admin/',$html);
				$html = str_replace('###SHOP_ARTIKEL_LINK###','http://'.$strDomain['name'].'/'.$strURL_shop.'/',$html);
				$html = str_replace("###SHOP_ADD_TYPE###",$strSubjectAdd,$html);
				
				# Nur wenn Shopste.com aktiv 
				if($iPageID_shopste != '') {
					####SHOP_ARTIKEL_LINK_Marktplatz###
					
					$strURL_shop = getPathUrl($_SESSION['language'],$iPageID_shopste);
					
					$html = str_replace('###SHOP_ARTIKEL_MAKRTPLATZ_LINK###','Marktplatz: <a href="'.CORE_SERVER_DOMAIN.$strURL_shop.'">###SHOP_ARTIKEL_NAME###</a>',$html);
					$html = str_replace('###SHOP_ARTIKEL_NAME###',	$ShopInfoData['name_de'],$html);
				} else {
					$html =  str_replace('###SHOP_ARTIKEL_MAKRTPLATZ_LINK###','',$html);
				}

	 
				
				//Create a new PHPMailer instance
				$mail = new PHPMailer();
				$mail -> charSet = "UTF-8";
				// Set PHPMailer to use the sendmail transport
				$mail->isSendmail();
				$mail->setFrom(CORE_MAIL_FROM_EMAIL,CORE_MAIL_FROM_EMAIL_NAME);
				//Set an alternative reply-to address
				$mail->addReplyTo(CORE_MAIL_FROM_EMAIL,CORE_MAIL_FROM_EMAIL_NAME);
				//Set who the message is to be sent to
				$mail->addAddress($ShopInfoData['email_shop_main'],$ShopInfoData['email_shop_main_form_name']);
				
				//Set who the message is to be sent to
				$mail->AddBCC(CORE_MAIL_SEND_BCC,CORE_MAIL_SEND_BCC_NAME);
				//Set the subject line
				
				#'Shopste ###SHOP_ARTIKEL_NAME###: Artikel neu (Shop ###SHOP_ADD_TYPE###) eingestellt: ###SHOP_ARTIKEL_NAME###
				
				$betreff = $strEmailVorlage['betreff'];
				
				$betreff = str_replace("###SHOP_ARTIKEL_STATUS###",$strArtikelModus,$betreff);
				$betreff = str_replace("###SHOP_ARTIKEL_NAME###",$ShopInfoData['name_de'],$betreff);
				$betreff = str_replace("###SHOP_ADD_IMPORT###",$strImportiert,$betreff);
				$betreff = str_replace("###SHOP_ARTIKEL_PREIS###",number_format($ShopInfoData['preis'], 2, ",", "."),$betreff);
				$betreff = str_replace("###SHOP_ADD_TYPE###",$strSubjectAdd,$betreff);
					
				$mail->Subject = utf8_decode(html_entity_decode($betreff));
				//Read an HTML message body from an external file, convert referenced images to embedded,
				//convert HTML into a basic plain-text alternative body
				$html = utf8_decode($html);
				$mail->msgHTML($html, dirname(__FILE__));
				//Replace the plain text body with one created manually
				$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';
				//Attach an image file
				//$mail->addAttachment('images/phpmailer_mini.png');
				$_SESSION['get_shop_menue'] = $_POST['marktplatz_shop_category'];
				$_SESSION['get_shop_cat'] = $_POST['marktplatz_shop_category_path'];
				$_SESSION['get_page_menue'] = $_POST['page_menue_id'];
				$_SESSION['get_page_cat'] = $_POST['shop_cat_id'];
				
				//$_SESSION['acp_imported_ids'] = '';
				//send the message, check for errors
				if (!$mail->send()) {
					echo "Mailer Error: " . $mail->ErrorInfo;
				} else {
					$strImportiert ="Artikel importiert!";
					echo $strImportiert;
				}	
				exit;
			} else {
			#######################	
			# Shop Artikel EDIT
			#######################
			
			$strArtikelModus = 'aktualisiert';
			
				$query = "SELECT * FROM shop_item WHERE shop_item_id='".$_POST['shop_id']."'";
				$resQuery = DBi::$conn->query($query) or die(mysqli_error());
				$dataShop = mysqli_fetch_assoc($resQuery);
				
				if(empty($_POST['menue_sortierung'])) {
					$_POST['menue_sortierung'] = 0;
				}
				
				$query = "UPDATE `menue` SET `name_de`='".$_POST['page_url_name']."', `titel_de`='".$_POST['page_title']."', sortierung= '".$_POST['menue_sortierung']."',template_file='".$_POST['page_template_layout']."' WHERE  `id`=".$dataShop['menue_id']."; ";
				DBi::$conn->query($query) or die(mysqli_error());
			
				# Die Seiten ID der Kategorie ermitteln
				$query ="SELECT * FROM shop_category WHERE shop_cat_id = '".$_POST['shop_cat_id']."'";
				$resCatTbl = DBi::$conn->query($query);
				$strMenueTbl = mysqli_fetch_assoc($resCatTbl);
				
				#$query ="SELECT * FROM menue_parent WHERE menue_id=".$_POST['page_menue_id'];
				#$resQuery = DBi::$conn->query($query) or die(mysqli_error());
				#$strMenueParrent = mysqli_fetch_assoc($resQuery);
			
				$query = "UPDATE `menue_parent` SET `parent_id`=".$strMenueTbl['page_id']." WHERE  `menue_id`=".$dataShop['menue_id'].";";
				DBi::$conn->query($query) or die(mysqli_error());		
				
				if(empty($_POST['shop_versandkosten'])) {
					$_POST['shop_versandkosten'] = 0;
				}
				
				$query = "UPDATE shop_item SET name_de='".$_POST['shop_item_name']."',preis='".$_POST['shop_item_price']."',shop_cat_id='".$_POST['shop_cat_id']."',menge='".$_POST['shop_item_menge']."',beschreibung='".$_POST['shop_item_beschreibung']."',gewicht='".$_POST['shop_item_gewicht']."',item_number='".$_POST['shop_item_artnummer']."',ean='".$_POST['shop_ean']."',item_enabled='".$_POST['shop_item_enable']."',versandkosten='".str_replace(',','.',$_POST['shop_versandkosten'])."',item_mwst='".$_POST['shop_item_mwst']."' WHERE shop_item_id='".$_POST['shop_id']."'";
				DBi::$conn->query($query) or die(mysqli_error());
				

				
				// ################################################
				// # Email Rausschicken - Artikel eingestellt
				// ################################################
				$query = "SELECT * FROM shop_item JOIN shop_info ON shop_item.domain_id = shop_info.domain_id WHERE shop_item.shop_item_id='".$_POST['shop_id']."'";
				$resShopInfo = DBi::$conn->query($query) or die(mysqli_error());
				$ShopInfoData = mysqli_fetch_assoc($resShopInfo);
				
				$page_id_subshop = $ShopInfoData['menue_id'];
				
								// Attribute abspeichern
				if($_POST['hasAdditionalValues'] == "true") {
					foreach ($_POST as $key => $value) {
						#echo strpos("additional_",$key).'<br/>';
						
						if(strpos($key,"additional_") === false) {
						} else {
							
							# Typ aus dem Array extrahieren
							$strTyp = explode("_",$key);
							
							foreach ($_POST as $key1 => $value1) {
								#echo strpos("additional_",$key).'<br/>';
								
								if(strpos($key1,"old_".$strTyp[2]) === false) {							
									#echo "IN...";
								} else {
									$oldValueWhere = $value1;
									#echo $oldValueWhere;
									break;
								}
								
							}
							
							$query ="UPDATE shop_item_additional SET shop_item_additional_types_id ='".$value."' WHERE shop_item_additional_types_id='".$oldValueWhere."'";
							#echo $query;
							DBi::$conn->query($query) or die(mysqli_error());
						}
					}
				}
				# Neue Menü Seite auf Shopste.com anlegen
				#echo $ShopInfoData['shopste_marktplatz_menue_id'];
				#echo '-'.$_POST['marktplatz_shop_category'];
				if($ShopInfoData['shopste_marktplatz_menue_id'] == 0 && $_POST['marktplatz_shop_category'] != 'KEINE-AUSWAHL') {
					echo "Markplatz Eintrag \n";
					
					if(empty($_POST['shop_page_sort'])) {
						$_POST['shop_page_sort'] = 0;
					}
					
					$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id,content_type,created_at,template_file) VALUES ('".$_POST['shop_item_name']."', '".$_POST['shop_page_titel']."', '".$_POST['shop_page_sort']."', 'produktunsichtbar', '".$_POST['page_layout']."','".$_SESSION['domain_id']."','produktdetailseite','".date("Y-m-d H:i:s")."','".$_POST['page_template_layout']."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error());
					$iPageID_shopste = mysqli_insert_id(DBi::$conn);
					
					# Die Seiten ID der Kategorie ermitteln
					$query ="SELECT * FROM shop_category WHERE shop_cat_id = '".$_POST['marktplatz_shop_category']."'";
					$resCatTbl = DBi::$conn->query($query);
					$strMenueTbl = mysqli_fetch_assoc($resCatTbl);
				
					$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID_shopste.", ".$strMenueTbl['page_id'].");";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error());
					
					// Modul Einstellugen Speichern
					$query = "INSERT INTO `modul_portal_shop_item_detail` (`title_de`, `menue_id`, `last_usr`,shop_item_id) VALUES ('".$_POST['shop_page_titel']."', ".$iPageID_shopste.", 0,'".$_POST['shop_id']."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error());
					$iModulID = mysqli_insert_id(DBi::$conn);
				
					// Modul auf einer Seite bekannt machen
					if(empty($_POST['module_position'])) {
						$_POST['module_position'] =0;
					}
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID_shopste.", ".$iModulID.", 'portal_shop_item_detail', 'col-main', '".$_POST['module_position']."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error());
					
					// Modul Einstellugen Speichern
					$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('', ".$strMenueTbl['page_id'].", 0, 'submenue', 'Y');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error());
					$iModulID = mysqli_insert_id(DBi::$conn);
				
					// Modul auf einer Seite bekannt machen
					if(empty($_POST['module_position'])) {
						$_POST['module_position'] =0;
					}
					$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID_shopste.", ".$iModulID.", 'menue', '".$strModuleColum."', '".$_POST['module_position']."');";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error);
					
					
				
					$strSubjectAdd = '| Shopste.com';
				}
				
				# In Marktplatz einstellen / UPDATE
				if($_POST['marktplatz_shop_category'] != 'KEINE-AUSWAHL') {
					
					if($iPageID_shopste != 0) {
						$strMarketMenue = ",shopste_marktplatz_menue_id='".$iPageID_shopste."'";
						$ShopInfoData['shopste_marktplatz_menue_id'] = $iPageID_shopste;
					}
					$strSubjectAdd = '| Shopste.com';
					# Die Seiten ID der Kategorie ermitteln
					$query ="SELECT * FROM shop_category WHERE shop_cat_id = '".$_POST['marktplatz_shop_category']."'";
					$resCatTbl = DBi::$conn->query($query);
					$strMenueTbl = mysqli_fetch_assoc($resCatTbl);
 
				
					$query2 = "UPDATE `menue_parent` SET `parent_id`=".$strMenueTbl['page_id']." WHERE  `menue_id`=".$ShopInfoData['shopste_marktplatz_menue_id'].";";
					DBi::$conn->query($query2) or die(mysqli_error());		
					
					$query = "UPDATE shop_item SET shopste_marktplatz_cat='".$_POST['marktplatz_shop_category']."' $strMarketMenue WHERE shop_item_id='".$_POST['shop_id']."'";
					
					if($ShopInfoData['shopste_marktplatz_menue_id'] != 0) {
						$iPageID_shopste = $ShopInfoData['shopste_marktplatz_menue_id'];
					}
				} else {
					$iPageID_shopste = $ShopInfoData['shopste_marktplatz_menue_id'];
					$query = "UPDATE shop_item SET shopste_marktplatz_cat='".$_POST['marktplatz_shop_category']."' WHERE shop_item_id='".$_POST['shop_id']."'";
				}
				DBi::$conn->query($query) or die(mysqli_error());
				
				
				$path = realpath($_SERVER["DOCUMENT_ROOT"]);
				require_once $path.'/framework/phpmailer/PHPMailerAutoload.php';
				
				$query = "SELECT * FROM domains WHERE domain_id='".$ShopInfoData['domain_id']."'";
				$resDomain = DBi::$conn->query($query) or die(mysqli_error());
				$strDomain = mysqli_fetch_assoc($resDomain);
				
				$query = "SELECT * FROM domains WHERE domain_id='".$ShopInfoData['domain_id']."'";
			$resDomain = DBi::$conn->query($query) or die(mysqli_error());
			$strDomain = mysqli_fetch_assoc($resDomain);			
			
			$query ="SELECT count(*) as anzahl FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_ARTIKEL_ADD'";
			#echo $query;
			$resEmailCount = DBi::$conn->query($query) or die(mysqli_error());
			$strEmailCount = mysqli_fetch_assoc($resEmailCount);
			if($strEmailCount['anzahl'] > 0) {
				# Lade Benutzervorlage
				$query ="SELECT * FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_ARTIKEL_ADD'";
				#echo $query;
				$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error());
				$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
			} else {
				# Lade Defaultvorlage
				$query ="SELECT * FROM email_vorlage WHERE domain_id='0' AND standard='Y' AND typ='CORE_ARTIKEL_ADD'";
				#echo $query;
				$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error());
				$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
			}
			
		 	$strURL_shop = getPathUrl($_SESSION['language'],$ShopInfoData['menue_id']);
			
			$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$ShopInfoData['shop_item_id']."'";
			$resPicture = DBi::$conn->query($query) or die(mysqli_error());
			$strPicture = mysqli_fetch_assoc($resPicture);
			$path = realpath($_SERVER["DOCUMENT_ROOT"]);
		
			$html = $strEmailVorlage['content'];
			$html = str_replace("###SHOP_ARTIKEL_BILD###",'<img src="http://shopste.com'.$strPicture['picture_url'].'"/>',$html);
			#$html = str_replace("###SHOP_ARTIKEL_MAKRTPLATZ_LINK###",$strArtikelModus,$html);
			$html = str_replace("###SHOP_ARTIKEL_STATUS###",$strArtikelModus,$html);
			$html = str_replace('###SHOP_MITGLIEDSNAME###',	$ShopInfoData['shop_mitgliedsname'],$html);
			$html = str_replace('###SHOP_ARTIKEL_NAME###',	$ShopInfoData['name_de'],$html);
			$html = str_replace('###SHOP_ARTIKEL_MENGE###',$ShopInfoData['menge'],$html);
			$html = str_replace('###SHOP_ARTIKEL_GEWICHT###',$ShopInfoData['gewicht'],$html);
			$html = str_replace('###SHOP_ARTIKEL_PREIS###',number_format($ShopInfoData['preis'], 2, ",", "."),$html);
			$html = str_replace('###SHOP_ARTIKEL_BESCHREIBUNG###',$ShopInfoData['beschreibung'],$html);
			$html = str_replace('###SHOP_ARTIKEL_GEWICHT###',$ShopInfoData['gewicht'],$html);
			$html = str_replace('###SHOP_ARTIKEL_NUMMER###',$ShopInfoData['item_number'],$html);
			$html = str_replace('###SHOP_ARTIKEL_MWST###',$ShopInfoData['item_mwst'],$html);
			$html = str_replace('###SHOP_ADMIN_LOGIN###','http://'.$strDomain['name'].'/admin/',$html);
			$html = str_replace('###SHOP_ARTIKEL_LINK###','http://'.$strDomain['name'].'/'.$strURL_shop.'/',$html);
			$html = str_replace("###SHOP_ADD_TYPE###",$strSubjectAdd,$html);
			
			# Nur wenn Shopste.com aktiv 
			if($iPageID_shopste != '') {
				####SHOP_ARTIKEL_LINK_Marktplatz###
				
				$strURL_shop = getPathUrl($_SESSION['language'],$iPageID_shopste);
				
				$html = str_replace('###SHOP_ARTIKEL_MAKRTPLATZ_LINK###','Marktplatz: <a href="'.CORE_SERVER_DOMAIN.$strURL_shop.'">###SHOP_ARTIKEL_NAME###</a>',$html);
				$html = str_replace('###SHOP_ARTIKEL_NAME###',	$ShopInfoData['name_de'],$html);
			} else {
				$html =  str_replace('###SHOP_ARTIKEL_MAKRTPLATZ_LINK###','',$html);
			}

 
			
			//Create a new PHPMailer instance
			$mail = new PHPMailer();
			$mail -> charSet = "UTF-8";
			// Set PHPMailer to use the sendmail transport
			$mail->isSendmail();
			$mail->setFrom(CORE_MAIL_FROM_EMAIL,CORE_MAIL_FROM_EMAIL_NAME);
			//Set an alternative reply-to address
			$mail->addReplyTo(CORE_MAIL_FROM_EMAIL,CORE_MAIL_FROM_EMAIL_NAME);
			//Set who the message is to be sent to
			$mail->addAddress($ShopInfoData['email_shop_main'],$ShopInfoData['email_shop_main_form_name']);
			
			//Set who the message is to be sent to
			$mail->AddBCC(CORE_MAIL_SEND_BCC,CORE_MAIL_SEND_BCC_NAME);
			//Set the subject line
			
			#'Shopste ###SHOP_ARTIKEL_NAME###: Artikel neu (Shop ###SHOP_ADD_TYPE###) eingestellt: ###SHOP_ARTIKEL_NAME###
			
			$betreff = $strEmailVorlage['betreff'];
			
			$betreff = str_replace("###SHOP_ARTIKEL_STATUS###",$strArtikelModus,$betreff);
			$betreff = str_replace("###SHOP_ARTIKEL_NAME###",$ShopInfoData['name_de'],$betreff);
			$betreff = str_replace("###SHOP_ADD_IMPORT###",$strImportiert,$betreff);
			$betreff = str_replace("###SHOP_ARTIKEL_PREIS###",number_format($ShopInfoData['preis'], 2, ",", "."),$betreff);
			$betreff = str_replace("###SHOP_ADD_TYPE###",$strSubjectAdd,$betreff);
				
			$mail->Subject = utf8_decode(html_entity_decode($betreff));
				
				//Read an HTML message body from an external file, convert referenced images to embedded,
				//convert HTML into a basic plain-text alternative body
				$html = utf8_decode($html);
				$mail->msgHTML($html, dirname(__FILE__));
				//Replace the plain text body with one created manually
				$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';
				//Attach an image file
				//$mail->addAttachment('images/phpmailer_mini.png');
				$_SESSION['get_shop_menue'] = $_POST['marktplatz_shop_category'];
				$_SESSION['get_shop_cat'] = $_POST['marktplatz_shop_category_path'];
				$_SESSION['get_page_menue'] = $_POST['page_menue_id'];
				$_SESSION['get_page_cat'] = $_POST['shop_cat_id'];
				
				//$_SESSION['acp_imported_ids'] = '';
				//send the message, check for errors
				if (!$mail->send()) {
					echo "Mailer Error: " . $mail->ErrorInfo;
				} else {
					echo "Artikel aktualisiert!";
				}	
				exit(0);
			}
		}
	}
	
	$strDomain_ary = getDomainInfo();
	
	if($_GET['modus'] == 'edit') {
		$query = "SELECT * FROM shop_item WHERE shop_item_id='".$_GET['shop_id']."'";
		$resQuery = DBi::$conn->query($query) or die(mysqli_error());
		$dataShop = mysqli_fetch_assoc($resQuery);
		$_SESSION['acp_shop_item_id'] = $dataShop['shop_item_id'];
		$query = "SELECT * FROM menue WHERE id='".$dataShop['menue_id']."'";
		$resMenueData = DBi::$conn->query($query) or die(mysqli_error());
		$strMenueData = mysqli_fetch_assoc($resMenueData);	
		
		$query = "SELECT * FROM menue_parent WHERE menue_id='".$dataShop['menue_id']."'";
		$resMenueData = DBi::$conn->query($query) or die(mysqli_error());
		$strMenueData_parent = mysqli_fetch_assoc($resMenueData);	
		
		#$strOptMenueSelekt2 = menue_generator(0,0,'',0,0,'select',$strMenueData_parent['parent_id']);		
		$strOptMenueSelekt = shop_category(0,0,'',0,0,'select',$dataShop['shop_cat_id']);	
		
		$tmpSession = $_SESSION['domain_id'];
		$_SESSION['domain_id'] = 1;
		#echo $_SESSION['domain_id'];
		#mail("jbludau@cubss.net","Datei hochgeladen ".$_FILES["system_upload"]["name"].' für '.$_POST['benutzername'],$_SESSION['domain_id']);
		$strOptMenueMarket = shop_category(0,0,'',0,0,'select',$dataShop['shopste_marktplatz_cat']);
		
		$query = "SELECT * FROM menue_parent WHERE menue_id='".$dataShop['shopste_marktplatz_menue_id']."'";
		$resMenueData = DBi::$conn->query($query) or die(mysqli_error());
		$strMenueData_parent = mysqli_fetch_assoc($resMenueData);
		
		#$strOptMenuePathMarket = menue_generator(0,0,'',0,0,'select',$strMenueData_parent['parent_id']);
		$_SESSION['domain_id'] = $tmpSession;
		
		# Importiert
		if($dataShop['status_de'] == 'API-importiert') {
			$strButtonName = 'Shop Produkt importieren';
			$strTitelPage ='SHOP Produkt importieren "'.$dataShop['name_de'].'"';
			if($dataShop['item_number'] == '') {
				$dataShop['item_number'] = $dataShop['shop_item_id'];
			}
			$strMenueData['name_de'] = $dataShop['name_de'];
			$strMenueData['titel_de'] = $dataShop['name_de'];
			$_GET['modus'] = 'API-importiert';
			$dataShop['item_enabled'] = 'Y';
		} else {
			$strButtonName = 'Shop Produkte editieren';
			$strTitelPage ='SHOP Produkt editieren "'.$dataShop['name_de'].'"';		
		}
		
		$query ="SELECT * FROM shop_item_picture WHERE shop_item_id='".$_GET['shop_id']."'";
		$resQuery = DBi::$conn->query($query) or die(mysqli_error());
		while($data = mysqli_fetch_assoc($resQuery)) {
		
			#$pic_type = strtolower(strrchr($data['picture_url'],"."));
			#$pic_filename = str_replace($pic_type,"",$data['picture_url']);	
			#$strNewPic = str_replace($pic_filename,$pic_filename.'_catList'.$pic_type,$pic_filename);
			#$html .= '<img src="'.$data['picture_url'].'" border="0"/><br/>';
			$htmlPic .= '<img src="'.$data['picture_url'].'" border="0" style="float:left"/><br>
			<input type="button" name="btnPictureDelete" onClick="javascript:setShopItemPictureDelete(\''.$data['shop_item_picture_id'].'\')" class="button" value="Bild löschen">';
		}
		$htmlPic .= '<div style="clear:both"></div>';
		
		# Formatierung Prozent
		$dataShop['item_mwst'] = str_replace(".",",",$dataShop['item_mwst']);
		$dataShop['preis'] = str_replace(".",",",$dataShop['preis']);
		
	} else {	
		if(isset($_SESSION['get_page_menue']) && $strDomain_ary['isShopste'] == 'N') {
			#$strOptMenueSelekt2 = menue_generator(0,0,'',0,0,'select',$_SESSION['get_page_menue']);
		} else {
			#$strOptMenueSelekt2 = menue_generator(0,0,'',0,0,'select',0);		
		}
		
		
		if(isset($_SESSION['get_page_cat'])) {
			$strOptMenueSelekt = shop_category(0,0,'',0,0,'select',$_SESSION['get_page_cat']);
		} else {
			$strOptMenueSelekt = shop_category(0,0,'',0,0,'select',0);		
		}	
	
 
		$tmpSession = $_SESSION['domain_id'];
		$_SESSION['domain_id'] = 1;
		#echo $_SESSION['domain_id'];
		#mail("jbludau@cubss.net","Datei hochgeladen ".$_FILES["system_upload"]["name"].' für '.$_POST['benutzername'],$_SESSION['domain_id']);
		#echo $_SESSION['get_shop_cat'];
		if($strDomain_ary['isShopste'] == 'Y') {
			if(isset($_SESSION['get_shop_cat'])) {
				$strOptMenueMarket = shop_category(0,0,'',0,0,'select',$_SESSION['get_shop_cat']);
			} else {
				$strOptMenueMarket = shop_category(0,0,'',0,0,'select',0);		
			}
		}
		if(isset($_SESSION['get_shop_menue'])) {
			#$strOptMenuePathMarket = menue_generator(0,0,'',0,0,'select',$_SESSION['get_shop_menue']);
		} else {
			#$strOptMenuePathMarket = menue_generator(0,0,'',0,0,'select',0);		
		}
		$_SESSION['domain_id'] = $tmpSession;
		
		$strButtonName = 'Shop Produkte anlegen';
		$strTitelPage ='SHOP Produkt anlegen';
		
		
	}

	
	#$strMenueData['container'] = $_SESSION['page_layout'];
	if($_POST['schritt'] == '') {
?>
<div id="acp_main_shop_item_form">

	<?php 
		if($strDomain_ary['isShopste'] == 'Y') {
			$strTitelPage = 'Shop Produkt erstellen (inkl. Shopste möglich)';
			$str_gui_header_category = 'Übergeordnete Shop-Kategorie ';
			$str_gui_header_product = 'Produktname';
			$str_gui_header_item_number = 'Interne Artikelnummer';
			$str_gui_footer_button_send = 'Shop Produkt erstellen';
			$strHTML = '<br/>Denken Sie sich einen Aussagekräftigen Produktnamen für Ihre Artikel aus. Denken Sie an "Keywords", das sind wichtige Schlagworte.<br/>
			Bitte füllen Sie das Formular aus. Die mit * gekennzeichten Felder sind auszufüllen.<br/>';
		} else {
			if($strDomain_ary['isRestaurant'] == 'Y') {
				$strTitelPage = 'Neue Speise erstellen';
				$str_gui_header_category = 'Übergeordnete Speisekategorie ';
				$str_gui_header_product = 'Speisename';
				$str_gui_header_item_number = 'Speisekarten-Nr.';
				$str_gui_footer_button_send = 'Speise der Speisekarte hinzufügen';
				$strHTML =  '<br/>Bitte denken Sie sich einen Speisenamen aus.<br/>
				Bitte füllen Sie das Formular aus. Die mit * gekennzeichten Felder sind auszufüllen.<br/>';
			} else {
				$strTitelPage = 'Shop Produkt erstellen';
				$str_gui_header_category = 'Übergeordnete Shop-Kategorie ';
				$str_gui_header_product = 'Produktname';
				$str_gui_header_item_number = 'Interne Artikelnummer';
				$str_gui_footer_button_send = 'Shop Produkt erstellen';
				$strHTML =  '<br/>Bitte füllen Sie das Formular aus. Die mit * gekennzeichten Felder sind auszufüllen.<br/>';				
			}
		}
	?>
	
			<h1><?php echo $strTitelPage ?></h1>
			
			<?php if($dataShop['created_at'] != '') { ?> 
				<div class="label" style="float:left;">Meta Daten</div>
				<?php echo getDateDE($dataShop['created_at']) ?><br/>
				<?php echo getDateDE($dataShop['updated_at']) ?><br/>
			<?php } ?>
<?php 
	echo $strHTML;
?>
	<form name="frmPageSetting" id="shop_item" action="/ACP/acp_shop_item.php" method="POST" onSubmit="return shop_save_item_form('shop_item');">
		<div id="acp_new_page_form">		
			<input type="hidden" id="acp_get_modus" name="modus" value="<?php echo $_GET['modus']; ?>"/>
			<input type="hidden" id="acp_get_modul_name" name="optModul" value="shop_item_detail"/>
			<input type="hidden" id="acp_get_shop_id" name="shop_id" value="<?php echo $_GET['shop_id'] ?>"/>		
			
			<div class="label" style="float:left;"><?php echo $str_gui_header_category; ?> 			
				<div id="shop_path">
					<select name="shop_cat_id" size="1">
						<?php 
						 echo $strOptMenueSelekt;
						?>
					</select>
				</div>
			</div>
			<div class="label" style="float:left;"><?php echo $str_gui_header_product; ?>*
				<div id="shop_item_name_head">
					<input type="text" id="shop_item_name" onKeyUp="setTextboxValue('page_url_name','shop_item_name');setTextboxValue('shop_page_titel','shop_item_name');" name="shop_item_name" value="<?php echo $dataShop['name_de']; ?>" required><span class="error" id="shop_item_name_err"></span>			
				</div>
			</div>
			<div class="label" style="float:right;">Preis*
				<div id="shop_item_price_head">
					<input type="text" id="shop_item_price_read" name="shop_item_price" value="<?php echo $dataShop['preis']; ?>" required><span class="error" id="shop_item_price_read_err"></span>
				</div>	
			</div>
			<div style="clear:both"></div>
			<div class="label" style="float:left;">Menge*
				<div id="shop_item_price">
					<input type="text" id="shop_item_menge"  name="shop_item_menge" value="<?php if(empty($dataShop['menge'])) { echo "1"; } else { echo $dataShop['menge']; }?>"><span class="error" id="shop_item_menge_err"></span>
				</div>	
			</div>

			<?php 
				if($strDomain_ary['isRestaurant'] == 'N') {				
			?>
			<div class="label" style="float:left;">Artikel Gewicht (in KG)*
				<div id="shop_item_price">
					<input type="text" id="shop_item_gewicht" name="shop_item_gewicht" value="<?php if(empty($dataShop['menge'])) { echo "0.0"; } else { echo $dataShop['gewicht']; } ?>"><span class="error" id="shop_item_gewicht_err"></span>
				</div>	
			</div>

			<?php 
				} else {
					echo '<input type="hidden" id="shop_item_gewicht" name="shop_item_gewicht" value="0.0"/>';
				}
			?>
			<div class="label" style="float:left;"><?php echo $str_gui_header_item_number ?>*
				<div id="shop_item_price">
					<input type="text" id="shop_item_artnummer" name="shop_item_artnummer" value="<?php echo $dataShop['item_number']; ?>"><span class="error" id="shop_item_artnummer_err"></span>
				</div>
			</div>
			
			<div class="label" style="float:left;">Mehrwertsteuer (in %)*
				<div id="shop_item_mwst_tmp">
					<?php if(empty($dataShop['item_mwst'])) { $dataShop['item_mwst'] = '19'; } ?>
					<input type="text" id="shop_item_mwst" name="shop_item_mwst" value="<?php echo $dataShop['item_mwst']; ?>"><span class="error" id="shop_item_mwst_err"></span>
				</div>
			</div>			
			
			<?php 
				if($strDomain_ary['isRestaurant'] == 'N') {								
			?>
			<div class="label" style="float:left;">Lieferzeit (in Tagen)*
				<div id="shop_item_lieferzeit_tmp">
					<input type="text" id="shop_item_lieferzeit" name="shop_item_lieferzeit" value="<?php if(empty($dataShop['lieferzeit'])) { echo "2 Tage"; } else { echo $dataShop['lieferzeit']; } ?>"><span class="error" id="shop_item_lieferzeit_err"></span>
				</div>
			</div>			
			<?php 
			} else {
				echo '<input type="hidden" id="shop_item_lieferzeit" name="shop_item_lieferzeit" value="2 Tage"/>';
			}
			?>
			<input type="hidden" id="schritt" name="schritt" value="bilder_upload"/>';
			<div style="clear:both"></div>
			<div class="label" style="float:left;">
			<?php 
				
				if($strDomain_ary['isShopste'] == 'N') {					
					echo "Speise aktiv";
				} else {
					echo "Shop und Marktplatz";
				}
			?>
			<?php 
			if($dataShop['item_enabled'] == 'N') {
				$strSelectedShop_disable_1 = 'checked="checked" ';
			} else {
				$strSelectedShop_disable_2 = 'checked="checked" ';
			}
			?>
			<input type="radio" name="shop_item_enable" value="Y" <?php echo $strSelectedShop_disable_2; ?>> Aktiv<br>
			<input type="radio" name="shop_item_enable" value="N" <?php echo $strSelectedShop_disable_1; ?>>Inaktiv<br>
			
			</div>
			
			<div class="label" style="float:left;">Seiten&uuml;berschriftt
				<div id="shop_page_titel_header">
					<input type="text" id="shop_page_titel" name="shop_page_titel" value="<?php echo $strMenueData['titel_de']; ?>">			
				</div>
			</div>			
			<div class="label" style="float:right;">Name des Men&uuml;punktes*
				<div id="module_url_path">
					<input type="text" id="page_url_name"  name="page_url_name" value="<?php echo $strMenueData['name_de']; ?>"><span class="error" id="page_url_name_err"></span>
				</div>
			</div>
			
			<div style="clear:both"></div>			
			<div class="label" style="float:left;">Artikel-Beschreibung</div>
			<div id="shop_item_price">
				<textarea name="shop_item_beschreibung" id="shop_item_beschreibung"><?php echo $dataShop['beschreibung']; ?></textarea>
			</div>	
			<script>
		var editor = CKEDITOR.replace('shop_item_beschreibung');
			</script> 
			<div style="clear:both"></div>
			
			<?php 
			  if($strDomain_ary['isShopste'] == 'Y') {
				  			  
			?>
			<h2>Shop Artikel Detailansicht Seite Informationen (SEO)</h2>
			Seitenüberschrift und Menüpunktname werden normalerweise vom Produktnamen hergeleitet. Die URL der Shop Detailansicht Seite ergibt sich aus der Kategorie.
			<div class="label" style="float:left;">Template Datei:*</div>
			<div id="page_template_layout">
				<select name="page_template_layout" size="1">
					<option value="index.tpl" <?php if($strMenueData['template_file'] == 'index.tpl') echo "selected=true"?>>Standardtemplate</option>
				</select>			
			</div>			
			<div style="clear:both"></div>			
			<?php 
			  } else {
				  echo '<input type="hidden" name="page_template_layout" value="index.tpl">';				  
			  }
			?>
			<?php 
				if($strDomain_ary['isRestaurant'] == 'N') {
									
			?>
			<div class="label" style="float:left;">Seitenlayout:</div>
			<div id="page_layout">
				<select name="page_layout" size="1">
					<option value="col2-left-layout" <?php if($strMenueData['container'] == 'col2-left-layout') echo "selected=true"?>>MENÜ LINKS + MITTE</option>
					<option value="col2-right-layout" <?php if($strMenueData['layout'] == 'col2-right-layout') echo "selected=true"?>>MITTE + MENÜ RECHTS</option>
					<option value="col3-layout" <?php if($strMenueData['layout'] == 'col3-layout') echo "selected=true"?>>MENÜ LINKS + MITTE + RECHTS Spalte</option>
				</select>			
			</div>			
			<?php 
			} else {
				echo '<input type="hidden" name="page_layout" value="col2-left-layout">';
			}
			?>

			<div style="clear:both"></div>			
			<div class="label" style="float:left;">Sortierung
				<div id="shop_page_sort">
					<input type="text" name="shop_page_sort" value="<?php echo $strMenueData['sortierung']; ?>">
				</div>
			</div>

			<?php 
				if($strDomain_ary['isRestaurant'] == 'N') {
									
			?>			
			<div class="label" style="float:left;">EAN-Nummer</div>
			<div id="shop_ean">
				<input type="text" name="shop_ean" value="<?php echo $dataShop['ean']; ?>">
			</div>
			<?php 
				}
				else {
					echo '<input type="hidden" name="shop_ean" value=""/>';
				}
			?>
			<?php  
				if($strDomain_ary['isRestaurant'] == 'N') {
									
			?>			
			<div class="label" style="float:left;">Versandkosten</div>
			<div id="shop_versandkosten">
				<input type="text" name="shop_versandkosten" value="<?php echo $dataShop['versandkosten']; ?>">
			</div>			
			<div style="clear:both"></div>				
			<?php 
				} else {
					echo '<input type="hidden" name="shop_versandkosten" value=""/>';
				}
			?>			
			<?php 
				$query ="SELECT * FROM shop_item_additional_types WHERE domain_id='".$_SESSION['domain_id']."' GROUP BY typ";
				$resAtr = DBi::$conn->query($query) or die(mysqli_error());
				$bGefunden = false;
				$iCount =0;
				while($strAtr = mysqli_fetch_assoc($resAtr)) {
					$bGefunden = true;
					$html_atr .= '<strong>'.$strAtr['typ_titel'].'</strong><br/>';
					$html_atr .= '<select name="additional_opt_'.$strAtr['typ'].'">';
					
					$query = "SELECT * FROM shop_item_additional_types WHERE typ='".$strAtr['typ']."' ORDER BY isDefault ASC";
					$resAtrList =  DBi::$conn->query($query) or die(mysqli_error());
					while($strAtrList = mysqli_fetch_assoc($resAtrList)) {
						# Wird Artikel geändert
						$strFound['anzahl'] = 0;
						if($_GET['modus'] == 'edit') {
							$query = "SELECT count(*) as anzahl FROM shop_item_additional WHERE shop_item_id='".$dataShop['shop_item_id']."' AND shop_item_additional_types_id='".$strAtrList['shop_item_additional_types_id']."'";
							$resOPTSelect = DBi::$conn->query($query) or die(mysqli_error());
							$strFound = mysqli_fetch_assoc($resOPTSelect);
						}
						if($strFound['anzahl'] > 0){
							$html_atr .= '<option value="'.$strAtrList['shop_item_additional_types_id'].'" selected>'.$strAtrList['typ'].' - '.$strAtrList['value'].'</option>';
							$html_atr_select .= '<input type="hidden" name="old_'.$strAtrList['typ'].'" value="'.$strAtrList['shop_item_additional_types_id'].'"/>';
							
						} else {
							$html_atr .= '<option value="'.$strAtrList['shop_item_additional_types_id'].'">'.$strAtrList['typ'].' - '.$strAtrList['value'].'</option>';
						}
					}
					$html_atr .= '</select>'.$html_atr_select;
					$html_atr .= '<br/><br/>';
				}
				
			if($bGefunden == true) {
				echo '<h2>Weitere Produkteigenschaften</h2>';
				echo '<input type="hidden" name="hasAdditionalValues" value="true"/>';
				echo $html_atr;
			} 
			
			if($strDomain_ary['isShopste'] == 'Y') {
			?>
			<h1>Shopste Marktplatz</h1><br/>
			Wenn Sie hier eine Kategorie auswählen wird Ihr Produkt in die jeweilige Kategorie auf Shopste.com veröffentlicht. Wählen Sie nichts aus "Keine Auswahl", wenn Sie nicht möchten das Ihr Artikel auf Shopste.com eingestellt wird. 
			
			<div class="label" style="float:left;">Marktplatz Kategorie ausw&auml;hlen <span class="error" id="marktplatz_shop_category_err"></span></div>
			<div id="marktplatz_shop_category">
				<select id="marktplatz_shop_category"  name="marktplatz_shop_category" size="1">
					<option value="KEINE-AUSWAHL">Keine Auswahl</option>
					<option value="0">Hauptkategorie (Men&uuml;ebene 0)</option>';
					<?php 
					 echo $strOptMenueMarket;
					?>
				</select>
			</div>			
			<?php
			} else {
				echo '<input type="hidden" name="marktplatz_shop_category" value="KEINE-AUSWAHL">';
			}
			?>
			
			<div style="clear:both"></div>	
			<br/>
			<div id="module_submit">
				<input type="submit" class="button module_form_submit" id="module_form_submit" name="module_submit" value="<?php echo $str_gui_footer_button_send; ?>">
			</div>		
		</div>
	</form>
<?php
// preselect shop_id 
//if($_GET['modus'] == 'new' or $_GET['modus'] == 'edit' or $_GET['modus'] == 'API-importiert') {
if( $_GET['modus'] == 'edit' or $_GET['modus'] == 'API-importiert') {
?>
<h1>Schritt 2/2 Bilder hochladen f&uuml;r f&uuml;r Produkt '<?php echo $strArtikel['name_de'] ?>'</h1>
F&uuml;gen Sie Ihren Artikeln Bilder in unlimitierter Anzahl hinzu.<br/><br/>
<form id="upload" method="post" action="/framework/ajax_upload/upload.php" enctype="multipart/form-data">
			<div id="drop">
				Dateien per Drag and Drop hier her verschieben...

				<a>Datei auswählen</a>
				<input type="file" name="upl" multiple />
			</div>

			<ul>
				<!-- The file uploads will be shown here -->
			</ul>
<input type="hidden" name="shop_id" id="shop_id" value="<?php echo $dataShop['shop_item_id']; ?>"/>
</form>

<div id="box_item_picture"><?php echo $htmlPic; ?></div>
<!-- Our main JS file -->

<div id="box_item_picture"><?php echo $htmlPic; ?></div>

<h2>Facebook Social Media</h2>
<?php 
		$query = "SELECT * FROM benutzer JOIN api_facebook ON benutzer.api_facebook_id = api_facebook.api_facebook_id WHERE id='".$_SESSION['user_id']."'";
		#echo $query;
		$resAdmin = DBi::$conn->query($query) or die(mysqli_error());
		$strUserFacebook = mysqli_fetch_assoc($resAdmin);
		
		if(isset($strUserFacebook['api_facebook_id'])) {
			
			echo '<select name="optFanpageSelect" id="optFanpageSelect">';
			echo '<option value="'.$strUserFacebook['user_id'].'">Profil: '.$strUserFacebook['vorname'].' '.$strUserFacebook['nachname'].'</option>';
			
			##################################
			# >> Fanpage vorhanden?
			##################################
			$query ="SELECT * FROM api_facebook_fanpages WHERE api_facebook_id='".$strUserFacebook['api_facebook_id']."'";
			#echo $query;
			$resUserFacebookFanpage = DBi::$conn->query($query) or die(mysqli_error());
			while($strFanpage2Post = mysqli_fetch_assoc($resUserFacebookFanpage)) {
				echo '<option value="'.$strFanpage2Post['fanpage_id'].'">Seite: '.$strFanpage2Post['fanpage_name'].'</option>';
			}
			
			echo "</select>";
			echo '<input id="acp_shop_post_facebook" type="submit" class="button" name="submit" value="Auf Facebook veröffentlichen" style="width:277px"/>';
			echo '<div id="box_item_social_media"></div>';
		}
?>
<script type="text/javascript">
$( "#acp_shop_post_facebook" ).click(function() {
	//alert('upload..');
	var shop_id = $('#shop_id').val();
	$("#box_item_social_media").html('Verarbeite Anfrage...');
		$.ajax(
    {
        url : '/ACP/acp_facebook_post_item.php',
        type: "POST",
        data : "modus=init&shop_item_id=" + shop_id + "&fanpage_id=" + $('#optFanpageSelect').val(),
        success:function(data, textStatus, jqXHR)
        {
            $("#box_item_social_media").html(data);
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });
	return false;
});
</script>
<script type="text/javascript">
$( "#shop_bild_upload" ).click(function() {
	
//jQuery(function () {
 
	jQuery('#shop-bilder-upload').uploadProgress({ 
		progressURL:'/ACP/acp_shop_bilder_upload.php',
		displayFields : ['kb_uploaded','kb_average','est_sec'],
		start: function() { 
			jQuery('#upload-message').html('<strong>Hochladen wurde gestartet...</strong>'); 
			jQuery('#shop_bild_upload',this).val('<strong>Hochladen... Bitte warten</strong>');
		},
		success: function() { 
			jQuery('#shop_bild_upload',this).val('Datei hochladen');
			jQuery(this).get(0).reset();
			jQuery('#upload-message').html('<strong>Datei wurde hochgeladen!</strong>'); 
			var loadUrl = '/ACP/acp_shop_item_picture_preview.php';
			var shop_id = $('#shop_id').val();
			
			$("#box_item_picture").html(ajax_load).load(loadUrl, "shop_id=" + shop_id);
		}
	}); 
	//});
});
$(document).ready(function() {
	 $("html, body").animate({
            scrollTop: 0
        }, 300);
});
</script>
<h2>Konfigurierbare Optionen</h2>
<button class="button" onClick="shop_attribute_item_clear('<?php echo $_GET['shop_id']; ?>')">Kombination l&ouml;schen</button>
<div id="combination_clear_message"></div>
<?php 
$_SESSION['shop_item_id'] = $_GET['shop_id'];
include_once('acp_shop_attribute_kombination.php');

  }
?>
<?php  

} elseif($_POST['schritt'] == 'bilder_upload') {
?>
<?php
$strShopID = str_replace('ï»¿','',utf8_encode($_POST['shop_id']));
$query = "SELECT * FROM shop_item WHERE shop_item_id = '".$strShopID."'";
#echo $strShopID.'-'.$query;
$resArtikel = DBi::$conn->query($query) or die(mysqli_error());
$strArtikel = mysqli_fetch_assoc($resArtikel);
?>
<h1>Schritt 2/2 Bilder hochladen f&uuml;r Produkt '<?php echo $strArtikel['name_de'] ?>'</h1>
<form id="upload" method="post" action="/framework/ajax_upload/upload.php" enctype="multipart/form-data">
			<div id="drop">
				Dateien per Drag and Drop hier her verschieben...

				<a>Datei auswählen</a>
				<input type="file" name="upl" multiple />
			</div>

			<ul>
				<!-- The file uploads will be shown here -->
			</ul>
<input type="hidden" name="shop_id" id="shop_id" value="<?php echo $strShopID; ?>"/>
		</form>
</div>
<div id="box_item_picture"><?php echo $htmlPic; ?></div>
<!-- Our main JS file -->
<script src="/framework/ajax_upload/assets/js/script.js"></script>

<h2>Facebook Social Media</h2>

<?php 
		$query = "SELECT * FROM benutzer JOIN api_facebook ON benutzer.api_facebook_id = api_facebook.api_facebook_id WHERE id='".$_SESSION['user_id']."'";
		#echo $query;
		$resAdmin = DBi::$conn->query($query) or die(mysqli_error());
		$strUserFacebook = mysqli_fetch_assoc($resAdmin);
		
		if(isset($strUserFacebook['api_facebook_id'])) {
			
			echo '<select name="optFanpageSelect" id="optFanpageSelect">';
			echo '<option value="'.$strUserFacebook['user_id'].'">Profil: '.$strUserFacebook['vorname'].' '.$strUserFacebook['nachname'].'</option>';
			##################################
			# >> Fanpage vorhanden?
			##################################
			$query ="SELECT * FROM api_facebook_fanpages WHERE api_facebook_id='".$strUserFacebook['api_facebook_id']."'";
			#echo $query;
			$resUserFacebookFanpage = DBi::$conn->query($query) or die(mysqli_error());
			while($strFanpage2Post = mysqli_fetch_assoc($resUserFacebookFanpage)) {
				echo '<option value="'.$strFanpage2Post['fanpage_id'].'">'.$strFanpage2Post['fanpage_name'].'</option>';
			}
			
			echo "</select>";
			echo '<input id="acp_shop_post_facebook" type="submit" class="button" name="submit" value="Auf Facebook veröffentlichen" style="width:277px"/>';
		}
?>

<div id="box_item_social_media"></div>
<script type="text/javascript">
$( "#acp_shop_post_facebook" ).click(function() {
	// JB B
	//var shop_id = $('#shop_id_id').val();
	var shop_id = $('#shop_id').val();
	$("#box_item_social_media").html('Verarbeite Anfrage...');
		$.ajax( 
    {
        url : '/ACP/acp_facebook_post_item.php',
        type: "POST",
        data : "modus=init&shop_item_id=" + shop_id + "&fanpage_id=" + $('#optFanpageSelect').val(),
        success:function(data, textStatus, jqXHR)
        {
            $("#box_item_social_media").html(data);
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });
	return false;
});
</script>
<script type="text/javascript">
$( "#shop_bild_upload" ).click(function() {
 
	jQuery('#shop-bilder-upload').uploadProgress({ 
		progressURL:'/ACP/acp_shop_bilder_upload.php',
		displayFields : ['kb_uploaded','kb_average','est_sec'],
		start: function() { 
			jQuery('#upload-message').html('<strong>Hochladen wurde gestartet...</strong>'); 
			jQuery('#shop_bild_upload',this).val('<strong>Hochladen... Bitte warten</strong>');
			//return false;
		},
		success: function() { 
			jQuery('#shop_bild_upload',this).val('Datei hochladen');
			jQuery(this).get(0).reset();
			jQuery('#upload-message').html('<strong>Datei wurde hochgeladen!</strong>'); 
			var loadUrl = '/ACP/acp_shop_item_picture_preview.php';
			var shop_id = $('#shop_id_id').val();
			$("#box_item_picture").html(ajax_load).load(loadUrl, "shop_id=" + shop_id);
			//return false;
		}
	});
	//});
	//return false;
});
$(document).ready(function() {
 $("html, body").animate({
            scrollTop: 10
        }, 300);
});
</script>
<?php 
}
?>