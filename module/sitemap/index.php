<?php 
$gbl_modul_sitemap_preview_rss_content = core_sitemap_preview_rss_content;
$gbl_modul_sitemap_preview_news_content = core_sitemap_preview_news_content;
$gbl_modul_sitemap_preview_shop_content = core_sitemap_preview_shop_content;

###########################################
# >> Sitemap Modul 
###########################################
function sidemap_generator($parent, $level,$html,$openli,$opencon,$typ='menue',$page_id='') { 

	global $gbl_modul_sitemap_preview_rss_content;
	global $gbl_modul_sitemap_preview_shop_content;
	global $gbl_modul_sitemap_preview_news_content;
	if($level > 0) {
			#echo "LEVEL:".$level." ".$row['menue_id'];
			
	}
	if($_SESSION['login'] == 1) {
		$extSichtbar = " AND status_".$_SESSION['language']."='sichtbar'"; 	 # sonst nicht da 1=1
	} else {
		$extSichtbar = " AND status_".$_SESSION['language']."='sichtbar'"; 
	} 
	
	# Alle Kinder des Menüpunkts abrufen
	$query = "SELECT * FROM menue_parent LEFT JOIN menue ON menue_parent.menue_id=menue.id	WHERE menue_parent.parent_id=$parent AND domain_id=".$_SESSION['domain_id']." ".$extSichtbar." ORDER BY sortierung ASC";   
	$result = DBi::$conn->query($query) or die(mysqli_error());; 
 
	
	while ($row = mysqli_fetch_array($result)) {
		$content = @mysqli_num_rows(DBi::$conn->query("SELECT * FROM module_in_menue WHERE menue_id=$row[id]"));
 
		# Kinder abrufen
		$query = "SELECT * FROM menue_parent WHERE parent_id=".$row['id'];
		$resMenuParent = DBi::$conn->query($query);
		$kinder[$row['id']] = @mysqli_num_rows($resMenuParent);
		#$id = $row['id'];
		# Umlaute entfernen
		$convertMenue = menue_NameConvert($row['menue_id']);
		
		# Kein Target angegeben _self 
		if($row['target'] == '') { 
			$row['target'] ='_self'; 
		} 
		
		# Link Bauen YUI Class
		$url = '<a ';
		if($level == '0') { 
			$class = 'class="'.$yuiclass[3].'"'; 
		}
		if($level != '0') { 
			$class = 'class="'.$yuiclass[4].'"'; 
		}
		$url .= $class;
		
		# Page Settings holen
		$page_settings = getPageSettings($row['id']);
		
		# Titel der Seite holen Link title Attribut
		$titel = getPageTitle($page_settings);			
		
		# URL mit absoluten Pfad 
		$url .= ' href="/'.getPathUrl($_SESSION['language'],$row['id']).'" target="'.$row['target'].'" title="'.$titel.'">';
		
		# Aktuelle Seite gleich Datensatz hervorheben
		if($_GET['page_id'] == $row['id']) {
			#$page_settings = getPageSettings($row['id']); 
			if(@$_SESSION['login'] == 1 && $page_settings['fertig_de'] == '100') {
				$url .= '<img src="'.$_SESSION['domain_name'].'/images/page_done.png" border="0">';
			}
			$url .= '<h3>'.$convertMenue['text'].'</h3></a>';
		} else {
			# Page Settings holen
			#$page_settings = getPageSettings($row['id']); 
			if(@$_SESSION['login'] == 1) {									
				#if ($page_settings['fertig_de'] == '100') {						
				#	$url .= '<img src="'.$_SESSION['domain_name'].'/images/page_done.png"  title="'.$titel.'" border="0">';
				#}
			}
			#echo $convertMenue['text'];
			$url .= '<h3>'.$convertMenue['text'].'</h3></a>';		
		}
			
		# Kein Inhalt
		if($content < 1 && @$_SESSION['login'] != '1') { 
			#$url='<bdo '.$class.'>'.$row['name_de'].'</bdo>'; 
		}
		
		# Externer Link im Menü
		if($row['exturl'] != '' && @$_SESSION['login'] != '1') {  
			$url='<a '.$class.' href="'.$row['exturl'].'" target="'.$row['target'].'"><h3>'.$row['name_de'].'</h3></a>'; 
		}
		
		if($typ == 'menue') {
			$openli++;
			if($level == '0') { 
				$html .= '      <li>'.$skipitem.$url;  	
			}
			
			if($level != '0') { 
				$html .= '      <li>'.$skipitem.$url;	 
			}
			
			if($kinder[$row['id']] > 0) { //4
				$opencon++;					
				$html .= '    <ul>'."\n"; 										
			} //4
					# RSS-Kategorie laden
			$query = "SELECT * FROM module_in_menue LEFT JOIN modul_rss_categoryview ON modul_rss_categoryview.id=module_in_menue.modul_id WHERE typ='rss_categoryview' AND module_in_menue.menue_id = '".$row['id']."'";   	
			$resRSSKategorie = DBi::$conn->query($query);
			#echo $query.'<br/>';
			$strRSSKategorie = mysqli_fetch_assoc($resRSSKategorie);
			#print_r($strRSSKategorie);

			#echo 'STATUS: '.$gbl_modul_sitemap_preview_rss_content;
	#		echo $row['id'].' - '.$strRSSKategorie['menue_id'].'<br/>';
			
			if($strRSSKategorie['news_cat'] != '' && $strRSSKategorie['gui_content_show_news'] == 'Y' AND $gbl_modul_sitemap_preview_rss_content == 'Y') {
				
				$query = "SELECT * FROM modul_rss_content WHERE news_cat='".$strRSSKategorie['news_cat']."' ORDER BY created_at DESC LIMIT 0,10";
				#echo $query;
				$resRSSKategorie_content = DBi::$conn->query($query);
				$html  .= '<ul>';
				while($strRSSKategorie_content = mysqli_fetch_assoc($resRSSKategorie_content)) {
						$html  .= '<li><a title="'.$strRSSKategorie_content['AddTitel'].'" href="/'.getPathUrl($_SESSION['language'],$row['menue_id']).'">'.$strRSSKategorie_content['created_at'].' '.$strRSSKategorie_content['AddTitel'].'</a></li>';
				}
				$html  .= '</ul>'; 
			}
			
			$query = "SELECT * FROM module_in_menue LEFT JOIN modul_news_categoryview ON modul_news_categoryview.id=module_in_menue.modul_id WHERE typ='news_categoryview' AND module_in_menue.menue_id = '".$row['id']."'";   	
			$resRSSKategorie = DBi::$conn->query($query);
			#echo $query.'<br/>';
			$strRSSKategorie = mysqli_fetch_assoc($resRSSKategorie);
			#print_r($strRSSKategorie);

			#echo 'STATUS: '.$gbl_modul_sitemap_preview_news_content;
	#		echo $row['id'].' - '.$strRSSKategorie['menue_id'].'<br/>';
			#$html .= $strRSSKategorie['gui_content_show_news']." - OK<br/>";
	
			if($strRSSKategorie['news_cat'] != '' && $strRSSKategorie['gui_content_show_news'] == 'Y') {
				
				$query = "SELECT * FROM modul_news_content WHERE news_cat='".$strRSSKategorie['news_cat']."' ORDER BY created_at DESC LIMIT 0,10";
				#echo $query;
				$resRSSKategorie_content = DBi::$conn->query($query); 
				$html  .= '<ul>';
				while($strRSSKategorie_content = mysqli_fetch_assoc($resRSSKategorie_content)) {
						$html  .= '<li><a title="'.$strRSSKategorie_content['AddTitel'].'" href="/'.getPathUrl($_SESSION['language'],$row['menue_id']).'">'.$strRSSKategorie_content['created_at'].' '.$strRSSKategorie_content['AddTitel'].'</a></li>';
				}
				$html  .= '</ul>'; 
			}
			
			$query = "SELECT * FROM module_in_menue LEFT JOIN modul_portal_shop_cat_list ON modul_portal_shop_cat_list.id=module_in_menue.modul_id WHERE typ='portal_shop_cat_list' AND module_in_menue.menue_id = '".$row['id']."'";   	
			$resRSSKategorie = DBi::$conn->query($query);
			#echo $query.'<br/>';
			$strRSSKategorie = mysqli_fetch_assoc($resRSSKategorie);
			#print_r($strRSSKategorie);

			#echo 'STATUS: '.$gbl_modul_sitemap_preview_news_content;
	#		echo $row['id'].' - '.$strRSSKategorie['menue_id'].'<br/>';
			if($strRSSKategorie['shop_cat_id'] != '' && $gbl_modul_sitemap_preview_shop_content == 'Y') {
				
				$query = "SELECT * FROM shop_item WHERE shopste_marktplatz_cat='".$strRSSKategorie['shop_cat_id']."' ORDER BY created_at DESC LIMIT 0,10";
				#echo $query;
				$resRSSKategorie_content = DBi::$conn->query($query); 
				$html  .= '<ul>';
				while($strRSSKategorie_content = mysqli_fetch_assoc($resRSSKategorie_content)) {
						$html  .= '<li><a title="'.$strRSSKategorie_content['name_de'].'" href="/'.getPathUrl($_SESSION['language'],$row['menue_id']).'">'.$strRSSKategorie_content['name_de'].'</a></li>';
				}
				$html  .= '</ul>';
			}
			# Rekursion
			$html = sidemap_generator($row['menue_id'], $level+1,$html,$openli,$opencon);  
 
			if($openli > $level && $kinder[$id] == 0) { 
				$openli--;
			}	   
			
			if($kinder[$id] == 0 && $opencon > $level) { 
				$html .= '    </ul>'."\n"; 															
				$opencon--;
			} 
			if($openli == $level && $opencon ==$level) { 
				$html .= '</li>'."\n"; 		 
			} 
		} else if($typ == 'select') {
			# jb de-en
			#echo "IN";
 
			#echo $level.'--'.$space.$row['name_de'];
			
			switch($level) {
				case '0':
					$space ='';
					break;
				case '1':
					$space ='...';
					break;					
				case '2':
					$space ='......';
					break;							
				case '3':
					$space ='.........';
					break;
				case '4':
					$space ='............';					
					break;
				case '5':
					$space ='...............';
					break;
				case '6':
					$space ='..................';
					break;
				default: 
					$space ='..................';
					break;								
			}
			if($page_id == $row['menue_id']) {
				$html .= '<option value="'.$row['menue_id'].'" selected=true>'.$space.$row['name_de'].'</option>';			
			} else {
				$html .= '<option value="'.$row['menue_id'].'">'.$space.$row['name_de'].'</option>';
			}
			$html = sidemap_generator($row['menue_id'], $level+1,$html,$openli,$opencon,$typ,$page_id);  
		}

   } # END WHILE MENU GENERIEREN 

   return $html;
} 

