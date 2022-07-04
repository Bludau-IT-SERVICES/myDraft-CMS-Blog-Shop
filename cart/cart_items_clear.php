<?php
	session_start();
	mail("info@shopste.com","Sofortkauf Abbruch","Sofortkauf Abbruch".$_SESSION['shop_cart_ids']);
	$_SESSION['shop_cart_ids'] = '';
?>