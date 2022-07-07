<?php 
	@session_start();
	
function getPageBrowse($setDataAnzahl) {
	
 #############################
 # >>  Config 
 # -------------------------------------------------------
 #  $iSizeUp 		= Anzahl im positiven Bereich
 #  $iSizeDown_tmp 	= Anzahl im negativen Bereich
 #  $iSizePerPage 	= Anzahl der Artikel pro Seite 
 #############################
 $iSizeUp = 6;
 $iSizeDown_tmp = 4;
 $iSizePerPage = 100;
 $strTableBrowse = '';
 $strNextPage ='';
# echo $setDataAnzahl.'#';
 if(!is_numeric($_GET['seite'])) {
	$_GET['seite'] = 1;
 }

 if(!empty($setDataPerPage)) {
	$iSizePerPage = $setDataPerPage;
 }
 
 if(isset($setDataAnzahl)) {
	$itemCounting = $setDataAnzahl;
}	else {	
		if(empty($itemCounting)) {
			$itemCounting = $setDataAnzahl;
		}
	}


 
 # Nächste Seite setzen
 if ((!isset($_GET['seite'])) || ($_GET['seite'] == "0")) {
	$iCount_next = 0;
 } else {
	$iCount_next = $_GET['seite'] * $iSizePerPage;
	#$iCount_next += $iSizePerPage;
 }
 
  # Zwischenspeichern des Nächsten 
 $iCount_next_tmp = ($iCount_next - $iSizePerPage);
 
 # Anzahl Seiten berechnen
# echo $iCount_next;
 $iAnzahl = ceil($iCount_next / $iSizePerPage);
	
 if ($iAnzahl == 0) {
	$SizeDown = 0;
	$bSetBefore = true;
 } else {
	$SizeDown = $iSizeDown_tmp;
	$bSetBefore = false;
 }
 #############
 # Minusbreich 
 #############
 if ($iAnzahl == 0) {
   $iStartCounter = $iAnzahl;
 } else {
   $iStartCounter = $iAnzahl ;
 }

# URL erzeugen 
$strPath = str_replace('/Seite/'.urlencode($_GET['seite']),'',$_SERVER['REQUEST_URI']);
$strPath = $strPath.'Seite/'; #.urlencode($_GET['seite']);
#$strPath = str_replace('//','/',$strPath);
#echo $iAnzahl.'-#'.$iStartCounter.'#'.($iAnzahl + $iSizeUp).'#'.$itemCounting;

 for($i = $iStartCounter; $i <= ($iAnzahl + $iSizeUp); $i++) {
	
	if ($i==1){

	   $iCount_next_tmp += $iSizePerPage;
	   continue;	   
	}
	
	# Nicht über der Artikelgröße 

	if ($iCount_next_tmp <= $itemCounting) {
	
		# Kleiner als angeklickte Seite 	
	    if ($SizeDown > 0 AND $bSetBefore == false) {
		  $iCount_next_tmp2 -= $iCount_next_tmp2- $iSizePerPage;
		  $iMin = $iStartCounter - $SizeDown;
		  
		  # Ausgeben der Seiten 
		  for ($x = $iMin; $x <= $iStartCounter-1; $x++) {
		  
		    if ($iMin > 0) {
 
				$strNextPage .= "<a href=\"".$strPath.''.$iMin."/\"/> [".($iMin)."] </a>";
			} 
			$iMin++;			
			$iCount_next_tmp2 -= $iSizePerPage;		
		
		  }
		  
		  $bSetBefore = true;
		  #$i--;
		} 		
		# Aktuelle Seite 
		
		
		if ($i == $iStartCounter) {		
				if ($i == 0) {
				  $strNextPage .= "[".(1)."]";			
				} else {
				  $strNextPage .= "[".($i)."]";			
				}
				
				$iCount_next_tmp += $iSizePerPage;
		} 
		# Höhere Seiten 
		else {
 
			$strNextPage .= "<a href=\"".$strPath."".$i."/\"> [".($i)."] </a>";						
			$iCount_next_tmp += $iSizePerPage;
		}		
		
	} else {
		break;
		
	}		
	
 }
  
  ####################
  # >> 1. Seite berücksichtigen
  ####################
  if ($iAnzahl == 0) {
	 
	
  } else {
	$iAnzahl_tmp = $iAnzahl;
  }
  

				
 $strTableBrowse = "<table width=\"65%\" bgcolor=\"#F2F2F2\"  border=\"0\" cellspacing=\"3\" align=\"center\" cellpadding=\"3\" style=\"border-width:2px; border-color:#F2F2F2; border-style:solid;\">
   <tr>
	<td colspan=\"3\">
	  <font color=\"000000\"><strong>Weitere Seiten anzeigen</strong>
	</td>
   </tr>
   <tr>
      <td colspan=\"3\">"."Auf Shop Seite ";
	  if ($iAnzahl_tmp == 0) {
			$strTableBrowse .=  '1';
			$iAnzahl_tmp = 1;
		} else {
			$strTableBrowse .=  $iAnzahl_tmp;
		}
		if($iCount_next > $itemCounting) {
			$anzahl = $itemCounting;
		} else {
			$anzahl = $iAnzahl_tmp * $iSizePerPage;
		} 
		$strTableBrowse .= " | ".$itemCounting." Artikel in dieser Kategorie ";		
			
	  
	  
	  $strTableBrowse .="</td>  
   </tr>
	<tr>
		<td id=\"highlight_tr_parent\">";
 
		
		if ($_GET['seite'] == 1) {
						$strTableBrowse .= "Zurück";
		} else {
			if($bGallerie == true) {
 				$strPara = '&iParrent='.$_GET['iParrent'];
			}
			$seite1 = ($_GET['seite'] - 1);
			if($seite1 <= 0) {
				$seite1 = 1;
			}
			$strTableBrowse .= "<a href=\"".$strPath."".$seite1."/\"/>Zurück</a>";
		}
						
		$strTableBrowse .= "</td>
		<td align=\"center\" bgcolor=\"#F2F2F2\">".
		$strNextPage	
		."</td>		
		<td id=\"highlight_tr_parent\">";

		# 1. Seite 
		if ($iCount_next == "0") {
			$iCount_next = $iSizePerPage;
		}
		
		if($iCount_next > $itemCounting) {
			$iCount_next = $itemCounting;					
			$strTableBrowse .= "nächste Seite";
			$bNoGetNext = true;
		} else {
 
			$strTableBrowse .= "<a href=\"".$strPath."".($_GET['seite'] + 1)."/\"/>Vor</a>";
		}		

		$strTableBrowse .= "</td>		
	</tr>
	</table><br/><a name=\"listeanfang\"></a>";
	
	# + 20 Kompensieren
	$iCount_next -= $iSizePerPage;
	
	
	if ($itemCounting < $iSizePerPage) {   
	   $iCount_next	= 0; 
	   
	   #$strTableBrowse = '';
    }	
	$iNext = $_GET['Next'] + $iSizePerPage;
	if($itemCounting <= $iNext) {		
		$iCount_next = $_GET['Next'];
		$iSizePerPage = $itemCounting - $_GET['Next'];		
		if ($iSizePerPage == 1) {
			$iSizePerPage	= 100;
		} 
	}
	if($bNoGetNext == true) {
		$strTableBrowse = '';
	}
	return $strTableBrowse;
}
	
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

		$text .= '<div id="shop_speisekarte_result_'.$config['modul_id'].'">';
		
		if($config['searchText'] == '') {
			$LikeSuche ='';
		}  else {
			if($config['searchText'] != 'Bitte Suchbegriff eingeben') {
				$strWords = explode(" ",$config['searchText']);
				for($i=0; $i < count($strWords); $i++) {
					$LikeSuche .= " (name_de LIKE '%".$strWords[$i]."%'  OR beschreibung LIKE '%".$strWords[$i]."%') AND";		
				}
			}
		}
		#echo $dataShopListe['shop_cat_id'];
		$ids = getSubKategorie($dataShopListe['shop_cat_id'],"",0);
			#<option value="order_datum_asc">Neueste Angebote zuerst</option>
            #<option value="order_datum_desc">Jetzt endende Angebote zuerst</option>
            #<option value="order_preis_asc">Preiswerteste Angebote zuerst</option>
            #<option value="order_preis_desc">Teuerste Angebote zuerst</option>
            #<option value="order_az_asc">Titel (A-Z)</option>
		
		if(isset($_GET['seite'])) {
			if($_GET['seite'] == 1) {
				$strLimitBy = ' LIMIT 0,100';
			} else if ($_GET['seite'] == 2) {
				$strLimitBy = ' LIMIT 100,200';
			} else {
				$strLimitBy = ' LIMIT '.($_GET['seite'] * 100).','.(($_GET['seite'] * 100) + 100);
			}
		} else {
			$strLimitBy = ' LIMIT 0,100';	
		}
		#echo $ids;
		#echo $strLimitBy;
		
			switch($dataShopListe['orderby']) {
				case 'order_datum_asc':
					
					$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." (shop_cat_id='".$dataShopListe['shop_cat_id']."' ".$ids.") AND system_closed_shop='N' AND item_enabled='Y' AND domain_id='".$_SESSION['domain_id']."' ORDER BY updated_at DESC".$strLimitBy;
					break;
				case 'order_datum_desc':
					$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." (shop_cat_id='".$dataShopListe['shop_cat_id']."' ".$ids.") AND system_closed_shop='N' AND item_enabled='Y' AND domain_id='".$_SESSION['domain_id']."' ORDER BY updated_at ASC".$strLimitBy;
					break;
				case 'order_preis_asc':
					$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." (shop_cat_id='".$dataShopListe['shop_cat_id']."' ".$ids.") AND system_closed_shop='N' AND item_enabled='Y' AND domain_id='".$_SESSION['domain_id']."' ORDER BY preis ASC".$strLimitBy;
					break;
				case 'order_preis_desc':
					$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." (shop_cat_id='".$dataShopListe['shop_cat_id']."' ".$ids.") AND system_closed_shop='N' AND item_enabled='Y' AND domain_id='".$_SESSION['domain_id']."' ORDER BY preis DESC".$strLimitBy;
					break;
				default:
					$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." (shop_cat_id='".$dataShopListe['shop_cat_id']."' ".$ids.") AND system_closed_shop='N' AND item_enabled='Y' AND domain_id='".$_SESSION['domain_id']."' ORDER BY updated_at DESC".$strLimitBy;
					break;
			}			
