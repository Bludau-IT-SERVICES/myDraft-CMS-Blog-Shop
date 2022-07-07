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
		
	if($_GET['modus'] == 'show_delete_warning') {		
		# Seiten Einstellungen laden
		echo '<input type="hidden" name="acp_page_id" id="acp_page_id" value="'.$_GET['page_id'].'">';
		$aryPage = getPageSettings($_GET['page_id']);
		
		echo "<h2>Seite '".$aryPage['name_de']."' l&ouml;schen</h2>";
		echo "Bitte stellen Sie sicher das es keine Untermenüpunkte mehr gibt!<br/>";
		echo '<button onClick="set_page_delete(\'Delete_page\')">Sind Sie wirklich sicher die Seite '.$aryPage['name_de'].' zu l&ouml;schen</button><br/><br/>';
	} else if($_GET['modus'] == 'page_delete') {
	
		$query = "SELECT count(*) as anzahl FROM menue_parent WHERE parent_id='".$_GET['page_id']."'";
		$strParentAnzahl = mysqli_fetch_assoc(DBi::$conn->query($query));
		if($strParentAnzahl['anzahl'] > 0) {
			echo "<h2>Seite NICHT gel&ouml;scht - Unterkategorie Problem</h2>
			<strong>Bitte löschen Sie zuerst die Unterkategorie, um eine Zerstörrung des Menüs zu verhindern.</strong><br/><br/>";
			exit;
		} else {
			# MODULE IN SEITE HOLEN
			$query = "SELECT * FROM module_in_menue WHERE menue_id='".$_GET['page_id']."'";
			$resModule = DBi::$conn->query($query) or die(mysqli_error());
			while($strModule = mysqli_fetch_assoc($resModule)) {
				# MODULE AUS EIGENER MODULTABELLE LÖSCHEN
				$query = "DELETE FROM modul_".$strModule['typ']." WHERE id='".$strModule['modul_id']."'";
				DBi::$conn->query($query) or die(mysqli_error());			
			}
			
			$query = "DELETE FROM module_in_menue WHERE menue_id='".$_GET['page_id']."'";
			#echo $query.'<br/>';
			DBi::$conn->query($query) or die(mysqli_error());		

			$query = "DELETE FROM menue_parent WHERE menue_id='".$_GET['page_id']."'";
			DBi::$conn->query($query) or die(mysqli_error());		
			#echo $query.'<br/>';
			$query = "DELETE FROM menue WHERE id='".$_GET['page_id']."'";
			DBi::$conn->query($query) or die(mysqli_error());			
			#echo $query.'<br/>';
			echo "<h2>Seite erfolgreich gel&ouml;scht!</h2>";
		}
	}
?>