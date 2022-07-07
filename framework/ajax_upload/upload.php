<?php
$path = realpath($_SERVER["DOCUMENT_ROOT"]);

	require_once($path.'/include/inc_config-data.php');
	require_once($path.'/include/inc_basic-functions.php');
	require_once($path.'/include/inc_thumbnails.php');
// A list of permitted file extensions
$allowed = array('png', 'jpg', 'gif','zip');

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
	if(move_uploaded_file($_FILES['upl']['tmp_name'],$path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/orginal/".$_FILES['upl']['name'])){

		if(!empty($_FILES['upl']['name'])) {
		
			#if(url_check('http://shopste.com'.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/orginal/'.$_FILES['upl']['name'])) {
				
				$query = "SELECT count(*) as anzahl FROM shop_item_picture WHERE shop_item_id='".$_POST['shop_id']."'";
				$strHauptBildDa = mysql_fetch_assoc(mysql_query($query)); 
				$iNr = $strHauptBildDa['anzahl'];
				
				#############################################
				# >> Aktuelles Bild in Datenbank speichern
				#############################################
				$query = "INSERT INTO shop_item_picture (shop_item_id,picture_url,modus,picture_nr) VALUES ('".$_POST['shop_id']."','/portals/".$_SERVER['SERVER_NAME']."image/produkte/orginal/".mysql_real_escape_string($_FILES['upl']['name'])."','main-picture','".($iNr +1)."')";
				#$query = "INSERT INTO shop_item_picture (shop_item_id,picture_url,modus) VALUES ('1','/media/shop/1','main-picture')";
				mysql_query($query) or die(mysql_error()); 
				
				 
				$strMainPicture = $path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/orginal/".$_FILES['upl']['name'];
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
				$im = image_resample($img, 230, 200, "get", "000000");
				switch($type){
					case 'bmp': $img = imagewbmp($im,$path.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/kategorie/'.$_FILES['upl']['name']); break;
					case 'gif': $img = imagegif($im,$path.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/kategorie/'.$_FILES['upl']['name']); break;
					case 'jpg': $img = imagejpeg($im,$path.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/kategorie/'.$_FILES['upl']['name']); break;
					case 'png': $img = imagepng($im,$path.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/kategorie/'.$_FILES['upl']['name']); break;
					default : echo "Nicht unterstützt!";
				}
				imagedestroy($img);
 
				
				#########################################################
				# Detailansicht Bild erzeugen
				#########################################################
				#$path = realpath($_SERVER["DOCUMENT_ROOT"]);
				$strMainPicture = $path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/orginal/".$_FILES['upl']['name'];
				 $type = strtolower(substr(strrchr($strMainPicture,"."),1));
				 if($type == 'jpeg') $type = 'jpg';
				  switch($_FILES['upl']['type']){
					case 'image/gif': $img = imagecreatefromgif($strMainPicture); break;
					case 'image/jpeg': $img = imagecreatefromjpeg($strMainPicture); break;
					case 'image/png': $img = imagecreatefrompng($strMainPicture); break;
					default : echo "3. Dateityp nicht unterstützt! - ".$_FILES['upl']['type'];
				  }
				  
				$im = image_resample($img, 350, 350, "get", "000000");
				switch($type){
					case 'bmp': $img = imagewbmp($im,$path.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/detail/'.$_FILES['upl']['name']); break;
					case 'gif': $img = imagegif($im,$path.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/detail/'.$_FILES['upl']['name']); break;
					case 'jpg': $img = imagejpeg($im,$path.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/detail/'.$_FILES['upl']['name']); break;
					case 'png': $img = imagepng($im,$path.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/detail/'.$_FILES['upl']['name']); break;
					default : echo "Unsupported picture type!";
				}				
				imagedestroy($img);
				 
						
				mail("jbludau@bludau-media.de","Shopste Datei hochgeladen ".$_FILES["upl"]["name"].' für '.$_SERVER['SERVER_NAME'],'Upload: '.$_SERVER['SERVER_NAME'].'\n https://shopste.com/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/kategorie/'.$_FILES['upl']['name']);
		#	}
			
		}
		echo '{"status":"success"}';
		exit;
	}
}

echo '{"status":"error"}';
exit; 
?>