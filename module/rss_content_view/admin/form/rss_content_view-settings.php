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
		
		$query = "INSERT INTO `modul_".$_POST['optModul']."` (`title_de`, `menue_id`, `last_usr`,content_de,created_at,gui_header_show_external_link,gui_header_show_sources_link,gui_header_show_date,gui_header_show_category,gui_header_show_category_link,gui_footer_rateing,gui_footer_social_media) VALUES ('".$_POST['module_title']."', ".$_SESSION['page_id'].", 0, '".$_POST['module_texthtml_content']."','".date("Y-m-d H:i:s")."','".$_POST['gui_header_show_external_link']."','".$_POST['gui_header_show_sources_link']."','".$_POST['gui_header_show_date']."','".$_POST['gui_header_show_category']."','".$_POST['gui_header_show_category_link']."','".$_POST['gui_footer_rateing']."','".$_POST['gui_footer_social_media']."');";
		$resInsert = DBi::$conn->query($query) or die(mysqli_error());
		$iModulID = mysql_insert_id();
		
		$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$_SESSION['page_id'].", ".$iModulID.", '".$_POST['optModul']."', '".$_POST['page_layout']."', '".$_POST['module_position']."');";
		
		$resInsert = DBi::$conn->query($query) or die(mysqli_error());
	}  elseif($_POST['modus'] == 'edit') {
		// Module Einstellungen UPDATE
		$query = "UPDATE `modul_".$_POST['optModul']."` SET title_de='".$_POST['module_title']."',gui_header_show_external_link='".$_POST['gui_header_show_external_link']."',gui_header_show_sources_link='".$_POST['gui_header_show_sources_link']."',gui_header_show_date='".$_POST['gui_header_show_date']."',gui_header_show_category='".$_POST['gui_header_show_category']."',gui_header_show_category_link='".$_POST['gui_header_show_category_link']."',gui_footer_social_media='".$_POST['gui_footer_social_media']."',gui_footer_rateing='".$_POST['gui_footer_rateing']."', content_de='".$_POST['module_texthtml_content']."' WHERE id='".$_POST['module_id']."'";
		DBi::$conn->query($query) or die(mysqli_error());
		
		echo "IN";
		// Page Einstellungen UPDATE
		$query = "UPDATE module_in_menue SET container='".$_POST['page_layout']."',position='".$_POST['module_position']."' WHERE modul_id='".$_POST['module_id']."' AND typ='".$_POST['optModul']."'";
		DBi::$conn->query($query) or die(mysqli_error());
	}
} else {
?>
<div id="module_save">
<?php 
	if($_GET['modus'] == 'new') { 
		echo '<h2>RSS-Feed Modul <strong>hinzuf&uuml;gen</strong></h2>'; 
		require('../../../../include/inc_config-data.php');
		$query = "SELECT max(position) as max_position FROM module_in_menue WHERE module_in_menue.menue_id =1";
		$resPosition = DBi::$conn->query($query) or die(mysqli_error());
		$dataPosition = mysqli_fetch_assoc($resPosition);
		$strButtonName = 'Modul hinzuf&uuml;gen';
		
	}
	if($_GET['modus'] == 'edit') {
		require('../../../../include/inc_config-data.php');
		echo '<h3>RSS-Feed Modul <strong>editieren</strong></h3>';
		
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
					<input type="hidden" id="acp_get_modul_name" name="optModul" value="rss_content_view"/>
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
				<div class="label" style="float:left;">Layout Position</div>
				<div id="module_position">
						<select name="page_layout" size="1">        
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
						$dataMenue['gui_header_show_category_link'] = 'Y';
						$dataMenue['gui_header_show_category'] = 'Y';
						$dataMenue['gui_header_show_external_link'] = 'Y';
						$dataMenue['gui_header_show_date'] = 'Y';
						$dataMenue['gui_header_show_category_link'] = 'Y';
						$dataMenue['gui_header_show_sources_link'] = 'Y';
						$dataMenue['gui_footer_rateing'] = 'Y';						
						$dataMenue['gui_footer_social_media'] = 'Y';						
					} else {
						echo $dataPageData['position']; 
					}
					?>">
				</div>
				<div style="clear:both"></div>
			<div class="label" style="float:left;">Kategorie anzeigen</div><br/>
					<input type="radio" id="gui_header_show_category_link1" name="gui_header_show_category_link" value="Y" <?php if($dataMenue['gui_header_show_category_link'] == 'Y') { echo "checked"; } ?>> 
					<label for="gui_header_show_category_link1">Ja</label><br> 
					<input type="radio" id="gui_header_show_category_link0" name="gui_header_show_category_link" value="N"<?php if($dataMenue['gui_header_show_category_link'] == 'N') { echo "checked"; } ?>> <label for="gui_header_show_category_link0"> Nein</label>				
				
				<div class="label" style="float:left;">Kategorie Link aktiv</div><br/>
					<input type="radio" id="gui_header_show_category1" name="gui_header_show_category" value="Y" <?php if($dataMenue['gui_header_show_category'] == 'Y') { echo "checked"; } ?>> 
					<label for="gui_header_show_category1">Ja</label><br> 
					<input type="radio" id="gui_header_show_category0" name="gui_header_show_category" value="N"<?php if($dataMenue['gui_header_show_category'] == 'N') { echo "checked"; } ?>> <label for="gui_header_show_category0"> Nein</label>
				
				<div class="label" style="float:left;">Datum anzeigen</div><br/>
					<input type="radio" id="gui_header_show_date1" name="gui_header_show_date" value="Y" <?php if($dataMenue['gui_header_show_date'] == 'Y') { echo "checked"; } ?>> 
					<label for="gui_header_show_date1">Ja</label><br> 
					<input type="radio" id="gui_header_show_date0" name="gui_header_show_date" value="N"<?php if($dataMenue['gui_header_show_date'] == 'N') { echo "checked"; } ?>> <label for="gui_header_show_date0"> Nein</label>
					
				<div class="label" style="float:left;">Externe Links anzeigen</div><br/>
					<input type="radio" id="gui_show_header_external_source1" name="gui_header_show_external_link" value="Y" <?php if($dataMenue['gui_header_show_external_link'] == 'Y') { echo "checked"; } ?>> 
					<label for="gui_show_header_external_source1">Ja</label><br> 
					<input type="radio" id="gui_show_header_external_source0" name="gui_header_show_external_link" value="N" <?php if($dataMenue['gui_header_show_external_link'] == 'N') { echo "checked"; } ?>> <label for="gui_show_header_external_source0"> Nein</label>
				<div class="label" style="float:left;">Quelle anzeigen</div><br/>
					<input type="radio" id="gui_header_show_sources_link1" name="gui_header_show_sources_link" value="Y" <?php if($dataMenue['gui_header_show_sources_link'] == 'Y') { echo "checked"; } ?>> 
					<label for="gui_header_show_sources_link1">Ja</label><br> 
					<input type="radio" id="gui_header_show_sources_link0" name="gui_header_show_sources_link" value="N"<?php if($dataMenue['gui_header_show_sources_link'] == 'N') { echo "checked"; } ?>> <label for="gui_header_show_sources_link0"> Nein</label>
					
				<div class="label" style="float:left;">News Bewerten können</div><br/>
					<input type="radio" id="gui_footer_rateing1" name="gui_footer_rateing" value="Y" <?php if($dataMenue['gui_footer_rateing'] == 'Y') { echo "checked"; } ?>> 
					<label for="gui_footer_rateing1">Ja</label><br> 
					<input type="radio" id="gui_footer_rateing0" name="gui_footer_rateing" value="N"<?php if($dataMenue['gui_footer_rateing'] == 'N') { echo "checked"; } ?>> <label for="gui_footer_rateing0"> Nein</label>	
				<div class="label" style="float:left;">Social Media anzeigen</div><br/>
					<input type="radio" id="gui_footer_social_media1" name="gui_footer_social_media" value="Y" <?php if($dataMenue['gui_footer_social_media'] == 'Y') { echo "checked"; } ?>> 
					<label for="gui_footer_social_media1">Ja</label><br> 
					<input type="radio" id="gui_footer_social_media0" name="gui_footer_social_media" value="N"<?php if($dataMenue['gui_footer_social_media'] == 'N') { echo "checked"; } ?>> <label for="gui_footer_social_media0"> Nein</label>						
				<div style="clear:both"></div>				
				<div class="label" style="float:left;">Beschreibungstext für die Webseite / Shop</div>
 
				<div id="shop_item_price">
					<textarea name="module_texthtml_content" id="module_texthtml_content">	<?php echo $dataMenue['content_de']; ?></textarea>
				</div>	
				<script>
					var oFCKeditor = new FCKeditor('module_texthtml_content') ;
					oFCKeditor.BasePath	= '/framework/fckeditor/';
					oFCKeditor.ReplaceTextarea() ;
				</script>
				<input type="hidden" id="acp_get_hasHTMLModule" name="acp_get_hasHTMLModule" value="1"/>
				<div id="module_submit"><br/>
					<input type="submit" class="button" name="module_submit" value="<?php echo $strButtonName; ?>">
				</div>		
			</form>
	  </p>
</div>	  
<?php 
	}
?>
