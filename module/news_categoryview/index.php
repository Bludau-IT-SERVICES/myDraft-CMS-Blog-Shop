<?php 
session_start();

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/inc_pagging.php')) {
	include_once($_SERVER['DOCUMENT_ROOT'].'/include/inc_pagging.php');
} else {
	include_once('../../../include/inc_pagging.php');
}
############################################################
# Hauptausgabe funktion
############################################################
function setNewsCategoryOutput($config,$dataNewsCategory) {
	
		############################################################
		# Suchtext als MySQL Query
		############################################################
		if($config['searchText'] == '' && isset($config['searchText']) == false) {
			$LikeSuche ='';
		}  else {
			if($config['searchText'] != 'Bitte Suchbegriff eingeben') {
				$strWords = explode(" ",$config['searchText']);
				for($i=0; $i < count($strWords); $i++) {
					$LikeSuche .= " AddTitel LIKE '%".$strWords[$i]."%' AND";		
				}
			}
		}
		
		############################################################
		# Anzahl der Kategorie bestimmen
		############################################################
		if(isset($_SESSION['CORE_default_module_list_item_count'])) {
			if(is_numeric($_SESSION['CORE_default_module_list_item_count'])) {		 
				$iSizePerPage =	 $_SESSION['CORE_default_module_list_item_count'];
			} else {
				$iSizePerPage = 50;	 		 
			}  
		} else {
			$iSizePerPage = 50;	 
		}
 
		##########################################################
		# >> MYSQL Limit festlegen
		##########################################################
		if(isset($_GET['seite'])) {
			if($_GET['seite'] == 1) {
				$strLimitBy = ' LIMIT 0,'.$iSizePerPage;
			} else {
				#$strLimitBy = ' LIMIT '.($_GET['seite'] * 100).','.(($_GET['seite'] * 100) + 100);
				if($_GET['seite'] > 1) {
					$seite = ($_GET['seite'] -1);
				} else {
					$seite = ($_GET['seite']);
				}
				
				$strLimitBy = ' LIMIT '.($seite * $iSizePerPage).','.$iSizePerPage;
			}
		} else {
			$strLimitBy = ' LIMIT 0,'.$iSizePerPage;	
		}	
		
		switch($dataShopListe['orderby']) {
			case 'order_datum_asc':
				$query = "SELECT * FROM modul_news_content WHERE ".$LikeSuche." (Bereich='".$dataNewsCategory['news_cat']."' ".$ids.")   ORDER BY created_at DESC".$strLimitBy;
				break;
			case 'order_datum_desc':
				$query = "SELECT * FROM modul_news_content WHERE ".$LikeSuche." (Bereich='".$dataNewsCategory['news_cat']."' ".$ids.") ORDER BY created_at ASC".$strLimitBy;
				break;
			case 'order_preis_asc':
				$query = "SELECT * FROM modul_news_content WHERE ".$LikeSuche." (Bereich='".$dataNewsCategory['news_cat']."' ".$ids.") ORDER BY preis ASC".$strLimitBy;
				break;
			case 'order_preis_desc':
				$query = "SELECT * FROM modul_news_content WHERE ".$LikeSuche." (Bereich='".$dataNewsCategory['news_cat']."' ".$ids.")  ORDER BY preis DESC".$strLimitBy;
				break;
			default:
				$query = "SELECT * FROM modul_news_content WHERE ".$LikeSuche." (Bereich='".$dataNewsCategory['news_cat']."' ".$ids.") ORDER BY created_at DESC".$strLimitBy;
				break;
		}				
		
		#############################################################
		# Suchanfrage vorhanden
		#############################################################
		if($config['searchText'] != '') {	
			$query2 = "SELECT count(*) as anzahl FROM modul_news_content WHERE ".$LikeSuche." news_cat=".$dataNewsCategory['news_cat']." ".$ids." ORDER BY created_at DESC";

			$resItemsCount = DBi::$conn->query($query2) or die(mysqli_error(DBi::$conn));
			$strItemsCount = mysqli_fetch_assoc($resItemsCount); 
				
			# Suchenanfragen speichern 			
			$query3 ="SELECT count(*) as anzahl FROM suche_anfragen WHERE suchanfrage='".$config['searchText']."' AND shop_cat_id='".$dataNewsCategory['news_cat']."'";
			$resSuchanfrageCount = DBi::$conn->query($query3) or die(mysqli_error(DBi::$conn));
			$strSuchanfrageCount = mysqli_fetch_assoc($resSuchanfrageCount);
			
			if($strSuchanfrageCount['anzahl'] == 0) {			
				$query4 ="INSERT INTO suche_anfragen(suchanfrage,treffer,shop_cat_id,modul_typ) VALUES('".$config['searchText']."','".$strItemsCount['anzahl']."','".$dataNewsCategory['news_cat']."','news_categoryview')";	
				DBi::$conn->query($query4) or die(mysqli_error(DBi::$conn));
				
			} else {
				$query4 ="UPDATE suche_anfragen SET suchanzahl=suchanzahl+1,treffer='".$strItemsCount['anzahl']."',modul_typ='news_categoryview' WHERE suchanfrage='".$config['searchText']."'";	
				DBi::$conn->query($query4) or die(mysqli_error(DBi::$conn));
			}
			
		}			

		$query2 = "SELECT count(*) as anzahl FROM modul_news_content WHERE ".$LikeSuche." (Bereich='".$dataNewsCategory['news_cat']."' ".$ids.")";

		$resItemsCount = DBi::$conn->query($query2) or die(mysqli_error(DBi::$conn));
		$strItemsCount = mysqli_fetch_assoc($resItemsCount); 

		$text .= '<br/>  
			<input type="text" placeholder="Bitte Suchbegriff eintragen..." onclick="javascript:news_cat_search_intro()" onBlur="news_cat_search_intro_reset()" onkeypress="onEnterPortal_news(event,'.$config['modul_id'].','.$dataNewsCategory['news_cat'].')" id="txtQuickSearch" name="txtQuickSearch" value="'.$strValueSuchen.'"/>';
			
		$text .='<input type="hidden" name="modul_id" id="modul_id" value="'.$config['modul_id'].'"/>
		<input type="hidden" name="news_cat" id="news_cat" value="'.$dataNewsCategory['news_cat'].'"/>
		
		<span class="button" style="margin-left:10px" onClick="news_cat_searching(\''.$config['modul_id'].'\',\''.$dataNewsCategory['news_cat'].'\')" >Suchen</span>';
		
		$strPagging .= getPageBrowse($strItemsCount['anzahl'],'news_categoryview');
		$text .= $strPagging;		
		
		$resNewsCategory = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		$iCount = 0;
		
		while($aryNewsCategory = mysqli_fetch_assoc($resNewsCategory)) {
			
			# Link zum Blog-Beitrag / News-Beitrag
			$path = getPathUrl($_SESSION['language'],$aryNewsCategory['page_id']);
			#$strLinkWeiterlesen = $_SESSION['domain_method'].$_SERVER['SERVER_NAME'].'/'.$path;
			$strLinkWeiterlesen = '/'.$path;
			
			$text .= '
			
			<article class="block news_category">
			
			<div class="block-title">';
				$text .= '<h1><a title="Zum kompletten Blogbeitrag '.$aryNewsCategory['AddTitel'].'" href="'.$strLinkWeiterlesen.'">&#8801; '.$aryNewsCategory['AddTitel'].'</a></h1>';
			$text .= '</div>';
			
			$text .= '<div class="content news_category">';
			
			###################################################
			# >> Snippet generieren
			# - Youtube Support <iframe>
			##################################################
			$bIframe = false;
			$iPos = strpos($aryNewsCategory['AddText'],"<iframe",0);
			if($iPos > 0) {
				$bIframe = true;
				$iPos = strpos($aryNewsCategory['AddText'],"</iframe",0);
			} else {				
				$iPos = strpos($aryNewsCategory['AddText'],">",350);
			}
		 
			if($iPos > 349 || $bIframe == true) {								
				$text .= substr($aryNewsCategory['AddText'],0,$iPos + 9); // + <iframe> länge
			} else {
				$text .= substr($aryNewsCategory['AddText'],0,350);
			} 
			
			$text .= '<br/><a title="Blog Beitrag '.$aryNewsCategory['AddTitel'].' zu ende lesen" href="'.$strLinkWeiterlesen.'" class="link-red">⮩ Weiterlesen des Blog Beitrag</a>';
 
			$text .= '</div>';

			$text .= '</article>';
		}
	 
		# Blättern unten einfügen
		$text .= $strPagging;	
		 
		
	return $text;
}

