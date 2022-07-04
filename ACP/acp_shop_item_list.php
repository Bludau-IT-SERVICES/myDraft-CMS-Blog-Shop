<?php 
	@session_start();
		// Datenbankverbindung
	require_once('../include/inc_config-data.php');
	require_once('../include/inc_basic-functions.php');
	$strButtonName = 'Shop Produkte anlegen';
	$strDomain_ary = getDomainInfo();
	$_POST = mysql_real_escape_array($_POST);
	$_GET = mysql_real_escape_array($_GET);
	
	if(isset($_GET['modus'])) {
		$_POST = $_GET;
	}
	#$_COOKIE = mysql_real_escape_array($_COOKIE);
	
	// Login überprüfen
	$chkCookie = admin_cookie_check();

	if($_SESSION['login'] == 1) {		
		$_SESSION['login'] = 1;
	} else {
		exit(0);
	}
	
	if($_GET['init'] == 'Y') {
?>

<?php
	}
	#echo $chkCookie.$_SESSION['domain_id'];
	
	$bModusSelected = false;
	#echo $_POST['modus'].'DDD'; 
	switch($_POST['modus']) {
		case 'byKategorie':
			$strWords = explode(" ",$_POST['suche']);
			for($i=0; $i < count($strWords); $i++) {
				if($i == count($strWords) -1) {
					$LikeSuche .= "(name_de LIKE '%".$strWords[$i]."%' OR item_number LIKE '%".$strWords[$i]."%')";		
 				} else {
					$LikeSuche .= "(name_de LIKE '%".$strWords[$i]."%'  OR item_number LIKE '%".$strWords[$i]."%') AND";		
				}				
			}			
			if($_POST['catid'] != '') {
				$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." AND shop_cat_id='".$_POST['catid']."' AND domain_id='".$_SESSION['domain_id']."' ORDER BY created_at DESC";
				$strOptMenueMarket = shop_category(0,0,'',0,0,'select',$_POST['catid']);
			} else {
				#$query = "SELECT * FROM shop_item WHERE parrent_shop_item_id=0  AND domain_id='".$_SESSION['domain_id']."'  ORDER BY created_at DESC";
				$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." AND domain_id='".$_SESSION['domain_id']."'  ORDER BY created_at DESC";
				$strOptMenueMarket = shop_category(0,0,'',0,0,'select',0);
			}		
			$bModusSelected	= true;
			$strName = 'Nach Kategorie';
			break;
		case 'byImportiert':
			$strWords = explode(" ",$_POST['suche']);
			for($i=0; $i < count($strWords); $i++) {
				if($i == count($strWords) -1) {
					$LikeSuche .= "(name_de LIKE '%".$strWords[$i]."%' OR item_number LIKE '%".$strWords[$i]."%')";		
 				} else {
					$LikeSuche .= "(name_de LIKE '%".$strWords[$i]."%'  OR item_number LIKE '%".$strWords[$i]."%') AND";		
				}				
			}				
			$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." AND status_de='API-importiert'  AND domain_id='".$_SESSION['domain_id']."'  ORDER BY created_at DESC";
			$bModusSelected	= true;
			$strName = 'Importiert';
			break;
		case 'bySKU':
			
			$strWords = explode(" ",$_POST['suche']);			
			for($i=0; $i < count($strWords); $i++) {
				if($i == count($strWords) -1) {
					$LikeSuche .= "(name_de LIKE '%".$strWords[$i]."%' OR item_number LIKE '%".$strWords[$i]."%')";		
 				} else {
					$LikeSuche .= "(name_de LIKE '%".$strWords[$i]."%'  OR item_number LIKE '%".$strWords[$i]."%') AND";		
				}				
			}				
			$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." AND item_enabled='Y' AND status_de='verkaufsbereit'  AND domain_id='".$_SESSION['domain_id']."' ORDER BY item_number ASC";
			$bModusSelected	= true;
			$strName = 'nach Artikelnummer 0-9';			
			break;
		case 'byABC':
			
			echo $_POST['suche'].'DDD';
			$strWords = explode(" ",$_POST['suche']);			
			for($i=0; $i < count($strWords); $i++) {
				if($i == count($strWords) -1) {
					$LikeSuche .= "(name_de LIKE '%".$strWords[$i]."%' OR item_number LIKE '%".$strWords[$i]."%')";		
 				} else {
					$LikeSuche .= "(name_de LIKE '%".$strWords[$i]."%'  OR item_number LIKE '%".$strWords[$i]."%') AND";		
				}				
			}				
			$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." AND item_enabled='Y' AND status_de='verkaufsbereit'  AND domain_id='".$_SESSION['domain_id']."' ORDER BY name_de DESC";
			$bModusSelected	= true;
			$strName = 'Produktverwaltung A - Z';			
			break;
		case 'byNotActive':
			$strWords = explode(" ",$_POST['suche']);
			for($i=0; $i < count($strWords); $i++) {
				if($i == count($strWords) -1) {
					$LikeSuche .= "(name_de LIKE '%".$strWords[$i]."%' OR item_number LIKE '%".$strWords[$i]."%')";		
 				} else {
					$LikeSuche .= "(name_de LIKE '%".$strWords[$i]."%'  OR item_number LIKE '%".$strWords[$i]."%') AND";		
				}				
			}				
			$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." AND item_enabled='N' AND status_de='verkaufsbereit'  AND domain_id='".$_SESSION['domain_id']."' ORDER BY created_at DESC";
			$bModusSelected	= true;
			$strName = 'Nicht aktiv';
			break;
		case 'byNotMarket':
			$strWords = explode(" ",$_POST['suche']);
			for($i=0; $i < count($strWords); $i++) {
				if($i == count($strWords) -1) {
					$LikeSuche .= "(name_de LIKE '%".$strWords[$i]."%' OR item_number LIKE '%".$strWords[$i]."%')";		
 				} else {
					$LikeSuche .= "(name_de LIKE '%".$strWords[$i]."%'  OR item_number LIKE '%".$strWords[$i]."%') AND";		
				}				
			}		
			$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." AND shopste_marktplatz_cat=0  AND domain_id='".$_SESSION['domain_id']."' ORDER BY created_at DESC";
			$strName = 'Nicht bei Shopste';
			$bModusSelected	= true;
			break;
		case 'byLastChange':
			$strWords = explode(" ",$_POST['suche']);
			for($i=0; $i < count($strWords); $i++) {
				if($i == count($strWords) -1) {
					$LikeSuche .= "(name_de LIKE '%".$strWords[$i]."%' OR item_number LIKE '%".$strWords[$i]."%')";		
 				} else {
					$LikeSuche .= "(name_de LIKE '%".$strWords[$i]."%'  OR item_number LIKE '%".$strWords[$i]."%') AND";		
				}				
			}
			$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." AND domain_id='".$_SESSION['domain_id']."' ORDER BY updated_at DESC";
			$strName = 'Zuletzt geändert';
			$bModusSelected	= true;
			break;
		case 'byCreated': 
			$strWords = explode(" ",$_POST['suche']);
			for($i=0; $i < count($strWords); $i++) {
				if($i == count($strWords) -1) {
					$LikeSuche .= "(name_de LIKE '%".$strWords[$i]."%' OR item_number LIKE '%".$strWords[$i]."%')";		
 				} else {
					$LikeSuche .= "(name_de LIKE '%".$strWords[$i]."%'  OR item_number LIKE '%".$strWords[$i]."%') AND";		
				}				
			}		
			$query = "SELECT * FROM shop_item WHERE ".$LikeSuche." AND  domain_id='".$_SESSION['domain_id']."' ORDER BY created_at DESC";
			$strName = 'Erstellt am';
			$bModusSelected	= true;
			break;		
		case 'byActiveMarket':
			$strWords = explode(" ",$_POST['suche']);
			for($i=0; $i < count($strWords); $i++) {
				if($i == count($strWords) -1) {
					$LikeSuche .= "(name_de LIKE '%".$strWords[$i]."%' OR item_number LIKE '%".$strWords[$i]."%')";		
 				} else {
					$LikeSuche .= "(name_de LIKE '%".$strWords[$i]."%'  OR item_number LIKE '%".$strWords[$i]."%') AND";		
				}				
			}		
			$query = "SELECT * FROM shop_item WHERE  ".$LikeSuche." AND shopste_marktplatz_cat != 0  AND domain_id='".$_SESSION['domain_id']."'  ORDER BY updated_at DESC";
			$bModusSelected	= true;
			$strName = 'Auf Shopste Marktplatz';
			break;
		case 'byImportDelete':

			// Alle Importierten Artikel abrufen 
			$query = "SELECT * FROM shop_item WHERE status_de='API-importiert'  AND domain_id='".$_SESSION['domain_id']."' ";
			$resShopItem = DBi::$conn->query($query) or die(mysqli_error());
			while($strShopItemData = mysqli_fetch_assoc($resShopItem)) {
				
				echo "<h2>".$strShopItemData['name_de']."</h2>";
				
				##############################################
				# >> Shop Seite löschen
				##############################################
				if($strShopItemData['menue_id'] != 0) {
					$query = "SELECT * FROM module_in_menue WHERE menue_id='".$strShopItemData['menue_id']."'";
					$resModule = DBi::$conn->query($query) or die(mysqli_error());
					while($strModule = mysqli_fetch_assoc($resModule)) {
						# MODULE AUS EIGENER MODULTABELLE LÖSCHEN
						$query = "DELETE FROM modul_".$strModule['typ']." WHERE id='".$strModule['modul_id']."'";
						DBi::$conn->query($query) or die(mysqli_error());			
					}
					
					$query = "DELETE FROM module_in_menue WHERE menue_id='".$strShopItemData['menue_id']."'";
					#echo $query.'<br/>';
					DBi::$conn->query($query) or die(mysqli_error());		

					$query = "DELETE FROM menue_parent WHERE menue_id='".$strShopItemData['menue_id']."'";
					DBi::$conn->query($query) or die(mysqli_error());		
					#echo $query.'<br/>';
					$query = "DELETE FROM menue WHERE id='".$strShopItemData['menue_id']."'";
					DBi::$conn->query($query) or die(mysqli_error());			
					#echo $query.'<br/>';
					echo ">> Shop Seite + Module erfolgreich gel&ouml;scht!<br/>";
				} else {
					echo ">> Shop Seite nicht vorhanden!<br/>";
				}
				
				##############################################
				# >> Marktplatz Eintrag löschen
				##############################################
				if($strShopItemData['shopste_marktplatz_menue_id'] != 0) {
					$query = "SELECT * FROM module_in_menue WHERE menue_id='".$strShopItemData['shopste_marktplatz_menue_id']."'";
					$resModule = DBi::$conn->query($query) or die(mysqli_error());
					while($strModule = mysqli_fetch_assoc($resModule)) {
						# MODULE AUS EIGENER MODULTABELLE LÖSCHEN
						$query = "DELETE FROM modul_".$strModule['typ']." WHERE id='".$strModule['modul_id']."'";
						DBi::$conn->query($query) or die(mysqli_error());			
					}
					
					$query = "DELETE FROM module_in_menue WHERE menue_id='".$strShopItemData['shopste_marktplatz_menue_id']."'";
					#echo $query.'<br/>';
					DBi::$conn->query($query) or die(mysqli_error());		

					$query = "DELETE FROM menue_parent WHERE menue_id='".$strShopItemData['shopste_marktplatz_menue_id']."'";
					DBi::$conn->query($query) or die(mysqli_error());		
					#echo $query.'<br/>';
					$query = "DELETE FROM menue WHERE id='".$strShopItemData['shopste_marktplatz_menue_id']."'";
					DBi::$conn->query($query) or die(mysqli_error());			
					#echo $query.'<br/>';
					echo ">> Marktplatz Artikelseite + Module erfolgreich gel&ouml;scht!<br/>";
				} else {
					echo ">> Marktplatz Artikelseite nicht vorhanden!<br/>";
				}				
				
				######################################################
				# >> Bilder löschen
				######################################################
				// Das Orginalbild holen und alle Bilder die als Thumbnail erzeugt wurden werden gelöscht
				$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$strShopItemData['shop_item_id']."' AND modus='orginal-picture'";
				$resPicture = DBi::$conn->query($query) or die(mysqli_error());
				$path = realpath($_SERVER["DOCUMENT_ROOT"]);
				while($strPicture = mysqli_fetch_assoc($resPicture)) {
					// Existiert Orginalbild
					if (file_exists($path.$strPicture['picture_url'])) {
						// Orginal
						echo "L&ouml;sche Orginalbild: '".$strPicture['picture_url']."'<br/>";
						unlink($path.$strPicture['picture_url']);
						// Kategoriebild
						$strBild = str_replace("/produkte/orginal/","/produkte/kategorie/",$strPicture['picture_url']);
						if (file_exists($path.$strBild)) {	
							echo "L&ouml;sche Kategoriebild: '".$strPicture['picture_url']."'<br/>";
							unlink($path.$strBild);	
						} else {
							echo "Fehler beim überprüfen ob Bild '".$strPicture['picture_url']."' existiert.<br/><h3>Abbruch!</h3>";
							exit(0);
						}
						// Produktdetail
						$strBild = str_replace("/produkte/orginal/","/produkte/detail/",$strPicture['picture_url']);
						if (file_exists($path.$strBild)) {
							echo "L&ouml;sche Detailansicht: '".$strPicture['picture_url']."'<br/>";
							unlink($path.$strBild);
						} else {
							echo "Fehler beim überprüfen ob Bild '".$strPicture['picture_url']."' existiert.<br/><h3>Abbruch!</h3>";
							exit(0);
						}
						
					} else {
						echo "Fehler beim überprüfen ob Bild '".$strPicture['picture_url']."' existiert.<br/><h3>Abbruch!</h3>";
						exit(0);
					}					
				}
				echo "L&oum;sche Artikel aus Artikeldatenbank<br/><br/>";
				$query = "DELETE FROM shop_item WHERE shop_item_id='".$strShopItemData['shop_item_id']."'";
				DBi::$conn->query($query) or die(mysqli_error());
			}
			$bModusSelected	= true;
			break;
		case 'byAlleArtikelDeleteAsk':
			echo "<h2>Sind Sie sich sicher alle Artikel und Ihre Seiten und Module zu löschen?</h2>";
			echo '<span class="spanlink" onClick="acp_shop_item_delete_imported(\'byAlleArtikelDelete\')">Ja, möchte löschen</span> | ';
			echo '<span class="spanlink" onClick="acp_shop_item_delete_imported(\'\')">Nein, möchte wieder zurück</span>';
			break;
		case 'byAlleArtikelDelete':

			// Alle Importierten Artikel abrufen 
			$query = "SELECT * FROM shop_item WHERE domain_id='".$_SESSION['domain_id']."' ";
			$resShopItem = DBi::$conn->query($query) or die(mysqli_error());
			while($strShopItemData = mysqli_fetch_assoc($resShopItem)) {
				
				echo "<h2>".$strShopItemData['name_de']."</h2>";
				
				##############################################
				# >> Shop Seite löschen
				##############################################
				if($strShopItemData['menue_id'] != 0) {
					$query = "SELECT * FROM module_in_menue WHERE menue_id='".$strShopItemData['menue_id']."'";
					$resModule = DBi::$conn->query($query) or die(mysqli_error());
					while($strModule = mysqli_fetch_assoc($resModule)) {
						# MODULE AUS EIGENER MODULTABELLE LÖSCHEN
						$query = "DELETE FROM modul_".$strModule['typ']." WHERE id='".$strModule['modul_id']."'";
						DBi::$conn->query($query) or die(mysqli_error());			
					}
					
					$query = "DELETE FROM module_in_menue WHERE menue_id='".$strShopItemData['menue_id']."'";
					#echo $query.'<br/>';
					DBi::$conn->query($query) or die(mysqli_error());		

					$query = "DELETE FROM menue_parent WHERE menue_id='".$strShopItemData['menue_id']."'";
					DBi::$conn->query($query) or die(mysqli_error());		
					#echo $query.'<br/>';
					$query = "DELETE FROM menue WHERE id='".$strShopItemData['menue_id']."'";
					DBi::$conn->query($query) or die(mysqli_error());			
					#echo $query.'<br/>';
					echo ">> Shop Seite + Module erfolgreich gel&ouml;scht!<br/>";
				} else {
					echo ">> Shop Seite nicht vorhanden!<br/>";
				}
				
				##############################################
				# >> Marktplatz Eintrag löschen
				##############################################
				if($strShopItemData['shopste_marktplatz_menue_id'] != 0) {
					$query = "SELECT * FROM module_in_menue WHERE menue_id='".$strShopItemData['shopste_marktplatz_menue_id']."'";
					$resModule = DBi::$conn->query($query) or die(mysqli_error());
					while($strModule = mysqli_fetch_assoc($resModule)) {
						# MODULE AUS EIGENER MODULTABELLE LÖSCHEN
						$query = "DELETE FROM modul_".$strModule['typ']." WHERE id='".$strModule['modul_id']."'";
						DBi::$conn->query($query) or die(mysqli_error());			
					}
					
					$query = "DELETE FROM module_in_menue WHERE menue_id='".$strShopItemData['shopste_marktplatz_menue_id']."'";
					#echo $query.'<br/>';
					DBi::$conn->query($query) or die(mysqli_error());		

					$query = "DELETE FROM menue_parent WHERE menue_id='".$strShopItemData['shopste_marktplatz_menue_id']."'";
					DBi::$conn->query($query) or die(mysqli_error());		
					#echo $query.'<br/>';
					$query = "DELETE FROM menue WHERE id='".$strShopItemData['shopste_marktplatz_menue_id']."'";
					DBi::$conn->query($query) or die(mysqli_error());			
					#echo $query.'<br/>';
					echo ">> Marktplatz Artikelseite + Module erfolgreich gel&ouml;scht!<br/>";
				} else {
					echo ">> Marktplatz Artikelseite nicht vorhanden!<br/>";
				}				
				
				######################################################
				# >> Bilder löschen
				######################################################
				// Das Orginalbild holen und alle Bilder die als Thumbnail erzeugt wurden werden gelöscht
				$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$strShopItemData['shop_item_id']."' AND modus='orginal-picture'";
				$resPicture = DBi::$conn->query($query) or die(mysqli_error());
				$path = realpath($_SERVER["DOCUMENT_ROOT"]);
				while($strPicture = mysqli_fetch_assoc($resPicture)) {
					// Existiert Orginalbild
					if (file_exists($path.$strPicture['picture_url'])) {
						// Orginal
						echo "L&ouml;sche Orginalbild: '".$strPicture['picture_url']."'<br/>";
						unlink($path.$strPicture['picture_url']);
						// Kategoriebild
						$strBild = str_replace("/produkte/orginal/","/produkte/kategorie/",$strPicture['picture_url']);
						if (file_exists($path.$strBild)) {	
							echo "L&ouml;sche Kategoriebild: '".$strPicture['picture_url']."'<br/>";
							unlink($path.$strBild);	
						} else {
							echo "Fehler beim überprüfen ob Bild '".$strPicture['picture_url']."' existiert.<br/><h3>Abbruch!</h3>";
							#exit(0);
						}
						// Produktdetail
						$strBild = str_replace("/produkte/orginal/","/produkte/detail/",$strPicture['picture_url']);
						if (file_exists($path.$strBild)) {
							echo "L&ouml;sche Detailansicht: '".$strPicture['picture_url']."'<br/>";
							unlink($path.$strBild);
						} else {
							echo "Fehler beim überprüfen ob Bild '".$strPicture['picture_url']."' existiert.<br/><h3>Abbruch!</h3>";
							#exit(0);
						}
						
					} else {
						echo "Fehler beim überprüfen ob Bild '".$strPicture['picture_url']."' existiert.<br/><h3>Abbruch!</h3>";
						#exit(0);
					}					
				}
				echo "L&oum;sche Artikel aus Artikeldatenbank<br/><br/>";
				$query = "DELETE FROM shop_item WHERE shop_item_id='".$strShopItemData['shop_item_id']."'";
				DBi::$conn->query($query) or die(mysqli_error());
				
				# Kindprodukte Produkte löschen
				$query = "DELETE FROM shop_item WHERE parrent_shop_item_id='".$strShopItemData['shop_item_id']."'";
				DBi::$conn->query($query) or die(mysqli_error());
				
				# Eigenschaft  für Produkt löschen
				$query = "DELETE FROM shop_item_eigenschaft WHERE id_shop_item='".$strShopItemData['shop_item_id']."'";
				DBi::$conn->query($query) or die(mysqli_error());
				
				# Eigenschaftwert für Produkt löschen
				$query = "DELETE FROM shop_item_eigenschaftwert WHERE id_item_shop='".$strShopItemData['shop_item_id']."'";
				DBi::$conn->query($query) or die(mysqli_error());				
				
			}
			$bModusSelected	= true;
			break;	
		case 'search_item':
			#echo "<h2>Suchergebnis<h2>";
			$strWords = explode(" ",$_POST['suche']);
			for($i=0; $i < count($strWords); $i++) {
				if($i == count($strWords) -1) {
					$LikeSuche .= "(name_de LIKE '%".$strWords[$i]."%' OR item_number LIKE '%".$strWords[$i]."%')";		
 				} else {
					$LikeSuche .= "(name_de LIKE '%".$strWords[$i]."%'  OR item_number LIKE '%".$strWords[$i]."%') AND";		
				}				
			}
			$query ="SELECT * FROM shop_item WHERE ".$LikeSuche." AND domain_id='".$_SESSION['domain_id']."'";
			#echo $query;
			$strName = 'Suche';
			$bModusSelected	= true;
			break;
		case 'byDeleteAllProducts':
			echo '<br/><br/><span class="spanlink" onClick="acp_shop_item_delete_imported(\'byAlleArtikelDeleteAsk\')">Alle Produkte unwiederruflich löschen</span><br/><br/>';
			
			break;
		default:
			$strWords = explode(" ",$_POST['suche']);
			for($i=0; $i < count($strWords); $i++) {
				if($i == count($strWords) -1) {
					$LikeSuche .= "(name_de LIKE '%".$strWords[$i]."%' OR item_number LIKE '%".$strWords[$i]."%')";		
 				} else {
					$LikeSuche .= "(name_de LIKE '%".$strWords[$i]."%'  OR item_number LIKE '%".$strWords[$i]."%') AND";		
				}				
			}		
			$query = "SELECT * FROM shop_item WHERE  ".$LikeSuche." AND domain_id='".$_SESSION['domain_id']."'  ORDER BY item_number ASC";		
			#echo $query;
		#	$bModusSelected	= true;
			$strName = 'XXX';
			$bModusSelected	= true;
			break;
	}

