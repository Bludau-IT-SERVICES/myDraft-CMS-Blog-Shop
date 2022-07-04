<?php
@session_start();
#extract($_REQUEST);

// servlet that handles uploadprogress requests:
if ($upload_id) {
	$data = uploadprogress_get_info($upload_id);
	if (!$data)
		$data['error'] = 'upload id not found';
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


// display on completion of upload:
if ($UPLOAD_IDENTIFIER) {

	// Login überprüfen
	$chkCookie = admin_cookie_check();

	#echo $chkCookie;
	if($chkCookie == 1) {		
		$_SESSION['login'] = 1;
	} else {
		echo "KEIN LOGIN";
		exit(0);
	}
 
	#echo "<pre>";
	#print_r($_FILES);
	#echo "</pre>";
	
	$uploads_dir = 'media';
	
	#mkdir('/media/shop', 0777, true);
	
	#echo $_FILES['file1']['tmp_name'];
	#move_uploaded_file($_FILES['file1']['tmp_name'],"../media/shop/".$_FILES['file1']['name']);
	$path = realpath($_SERVER["DOCUMENT_ROOT"]);
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
	#move_uploaded_file($_FILES['file1']['tmp_name'],$path."/media/".$_FILES['file1']['name']);
	move_uploaded_file($_FILES['file1']['tmp_name'],$path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/orginal/".$_FILES['file1']['name']);
	
	
// display on completion of upload:
#if ($_POST['UPLOAD_IDENTIFIER']) {

 
	#echo "<pre>";
	#print_r($_FILES);
	#echo "</pre>";
	
	$uploads_dir = 'media';
	
	#mkdir('/media/shop', 0777, true);
	
	#echo $_FILES['file1']['tmp_name'];
	#move_uploaded_file($_FILES['file1']['tmp_name'],"../media/shop/".$_FILES['file1']['name']);
	$path = realpath($_SERVER["DOCUMENT_ROOT"]);
	#echo $path;
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
	
	#echo $_FILES['file1']['tmp_name'];
	#Bild verschieben
	move_uploaded_file($_FILES['file1']['tmp_name'],$path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/orginal/".$_FILES['file1']['name']);
	
	if(!empty($_FILES['file1']['name'])) {
		if(url_check('http://shopste.com'.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/orginal/'.$_FILES['file1']['name'])) {
			
			$query = "SELECT count(*) as anzahl FROM shop_item_picture WHERE shop_item_id='".$_POST['shop_id']."'";
			$strHauptBildDa = mysqli_fetch_assoc(DBi::$conn->query($query)); 
			$iNr = $strHauptBildDa['anzahl'];
			
			$query = "INSERT INTO shop_item_picture (shop_item_id,picture_url,modus,picture_nr) VALUES ('".$_POST['shop_id']."','/portals/".$_SERVER['SERVER_NAME']."image/produkte/orginal/".$_FILES['file1']['name']."','main-picture','".($iNr +1)."')";
			#$query = "INSERT INTO shop_item_picture (shop_item_id,picture_url,modus) VALUES ('1','/media/shop/1','main-picture')";
			DBi::$conn->query($query) or die(mysqli_error());
			
			################################################################
			# >> Kategorieansicht Bild
			################################################################
			echo $path.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/kategorie/'.$_FILES['file1']['name'];
			
			$strMainPicture = $path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/orginal/".$_FILES['file1']['name'];
			$type = strtolower(substr(strrchr($strMainPicture,"."),1));
			if($type == 'jpeg') $type = 'jpg';
			switch($type){
				case 'bmp': $img = imagecreatefromwbmp($strMainPicture); break;
				case 'gif': $img = imagecreatefromgif($strMainPicture); break;
				case 'jpg': $img = imagecreatefromjpeg($strMainPicture); break;
				case 'png': $img = imagecreatefrompng($strMainPicture); break;
				default : echo "Unsupported picture type!";
			}
			#$im = imagecreatefromjpeg($strMainPicture);
			$im = image_resample($img, 230, 200, "get", "000000");
			switch($type){
				case 'bmp': $img = imagewbmp($im,$path.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/kategorie/'.$_FILES['file1']['name']); break;
				case 'gif': $img = imagegif($im,$path.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/kategorie/'.$_FILES['file1']['name']); break;
				case 'jpg': $img = imagejpeg($im,$path.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/kategorie/'.$_FILES['file1']['name']); break;
				case 'png': $img = imagepng($im,$path.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/kategorie/'.$_FILES['file1']['name']); break;
				default : echo "Nicht unterstützt!";
			}
			#imagejpeg($im,$path.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/kategorie/'.$_FILES['file1']['name']);
			#exit;
			imagedestroy($img);
			
			#########################################################
			# Detailansicht Bild erzeugen
			#########################################################
			#$path = realpath($_SERVER["DOCUMENT_ROOT"]);
			$strMainPicture = $path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/orginal/".$_FILES['file1']['name'];
			 $type = strtolower(substr(strrchr($strMainPicture,"."),1));
			 if($type == 'jpeg') $type = 'jpg';
			  switch($type){
				case 'bmp': $img = imagecreatefromwbmp($strMainPicture); break;
				case 'gif': $img = imagecreatefromgif($strMainPicture); break;
				case 'jpg': $img = imagecreatefromjpeg($strMainPicture); break;
				case 'png': $img = imagecreatefrompng($strMainPicture); break;
				default : echo "Nicht unterstützt!";
			  }
			  
			#$im = imagecreatefromjpeg($path."/portals/".$_SERVER['SERVER_NAME']."image/produkte/orginal/".$_FILES['file1']['name']);
			$im = image_resample($img, 350, 350, "get", "000000");
			
			$type = strtolower(substr(strrchr($strMainPicture,"."),1));
			if($type == 'jpeg') $type = 'jpg';
			  switch($type){
				case 'bmp': $img = imagewbmp($im,$path.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/detail/'.$_FILES['file1']['name']); break;
				case 'gif': $img = imagegif($im,$path.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/detail/'.$_FILES['file1']['name']); break;
				case 'jpg': $img = imagejpeg($im,$path.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/detail/'.$_FILES['file1']['name']); break;
				case 'png': $img = imagepng($im,$path.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/detail/'.$_FILES['file1']['name']); break;
				default : echo "Unsupported picture type!";
			}
			#imagejpeg($im,$path.'/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/detail/'.$_FILES['file1']['name']);
			#exit;
			imagedestroy($im);
					
			mail("jbludau@bludau-media.de","Shopste Datei hochgeladen ".$_FILES["file1"]["name"].' für '.$_SERVER['SERVER_NAME'],'Upload: '.$_SERVER['SERVER_NAME'].'\n http://www.shopste.com/portals/'.$_SERVER['SERVER_NAME'].'image/produkte/kategorie/'.$_FILES['file1']['name']);
		}
		
	}
	#exit(0);
#}	
	exit;
}
	#print_r($_FILE);
?>