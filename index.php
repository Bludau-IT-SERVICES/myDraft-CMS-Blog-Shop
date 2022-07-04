<?php
######################################################################################################################
### >> BLUDAU MEDIA MYDRAFT "myDraft"
### ---------------------------------
### Datum: 14.04.2019 (v. 0.8.0)
### Version: 0.8.x
### Author: Bludau Media (CEO, Founder Jan Bludau) written by thE_iNviNciblE aka Horus Sirius
###
### About PLUGINS/MODULE
### --------------------
### /module/%name_of_module§/index.php (init function LoadModul_%name_of_module%)
### Changelog v.0.8.5 am 23.05.2019:
### - meta description Variable 
### - rss_content_view automatisierte description
### Changelog v.0.8.0:
### ------------------
### - mySQL Schema Updated for overall supporting created_at, updated_at (also needed for every own written plugin) to be full compatibel with myDraft 0.8.xml_error_string
### - beginn of "version_info.txt" every PLUGIN
### - PLESK-Support: default install path: define("CORE_INSTALL_PATH","/var/www/vhosts/".$_SERVER['SERVER_NAME']."/httpdocs/");
###
######################################################################################################################

######################################################################################################################
## >> DEV-SCHALTER
######################################################################################################################
#error_reporting(E_ALL); // Wirklich alle Fehlermeldungen ausgeben
#ini_set('display_errors', TRUE); // evtl. hilfreich

######################################################################################################################
# >> ERWARTBARE $_GET PARAMETER VON URL
# --------------------------------------
# - $_GET['page_id'] = SEITEN-ID
######################################################################################################################

######################################################################################################################
# >> SERVER-CONFIG VARS 
# ------------------------------------------
# - auf HTTP-HEADER und PHP-VARIABLEN ebene
######################################################################################################################

#header("Link: </templates/tsecurity.de/css/menu.css>; rel=preload; as=style,</templates/tsecurity.de/css/template_master.css>; rel=preload; as=style",false);
#header("Expires: Tue, 03 Jul 2019 06:00:00 GMT");
#header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
#header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
#header("Cache-Control: post-check=0, pre-check=0", false);
#header("Pragma: no-cache"); 
#header("Connection: close");
#header('Access-Control-Allow-Origin: *');
header("Cache-Control: max-age=14400");
header("Cache-Control: s-maxage=84000");
header("Cloudflare-CDN-Cache-Control:max-age=24400");
header("CDN-Cache-Control: 18000");
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + (60*60*24*45)) . ' GMT');
ini_set("session.gc_maxlifetime", 86400);
ini_set("session.cookie_lifetime", 86400);
#header_remove("X-Frame-Options");


##########################################
# >> PHP Session ID initalisieren
##########################################
session_start(); 

#################################
# Default Parameter laden 
# -- z.B. Sprache  
#################################
$_SESSION['language'] = 'de';
$_SESSION['domainLanguage'] = 'de';

# INSTALL-PATH
define("CORE_INSTALL_PATH","/var/www/vhosts/freie-welt.eu/httpdocs/");

# STANDARD INCLUDES 

include_once(CORE_INSTALL_PATH.'include/inc_config-data.php');

# BASIC CORE FUNCTIONS IN EVERY PLUGIN AVAILABLE
include_once(CORE_SERVER_PATH.'include/inc_basic-functions.php');

# LAYOUTING STUFF for modules
include_once(CORE_SERVER_PATH.'include/inc_buildbox.php');

# OPTIONAL: BASKET CASE 
include_once(CORE_SERVER_PATH.'cart/cart_info.php');	

# CORE-ADDON: Geräteerkennung: isMobile und isTablet oder Computer
require_once 'framework/php-mobile-detect/Mobile_Detect.php';

#####################################################################
# >> INIT GERÄTEERKENNUNG unter PHP
# ---------------------------------
# - phone
# - tablet
# - pc
#####################################################################

$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
$_SESSION['CORE_device_typ'] = $deviceType;
$client_type = 'desktop';
if($deviceType != 'phone' && $deviceType != 'tablet') {
	$_SESSION['CORE_default_module_list_item_count'] = CORE_DEFAULT_ITEMS_PER_PAGE_COMPUTER;
	$client_type = 'none_desktop';
} else {
	$_SESSION['CORE_default_module_list_item_count'] = CORE_DEFAULT_ITEMS_PER_PAGE_MOBILE;	
	$client_type = 'none_desktop';
}

