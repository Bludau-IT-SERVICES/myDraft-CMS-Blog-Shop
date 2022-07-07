<?php 
	session_start();
	
	#SELECT * FROM shop_attribute_set JOIN shop_attribute ON shop_attribute_set.shop_attribute_id = shop_attribute.attribute_set_id JOIN shop_attribute_value ON shop_attribute.attribute_id = shop_attribute_value.shop_attribute_id
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
	
	#echo $_POST['frmsenden'];
	if($_POST['modus'] == 'delete_attribute_value') {
		$query = "DELETE FROM shop_attribute_value WHERE shop_attribute_value_id='".$_POST['shop_attribute_value_id']."'";	
		DBi::$conn->query($query) or die(mysqli_error());
		exit;
	} elseif($_POST['modus'] == 'shop_attribute_new_attribute') {
		$query = "INSERT INTO shop_attribute_value(shop_attribute_id,value_de,created_at) VALUES('".$_POST['attribute_id']."','','".date("Y-m-d H:i:s")."');";
		DBi::$conn->query($query) or die(mysqli_error());
		$iID = mysqli_insert_id(DBi::$conn);
		$html .= '<div id="attribute_value_'.$iID.'">';
			$html .= '<input type="text" name="attribut_'.$_POST['attribute_id'].'['.$iID.']" value=""/> <a href="#" onClick="shop_attribute_value_delete(\''.$iID.'\')">L&ouml;schen</a><br/><br/>';
		$html .= '</div>';
		echo $html;
		exit;
	} elseif($_POST['modus'] == 'attributeset_new_save') {
		$query = "INSERT INTO shop_attribute_set(set_name_de,created_at,domain_id) VALUES('".$_POST['txtAttributset']."','".date("Y-m-d H:i:s")."','".$_SESSION['domain_id']."');";
		DBi::$conn->query($query) or die(mysqli_error());
		$iID = mysqli_insert_id(DBi::$conn);
		exit;
	} elseif($_POST['modus'] == 'attribut_new') {
	
		$query = "SELECT * FROM shop_attribute_set WHERE shop_attribute_id='".$_POST['attributset_id']."'";
		$strAttributSet = mysqli_fetch_assoc(DBi::$conn->query($query));
		$html .= '<div id="shop_attribut_attribute">';
		$html .= '<h2>Neues Attribut im Attributset "'.$strAttributSet['set_name_de'].'" anlegen</h2>';
		$html .= '<form name="frmAttributNew" id="frmAttributNew" action="/ACP/acp_shop_attribute.php" method="POST" onSubmit="return shop_attributset_new_attribut(\'frmAttributNew\');">';
		$html .= '<input type="hidden" id="attributset_id" name="attributset_id" value="'.$_POST['attributset_id'].'"/>';
		$html .= '<input type="hidden" id="modus" name="modus" value="attribute_new_save"/>';
		$html .= '<input type="text" name="txtAttributName"/>';
		$html .= '<input type="submit" name="btnNewAttribute"/>';
		$html .= '</form>';
		$html .= '</div>';
		echo $html;
		exit;
	} elseif($_POST['modus'] == 'shop_attributeset_new') {
		$html = '<div id="shop_attribut_attribute">';
		$html .= '<h2>Neues Attributset anlegen</h2>';
		$html .= '<form name="frmAttributSetNew" id="frmAttributSetNew" action="/ACP/acp_shop_attribute.php" method="POST" onSubmit="return shop_attributset_new_save(\'frmAttributSetNew\');">';
		$html .= '<input type="hidden" id="modus" name="modus" value="attributeset_new_save"/>';
		$html .= '<input type="text" name="txtAttributset"/>';
		$html .= '<input type="submit" name="btnNewAttributSet"/>';
		$html .= '</form>';
		$html .= '</div>';
		echo $html;
		exit;
	} elseif($_POST['modus'] == 'shop_attributeset_delete') {
		#attributeset_id
		$query = "DELETE FROM shop_attribute_set WHERE shop_attribute_id='".$_POST['attributeset_id']."'";	
		DBi::$conn->query($query) or die(mysqli_error());
		
		#shop_attribute_id
		$query = "SELECT * FROM shop_attribute WHERE attribute_set_id='".$_POST['attributeset_id']."'";
		$resAttribute = DBi::$conn->query($query);
		while($strAttribute = mysqli_fetch_assoc($resAttribute)) {
			$query = "DELETE FROM shop_attribute_value WHERE shop_attribute_id='".$strAttribute['attribute_id']."'";	
			DBi::$conn->query($query) or die(mysqli_error());	
		}
		$query = "DELETE FROM shop_attribute WHERE attribute_set_id='".$_POST['attributeset_id']."'";	
		DBi::$conn->query($query)  or die(mysqli_error());	
		exit;
	} elseif($_POST['modus'] == 'attribute_new_save') {
	
		$query = "INSERT INTO shop_attribute(attribute_set_id,name_de,created_at) VALUES('".$_POST['attributset_id']."','".$_POST['txtAttributName']."','".date("Y-m-d H:i:s")."');";
		DBi::$conn->query($query) or die(mysqli_error());
		$iID = mysqli_insert_id(DBi::$conn);
		
		$query = "INSERT INTO shop_attribute_value(shop_attribute_id,value_de,created_at) VALUES('".$iID."','','".date("Y-m-d H:i:s")."');";
		DBi::$conn->query($query) or die(mysqli_error());
		$iID = mysqli_insert_id(DBi::$conn);
		exit;
	} elseif($_POST['modus'] == 'delete_attribute') {
		$query = "SELECT * FROM shop_attribute WHERE attribute_id='".$_POST['shop_attribute_id']."'";	
		$resAttribut = DBi::$conn->query($query) or die(mysqli_error());
		$strAttribut = mysqli_fetch_assoc($resAttribut);
		
		$query = "DELETE FROM `shop_attribute` WHERE  `attribute_id`=".$_POST['shop_attribute_id'].";";
		DBi::$conn->query($query) or die(mysqli_error());
		
		$query = "DELETE FROM shop_attribute_value WHERE shop_attribute_id='".$_POST['shop_attribute_id']."'";	
		DBi::$conn->query($query) or die(mysqli_error());
		
		echo $strAttribut['attribute_set_id'];
		exit;
	}	elseif($_POST['frmsenden'] == 'true') {
	
		$query = "SELECT * FROM shop_attribute WHERE attribute_set_id=".$_POST['attribute_set_id'];
		$resAttribute = DBi::$conn->query($query) or die(mysqli_error());
		while($strAttribute = mysqli_fetch_assoc($resAttribute)) {
		
			$attribut_werte = $_POST['attribut_'.$strAttribute['attribute_id']];
			foreach( $attribut_werte as $key => $n ) {
				$query = "UPDATE shop_attribute_value SET value_de='".$attribut_werte[$key]."' WHERE shop_attribute_value_id='".$key."'";
				DBi::$conn->query($query) or die(mysqli_error());
			}		
		
		}
		echo "Attributset gespeichert.";
		exit;
	}
	
	$query = "SELECT * FROM shop_attribute_set WHERE shop_attribute_set.domain_id='".$_SESSION['domain_id']."'";

	$resSetListe = DBi::$conn->query($query) or die(mysqli_error());
	$html = '<h2>Attributsets zur Auswahl</h2>';
	while($SetListe = mysqli_fetch_assoc($resSetListe)) {
		$html .= '<h3><a href="#" onClick="attribute_liste_load(\''.$SetListe['shop_attribute_id'].'\')">'.$SetListe['set_name_de'].'</a> <font size="1"><a href="#" onClick="shop_attributset_delete(\''.$SetListe['shop_attribute_id'].'\')">L&ouml;schen</a></font></h3><br/>';
	}
	$html .='<a href="#" onClick="shop_attributset_new()">Neues Attributset</a><br/>';
	
