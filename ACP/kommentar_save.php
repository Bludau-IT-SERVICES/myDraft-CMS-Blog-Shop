<?php
@session_start();
	include("../include/inc_config-data.php");
	include("../include/inc_basic-functions.php");
	
#################################
# >> Default Parameter laden 
# -- z.B. Sprache  
#################################
$_SESSION['language'] = 'de';
$_SESSION['domainLanguage'] = 'de';

	$_POST = mysql_real_escape_array($_POST);
	
	$query = "INSERT INTO modul_kommentar_content(email,title_de,content_de,menue_id) VALUES('".$_POST['txtEmail']."','".htmlentities(html_entity_decode($_POST['txtTitle']))."','".htmlentities(html_entity_decode($_POST['txtKommentar']))."','".$_POST['txtMenue']."')"; 
	mysql_query($query) or die(mysql_error());
	$id = mysql_insert_id();
	
	if(isset($_POST['txtKommentar_id'])) {
		$parrent = $_POST['txtKommentar_id'];
	} else {
		$parrent = 0;
	}
	
	$query = "INSERT INTO modul_kommentar_parent(kommentar_id,kommentar_parent,menue_id) VALUES('".$id."','".$parrent."','".$_POST['txtMenue']."')";
	mysql_query($query) or die(mysql_error());
	
	
	$query = "SELECT DISTINCT email FROM modul_kommentar_content";
	$resEmailList = mysql_query($query);
	
	while($email = mysql_fetch_assoc($resEmailList)) {
		$emailList .= $email['email'].',';
	}
	
	$emailList = substr($emailList,0,strlen($emailList) -1);
	$url .= '<a href="'.$_SESSION['domain_name'].'/'.getPathUrl($_SESSION['language'],$_POST['txtMenue']).'">'.$_POST['txtTitle'].'</a>';
	
		$aryPage = getPageSettings($_POST['txtMenue']);
		$strPageInfo .= 'Titel: '. htmlentities($aryPage['titel_de'])."<br/>";
		$strPageInfo .= 'Erstellt am: '. $aryPage['created_at']."<br/>";
	
	
	$headers  = "From: info@tsecurity.de\r\n" .
	"X-Mailer: php\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$headers .= "Bcc: $emailList\r\n";

	mail("info@tsecurity.de","Kommentar: ".$aryPage['titel_de'],'<br>'.$strPageInfo.'<br/>Inhalt:'.htmlentities(html_entity_decode($_POST['txtKommentar'])).'<br><br>&Ouml;ffnen: '.$url.'<br>',$headers);
?>