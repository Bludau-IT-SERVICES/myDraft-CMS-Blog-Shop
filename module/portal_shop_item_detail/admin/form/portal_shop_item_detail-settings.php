<?php 
session_start();

function getProduktListe() {
	$query = "SELECT * FROM shop_item";
	$resData = DBi::$conn->query($query);
	while($data = mysqli_fetch_assoc($resData)) {	
		$html .= '<option value="'.$data['shop_item_id'].'">'.$data['name_de'].'</option>';
	}
	return $html;
}

if(isset($_POST['optModul'])) {
	// Datenbankverbindung
	require('../../../../include/inc_config-data.php');
	require_once('../../../../include/inc_basic-functions.php');
	
	$_GET = mysql_real_escape_array($_GET);
	$_POST = mysql_real_escape_array($_POST);
	
	// Login überprüfen
	$chkCookie = admin_cookie_check();

	#echo $chkCookie;
	if($_SESSION['login'] == 1) {		
		$_SESSION['login'] = 1;
	} else {
		exit(0);
	}
	
	if($_POST['modus'] == 'new') {
		// Modul Einstellugen Speichern
		$query = "INSERT INTO `modul_".$_POST['optModul']."` (`title_de`, `menue_id`, `last_usr`,shop_item_id) VALUES ('".$_POST['module_title']."', ".$_SESSION['page_id'].", 0,'".$_POST['shop_item_id']."');";
		$resInsert = DBi::$conn->query($query) or die(mysqli_error());
		$iModulID = mysqli_insert_id(DBi::$conn);
		
		// Modul auf einer Seite bekannt machen
		$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$_SESSION['page_id'].", ".$iModulID.", '".$_POST['optModul']."', '".$_POST['page_layout']."', '".$_POST['module_position']."');";
	
		$resInsert = DBi::$conn->query($query) or die(mysqli_error());
	} elseif($_POST['modus'] == 'edit') {		
		// Module Einstellungen UPDATE
		
		if(isset($_POST['txtShopItemID'])) {
			$_POST['shop_item_id'] = $_POST['txtShopItemID'];
		}
		
		$query = "UPDATE `modul_".$_POST['optModul']."` SET title_de='".$_POST['module_title']."',menue_id='".$_POST['module_menue_id']."',shop_item_id='".$_POST['shop_item_id']."' WHERE id='".$_POST['module_id']."'";
		DBi::$conn->query($query) or die(mysqli_error());
		
		// Page Einstellungen UPDATE
		$query = "UPDATE module_in_menue SET container='".$_POST['page_layout']."',position='".$_POST['module_position']."' WHERE modul_id='".$_POST['module_id']."' AND typ='".$_POST['optModul']."'";
		DBi::$conn->query($query) or die(mysqli_error());
	}
} else {
?>
<div id="module_save">
<?php 
	if($_GET['modus'] == 'new') {
		echo '<h2>Neues Portal Shop Detail Modul <strong>hinzuf&uuml;gen</strong></h2>';
		require('../../../../include/inc_config-data.php');
		$query = "SELECT max(position) as max_position FROM module_in_menue WHERE module_in_menue.menue_id =1";
		$resPosition = DBi::$conn->query($query) or die(mysqli_error());
		$dataPosition = mysqli_fetch_assoc($resPosition);	
		$strButtonName = 'Modul hinzuf&uuml;gen';
	}
	if($_GET['modus'] == 'edit') {
		require('../../../../include/inc_config-data.php');
		echo '<h3>Portal Shop Detail Modul <strong>editieren</strong></h3>';
		
		$query = "SELECT * FROM modul_".$_GET['module_name']." WHERE id='".$_GET['id']."'";
		$resModul= DBi::$conn->query($query) or die(mysqli_error());
		$dataMenue = mysqli_fetch_assoc($resModul);
		
		$query = "SELECT * FROM module_in_menue WHERE modul_id='".$_GET['id']."' AND typ='".$_GET['module_name']."'";
		$resModulMenue = DBi::$conn->query($query) or die(mysqli_error());
		$dataPageData = mysqli_fetch_assoc($resModulMenue);
		$strButtonName = 'Modul bearbeiten speichern';
	}
?>
	  <p>		
			<form name="frmModulAdd" id="module_ajax_save_form" action="/module/<?= $_GET['module_name'].'/admin/form/'.$_GET['module_name'].'-settings.php'; ?>" method="POST" onSubmit="return module_save_form('module_ajax_save_form');">
				<div class="label" style="float:left;">
					<input type="hidden" id="acp_get_modul_name" name="optModul" value="portal_shop_item_detail"/>
					<input type="hidden" id="acp_get_modus" name="modus" value="<?php echo $_GET['modus']; ?>"/>
					<input type="hidden" id="acp_get_modul_id" name="module_id" value="<?php echo $_GET['id']; ?>"/>
					<?php 
					if($_GET['modus'] == 'new') {
					?>
						<input type="hidden" id="acp_get_page_id" name="page_id" value="<?php echo $_SESSION['page_id']; ?>"/>
					<?php 
					}
					?>
				</div>				
				<div style="clear:both"></div>				
				<div class="label" style="float:left;">&Uuml;berschrift:</div>
				<div id="module_title">
					<input type="text" name="module_title" value="<?php echo $dataMenue['title_de']; ?>">
				</div>
				<div style="clear:both"></div>		
				<div class="label" style="float:left;">Produkt</div>
				<div id="module_shop_id">
					<select name="shop_item_id" size="1">
						
						<?php echo getProduktListe(); ?>
					</select>
				</div>
				<div class="label" style="float:left;">Shopste Artikel-ID</div>
				<div id="module_shop_id">
					<input type="text" name="txtShopItemID" value=""/>
				</div>				
				<div style="clear:both"></div>					
			<?php 
				if($_GET['modus'] == 'edit') {											
			?>
				<div class="label" style="float:left;">Men&uuml; Seiten ID</div>
				<div id="module_title">
					<input type="text" name="module_menue_id" value="<?php echo $dataMenue['menue_id']; ?>">
				</div>
				<div style="clear:both"></div>				
			<?php 
				}
			?>
				<div class="label" style="float:left;">Layout Position:</div>
				<div id="module_position">
						<select name="page_layout" size="1">        
							<option value="col-main" <?php if($dataPageData['container'] == 'col-main') echo "selected=true"?>>Box Mitte</option>
							<option value="col-left" <?php if($dataPageData['container'] == 'col-left') echo "selected=true"?>>Box Links</option>
							<option value="col-right" <?php if($dataPageData['container'] == 'col-right') echo "selected=true"?>>Box Rechts</option>
						</select>			
				</div>		
				<div style="clear:both"></div>				
				<div class="label" style="float:left;">Position:</div>
				<div id="module_position">
					<input type="text" name="module_position" value="<?php 
					if($_GET['modus'] == 'new') {
						echo $dataPosition['max_position'] +1;
					} else {
						echo $dataPageData['position']; 
					}
					?>">
				</div>
				<div style="clear:both"></div>				
				<div class="label" style="float:left;">Eintragen:</div>
				<div id="module_submit">
					<input type="submit" class="module_form_submit button" id="module_form_submit" name="module_submit" value="<?php echo $strButtonName; ?>">
				</div>		
			</form>
	  </p>
</div>	  
<?php 
	}
?>	  
<div id="message_module"></div>