<?php

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
function getSubKategorie_ids($iParrentCat,$strIDs,$level) {

	$query = "SELECT * FROM shop_category_parent LEFT JOIN shop_category ON shop_category_parent.shop_cat_id=shop_category.shop_cat_id	WHERE shop_category_parent.shop_cat_parent=".$iParrentCat." AND shop_category.domain_id='".$_SESSION['domain_id']."'  ORDER BY name_".$_SESSION['language']." ASC"; 
 
	$resCat = DBi::$conn->query($query) or die(mysqli_error());
	
	while($strCatMenue = mysqli_fetch_assoc($resCat)) {	
		$strIDs .= $strCatMenue['shop_cat_id'].',';
		#echo $strIDs; 
		$strIDs = getSubKategorie_ids($strCatMenue['shop_cat_id'],$strIDs,$level+1);
		#echo $strCatMenue['shop_cat_id'];
	}
	
	return $strIDs;
	
}

function getSubKategorie_portal_ids($iParrentCat,$strIDs,$level) {

	$query = "SELECT * FROM shop_category_parent LEFT JOIN shop_category ON shop_category_parent.shop_cat_id=shop_category.shop_cat_id	WHERE shop_category_parent.shop_cat_parent=".$iParrentCat." AND shop_category.domain_id='".$_SESSION['domain_id']."'  ORDER BY name_".$_SESSION['language']." ASC"; 
 
	$resCat = DBi::$conn->query($query) or die(mysqli_error());
	
	while($strCatMenue = mysqli_fetch_assoc($resCat)) {	
		$strIDs .= $strCatMenue['shop_cat_id'].',';
		#echo $strIDs; 
		$strIDs = getSubKategorie_portal_ids($strCatMenue['shop_cat_id'],$strIDs,$level+1);
		#echo $strCatMenue['shop_cat_id'];
	}

#	$tmpID = substr($strIDs,0,(strlen($strIDs) -1));
	#$strIDs .= $tmpID;
	
	return $strIDs;
	
}
function getSubKategorie_portal($iParrentCat,$strIDs,$level) {

	$query = "SELECT * FROM shop_category_parent LEFT JOIN shop_category ON shop_category_parent.shop_cat_id=shop_category.shop_cat_id	WHERE shop_category_parent.shop_cat_parent=".$iParrentCat." AND shop_category.domain_id='".$_SESSION['domain_id']."'  ORDER BY name_".$_SESSION['language']." ASC"; 
 
	$resCat = DBi::$conn->query($query) or die(mysqli_error());
	
	while($strCatMenue = mysqli_fetch_assoc($resCat)) {	
		$strIDs .= " OR shopste_marktplatz_cat=".$strCatMenue['shop_cat_id'];
		#echo $strIDs; 
		$strIDs = getSubKategorie_portal($strCatMenue['shop_cat_id'],$strIDs,$level+1);
		#echo $strCatMenue['shop_cat_id'];
	}
	
	return $strIDs;
	
}

function getSubKategorie_rss($iParrentCat,$strIDs,$level) {

	$query = "SELECT * FROM modul_rss_category_parent LEFT JOIN modul_rss_category ON modul_rss_category_parent.id_news_category_parent=modul_rss_category.news_cat_id	WHERE modul_rss_category_parent.news_cat_parent=".$iParrentCat." AND modul_rss_category.domain_id='".$_SESSION['domain_id']."'  ORDER BY name_".$_SESSION['language']." ASC"; 
 
	$resCat = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
	
	while($strCatMenue = mysqli_fetch_assoc($resCat)) {	
		$strIDs .= " OR news_cat=".$strCatMenue['news_cat_id']; 
		#echo $strIDs; 
		$strIDs = getSubKategorie_rss($strCatMenue['news_cat_id'],$strIDs,$level+1);
		#echo $strCatMenue['shop_cat_id'];
	}
	
	return $strIDs;
	
}
 
