<?php
session_start();			

include_once('../include/inc_basic-functions.php');
include_once('../include/inc_config-data.php');

$_POST = mysql_real_escape_array($_POST);
$_GET = mysql_real_escape_array($_GET);
	
$_SESSION['domainLanguage'] = 'de';
$_SESSION['language'] = 'de';
#$_POST = mysql_real_escape_array($_POST);
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);

	
function processPWD($UserData) {
	
	//print_r($UserData);
	
	if(isset($UserData['username']) AND isset($UserData['password'])) {
				// Session für den Login 
				
				if($UserData['email_validate'] == 'N') {
					 $strMessage = '<br/><font color="red">Ihre Emailadresse wurde noch nicht bestätigt oder Sie wurden gesperrt!<br/>
					 <a href="https://shopste.com/de/891/Ueber-Shopste/Kontakt-aufnehmen/">Shopste eine Email schicken</a></font>, falls Sie keine Freischaltungsemail erhalten haben<br/><br/>';
					 mail("info@shopste.com","Benutzeraccount nicht freigeschaltet ".$UserData['username'],"Benutzer nicht freigeschaltet: ".$UserData['email']);
				} else {
					
					
					
					$_SESSION['login'] == '1';
					$_SESSION['user_id'] = $UserData['id'];
					$_SESSION['username'] = $UserData['username'];
					
					// Cookie setzten - doppelt MD5 
					#$res = setcookie("admin_UID",$UserData['id'] , time() + 2592000,"/", $_SERVER['SERVER_NAME']);
					
					$res = setcookie("admin_pwd",md5($UserData['password']), time() + 2592000,"/", $_SERVER['SERVER_NAME'],false,true);
					$res = setcookie("admin_user",$UserData['username'], time() + 2592000,"/", $_SERVER['SERVER_NAME'],false,true);	
					$res = setcookie("relogin",$_POST['chkEingeloggtbleiben'], time() + 2592000,"/", $_SERVER['SERVER_NAME'],false,true);	

				
					$path = realpath($_SERVER["DOCUMENT_ROOT"]);
					/*require_once $path.'/framework/phpmailer/PHPMailerAutoload.php';
		 
					$path = getPathUrl($_SESSION['domainLanguage'],$_COOKIE['last_page']);

					
					$query ="SELECT count(*) as anzahl FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_ADMIN_LOGIN'";
					#echo $query;
					$resEmailCount = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$strEmailCount = mysqli_fetch_assoc($resEmailCount);
					if($strEmailCount['anzahl'] > 0) {
						# Lade Benutzervorlage
						$query ="SELECT * FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_ADMIN_LOGIN'";
						#echo $query;
						$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
						$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
					} else {
						# Lade Defaultvorlage
						$query ="SELECT * FROM email_vorlage WHERE domain_id='0' AND standard='Y' AND typ='CORE_ADMIN_LOGIN'";
						#echo $query;
						$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
						$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
					}		


					$strEmailVorlage['content'] = str_replace('###ADMIN_USERNAME###',$UserData['username'],$strEmailVorlage['content']);
					$strEmailVorlage['content'] = str_replace('###ADMIN_LOGIN_LAST###',$UserData['updated_at'],$strEmailVorlage['content']);
					$strEmailVorlage['content'] = str_replace('###ADMIN_LOGIN_COUNT###',$UserData['login_count'],$strEmailVorlage['content']);
					$strEmailVorlage['content'] = str_replace('###ADMIN_ANGEMELDET_OK###',$strLogin,$strEmailVorlage['content']);
					$strEmailVorlage['content'] = str_replace('###ADMIN_LOGIN_IP###',$_SERVER['REMOTE_ADDR'],$strEmailVorlage['content']);
					$strEmailVorlage['content'] = str_replace('###ADMIN_HOSTNAME###',$hostname,$strEmailVorlage['content']);
					$strEmailVorlage['content'] = str_replace('###ADMIN_EMAIL###',$UserData['email'],$strEmailVorlage['content']);
					$strEmailVorlage['content'] = str_replace('###ADMIN_VORNAME###',$UserData['vorname'],$strEmailVorlage['content']);
					$strEmailVorlage['content'] = str_replace('###ADMIN_NACHNAME###',$UserData['nachname'],$strEmailVorlage['content']);
					$strEmailVorlage['content'] = str_replace('###ADMIN_PLZ###',$UserData['plz'],$strEmailVorlage['content']);
					$strEmailVorlage['content'] = str_replace('###ADMIN_ORT###',$UserData['stadt'],$strEmailVorlage['content']);
					
					if($domain_res['bIsSSL'] == 'Y') {
						$strTYP = 'https://';
					} else {
						$strTYP = 'http://';					
					}
					
					$strEmailVorlage['content'] = str_replace('###ADMIN_LOGIN_URL###',$strTYP.$_SERVER['SERVER_NAME'],$strEmailVorlage['content']);
					$strEmailVorlage['content'] = str_replace('###ADMIN_DOMAIN_NAME###',$_SERVER['SERVER_NAME'],$strEmailVorlage['content']);
					$strEmailVorlage['content'] = str_replace('###ADMIN_LOGIN_NAME###',$_SERVER['SERVER_NAME'],$strEmailVorlage['content']);
					
					$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
					# Artikel Email Aktuallisierung
					
					
					$mail = new PHPMailer();
					$mail -> charSet = "UTF-8";
					
					$mail->isSendmail();
					$mail->setFrom(CORE_MAIL_FROM_EMAIL,CORE_MAIL_FROM_EMAIL_NAME);
					
					$mail->addReplyTo(CORE_MAIL_FROM_EMAIL,CORE_MAIL_FROM_EMAIL_NAME);
					
					
					$mail->addAddress(CORE_MAIL_FROM_EMAIL,CORE_MAIL_FROM_EMAIL_NAME);
					
					
					#$mail->AddBCC("info@shopste.com","Shopste API");
					$strEmailVorlage['betreff'] = str_replace('###ADMIN_USERNAME###',$UserData['username'],$strEmailVorlage['betreff']);
					$strEmailVorlage['betreff'] = str_replace('###ADMIN_LOGIN_COUNT###',$UserData['login_count'],$strEmailVorlage['betreff']);
					$strEmailVorlage['betreff'] = str_replace('###ADMIN_DOMAIN_NAME###',$_SERVER['SERVER_NAME'],$strEmailVorlage['betreff']);
					$mail->Subject = utf8_decode($strEmailVorlage['betreff']);
					
					
					$mail->msgHTML(utf8_decode($strEmailVorlage['content']), dirname(__FILE__));
					
					$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';
		 
					
					//$_SESSION['acp_imported_ids'] = '';
					//send the message, check for errors
					if (!$mail->send()) {
						echo "Mailer Error: " . $mail->ErrorInfo;
					} else {
						echo "Email gesendet!";
					}	
					
					$query = "UPDATE benutzer SET login_count=login_count +1 WHERE username='".$UserData['username']."'";
					DBi::$conn->query($query);
					*/
					if(!empty($_GET['page_id'])) {			
						$path = getPathUrl($_SESSION['domainLanguage'],$_GET['page_id']);
						#echo $path;
						#header("Location: /".$path);	
						$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
						header("Location: ".$strLink);	
						
						//header("location:http://".$_SERVER['SERVER_NAME']."/");
					} else {
						header("Location: /index.php");
					}
				}
		} else {
			
			$domain = $_SERVER['HTTP_HOST'];
			$domain = str_replace("www.", "", $domain);
			$query = "SELECT * from domains WHERE name='$domain'";
			$domain_res = mysqli_fetch_assoc(DBi::$conn->query($query));
		
			$strMessage ='<font color="red">Falscher Login</font>';
			$query = "SELECT * FROM benutzer WHERE username='".$_POST['txtUsername']."' AND domain_id='".$domain_res['domain_id']."'";
			#echo $query;
			$resLogin = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$UserData = mysqli_fetch_assoc($resLogin);
		
			$query = "UPDATE benutzer SET login_error=login_error +1 WHERE username='".$UserData['username']."'";
			DBi::$conn->query($query);
			
			$path = realpath($_SERVER["DOCUMENT_ROOT"]);
			/*require_once $path.'/framework/phpmailer/PHPMailerAutoload.php';
 
			$path = getPathUrl($_SESSION['domainLanguage'],$domain_res['startseite']);
			

			$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
			
			$query ="SELECT count(*) as anzahl FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_ADMIN_LOGIN_WRONG'";
			#echo $query;
			$resEmailCount = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strEmailCount = mysqli_fetch_assoc($resEmailCount);
			if($strEmailCount['anzahl'] > 0) {
				# Lade Benutzervorlage
				$query ="SELECT * FROM email_vorlage WHERE domain_id='".$_SESSION['domain_id']."' AND standard='N' AND typ='CORE_ADMIN_LOGIN_WRONG'";
				#echo $query;
				$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
			} else {
				# Lade Defaultvorlage
				$query ="SELECT * FROM email_vorlage WHERE domain_id='0' AND standard='Y' AND typ='CORE_ADMIN_LOGIN_WRONG'";
				#echo $query;
				$resEmailVorlage = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$strEmailVorlage = mysqli_fetch_assoc($resEmailVorlage);
			}		


			$strEmailVorlage['content'] = str_replace('###ADMIN_USERNAME###',$UserData['username'],$strEmailVorlage['content']);
			$strEmailVorlage['content'] = str_replace('###ADMIN_LOGIN_LAST###',$UserData['updated_at'],$strEmailVorlage['content']);
			$strEmailVorlage['content'] = str_replace('###ADMIN_LOGIN_COUNT###',$UserData['login_count'],$strEmailVorlage['content']);
			$strEmailVorlage['content'] = str_replace('###ADMIN_ANGEMELDET_OK###',$strLogin,$strEmailVorlage['content']);
			$strEmailVorlage['content'] = str_replace('###ADMIN_LOGIN_IP###',$_SERVER['REMOTE_ADDR'],$strEmailVorlage['content']);
			$strEmailVorlage['content'] = str_replace('###ADMIN_HOSTNAME###',$hostname,$strEmailVorlage['content']);
			$strEmailVorlage['content'] = str_replace('###ADMIN_EMAIL###',$UserData['email'],$strEmailVorlage['content']);
			$strEmailVorlage['content'] = str_replace('###ADMIN_VORNAME###',$UserData['vorname'],$strEmailVorlage['content']);
			$strEmailVorlage['content'] = str_replace('###ADMIN_NACHNAME###',$UserData['nachname'],$strEmailVorlage['content']);
			$strEmailVorlage['content'] = str_replace('###ADMIN_PLZ###',$UserData['plz'],$strEmailVorlage['content']);
			$strEmailVorlage['content'] = str_replace('###ADMIN_ORT###',$UserData['stadt'],$strEmailVorlage['content']);
			
			if($domain_res['bIsSSL'] == 'Y') {
				$strTYP = 'https://';
			} else {
				$strTYP = 'http://';					
			}
			
			$strEmailVorlage['content'] = str_replace('###ADMIN_LOGIN_URL###',$strTYP.$_SERVER['SERVER_NAME'],$strEmailVorlage['content']);
			$strEmailVorlage['content'] = str_replace('###ADMIN_DOMAIN_NAME###',$_SERVER['SERVER_NAME'],$strEmailVorlage['content']);
			$strEmailVorlage['content'] = str_replace('###ADMIN_LOGIN_NAME###',$_SERVER['SERVER_NAME'],$strEmailVorlage['content']);
 
			$mail = new PHPMailer();
			$mail -> charSet = "UTF-8";
			
			$mail->isSendmail();
			$mail->setFrom(CORE_MAIL_FROM_EMAIL,CORE_MAIL_FROM_EMAIL_NAME);
			
			$mail->addReplyTo(CORE_MAIL_FROM_EMAIL,CORE_MAIL_FROM_EMAIL_NAME);
			
			
			$mail->addAddress($UserData['email'],$UserData['vorname'].' '.$UserData['nachname']);
			
			
			$mail->AddBCC(CORE_MAIL_FROM_EMAIL,CORE_MAIL_FROM_EMAIL_NAME);

			$strEmailVorlage['betreff'] = str_replace('###ADMIN_USERNAME###',$UserData['username'],$strEmailVorlage['betreff']);
			$strEmailVorlage['betreff'] = str_replace('###ADMIN_LOGIN_COUNT###',$UserData['login_count'],$strEmailVorlage['betreff']);
			$strEmailVorlage['betreff'] = str_replace('###ADMIN_DOMAIN_NAME###',$_SERVER['SERVER_NAME'],$strEmailVorlage['betreff']);			
			
			$mail->Subject = utf8_decode($strEmailVorlage['betreff']);
			
			
			$mail->msgHTML(utf8_decode($strEmailVorlage['content']), dirname(__FILE__));
			
			$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';
 
			
			//$_SESSION['acp_imported_ids'] = '';
			//send the message, check for errors
			if (!$mail->send()) {
				echo "Mailer Error: " . $mail->ErrorInfo;
			} else {
				echo "Login Fehler!";
			}	*/
		}
}	
	
	if(isset($_POST['txtUsername'])) {
		session_start();
		
		$domain = DBi::mysql_escape($_SERVER['HTTP_HOST'],DBi::$conn);
		$domain = str_replace("www.", "", $domain);
		$query = "SELECT * from domains WHERE name='$domain'";
		$domain_res = mysqli_fetch_assoc(DBi::$conn->query($query));
		#print_r($domain_res.$domain);


		$query = "SELECT count(*) as anzahl FROM benutzer WHERE username='".$_POST['txtUsername']."' AND bISBlowfish='N' AND domain_id='".$domain_res['domain_id']."'";
		
		#echo $query;
		$resLogin = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		$UserDataCount = mysqli_fetch_assoc($resLogin);
		
		if($UserDataCount['anzahl'] > 0) {
			$query = "SELECT * FROM benutzer WHERE username='".$_POST['txtUsername']."' AND password='".md5($_POST['txtPasswort'])."' AND domain_id='".$domain_res['domain_id']."'";
		
			$resLogin = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$UserData = mysqli_fetch_assoc($resLogin);
		
			if($_POST['chkEingeloggtbleiben'] == 1)  {
				$strLogin = "Ja";
			} else {
				$strLogin = "Nein";
			}
			processPWD($UserData);
			
		} else {
			# BLOWFISH
			
			$query = "SELECT * FROM benutzer WHERE username='".$_POST['txtUsername']."' AND bISBlowfish='Y' AND domain_id='".$domain_res['domain_id']."'";	

			#echo $query;
			$resLogin = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			while($UserDataCount = mysqli_fetch_assoc($resLogin)) {
				
				if(encrypt_decrypt('encrypt',$_POST['txtPasswort']) == $UserDataCount['password']) {				
					$strMessage ='<font color="red">LOGIN OK</font>';
					#echo $_POST['txtPasswort'].' '.$UserDataCount['password'];
					processPWD($UserDataCount);
				}	else {
			#	echo $_POST['txtPasswort'].' - '.$UserDataCount['password'];
					$strMessage ='<font color="red">Falscher Login</font>';
				}			
			}			
	}
}
 
