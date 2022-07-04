<?php
	session_start();
	include_once('../include/inc_config-data.php');
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
	
	$query = "UPDATE modul_".$_GET['modul']. " SET title_".$_SESSION['domainLanguage']."='".$_GET['content']."' WHERE id=".$_GET['id'];
	DBi::$conn->query($query) or die(mysqli_error());
	echo "<h3>".$_GET['content']."</h3>";
?>