<?php 
	session_start();


	// Datenbankverbindung
	require_once('../include/inc_config-data.php');
	require_once('../include/inc_basic-functions.php');
	
	$_POST = mysql_real_escape_array($_POST);
	$_GET = mysql_real_escape_array($_GET);
	// Login überprüfen
	$chkCookie = admin_cookie_check();

	#echo $chkCookie;
	if($_SESSION['login'] == 1) {		
		$_SESSION['login'] = 1;
	} else {
		echo "KEIN LOGIN";
		exit(0);
	}	
	if(empty($_GET['shop_id'])) {
		$_GET['shop_id']  = $_SESSION['acp_shop_item_id'];
	}
	#if($_GET['shop_id']) {
	#echo $_GET['shop_id'].'A';
		$query ="SELECT * FROM shop_item_picture WHERE shop_item_id='".$_GET['shop_id']."'";
		$resQuery = DBi::$conn->query($query) or die(mysqli_error());
		while($data = mysqli_fetch_assoc($resQuery)) {
		
			#$pic_type = strtolower(strrchr($data['picture_url'],"."));
			#$pic_filename = str_replace($pic_type,"",$data['picture_url']);	
			#$strNewPic = str_replace($pic_filename,$pic_filename.'_catList'.$pic_type,$pic_filename);
			#$html .= '<img src="'.$data['picture_url'].'" border="0"/><br/>';
			$htmlPic .= '<img src="'.$data['picture_url'].'" border="0" style="float:left"/><br/>
			<input type="button" name="btnPictureDelete" onClick="javascript:setShopItemPictureDelete(\''.$data['shop_item_picture_id'].'\')" class="button" value="Bild löschen">';
		}
		$htmlPic .= '<div style="clear:both"></div>';

		echo $htmlPic;
	#}	
?>