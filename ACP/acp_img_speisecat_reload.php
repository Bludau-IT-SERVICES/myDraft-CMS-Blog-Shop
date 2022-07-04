<?php
	@session_start();
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
	#sleep(2000);
	$query = "SELECT * FROM shop_categories_images WHERE shop_cat_id='".$_GET['shop_cat_id']."'";
	$resCountCatIMG = DBi::$conn->query($query);
	$strCountCatIMG = mysqli_fetch_assoc($resCountCatIMG);
	echo '<img src="'.$strCountCatIMG['img_path'].'"/>';

?>