<?php 
#echo $_COOKIE['last_page'];
@session_start();
	require_once('../../../../include/inc_basic-functions.php');
if(isset($_POST['optModul'])) {
	require('../../../../include/inc_config-data.php');
	
	$_GET = mysql_real_escape_array($_GET);
	$_POST = mysql_real_escape_array($_POST);	
	
	// Login überprüfen
	$chkCookie = admin_cookie_check();

	#echo $chkCookie;
	if($_SESSION['login'] == 1) {		
		$_SESSION['login'] = 1;
	#	echo "Login";
	} else {
		echo "kein Login";
		exit(0);
	}		
	if($_POST['modus'] == 'new') {
		
		#echo $_COOKIE['last_page'];
		
		$query = "INSERT INTO `modul_".$_POST['optModul']."` (`title_de`, `menue_id`, `last_usr`,content_de,created_at) VALUES ('".$_POST['module_title']."', ".$_SESSION['page_id'].", 0, '".$_POST['module_texthtml_content']."','".date("Y-m-d H:i:s")."');";
		$resInsert = DBi::$conn->query($query) or die($query.mysqli_error());
		$iModulID = mysqli_insert_id(DBi::$conn);
		
		$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$_SESSION['page_id'].", ".$iModulID.", '".$_POST['optModul']."', '".$_POST['page_layout']."', '".$_POST['module_position']."');";
		
		#,Author,Webseite,eMail
		#'".$_POST['module_author']."','".$_POST['module_webseite']."','".$_POST['module_email']."'
 		# Seite anlegen + module
		$strDatumDE = explode(" ",$_POST['module_erstelltam']);
		$strDatumDE2 = explode(".",$strDatumDE2[0]);
		$eng_datum = $strDatumDE2[2].'-'.$strDatumDE2[1].'-'.$strDatumDE2[0].' '.$strDatumDE2[1];

		$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id,content_type) VALUES ('".$_POST['module_title']."', '".$_POST['module_title']."', '".$_POST['shop_page_sort']."', 'unsichtbar', 'col2-left-layout','".$_SESSION['domain_id']."','news_content');";
		$resInsert = DBi::$conn->query($query) or die(mysqli_error());
		$iPageID = mysqli_insert_id(DBi::$conn);
			
		$query = "INSERT INTO `modul_news_content` (`AddTitel`,Bereich,AddText,domain_id,page_id,Author,Webseite,AddDatum,eMail) VALUES ('".$_POST['module_title']."','".$_POST['shop_cat_id']."','".$_POST['module_texthtml_content']."','".$_SESSION['domain_id']."','".$iPageID."','".$_POST['module_author']."','".$_POST['module_webseite']."','".$eng_datum."','".$_POST['module_email']."');";
			$resInsert = DBi::$conn->query($query) or die($query.mysqli_error());
			$iPageID2 = mysqli_insert_id(DBi::$conn);
			$icat = $iPageID2;
			#echo $iPageID2;
			#print_r($query);
			#print_r($_POST);
			$_SESSION['system_shop_last_cat'] = $_POST['shop_cat_id'];
			
			if(empty($_POST['shop_cat_id'])) {
				$_POST['shop_cat_id'] = '0';
			}
 
			$query = "SELECT * FROM modul_news_category WHERE news_cat_id='".$_POST['shop_cat_id']."'";
			$resNewsCat = DBi::$conn->query($query);
			$strNewsCat = mysqli_fetch_assoc($resNewsCat);
			
			
			$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID.", ".$strNewsCat['page_id'].");";
			$resInsert = DBi::$conn->query($query) or die($query.mysqli_error());
			
	 
			
			// Modul Einstellugen Speichern
			$query = "INSERT INTO `modul_news_content_view` (`title_de`, `menue_id`, `last_usr`,news_cat) VALUES ('".$_POST['module_title']."', ".$iPageID.", 0,'".$icat."');";
			$resInsert = DBi::$conn->query($query) or die($query.mysqli_error());
			$iModulID = mysqli_insert_id(DBi::$conn);
		
			// Modul auf einer Seite bekannt machen
			$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'news_content_view', 'col-main', '".$_POST['module_position']."');";
			$resInsert = DBi::$conn->query($query) or die($query.mysqli_error());
			
			// Modul Einstellugen Speichern
			$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('".$_POST['module_title']."', ".$iPageID.", 0, 'submenue', 'Y');";
			$resInsert = DBi::$conn->query($query) or die($query.mysqli_error());
			$iModulID = mysqli_insert_id(DBi::$conn);
		
			// Modul auf einer Seite bekannt machen
			$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'menue', 'col-left', '".$_POST['module_position']."');";
	
			$resInsert = DBi::$conn->query($query) or die($query.mysqli_error());
			
		$resInsert = DBi::$conn->query($query) or die($query.mysqli_error());
		
		$path = getPathUrl($_SESSION['language'],$iPageID);			
		$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
		@mail("info@shopste.com","News Kategorie angelegt: '".$_POST['module_title']."'",'LINK: '.$strLink);
		echo $strLink;
		exit;
			
	}  elseif($_POST['modus'] == 'edit') {
		// Module Einstellungen UPDATE
		$query = "UPDATE `modul_".$_POST['optModul']."` SET eMail='".$_POST['module_email']."',Author='".$_POST['module_author']."',Webseite='".$_POST['module_webseite']."',title_de='".$_POST['module_title']."', content_de='".$_POST['module_texthtml_content']."' WHERE id='".$_POST['module_id']."'";
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
		echo '<h2>Neues News Inhalt Modul <strong>hinzuf&uuml;gen</strong></h2>'; 
		require('../../../../include/inc_config-data.php');
		$query = "SELECT max(position) as max_position FROM module_in_menue WHERE module_in_menue.menue_id =1";
		$resPosition = DBi::$conn->query($query) or die(mysqli_error());
		$dataPosition = mysqli_fetch_assoc($resPosition);
		$strButtonName = 'News Inhalt Modul hinzuf&uuml;gen';
		
	}
	if($_GET['modus'] == 'edit') {
		require('../../../../include/inc_config-data.php');
		echo '<h3>News Inhalt Modul <strong>editieren</strong></h3>';
		
		$query = "SELECT * FROM modul_".$_GET['module_name']." WHERE id='".$_GET['id']."'";
		$resModul= DBi::$conn->query($query) or die(mysqli_error());
		$dataMenue = mysqli_fetch_assoc($resModul);
		
		$query = "SELECT * FROM module_in_menue WHERE modul_id='".$_GET['id']."' AND typ='".$_GET['module_name']."'";
		$resModulMenue = DBi::$conn->query($query) or die(mysqli_error());
		$dataPageData = mysqli_fetch_assoc($resModulMenue);
		$strButtonName = 'News Inhalt Modul bearbeiten speichern';
	}
	$strOptMenueSelekt = new_category(0,0,'',0,0,'select',$_SESSION['system_shop_last_cat']);
