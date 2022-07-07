<?php
 
@session_start();

#$_SESSION['page_id'] = $_GET['page_id'];
$_SESSION['language'] = 'de';
$_SESSION['domain_name'] = "http://".$_SERVER['HTTP_HOST'];
// Sprachauswahl
$_SESSION['domainLanguage'] = 'de';

$path = realpath ($_SERVER["DOCUMENT_ROOT"]);
if (file_exists(dirname(__FILE__).'/include/inc_config-data.php')) {
	include_once($path.'/include/inc_config-data.php'); 
	include_once($path.'/include/inc_basic-functions.php');
	include_once($path.'/include/inc_pagging.php');
} else {
	include_once(dirname(__FILE__).'/../../include/inc_config-data.php');
	include_once(dirname(__FILE__).'/../../include/inc_basic-functions.php');
	include_once(dirname(__FILE__).'/../../include/inc_pagging.php');
}
 
$bytes = random_bytes(2);

$query = "INSERT INTO menue_visitors(visitor,page_id,kunden_id) VALUES('".bin2hex($bytes)."','".mysqli_real_escape_string(DBi::$conn,$_GET['page_id'])."','0')";
DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));

$query = "UPDATE menue SET visitors=visitors+1 WHERE id='".mysqli_real_escape_string(DBi::$conn,$_GET['page_id'])."'";
DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));


#mysqli_real_escape_string($_GET['rss_id']
$query = "SELECT Webseite,AddTitel FROM modul_rss_content WHERE news_content_id=".mysqli_real_escape_string(DBi::$conn,$_GET['rss_id']);
$rss_url_data = mysqli_fetch_assoc(DBi::$conn->query($query));
#echo $rss_url_data['Webseite'];
#echo $query;
mysqli_close(DBi::$conn);																							   
																								   
$path = realpath($_SERVER['DOCUMENT_ROOT']);  
require_once ($path."/framework/piwik/MatomoTracker.php");   
$t = new MatomoTracker( $idSite = 1,'https://matomo.freie-welt.eu/');
$t->setTokenAuth(CORE_PIWIK_API_KEY);
$t->doTrackPageView('W= '.$rss_url_data['AddTitel']);
$t->setIp($_SERVER['REMOTEADDR']);
if(isset($_SERVER['HTTPREFERER']))
$t->setUrl($_SERVER['HTTPREFERER']);

header('Location:'.str_replace('&amp;','&',urldecode($rss_url_data['Webseite'])));
?>