#echo $query;
			$query2 = "SELECT count(*) as anzahl FROM shop_item WHERE ".$LikeSuche." (shop_cat_id='".$dataShopListe['shop_cat_id']."' ".$ids.") AND system_closed_shop='N' AND item_enabled='Y' ORDER BY updated_at DESC";
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
		
		$strPagging .= getPageBrowse($strItemsCount['anzahl']);
		
		$text_table .= $strPagging;
		$text_table .= '<table width="100%">
			<thead>
				<tr class="shop_speisekarte_list_row_dark">
					<td width="10%">Speisekarte</td>
					<td width="70%">Name</td>
					<td width="10%">Preis</td>
					<td width="20%">Bestellen</td>
				</tr>
			</thead>';	
		$bgefunden = false;
		$iCount2=0;

		while($data = mysqli_fetch_assoc($resShopList)) {
			$bgefunden = true;
					#echo "IN".$text;
			$path = getPathUrl($_SESSION['language'],$data['menue_id']);				
			$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
			$iCount++;
			#if($iSpalten == 3) {
			#	$text .= '<div style="clear:both"></div>';
			#	$iSpalten=0;
			#}
			$iSpalten++;
			
			
			if($iCount2 == 0) {
				$css = 'shop_speisekarte_list_row_light';
				$iCount2++;
			} else {
				$css = 'shop_speisekarte_list_row_dark';
				$iCount2 =0;
			}
			
			# Zusatz Attribute laden
			$query ="SELECT * FROM shop_item_additional JOIN shop_item_additional_types ON shop_item_additional.shop_item_additional_types_id =  shop_item_additional_types.shop_item_additional_types_id WHERE shop_item_id='".$data['shop_item_id']."'";
			$resShopAdditional = DBi::$conn->query($query) or die(mysqli_error());
			$strAdditional = '';
			while($strShopAddtional = mysqli_fetch_assoc($resShopAdditional)) {
				if($strShopAddtional['typ'] == 'scharf') {
					if($strShopAddtional['value'] == 'Ja') {
						$strAdditional .= '<img src="/portals/mekong.shopste.comimage/peperoni.gif" title="Scharf" alt="scharf-ja"/>';
					}
				}
				if($strShopAddtional['typ'] == 'knoblauch') {
					if($strShopAddtional['value'] == 'Ja') {
						$strAdditional .= '<img src="/portals/mekong.shopste.comimage/knoblauch.gif" title="Knoblauch" alt="knoblauch-ja"/>';
					}
				}				
			}
 				$text_table .= '
				<tr class="'.$css.'">
					<td>'.$data['item_number'].'</td>
					<td><div class="shop_speisekarte_header"><a style="width:90%;display:block;" href="'.$strLink.'"><h1>'.$data['name_de'].' '.$strAdditional.'</h1></a></div><br/></td>
					<td align="right">';
								#'MwSt_inkl','MwSt_exkl','MwSt_befreit'
			# Domain bestimmmen
			$domain = mysql_real_escape_string($_SERVER['HTTP_HOST']);
			$domain = str_replace("www.", "", $domain);
			$query = "SELECT * from domains WHERE name='$domain'";
			
			$data['preis'] = str_replace(".",",",$data['preis']);
			$domain_res = mysqli_fetch_assoc(DBi::$conn->query($query));
			switch($domain_res['shop_mwst_setting']) {
				case 'MwSt_inkl':
					$text_table .= '<strong>'.$data['preis'].' EUR </strong><br/><font size="1">inkl. '.$data['item_mwst'].'% MwSt.</font>';
					break;
				case 'MwSt_exkl':
					$text_table .= '<strong>'.$data['preis'].' EUR </strong><br/><font size="1">exkl. '.$data['item_mwst'].'% MwSt.</font>';
					break;
				case 'MwSt_befreit':
					$text_table .= '<strong>'.$data['preis'].' EUR </strong><br/><font size="1">Kleinunternehmer : Der Umsatz ist nach dem Umsatzsteuergesetz <br/>&sect; 19 nicht Steuerpflichtig</font>';
					break;
			}
			
			$text_table .='</td>
					<td>';
			#$text_table .= '<div id="shop_list_item">
			$text_table .= '
			<form name="frmItemAdd_'.$data['shop_item_id'].'" id="cart_item_add_'.$data['shop_item_id'].'" action="cart/cart_item_add.php" method="POST" onSubmit="return cart_item_add(\'cart_item_add_'.$data['shop_item_id'].'\');">';		
			
					if($data['menge']  > 0) {
				$text_table .='<input type="submit" style="margin-bottom:25px"; class="button2" value="In den Warenkorb"/>';
			} else {
				$text_table .='Artikel ausverkauft';
			}
			$text_table .='</td>
				</tr>
				<tr class="'.$css.'">
				<td colspan="4">'.$data['beschreibung'].'</td>
				</tr>';	
		

			$text_table .= '</font>
					<input type="hidden" name="shop_item_id" value="'.$data['shop_item_id'].'"/>
					<input type="hidden" name="shop_item_count" value="1"/>
					<input type="hidden" name="shop_item_price" value="'.$data['preis'].'"/>';
			
				
					$text_table .='</form>';
		}
		if($bgefunden == true) {
			$text .= $text_table;
			$text .='</table></div>';
		}
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
function LoadModul_shop_speisekarte($config) {

		$dataShopListe = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_shop_speisekarte WHERE id=".$config['modul_id']));
		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='shop_cat_list'"));
		#echo $config['modul_id'];
		
		$dataShopListe['typ'] = 'shop_speisekarte';
		
		$titel = convertUmlaute($dataShopListe["title_".$_SESSION['language']]);
		if($titel == '') { 
			$titel = getPageTitle_shop_cat($dataShopListe);
		}
		$text = '<div class="content shop_speisekarte_list" id="modul_'.$config['typ'].'_'.$config['modul_id'].'"><br/>';
		#$text .= '<h3 class="shop_category_header1">'.$titel.' Online Bestellen<a name="shop_sort"></a></h3>';
	
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
		$text .='	<input type="text" onclick="javascript:shop_cat_search_intro()" onBlur="shop_cat_search_intro_reset()" id="txtQuickSearch" name="txtQuickSearch" value="'.$strValueSuchen.'"/>
		<input type="hidden" name="modul_id" id="modul_id" value="'.$config['modul_id'].'"/>
		<input type="hidden" name="shop_cat_id" id="shop_cat_id" value="'.$dataShopListe['shop_cat_id'].'"/>
		<script>
		$( document ).ready(function() {
		$(\'txtQuickSearch\').bind("enterKey",function(e){
   //do stuff here
});
$(\'txtQuickSearch\').keyup(function(e){
    if(e.keyCode == 13)
    {
        shop_speisekarte_search(\''.$config['modul_id'].'\',\''.$dataShopListe['shop_cat_id'].'\') 
    }
});
});
		</script>
		<span class="button" style="margin-left:10px" onClick="shop_speisekarte_search(\''.$config['modul_id'].'\',\''.$dataShopListe['shop_cat_id'].'\')" >Suchen</span>';
		$text .= '<select onchange="javascript:shop_speisekarte_sort(\''.$config['modul_id'].'\',\''.$dataShopListe['shop_cat_id'].'\',\'preis\')" class="selectListSort" id="shop_category_sortby" name="optSortBy">
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

	$titel .= " Online bestellen";
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