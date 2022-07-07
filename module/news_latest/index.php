<?php 
session_start();
####################################
# >> Neuste Blog Beiträge Modul 
#-----------------------------------
# typ: core
# since: 14.04.2019 (version_info)
####################################
function LoadModul_news_latest($config) {

		$dataNewsLatest = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM modul_news_latest WHERE id=".$config['modul_id']));		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='".$config['typ']."'"));
 
		if($dataNewsLatest['news_category_id'] == "0") {
			$query = "SELECT *,modul_news_content.created_at as content_date,modul_news_content.page_id as content_page_id FROM modul_news_content JOIN modul_news_category ON modul_news_content.news_cat = modul_news_category.news_cat_id ORDER BY news_content_id DESC LIMIT 0,".$dataNewsLatest['news_category_show_items_count']."";			
		} else {
			$query = "SELECT *,modul_news_content.created_at as content_date,modul_news_content.page_id as content_page_id FROM modul_news_content JOIN modul_news_category ON modul_news_content.news_cat = modul_news_category.news_cat_id WHERE news_cat_id='".$dataNewsLatest['news_category_id']."' ORDER BY news_content_id DESC LIMIT 0,".$dataNewsLatest['news_category_show_items_count']."";	
		}
		
		$resNewsLatest = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		$iCount = 0;
		
		$text .= '<article class="block news_latest">';
		
		while($strNewsLatest = mysqli_fetch_assoc($resNewsLatest)) {
			
			if($iCount == 0) {
				$strTitelModule = $strNewsLatest['AddTitel'];
			}
			
			$text .= '<div class="content">';
			$text .= '<header>Von <i>'.$strNewsLatest["Author"].'</i> vom <i><time datetime="'.$strNewsLatest["AddDatum"].'">'.getDateDE($strNewsLatest["AddDatum"]).'</time></i></header><br/>';

			###################################################
			# >> Snippet generieren
			# - Youtube Support <iframe>
			##################################################
			$bIframe = false;
			$iPos = strpos($strNewsLatest['AddText'],"<iframe",0);
			if($iPos > 0) {
				$bIframe = true;
				$iPos = strpos($strNewsLatest['AddText'],"</iframe",0);
			} else {				
				$iPos = strpos($strNewsLatest['AddText'],">",350);
			}
		 
			if($iPos > 349 || $bIframe == true) {								
				$text .= substr($strNewsLatest['AddText'],0,$iPos + 9); // + <iframe> länge
			} else {
				$text .= substr($strNewsLatest['AddText'],0,350);
			} 
			
			# Link zum Blog-Beitrag / News-Beitrag
			$path = getPathUrl($_SESSION['language'],$strNewsLatest['content_page_id']);
			#$strLinkWeiterlesen = $_SESSION['domain_method'].$_SERVER['SERVER_NAME'].'/'.$path;
			$strLinkWeiterlesen = '/'.$path;
			
			
			$text .= '<br/><a title="Blog Beitrag '.$strNewsLatest['AddTitel'].' zu ende lesen" href="'.$strLinkWeiterlesen.'" class="link-red">⮩ Weiterlesen des Blog Beitrag</a>';
 
			$query ="SELECT count(*) as anzahl FROM modul_news_content WHERE news_cat='".$strNewsLatest['news_cat']."'";
			$resCounting = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strAnzahl = mysqli_fetch_assoc($resCounting);
			
			$strURL = getPathURL('de',$strNewsLatest['page_id']);
			$text .= '<footer class="blog_footer">Weitere Blogbeiträge der Blogkategorie <a title="Weitere Blogbeiträge aus der Blog Kategorie '.$strNewsLatest['name_de'].'" href="'.$strURL.'">'.$strNewsLatest['name_de'].' ('.$strAnzahl['anzahl'].')</a> ansehen.</footer>';
 
			$iCount++;
		}
		
		$text .= '</article>';
		
	  $result = array("title"=>$strTitelModule,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ'],"box_design"=>"plain");

	  return $result;
 } 
 ?>