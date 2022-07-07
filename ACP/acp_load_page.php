<?php 
	session_start();
	include_once('../include/inc_config-data.php');
	include_once('../include/inc_buildbox.php');
	include_once('../include/inc_basic-functions.php');
	# Seiten Einstellungen laden

	// Login überprüfen
	$chkCookie = admin_cookie_check();

	#echo $chkCookie;
	if($_SESSION['login'] == 1) {	
		$_SESSION['login'] = 1;
	} else {
		exit(0);
	}
	
	$_GET = mysql_real_escape_array($_GET);
	$aryPage = getPageSettings($_GET['page_id']);
	
	echo getPageLayoutHTML($aryPage);
?>