function getPageBrowse($setDataAnzahl,$pageing_modul_typ,$bAjaxClick=false) {
	
 #############################
 # >>  Config 
 # -------------------------------------------------------
 #  $iSizeUp 		= Anzahl im positiven Bereich
 #  $iSizeDown_tmp 	= Anzahl im negativen Bereich
 #  $iSizePerPage 	= Anzahl der Artikel pro Seite 
 #############################
 $iSizeUp = 40;
 $iSizeDown_tmp = 40;
 
 if(isset($_SESSION['CORE_default_module_list_item_count'])) {
	if(is_numeric($_SESSION['CORE_default_module_list_item_count'])) {		 
		$iSizePerPage =	 $_SESSION['CORE_default_module_list_item_count'];
	 } else {
		$iSizePerPage = 50;	 		 
	 }  
 } else {
	$iSizePerPage = 50;	 
 }
 
 $strTableBrowse = '';
 $strNextPage ='';
 $iCount_next_tmp2 = 0;
 
 if(!isset($_GET['seite'])) {
	 $_GET['seite'] = 1;
 }

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
 }
 
  # Zwischenspeichern des Nächsten 
 $iCount_next_tmp = ($iCount_next - $iSizePerPage);
 
 # Anzahl Seiten berechnen
 $iAnzahl = ceil($iCount_next / $iSizePerPage);
 $iAnzahlGes = ceil($itemCounting / $iSizePerPage);
 
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
if(strpos($_SERVER['REQUEST_URI'],'/module/') ===  false) {
 
	if($pageing_modul_typ != '') {
		$strTyp = $pageing_modul_typ;
	} else {
		$strTyp = 'portal_shop_cat_list';
	}
	
	# Initalisieren wenn nicht gesetzt 
	if(!isset($_GET['module_id'])) {
		$_GET['module_id'] = 0;
	}
	
	###################################
	# EXISTERT SEITEN ID 
	###################################

	if(!isset($_SESSION['page_id'])) {
		if(isset($_GET['module_id'])) {		
			$query ="SELECT * FROM module_in_menue WHERE modul_id='".$_GET['module_id']."' AND typ='".$strTyp."'";
		}	
		$strModule = mysqli_fetch_assoc(DBi::$conn->query($query));	
		$path = getPathUrl($_SESSION['language'],$strModule['menue_id']);				
	} else {
		$path = getPathUrl($_SESSION['language'],$_SESSION['page_id']);						
	}
	
	
	if(!isset($_GET['suchtext'])) {
		$_GET['suchtext'] = '';
	}
	
	#echo $_GET['suchtext'].'VV';
	if($_GET['suchtext'] != 'Bitte Suchbegriff eintragen...' && $_GET['suchtext'] != '') {
		$strPathExtend = 'Suche/'.urlencode($_GET['suchtext']).'/';
	} else {
		$strPathExtend = '';
	}
	$strPath = '/'.$path.$strPathExtend.'Seite/';
} else {
	if($pageing_modul_typ != '') {
		$strTyp = $pageing_modul_typ;
	} else {
		$strTyp = 'portal_shop_cat_list';
	}

	# Initalisieren wenn nicht gesetzt 
	if(!isset($_GET['module_id'])) {
		$_GET['module_id'] = 0;
	}
	
	###################################
	# EXISTERT SEITEN ID 
	###################################
	if(!isset($_SESSION['page_id'])) {
		if(isset($_GET['module_id'])) {		
			$query ="SELECT * FROM module_in_menue WHERE modul_id='".$_GET['module_id']."' AND typ='".$strTyp."'";
		}	
		$strModule = mysqli_fetch_assoc(DBi::$conn->query($query));	
		$path = getPathUrl($_SESSION['language'],$strModule['menue_id']);				
	} else {
		$path = getPathUrl($_SESSION['language'],$_SESSION['page_id']);						
	}
	
	if(!isset($_GET['suchtext'])) {
		$_GET['suchtext'] = '';
	}
	
	if($_GET['suchtext'] != 'Bitte Suchbegriff eintragen...' && $_GET['suchtext'] != '') {
		$strPathExtend = 'Suche/'.urlencode($_GET['suchtext']).'/';
	} else {
		$strPathExtend = '';
	}
	$strPath = '/'.$path.$strPathExtend.'Seite/';

}

 for($i = $iStartCounter; $i <= ($iAnzahl + $iSizeUp); $i++) {
	
	#if ($i==1){

	#   $iCount_next_tmp += $iSizePerPage;
	#   continue;	   
	#}
	
	# Nicht über der Artikelgröße 

	if ($iCount_next_tmp <= $itemCounting) {
	
		# Kleiner als angeklickte Seite 	
	    if ($SizeDown > 0 AND $bSetBefore == false) {
		  $iCount_next_tmp2 -= $iCount_next_tmp2- $iSizePerPage;
		  $iMin = $iStartCounter - $SizeDown;
		  
		  # Ausgeben der Seiten 
		  for ($x = $iMin; $x <= $iStartCounter-1; $x++) {
		  
		    if ($iMin > 0) {
				#
				if($bAjaxClick == true) {
					$strNextPage .= " <span class=\"spanlink paging\" id=\"".$iMin."\">[ ".($iMin)." ]</span> ";
				} else {
					$strNextPage .= " <a  class=\"paging\" href=\"".$strPath.''.$iMin."/#inhalt\">[ ".($iMin)." ]</a> ";
				}
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
				  $strNextPage .= " [".(1)."] ";			
				} else {
				  $strNextPage .= " [".($i)."] ";			
				}
				
				$iCount_next_tmp += $iSizePerPage;
		} 
		# Höhere Seiten 
		else {
 
			if($bAjaxClick == true) {			
				$strNextPage .= "<span class=\"spanlink paging\" id=\"".$i."\"> [ ".($i)." ] </span>";						
			} else {				
				$strNextPage .= " <a class=\"paging\" id=\"".$i."\" href=\"".$strPath."".$i."/#inhalt\">[ ".($i)." ]</a> ";				
			}
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
  

  $strTableBrowse = "";
				
 $strTableBrowse .= "Seite ";
	  if(isset($iAnzahl_tmp)) {
		  $iAnzahl_tmp = 0;
	  }
	  if ($iAnzahl_tmp == 0) {
			#$strTableBrowse .=  '1';	
			$iAnzahl_tmp = 1;
		} else {
			#$strTableBrowse .=  $iAnzahl_tmp;
		}
		if($iCount_next > $itemCounting) {
			$anzahl = $itemCounting;
		} else {
			$anzahl = $iAnzahl_tmp * $iSizePerPage;
		} 
		if(is_numeric($_GET['seite'])) {
			$core_seite = $_GET['seite'];
		} else {
			$core_seite = 1;
		}
		if($core_seite == 1) {
			$core_seite_von_anzahl = "1";
		} else {
			$core_seite_von_anzahl = (($core_seite - 1) * $iSizePerPage);			
		}
		
		$strTableBrowse .= '<strong  class="page_counter_text_strong">'.$core_seite.'</strong> von <strong class="page_counter_text_strong">'.number_format($iAnzahlGes, 0, ',', '.')."</strong> Seiten (Bei ".PLATTFORM_ITEM_TYPE." <strong>".$core_seite_von_anzahl."</strong> - <strong>".($iSizePerPage * $core_seite)."</strong>)<br/>";
			if($itemCounting == '0') {
				$strTableBrowse .= '<strong  class="page_counter_text_strong">'.number_format($setDataAnzahl, 0, ',', '.')."x</strong> ".PLATTFORM_ITEM_TYPES." in dieser Kategorie";
			} else {
				$strTableBrowse .= '<strong  class="page_counter_text_strong">'.number_format($itemCounting, 0, ',', '.')."x</strong> ".PLATTFORM_ITEM_TYPES." in dieser Kategorie";						
			}
			
			
	  
	  
	  $strTableBrowse .="<br/><br/> ";
 
		
		if ($_GET['seite'] == 1) {
			#$strTableBrowse .= "Zurück";			
		} else {
			
			#DEFAULT Galerie false
			if(!isset($bGallerie)) {
				$bGallerie = false;
			}
			
			if($bGallerie == true) {
 				$strPara = '&iParrent='.$_GET['iParrent'];
			}
			$seite1 = ($_GET['seite'] - 1);
			if($seite1 <= 0) {
				$seite1 = 1;
			}
			if($bAjaxClick == true) {
				$strTableBrowse .= "<span class=\"spanlink paging\" id=\"".$seite1."\"><img src=\"/images/ic_arrow_back_black_18dp.png\"> Auf Seite ".($core_seite - 1)." zurück</span>";
			} else {
				$strTableBrowse .= "<a class=\"paging\" id=\"".$seite1."\" href=\"".$strPath."".$seite1."/#inhalt\"><img src=\"/images/ic_arrow_back_black_18dp.png\"> Auf Seite ".($core_seite - 1)." zurück</a> | ";
			}
		}
						

		# 1. Seite 
		if ($iCount_next == "0") {
			$iCount_next = $iSizePerPage;			
		}
		
		if($iCount_next > $itemCounting) {
			$iCount_next = $itemCounting;					
			$strTableBrowse .= "nächste Seite";
			#$bNoGetNext = true;
		} else {
 
			if($bAjaxClick == true) {
				# add: | <span class=\"spanlink paging\" id=\"".($iAnzahlGes)."\">letzte Seite</span>
				$strTableBrowse .= "<span class=\"spanlink paging\" id=\"".($_GET['seite'] + 1)."\"><img src=\"/images/ic_arrow_forward_black_18dp.png\"> Nächste ".($core_seite + 1)." Seite</span> ";
			} else {
				$strTableBrowse .= "<a class=\"paging\" id=\"".($_GET['seite'] + 1)."\" href=\"".$strPath."".($_GET['seite'] + 1)."/#inhalt\"><img src=\"/images/ic_arrow_forward_black_18dp.png\"> Nächste ".($core_seite + 1)." Seite</a> | <a class=\"paging\" id=\"".($_GET['seite'])."\" href=\"".$strPath."".($iAnzahlGes)."/#inhalt\"><img src=\"/images/ic_last_page_black_18dp.png\"> Letzte Seite</a><br/>";
				
			}
		}		

		$strTableBrowse .= '<br/>'.$strNextPage."<a name=\"start\"></a>";
	
	# + 20 Kompensieren
	$iCount_next -= $iSizePerPage;
	
	
	if ($itemCounting < $iSizePerPage) {   
	   $iCount_next	= 0; 
	   
	   #$strTableBrowse = '';
    }
	if(!isset($_GET['Next'])) {
		$_GET['Next'] = 0;
	}
	$iNext = $_GET['Next'] + $iSizePerPage;
	if($itemCounting <= $iNext) {		
		$iCount_next = $_GET['Next'];
		$iSizePerPage = $itemCounting - $_GET['Next'];		
		if ($iSizePerPage == 1) {
			$iSizePerPage	= 50;
		} 
	}
	
	if($iAnzahlGes == 0 || $iAnzahlGes == 1) {
		return "";
	} else {
		return $strTableBrowse;
	}
}
?>