##########################################
# >> CHECK Domain-Administrator logged in
##########################################

# basic_function.php
$chkCookie = admin_cookie_check();

if($chkCookie == 1) {		
	$_SESSION['login'] = 1;
} else {
	$_SESSION['login'] = 0;
}

#########################################################################
# >> Includes für MyDraft und Smarty
#########################################################################

# Root Pfad
define('MYDRAFT_DIR', $path.'/');

# Smarty Installationsverzeichnis
define('SMARTY_DIR', $path.'/framework/smarty/');
#$mydraft->register_function("function","search_lastest","search_lastest");

########################################################################
# IP Adresse für das Tracking holen + PROXY Support
#
# - bei Client Header Übermittelung (nicht anonymen Proxys, echte IP)
########################################################################

if (! isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	$client_ip = $_SERVER['REMOTE_ADDR'];
}
else {
	$client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
if(!isset($_COOKIE['trackid'])) {
	# IP wird zur tracking_id
	$trackid = md5($client_ip);
	# Cookie setzen
	setcookie("trackid",$trackid , time() + 2592000,"/", $_SERVER['SERVER_NAME']);
} else {
	# Wert aus Cookie holen
	$trackid = $_COOKIE['trackid'];
}

############################################################
# >> Aufgerufene Domain ermitteln 
# - bei z.B. * Domain Subdomains Alias
############################################################

$core_domain_info_ary = getDomainInfo();
$_SESSION['template_folder'] = "/".$core_domain_info_ary['template_folder'];  // Wird pro Domain festgelegt

# Initalisierung von Mydraft 
include(MYDRAFT_DIR . 'libs/mydraft.setup.php');

# Klasse laden
$mydraft = new Mydraft;

# CORE INIT 
# ---------
$mydraft->tpl->assign('layout_device_type', $deviceType);

#######################################################################################################
# >> WIRD KEINE SEITENID über HTTP GET Übertragen?
# ------------------------------------------------------
# - $_GET['page_id'] = Enthält MySQL Menü-ID / SEITEN-ID
# - $core_domain_info_ary['startseite'] = SEITEN-ID der Startseite der Domain (Menü-Root-0 Level)
#######################################################################################################

if(!isset($_GET['page_id'])) {
	
	$_GET['page_id'] = $core_domain_info_ary['startseite'];
	$_SESSION['page_id'] = $core_domain_info_ary['startseite'];
	
}else {
	// Sicherheitsprüfung ob Zahl
	if(is_numeric($_GET['page_id'])) {
		$_SESSION['page_id'] = $_GET['page_id'];	
	}
}

# >> COOKIE SETZTEN
setcookie("last_page",$_SESSION['page_id'] , time() + 2592000,"/", $_SERVER['SERVER_NAME']);

$_SESSION['domain_id'] = $core_domain_info_ary['domain_id'];
$_SESSION['suchtext'] = $_GET['suche'];

###################################################
# >> Weiterleitungen
###################################################
/* if($aryPage['domain_id'] != $domain_res['domain_id']) {
	 #echo "IN";
	 
	 # Hole Domainnamen aus Domain Tabelle auf Bassis von Seiten Domain ID
	 $query = "SELECT * FROM domains WHERE domain_id = '".$aryPage['domain_id']."'";
	 $res = DBi::$conn->query($query) or die(mysqli_error());
	 $strDomain = mysqli_fetch_assoc($res);
	 
	 # Ist bei dem Seiteneintrag eine Marktplatz Seite zugeordnet
	 $aryMarketPlaceID = getIsMarketPlacePageID($aryPage['id']);
	 
	 # 
	 if($aryMarketPlaceID['isMarketPlace'] == false) {
		 $path = getPathUrl('de',$aryMarketPlaceID['shopste_marktplatz_menue_id']);				
		 header('Location: '.CORE_HTTPS_METHOD.'://'.$strDomain['name'].'/'.$path, true, 301);
		 exit(0);
	 } else {
		if(strpos("https://shopste.com",$_SESSION['domain_name']) === false) {
			$path = getPathUrl('de',$aryPage['id']);		
			header('Location: '.$_SESSION['domain_name'].'/'.$path, true, 301);
			exit(0);
		}
		
	 }
	// #echo "NICHT KORREKT";	
	
 } */
 
##############################################################################################################
# >> GIBT ES KEINE DOMAIN IN DATENBANK?
## -----------------------------------------------
# - Smarty Template Variable $core_domain_info_ary['name'] = core_domain_name (Domain Name)
##############################################################################################################

if($core_domain_info_ary['name'] == '') {
	
	header("HTTP/1.0 404 Not Found");

	$error_group = 'CORE_INIT_PAGE';
	$error_text = 'Es konnte kein Domainnamen ermittelt werden';
	setCORE_error_msg($error_group,$error_text,$_GET['page_id']);
	
	# Template Seite laden
	$mydraft->displayCMSPage('error_pages/no_domain.tpl','no_domainname_found','false');
	mysqli_close(DBi::$conn);
	exit(0);
}

##############################################################################################################
# >> GIBT ES FÜR DOMAIN EINE FREISCHALTUNG PER EMAIL?
## -----------------------------------------------
# - Smarty Template Variable - core_domain_email_freischaltung = $core_domain_info_ary['email_freischaltung']
##############################################################################################################

if($core_domain_info_ary['email_freischaltung'] == 'N') {
	header("HTTP/1.0 404 Not Found");
	
	$error_group = 'CORE_INIT_PAGE';
	$error_text = 'Keine Emailfreischaltung';
	setCORE_error_msg($error_group,$error_text,$_GET['page_id']);
	
	# Template Seite laden
	$mydraft->displayCMSPage('error_pages/no_domain_email_activation.tpl','no_domain_activation','false');	
	mysqli_close(DBi::$conn);	   
	exit(0);
}

###########################################################################################
## >> Domain Setting: Globales Caching AKIV?
## -----------------------------------------------
## - $core_domain_info_ary['bGlobalCaching'] = Schalter für jede einzelne CMS Seite möglch
###########################################################################################

if($core_domain_info_ary['bGlobalCaching'] == 'Y') {
	
	// ADMIN-LOGIN 
	// - 1 = JA
	// - 0 = NEIN
	if($_SESSION['login'] == 1) {
		
		$bNoCache = true;
		$mydraft->tpl->assign('CORE_CACHE', 'NO');
	} else {		
	
		$bNoCache = false;
		$mydraft->tpl->assign('CORE_CACHE', 'YES');
	}
} else {
	$bNoCache = false;
	$mydraft->tpl->assign('CORE_CACHE', 'NO');
}

#########################################################################################################
# >> Domain Einstellung für HTTPS benutzen
# ------------------------------------------------------------
# - $core_domain_info_ary['bIsSSL'] = pro domain / subdomain steuerbar
# - stehen im Smarty Template auch als Template VARIABLEN zur Verfügung
# - PHP Konstante - "CORE_HTTPS" (boolean) - true / false / "CORE_HTTPS_METHOD" = NAME DER METHODE
########################################################################################################

if($core_domain_info_ary['bIsSSL'] == 'Y') {
	$strHTTP = 'https';
	$_SESSION['domain_method'] = 'https://';
	define("CORE_HTTPS",     "true");
	define("CORE_HTTPS_METHOD",     "https");
} else {		
	$strHTTP = 'http';
	$_SESSION['domain_method'] = 'http://';
	define("CORE_HTTPS",     "false");
	define("CORE_HTTPS_METHOD",     "http");	
}

##########################################################################################
# >> SESSION VARIABLEN (PHP)
# -----------------------------------------------------------------------------
# - $_SESSION['domain_id'] = domainid
																			   
# - $_SESSION['domain_ary'] = Domain Row als Array
# - $_SESSION['domain_method'] = HTTP-Protokoll (http://,http://)									 
# - $_SESSION['domain_name'] = enthalt den kompletten Domainnamen http / https
# - $_SESSION['login'] = ADMIN-LOGIN Ja(1)/Nein(0)
# - $_SESSION['page_id'] = SEITEN-ID in menue MySQL Tabelle
# - $_SESSION['CORE_device_typ'] = GERÄTETYP vom Benutzer
# - $_SESSION['CORE_default_module_list_item_count'] = CORE-Listing by Device
# - $_SESSION['template_folder'] = Template Folder, selected /templates/%mytemplate%/
##########################################################################################

if(CORE_HTTPS == "true") {	
	$_SESSION['domain_name'] = "https://".$_SERVER['HTTP_HOST'];
} else {
	$_SESSION['domain_name'] = "http://".$_SERVER['HTTP_HOST'];
}
																								   

####################################################
# >> Parameter: Modus
# -- index.php?modus=
####################################################

switch($_GET['modus']) {
	case 'logout':			
		include_once('ACP/login_abmeldung.php');
		break;
	case 'user_logout':
		$_SESSION['portal_login'] = 0;
		$_SESSION['portal_user'] = "";
		$_SESSION['portal_pwd'] = "";
		
		#Portal Login
		$res = setcookie("portal_pwd", time() - 2592000,"/", $_SERVER['SERVER_NAME']);
		$res = setcookie("portal_user", time() - 2592000,"/", $_SERVER['SERVER_NAME']);	
		$res = setcookie("portal_eingeloggt_bleiben", time() - 2592000,"/", $_SERVER['SERVER_NAME']);
		break;
}

##################################################
# >> Seiten Einstellungen laden
##################################################
function Fetch_Menue_Content($page_id,$typ,$position) {	
	$query = "SELECT * FROM ".$typ." WHERE id='".$page_id."'";
	$resModuleData = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
	$strModuleData = mysqli_fetch_assoc($resModuleData); 		
 
	return $strModuleData;
}

function Fetch_Modul_byPageID($page_id,$typ,$position) {	
 
	$query = "SELECT * FROM modul_".$typ." WHERE page_id='".$page_id."'";
	$resModuleData = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
	$strModuleData = mysqli_fetch_assoc($resModuleData); 		
 
	return $strModuleData;
}
function Fetch_Modul_byMenueID($page_id,$typ,$position) {	
 
	$query = "SELECT * FROM modul_".$typ." WHERE menue_id='".$page_id."'";
	$resModuleData = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
	$strModuleData = mysqli_fetch_assoc($resModuleData); 		
 
	return $strModuleData;
}

# SeitenID ist ein Pflichtfeld für das CMS
if(is_numeric($_GET['page_id'])) {

	####################################################
	# Seiteneinstellungen laden
	####################################################
	$aryPage = getPageSettings($_GET['page_id']);

	############################################################
	# Meta Description nach Content-Type
	############################################################
	if ($aryPage['content_type'] == 'news_content') {
		
		$PageMeta['title'] = Fetch_Modul_byMenueID($_GET['page_id'],'news_content_view',0);				
		$PageMeta['description'] = Fetch_Modul_byPageID($_GET['page_id'],'news_content',0);
		
	} elseif ($aryPage['content_type'] == 'normale_seite') {
		
		$PageMeta['title'] = Fetch_Menue_Content($_GET['page_id'],'menue',0);
		
	} elseif ($aryPage['content_type'] == 'news_kategorie') {
		
		$PageMeta['title'] = Fetch_Menue_Content($_GET['page_id'],'menue',0);
		
	} elseif ($aryPage['content_type'] == 'news_content') {
		
		$PageMeta['title'] = Fetch_Menue_Content($_GET['page_id'],'menue',0);
		
	} elseif ($aryPage['content_type'] == 'rss_content') {
		
		$PageMeta['title'] = Fetch_Modul_byMenueID($_GET['page_id'],'rss_content_view',0);
		$PageMeta['description'] = Fetch_Modul_byPageID($_GET['page_id'],'rss_content',0);		
	}
	#########################################################################
	# Template File - Festlegen im Template Order als *.tpl
	#########################################################################
	$template_file = $aryPage['template_file'];
	
		# Spezialseite: Suchen
		if (!empty($_SESSION['suchtext'])) {
			$PageMeta['title'] = "✅ Expertenwissen vom Thema:".$_SESSION['suchtext'];
			$PageMeta['description'] = "Durchsuchen Sie 🚀 Expertenwissen aus 1,2 Millionen Artikeln nach dem Thema ".$_SESSION['suchtext'];	
			$aryPage['content_type'] = 'suchen';
			
		} 
		
} else { 
	
	echo "<h1>Es gab diese Seiten-ID nie.</h1>";
	mysqli_close(DBi::$conn); 
	exit(0);
}

$path = realpath($_SERVER['DOCUMENT_ROOT']);  
require_once ($path."/framework/piwik/MatomoTracker.php");   
$t = new MatomoTracker( $idSite = 1,'https://freie-welt.eu/framework/piwik/');
$t->setTokenAuth(CORE_PIWIK_API_KEY);
$t->doTrackPageView($aryPage['titel_de']);
#$t->doTrackContentInteraction('submit', 'ImageAd', '/path/ad.jpg', 'http://www.example.com');
#$t->doTrackContentImpression('Text Ad', 'Click to download Piwik now', 'http://piwik.org/download');
$t->setIp($_SERVER['REMOTEADDR']);
#$t->setUrl($_SERVER['HTTPREFERER'].'&'.$_SERVER['QUERY_STRING']);
if(isset($_SERVER['HTTPREFERER']))
$t->setUrl($_SERVER['HTTPREFERER']);

if(!isset($_SESSION['customer_id'])) {
	$_SESSION['customer_id'] = random_bytes(2);	
}

$query = "INSERT INTO menue_visitors(visitor,page_id,kunden_id) VALUES('".bin2hex($_SESSION['customer_id'])."','".mysqli_real_escape_string(DBi::$conn,$_GET['page_id'])."','0')";
DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));

