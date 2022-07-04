<?php 
	@session_start();
function getSubKategorie($iParrentCat,$strIDs,$level) {

	$query = "SELECT * FROM shop_category_parent LEFT JOIN shop_category ON shop_category_parent.shop_cat_id=shop_category.shop_cat_id	WHERE shop_category_parent.shop_cat_parent=".$iParrentCat." AND shop_category.domain_id='".$_SESSION['domain_id']."'  ORDER BY name_".$_SESSION['language']." ASC"; 
 
	$resCat = DBi::$conn->query($query) or die(mysqli_error());
	
	while($strCatMenue = mysqli_fetch_assoc($resCat)) {	
		$strIDs .= " OR shop_cat_id=".$strCatMenue['shop_cat_id'];
		#echo $strIDs; 
		$strIDs = getSubKategorie($strCatMenue['shop_cat_id'],$strIDs,$level+1);
		#echo $strCatMenue['shop_cat_id'];
	}
	
	return $strIDs;
	
}	
function setUpdateCarList($config,$dataShopListe) {

		$text .= '<div id="shop_cat_list_result_'.$config['modul_id'].'">';
		
		if($config['searchText'] == '') {
			$LikeSuche ='';
		}  else {
 
			$strWords = explode(" ",$config['searchText']);
			for($i=0; $i < count($strWords); $i++) {
				$LikeSuche .= " name_de LIKE '%".$strWords[$i]."%' AND";		
			}
		}
		#echo $dataShopListe['shop_cat_id'];
		$ids = getSubKategorie($dataShopListe['shop_cat_id'],"",0);
			#<option value="order_datum_asc">Neueste Angebote zuerst</option>
            #<option value="order_datum_desc">Jetzt endende Angebote zuerst</option>
            #<option value="order_preis_asc">Preiswerteste Angebote zuerst</option>
            #<option value="order_preis_desc">Teuerste Angebote zuerst</option>
            #<option value="order_az_asc">Titel (A-Z)</option>
		$strLimitBy = ' LIMIT 0,150';
			switch($dataShopListe['orderby']) {
				case 'order_datum_asc':
					$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." (shop_cat_id='".$dataShopListe['shop_cat_id']."' ".$ids.") AND system_closed_shop='N' AND item_enabled='Y' ORDER BY updated_at DESC";
					break;
				case 'order_datum_desc':
					$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." (shop_cat_id='".$dataShopListe['shop_cat_id']."' ".$ids.") AND system_closed_shop='N' AND item_enabled='Y' ORDER BY updated_at ASC";;
					break;
				case 'order_preis_asc':
					$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." (shop_cat_id='".$dataShopListe['shop_cat_id']."' ".$ids.") AND system_closed_shop='N' AND item_enabled='Y' ORDER BY preis ASC";;
					break;
				case 'order_preis_desc':
					$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." (shop_cat_id='".$dataShopListe['shop_cat_id']."' ".$ids.") AND system_closed_shop='N' AND item_enabled='Y' ORDER BY preis DESC";;
					break;
				default:
					$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." (shop_cat_id='".$dataShopListe['shop_cat_id']."' ".$ids.") AND system_closed_shop='N' AND item_enabled='Y' ORDER BY updated_at DESC".$strLimitBy;
					break;
			}			

			$query2 = "SELECT count(*) as anzahl FROM shop_item WHERE ".$LikeSuche." (shop_cat_id='".$dataShopListe['shop_cat_id']."' ".$ids.") AND system_closed_shop='N' AND item_enabled='Y' ORDER BY updated_at DESC".$strLimitBy;
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
			
		$resShopList = DBi::$conn->query($query)or die(mysqli_error());
		$iCount = 0;
		$iSpalten =0;
		while($data = mysqli_fetch_assoc($resShopList)) {
			$path = getPathUrl($_SESSION['language'],$data['menue_id']);				
			$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
			$iCount++;
			if($iSpalten == 3) {
				$text .= '<div style="clear:both"></div>';
				$iSpalten=0;
			}
			$iSpalten++;
			$text .= '<div id="shop_list_item">
			
			<form name="frmItemAdd_'.$data['shop_item_id'].'" id="cart_item_add_'.$data['shop_item_id'].'" action="cart/cart_item_add.php" method="POST" onSubmit="return cart_item_add(\'cart_item_add_'.$data['shop_item_id'].'\');">';
			

			
			$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$data['shop_item_id']."' AND picture_nr=1";
			$resPicture = DBi::$conn->query($query) or die(mysqli_error());
			$strPicture_cat = mysqli_fetch_assoc($resPicture);
			
			// Thumb Nail laden
			#$pic_type = strtolower(strrchr($strPicture['picture_url'],"."));
			#$pic_filename = str_replace($pic_type,"",$strPicture['picture_url']);	
			#$strNewPic = str_replace($pic_filename,$pic_filename.'_catList'.$pic_type,$pic_filename);
	
			
			if($strPicture_cat['picture_url'] != '') {
				$text .= '<a class="cloud-zoom" href="'.$strPicture_cat['picture_url'].'" rel="useWrapper: true,showTitle: true, zoomWidth:\'300\', zoomHeight:\'400\', adjustY:0, adjustX:10">';
			} else {
				$text .= '<a href="'.$strPicture['picture_url'].'" >'.$data['name_de'];
			}
			
			if($strPicture_cat['picture_url'] != '') {
				$strBig = str_replace("/produkte/orginal/","/produkte/kategorie/",$strPicture_cat['picture_url']);
				$text .= '<img id="shop_item_picture_'.$data['shop_item_id'].'" src="'.$strBig.'" height="200" width="230" border="0" title="'.$data['name_de'].'" style="z-index:9"/></a><br/>';
			}
			$text .= '<div class="shop_item_header"><h1><a style="width:90%;display:block;" href="'.$strLink.'">'.$data['name_de'].'</a></h1></div>';
			#'MwSt_inkl','MwSt_exkl','MwSt_befreit'
			# Domain bestimmmen
			$domain = $_SERVER['HTTP_HOST'];
			$domain = str_replace("www.", "", $domain);
			$query = "SELECT * from domains WHERE name='$domain'";
			
			$data['preis'] = str_replace(".",",",$data['preis']);
			$domain_res = mysqli_fetch_assoc(DBi::$conn->query($query));
			switch($domain_res['shop_mwst_setting']) {
				case 'MwSt_inkl':
					$text .= 'Preis: <strong>'.$data['preis'].' EUR </strong><font size="1">inkl. '.$data['item_mwst'].'% MwSt.</font>';
					break;
				case 'MwSt_exkl':
					$text .= 'Preis: <strong>'.$data['preis'].' EUR </strong><font size="1">exkl. '.$data['item_mwst'].'% MwSt.</font>';
					break;
				case 'MwSt_befreit':
					$text .= 'Preis: <strong>'.$data['preis'].' EUR </strong><br/><font size="1">Kleinunternehmer : Der Umsatz ist nach dem Umsatzsteuergesetz <br/>§ 19 nicht Steuerpflichtig</font>';
					break;
			}
			
			$text .= '</font><br/>
					<input type="hidden" name="shop_item_id" value="'.$data['shop_item_id'].'"/>
					<input type="hidden" name="shop_item_count" value="1"/>
					<input type="hidden" name="shop_item_price" value="'.$data['preis'].'"/>';
			if($data['menge']  > 0) {
				$text .='<input type="submit" class="button" value="In den Warenkorb"/>';
			} else {
				$text .='Artikel ausverkauft';
			}
				
					$text .='</form>
			</div>';
		}
			
		if($iCount == 0) {
			$text .= '<h2>Trefferliste</h2>Keine Artikel im Shopste Marktplatz gefunden. <br/><br/>Wechseln Sie zur Oberkategorie und Suchen Sie dort erneut.<br/><h2>Verkaufen beim Shopste Marktplatz für H&auml;ndler und Private</h2>Erstellen Sie noch heute Ihren <a href="http://shopste.com/de/3/Onlineshop-erstellen/" title="kostenlosen Online Shop erstellen">kostenlosen Online Shop</a> und nehmen Sie am Shopste Marktplatz teil.<br/>Benutzen Sie den Shopste Importer um Turbo Lister und Delcampe Lister3 CSV Dateien direkt zu importieren';
		}
		$text .= '<div style="clear:both"></div>
		</div></div>'; // Result |config modus 
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
function LoadModul_shop_cat_neuste($config) {

		$dataShopListe = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_shop_cat_neuste WHERE id=".$config['modul_id']));
		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='shop_cat_neuste'"));
		#echo "IN";
		
		$dataShopListe['typ'] = 'shop_cat_neuste';
		
		$titel = convertUmlaute($dataShopListe["title_".$_SESSION['language']]);
		if($titel == '') { 
			$titel = getPageTitle_shop_cat($dataShopListe);
		}
		$text = '<div class="content shop_cat_neuste" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
		$text .= '<h1 class="shop_category_header1">'.$titel.' Online Shoppen<a name="shop_sort"></a></h1>';
	
		# SUCHANFRAGEN ausgeben
		if($_GET['suche'] == '') {
			$query = "SELECT * FROM suche_anfragen WHERE freigeschaltet='Y' AND shop_cat_id=".$dataShopListe['shop_cat_id']." ORDER BY updated_at DESC LIMIT 0,5";
			$resSuchanfragen = DBi::$conn->query($query) or die(mysqli_error());
			$iCount = 0 ;
			while($strSuchanfragen = mysqli_fetch_assoc($resSuchanfragen)) {
				$strPath = str_replace('/suche/'.urlencode($strSuchanfragen['suchanfrage']),'',$_SERVER['REQUEST_URI']);
				$strPath = $strPath.'suche/'.urlencode($strSuchanfragen['suchanfrage']);
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
		
		$text .='	<input type="text" onclick="javascript:shop_cat_search_intro()"  onKeyUp="shop_cat_search(\''.$config['modul_id'].'\',\''.$dataShopListe['shop_cat_id'].'\')" onBlur="shop_cat_search_intro_reset()" id="txtQuickSearch" name="txtQuickSearch" value="'.$strValueSuchen.'"/>';
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
	$_POST = mysql_real_escape_array($_POST);
	$_GET  = mysql_real_escape_array($_GET);	
	$config['modul_id'] = $_GET['modul_id'];
	$config['searchText'] = $_GET['suchtext'];
	$dataShopListe['shop_cat_id'] = $_GET['shop_cat_id'];
	$dataShopListe['orderby'] = $_GET['orderby'];
	$dataShopListe['orderby_modus'] = $_GET['orderby_modus'];
	echo setUpdateCarList($config,$dataShopListe);
 }
 ?>