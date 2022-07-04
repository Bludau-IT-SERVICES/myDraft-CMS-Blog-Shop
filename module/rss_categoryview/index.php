<?php 
@session_start();
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/inc_pagging.php')) {
	include_once($_SERVER['DOCUMENT_ROOT'].'/include/inc_pagging.php');
} else {
	include_once('../../../include/inc_pagging.php');
}

####################################
# >> RSS Kategorie anzeigen 
####################################
function setUpdateRSSList($config,$tblRSS_kategorie_data,$titel) {
		
	if(isset($_SESSION['suchtext'])) {
		$strWords = explode(" ",$_SESSION['suchtext']);
		for($i=0; $i < count($strWords); $i++) {
			$LikeSuche .= " AddTitel LIKE '%".$strWords[$i]."%' AND";		
		}
	} else {
		if($_SESSION['suchtext'] == '' && isset($_SESSION['suchtext']) == false) {
			$LikeSuche ='';
	}  else {
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
		if(isset($_SESSION['suchtext'])) {
			$iSizePerPage = 100;
			$_SESSION['CORE_default_module_list_item_count'] = 100;
		} else {	
			if($config['searchText'] != '') {
				$iSizePerPage = 100;
				$_SESSION['CORE_default_module_list_item_count'] = 100;
			}
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

		$ids = getSubKategorie_rss($tblRSS_kategorie_data['news_cat'],"",0); 		
		
		switch($dataShopListe['orderby']) {
			case 'order_datum_asc':
				$query = "SELECT * FROM modul_rss_content WHERE ".$LikeSuche." (news_cat='".$tblRSS_kategorie_data['news_cat']."' ".$ids.")   ORDER BY modul_rss_content.created_at DESC".$strLimitBy;
				break;
			case 'order_datum_desc':
				$query = "SELECT * FROM modul_rss_content WHERE ".$LikeSuche." (news_cat='".$tblRSS_kategorie_data['news_cat']."' ".$ids.") ORDER BY modul_rss_content.created_at ASC".$strLimitBy;
				break;
			case 'order_preis_asc':
				$query = "SELECT * FROM modul_rss_content WHERE ".$LikeSuche." (news_cat='".$tblRSS_kategorie_data['news_cat']."' ".$ids.") ".$strLimitBy;
				break;
			case 'order_preis_desc':
				$query = "SELECT * FROM modul_rss_content WHERE ".$LikeSuche." (news_cat='".$tblRSS_kategorie_data['news_cat']."' ".$ids.") ".$strLimitBy;
				break;
			default:
				$query = "SELECT *,modul_rss_category.page_id as cat_page_id,modul_rss_content.page_id as rss_page_id FROM modul_rss_content LEFT JOIN modul_rss_category ON modul_rss_content.news_cat = modul_rss_category.news_cat_id WHERE ".$LikeSuche." (news_cat='".$tblRSS_kategorie_data['news_cat']."' ".$ids.") ORDER BY modul_rss_content.created_at DESC".$strLimitBy;
				break;
		}					

		if($_SESSION['suchtext'] != '') {		
			$query2 = "SELECT count(*) as anzahl FROM modul_rss_content WHERE ".$LikeSuche." news_cat=".$tblRSS_kategorie_data['news_cat']." ".$ids." ORDER BY updated_at DESC";
		
			$resItemsCount = DBi::$conn->query($query2) or die(mysqli_error(DBi::$conn));
			$strItemsCount = mysqli_fetch_assoc($resItemsCount); 
		
			#############################################################
			# Suchenanfragen speichern 
			#############################################################
			$query3 ="SELECT count(*) as anzahl FROM suche_anfragen WHERE suchanfrage='".$_SESSION['suchtext'] ."' AND shop_cat_id='".$tblRSS_kategorie_data['news_cat']."'";
			$resSuchanfrageCount = DBi::$conn->query($query3) or die(mysqli_error(DBi::$conn));
			$strSuchanfrageCount = mysqli_fetch_assoc($resSuchanfrageCount);
			if($strSuchanfrageCount['anzahl'] == 0) {
				# INSERT DELAYED 
				$query4 ="INSERT INTO suche_anfragen(suchanfrage,treffer,shop_cat_id,modul_typ) VALUES('".$_SESSION['suchtext'] ."','".$strItemsCount['anzahl']."','".$tblRSS_kategorie_data['news_cat']."','rss_categoryview')";	
				DBi::$conn->query($query4) or die(mysqli_error(DBi::$conn));
				
			} else {
				$query4 ="UPDATE suche_anfragen SET suchanzahl=suchanzahl+1,treffer='".$strItemsCount['anzahl']."',modul_typ='rss_categoryview' WHERE suchanfrage='".$_SESSION['suchtext'] ."'";	
				DBi::$conn->query($query4) or die(mysqli_error(DBi::$conn));
			}
		}
		
		$query2 = "SELECT count(*) as anzahl FROM modul_rss_content WHERE ".$LikeSuche." (news_cat='".$tblRSS_kategorie_data['news_cat']."' ".$ids.") ORDER BY AddDatum DESC";
		$tmp =  $query;

		$resItemsCount = DBi::$conn->query($query2) or die('ERR:0001: '.mysqli_error(DBi::$conn));
		$strItemsCount = mysqli_fetch_assoc($resItemsCount); 
		
		$text_footer ='<input type="hidden" name="modul_id" id="modul_id" value="'.$config['modul_id'].'"/>
		<input type="hidden" name="news_cat" id="news_cat" value="'.$tblRSS_kategorie_data['news_cat'].'"/>';

		# News Quellen
		$ids_quellen = str_replace('news_cat','rss_cat',$ids); 
		$query_quellen = "SELECT count(*) as anzahl FROM modul_rss_quelle WHERE (rss_cat='".$tblRSS_kategorie_data['news_cat']."' ".$ids_quellen.") ORDER BY created_at DESC";
 
		$resItemsCount_quellen = DBi::$conn->query($query_quellen) or die('ERR:0002: '.mysqli_error(DBi::$conn));
		$strItemsCount_quellen = mysqli_fetch_assoc($resItemsCount_quellen); 
		$text .= 'Feed-Quellen: <span style="font-color:#46f70cd9">'.$strItemsCount_quellen['anzahl']."</span><br/>";
		$text_footer .= '<h3>RSS Feeds vom IT News Nachrichtenportal Team Security <i class="fa fa-rss" aria-hidden="true"></i> abonieren</h3><strong>'.$strItemsCount_quellen['anzahl'].'x</strong> RSS Feed <i class="fa fa-rss" aria-hidden="true"></i> Quellen<br/>';
		$strCountQuellen = explode("OR",$ids);
		$text_footer .= 'RSS Feed <i class="fa fa-rss" aria-hidden="true"></i> Unterkategorien von <strong>'.$titel.'</strong>: <strong>'.(count($strCountQuellen)-1).'x</strong><br/>';
		
		$url = str_replace("&","",$tblRSS_kategorie_data['name_de']);
		$url = str_replace(" ","-",$url);
		
		$text_footer .= 'RSS Feed <i class="fa fa-rss" aria-hidden="true"></i> Kategorie von IT News Nachrichtenportal Team Security <a title="RSS Feed '.$tblRSS_kategorie_data['name_de'].'" href="'.CORE_SERVER_DOMAIN.CORE_RSS_CONTENT_HTTP_PATH.'/'.rawurlencode($url).'|'.$tblRSS_kategorie_data['news_cat'].'">
		<i class="fa fa-rss" aria-hidden="true"></i> '.$tblRSS_kategorie_data['name_de'].' abonieren</a> <a href="https://validator.w3.org/feed/check.cgi?url='.CORE_SERVER_DOMAIN.CORE_RSS_CONTENT_HTTP_PATH.'/'.rawurlencode($url).'|'.$tblRSS_kategorie_data['news_cat'].'"> <img src="/image/valid-rss-rogers.png" alt="[Valid RSS]" width="40" title="Validate my RSS feed" /></a><br/>';
		
		#if (strlen(CORE_SERVER_RSS_ADDING_LINK) > 0) {
		#	$text_footer .= CORE_SERVER_RSS_ADDING_TEXT.'<br/>'.CORE_SERVER_RSS_SOCIAL_MEDIA;
		#}

		#if(strlen(CORE_SERVER_RSS_DOWNLOAD_TEXT) > 0) {
			#$text_footer .= CORE_SERVER_RSS_DOWNLOAD_TEXT.'<br/><br/>';
		#}

		$text .= '<span id="rss_categoryview_result_'.$config['modul_id'].'">';
		
		###########################
		# Blättern einbinden
		###########################
		$text .= '<a name="inhalt"></a>';
		$strPagging .= getPageBrowse($strItemsCount['anzahl'],'rss_categorieview'); 
		$text .= $strPagging;	
		$text .= '<br/></span><br/>';

		$query = $tmp ;
		$resNewsLatest = DBi::$conn->query($query) or die('ERR:0018: '.mysqli_error(DBi::$conn));
		$iCount = 0;
		$text .='<div class="row">';
		while($tblRSS_data = mysqli_fetch_assoc($resNewsLatest)) {

			$strPath_Kategorie = getPathUrl($_SESSION['language'],$tblRSS_data['cat_page_id']);
			$Artikel_path = getPathUrl($_SESSION['language'],$tblRSS_data['rss_page_id']);
			$aryPage = getPageSettings($tblRSS_data['rss_page_id']);
			$pathinfo = parse_url($tblRSS_data["Webseite"]);

			$text .= '<article class="block rss_category col-xs-12 col-md-6 col-lg-6 col-xl-4"><div class="box_rss_category">';
			$text .= '<header>';
			#if($tblRSS_kategorie_data['gui_header_show_date'] == 'Y') {			
			#}
			$tblRSS_data['name_de'] = str_replace("&quot;",'"',$tblRSS_data['name_de']);

			$text .= '<h1><a class="rss_head_link" title="'.$tblRSS_data['AddTitel'].'" href="'.$Artikel_path.'">➛ '.$tblRSS_data['AddTitel'].'</a></h1><p>';

			$text .= '<h2><time datetime="'.$tblRSS_data['AddDatum'].'">❅ '.getDateDE($tblRSS_data['AddDatum']).'</time>  <i style="font-size:10px">('.$aryPage['visitors'].')</i></h2>';
			if($tblRSS_kategorie_data['gui_header_show_category'] == 'Y') {
				if($tblRSS_kategorie_data['gui_header_show_category_link'] == 'Y') {
					
					$text .= '<a class="rss_head_link_hell" title="Kategorie '.$tblRSS_data['name_de'].' öffnen" target="_blank" href="'.CORE_SHOP_URL.'/'.rawurldecode($strPath_Kategorie).'"><strong>&#10056; '.$tblRSS_data['name_de'].'</strong></a>';				
				} else {
					$text .= '<strong>&#10056; '.$tblRSS_data['name_de'].'</strong>';									
				}
			}
		
		$text .= ' <a class="rss_head_link_hell" title="Nachrichtenwebseite direkt öffnen" target="_blank" href="'.CORE_SHOP_URL.'/weiterleitung.php?rss_id='.$tblRSS_data["news_content_id"].'&page_id='.$tblRSS_data['rss_page_id'].'"> <strong>&#10070; '.str_replace("www.","",$pathinfo['host']).'</strong></a>
		<strong> <a title="Startseite der RSS-Quelle öffnen" target="_blank" href="'.$pathinfo['scheme'].'://'.$pathinfo['host'].'"> <i class="fas fa-link"></i></a></strong>';	
 
			$text .= '</p></header>';
 
			$text .= '<div class="content rss_category" id="modul_rss_categoryview_'.$tblRSS_data['news_content_id'].'">';

			if($tblRSS_kategorie_data['gui_footer_rateing'] == 'Y') {
				$query = "SELECT sum(score) as ges, count(*) as anzahl  FROM seiten_bewertung where seiten_id='".DBi::mysql_escape($tblRSS_data['rss_page_id'],DBi::$conn)."'";
				$res2 = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));	
				$strBewertung = mysqli_fetch_assoc($res2);
				
				if($strBewertung['anzahl'] > 0) {
					$score = $strBewertung['ges'] / $strBewertung['anzahl'];					
				} else {
					$score = 0.0;
				}
			} 

			$tblRSS_data['AddText'] = strip_tags($tblRSS_data['AddText']);

			if(strlen($tblRSS_data['AddText']) >= 150) {			
					$iPos = strpos($tblRSS_data['AddText'],' ',150);				
			} else {
				$iPos = 0;
			}

			$text .= substr($tblRSS_data['AddText'],0,$iPos)."...";

			$text .= '<strong><a title="Weitere Informationen aus der RSS-Quelle mit dem Artikel '.$tblRSS_data['AddTitel'].'" href="'.CORE_SHOP_URL.'/'.rawurldecode($Artikel_path).'" target="_blank" class="link-red"> weiterlesen</a></strong>';					
			
			$text .= '</div>';

			$text .= '</article>';
			$iCount++;

		}
		$text .='</div>';
		$text .='</div>';
		$text .= $strPagging;
 
		$text .= $text_footer;
		#$text .= '</div>';

	return '<section>'.$text.'</section>';
}		
function LoadModul_rss_categoryview($config) {

		$dataRSS_DB_ITEM = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM modul_rss_categoryview LEFT JOIN modul_rss_category ON modul_rss_categoryview.news_cat = modul_rss_category.news_cat_id WHERE id=".$config['modul_id']));
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='rss_categoryview'"));

		$dataRSS_DB_ITEM['typ'] = 'rss_categoryview';
		
		$text = '<div class="content plain" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';

		#################################################
		# >> Suchvorschläge pro Modul-ID
		#################################################
		$query = "SELECT * FROM suche_anfragen WHERE shop_cat_id='".$dataRSS_DB_ITEM['news_cat']."' AND freigeschaltet='Y' ORDER BY updated_at DESC LIMIT 0,15";
		#echo $query;
		$resSearch = DBi::$conn->query($query) or die('ERR:77018: '.mysqli_error(DBi::$conn));		
		$strpfad = getPathUrl('de',$_GET['page_id']);
		$text .= 'Themensuche: ';
		while($strSuche = mysqli_fetch_assoc($resSearch)) {
			
			$txtSuche .= '<a style="font-family:Chango;font-size:14px;" title="Suche nach Themen" href="'.$strpfad.'/Suche/'.$strSuche['suchanfrage'].'/Seite/1/">'.$strSuche['suchanfrage'].' ('.$strSuche['suchanzahl'].')</a>, ';
		}
		#$text .= substr($txtSuche,0,strpos($txtSuche) -2);
		
		$text .= '<br/>
			<input type="text" onclick="javascript:news_cat_search_intro()" onBlur="news_cat_search_intro_reset()" onkeypress="onEnterPortal_rss(event,'.$config['modul_id'].','.$dataRSS_DB_ITEM['news_cat'].')"  id="txtQuickSearch" name="txtQuickSearch" placeholder="THEMA..." value="'.$strValueSuchen.'"/>';

		$text .= $dataRSS_DB_ITEM["content_".$_SESSION['language']];

		$text .= '<span class="button" style="margin-left:10px" onClick="rss_cat_search(\''.$config['modul_id'].'\',\''.$dataRSS_DB_ITEM['news_cat'].'\')" >Suchen</span><br/>';
		

		$text .= setUpdateRSSList($config,$dataRSS_DB_ITEM,$dataRSS_DB_ITEM["title_".$_SESSION['language']]);
				
	  $result = array("title"=>'🍀'.$dataRSS_DB_ITEM["title_".$_SESSION['language']],"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ'],"box_design"=>"plain");

	  return $result;
 } 

if ($_GET['bAjaxLoad'] == "true") {
 	include_once('../../include/inc_config-data.php');
	include_once('../../include/inc_basic-functions.php');
	include_once('../../include/inc_pagging.php');
	$_POST = mysql_real_escape_array($_POST);
	$_GET  = mysql_real_escape_array($_GET);	
	$_SESSION  = mysql_real_escape_array($_SESSION);	
	$path = realpath($_SERVER['DOCUMENT_ROOT']);  
	require_once ($path."/framework/piwik/MatomoTracker.php"); 
	if(isset($_GET['suchtext'])) {
		$t = new MatomoTracker( $idSite = 1,'https://freie-welt.eu/framework/piwik/');
		$t->setTokenAuth(CORE_PIWIK_API_KEY);
		$t->doTrackPageView('Ajax Suche: '.$_GET['suchtext']." - ".$_GET['modul_id']);
		$t->setIp($SERVER['REMOTEADDR']);
		
		if(isset($SERVER['HTTPREFERER'])) {
			$t->setUrl($SERVER['HTTPREFERER']);						
		}			
	} else {
		$t = new MatomoTracker( $idSite = 1,'https://tsecurity.de/framework/piwik/');
		$t->setTokenAuth(CORE_PIWIK_API_KEY);
		$t->doTrackPageView('XXX: Ajax Suche: '.$_GET['suchtext']." - ".$_GET['modul_id']);
		$t->setIp($SERVER['REMOTEADDR']);
		
		if(isset($SERVER['HTTPREFERER'])) {
			$t->setUrl($SERVER['HTTPREFERER']);						
		}						
	}

	$config['modul_id'] = $_GET['modul_id'];
	$config['searchText'] = $_GET['suchtext'];
	$_SESSION['suchtext'] = $_GET['suchtext'];
	//$config['module_item'] = $dataRSS_DB_ITEM['title_de'];
	$dataShopListe['news_cat'] = $_GET['shop_cat_id'];
	$dataShopListe['orderby'] = $_GET['orderby'];
	$dataShopListe['orderby_modus'] = $_GET['orderby_modus'];
	
	echo setUpdateRSSList($config,$dataShopListe,'');
 }
 ?>