$query = "UPDATE menue SET visitors=visitors+1 WHERE id='".mysqli_real_escape_string(DBi::$conn,$_GET['page_id'])."'";
DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));


#####################################################
# >> Prüfung Datei aus Template Ordner laden
#####################################################

if($aryPage['template_file'] == '') {

	header("HTTP/1.0 404 Not Found"); // SEO FRIENDLY
	
	$error_group = 'CORE_INIT_PAGE';
	$error_text = '<h1>Kein gültiges Template gefunden.</h1> <h2>Konnte nicht geladen werden weil kein Menüeintrag für Domain.</h2>';
	
	#setCORE_error_msg($error_group,$error_text,$_GET['page_id']);
 	
	# Template Seite laden
	$mydraft->displayCMSPage('error_pages/no_template_file.tpl','no_template_file','false');	
    mysqli_close(DBi::$conn);
	exit(0);
} 
##########################################################################
# TEMPLATE VARIABLEN GENERIEREN 
# -----------------------------
# - canonical url holen (301, fehlt noch) $core_domain_info_ary['name']
##########################################################################

$aktuelle_url = $_SESSION['domain_name'].'/'.getPathUrl($_SESSION['language'],$_GET['page_id']);
	 
############################################
# >> CACHE STEUERUNG: isCached?
############################################

