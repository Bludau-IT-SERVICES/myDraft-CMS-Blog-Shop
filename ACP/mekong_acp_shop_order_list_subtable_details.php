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
	$html = '<table width=100%">';
	$html .= '<tr>
	<td><strong>Artikelnummer</strong></td>
	<td><strong>Artikelname</strong></td>
	<td><strong>Menge</strong></td>
	<td><strong>Status</strong></td>
	<td><strong>Kommentar</strong></td>
	<td><strong>Preis</strong></td></tr>';
	while($strOrderDetail = mysqli_fetch_assoc($resShopOrderDetail)) {
	
		$html .= '<tr>
		<td>'.$strOrderDetail['item_number'].'</td>
		<td>'.$strOrderDetail['name_de'].' <strong>'.$strOrderDetail['shop_item_additional_info'].'<strong></td>
		<td  class="td_right">'.$strOrderDetail['order_menge'].'x</td>
		<td>'.$strOrderDetail['order_status'].'</td>
		<td>'.$strOrderDetail['shop_item_comment'].'</td>
		<td  class="td_right">'.number_format($strOrderDetail['preis'],2,',','.').' EUR</td></tr>';		
	}
	$html .= '</table>';
	echo $html;
?>