<?php
	require_once('../include/inc_config-data.php');
	require_once('../include/inc_basic-functions.php');
	
	$_POST = mysql_real_escape_array($_POST);
	$_GET = mysql_real_escape_array($_GET);
	
	# Domain bestimmmen
	if(!empty($_POST['domain_id']) && $_POST['domain_id'] != 'undefined') {
	

		#exit();
		$domain_res['domain_id'] = $_POST['domain_id'];
	
	} else {
		$domain = $_SERVER['HTTP_HOST'];
		$domain = str_replace("www.", "", $domain);
		$query = "SELECT * from domains WHERE name='$domain'";
		$domain_res = mysqli_fetch_assoc(DBi::$conn->query($query));
		#print_r($domain_res);
		
		#$domain_res['domain_id'] = $_POST['domain_id'];
	}	
	$strWhere = "WHERE name='".$_POST['option_name']."'";
	$strWhere .= " AND domain_id='".$domain_res['domain_id']."'";
	
	$query ="SELECT * FROM domain_settings ".$strWhere;
	$resOption = DBi::$conn->query($query) or die(mysqli_error());
	$strOption = mysqli_fetch_assoc($resOption);
	echo $strOption['value'];
?>