?>
	  <p>
			<form name="frmModulAdd" id="module_ajax_save_form" action="/module/<?= $_GET['module_name'].'/admin/form/'.$_GET['module_name'].'-settings.php'; ?>" method="POST" onSubmit="return module_save_form('module_ajax_save_form');">
				<div class="label" style="float:left;">
					<input type="hidden" id="acp_get_modul_name" name="optModul" value="news_content_view"/>
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
				<div class="label" style="float:left;">&Uuml;bergeordnete News Kategorie</div>
				<div id="shop_path">
					<select name="shop_cat_id" size="1">
						<?php 
						 echo $strOptMenueSelekt;
						?>
					</select>
				</div>					
				<div style="clear:both"></div>						
				<div class="label" style="float:left;">News &Uuml;berschrift</div>
				<div id="module_title">
					<input type="text" name="module_title" value="<?php echo $dataMenue['title_de']; ?>">
				</div>				
				<div style="clear:both"></div>
				
				<div class="label" style="float:left;">Einsender / Author</div>
				<div id="module_author">
					<input type="text" name="module_author" value="<?php echo $dataMenue['Author']; ?>">
				</div>				
				<div style="clear:both"></div>				

				<div class="label" style="float:left;">Weiterlesen Webseite / Author Webseite</div>
				<div id="module_webseite">
					<input type="text" name="module_webseite" value="<?php echo $dataMenue['Webseite']; ?>">
				</div>				
				<div style="clear:both"></div>
				<div class="label" style="float:left;">Email-Adresse*</div>
				<div id="module_email">
					<input type="text" name="module_email" value="<?php echo $dataMenue['eMail']; ?>">
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
					} else {
						echo $dataPageData['position']; 
					}
					?>">
				</div>
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
