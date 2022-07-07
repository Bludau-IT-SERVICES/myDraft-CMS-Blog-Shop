<?php 
####################################
# >> News Beitrag anzeigen Modul 
####################################
function LoadModul_news_content_view($config) {

	$module = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM modul_news_content_view WHERE id=".$config['modul_id']));
	$dataNewsContent = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM modul_news_content WHERE news_content_id=".$module['news_cat']));		 
	$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='".$config['typ']."'"));

	$text = '<article class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';

	$text .= '<header>Von <i>'.$dataNewsContent["Author"].'</i> vom <i><time datetime="'.$dataNewsContent["AddDatum"].'">'.getDateDE($dataNewsContent["AddDatum"]).'</time></i></header><br/>';
	$text .= $dataNewsContent["AddText"];
	
	# Footer News Beitrag
	$resItemsCount = DBi::$conn->query("SELECT count(*) as anzahl FROM modul_news_content WHERE Bereich='".$dataNewsContent['news_cat']."'");
	$strItemsCount = mysqli_fetch_assoc($resItemsCount); 
	
	$dataNewsCategory = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM modul_news_category WHERE news_cat_id=".$dataNewsContent['news_cat']));		 
	$strNewsCategory_url = getPathURL('de',$dataNewsCategory['page_id']);
	
	$text .= '<footer>Weitere Blogbeiträge von <a title="Weitere Blogbeiträge aus der Blog Kategorie '.$dataNewsCategory['name_de'].'" href="'.$strNewsCategory_url.'">'.$dataNewsCategory['name_de'].' ('.$strItemsCount['anzahl'].')</a> ansehen.</footer>';
	 
	$text .= '</article>'; // config modus 


	$result = array("title"=>$dataNewsContent['AddTitel'],"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	return $result;
 } 
 ?>