####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_sitemap($config) {

		$dataTextHTML = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_sitemap WHERE id=".$config['modul_id']));
		global $gbl_modul_sitemap_preview_rss_content;
		global $gbl_modul_sitemap_preview_news_content;
		global $gbl_modul_sitemap_preview_shop_content;
		$gbl_modul_sitemap_preview_rss_content = $dataTextHTML['gui_show_rss_content_preview'];
		$gbl_modul_sitemap_preview_news_content = $dataTextHTML['gui_show_news_content_preview'];
		$gbl_modul_sitemap_preview_shop_content = $dataTextHTML['gui_show_shop_content_preview'];
		
		#echo $dataTextHTML['gui_show_rss_content_preview'].' - '.$gbl_modul_sitemap_preview_rss_content;
		
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='sitemap'"));
		#echo "IN";
		
		$dataTextHTML['typ'] = 'sitemap';
		
		$text = '<div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
		
		$text .= convertUmlaute($dataTextHTML["content_".$_SESSION['language']]);
		$titel = convertUmlaute($dataTextHTML["title_".$_SESSION['language']]);
		

		
		if($text == '') {   
			$text = convertUmlaute($dataTextHTML["content_de"]); 
		} 
		
		if($titel == '') { 
			$titel = convertUmlaute($dataTextHTML["title_de"]); 
		} 
		
		// && $config["container"]
		if($_SESSION['login'] == '1'  AND $module_in_menue['container'] == 'col-main') {
			$strReturn = getMember($dataTextHTML['last_usr']);
			if(!empty($strReturn)) {
				$ary = explode(" ",$dataTextHTML['lastchange']);
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

		
		$text .= '<ul>'.sidemap_generator(0,0,'',0,0).'</ul>';
		
		
		$text .= '</div>'; // config modus 

		
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 } 
 ?>