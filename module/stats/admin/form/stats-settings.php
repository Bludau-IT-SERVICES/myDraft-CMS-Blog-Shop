<?php 
#echo $_COOKIE['last_page'];
@session_start();

if(isset($_POST['optModul'])) {
	require('../../../../include/inc_config-data.php');
	require_once('../../../../include/inc_basic-functions.php');
	
	$_GET = mysql_real_escape_array($_GET);
	$_POST = mysql_real_escape_array($_POST);	
	
	// Login überprüfen
	$chkCookie = admin_cookie_check();

	#echo $chkCookie;
	if($_SESSION['login'] == 1) {		
		$_SESSION['login'] = 1;
		echo "Login";
	} else {
		echo "kein Login";
		exit(0);
	}		
	if($_POST['modus'] == 'new') {
		
		#echo $_COOKIE['last_page'];
		
		$query = "INSERT INTO `modul_".$_POST['optModul']."` (`title_de`, `menue_id`, `last_usr`,created_at,typ,bShowRating) VALUES ('".$_POST['module_title']."', ".$_SESSION['page_id'].", 0,'".date("Y-m-d H:i:s")."','".$_POST['module_typ']."','".$_POST['modul_bshow_rating']."');";
		$resInsert = DBi::$conn->query($query) or die(mysqli_error());
		$iModulID = mysql_insert_id();
		
		$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$_SESSION['page_id'].", ".$iModulID.", '".$_POST['optModul']."', '".$_POST['page_layout']."', '".$_POST['module_position']."');";
		
		$resInsert = DBi::$conn->query($query) or die(mysqli_error());
	}  elseif($_POST['modus'] == 'edit') {
		// Module Einstellungen UPDATE
		$query = "UPDATE `modul_".$_POST['optModul']."` SET title_de='".$_POST['module_title']."',typ='".$_POST['module_typ']."', bShowRating='".$_POST['modul_bshow_rating']."' WHERE id='".$_POST['module_id']."'";
		DBi::$conn->query($query) or die(mysqli_error());
		
		#echo "IN";
		// Page Einstellungen UPDATE
		$query = "UPDATE module_in_menue SET container='".$_POST['page_layout']."',position='".$_POST['module_position']."' WHERE modul_id='".$_POST['module_id']."' AND typ='".$_POST['optModul']."'";
		DBi::$conn->query($query) or die(mysqli_error());
	}
} else {
?>
<div id="module_save">
<?php 
	if($_GET['modus'] == 'new') { 
		echo '<h2>Stats Modul <strong>hinzuf&uuml;gen</strong></h2>'; 
		require('../../../../include/inc_config-data.php');
		$query = "SELECT max(position) as max_position FROM module_in_menue WHERE module_in_menue.menue_id =1";
		$resPosition = DBi::$conn->query($query) or die(mysqli_error());
		$dataPosition = mysqli_fetch_assoc($resPosition);
		$strButtonName = 'Modul hinzuf&uuml;gen';
		
	}
	if($_GET['modus'] == 'edit') {
		require('../../../../include/inc_config-data.php');
		echo '<h3>Stats  Modul <strong>editieren</strong></h3>';
		
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
					<input type="hidden" id="acp_get_modul_name" name="optModul" value="stats"/>
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
				<div class="label" style="float:left;">&Uuml;berschrift</div>
				<div id="module_title">
					<input type="text" name="module_title" value="<?php echo $dataMenue['title_de']; ?>">
				</div>
				<div style="clear:both"></div>
				<div class="label" style="float:left;">Datenquelle</div>
				<div id="module_position">  
						<select name="module_typ" size="10">        
							<option value="DAY" <?php if($dataMenue['typ'] == 'DAY') echo "selected=true"?>>Heute</option>
							<option value="DAY_24" <?php if($dataMenue['typ'] == 'DAY_24') echo "selected=true"?>>24 Std.</option>
							<option value="DAY_48" <?php if($dataMenue['typ'] == 'DAY_48') echo "selected=true"?>>48 Std.</option>
							<option value="WEEK" <?php if($dataMenue['typ'] == 'WEEK') echo "selected=true"?>>Diese Woche</option>
							<option value="MONTH_1" <?php if($dataMenue['typ'] == 'MONTH_1') echo "selected=true"?>>Letzten 30 Tage</option>
							<option value="MONTH" <?php if($dataMenue['typ'] == 'MONTH') echo "selected=true"?>>Diesen Monat</option>
							<option value="YEAR" <?php if($dataMenue['typ'] == 'YEAR') echo "selected=true"?>>Dieses Jahr</option>
							<option value="DAY_LAST" <?php if($dataMenue['typ'] == 'DAY_LAST') echo "selected=true"?>>Gestern</option>
							<option value="WEEK_LAST" <?php if($dataMenue['typ'] == 'WEEK_LAST') echo "selected=true"?>>Letzte Woche</option>
							<option value="MONTH_LAST" <?php if($dataMenue['typ'] == 'MONTH_LAST') echo "selected=true"?>>Letzten Monat</option>
							<option value="YEAR_LAST" <?php if($dataMenue['typ'] == 'YEAR_LAST') echo "selected=true"?>>Letztes Jahr</option>
						</select>			
				</div>
				<div style="clear:both"></div>				
				<div class="label" style="float:left;">Anzeige von Bewertung</div>
				<div id="module_position">
						<select name="modul_bshow_rating" size="3">        
							<option value="col-main" <?php if($dataMenue['bShowRating'] == 'Y') echo "selected=true"?>>Ja, anzeigen</option>
							<option value="col-left" <?php if($dataMenue['bShowRating'] == 'N') echo "selected=true"?>>Nein, ausblenden</option>
						</select>			
				</div>					
				<div style="clear:both"></div>				
				<div class="label" style="float:left;">Layout Position</div>
				<div id="module_position">
						<select name="page_layout" size="3">        
							<option value="col-main" <?php if($dataPageData['container'] == 'col-main') echo "selected=true"?>>Box Mitte</option>
							<option value="col-left" <?php if($dataPageData['container'] == 'col-left') echo "selected=true"?>>Box Links</option>
							<option value="col-right" <?php if($dataPageData['container'] == 'col-right') echo "selected=true"?>>Box Rechts</option>
						</select>			
				</div>		
				<div style="clear:both"></div>
				<div class="label" style="float:left;">Position</div>
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
				<div id="module_submit"><br/>
					<input type="submit" class="button" name="module_submit" value="<?php echo $strButtonName; ?>">
				</div>		
			</form>
	  </p>
</div>	  
<?php 
	}
?>
