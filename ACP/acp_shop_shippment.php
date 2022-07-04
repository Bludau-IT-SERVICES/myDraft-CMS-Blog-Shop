<?php 
	session_start();
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
	
	if($_POST['modus'] == 'delete_item') {
		$query = "DELETE FROM shop_shippment_detail WHERE shop_shippment_detail_id='".$_POST['shop_shippment_detail_id']."'";
		DBi::$conn->query($query) or die(mysqli_error());
		exit;
	}	
	if($_POST['modus'] == 'newline') {
		$query = "INSERT INTO shop_shippment_detail(shop_shippment_id,gewicht_von,gewicht_bis,versandkosten,domain_id) VALUES('".$_POST['shippment_id']."','0.0','0.0','0.0','".$_SESSION['domain_id']."');";
		DBi::$conn->query($query) or die(mysqli_error());
		$iID = mysqli_insert_id(DBi::$conn);
		$html .= '<div id="shippment_item_'.$iID.'">';
			$html .= '<input type="text" name="gewicht_von['.$iID.']" value="0.0"/>KG ';
			$html .= '<input type="text" name="gewicht_bis['.$iID.']" value="0.0"/>KG ';
			$html .= '<input type="text" name="versandkosten['.$iID.']" value="0.0"/> EUR <a href="#" onClick="shop_shippment_delete(\''.$iID.'\')">L&ouml;schen</a><br/><br/>';
		$html .= '</div>';
		echo $html;
		exit;
	}
	if($_POST['modus'] == 'versandart_newline') {
		if($_POST['txtVersandartNewName'] != '') {
			$query = "INSERT INTO shop_shippment(name_de,domain_id,mwst,created_at) VALUES('".$_POST['txtVersandartNewName']."','".$_SESSION['domain_id']."','".str_replace(",",".",$_POST['txtVersandartMWST'])."','".date("Y-m-d H:i:s")."');";
			DBi::$conn->query($query) or die(mysqli_error());
			$html .= '<h2>Neue Versandart gespeichert</h2>';
			
		}
	}
	if($_POST['modus'] == 'versandart_edit') {
		$query = "UPDATE shop_shippment SET name_de='".$_POST['txtVersandartNewName']."',mwst='".str_replace(",",".",$_POST['txtVersandartMWST'])."' WHERE shop_shippment_id='".$_POST['versandart_edit_id']."'";
		$resData = DBi::$conn->query($query) or die(mysqli_error());
		#$html .= '<h2>Änderungen an den Versandkosten gespeichert</h2>';
	}
	
	if($_POST['modus'] == 'versandart_delete') {
		$query = "DELETE FROM shop_shippment WHERE shop_shippment_id='".$_POST['versandart_id']."'";
		DBi::$conn->query($query) or die(mysqli_error());
		
		$query = "DELETE FROM shop_shippment_detail WHERE shop_shippment_id='".$_POST['versandart_id']."'";
		DBi::$conn->query($query) or die(mysqli_error());	
	}	
	if($_POST['frmsenden'] == 'true') {
		$gewicht_von = $_POST['gewicht_von'];
		$gewicht_bis = $_POST['gewicht_bis'];
		$versandkosten = $_POST['versandkosten'];
		foreach( $gewicht_von as $key => $n ) {
			print "Nummer '".$key."' | gewicht von ".$gewicht_von[$key].
			"| Versandkosten ".$versandkosten[$key];
			// Update der Versandkosten
			$query = "UPDATE shop_shippment_detail SET gewicht_von='".$gewicht_von[$key]."',gewicht_bis='".$gewicht_bis[$key]."',versandkosten='".str_replace(",",".",$versandkosten[$key])."' WHERE shop_shippment_detail_id='".$key."'";
			#echo $query;
			DBi::$conn->query($query) or die(mysqli_error());
		}
		exit;
	}
