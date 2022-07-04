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
		exit(0);
	}
	
	$query = "SELECT * FROM shop_item_picture WHERE shop_item_picture_id='".$_POST['picture_id']."'";
			$resPicture = DBi::$conn->query($query) or die(mysqli_error());
			$path = realpath($_SERVER["DOCUMENT_ROOT"]);
			while($strPicture = mysqli_fetch_assoc($resPicture)) {
				// Existiert Datei 
				if (file_exists($path.$strPicture['picture_url'])) {
					// Orginal
					echo "L&ouml;sche Orginalbild: '".$strPicture['picture_url']."'<br/>";
					unlink($path.$strPicture['picture_url']);
					
					$strBild = str_replace("/produkte/orginal/","/produkte/kategorie/",$strPicture['picture_url']);
					if (file_exists($path.$strBild)) {	
						echo "L&ouml;sche Kategoriebild: '".$strPicture['picture_url']."'<br/>";
						unlink($path.$strBild);	
					}
					$strBild = str_replace("/produkte/orginal/","/produkte/detail/",$strPicture['picture_url']);
					if (file_exists($path.$strBild)) {
						echo "L&ouml;sche Detailansicht: '".$strPicture['picture_url']."'<br/>";
						unlink($path.$strBild);
					}
					
				}
				$picture_url = $strPicture['picture_url'];
			}
		$query = "";
		
		$query = "DELETE FROM shop_item_picture WHERE shop_item_picture_id='".$_POST['picture_id']."'";
		
		DBi::$conn->query($query) or die(mysqli_error());
		
		mail('jbludau@cubss.net',"Shopste Bild gelöscht".$path.$picture_url,'für '.$_SERVER['SERVER_NAME'].' Pfad: '.$path.$picture_url);
?>