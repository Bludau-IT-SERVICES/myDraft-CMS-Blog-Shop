<h1>Benutzerverwaltung</h1>
<?php
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
	
function getUserTable() {
	# Benutzerliste abrufen
	$query = "SELECT * FROM benutzer WHERE domain_id='".$_SESSION['domain_id']."' ORDER BY username DESC";
	$resUserData = DBi::$conn->query($query);
	
	# Anzahl Benutzer zählen 0 =< 1 Benutzer kein löschen mehr
	$query = "SELECT count(*) as anzahl FROM benutzer WHERE domain_id='".$_SESSION['domain_id']."' ORDER BY username DESC";
	$resUserDataCount = DBi::$conn->query($query);
	$UserCount = mysqli_fetch_assoc($resUserDataCount);
	
	$html = '<br/><span  onClick="setLocationOpen(\'/ACP/acp_benutzerverwaltung.php?modus=new\')" id="gui_userverwaltung_new" class="spanlink">Neuen Benutzer anlegen</span><br/><br/>';
	
	$html .='<table width="100%" id="gui_userverwaltung">
<tr>
	<td><strong>Benutzername</strong></td>
	<td><strong>Name</strong></td>
	<td><strong>Email</strong></td>
	<td><strong>Aktion</strong></td>
</tr>';

	while($UserData = mysqli_fetch_assoc($resUserData)) {
		$html .= '<tr>
			<td>'.$UserData['username'].'</td>
			<td>'.$UserData['vorname'].' '.$UserData['nachname'].'</td>
			<td>'.$UserData['email'].'</td>
			<td><span class="spanlink" onClick="setLocationOpen(\'/ACP/acp_benutzerverwaltung.php?modus=change&uid='.$UserData['id'].'\')" id="gui_userverwaltung_change">ver&auml;ndern</span> | ';
		if($UserCount['anzahl'] <= 1) {
			$html .='(nicht l&ouml;schbar)</td>';			
		} else {
			$html .='<span  onClick="setLocationOpen(\'/ACP/acp_benutzerverwaltung.php?modus=delete&uid='.$UserData['id'].'\')" id="gui_userverwaltung_delete" class="spanlink">l&ouml;schen</span></td>';						
		}
		
		$html .= '</tr>';
	}
	$html .= '</table>';
	
	return $html;
}	
	
	if(isset($_GET['modus'])) {
		$_POST['acp_get_modus'] = $_GET['modus'];
	}
	# Modi Auswahl
	switch($_POST['acp_get_modus'])  {
		case 'user_delete_now':
			$query = "DELETE FROM benutzer WHERE id='".$_GET['uid']."'";
			DBi::$conn->query($query);
			echo "Benutzer wurde gel&ouml;scht.";
			break;
		case 'change_save':
		
			# Beide Passwörter gleich
			if($_POST['txtPasswort1'] == $_POST['txtPasswort2']) {
				
				# Blowfish Passwort generieren
				$options = [
					'cost' => 11,
					'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
				];
				
				$hash =  password_hash($_POST['txtPasswort1'], PASSWORD_BCRYPT, $options);
				
				$query = "UPDATE benutzer SET username='".$_POST['txtBenutzername']."',password='".$hash."' ,vorname='".$_POST['txtVorName']."',nachname='".$_POST['txtNachName']."' WHERE email='".$_POST['txtEmail']."' AND domain_id='".$_SESSION['domain_id']."'";				
				DBi::$conn->query($query);
				
				echo getUserTable();
			}
			exit(0);
			break;
		case 'user_add_new_save':
		
			# Blowfish Passwort generieren
			$options = [
				'cost' => 11,
				'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
			];
			
			$hash =  password_hash($_POST['txtPasswort1'], PASSWORD_BCRYPT, $options);
				
			$query = "INSERT INTO benutzer(username,password,erstellt_am,domain_id,vorname,nachname,email,profile_id,email_crc,email_validate,email_freischaltung_datum) VALUES('".$_POST['txtBenutzername']."','".$hash."','".date("Y-m-d H:i:s")."','".$_SESSION['domain_id']."','".$_POST['txtVorName']."','".$_POST['txtNachName']."','".$_POST['txtEmail']."','1','".crc32(date("Y-m-d H:i:s"))."','Y','".date("Y-m-d H:i:s")."')";
			DBi::$conn->query($query);
			
			echo getUserTable();
			exit(0);
			break;
	}
