<?php
	@session_start();
	
   include("include/inc_config-data.php");
   include("include/inc_basic-functions.php");
	
	$_GET['score'] = DBi::mysql_escape($_GET['score'],DBi::$conn);
	$_GET['page_id'] = DBi::mysql_escape($_GET['page_id'],DBi::$conn);
	
	$query = "INSERT INTO seiten_bewertung(score,seiten_id,created_at) VALUES('".$_GET['score']."','".$_GET['page_id']."','".date("Y-m-d H:i:s")."')";
	DBi::$conn->query($query) or die(mysqli_error());
	
	$query = "SELECT sum(score) as ges, count(*) as anzahl  FROM seiten_bewertung where seiten_id='".$_GET['page_id']."'";
 	$res2 = DBi::$conn->query($query) or die(mysqli_error());	
	$strBewertung = mysqli_fetch_assoc($res2);
	$score = $strBewertung['ges'] / $strBewertung['anzahl'];
	if($score == '') {
		$score = $_GET['score'];
	}
	
	$query = "SELECT * FROM menue where id='".$_GET['page_id']."'";
 	$res3 = DBi::$conn->query($query) or die(mysqli_error());	
	$textname = mysqli_fetch_assoc($res3);
	$strURL = getPathUrl('de',$_GET['page_id']);
	mail("info@tsecurity.de","Vote Shopste Bewertung:".$textname['name_de']." | ".$score,"ID:".$_GET['page_id']."\n Neuer Score:".$score."Besucher:".$textname['visitors']." vom ".$textname['created_at'].'\n URL: '.$strURL);
	DBi::$conn->query($query) or die(mysqli_error());
?>