$strSeitenCacheID = $_GET['page_id'].'-'.$core_domain_info_ary['domain_id'].'-'.$_GET['suche'].'-'.$_GET['seite'].'-'.$_SESSION['login'].'-'.$deviceType;

$tpl = $mydraft->tpl->createTemplate($template_file, $strSeitenCacheID, '');	
$mydraft->tpl->assign('CACHED_TIMESTAMP', date("d.m.Y H:i:s",$tpl->cached->timestamp));	

if($aryPage['content_type'] == 'rss_kategorie') {
	$query = "SELECT * FROM module_in_menue JOIN modul_rss_categoryview ON module_in_menue.modul_id = modul_rss_categoryview.id WHERE module_in_menue.menue_id='".$_GET['page_id']."' AND module_in_menue.typ='rss_categoryview'";
	$strRSSCat = mysqli_fetch_assoc(DBi::$conn->query($query));
	
	$query = "SELECT * FROM modul_rss_content WHERE news_cat='".$strRSSCat['id']."' ORDER BY created_at DESC LIMIT 0,1";
	$strRSSLastContent = mysqli_fetch_assoc(DBi::$conn->query($query));

	if($strRSSLastContent['created_at'] >= date("Y-m-d H:i:s",$tpl->cached->timestamp)) {
		#echo "Neuer Inhalt: ".getDateDE($strRSSLastContent['created_at']);
		$bNoCache = false;
	} else {
		/*echo "Alter Stand <br>
		Kategorie-Datenstand: ".getDateDE($strRSSLastContent['created_at'])."<br/>
		Seitencache-Datum: ".date("d.m.Y H:i:s",$tpl->cached->timestamp); 
		*/
		$bNoCache = false;
	}
}

