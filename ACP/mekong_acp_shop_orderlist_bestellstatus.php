<?php
	session_start();
	include_once('../include/inc_config-data.php');
	include_once('../include/inc_basic-functions.php');
	
	$_POST = mysql_real_escape_array($_POST);
	$_GET = mysql_real_escape_array($_GET);
	// Login überprüfen
	$chkCookie = admin_cookie_check();

	if($_SESSION['login'] == 1) {		
		$_SESSION['login'] = 1;
	} else { 
		echo "KEIN";
		exit(0);
	}
	#print_r($_POST);
	
	$query = "UPDATE shop_order SET ges_order_status='".$_POST['orderstatus']."' WHERE shop_order_id='".$_POST['order_id']."'";
	$res = DBi::$conn->query($query) or die(mysqli_error());

	$query = "UPDATE shop_order_list SET order_status='".$_POST['orderstatus']."' WHERE id_shop_order='".$_POST['order_id']."'";
	$res = DBi::$conn->query($query) or die(mysqli_error());
	

?>