function acp_getPageBrowse($setDataAnzahl) {
	
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
$strPath = $_SERVER['REQUEST_URI'];
#$strPath = $strPath.'Seite/'; #.urlencode($_GET['seite']);
#$strPath = str_replace('//','/',$strPath);
#echo $iAnzahl.'-#'.$iStartCounter.'#'.($iAnzahl + $iSizeUp).'#'.$itemCounting;

 for($i = $iStartCounter; $i <= ($iAnzahl + $iSizeUp); $i++) {
	#echo "IN";
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
 
				$strNextPage .= "<span class=\"spanlink\" onClick=acp_item_list_pageing('".$iMin."')> [".($iMin)."] </span>";
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
 
			$strNextPage .= "<span class=\"spanlink\" onClick=acp_item_list_pageing('".$i."')> [".($i)."] </span>";			
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
  
#echo $strNextPage;
				
 $strTableBrowse = "<table width=\"100%\" bgcolor=\"#F2F2F2\"  border=\"0\" cellspacing=\"3\" align=\"center\" cellpadding=\"3\" style=\"border-width:2px; border-color:#F2F2F2; border-style:solid;table-layout: auto;\">
   <tr>
      <td colspan=\"3\">"."In dieser Kategorie";
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
		$strTableBrowse .= " Artikel in Kategorie ";
			$strTableBrowse .= $itemCounting.'x';						
			
	  
	  
	  $strTableBrowse .="</td>  
   </tr>
	<tr>
		<td id=\"highlight_tr_parent\">";
 		
		$strTableBrowse .= "</td>
		<td align=\"center\" bgcolor=\"#F2F2F2\">".$strNextPage."</td>		
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
 
			$strTableBrowse .= "<span class=\"spanlink\" onClick=acp_item_list_pageing('".($_GET['seite'] + 1)."')>Vorw&auml;rts blättern</span>";
		}		

		$strTableBrowse .= "</td>		
	</tr>
	</table><br/><a name=\"start\"></a>";
	
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
	
	return $strTableBrowse;
}	
?>
  <script>
  $(function() {
    $( "#tabs" ).tabs({
      beforeLoad: function( event, ui ) {
        ui.jqXHR.fail(function() {
          ui.panel.html(
            "Tab konnte nicht geladen werden. " +
            "Bitte aktuallisieren Sie die Webseite." );
        });
      }
    });
  });
  </script>
  



  <?php 
  function getSektionHTML($query,$strOptMenueMarket) {
 
	if($strOptMenueMarket != '') {
		echo '<br/><select onChange="javascript:acp_shop_item_list(\'byKategorie\')" id="marktplatz_shop_category"  name="marktplatz_shop_category" size="20">
					'.$strOptMenueMarket.'					
				</select>';
	}
 
	# Config Blättern
	######################
	$bTreffer = false;
	$iSizePerPage = 100;
	
	#############################################################
	# >> GLOBAL zählen der Produkte 
	#############################################################
	$query_anzahl = str_replace("* FROM","count(*) as anzahl FROM",$query);
	#echo $query_anzahl;
	$resAnzahl = DBi::$conn->query($query_anzahl) or die(mysqli_error());
	$strA = mysqli_fetch_assoc($resAnzahl);
	$iAnzahl = $strA['anzahl'];
		
	if(isset($_POST['seite'])) {
		$_GET['seite'] = $_POST['seite'];
	}
	
	#echo $iAnzahl.$query;
	if($iAnzahl != 0) {
		echo acp_getPageBrowse($iAnzahl);
	}
	
	if(isset($_GET['seite'])) {
		if($_GET['seite'] == 1) {
			$strLimitBy = ' LIMIT 0,100';
		} else {
			$strLimitBy = ' LIMIT '.(($_GET['seite'] -1) * 100).','.(($_GET['seite'] * 100));
		}
	} else {
		$strLimitBy = ' LIMIT 0,100';	
	}
	
	# Begrenzung dranhängen
	$query .= ' '.$strLimitBy;
	
	
	echo '<script>';
	echo '$(document).ready(function(){';
	echo 'if ( $("#acp_shop_itemlist_modus").length) {
			//alert("Das DIV mit der ID mydivid existiert");
			$(\'#acp_shop_itemlist_modus\').val(\''.$_POST['modus'].'\');
	} else {
		$(document.body).append(\'<input type="hidden" name="modus" id="acp_shop_itemlist_modus" value="'.$_POST['modus'].'"/>\');
	}';
	echo '});';
	echo '</script>';
	
	#echo $query;
	if(!empty($query) && $iAnzahl != 0) {
		$resQuery = DBi::$conn->query($query) or die(mysqli_error());
		
		echo '<input style="float: left;
margin-top: 15px;" onkeypress="onEnter_acp_produkt(event,\''.$_POST['modus'].'\')" type="text" name="txtSucheArtikel" placeholder="Suchbegriff eingeben..." id="txtSucheArtikel" value="'.$_POST['suche'].'"/>
	<button style="margin-bottom: 23px;" class="button" onClick="acp_shop_item_search(\'search_item\')">Suchen</button>
	
	
	<table width="100%" class="produkt_list_tbl">
		<thead>
			<tr style="border-bottom: 1px solid;">
				<td width="60px"><strong>ID</strong></td>
				<td width="60px"><strong>Art.Nr.</strong></td>
				<td width="100px"><strong>Bild</strong></td>
				<td width="40%"><strong>Produktbezeichnung</strong></td>
				<td width="80px"><strong>Lager</strong></td>
				<td width="87px"><strong>Preis</strong></td>
				<td width="335px"><strong>Aktionen</strong></td>
			</tr>
		</thead>';
		$i = 0;
		
		while($data = mysqli_fetch_assoc($resQuery)) {
		
			if($i == 0) {
				$cssClass = 'text_list_line_white';
				$i++;
			} else {
				$cssClass = 'text_list_line_gray';
				$i=0;
			}
			
			if($data['menue_id'] != 0) {
				$strLink = getPathUrl($_SESSION['language'],$data['menue_id']);
			} else {
				$strLink = '';
			}
			if($data['shopste_marktplatz_menue_id'] != '0') {
				$strLinkMarket = getPathUrl($_SESSION['language'],$data['shopste_marktplatz_menue_id']);
			} else {
				$strLinkMarket ='';
			}
			$query_pic = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$data['shop_item_id']."' AND picture_nr=1";
			$resPic = DBi::$conn->query($query_pic) or die(mysqli_error());
			$strPic = mysqli_fetch_assoc($resPic);
			
			echo '<tr class="'.$cssClass.'">';
				echo '<td>';
				echo $data['shop_item_id'];
				echo '</td>';
				echo '<td>';
				echo $data['item_number'];
				echo '</td>';				
				echo '<td>';
				if(file_exists($strPic['picture_url'])) {
					echo '<img src="'.$strPic['picture_url'].'" class="acp_picture_list"/>';					
				} else {
					echo "&nbsp;";
				}
				
				echo '</td>';				
				echo '<td>';
				
				# Produktbezeichnung
				$domain_res = getDomainInfo();
				if($strLink != ''&& $domain_res['has_product_content_page'] == 'Y') {
					echo '<a href="'.$strLink.'" target="_blank">';
				}
				echo $data['name_de'];
				if($strLink != ''  && $domain_res['has_product_content_page'] == 'Y') {
					echo '</a>';
				}
				
				echo '<br/><span style="font-size:0.5em">'.getDateDE($data['created_at']).'</span>';
				echo '</td>';
				echo '<td>';
				echo $data['menge'];
				echo '</td>';					
				echo '<td>';
				echo str_replace(".",",",$data['preis']).' EUR';
				echo '</td>';				
				echo '<td width="30%">';
				
				if($data['item_enabled'] == 'Y') {
					$strEnabled = 'Inaktiv setzen';
				} else {
					$strEnabled = 'Aktiv setzen';
				}
				
				echo '<span id="acp_shop_list_edit" class="spanlink" onClick="getShopItemUpdate(\''.$data['shop_item_id'].'\')">Editieren</span> | <span id="acp_shop_list_edit" class="spanlink" onClick="set_shop_item_delete(\''.$data['shop_item_id'].'\')">l&ouml;schen</span> | <span id="acp_shop_list_stock0" class="spanlink" onClick="set_shop_item_stock_0(\''.$data['shop_item_id'].'\')">Lager auf 0</span> | <span id="acp_shop_list_activator" class="spanlink" onClick="set_shop_item_activator(\''.$data['shop_item_id'].'\',\''.$data['item_enabled'].'\')">'.$strEnabled.'</span>';
				
				if($data['shopste_marktplatz_menue_id'] != '0') {
					echo ' | <a href="'.$strLinkMarket.'">Marktplatz &ouml;ffnen</a>';
				}
				
				echo '</td>';				
			echo '</tr>';
			$bTreffer = true;
		}
	} else {
		echo "Keine Artikel in der Sektion gefunden";
	}
	if($_POST['modus'] == 'byImportiert') {
			echo '<br/><br/><span class="spanlink" onClick="acp_shop_item_delete_imported(\'byImportDelete\')">Importierte Produkte löschen</span><br/><br/>';
	} else {
		#if($bTreffer == true) {
		
		#}
	}
	if($bTreffer == false and $bModusSelected == true) {
		echo '<br/><h3>Es wurden keine Artikel in der Ergebnisauflistung gefunden.</h3>';
	}	  
	  
  }

  
  #echo $_POST['modus'];
 # if($_POST['modus'] == 'byLastChange' || $_POST['modus'] =='search_item') {
  ?>
 

<?php 
   if(!isset($_GET['modus'])) {	   
   # ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all
?>
    <div id="tabs">
	
    <ul class="ui-tabs-nav" role="tablist">
		<li><a href="/ACP/acp_shop_item_list.php?modus=bySKU&seite=<?php echo $_POST['seite'] ?>&suche=<?php echo $_POST['suche']; ?>">Nach Artikelnummer A - Z</a></li>
		<li><a href="/ACP/acp_shop_item_list.php?modus=byABC&seite=<?php echo $_POST['seite'] ?>&suche=<?php echo $_POST['suche']; ?>">Produktbezeichnung A - Z </a></li>
		<li><a href="/ACP/acp_shop_item_list.php?modus=byLastChange&seite=<?php echo $_POST['seite'] ?>&suche=<?php echo $_POST['suche']; ?>">Zuletzt ge&auml;ndert</a></li>
		<li><a href="/ACP/acp_shop_item_list.php?modus=byKategorie&seite=<?php echo $_POST['seite'] ?>&suche=<?php echo $_POST['suche']; ?>">Nach Kategorie</a></li>
		<li><a href="/ACP/acp_shop_item_list.php?modus=byCreated&seite=<?php echo $_POST['seite'] ?>&suche=<?php echo $_POST['suche']; ?>">Erstellt am</a></li>
		<li><a href="/ACP/acp_shop_item_list.php?modus=byNotActive&seite=<?php echo $_POST['seite'] ?>&suche=<?php echo $_POST['suche']; ?>">Nicht aktiviert </a></li>
	<?php
		if($strDomain_ary['isShopste'] == 'Y') {					
	?>		
		<li><a href="/ACP/acp_shop_item_list.php?modus=byNotMarket&seite=<?php echo $_POST['seite'] ?>&suche=<?php echo $_POST['suche']; ?>">Nicht auf Shopste Marktplatz</a></li>
	<?php
	}
	?>		
		<li><a href="/ACP/acp_shop_item_list.php?modus=byImportiert&seite=<?php echo $_POST['seite'] ?>&suche=<?php echo $_POST['suche']; ?>">Importiert</a></li>   
	<?php
		if($strDomain_ary['isShopste'] == 'Y') {					
	?>
		<li><a href="/ACP/acp_shop_item_list.php?modus=byActiveMarket&seite=<?php echo $_POST['seite'] ?>&suche=<?php echo $_POST['suche']; ?>">Auf Shopste Market</a></li>
	<?php
	}
	?>
		<li><a href="/ACP/acp_shop_item_list.php?modus=byDeleteAllProducts&seite=<?php echo $_POST['seite'] ?>&suche=<?php echo $_POST['suche']; ?>">Alle Produkte l&ouml;schen</a></li>
	</ul>
  </div>
 <?php
   }
   ?>
<div id="acp_main_shop_item_form">	   
<div id="tabs-1">
<h1>Produktverwaltung - <?php echo $strName.'<br/>'; ?></h1>

<?php 
	if(isset($_POST['message'])) {
		echo $_POST['message'];
	}
#echo $query;
 getSektionHTML($query,$strOptMenueMarket);
?>
</div>
</div>
	
</table>		

