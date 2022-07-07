<?php
	if(isset($_POST)) {
		session_start();
		include_once('../include/inc_config-data.php');
		require_once('../include/inc_basic-functions.php');
		
		// Login überprüfen
		$chkCookie = admin_cookie_check();

		#echo $chkCookie;
	if($_SESSION['login'] == 1) {	
			$_SESSION['login'] = 1;
		} else {
			exit(0);
		}
		
		$_POST = mysql_real_escape_array($_POST);
		#echo "IN";
		$query = "DELETE FROM `modul_".$_POST['module_name']."` WHERE  `id`=".$_POST['module_id'].";";
		$resDeleteModuleData = DBi::$conn->query($query) or die(mysqli_error());
						
		$query = "DELETE FROM `module_in_menue` WHERE  modul_id='".$_POST['module_id']."' AND `typ`='".$_POST['module_name']."';";
		$resDeleteModuleData = DBi::$conn->query($query) or die(mysqli_error());
	}
?>