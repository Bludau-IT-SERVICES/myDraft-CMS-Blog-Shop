<?php  
function getTitleParrent($parrentID) {
	if($parrentID !='') {
	$query = "SELECT * FROM menue WHERE id=".$parrentID;

	$result = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
	$strName  = mysqli_fetch_assoc($result);
	return $strName['name_de'];
	}
	return "";
} 

function LoadModul_menue($config) { 
	
	$bIn = false;
	$text = '';
	$titel = '';
	
	$query = "SELECT * FROM modul_menue WHERE id=".$config['modul_id'];
	$resModulMenue = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
 	$dataMenue = mysqli_fetch_array($resModulMenue); 		
 
	$bSettingactiv = true; 
	
	# >> HAUPTMEN? 
	# nicht Submenu 
	#echo $dataMenue["typ"];
	if ($dataMenue["typ"] == 'menue') { 
	
		$standardtitel = core_menu_default_header_titel;
	
		# Alphabetisch 
		if($dataMenue['bAlphabetisch'] == 'Y') {
			$strORDERBY = ' ORDER BY name_de ASC'; 
		} else {
			$strORDERBY = ' ORDER BY sortierung '; 
		} 
		
		# Abrufen der Men? Daten
		$query = "SELECT * FROM menue_parent WHERE menue_id='".$dataMenue["menue_id"]."'";
		#echo $query;
		$resMenuParent = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		$getParent = mysqli_fetch_assoc($resMenuParent);
		#print_r($getParent);
		if(empty($getParent['parent_id'])) {
			$getParent['parent_id'] = 0;
		}
		$query = "SELECT * FROM menue_parent LEFT JOIN menue ON menue_parent.menue_id=menue.id WHERE menue.domain_id='".$_SESSION['domain_id']."' AND menue_parent.parent_id=".$getParent['parent_id']." AND status_de='sichtbar' AND (content_type='kategorie_seite' OR content_type='news_content' or content_type='normale_seite' OR content_type='rss_kategorie' OR content_type='rss_content') ".$strORDERBY;
		#echo $query.'<br/>';
		$menueqry = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 
		
	} else if ($dataMenue["typ"] == 'submenue') { 
	
		if($dataMenue['bAlphabetisch'] == 'Y') {
			$strORDERBY = ' ORDER BY menue.name_de ASC'; 
		} else {
			$strORDERBY = ' ORDER BY menue.sortierung '; 
		} 
		
		$query = "SELECT * FROM menue_parent LEFT JOIN menue ON menue_parent.menue_id=menue.id WHERE menue.domain_id='".$_SESSION['domain_id']."' AND menue_parent.parent_id=".$dataMenue["menue_id"]." AND (content_type='kategorie_seite' OR content_type='news_content' or content_type='normale_seite' OR content_type='rss_kategorie' OR content_type='rss_content') AND status_de='sichtbar' ".$strORDERBY;
		#echo "IN".$query;
		$menueqry = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 
		$standardtitel = 'Kategorien';
	}	 

	if(!empty($menueqry)) {
	
		$text .= '<nav><ul class="modul_menue_normal" id="modul_menue_'.$config['modul_id'].'">';
		while ($menuerow = mysqli_fetch_array($menueqry)) { 	
				#echo "IN";
				$page_settings = getPageSettings($menuerow['id']);
				$titel_link = getPageTitle($page_settings);
				
				# NEU - nur wenn kein Title
				if ($dataMenue["title_de"] == '') {
					if(isset($getParent['parent_id'])) {
						$titel = convertUmlaute(getTitleParrent($getParent['parent_id']));
					}
				}
				
				#echo $dataMenue["title_de"].'IN';
				if($titel == '') { $titel = convertUmlaute($dataMenue["title_de"]); } 
				if($titel == '') { $titel = $standardtitel; } 
				$page_name = menue_NameConvert($menuerow["id"]);
				 
				#$text .= '<li style="padding-bottom:8px;">';	 
				
				if($_SESSION['page_id'] != $menuerow['id']) { 
				
					$path = getPathUrl($_SESSION['language'],$menuerow['id']);
					
					#$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
					
					
					# Mit Link 
					if(!empty($page_name['text'])) {
						$text .= '<li class="menue_side_item"> <a href="'.'/'.$path.'?pk_campaign=nav-menu&pk_kwd=nav-menu&pk_source=nav-menu" title="'.$titel_link.'">&#10149; <strong>'.$page_name['text'].'</strong></a></li>'; 
					}
				} else {
					# Aktuelle Seite ohne Link 
					$text .= '<li class="menue_side_item">&#10149; <strong>'.$page_name['text'].'</strong></li>'; 
				}
			
 
			#$text .= '</div>';
			$bIn = true;
		} 
		
		if($bIn == false) {
				$query = "SELECT * FROM menue_parent WHERE menue_id='".$dataMenue["menue_id"]."'";
				$resMenue = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$menParent = mysqli_fetch_assoc($resMenue);
				
				if(isset($menParent["parent_id"])) {
					$query = "SELECT * FROM menue JOIN menue_parent ON menue.id =menue_parent.menue_id WHERE id='".$menParent["parent_id"]."'";
					$resMenue = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
					$menParent2 = mysqli_fetch_assoc($resMenue);				
				}
				
				if(isset($menParent2['parent_id'])) {
					$query = "SELECT * FROM menue_parent LEFT JOIN menue ON menue_parent.parent_id = menue.id WHERE parent_id='".$menParent2['parent_id']."' ORDER BY id DESC LIMIT 0,10";
					#echo $query;
					$resMenue = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				}

				while($strMenue = mysqli_fetch_assoc($resMenue)) {
								
					$path = getPathUrl($_SESSION['language'],$strMenue['menue_id']);
					
					#$strLink = CORE_SERVER_DOMAIN.$path;
					
					$page_settings = getPageSettings($strMenue['menue_id']);
					$titel_link = getPageTitle($page_settings);
					
					# Mit Link 
					if(!empty($page_settings['name_de']) && $strMenue['menue_id'] != 2657 && $strMenue['menue_id'] != 2658 && $strMenue['menue_id'] != 2660) {
						$text .= '<li class="menue_side_item">+ <a href="/'.$path.'?pk_campaign=nav-menu&pk_kwd=nav-menu&pk_source=nav-menu" title="'.$titel_link.'"><strong>'.$page_settings['name_de'].'</strong></a></li>'; 
					}
				}
		}
		$text .= '</ul>';			
		$text .= '</nav>';			
					   
		/*if($bIn == false) {
			$config['modul_id'] = '19348';
			$config['typ'] = 'menue_shopcategory';
			include_once('../menue_shopcategory/index.php');
			$resModul = LoadModul_menue_shopcategory($config);
			print_r($resModul);
			echo $resModul['content'];
		}*/
		
	}
	#$text .='</ul>';
		

	  $text2 = '<div class="content menue" id="'.$config['typ'].'_'.$config['modul_id'].'">';			 
	  $text2 .= $text;
	  $text2 .= '</div>';	
	  
	  $result = array("title"=>$titel,"content"=>$text2,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 } 
 ?>  