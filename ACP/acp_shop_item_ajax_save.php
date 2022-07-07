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
	
	switch($_GET['modus']) {
		case 'new_item':
		
			if(is_numeric($_GET['content_price']) == true && strlen($_GET['content_name']) > 0 && strlen($_GET['content_number']) > 0) {
				$query = "INSERT INTO shop_item(name_de,preis,shop_cat_id,item_number) VALUES('".$_GET['content_name']."','".$_GET['content_price']."','".$_GET['content_cat_id']."','".$_GET['content_number']."')";
				DBi::$conn->query($query);
				echo "Speise gespeichert.";
			}
			
			break;
		case 'shop_item_price':
			$query = "UPDATE shop_item SET preis='".trim(str_replace(',','.',str_replace('€','',$_GET['content'])))."' WHERE shop_item_id='".$_GET['id']."'";
			DBi::$conn->query($query) or die(mysqli_error());
			#echo '<div onclick="javascript:setShop_item_change(\''.$_GET['id'].'\')" id="shop_item_name_'.$_GET['id'].'" class="item_description autoheight'.$iItemCount.'">'.$_GET['content'].'</div>';
			echo $_GET['content'];		
			break;
		case 'shop_item_number':
			$query = "UPDATE shop_item SET item_number='".trim($_GET['content'])."' WHERE shop_item_id='".$_GET['id']."'";
			DBi::$conn->query($query) or die(mysqli_error());
			#echo '<div onclick="javascript:setShop_item_change(\''.$_GET['id'].'\')" id="shop_item_name_'.$_GET['id'].'" class="item_description autoheight'.$iItemCount.'">'.$_GET['content'].'</div>';
			echo $_GET['content'];		
			break;
		case 'shop_item_name':
			$query = "UPDATE shop_item SET name_de='".trim($_GET['content'])."' WHERE shop_item_id='".$_GET['id']."'";
			DBi::$conn->query($query) or die(mysqli_error());
			#echo '<div onclick="javascript:setShop_item_change(\''.$_GET['id'].'\')" id="shop_item_name_'.$_GET['id'].'" class="item_description autoheight'.$iItemCount.'">'.$_GET['content'].'</div>';
			echo $_GET['content'];
			break;
		
	}
?>	