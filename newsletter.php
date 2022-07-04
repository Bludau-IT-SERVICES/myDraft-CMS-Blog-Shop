<?php
session_start();
$ServerPathComplete = dirname(__FILE__);

require_once($ServerPathComplete.'/include/inc_config-data.php');
require_once($ServerPathComplete.'/include/inc_basic-functions.php');

function set_send_email() {
	if(isset($_GET['api-key'])) {
			if($_GET['api-key'] != CORE_CRON_API_KEY) {
				exit(0);
			}	
		} else {
			exit(0);
		} 

		#$query = "SELECT * FROM api_twitter_history WHERE  api_twitter_history.created_at >= CURDATE()  ORDER BY created_at DESC"; 
		$query = "SELECT * FROM api_twitter_history WHERE  DATE(created_at) = DATE( DATE_SUB( NOW() , INTERVAL 1 DAY ) ) ORDER BY created_at DESC"; 
		$resNews2Send = DBi::$conn->query($query) or die('ERR003:'.mysqli_error());
		
		$strEmail ='<h1>News von '.CORE_SERVER_PLATTFORM_NAME.'</h1>'; 		
		$bIn = "false";
		$iCount = 1;
		while($strNews2Send = mysqli_fetch_assoc($resNews2Send)) {
			$strContent = explode("http",$strNews2Send['message']);
			$iCount++;
			$strEmail .= $iCount.' - '.utf8_decode($strContent[0].' <br/><a href="http'.$strContent[1].'">Webseite öffnen</a><br/><br/>');
			$bIn = "true";
		} 
		if($bIn == "true") {
			#$headers .= "Bcc: jbludau@cubss.net\r\n";
			$query = "SELECT * FROM newsletter WHERE enabled='Y'";
			$res = DBi::$conn->query($query);
			$iCount = 0;
			while($strNewsletter = mysqli_fetch_assoc($res)) {
				$iCount++;
				$sendTemplate = $strEmail;
				echo $iCount.' - '.$strNewsletter['email'].'<br/>';
				
				# Email abschicken
				$strEmailLink = utf8_decode('Abmeldung: <a href="'.CORE_SERVER_DOMAIN.'newsletter.php?modus=abmelden_link&email='.$strNewsletter['email'].'&crc='.$strNewsletter['crc'].'">Abmeldung für '.$strNewsletter['email'].' durchführen</a>');
				 
				$sendTemplate .= $strEmailLink;
				
				$headers = "From: ".CORE_SERVER_PLATTFORM_NAME." <".CORE_MAIL_SEND_BCC.">\r\n"; 
  "X-Mailer: php\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				
				if(empty($strNewsletter['email_user_name'])) {
					$strWer = $strNewsletter['email'];
				} else {
					$strWer = $strNewsletter['email_user_name'];					
				} 
				
				mail($strNewsletter['email'],CORE_SERVER_PLATTFORM_NAME." Newsletter für ".$strWer,$sendTemplate,$headers);
				
				# Gesendet Datum speichern
				$query = "UPDATE newsletter SET gesendet_am='".date("Y-m-d H:i:s")."',gesendet_anzahl=gesendet_anzahl+1 WHERE crc='".$strNewsletter['crc']."'";
				DBi::$conn->query($query) or die('ERR004:'.mysqli_error());
				
			}			
		}
	return true;
}

$_POST = mysql_real_escape_array($_POST);
$_GET  = mysql_real_escape_array($_GET);	

if(isset($argv[1])) {
	switch($argv[1]) { 
		case 'send':
			$_POST['modus'] = 'send';
			$_POST['cron'] = 'Y';
			break;
		case 'tweet_feeds':
			$_POST['modus'] = 'tweet_feeds';
			break;
	}
}

#print_r($_POST);

if(!isset($_POST['modus'])) {
	
	if(isset($_GET['modus'])) {
		$_POST['modus'] = $_GET['modus'];	
	} else {
		$_POST['modus'] = 'read_feeds';		
	}
}


switch($_POST['modus']) {
	case 'abmelden_link':
		
		if(isset($_GET['email'])) {
			# Newsletter deaktivieren
			$query ="UPDATE newsletter SET enabled='N' WHERE email='".$_GET['email']."' AND crc='".$_GET['crc']."'";
			$res = DBi::$conn->query($query) or die('ERR001:'.mysqli_error());
			echo "Ihre Email '".strip_tags($_GET['email'])."' wurde vom Newsletter abgemeldet";
			
			$headers = "From: ".CORE_SERVER_PLATTFORM_NAME." <".CORE_MAIL_SEND_BCC.">\r\n";
  "X-Mailer: php\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";			
			
			mail(CORE_MAIL_SEND_BCC,"Newsletter Abmeldung:".$_GET['email'],"Newsletter Abmeldung abgeschlossen",$headers);
		}
		
		break;
	case 'activate':
		$query ="SELECT * FROM newsletter WHERE crc='".$_GET['crc']."'";
		$res = DBi::$conn->query($query) or die('ERR002:'.mysqli_error());;
		$strEmail = mysqli_fetch_assoc($res);
		
		# Aktivieren
		$query ="UPDATE newsletter SET enabled='Y' WHERE crc='".$_GET['crc']."'";
		$res = DBi::$conn->query($query);
		
		$headers = "From: ".CORE_SERVER_PLATTFORM_NAME." <".CORE_MAIL_SEND_BCC.">\r\n";
  "X-Mailer: php\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";				
		mail(CORE_MAIL_SEND_BCC,"Newsletter Aktivierung: ".$_GET['email'],"Newsletter Aktivierung abgeschlossen",$headers);
		echo "Ihre Email '".strip_tags($strEmail['email'])."' für den Newsletter wurde aktiviert";
		
		break;
	case 'send':
	
		set_send_email(); 
		
		echo "Alle Email wurden versendet.";
		break;
}
?>