<?php
	session_start();
	require_once('../include/inc_config-data.php');
	require_once('../include/inc_basic-functions.php');
	
	$_POST = mysql_real_escape_array($_POST);
	$_GET = mysql_real_escape_array($_GET);
	
	$query = "INSERT INTO menue_visitors(visitor,page_id,kunden_id) VALUES('".$_POST['uid']."','".$_POST['page_id']."','".$_POST['kuid']."')";
	DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
	 
	$query = "UPDATE menue SET visitors=visitors+1 WHERE id='".$_POST['page_id']."'";
	DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
	
	mysqli_close(DBi::$conn);
?>