####################################
# >> Blog Kategorie Modul INIT
####################################
function LoadModul_news_categoryview($config) {

	$dataNewsCategory = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM modul_news_categoryview WHERE id=".$config['modul_id']));	 
	$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='".$config['typ']."'"));
	
	# Modul Inhalt ausgeben
	$text = '<div class="content-plain" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
		$text .= setNewsCategoryOutput($config,$dataNewsCategory);
	$text .= '</div>';

	$result = array("title"=>$dataNewsCategory['title_de'],"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ'],"box_design"=>"plain");

	return $result;
} 

####################################
# >> Ajax Laden unterstützen
####################################
if ($_GET['bAjaxLoad'] == "true") {
	
	include_once('../../include/inc_config-data.php');
	include_once('../../include/inc_basic-functions.php');
	include_once('../../include/inc_pagging.php');

	$_POST = mysql_real_escape_array($_POST);
	$_GET  = mysql_real_escape_array($_GET);	

	// MATOMO TRACKING
	if(CORE_PIWIK_ACTIVE == 'YES') {
		
		$path = realpath($_SERVER['DOCUMENT_ROOT']);  
		
		require_once ($path."/framework/piwik/PiwikTracker.php");
		
		if(isset($_GET['suchtext'])) {
			PiwikTracker::$URL = CORE_PIWIK_TRACK_URL;
			$t = new PiwikTracker( $idSite = 1 );
			$t->setTokenAuth(CORE_PIWIK_API_KEY);
			$t->doTrackPageView('NEWS-CATEGORY: Suche nach '.$config['searchText']." - ".$_GET['shop_cat_id']);
			$t->setIp($SERVER['REMOTEADDR']);
			
			if(isset($SERVER['HTTPREFERER'])) {
				$t->setUrl($SERVER['HTTPREFERER']);						
			}			
		} else {
			PiwikTracker::$URL = CORE_PIWIK_TRACK_URL;
			$t = new PiwikTracker( $idSite = 1 );
			$t->setTokenAuth(CORE_PIWIK_API_KEY);
			$t->doTrackPageView('NEWS-CATEGORY: Katgorie blättern'.$_GET['shop_cat_id']);
			$t->setIp($SERVER['REMOTEADDR']);
			
			if(isset($SERVER['HTTPREFERER'])) {
				$t->setUrl($SERVER['HTTPREFERER']);						
			}						
		}
		
	}	

	$config['modul_id'] = $_GET['modul_id'];
	$config['searchText'] = $_GET['suchtext'];
	$dataShopListe['news_cat'] = $_GET['shop_cat_id'];
	$dataShopListe['orderby'] = $_GET['orderby'];
	$dataShopListe['orderby_modus'] = $_GET['orderby_modus'];
	
	echo setNewsCategoryOutput($config,$dataShopListe);
}

 ?>