?>
<html>
	<head>
		<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
		<meta content="INDEX,FOLLOW" name="robots">
		<link media="all" href="../css/template_master.css" type="text/css" rel="stylesheet">
		<title>Login in den Administrationsbereich</title>
	</head>
	<body>
		<?php 
		if(!isset($_COOKIE['admin_user'])) {
		?>
			<div class="page">
			<div class="block block-cart" id="box_texthtml_21"><div class="block-title"> <h1>Anmeldung bei <?php echo $_SERVER['HOST']; ?></h1></div>
			<div class="content" id="modul_texthtml_21"><p>
			<h2>Anmeldung</h2>
			
			
			<?php echo $strMessage; ?>
			<form action="/ACP/login.php" method="POST">
					<div style="margin-bottom:7px">Shop-Domain:&nbsp; <?php echo $_SERVER['SERVER_NAME']; ?><br/></div>
					<div style="margin-bottom:7px;">Benutzername:<input type="text" style="margin-left:5px" name="txtUsername" autofocus/><br/></div>
					<div style="margin-bottom:7px">Passwort:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="password" name="txtPasswort"/><br/><br/></div>
					<label><input type="checkbox" value="Y" name="chkEingeloggtbleiben"/> Angemeldet bleiben</label><br/><br/>
					<input type="submit" class="button" value="Anmelden beim Shopste Administrationsbereich"/>
			</form>
			</p></div></div>
			</div>
		<?php 
		} else {
		?>
		<div class="page">
		<div class="block block-cart" id="box_texthtml_21"><div class="block-title"> <h1>Anmeldung zum Administrationsbereich</h1></div>
		<div class="content" id="modul_texthtml_21"><p>
		<h2>Anmeldung</h2>
		<?php echo $strMessage; ?>
		Sie sind bereits als <strong><?php echo $_COOKIE['admin_user'] ?></strong> angemeldet.<br/><br/>
		
		<a href="/index.php?modus=logout">Abmelden</a><br/>
		<a href="/">Zum Online Shop gehen</a>
		</p></div></div>
		</div>
		<?php
		}
		?>
<!-- Matomo -->
<script type="text/javascript">
  var _paq = window._paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(["setDocumentTitle", document.domain + "/" + document.title]);
  _paq.push(["setCookieDomain", "*.freie-welt.eu"]);
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="https://freie-welt.eu/framework/piwik/";
    _paq.push(['setTrackerUrl', u+'matomo.php']);
    _paq.push(['setSiteId', '1']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src="https://freie-welt.eu/framework/piwik/matomo.php?idsite=1&amp;rec=1" style="border:0;" alt="" /></p></noscript>
<!-- End Matomo Code -->		
	</body>	
</html>
<?php 
mysqli_close(DBi::$conn);
?>