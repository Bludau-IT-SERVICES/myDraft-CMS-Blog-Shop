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
	
	$query = "UPDATE modul_texthtml SET content_".$_SESSION['domainLanguage']."='".$_POST['content']."' WHERE id=".$_POST['texthtml_id'];
	DBi::$conn->query($query) or die(mysqli_error());
	echo '<div id="modul_texthtml_'.$_POST['texthtml_id'].'" class="content">'.$_POST['content'].'</div>';
?>