<?php 
	@session_start();
	$path = realpath($_SERVER['DOCUMENT_ROOT']);
	
	include_once($path.'/include/inc_pagging.php');
	
function setUpdateCarList($config,$dataShopListe) {


		$text = '<div id="portal_shop_cat_list_result_'.$config['modul_id'].'">';
		if(!isset($config['searchText'])) {
			$config['searchText'] = '';
		}
		if($config['searchText'] == '' || $config['searchText'] == 'Bitte Suchbegriff eingeben...') {
			$LikeSuche ='';
		}  else {
			$strWords = explode(" ",$config['searchText']);


			for($i=0; $i < count($strWords); $i++) {
				$LikeSuche .= " name_de LIKE '%".$strWords[$i]."%' AND";		
			}
		}
		#echo $dataShopListe['shop_cat_id'];
		#echo $ids.$dataShopListe['shop_cat_id'];
		#exit(0);
		#$time_start = microtime(true);
		
		#$ids = getSubKategorie_portal($dataShopListe['shop_cat_id'],"",0);

		#$time_end = microtime(true);
		#$execution_time = ($time_end - $time_start);
		#$text .= $execution_time.' Minuten';
		
		#echo $ids;
		#$query2 = "SELECT count(shop_item_id) as anzahl FROM shop_item WHERE ".$LikeSuche." (shop_cat_id='".$dataShopListe['shop_cat_id']."' ".$ids.") AND system_closed_shop='N' AND item_enabled='Y' ORDER BY updated_at DESC";
		
		$ids2 = getSubKategorie_portal_ids($dataShopListe['shop_cat_id'],"",0);
		$ids2 = substr($ids2,0,(strlen($ids2) -1));
		if(strlen($ids2) > 0) {
			$ids2 = ','.$ids2;
		}		
		
		#print_r($ids2);
		$query2 = "SELECT count(*) as anzahl FROM shop_item_category2items JOIN  shop_item  ON shop_item.shop_item_id = shop_item_category2items.shopste_item WHERE ".$LikeSuche." (shop_item_category2items.shopste_item_cat IN (".$dataShopListe['shop_cat_id']."".$ids2.")) AND system_closed_shop='N' AND item_enabled='Y' ORDER BY shop_item.updated_at DESC";
		#print_r($query2);
		
		$resItemsCount = DBi::$conn->query($query2) or die(mysqli_error(DBi::$conn));
		$strItemsCount = mysqli_fetch_assoc($resItemsCount); 
		
		#$time_start = microtime(true);
		$pageing_modul_typ = 'portal_shop_cat_list';
		$strPagging = getPageBrowse($strItemsCount['anzahl'],$pageing_modul_typ,false);
		#$time_end = microtime(true);
		#$execution_time = ($time_end - $time_start);
		#$text .= $execution_time.' Minuten';
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

		########################################################################
		# Attributfilter einbauen
		########################################################################
		/* $query = "SELECT * FROM shop_item WHERE ".$LikeSuche." (shopste_marktplatz_cat='".$dataShopListe['shop_cat_id']."' ".$ids.") AND system_closed_shop='N' AND item_enabled='Y' ORDER BY updated_at DESC";
		#$time_start = microtime(true);
		$resItemListAll = DBi::$conn->query($query) or die(mysqli_error());
		#$time_end = microtime(true);
		#$execution_time = ($time_end - $time_start);
		#$text .= $execution_time.' Minuten';
		while($strItem = mysqli_fetch_assoc($resItemListAll)) {
			
			$query = "SELECT * FROM shop_item_eigenschaft JOIN shop_item_eigenschaftwert ON shop_item_eigenschaft.shop_item_eigenschaft_id = shop_item_eigenschaftwert.id_shop_item_eigenschaft WHERE shop_item_eigenschaft.id_shop_item =".$strItem['shop_item_id'].' ORDER BY shop_item_eigenschaft.shop_item_eigenschaft_id ASC';
			$res = DBi::$conn->query($query) or die(mysqli_error());
			$iCount = 0;
			$iCountAttribute = 0;
			$bIn = false;
			$iSelectClose =0;
			$strAttribAry = array();
			while($strEigenschaft = mysqli_fetch_assoc($res)) {

					if($iEigenschaft != $strEigenschaft['shop_item_eigenschaft_id']) {
						$iCount = 0;
						$strAttribAry[$iCountAttribute] = $strEigenschaft['shop_item_eigenschaft_id'];
						$iCountAttribute++;
					}
				#echo $iEigenschaft.'--'.$iCount.' ';
					if($iCount == 0) {
						if($iSelectClose > 0) {
							$text .= '</select><br/>';
							#$iCountAttribute++;
						}
						$iSelectClose++;
						$text .= '<strong class="optUserLabel">'.$strEigenschaft['eigenschaft_name_de'].'*</strong><br/>';
						$text .= '<select class="optUserSelect" size="1" name="optModul" id="eigenschaft_'.$strEigenschaft['shop_item_eigenschaft_id'].'" onChange="shop_item_attribute(\''.$strEigenschaft['shop_item_eigenschaft_id'].'\')">';						
						$text .= '<option class="optUserSelectVal" value="Keine-Auswahl">Bitte Auswahl treffen</option>';
						#$text .= '<option value="'.$strEigenschaft['shop_item_eigenschaftwert_id'].'">'.$strEigenschaft['name_de'].'</option>';
						$text .= '<option class="optUserSelectVal" value="'.$strEigenschaft['name_de'].'">'.$strEigenschaft['name_de'].'</option>';
						
					} else {
						#$text .= '<option value="'.$strEigenschaft['shop_item_eigenschaftwert_id'].'">'.$strEigenschaft['name_de'].'</option>';
						$text .= '<option class="optUserSelectVal" value="'.$strEigenschaft['name_de'].'">'.$strEigenschaft['name_de'].'</option>';
					}

					$iEigenschaft = $strEigenschaft['shop_item_eigenschaft_id'];

				$iCount++;
				
				$bIn=true;
			} # end while 
		} # end while  */
		
		#echo $dataShopListe['shop_cat_id'];
		#print_r($ids);
		
			#<option value="order_datum_asc">Neueste Angebote zuerst</option>
            #<option value="order_datum_desc">Jetzt endende Angebote zuerst</option>
            #<option value="order_preis_asc">Preiswerteste Angebote zuerst</option>
            #<option value="order_preis_desc">Teuerste Angebote zuerst</option>
            #<option value="order_az_asc">Titel (A-Z)</option>
			#echo $LikeSuche;
		if(isset($_GET['seite'])) {
			if($_GET['seite'] == 1) {
				$strLimitBy = ' LIMIT 0,50';
			} else {
				#$strLimitBy = ' LIMIT '.($_GET['seite'] * 50).','.(($_GET['seite'] * 50) + 50);
				#$strLimitBy = ' LIMIT '.($_GET['seite'] * 50).','.(($_GET['seite'] * 50) + 50);
				#$strLimitBy = ' LIMIT '.($_GET['seite'] * 50).',50';	
				if($_GET['seite'] > 1) {
					$seite = ($_GET['seite'] -1);
				} else {
					$seite = ($_GET['seite']);
				}
				
				$strLimitBy = ' LIMIT '.($seite * 50).',50';
				
			}
		} else {
			$strLimitBy = ' LIMIT 0,50';	
		}
		
		if(!isset($dataShopListe['orderby'])) {
			$dataShopListe['orderby'] = '';
		}
		
		switch($dataShopListe['orderby']) {
				case 'order_datum_asc':
					$query = "SELECT * FROM shop_item_category2items JOIN  shop_item  ON shop_item.shop_item_id = shop_item_category2items.shopste_item WHERE ".$LikeSuche." (shop_item_category2items.shopste_item_cat IN(".$dataShopListe['shop_cat_id']."".$ids2.")) AND system_closed_shop='N' AND item_enabled='Y' AND menge > '0' ORDER BY shop_item.updated_at DESC".$strLimitBy;
					break;
				case 'order_datum_desc':
					$query = "SELECT * FROM shop_item_category2items JOIN  shop_item  ON shop_item.shop_item_id = shop_item_category2items.shopste_item WHERE ".$LikeSuche." (shop_item_category2items.shopste_item_cat IN (".$dataShopListe['shop_cat_id']."".$ids2.")) AND system_closed_shop='N' AND item_enabled='Y' AND menge > '0' ORDER BY shop_item.updated_at ASC".$strLimitBy;
					break;
				case 'order_preis_asc':
					$query = "SELECT * FROM shop_item_category2items JOIN  shop_item  ON shop_item.shop_item_id = shop_item_category2items.shopste_item WHERE ".$LikeSuche." (shop_item_category2items.shopste_item_cat IN (".$dataShopListe['shop_cat_id']."".$ids2.")) AND system_closed_shop='N' AND item_enabled='Y' AND menge > '0' ORDER BY shop_item.preis ASC".$strLimitBy;
					break;
				case 'order_preis_desc':
					$query = "SELECT * FROM shop_item_category2items JOIN  shop_item  ON shop_item.shop_item_id = shop_item_category2items.shopste_item WHERE ".$LikeSuche." (shop_item_category2items.shopste_item_cat IN (".$dataShopListe['shop_cat_id']."".$ids2.")) AND system_closed_shop='N' AND item_enabled='Y' AND menge > '0' ORDER BY shop_item.preis DESC".$strLimitBy;
					break;
				default:
					$query = "SELECT * FROM shop_item_category2items JOIN  shop_item  ON shop_item.shop_item_id = shop_item_category2items.shopste_item WHERE ".$LikeSuche." (shop_item_category2items.shopste_item_cat IN (".$dataShopListe['shop_cat_id']."".$ids2.")) AND system_closed_shop='N' AND item_enabled='Y' AND menge > '0' ORDER BY shop_item.updated_at DESC".$strLimitBy;
					break; 
			}			
		#echo $query; 
		#$time_start = microtime(true);
		
		$resShopList = DBi::$conn->query($query)or die(mysqli_error(DBi::$conn));
		#$time_end = microtime(true);
		#$execution_time = ($time_end - $time_start);
		#$text .= $execution_time.' Minuten';
		$iCount = 0;	
		if(!isset($config['searchText'])) {
			$config['searchText'] ='';
		}
		if($config['searchText'] != '') {	
				#############################################################
				# Suchenanfragen speichern 
				#############################################################
				$query3 ="SELECT count(*) as anzahl FROM suche_anfragen WHERE suchanfrage='".$config['searchText']."' AND shop_cat_id='".$dataShopListe['shop_cat_id']."'";
				$resSuchanfrageCount = DBi::$conn->query($query3) or die(mysqli_error());
				$strSuchanfrageCount = mysqli_fetch_assoc($resSuchanfrageCount);
				if($strSuchanfrageCount['anzahl'] == 0) {
					# INSERT DELAYED 
					$query4 ="INSERT INTO suche_anfragen(suchanfrage,treffer,shop_cat_id,modul_typ) VALUES('".$config['searchText']."','".$strItemsCount['anzahl']."','".$dataShopListe['shop_cat_id']."','portal_shop_cat_list')";	
					DBi::$conn->query($query4) or die(mysqli_error());
					
				} else {
					$query4 ="UPDATE suche_anfragen SET suchanzahl=suchanzahl+1,treffer='".$strItemsCount['anzahl']."',modul_typ='portal_shop_cat_list' WHERE suchanfrage='".$config['searchText']."'";	
					DBi::$conn->query($query4) or die(mysqli_error(DBi::$conn));
				}
			}
			
		#$time_start = microtime(true);
		if(!isset($strValueSuchen)) {
			$strValueSuchen = '';
		}
		$strHeader = '<input type="text"  placeholder="Bitte Suchbegriff eingeben..." id="txtQuickSearch" onkeypress="return onEnterPortal(event,\''.$config['modul_id'].'\',\''.$dataShopListe['shop_cat_id'].'\')" name="txtQuickSearch" value="'.$strValueSuchen.'"/>
<span class="button" style="margin-left:10px" onClick="shop_cat_search(\''.$config['modul_id'].'\',\''.$dataShopListe['shop_cat_id'].'\',\'1\')" >Suchen</span>';
		$strHeader .= '<input type="hidden" id="isPortal" name="isPortal" value="Y"/>';
		$strHeader .= '<select onchange="javascript:shop_cat_sort(\''.$config['modul_id'].'\',\''.$dataShopListe['shop_cat_id'].'\',\'preis\')" class="selectListSort" id="shop_category_sortby" name="optSortBy">
            <option value="order_datum_asc">Neueste Angebote zuerst</option>
            <option value="order_datum_desc">Jetzt endende Angebote zuerst</option>
            <option value="order_preis_asc">Preiswerteste Angebote zuerst</option>
            <option value="order_preis_desc">Teuerste Angebote zuerst</option>
            <option value="order_az_asc">Titel (A-Z)</option>
        </select><br/><br/>';
		
		$text .= '###HEADER###';
		
		$text .= '<ul class="shop_cat_list">';
		while($data = mysqli_fetch_assoc($resShopList)) {
		#echo $data['shopste_marktplatz_menue_id'];
			
		
			/*$query = "SELECT * from domains WHERE domain_id='".$data['domain_id']."'";
			$domain_res = mysqli_fetch_assoc(DBi::$conn->query($query));
			
			if($domain_res['bIsSSL'] == 'Y') {
				$strSchema = 'https';
			} else {
				$strSchema = 'http';
			}*/

			$path = getPathUrl($_SESSION['language'],$data['shopste_marktplatz_menue_id']);				
			$strLink = 'https://'.$_SERVER['SERVER_NAME'].'/'.$path;
 			
			$iCount++;
			$text .= '<li class="lvw_item_single">';
			
			$text .= '<div id="shop_list_item_'.$data['shop_item_id'].'">
			
			<form name="frmItemAdd" id="cart_item_add_'.$data['shop_item_id'].'" action="cart/cart_item_add.php" method="POST" onSubmit="return cart_item_add(\'cart_item_add_'.$data['shop_item_id'].'\');">';
						
			$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$data['shop_item_id']."' AND picture_nr=1";
			
			#$time_start = microtime(true);
		
			$resPicture = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
			$strPicture_cat = mysqli_fetch_assoc($resPicture);
			#$time_end = microtime(true);
			#$execution_time = ($time_end - $time_start);
			#$text .= $execution_time.' Minuten';
			
			// Thumb Nail laden
			#$pic_type = strtolower(strrchr($strPicture['picture_url'],"."));
			#$pic_filename = str_replace($pic_type,"",$strPicture['picture_url']);	
			#$strNewPic = str_replace($pic_filename,$pic_filename.'_catList'.$pic_type,$pic_filename);
	
			
			#$strBig = str_replace("/produkte/kategorie/","/produkte/orginal/",$strPicture_cat['picture_url']);
			$strBig = $strPicture_cat['picture_url'];
			if($strPicture_cat['picture_url'] != '') {
				#$text .= '<a class="cloud-zoom" href="'.$strPicture_cat['picture_url'].'" rel="useWrapper: true,showTitle: true, zoomWidth:\'300\', zoomHeight:\'400\', adjustY:0, adjustX:10">';
				$text .= '<a href="'.$strBig.'" rel="gallery" class="fancybox" title="'.$data['name_de'].'">';
			} else {
				$text .= '<a href="'.$strBig.'" rel="gallery" class="fancybox" title="'.$data['name_de'].'">'.$data['name_de'];
			}
			
			//URL pruefen
			if($strPicture_cat['bild_geprüft'] == 'Y') {
				if($strPicture_cat['bild_aktiv'] == 'Y') {
					//URL gueltig
					#$text .= '<img src="'.str_replace("/produkte/orginal/","/produkte/kategorie/",$strPicture_cat['picture_url']).'" alt="Icon" height="200" width="230" id="shop_item_picture_'.$data['shop_item_id'].'" border="0" title="'.$data['name_de'].'" style="z-index:9;" class="shop_galery "/>';					
					$text .= '<img class="lazy" align="center" data-original="'.str_replace("/produkte/orginal/","/produkte/kategorie/",$strPicture_cat['picture_url']).'" alt="Shopste Bild" height="200" width="230" id="shop_item_picture_'.$data['shop_item_id'].'" title="'.$data['name_de'].'" style="z-index:9;" class="shop_galery"/>';	
				} else {
					//URL ungueltig
					$text .= '<img  class="lazy" align="center" data-original="/templates/software-anwendungsentwicklung/media/bilder/artikel-bilder-shopping-kein-bild.png" alt="Icon" height="200" width="230" id="shop_item_picture_'.$data['shop_item_id'].'" title="'.$data['name_de'].'" style="z-index:9;" class="shop_galery"/>';					
				}
			} else {
				
				
				#if(url_check('http://shopste.com'.str_replace("/produkte/orginal/","/produkte/kategorie/",$strPicture_cat['picture_url']))){
					//URL gueltig
					$query_bild = "UPDATE shop_item_picture SET bild_geprüft='Y',bild_aktiv='Y' WHERE shop_item_picture_id='".$strPicture_cat['shop_item_picture_id']."'";
					
					#$text .= '<img src="'.str_replace("/produkte/orginal/","/produkte/kategorie/",$strPicture_cat['picture_url']).'" alt="Icon" height="200" width="230" id="shop_item_picture_'.$data['shop_item_id'].'" border="0" title="'.$data['name_de'].'" style="z-index:9;" class="shop_galery "/>';
					$text .= '<img  class="shop_galery lazy" align="center" data-original="'.str_replace("/produkte/orginal/","/produkte/kategorie/",$strPicture_cat['picture_url']).'" alt="Icon" height="200" width="230" id="shop_item_picture_'.$data['shop_item_id'].'" title="'.$data['name_de'].'" style="z-index:9;"/>';	
				#} else {
					//URL ungueltig
					/* $query_bild = "UPDATE shop_item_picture SET bild_geprüft='Y',bild_aktiv='N' WHERE shop_item_picture_id='".$strPicture_cat['shop_item_picture_id']."'";
					$text .= '<img class="lazy" data-original="/templates/software-anwendungsentwicklung/media/bilder/artikel-bilder-shopping-kein-bild.png" alt="Icon" height="200" width="230" id="shop_item_picture_'.$data['shop_item_id'].'" title="'.$data['name_de'].'" style="z-index:9;" class="shop_galery"/>'; */
				#}			
				
				$resBildUpdate = DBi::$conn->query($query_bild) or die(mysqli_error(DBi::$conn));
			}
			
			$text .= '</a><div class="shop_item_header"><a href="'.$strLink.'">'.substr($data['name_de'],0,75).'</a></div>';
			 
			$query = "SELECT * from domains WHERE domain_id='".$data['domain_id']."'";
			$domain_res = mysqli_fetch_assoc(DBi::$conn->query($query));
			
			switch($domain_res['shop_mwst_setting']) {
				case 'MwSt_inkl':
					$text .= '<strong>'.number_format($data['preis'], 2, ',', '.').' EUR </strong><font size="1">inkl. '.$data['item_mwst'].'% MwSt.</font>';
					break;
				case 'MwSt_exkl':
					$text .= '<strong>'.number_format($data['preis'], 2, ',', '.').' EUR </strong><font size="1">exkl. '.$data['item_mwst'].'% MwSt.</font>';
					break;
				case 'MwSt_befreit':
					$text .= '<strong>'.number_format($data['preis'], 2, ',', '.').' EUR </strong><br/><div class="kleinunternehmer"><font size="1">Kleinunternehmer UStG §19</font></div>';
					break;
				case 'MwSt_privatverkauf':
					$text .= '<strong>'.number_format($data['preis'], 2, ',', '.').' EUR </strong><font size="1">Privatverkauf</font>';
					break;
				default:
					$text .= '<strong>'.number_format($data['preis'], 2, ',', '.').' EUR </strong><br/>';					
			}
			#$text .= 'Preis: '.$data['preis'].' EUR <font size="1">inkl. '.$data['item_mwst'].'% MwSt.</font><br/>
			
					$text .= '<input type="hidden" name="shop_item_id" value="'.$data['shop_item_id'].'"/>
					<input type="hidden" name="shop_item_count" value="1"/>
					<input type="hidden" name="shop_item_price" value="'.$data['preis'].'"/>';
			if($data['menge']  > 0) {
				#$text .='<input type="submit" value="In den Warenkorb"/>';
			} else {
				$text .='Artikel ausverkauft';
			}
				
					$text .='</form>
			</div>';
			$text .= '</li>';
		}
		#$time_end = microtime(true);
			#$execution_time = ($time_end - $time_start);
			#$text .= $execution_time.' Minuten';
		$text .= '</ul>';
		
		if($iCount == 0) {			
			$text = str_replace('###HEADER###','',$text);
			$text .= '<h3>Keine Artikel in dieser Kategorie gefunden...</h3>
			<br/>Falls Sie noch nicht auf einer Oberkategorie sind, dann wechseln Sie zur Oberkategorie und Suchen Sie dort erneut.<br/>';
			
			$text .= '<div style="clear:both"></div>';
			$text .= $strPagging;
			
			$text .= '</div></div>'; // Result |config modus 
			$text .= '<br/><div class="block portal_shop_cat_list extended_site_information">';
				$text .= '<div class="block-title extended_site_information"><h1>Verkaufen Sie Produkte auf dem Shopste Marktplatz, der für H&auml;ndler und Privatleute gedacht ist!</h1></div>';
				
				$text .= '<div class="content extended_site_information">
				Erstellen Sie noch heute Ihren <a href="https://shopste.com/de/3/Onlineshop-erstellen/" title="kostenlosen Online Shop erstellen">kostenlosen Online Shop</a>
			<br/>Benutzen Sie den Shopste Lister um Turbo Lister und Delcampe Lister3 CSV Dateien direkt importieren zu können.
				</div>';
			$text .= '</div>'; 
			
			
		} else {
			$text = str_replace('###HEADER###',$strHeader,$text);
			$text .= '</div><div style="clear:both"></div>';
			$text .= $strPagging;
			$text .= '</div>'; 
		}

		$text .= "<script>
    $(document).ready(function() {
        $('.fancybox').fancybox({
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
function LoadModul_portal_shop_cat_list($config) {

		$dataShopListe = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_portal_shop_cat_list WHERE id=".$config['modul_id']));
		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='portal_shop_cat_list'"));
		#echo "IN";
		
		$dataShopListe['typ'] = 'portal_shop_cat_list';
		
		$titel = convertUmlaute($dataShopListe["title_".$_SESSION['language']]);
		if($titel == '') { 
			$titel = getPageTitle_shop_cat($dataShopListe);
		}
		
		$text = '<div class="content shop_cat_list" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
		$text .= '<br/>';
		# SUCHANFRAGEN ausgeben
		if(!isset($_GET['suche'])) {
			$_GET['suche'] = '';
		}
		
		if($_GET['suche'] == '') {
			$query = "SELECT * FROM suche_anfragen WHERE freigeschaltet='Y' AND shop_cat_id=".$dataShopListe['shop_cat_id']." ORDER BY updated_at DESC LIMIT 0,5";
			$resSuchanfragen = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
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
	
		
		
		if(isset($dataShopListe["content_".$_SESSION['language']])) {
			$text .= convertUmlaute($dataShopListe["content_".$_SESSION['language']]);		
		}

		
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
		#$time_start = microtime(true);
		
		$text .= setUpdateCarList($config,$dataShopListe);
		
		#$time_end = microtime(true);
		#$execution_time = ($time_end - $time_start);
		#$text .= $execution_time.' Minuten';
		$titel .= " Online kaufen";
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 } 
 if(!isset($_GET['bAjaxLoad'])) {
	 $_GET['bAjaxLoad'] = '';
 }
 if ($_GET['bAjaxLoad'] == "true") {	
 	
	include_once($path.'/include/inc_config-data.php');
	include_once($path.'/inc_basic-functions.php');		
	include_once($path.'/inc_pagging.php');
	$_POST = mysql_real_escape_array($_POST);
	$_GET  = mysql_real_escape_array($_GET);
	
	if(!isset($_GET['modul_id'])) {
		$_GET['modul_id'] ='';
	}
	if(!isset($_GET['orderby_modus'])) {
		$_GET['orderby_modus'] = '';
	}
				
/* 	if(CORE_PIWIK_ACTIVE == 'YES') {
		$path = realpath($_SERVER['DOCUMENT_ROOT']);  
		require_once ($path."/framework/piwik/PiwikTracker.php");
		if(isset($_GET['suchtext'])) {
			PiwikTracker::$URL = 'https://shopste.com/framework/piwik/';
			$t = new PiwikTracker( $idSite = 1 );
			$t->setTokenAuth(CORE_PIWIK_API_KEY);
			$t->doTrackPageView('Shopste.com Suche nach '.$config['searchText']." - ".$dataShopListe['shop_cat_id']);
			$t->setIp($SERVER['REMOTEADDR']);
			
			if(isset($SERVER['HTTPREFERER'])) {
				$t->setUrl($SERVER['HTTPREFERER']);						
			}			
		} else {
			PiwikTracker::$URL = 'https://shopste.com/framework/piwik/';
			$t = new PiwikTracker( $idSite = 1 );
			$t->setTokenAuth(CORE_PIWIK_API_KEY);
			$t->doTrackPageView('Shopste.com Katgorie blättern'.$_GET['shop_cat_id']);
			$t->setIp($SERVER['REMOTEADDR']);
			
			if(isset($SERVER['HTTPREFERER'])) {
				$t->setUrl($SERVER['HTTPREFERER']);						
			}						
		}
	} */
	
	$config['modul_id'] = $_GET['modul_id'];
	$config['searchText'] = $_GET['suchtext'];
	$dataShopListe['shop_cat_id'] = $_GET['shop_cat_id'];
	$dataShopListe['orderby'] = $_GET['orderby'];
	$dataShopListe['orderby_modus'] = $_GET['orderby_modus'];
	
	echo setUpdateCarList($config,$dataShopListe);
 }
 ?>