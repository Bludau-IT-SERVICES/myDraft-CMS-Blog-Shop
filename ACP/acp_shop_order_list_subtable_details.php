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
	
	$query = "SELECT * FROM shop_order_list JOIN shop_item ON shop_order_list.shop_item_id = shop_item.shop_item_id WHERE id_shop_order='".$_GET['order_id']."'";
	$resShopOrderDetail = DBi::$conn->query($query) or die(mysqli_error());
	$html = '<table width=70%">';
	$html .= '<tr>
	<td><strong>Bild</strong></td>
	<td><strong>Artikelname</strong></td>
	<td><strong>Status</strong></td>
	<td><strong>Preis</strong></td></tr>';
	while($strOrderDetail = mysqli_fetch_assoc($resShopOrderDetail)) {
	
		$path = getPathUrl($_SESSION['language'],$strOrderDetail['menue_id']);
		$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$strOrderDetail['shop_item_id']."'";
		$strBild = mysqli_fetch_assoc(DBi::$conn->query($query));
		$html .= '<tr>
		<td><img src="'.$strBild['picture_url'].'" width="50px" height="50px"></td>
		<td><a href="'.$path.'">'.$strOrderDetail['name_de'].'</a></td>
		<td>'.$strOrderDetail['order_status'].'</td>
		<td>'.$strOrderDetail['preis'].'</td></tr>';		
	}
	$html .= '</table>';
	echo $html;
?>