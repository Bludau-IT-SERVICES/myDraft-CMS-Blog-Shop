<?php 
session_start();
$path = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once($path.'/include/inc_config-data.php');
require_once($path.'/include/inc_basic-functions.php');

####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_rss_content_add($config) {

		#echo "SELECT * FROM modul_news_content_view JOIN modul_news_content ON modul_news_content_view.news_cat = modul_news_content.ID WHERE id=".$config['modul_id'];
		

		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='rss_content_add'"));
		#echo "IN";
 		 
		$dataTextHTML['typ'] = 'rss_content_add';
		
		$text = '<div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
		
		#$text .= convertUmlaute($dataTextHTML["content_".$_SESSION['language']]);
		#$titel = convertUmlaute($dataTextHTML["AddTitel".$_SESSION['language']]);
		
	#	print_r($dataTextHTML);
		
		if($text == '') {   
			$text = convertUmlaute($dataTextHTML["AddText"]); 
		} 
		
		if($titel == '') { 
			$titel = convertUmlaute($dataTextHTML["AddTitel"]); 
		} 
		
		$text .= $dataTextHTML["AddText"];
		
		// && $config["container"]
		if($_SESSION['login'] == '1'  AND $module_in_menue['container'] == 'col-main') {
			$strReturn = getMember($dataTextHTML['last_usr']);
			if(!empty($strReturn)) {
				$ary = explode(" ",$dataTextHTML['lastchange']);
				$german_de = getDateDE($ary[0]);
				$titel .= '</h1> - '.$strReturn.' - '.$german_de.' '.$ary[1];
			}
		}
		
		# Eingeloggt 
		if (@$_SESSION['login'] == '1')  { 
			if($titel == '') { 
				$titel = "Kein Titel"; 
			} 
		} 
		
		$strOptMenueSelekt = rss_category(0,0,'',0,0,'select',$_SESSION['system_shop_last_cat']);
		$strOptMenueSelekt2 = menue_generator(0,0,'',0,0,'select',$_SESSION['page_id']);
		
		$text .= '
<div id="acp_main_new_page_form">
	<h2>Neuen Team Security Inhalt hinzuf&uuml;gen</h2>
	<form name="frmModulAdd" id="rss_content_add_frm" action="/ACP/acp_rss_post.php" method="POST" onSubmit="return new_save_form(\'rss_content_add_frm\');">
			<div class="label" style="float:left;">&Uuml;bergeordnete News Kategorie</div>
			<div id="shop_path">
				<select name="shop_cat_id" size="1">'.$strOptMenueSelekt.'
				</select>
			</div>	
			
				<div class="label" style="float:left;">
					<input type="hidden" id="acp_get_modul_name" name="optModul" value="rss_content"/>
					<input type="hidden" id="acp_get_modus" name="modus" value="new"/>
					<input type="hidden" id="acp_get_modul_id" name="module_id" value="'.$_GET['id'].'"/>';
					
					if($_GET['modus'] == 'new') {
					
						$text .= '<input type="hidden" id="acp_get_page_id" name="page_id" value="'.$_SESSION['page_id'].'"/>';
					
					}
					
				$text .='</div>			
				<div style="clear:both"></div>				
				<div class="label" style="float:left;">News &Uuml;berschrift*</div>
				<div id="module_title">
					<input type="text" name="module_title" size="75" value="'.$dataMenue['title_de'].'">
				</div>				
				<div style="clear:both"></div>
				
				<div class="label" style="float:left;">Einsender / Author*</div>
				<div id="module_author">
					<input type="text" name="module_author" value="'.$dataMenue['Author'].'">
				</div>				
				<div style="clear:both"></div>				

				<div class="label" style="float:left;">Authoren Webseite</div>
				<div id="module_webseite">
					<input type="text" name="module_webseite" value="'.$dataMenue['Webseite'].'">
				</div>				
				<div style="clear:both"></div>	
				
				<div class="label" style="float:left;">Email-Adresse*</div>
				<div id="module_email">
					<input type="text" name="module_email" value="'.$dataMenue['eMail'].'">
				</div>				
				</div>		 
				
					<input type="hidden" name="module_position" value="col-main"
					<input type="hidden" name="module_position" value="';
					
					if($_GET['modus'] == 'new') {
						$text .= $dataPosition['max_position'] +1;
					} else {
						$text .= $dataPageData['position']; 
					}
					
				$text .='">
			 
				<div style="clear:both"></div>
				<div class="label" style="float:left;">Nachrichtentext eingeben*</div>
 
				<div id="shop_item_price">
					<textarea name="module_texthtml_content" id="module_texthtml_content">'.$dataMenue['content_de'].'</textarea>
				</div>	
				<script>
						var editor = CKEDITOR.replace(\'module_texthtml_content\');
				</script>
				<input type="hidden" id="acp_get_hasHTMLModule" name="acp_get_hasHTMLModule" value="1"/>
				<div id="module_submit"><br/>
					<input type="submit" class="button" name="module_submit" value="Blog Beitrag ver&ouml;ffentlichen">
				</div>		
			</form>
</div>';
		
		##############################
		# >> Inline suche
		##############################
		#$text = stringToFunction($text);
	
		if($dataTextHTML["AddText"] == '') {
			#$text .= 'Bitte doppelt anklicken zum editieren';
		}
		
		###############
		# >> Eingelogt 
		###############
		
		if (@$_SESSION["login"] == '1')  { 			
			#$text = '<div ondblclick="getTexthtmlEdit('.$dataTextHTML['id'].');" id="texthtml_'.$dataTextHTML['id'].'">'.$text.'</div>'; 
		} 
		
		$text .= '</div>'; // config modus 

		
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 } 
 ?>