?>
<?php
	# Modus auslesen
	switch($_GET['modus']) {
		case 'new':
			echo '<h2>Benutzer neu Anlegen</h2>';
			$strButtonText = 'erstellen';
			echo '<form name="frmUserSettingChange" id="acp_user_save" action="/ACP/acp_benutzerverwaltung.php" method="POST" onSubmit="return benutzer_save_item_form(\'acp_user_save\');">';
				echo 'Benutzername: <input type="text" id="txtBenutzername" name="txtBenutzername" value="'.$UserData['username'].'"/><span id="txtBenutzername_err"></span>';
				echo 'Vorname: <input type="text" id="txtVorName" name="txtVorName"  value="'.$UserData['vorname'].'"/><span id="txtVorName_err"></span>';
				echo 'Nachname: <input type="text" id="txtNachName" name="txtNachName" value="'.$UserData['nachname'].'"/><span id="txtNachName_err"></span>';
				echo 'Email: <input type="text" id="txtEmail" name="txtEmail" value="'.$UserData['email'].'"/><span id="txtEmail_err"></span>';
				echo 'Passwort: <input type="password" id="txtPasswort1" name="txtPasswort1"/><span id="txtPasswort1_err"></span>';
				echo 'Passwort Wiederholung <input id="txtPasswort2" type="password" name="txtPasswort2"/><span id="txtPasswort2_err"></span>';
				echo '<input type="hidden" id="acp_get_modus" name="acp_get_modus" value="user_add_new_save"/>';
				echo '<input type="submit" name="btnBenutzerChangeSenden" value="Benutzer '.$strButtonText.'"/>';
			echo '</form>';			
			break;
		case 'change':
			echo '<h2>Benutzer ver&auml;ndern</h2><br/>';
			$strButtonText = 'ver&auml;ndern';
			$query = "SELECT * FROM benutzer WHERE domain_id='".$_SESSION['domain_id']."' AND id='".$_GET['uid']."' ORDER BY username DESC";
			$resUserData = DBi::$conn->query($query);
			$UserData = mysqli_fetch_assoc($resUserData);
			#print_r($UserData);
			echo '<form name="frmUserSettingChange" id="acp_user_save" action="/ACP/acp_benutzerverwaltung.php" method="POST" onSubmit="return benutzer_save_item_form(\'acp_user_save\');">';
				echo 'Benutzername: <input type="text" id="txtBenutzername" name="txtBenutzername" value="'.$UserData['username'].'"/><span id="txtBenutzername_err"></span>';
				echo 'Vorname: <input type="text" id="txtVorName" name="txtVorName"  value="'.$UserData['vorname'].'"/><span id="txtVorName_err"></span>';
				echo 'Nachname: <input type="text" id="txtNachName" name="txtNachName" value="'.$UserData['nachname'].'"/><span id="txtNachName_err"></span>';
				echo 'Email: <input type="text" id="txtEmail" name="txtEmail" value="'.$UserData['email'].'"/><span id="txtEmail_err"></span>';
				echo 'Passwort: <input type="password" id="txtPasswort1" name="txtPasswort1"/><span id="txtPasswort1_err"></span>';
				echo 'Passwort Wiederholung <input id="txtPasswort2" type="password" name="txtPasswort2"/><span id="txtPasswort2_err"></span>';
				echo '<input type="hidden" id="acp_get_modus" name="acp_get_modus" value="change_save"/>';
				echo '<input type="submit" name="btnBenutzerChangeSenden" value="Benutzer '.$strButtonText.'"/>';
			echo '</form>';
			break;
		case 'delete':
			$query = "SELECT * FROM benutzer WHERE domain_id='".$_SESSION['domain_id']."' AND id='".$_GET['uid']."' ORDER BY username DESC";
			$resUserData = DBi::$conn->query($query);
			$UserData = mysqli_fetch_assoc($resUserData);
			echo "<br/><h2>Benutzer ".$UserData['username']." l&ouml;schen?</h2><br/>";
			echo '<span class="spanlink" onClick="setLocationOpen(\'/ACP/acp_benutzerverwaltung.php?modus=user_delete_now&uid='.$UserData['id'].'\')" id="gui_userverwaltung_change">'.$UserData['username'].' l&ouml;schen</span> ||| ';
			echo '<span class="spanlink" onClick="setLocationOpen(\'/ACP/acp_benutzerverwaltung.php\')" id="gui_userverwaltung_change"> NEIN, nicht l&ouml;schen </span><br/>';
			break;
		case 'delete':
			echo '<h2>Benutzer l&ouml;schen</h2>';
			break;
	}
?>	

<?php
	echo getUserTable();
?>