########################################
# - IST DIE SEITE IM CACHE VORHANDEN?
########################################
 
if(!$mydraft->tpl->isCached($template_file,$strSeitenCacheID,$bNoCache)) {

	# Seitenbewertung abrufen 
	$core_page_rating = getCORE_page_rating($_GET['page_id']);
		
	# Domain Namen 
	$mydraft->tpl->assign('domain_name', $_SESSION['domain_name']);
	
	# Domain Logo bestimmen
	if($core_domain_info_ary['logo_pfad'] == '') {
		$mydraft->tpl->assign('logo_pfad', '');
	} else {
		$mydraft->tpl->assign('logo_pfad', CORE_HTTPS_METHOD.'://'.$_SERVER['HTTP_HOST']."/".$core_domain_info_ary['logo_pfad']);
	}
						 
	################################################################################
	# >> Zuweisung der VARIABLEN an das aktuelle Template (zur Verfügung stellen)
	# + CACHED_TIMESTAMP
	# + CORE_CACHE
	# + layout_device_type
	################################################################################
																				 
	$mydraft->tpl->assign('page_http_uri',   $aktuelle_url);
	$mydraft->tpl->assign('page_url_cononical',   $aktuelle_url); 
	$mydraft->tpl->assign('core_domain_email_freischaltung', $core_domain_info_ary['email_freischaltung']);
	$mydraft->tpl->assign('core_domain_name', $core_domain_info_ary['name']);
	$mydraft->tpl->assign('page_title', strip_tags(getPageTitle($aryPage)));
																		 
 
	# META ANGABEN GENERIEREN
	if($PageMeta['title']['meta_title'] != '') {
		$mydraft->tpl->assign('meta_titel', strip_tags($PageMeta['title']['meta_title'])); 
	} else {		
		$mydraft->tpl->assign('meta_titel', strip_tags($PageMeta['title']['title_de'])); 
	}
	
	if($PageMeta['title']['meta_description'] != '') {
		$mydraft->tpl->assign('meta_description', strip_tags($PageMeta['title']['meta_description'])); 
	} else {
		$mydraft->tpl->assign('meta_description', str_replace('"',"&quot;",strip_tags($PageMeta['description']['AddText']))); 			
	}
 																	
	$mydraft->tpl->assign('meta_nofollow', $core_domain_info_ary['meta_nowfollow']); 
	$mydraft->tpl->assign('domain_id', $core_domain_info_ary['domain_id']);
	$mydraft->tpl->assign('google_webmaster', $core_domain_info_ary['google_webmaster']);
	$mydraft->tpl->assign('layout_style', $aryPage['layout']);
	$mydraft->tpl->assign('sys_score_page', $core_page_rating);
	$mydraft->tpl->assign('template_folder', $_SESSION['template_folder']);
	$mydraft->tpl->assign('admCheck', $_SESSION['login']);
	$mydraft->tpl->assign('page_id', $_SESSION['page_id']);
	$mydraft->tpl->assign('cart_info_bar', getCartFooterInfo());
	$mydraft->tpl->assign('trackid', $trackid);
	$mydraft->tpl->assign('aryPage', $aryPage);
	$mydraft->tpl->assign('domain_ary', $core_domain_info_ary);
	$mydraft->tpl->assign('core_https', CORE_HTTPS);
	$mydraft->tpl->assign('CORE_HTTPS_METHOD', CORE_HTTPS_METHOD);	
	$mydraft->tpl->assign('client_type', $client_type);
	$mydraft->tpl->assign('seite_aktuell', $_GET['seite']);
	$mydraft->tpl->assign('suche', $_GET['suche']);
	#$mydraft->tpl->assign('CACHED_TIMESTAMP', "");	 
}

