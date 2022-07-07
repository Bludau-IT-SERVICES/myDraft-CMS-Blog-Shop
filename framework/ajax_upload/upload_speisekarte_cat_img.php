<?php
$path = realpath($_SERVER["DOCUMENT_ROOT"]);
@session_start();
require_once($path.'/include/inc_config-data.php');
require_once($path.'/include/inc_basic-functions.php');
require_once($path.'/include/inc_thumbnails.php');
  
// Login überprüfen
$chkCookie = admin_cookie_check();

#echo $chkCookie;
if($chkCookie == 1) {		
	$_SESSION['login'] = 1;
} else {
	echo "KEIN LOGIN";
	exit(0);
}
	
// servlet that handles uploadprogress requests:
if ($upload_id) {
	$data = uploadprogress_get_info($upload_id);
	if (!$data)
		$data['error'] = 'upload id nicht gefunden';
	else {		
		$avg_kb = $data['speed_average'] / 1024;
		if ($avg_kb<100)
			$avg_kb = round($avg_kb,1);
		else if ($avg_kb<10)
			$avg_kb = round($avg_kb,2);
		else $avg_kb = round($avg_kb);
		
		// two custom server calculations added to return data object:
		$data['kb_average'] = $avg_kb;
		$data['kb_uploaded'] = round($data['bytes_uploaded'] /1024);
	}
	
	echo json_encode($data);
	exit;
}
	
// A list of permitted file extensions
$allowed = array('png', 'jpg', 'gif');

#$_FILES = mysql_real_escape_array($_FILES);

if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

	$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

	if(!in_array(strtolower($extension), $allowed)){
		echo '{"status":"error"}';
		exit;
	}
		
		if(file_exists($path."/portals/".$_SERVER['SERVER_NAME']."image/") == false) {
			mkdir($path."/portals/".$_SERVER['SERVER_NAME']."image/",0777);
		}
		if(file_exists($path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/") == false) {
			mkdir($path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/",0777);
		}
		if(file_exists($path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/orginal/") == false) {
			mkdir($path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/orginal/",0777);
		}
		if(file_exists($path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/kategorie/") == false) {
			mkdir($path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/kategorie/",0777);
		}
		if(file_exists($path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/detail/") == false) {
			mkdir($path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/detail/",0777);
		}		
		
	#if(move_uploaded_file($_FILES['upl']['tmp_name'], 'uploads/'.$_FILES['upl']['name'])){
	if(move_uploaded_file($_FILES['upl']['tmp_name'],$path."/templates/goldenwok/media/kategoriebilder/".$_FILES['upl']['name'])){

		if(!empty($_FILES['upl']['name'])) {
		
			#if(url_check('http://shopste.com'.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/orginal/'.$_FILES['upl']['name'])) {
				
				#$query = "SELECT count(*) as anzahl FROM shop_item_picture WHERE shop_item_id='".$_POST['shop_id']."'";
				#$strHauptBildDa = mysql_fetch_assoc(mysql_query($query)); 
				#$iNr = $strHauptBildDa['anzahl'];
				
				#############################################
				# >> Aktuelles Bild in Datenbank speichern
				#############################################
				
				# Darf nur 1. Kategoriebild geben
				$query = "SELECT count(*) as anzahl FROM shop_categories_images WHERE shop_cat_id='".$_POST['shop_cat_id']."'";
				$resCountCatIMG = mysql_query($query);
				$strCountCatIMG = mysql_fetch_assoc($resCountCatIMG);
				
				# aktuelles Bild löschen
				if($strCountCatIMG > 0) {
					$query = "DELETE FROM shop_categories_images WHERE shop_cat_id='".$_POST['shop_cat_id']."'";
					$resCountCatIMG = mysql_query($query);					
				}
				
				$query = "INSERT INTO shop_categories_images (shop_cat_id,img_path) VALUES ('".$_POST['shop_cat_id']."','/templates/goldenwok/media/kategoriebilder/".mysql_real_escape_string($_FILES['upl']['name'])."')";
				#$query = "INSERT INTO shop_item_picture (shop_item_id,picture_url,modus) VALUES ('1','/media/shop/1','main-picture')";
				mysql_query($query) or die(mysql_error()); 
				
				 
				$strMainPicture = $path."/templates/goldenwok/media/".$_FILES['upl']['name'];
				$type = strtolower(substr(strrchr($strMainPicture,"."),1));
				
				################################################################
				# >> Kategorieansicht Bild
				################################################################				

				switch($_FILES['upl']['type']){
					case 'image/gif': $img = imagecreatefromgif($strMainPicture); break;
					case 'image/jpeg': $img = imagecreatefromjpeg($strMainPicture); break;
					case 'image/png': $img = imagecreatefrompng($strMainPicture); break;
					default : echo "1. Dateityp nicht unterstützt! - ".$_FILES['upl']['type'];
				}
				#$im = imagecreatefromjpeg($strMainPicture);
				$im = image_resample($img, 370, 342, "get", "000000");
				switch($type){
					case 'bmp': $img = imagewbmp($im,$path.'/templates/goldenwok/media/kategoriebilder/'.$_FILES['upl']['name']); break;
					case 'gif': $img = imagegif($im,$path.'/templates/goldenwok/media/kategoriebilder/'.$_FILES['upl']['name']); break;
					case 'jpg': $img = imagejpeg($im,$path.'/templates/goldenwok/media/kategoriebilder/'.$_FILES['upl']['name']); break;
					case 'png': $img = imagepng($im,$path.'/templates/goldenwok/media/kategoriebilder/'.$_FILES['upl']['name']); break;
					default : echo "Nicht unterstützt!";
				}
				imagedestroy($img);
  
						
				mail("jbludau@bludau-media.de","Speisekarte Kategoriebild hochgeladen ".$_FILES["upl"]["name"].' für '.$_SERVER['SERVER_NAME'],'Upload: '.$_SERVER['SERVER_NAME'].'\n https://shopste.com/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/kategorie/'.$_FILES['upl']['name']);
		#	}
			
		}
		echo '{"status":"success"}';
		exit;
	}
}

echo '{"status":"error"}';
exit; 