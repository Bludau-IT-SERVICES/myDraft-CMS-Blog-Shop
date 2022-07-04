<?php 

###########################################
# >> Menüfunktion 
###########################################
function shop_sidemap_generator($parent, $level,$html,$openli,$opencon,$typ='menue',$page_id='') { 
		global $yuiclass;

	$yuiclass[0] = "yui-skin-sam";
	$yuiclass[1] = "yuimenubar yuimenubarnav";
	$yuiclass[2] = "bd";
	#yuimenubaritemlabel-hassubmenu 
	$yuiclass[3] = "yuimenubaritemlabel";
	#yuimenubaritemlabel-hassmenu-selected
	$yuiclass[4] = "yuimenuitemlabel";
	#yuimenubaritemlabel-selected 
	$yuiclass[5] = "yuimenubaritem";
	$yuiclass[6] = "yuimenuitem";
	$yuiclass[7] = "yuimenu";
	$yuiclass[8] = "first-of-type";	
	
	if($level > 0) {
			#echo "LEVEL:".$level." ".$row['menue_id'];
			
	}
	if($_SESSION['login'] == 1) {
		$extSichtbar = ''; 	
	} else {
		$extSichtbar = " AND status_".$_SESSION['language']."='sichtbar'"; 
	} 
	
	# Alle Kinder des Menüpunkts abrufen
	$query = "SELECT * FROM shop_category_parent LEFT JOIN shop_category ON shop_category_parent.shop_cat_id=shop_category.shop_cat_id	WHERE shop_category_parent.shop_cat_parent=$parent AND shop_category.domain_id='".$_SESSION['domain_id']."'  ORDER BY name_".$_SESSION['language']." ASC";   
	
	$result = DBi::$conn->query($query) or die(mysqli_error()); 
 
	
		
	while ($row = mysqli_fetch_array($result)) {
		$content = @mysql_num_rows(DBi::$conn->query("SELECT * FROM module_in_menue WHERE menue_id=$row[id]"));
		 
		# Kinder abrufen
		$query = "SELECT * FROM shop_category_parent WHERE shop_cat_parent=".$row['id'];
		$resMenuParent = DBi::$conn->query($query);
		$kinder[$row['id']] = @mysql_num_rows($resMenuParent);
	
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
		$url .= ' href="'.$_SESSION['domain_name'].'/'.getPathUrl($_SESSION['language'],$row['id']).'" target="'.$row['target'].'" title="'.$titel.'">';
		
		# Aktuelle Seite gleich Datensatz hervorheben
		if($_GET['page_id'] == $row['id']) {
			#$page_settings = getPageSettings($row['id']); 
			if(@$_SESSION['login'] == 1 && $page_settings['fertig_de'] == '100') {
				$url .= '<img src="'.$_SESSION['domain_name'].'/images/page_done.png" border="0">';
			}
			$url .= '<strong>'.$convertMenue['text'].'</strong></a>';
		} else {
			# Page Settings holen
			#$page_settings = getPageSettings($row['id']); 
			if(@$_SESSION['login'] == 1) {									
				if ($page_settings['fertig_de'] == '100') {						
					$url .= '<img src="'.$_SESSION['domain_name'].'/images/page_done.png"  title="'.$titel.'" border="0">';
				}
			}
			#echo $convertMenue['text'];
			$url .= $convertMenue['text'].'</a>';		
		}
			
		# Kein Inhalt
		if($content < 1 && @$_SESSION['login'] != '1') { 
			#$url='<bdo '.$class.'>'.$row['name_de'].'</bdo>'; 
		}
		
		# Externer Link im Menü
		if($row['exturl'] != '' && @$_SESSION['login'] != '1') {  
			$url='<a '.$class.' href="'.$row['exturl'].'" target="'.$row['target'].'">'.$row['name_de'].'</a>'; 
		}
		if($typ == 'menue') {
			
			$openli++;
			if($level == '0') { 
				$html .= '      <li id="menuItem_'.$row['menue_id'].'">'.$skipitem.$url;  	
			}
			
			if($level != '0') { 
				$html .= '      <li id="menuItem_'.$row['menue_id'].'">'.$skipitem.$url;	 
			}
			
			if($kinder[$row['id']] > 0) { //4
				$opencon++;					
				$html .= '    <ul>'."\n"; 										
			} //4
			
			$html = shop_sidemap_generator($row['shop_cat_id'], $level+1,$html,$openli,$opencon);  
echo "Ia";
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
				case '4':
					$space ='...............';	
					break;
				case '4':
					$space ='..................';	
					break;
				case '4':
					$space ='.....................';	
					break;
				default: 
					$space ='........................';	
					break;	
			}
			if($page_id == $row['shop_cat_id']) {
				$html .= '<option value="'.$row['shop_cat_id'].'" selected=true>'.$space.$row['name_de'].'</option>';			
			} else {
				$html .= '<option value="'.$row['shop_cat_id'].'">'.$space.$row['name_de'].'</option>';
			}
			$html = shop_sidemap_generator($row['shop_cat_id'], $level+1,$html,$openli,$opencon,$typ,$page_id);  
		}
	#echo "I";
   } # END WHILE MENU GENERIEREN 

   return $html;
} 

####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_sitemap_shopcategory($config) {

		$dataTextHTML = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_sitemap_shopcategory WHERE id=".$config['modul_id']));
		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='sitemap_shopcategory'"));
		#echo "IN";
		
		$dataTextHTML['typ'] = 'sitemap_shopcategory';
		
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
 
		
	#	$text .= shop_sidemap_generator(0,0,'',0,0);
		
		
		$text .= '</div>'; // config modus 

		
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 } 
 ?>