###########################################################################
# >> CACHE STEUERUNG NACH SEITENTYP UND AUSGABE / OUTPUT displayCMSPage()
# ------------------------------------------------------------------------
# - $aryPage['content_type'] = Seitentyp aus MySQL Datenbank pro page_id
###########################################################################


switch($aryPage['content_type']) {
	
	# - Warenkorb Seite wird nie aus dem Cache geladen
	case 'warenkorb_seite':
		# Über $mydraft->displayCMSPage()
		# -------------------------------
		# - Seiten an CACHING ID erkennen		
		# - $template_file = Template Datei aus MySQL Datenbank pro Seite möglich
		# - $strSeitenCacheID = eindeutige id, besteht auf seiten_id (page_id)
		# - $bNoCache = überschreibt Cache-Verarbeitung (ja,nein) Einstellung
		$mydraft->displayCMSPage($template_file,$strSeitenCacheID,true);
		break;
		
	# - Normales System-Verhalten mit Cache-Steuerung aus domain_settings MySQL Tabelle
	default:
		# Über $mydraft->displayCMSPage()
		# -------------------------------
		# - Seiten an CACHING ID erkennen		
		# - $template_file = Template Datei aus MySQL Datenbank pro Seite möglich
		# - $strSeitenCacheID = eindeutige id, besteht auf seiten_id (page_id)
		# - $bNoCache = überschreibt Cache-Verarbeitung (ja,nein) Einstellung	
		$mydraft->displayCMSPage($template_file,$strSeitenCacheID,$bNoCache);
		break;
}

mysqli_close(DBi::$conn); 
?>