?>
<h1>Shop Versandkosten</h1>
<?php 
	$query = "SELECT * FROM shop_shippment WHERE domain_id='".$_SESSION['domain_id']."'";
	$resShippmentList = DBi::$conn->query($query) or die(mysqli_error());
	$html .= '<ul>';
	$bFound = false;
	while($shippment = mysqli_fetch_assoc($resShippmentList)) {
		$html .= '<li><span onClick="shop_shippment_load(\''.$shippment['shop_shippment_id'].'\')" id="shippment_id_'.$shippment['shop_shippment_id'].'"><strong>'.$shippment['name_de'].'</strong></span><span class="spanlink" onClick="javascript:shop_shippment_load(\''.$shippment['shop_shippment_id'].'\')" style="margin-left:10px"> &ouml;fffnen</span><span class="spanlink" onClick="javascript:shop_shippment_new_edit_versandart(\''.$shippment['shop_shippment_id'].'\')" style="margin-left:10px"> bearbeiten</span><span class="spanlink" onClick="javascript:shop_shippment_new_delete(\''.$shippment['shop_shippment_id'].'\')" style="margin-left:10px"> löschen</span></li>';
		$bFound = true;
	}
	$html .= '</ul>';
	
	if($bFound == false) {
		$html .= '<h2>Bitte legen Sie Versandkosten an!</h2>Wenn Sie keine anlegen werden die Versandkosten später berechnet.<br/><br/>';
	}
	if(!isset($_GET['modus'])) {
		$_GET['modus'] = $_POST['modus'];
	}
	switch($_GET['modus']) {
		case "list_detail":
			$query = "SELECT * FROM shop_shippment_detail WHERE shop_shippment_id='".$_GET['shippment_id']."'";
			$resShippmentList = DBi::$conn->query($query) or die(mysqli_error());		
			$html .= '<br/><br/>
			<form name="frmShippmentSave" id="shop_shippment_'.$_GET['shippment_id'].'" action="/ACP/acp_shop_shippment.php" method="POST" onSubmit="return shop_shippment_save_item(\'shop_shippment_'.$_GET['shippment_id'].'\');">';
			$html .= '<input type="hidden" id="acp_get_modus" name="modus" value="'.$_GET['modus'].'"/>';
			while($shippment = mysqli_fetch_assoc($resShippmentList)) {		
				$html .= '<div id="shippment_item_'.$shippment['shop_shippment_detail_id'].'">';
					$html .= '<input type="text" name="gewicht_von['.$shippment['shop_shippment_detail_id'].']" value="'.$shippment['gewicht_von'].'"/>KG ';
					$html .= '<input type="text" name="gewicht_bis['.$shippment['shop_shippment_detail_id'].']" value="'.$shippment['gewicht_bis'].'"/>KG ';
					$html .= '<input type="text" name="versandkosten['.$shippment['shop_shippment_detail_id'].']" value="'.$shippment['versandkosten'].'"/> EUR <a href="#" onClick="shop_shippment_delete(\''.$shippment['shop_shippment_detail_id'].'\')">L&ouml;schen</a>';			
				$html .= '</div>';
				$html .= '<br/><br/>';
			}
			$html .= '<div id="shop_shippment_new_line"></div>';
			$html .= '<input type="hidden" name="frmsenden" value="true"/><input type="submit" name="frmShippment_send" value="Versandkosten speichern"/><button onClick="shop_shippment_new_line(\''.$_GET['shippment_id'].'\')">Neue Zeile</button></form><div id="test"></div>';
			break;
		case "versandart_newline":
			$html .='<br/><form name="frmShippmentSave" id="shop_shippment_versandartnew" action="/ACP/acp_shop_shippment.php" method="POST" onSubmit="return shop_shippment_versandartnewline(\'shop_shippment_versandartnew\');">';
			$html .= 'Mehrwertsteuer der Versandkosten<br/>
			<input type="text" name="txtVersandartMWST" id="txtVersandartMWST" value="19"/>%<br/><br/>';
			$html .= 'Name der Versandart<br/><input type="text" name="txtVersandartNewName" id="txtVersandartNewName"/><br/><br/>
			';
			$html .= '<input type="hidden" id="acp_get_modus" name="modus" value="'.$_GET['modus'].'"/>';
			$html .= '<input type="submit" name="frmShippment_send" value="Neue Versandart anlegen"/>';
			$html .='</form>';
			break;
		case "versandart_edit":
			$query = "SELECT * FROM shop_shippment WHERE shop_shippment_id='".$_POST['versandart_id']."'";
			$resVersandart = DBi::$conn->query($query) or die(mysqli_error());
			$data = mysqli_fetch_assoc($resVersandart);
			$html .='<br/><form name="frmShippmentSave" id="shop_shippment_versandartnew" action="/ACP/acp_shop_shippment.php" method="POST" onSubmit="return shop_shippment_versandartnewline(\'shop_shippment_versandartnew\');">';
 
			$html .= '<input type="hidden" id="acp_get_modus" name="modus" value="'.$_GET['modus'].'"/>';
			$html .= 'Mehrwertsteuer der Versandkosten<br/>
			<input type="text" name="txtVersandartMWST" id="txtVersandartMWST" value="'.$data['mwst'].'"/>%<br/><br/>';
			$html .= 'Name der Versandart<br/><input type="text" name="txtVersandartNewName" id="txtVersandartNewName" value="'.$data['name_de'].'"/><br/><br/>
			';
			$html .= '<input type="hidden" id="versandart_edit_id" name="versandart_edit_id" value="'.$data['shop_shippment_id'].'"/>';
			$html .= '<input type="submit" name="frmShippment_send" value="Neue Versandart speichern"/>';
			$html .='</form>';
			break;			
	}
	echo $html.'<br/><span class="spanlink" onClick="javascript:shop_shippment_load_new();">Neue Versandart anlegen</span><br/><br/>';
 ?>