<?php 
	@session_start();
#$path = dirname(__FILE__);
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/inc_pagging.php')) {
	include_once($_SERVER['DOCUMENT_ROOT'].'/include/inc_pagging.php');
} else {
	#$path = realpath($_SERVER["DOCUMENT_ROOT"]);
	#echo $path;
	#exit;
	include_once('../../../include/inc_pagging.php');
}

 

function setUpdateCarList($config,$dataShopListe) {

		$text .= '<div id="shop_cat_list_result_'.$config['modul_id'].'">';
		
		if($config['searchText'] == '' || $config['searchText'] == 'Bitte Suchbegriff eingeben') {
			$LikeSuche ='';
		}  else {
			if($config['searchText'] != 'Bitte Suchbegriff eingeben') {
				$strWords = explode(" ",$config['searchText']);
				for($i=0; $i < count($strWords); $i++) {
					$LikeSuche .= " name_de LIKE '%".$strWords[$i]."%' AND";		
				}
			}
		}
		#echo $dataShopListe['shop_cat_id'];
		$ids2 = getSubKategorie_ids($dataShopListe['shop_cat_id'],"",0);
		$ids2 = substr($ids2,0,(strlen($ids2) -1));
		if(strlen($ids2) > 0) {
			$ids2 = ','.$ids2;
		}		
		#echo $ids;
			#<option value="order_datum_asc">Neueste Angebote zuerst</option>
            #<option value="order_datum_desc">Jetzt endende Angebote zuerst</option>
            #<option value="order_preis_asc">Preiswerteste Angebote zuerst</option>
            #<option value="order_preis_desc">Teuerste Angebote zuerst</option>
            #<option value="order_az_asc">Titel (A-Z)</option>
		
		if(isset($_GET['seite'])) {
			if($_GET['seite'] == 1) {
				$strLimitBy = ' LIMIT 0,50';
			} else {
				#$strLimitBy = ' LIMIT '.($_GET['seite'] * 100).','.(($_GET['seite'] * 100) + 100);
				if($_GET['seite'] > 1) {
					$seite = ($_GET['seite'] -1);
				} else {
					$seite = ($_GET['seite']);
				}
				
				$strLimitBy = ' LIMIT '.($seite * 50).',50';
			}
		} else {
			$strLimitBy = ' LIMIT 0,100';	
		}
		#echo $ids;
			switch($dataShopListe['orderby']) {
				case 'order_datum_asc':
					$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." (shop_cat_id IN(".$dataShopListe['shop_cat_id']."".$ids2.")) AND system_closed_shop='N' AND item_enabled='Y' AND menge > '0' ORDER BY updated_at DESC".$strLimitBy;
					break;
				case 'order_datum_desc':
					$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." (shop_cat_id IN(".$dataShopListe['shop_cat_id']."".$ids2.")) AND system_closed_shop='N' AND item_enabled='Y' AND menge > '0' ORDER BY updated_at ASC".$strLimitBy;
					break;
				case 'order_preis_asc':
					$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." (shop_cat_id IN(".$dataShopListe['shop_cat_id']."".$ids2.")) AND system_closed_shop='N' AND item_enabled='Y' AND menge > '0' ORDER BY preis ASC".$strLimitBy;
					break;
				case 'order_preis_desc':
					$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." (shop_cat_id IN(".$dataShopListe['shop_cat_id']."".$ids2.")) AND system_closed_shop='N' AND item_enabled='Y' AND menge > '0' ORDER BY preis DESC".$strLimitBy;
					break;
				default:
					$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." (shop_cat_id IN(".$dataShopListe['shop_cat_id']."".$ids2.")) AND system_closed_shop='N' AND item_enabled='Y' AND menge > '0' ORDER BY updated_at DESC".$strLimitBy;
					break;
			}		
			#echo $query;
			$query2 = "SELECT count(*) as anzahl FROM shop_item WHERE ".$LikeSuche." (shop_cat_id IN (".$dataShopListe['shop_cat_id']."".$ids2.")) AND system_closed_shop='N' AND item_enabled='Y' ORDER BY updated_at DESC";
			$resItemsCount = DBi::$conn->query($query2) or die(mysqli_error());
			$strItemsCount = mysqli_fetch_assoc($resItemsCount); 
			
			if($config['searchText'] != '') {	
				#############################################################
				# Suchenanfragen speichern 
				#############################################################
				$query3 ="SELECT count(*) as anzahl FROM suche_anfragen WHERE suchanfrage='".$config['searchText']."' AND shop_cat_id='".$dataShopListe['shop_cat_id']."'";
				$resSuchanfrageCount = DBi::$conn->query($query3) or die(mysqli_error());
				$strSuchanfrageCount = mysqli_fetch_assoc($resSuchanfrageCount);
				if($strSuchanfrageCount['anzahl'] == 0) {
					$query4 ="INSERT INTO suche_anfragen(suchanfrage,treffer,shop_cat_id) VALUES('".$config['searchText']."','".$strItemsCount['anzahl']."','".$dataShopListe['shop_cat_id']."')";	
					DBi::$conn->query($query4) or die(mysqli_error());
					
				} else {
					$query4 ="UPDATE suche_anfragen set suchanzahl=suchanzahl+1 WHERE suchanfrage='".$config['searchText']."'";	
					DBi::$conn->query($query4) or die(mysqli_error());
				}
			}
		#echo $query;
		$resShopList = DBi::$conn->query($query)or die(mysqli_error());
		$iCount = 0;
		$iSpalten =0;
		$pageing_modul_typ = 'shop_cat_list';
		$strPagging .= getPageBrowse($strItemsCount['anzahl'],$pageing_modul_typ,false);
		$strPagging .= '<script type="text/javascript">
  $(document).ready(function(){

    $(".paging").click(function(){

      // $(this).attr("id")
     // alert(  );
	 var seite=$(this).attr("id");
	 //alert(seite);
shop_cat_search(\''.$config['modul_id'].'\',\''.$dataShopListe['shop_cat_id'].'\',seite)
    });

  });
</script>';		
		$text .= $strPagging;
		#$iCount = 0;
		$strCSSLiKlass = '';
		$text .= '<ul class="shop_cat_list">';
		while($data = mysqli_fetch_assoc($resShopList)) {
			$path = getPathUrl($_SESSION['language'],$data['menue_id']);				
			$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
			$iCount++;
			
			if($iSpalten == 3) {
				#$text .= '<div style="clear:both"></div>';
				$strCSSLiKlass = 'shop_cat_list_last_item';
				$iSpalten=0;
			}
			$iSpalten++;
			$text .= '<li class="'.$strCSSLiKlass.'">';
			
			
			$text .= '<div class="css_gui_product_live_list" id="shop_list_item_'.$data['shop_item_id'].'">
			
			<form name="frmItemAdd_'.$data['shop_item_id'].'" id="cart_item_add_'.$data['shop_item_id'].'" action="cart/cart_item_add.php" method="POST" onSubmit="return cart_item_add(\'cart_item_add_'.$data['shop_item_id'].'\');">';
			


			
			$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$data['shop_item_id']."' AND picture_nr=1";
			$resPicture = DBi::$conn->query($query) or die(mysqli_error());
			$strPicture_cat = mysqli_fetch_assoc($resPicture);
			
			// Thumb Nail laden
			#$pic_type = strtolower(strrchr($strPicture['picture_url'],"."));
			#$pic_filename = str_replace($pic_type,"",$strPicture['picture_url']);	
			#$strNewPic = str_replace($pic_filename,$pic_filename.'_catList'.$pic_type,$pic_filename);
	
			$strBig = str_replace("/produkte/kategorie/","/produkte/orginal/",$strPicture_cat['picture_url']);
			if($strPicture_cat['picture_url'] != '') {
				#$text .= '<a class="cloud-zoom" href="'.$strPicture_cat['picture_url'].'" rel="useWrapper: true,showTitle: true, zoomWidth:\'300\', zoomHeight:\'400\', adjustY:0, adjustX:10">';
				$text .= '<a href="'.$strBig.'" rel="gallery" class="fancybox" title="'.$data['name_de'].'">';
			} else {
				$text .= '<a href="'.$strBig.'" rel="gallery" class="fancybox" title="'.$data['name_de'].'">'.$data['name_de'];
			}


			//Bild prüfen
			if($strPicture_cat['bild_geprüft'] == 'Y') {
				if($strPicture_cat['bild_aktiv'] == 'Y') {
					//URL gueltig
					$text .= '<img  class="shop_galery lazy" data-original="'.str_replace("/produkte/orginal/","/produkte/kategorie/",$strPicture_cat['picture_url']).'" alt="Icon" height="200" width="230" id="shop_item_picture_'.$data['shop_item_id'].'" title="'.$data['name_de'].'" style="z-index:9;"/>';					
				} else {
					//URL ungueltig
					$text .= '<img  class="shop_galery lazy"  data-original="/templates/software-anwendungsentwicklung/media/bilder/artikel-bilder-shopping-kein-bild.png" alt="Icon" height="200" width="230" id="shop_item_picture_'.$data['shop_item_id'].'" title="'.$data['name_de'].'" style="z-index:9;"/>';					
				}
			} else {
				
				
				#if(url_check('http://shopste.com'.str_replace("/produkte/orginal/","/produkte/kategorie/",$strPicture_cat['picture_url']))){
					//URL gueltig
					$query_bild = "UPDATE shop_item_picture SET bild_geprüft='Y',bild_aktiv='Y' WHERE shop_item_picture_id='".$strPicture_cat['shop_item_picture_id']."'";
					
					$text .= '<img  class="shop_galery lazy" data-original="'.str_replace("/produkte/orginal/","/produkte/kategorie/",$strPicture_cat['picture_url']).'" alt="Icon"  height="200" width="230" id="shop_item_picture_'.$data['shop_item_id'].'" title="'.$data['name_de'].'" style="z-index:9;"/>';
				#} else {
					//URL ungueltig
					// $query_bild = "UPDATE shop_item_picture SET bild_geprüft='Y',bild_aktiv='N' WHERE shop_item_picture_id='".$strPicture_cat['shop_item_picture_id']."'";
					// $text .= '<img class="lazy" data-original="/templates/software-anwendungsentwicklung/media/bilder/artikel-bilder-shopping-kein-bild.png" alt="Icon"  height="200" width="230" id="shop_item_picture_'.$data['shop_item_id'].'" border="0" title="'.$data['name_de'].'" style="z-index:9;" class="shop_galery "/>';
				#}			
				
				$resBildUpdate = DBi::$conn->query($query_bild) or die(mysqli_error());
			}
			
			$text .= '</a><div class="shop_item_header" id="shop_item_header_id_'.$iCount.'"><a style="width:90%;display:block;" href="'.$strLink.'">'.substr($data['name_de'],0,100).'</a></div>';
			#'MwSt_inkl','MwSt_exkl','MwSt_befreit'
			# Domain bestimmmen
			$domain = $_SERVER['HTTP_HOST'];
			$domain = str_replace("www.", "", $domain);
			$query = "SELECT * from domains WHERE name='$domain'";
			
			#$data['preis'] = str_replace(".",",",$data['preis']);
			$domain_res = mysqli_fetch_assoc(DBi::$conn->query($query));
			switch($domain_res['shop_mwst_setting']) {
				case 'MwSt_inkl':
					$text .= 'Preis: <strong>'.number_format($data['preis'], 2, ',', '.').' EUR </strong><font size="1">inkl. '.$data['item_mwst'].'% MwSt.</font>';
					break;
				case 'MwSt_exkl':
					$text .= 'Preis: <strong>'.number_format($data['preis'], 2, ',', '.').' EUR </strong><font size="1">exkl. '.$data['item_mwst'].'% MwSt.</font>';
					break;
				case 'MwSt_befreit':
					$text .= 'Preis: <strong>'.number_format($data['preis'], 2, ',', '.').' EUR </strong><br/><div class="kleinunternehmer"><font size="1">Kleinunternehmer : Der Umsatz ist nach dem Umsatzsteuergesetz § 19 nicht Steuerpflichtig</font></div>';
					break;
				case 'MwSt_privatverkauf':
					$text .= 'Preis: <strong>'.number_format($data['preis'], 2, ',', '.').' EUR </strong><font size="1">Privatverkauf</font>';
					break;							
				default:
					$text .= 'Preis: <strong>'.number_format($data['preis'], 2, ',', '.').' EUR </strong><br/>';
			}
			
			$text .= '</font><br/>
					<input type="hidden" name="shop_item_id" value="'.$data['shop_item_id'].'"/>
					<input type="hidden" name="shop_item_count" value="1"/>
					<input type="hidden" name="shop_item_price" value="'.$data['preis'].'"/>';
			if($data['menge']  > 0) {
		 
					$text .='<input type="submit" style="margin-bottom:25px" class="button" value="In den Warenkorb"/>';
 
			} else {
				$text .='Artikel ausverkauft';
			} 
				
					$text .='</form>
			</div>';
			$text .= '</li>';
		}
		$text .= '</ul>';
		
		
		$text .= '<div style="clear:both"></div>'.$strPagging;;	
		if($iCount == 0) {
			$text .= '<h2>Trefferliste</h2>Keine Artikel im Shopste Marktplatz gefunden. <br/><br/>Wechseln Sie zur Oberkategorie und Suchen Sie dort erneut.<br/><h2>Verkaufen beim Shopste Marktplatz für H&auml;ndler und Private</h2>Erstellen Sie noch heute Ihren <a href="http://shopste.com/de/3/Onlineshop-erstellen/" title="kostenlosen Online Shop erstellen">kostenlosen Online Shop</a> und nehmen Sie am Shopste Marktplatz teil.<br/>Benutzen Sie den Shopste Importer um Turbo Lister und Delcampe Lister3 CSV Dateien direkt zu importieren';
		}
		$text .= '<div style="clear:both"></div>
		</div></div>'; // Result |config modus 
			$text .= "<script>
		$(document).ready(function() {
			$('.fancybox').fancybox({
					helpers:  {
			thumbs : {
				width: 50,
				height: 50
			}
		},
				padding : 0,
				openEffect  : 'elastic'
			});
			
			//$('.shop_item_header h1 a').bigText();
		$(function() {
			$(\"img.lazy\").lazyload();
		});
		});
	</script>";
		return $text;
}
##################################
# >> Seiten Titel holen 
##################################
function getPageTitle_shop_cat($dataShopListe) { 

	# fix jb 
	# global $page_settings;
	$domain = $_SERVER['HTTP_HOST'];
	$domain = str_replace("www.", "", $domain);
	
	#echo $dataShopListe['shop_cat_id'];
	$parentmenures_last = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM shop_category WHERE shop_cat_id='".$dataShopListe['shop_cat_id']."'"));
	
	$parentres_lastparent = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM shop_category_parent WHERE shop_cat_id='".$parentmenures_last['shop_cat_id']."'"));
	#print_r($parentres_lastparent);
	
	$parentmenures = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM shop_category WHERE shop_cat_id='".$parentres_lastparent['shop_cat_parent']."'"));
	#print_r($parentmenures);
	#$parentres = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM shop_category_parent WHERE shop_cat_parent='".$parentres_lastparent['shop_cat_parent']."'"));
#print_r($parentres);
	#$parentres2 = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM shop_category_parent WHERE shop_cat_parent='".$parentmenures[shop_cat_id]."'"));
	#$parentmenures2 = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM shop_category WHERE shop_cat_id='".$parentres2[shop_cat_id]."'"));
#print_r($parentmenures2);
	#$parentres3 = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM shop_category_parent WHERE shop_cat_parent='".$parentmenures2[shop_cat_id]."'"));
	#$parentmenures3 = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM shop_category WHERE shop_cat_id='".$parentres3[shop_cat_parent]."'"));

	$pagetitle = ''; 
 #print_r($parentmenures);
    /* if ($parentmenures3["name_".$_SESSION['language']] != '') {
	
		if (empty($pagetitle)) {
			$pagetitle .= $parentmenures3["name_".$_SESSION['language']];
		} else {
			$pagetitle .= " - ".$parentmenures3["name_".$_SESSION['language']];
		}	
		
	}*/

 if ($parentmenures["name_".$_SESSION['language']] != '') {
	
		if (empty($pagetitle)) {
			$pagetitle .=  $parentmenures["name_".$_SESSION['language']];
		} else {
			$pagetitle .=  ' - '.$parentmenures["name_".$_SESSION['language']];
		}		
		
	}


  if ($parentmenures_last["name_".$_SESSION['language']] != '') {

		if (empty($pagetitle)) {
			$pagetitle .= $parentmenures_last["name_".$_SESSION['language']];
		} else {
			$pagetitle .= " - ".$parentmenures_last["name_".$_SESSION['language']];
		}	
				
	} 	
	#$pagetitle = ' Online Shoppen';
	return $pagetitle;
}

####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_shop_cat_list($config) {

		$dataShopListe = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_shop_cat_list WHERE id=".$config['modul_id']));
		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='shop_cat_list'"));
		#echo $config['modul_id'];
		
		$dataShopListe['typ'] = 'shop_cat_list';
		
		$titel = convertUmlaute($dataShopListe["title_".$_SESSION['language']]);
		if($titel == '') { 
			$titel = getPageTitle_shop_cat($dataShopListe);
		}
		$text = '<div class="content shop_cat_list" id="modul_'.$config['typ'].'_'.$config['modul_id'].'"><br/>';
		#$text .= '<h1 class="shop_category_header1">'.$titel.' Online Shoppen<a name="shop_sort"></a></h1>';
	
		# SUCHANFRAGEN ausgeben
		if($_GET['suche'] == '') {
			$query = "SELECT * FROM suche_anfragen WHERE freigeschaltet='Y' AND shop_cat_id=".$dataShopListe['shop_cat_id']." ORDER BY updated_at DESC LIMIT 0,5";
			$resSuchanfragen = DBi::$conn->query($query) or die(mysqli_error());
			$iCount = 0 ;
			while($strSuchanfragen = mysqli_fetch_assoc($resSuchanfragen)) {
				$strPath = str_replace('/Suche/'.urlencode($strSuchanfragen['suchanfrage']),'',$_SERVER['REQUEST_URI']);
				$strPath = $strPath.'Suche/'.urlencode($strSuchanfragen['suchanfrage']);
				if($iCount == 0) {
					$text .= '<br/>Die letzten Suchanfragen: ';
				}
				
				$text .= '<a href="'.$strPath.'/">'.$strSuchanfragen['suchanfrage'].'</a> ';
				$iCount++;
			}		
			if($iCount != 0) {
				$text .= '<br/><br/>';
			}
		}
			
		if($_GET['suche'] != '') {
			$config['searchText'] = $_GET['suche'];
			$strValueSuchen = $config['searchText'];
		} else {
			$strValueSuchen = "Bitte Suchbegriff eingeben";
		}
		
		#Onkeydown="return shop_cat_search(\''.$config['modul_id'].'\',\''.$dataShopListe['shop_cat_id'].'\')"
		$text .= '<br/>
			<input type="text"  onclick="javascript:shop_cat_search_intro()" onBlur="shop_cat_search_intro_reset()" id="txtQuickSearch" onkeypress="return onEnterPortal(event,\''.$config['modul_id'].'\',\''.$dataShopListe['shop_cat_id'].'\')" name="txtQuickSearch" value="'.$strValueSuchen.'"/>';
			
		#$text .='	<input type="text" onclick="javascript:shop_cat_search_intro()" onBlur="shop_cat_search_intro_reset()" id="txtQuickSearch" name="txtQuickSearch" value="'.$strValueSuchen.'"/>';
		$text .='<input type="hidden" name="modul_id" id="modul_id" value="'.$config['modul_id'].'"/>
		<input type="hidden" name="shop_cat_id" id="shop_cat_id" value="'.$dataShopListe['shop_cat_id'].'"/>
		
		<span class="button" style="margin-left:10px" onClick="shop_cat_search(\''.$config['modul_id'].'\',\''.$dataShopListe['shop_cat_id'].'\',\'1\')" >Suchen</span>';
		$text .= '<select onchange="javascript:shop_cat_sort(\''.$config['modul_id'].'\',\''.$dataShopListe['shop_cat_id'].'\',\'preis\')" class="selectListSort" id="shop_category_sortby" name="optSortBy">
            <option value="order_datum_asc">Neueste Angebote zuerst</option>
            <option value="order_datum_desc">Jetzt endende Angebote zuerst</option>
            <option value="order_preis_asc">Preiswerteste Angebote zuerst</option>
            <option value="order_preis_desc">Teuerste Angebote zuerst</option>
            <option value="order_az_asc">Titel (A-Z)</option>
        </select><br/><br/>';
		 
		$text .= convertUmlaute($dataShopListe["content_".$_SESSION['language']]);		
		

		
		if($text == '') {   
			$text = convertUmlaute($dataShopListe["content_de"]); 
		} 
		
		if($titel == '') { 
			$titel = convertUmlaute($dataShopListe["title_de"]); 
		} 
		
		// && $config["container"]
		if($_SESSION['login'] == '1'  AND $module_in_menue['container'] == 'col-main') {
			$strReturn = getMember($dataShopListe['last_usr']);
			if(!empty($strReturn)) {
				$ary = explode(" ",$dataShopListe['lastchange']);
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

		
		##############################
		# >> Inline suche
		##############################
		#$text = stringToFunction($text);
		#echo $_SERVER['DOCUMENT_ROOT'];

		$text .= setUpdateCarList($config,$dataShopListe);

	$titel .= " Online kaufen";
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 } 
 if ($_GET['bAjaxLoad'] == "true") {
 	include_once('../../include/inc_config-data.php');
	include_once('../../include/inc_basic-functions.php');
	include_once('../../include/inc_pagging.php');
	
	$_POST = mysql_real_escape_array($_POST);
	$_GET  = mysql_real_escape_array($_GET);	
	
	if(CORE_PIWIK_ACTIVE == 'YES') {
			$path = realpath($_SERVER['DOCUMENT_ROOT']);  
			require_once ($path."/framework/piwik/PiwikTracker.php");
			if(isset($_GET['suchtext'])) {
				PiwikTracker::$URL = 'https://shopste.com/framework/piwik/';
				$t = new PiwikTracker( $idSite = 1 );
				$t->setTokenAuth(CORE_PIWIK_API_KEY);
				$t->doTrackPageView('Subshop Suche nach '.$config['searchText']." - ".$dataShopListe['shop_cat_id']);
				$t->setIp($SERVER['REMOTEADDR']);
				
				if(isset($SERVER['HTTPREFERER'])) {
					$t->setUrl($SERVER['HTTPREFERER']);						
				}			
			} else {
				PiwikTracker::$URL = 'https://shopste.com/framework/piwik/';
				$t = new PiwikTracker( $idSite = 1 );
				$t->setTokenAuth(CORE_PIWIK_API_KEY);
				$t->doTrackPageView('Subshop Katgorie blättern'.$_GET['shop_cat_id']);
				$t->setIp($SERVER['REMOTEADDR']);
				
				if(isset($SERVER['HTTPREFERER'])) {
					$t->setUrl($SERVER['HTTPREFERER']);						
				}						
			}
	}
		
	$config['modul_id'] = $_GET['modul_id'];
	$config['searchText'] = $_GET['suchtext'];
	$dataShopListe['shop_cat_id'] = $_GET['shop_cat_id'];
	$dataShopListe['orderby'] = $_GET['orderby'];
	$dataShopListe['orderby_modus'] = $_GET['orderby_modus'];
	echo setUpdateCarList($config,$dataShopListe);
 }
 ?>