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
	
	$query = "SELECT * FROM shop_order JOIN shop_order_customer ON shop_order.ges_order_customer_id = shop_order_customer.shop_order_customer_id WHERE shop_order.domain_id='".$_SESSION['domain_id']."' ORDER BY shop_order.created_at DESC";
	$resOrderMeta = DBi::$conn->query($query)or die(mysqli_error());
	$html = '
	<h1>Shop Bestellungen</h1>
	<table width=100%>';
	$html .= '<tr>
	<td>Order ID</td>
	<td>Name + Stadt</td>
	<td>Status</td>
	<td>Gewicht</td>
	<td>Versandkosten</td>
	<td>Endsumme</td>
	<td>Bestelldatum</td></tr>';
	$bIn = false;
	while($Order_meta = mysqli_fetch_assoc($resOrderMeta)) {
		$bIn = true;
		$html .= '<tr>
		<td>'.$Order_meta['shop_order_id'].' ('.$Order_meta['ges_order_anzahl'].')</td>
		<td><a class="spanlink" onClick="shop_order_subtable_details(\''.$Order_meta['shop_order_id'].'\')">'.$Order_meta['vorname'].' '.$Order_meta['nachname'].'</a> ('.$Order_meta['stadt'].')</td>
		<td>'.$Order_meta['ges_order_status'].'</td>
		<td>'.$Order_meta['ges_order_gewicht'].' KG</td>
		<td>'.$Order_meta['ges_order_versandkosten'].' EUR</td>
		<td>'.$Order_meta['ges_order_endsumme'].' EUR</td>
		<td>'.$Order_meta['created_at'].'</td>
		<tr>
			<td colspan="6"><div id="shop_order_details_'.$Order_meta['shop_order_id'].'"></div></td>
		</tr>
		</tr>';	
	}
	$html .= '</table>';
	if ($bIn == false) {
		$html = '<h2>Shop Bestellungen</h2>';
		$html .= '<h3>Es gibt noch keine Bestellungen!</h3>
		Bitte machen Sie mindestens eine Testbestellung!<br/><br/>';		
	}
	echo $html;
?>