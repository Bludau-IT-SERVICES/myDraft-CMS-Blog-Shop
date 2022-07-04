<?php
	session_start();
	include_once('../include/inc_config-data.php');
	include_once('../include/inc_buildbox.php');
	include_once('../include/inc_basic-functions.php');
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
	
	if($_GET['modul_direction'] == 'up') {
		$query = "UPDATE module_in_menue SET position = position -1 WHERE modul_id='".$_GET['id']."' AND typ='".$_GET['modul']."';";
		DBi::$conn->query($query) or die(mysqli_error());
		
	} else {
		$query = "UPDATE module_in_menue SET position = position +1 WHERE modul_id='".$_GET['id']."' AND typ='".$_GET['modul']."';";
		DBi::$conn->query($query) or die(mysqli_error());	
		
	}
	
	# Seiten Einstellungen laden
	$aryPage = getPageSettings($_GET['page_id']);
	
	echo getPageLayoutHTML($aryPage);
?>