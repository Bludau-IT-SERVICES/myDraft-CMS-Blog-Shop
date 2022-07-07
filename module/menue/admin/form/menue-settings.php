<?php 
	session_start();
	require_once('../../../../include/inc_config-data.php');
	require_once('../../../../include/inc_basic-functions.php'); 
	
	// Login überprüfen
	$chkCookie = admin_cookie_check();

	#echo $chkCookie;
	if($_SESSION['login'] == 1) {	
		$_SESSION['login'] = 1;
	} else {
		exit(0);
	}
if(isset($_POST['optModul'])) {
	// Datenbankverbindung
	if($_POST['modus'] == 'new') {
		
		
		// Modul Einstellugen Speichern
		$query = "INSERT INTO `modul_".$_POST['optModul']."` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('".$_POST['module_title']."', ".$_POST['module_menue_id'].", 0, '".$_POST['module_typ']."', '".$_POST['module_alphabetisch']."');";
		$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		$iModulID = mysqli_insert_id(DBi::$conn);
		
		// Modul auf einer Seite bekannt machen
		$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$_SESSION['page_id'].", ".$iModulID.", '".$_POST['optModul']."', '".$_POST['page_layout']."', '".$_POST['module_position']."');";
		#echo $query;
		
		$resInsert = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
	} elseif($_POST['modus'] == 'edit') {		
		// Module Einstellungen UPDATE
		$query = "UPDATE `modul_".$_POST['optModul']."` SET title_de='".$_POST['module_title']."',typ='".$_POST['module_typ']."',bAlphabetisch='".$_POST['module_alphabetisch']."',menue_id='".$_POST['module_menue_id']."' WHERE id='".$_POST['module_id']."'";
		DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		
		// Page Einstellungen UPDATE
		$query = "UPDATE module_in_menue SET container='".$_POST['page_layout']."',position='".$_POST['module_position']."' WHERE modul_id='".$_POST['module_id']."' AND typ='".$_POST['optModul']."'";
		DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
	}
} else {
?>
<div id="module_save">
<?php 
	if($_GET['modus'] == 'new') {
		echo '<h1>Neues Men&uuml; Modul <strong>hinzuf&uuml;gen</strong></h1>';
		$query = "SELECT max(position) as max_position FROM module_in_menue WHERE module_in_menue.menue_id =1";
		$resPosition = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		$dataPosition = mysqli_fetch_assoc($resPosition);	
		$strButtonName = 'Navigation Modul hinzuf&uuml;gen';
	}
	if($_GET['modus'] == 'edit') {
		echo '<h1>Navigation Modul <strong>editieren</strong></h1>';
		
		$query = "SELECT * FROM modul_".$_GET['module_name']." WHERE id='".$_GET['id']."'";
		$resModul= DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		$dataMenue = mysqli_fetch_assoc($resModul);
		
		$query = "SELECT * FROM module_in_menue WHERE modul_id='".$_GET['id']."' AND typ='".$_GET['module_name']."'";
		$resModulMenue = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		$dataPageData = mysqli_fetch_assoc($resModulMenue);
		$strButtonName = 'Navigation Modul bearbeiten speichern';
	}
?>
	  <p>		
			<form name="frmModulAdd" id="module_ajax_save_form" action="/module/<?= $_GET['module_name'].'/admin/form/'.$_GET['module_name'].'-settings.php'; ?>" method="POST" onSubmit="return module_save_form('module_ajax_save_form');">
				<div class="label" style="float:left;">
					<input type="hidden" id="acp_get_modul_name" name="optModul" value="menue"/>
					<input type="hidden" id="acp_get_modus" name="modus" value="<?php echo $_GET['modus']; ?>"/>
					<input type="hidden" id="acp_get_modul_id" name="module_id" value="<?php echo $_GET['id']; ?>"/>
					<input type="hidden" id="acp_get_page_id" name="acp_get_page_id" value="<?php echo $_SESSION['page_id']; ?>"/>
					<?php 
						$strOptMenueMarket = menue_generator(0,0,'',0,0,'select');
					?>
					Menüauswahl<br/>
					<select id="menue_id"  name="menue_id" size="1" onchange="getval(this);">
						<option value="KEINE-AUSWAHL">Keine Auswahl</option>
						<option value="0">Hauptkategorie (Men&uuml;ebene 0)</option>
						<?php 
							echo $strOptMenueMarket;
						?>
					</select>
 
				</div>				
				<div style="clear:both"></div>				
				<div class="label" style="float:left;">&Uuml;berschrift:</div>
				<div id="module_title">
					<input type="text" name="module_title" value="<?php echo $dataMenue['title_de']; ?>">
				</div>
				<div style="clear:both"></div>				
			<?php 
				#if($_GET['modus'] == 'edit') {											
			?>
				<div class="label" style="float:left;">Men&uuml; Seiten ID</div>
				<div id="module_title">
					<input type="text" id="module_menue_id" name="module_menue_id" value="<?php if($dataMenue['menue_id'] == '') { echo $_COOKIE['last_page']; } else { echo $dataMenue['menue_id']; } ?>">
				</div>
				<div style="clear:both"></div>				
			<?php 
				#}
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
				<div class="label" style="float:left;">Modul Typ:</div>
				<div id="module_subtyp">
					<select name="module_typ" size="1">        
						<option value="menue" <?php if($dataMenue['typ'] == 'menue') echo "selected=true"?>>Hauptmen&uuml;</option>
						<option value="submenue" <?php if($dataMenue['typ'] == 'submenue') echo "selected=true"?>>Untermen&uuml;</option>
					</select>			
				</div>
				<div style="clear:both"></div>				
				<div class="label" style="float:left;">Alphabetisch:</div>
				<div id="module_menue_alphabetisch">
					<select name="module_alphabetisch" size="1">        
						<option value="Y" <?php if($dataMenue['bAlphabetisch'] == 'Y') echo "selected=true"?>>Ja</option>
						<option value="N" <?php if($dataMenue['bAlphabetisch'] == 'N') echo "selected=true"?>>Nein</option>
					</select>			
				</div>
				<div style="clear:both"></div>				
				<div class="label" style="float:left;">Eintragen:</div>
				<div id="module_submit">
					<input type="submit" class="module_form_submit button" id="module_form_submit" name="module_submit" value="<?php echo $strButtonName; ?>">
				</div>		
			</form>
	  </p>
</div>
<script>
 function getval(sel) {
       $('#module_menue_id').val(sel.value);
    }
</script>	  
<?php 
	}
?>	  
<div id="message_module"></div>