?>
<div id="frmAttribute">
<?php 
echo $html;

	switch($_POST['modus']) {
	
	case 'edit':
		$query = "SELECT * FROM shop_attribute_set JOIN shop_attribute ON shop_attribute_set.shop_attribute_id = shop_attribute.attribute_set_id JOIN shop_attribute_value ON shop_attribute.attribute_id = shop_attribute_value.shop_attribute_id WHERE shop_attribute_set.shop_attribute_id='".$_POST['attribut_set']."'  ORDER BY shop_attribute_value.shop_attribute_id ASC, shop_attribute_value.shop_attribute_value_id ASC";
		$resAttribute = DBi::$conn->query($query) or die(mysqli_error());
		$iCount = 0;
		
		$query = "SELECT * FROM shop_attribute_set WHERE shop_attribute_id='".$_POST['attribut_set']."'";
		$strAttributSet = mysqli_fetch_assoc(DBi::$conn->query($query));
		
		// Formular
		$html = '<h2>Attributset "'.$strAttributSet['set_name_de'].'" editieren</h2> <button onClick="shop_attribute_new_attribute(\''.$_POST['attribut_set'].'\')">Neues Attribute</button><br/><br/>
		<form name="frmAttributSetSave" id="shop_attributset'.$_POST['attribut_set'].'" action="/ACP/acp_shop_attribute.php" method="POST" onSubmit="return shop_attributset_save_values(\'shop_attributset'.$_POST['attribut_set'].'\');">';
		
		while($strAttribute = mysqli_fetch_assoc($resAttribute)) {
			if($strAttribute['name_de'] == $strAttributName) {	
				$html .= '<div id="attribute_value_'.$strAttribute['shop_attribute_value_id'].'">';			
					$html .= '<input type="text" name="attribut_'.$strAttribute['attribute_id'].'['.$strAttribute['shop_attribute_value_id'].']" value="'.$strAttribute['value_de'].'"/><a href="#" onClick="shop_attribute_value_delete(\''.$strAttribute['shop_attribute_value_id'].'\')">L&ouml;schen</a><br/>';		
				$html .= '</div>';
				$strAttributeID = $strAttribute['attribute_id'];
			} else {
				if($iCount != 0) {
					$html .= '<div id="shop_attribute_value_new_line_'.$strAttributeID.'"></div>';
					$html .= '<a href="#" onClick="shop_attribute_value_new_line(\''.$strAttributeID.'\')">Neuen Wert anlegen</a>';
				}
				$html .= '<h2>'.$strAttribute['name_de'].'<font size="1"><a href="#" onClick="shop_attribute_delete(\''.$strAttribute['attribute_id'].'\')">L&ouml;schen</a></font></h2>';
				$html .= '<div id="attribute_value_'.$strAttribute['shop_attribute_value_id'].'">';
					$html .= '<input type="text" name="attribut_'.$strAttribute['attribute_id'].'['.$strAttribute['shop_attribute_value_id'].']" value="'.$strAttribute['value_de'].'"/><a href="#" onClick="shop_attribute_value_delete(\''.$strAttribute['shop_attribute_value_id'].'\')">L&ouml;schen</a><br/>';
				$html .= '</div>';
				$strAttributName = $strAttribute['name_de'];
			}
			$iCount++;
			$strAttributeID = $strAttribute['attribute_id'];
		}
		$html .= '<div id="shop_attribute_value_new_line_'.$strAttributeID.'"></div>';
		$html .= '<a href="#" onClick="shop_attribute_value_new_line(\''.$strAttributeID.'\')">Neuen Wert anlegen</a><br/><br/>';
		$html .= '<input type="hidden" name="frmsenden" value="true"/><input type="hidden" name="attribute_set_id" value="'.$_POST['attribut_set'].'"/><input type="submit" name="frmShippment_send" value="Attributset speichern"/></form>';
		echo $html;
?>
	
<?php
		break;
	case 'new':
?>
<div class="shop_attribute">
	<form name="frmAttributSetName" id="frmAttributSetName" action="/ACP/acp_shop_attribute_set_save.php" method="POST" onSubmit="return attribute_set_save_form('frmAttributSetName');">
		<input type="text" name="txtAttributSetName"/>
		<input type="submit" name="btnAttributSetName"/>
	</form>	
</div>
<?php 
	break;
}
?>
<div id="shop_attribut_attribute" class="shop_attribut_attribute"></div>
</div>