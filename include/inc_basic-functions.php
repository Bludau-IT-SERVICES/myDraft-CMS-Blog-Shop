<?php
$pagetitle ='';


########################################################
# Gegen SQL-Injection
########################################################
function mysql_real_escape_array($array) 
{
   foreach ($array as $key => $value) {
	   if (is_array($value)) {
		   $array[$key] = mysql_real_escape_array($value);
	   }
	   else {
		   $array[$key] = DBi::mysql_escape($value,DBi::$conn);
	   }
   }
   return $array;
}
function search_lastest() {
	
	$query = "SELECT * FROM suche_anfragen WHERE freigeschaltet='Y' ORDER BY updated_at LIMIT 0,6";
	
	$strpfad = getPathUrl('de',$_GET['page_id']);

	$resSearch = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));	
	$oSearch = mysqli_fetch_assoc($resSearch);

	while ($row = mysqli_fetch_array($oSearch)) {
		
		$strSearch .= '<a style="font-family:Chango" title="Suche nach Themen" href="'.$strpfad.'/Suche/'.$row['suchanfrage'].'/Seite/1/">'.$row['suchanfrage'].' ('.$strSuche['suchanzahl'].')</a>, ';
	}
	return $strSearch;
}
#############################################################
# >> CORE Fehlermeldungen verwalten
#############################################################
function getCORE_page_rating($seiten_id) {

	if($seiten_id == '') {
		$seiten_id = '0';
	}
	
	if(is_numeric($seiten_id)) {
		$query = "SELECT sum(score) as ges, count(*) as anzahl  FROM seiten_bewertung where seiten_id='".DBi::mysql_escape($seiten_id,DBi::$conn)."'";
		$res2 = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));	
		$strBewertung = mysqli_fetch_assoc($res2);
		if($strBewertung['anzahl'] > 0) {
			$score = $strBewertung['ges'] / $strBewertung['anzahl'];	
		}
	}
	
	if(!isset($score)) {
		$score = 0.0;
	}
	return $score;
}

#############################################################
# >> CORE Fehlermeldungen verwalten
#############################################################
function setCORE_error_msg($error_group,$error_text,$seiten_id) {
	
	if($seiten_id == '') {
		$seiten_id = '0';
	}
	
	if(!isset($_SESSION['domain_id'])) {
		$_SESSION['domain_id'] = 0;
	}
	
	if($seiten_id != 0) {
		$aryPage = getPageSettings($seiten_id);
		$strPageInfo = 'Name: '. $aryPage['name_de']."\r\n";
		$strPageInfo .= 'Titel: '. $aryPage['titel_de']."\r\n";
		$strPageInfo .= 'Status: '. $aryPage['status_de']."\r\n";
		$strPageInfo .= 'SeitenID: '. $aryPage['id']."\r\n";
		$strPageInfo .= 'Seitentyp: '. $aryPage['content_type']."\r\n";
		$strPageInfo .= 'Erstellt am: '. $aryPage['created_at']."\r\n";
		$strPageInfo .= 'Aktuallisiert am: '. $aryPage['updated_at']."\r\n";
		$strPageInfo .= 'Template Folder: '. $_SESSION['template_folder']."\r\n";
		$strPageInfo .= 'Template File: '. $aryPage['template_file']."\r\n";
		$strPageInfo .= 'Layout: '. $aryPage['layout']."\r\n";
	}
	
/*	mail(CORE_MAIL_FROM_EMAIL,CORE_SERVER_PLATTFORM_NAME." - ".$error_group." = ".$error_text." http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
	"Fehlerprotokoll:\r\n".$strPageInfo.
	"URL:".$strHTTP."://".DBi::mysql_escape($_SERVER['HTTP_HOST'],DBi::$conn).DBi::mysql_escape($_SERVER['REQUEST_URI'],DBi::$conn));*/
	
	
	$query = "INSERT INTO sys_error(page_id,error_code,error_message,domain_id,request_path) VALUES('".$seiten_id."','".$error_group."','".$error_text."','".$_SESSION['domain_id']."','http://".DBi::mysql_escape($_SERVER['HTTP_HOST'],DBi::$conn).DBi::mysql_escape($_SERVER['REQUEST_URI'],DBi::$conn)."')"; 
	#echo $query;
	DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
	
	return true;
	
}


#############################################################
# >> DomainInfo abrufen
#############################################################
function getDomainInfo() {
	$domain = DBi::mysql_escape($_SERVER['HTTP_HOST'],DBi::$conn);
	$domain = str_replace("www.", "", $domain);

	#$query = "SELECT * from domains WHERE name='$domain'";
	$query = "SELECT * from domains  JOIN shop_info ON domains.domain_id = shop_info.domain_id WHERE name='".$domain."'";
	$domain_res = mysqli_fetch_assoc(DBi::$conn->query($query));
	
	return $domain_res;
}
	
function news_category($parent, $level,$html,$openli,$opencon,$typ='menue',$page_id='') { 
	
	if($level > 0) {
			#echo "LEVEL:".$level." ".$row['menue_id'];
	}
	
	if($_SESSION['login'] == 1) {
		#$extSichtbar = ''; 	
	} else {
		#$extSichtbar = " AND status_".$_SESSION["language"]."='sichtbar'"; 
	} 
	
	# Alle Kinder des Men�punkts abrufen
	$query = "SELECT * FROM modul_news_category_parent LEFT JOIN modul_news_category ON modul_news_category_parent.news_cat_id=modul_news_category.news_cat_id	WHERE modul_news_category_parent.news_cat_parent=$parent AND modul_news_category.domain_id='".$_SESSION['domain_id']."'".$extSichtbar." ORDER BY name_".$_SESSION["language"]." ASC";   
	
	$result = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 
 
	while ($row = mysqli_fetch_array($result)) {
		$content = DBi::mysql_num_row(DBi::$conn->query("SELECT * FROM module_in_menue WHERE menue_id=$row[page_id]"));
		 
		# Kinder abrufen
		$query = "SELECT * FROM modul_news_category_parent WHERE news_cat_parent=".$row['news_cat_id'];
		$resMenuParent = DBi::$conn->query($query);
		$kinder[$row['news_cat_id']] = DBi::mysql_num_row($resMenuParent);
	
		# Umlaute entfernen
		#$convertMenue = menue_NameConvert($row['menue_id']);
		
		# Kein Target angegeben _self 
		if($row['target'] == '') { 
			$row['target'] ='_self'; 
		} 
		
		# Link Bauen YUI Class
		$url = '<a ';
		if($level == '0') { 
			$class = 'class="flexnav-item"'; 
		}
		if($level != '0') { 
			$class = 'class="flexnav-item"'; 
		}
		$url .= $class;
		
		# Page Settings holen
		$page_settings = getPageSettings($row['id']);
		
		# Titel der Seite holen Link title Attribut
		$titel = getPageTitle($page_settings);			
		
		# URL mit absoluten Pfad 
		$url .= ' href="'.$_SESSION['domain_name'].'/'.getPathUrl($_SESSION["language"],$row['page_id']).'" target="'.$row['target'].'" title="'.$titel.'">';
		
		# Aktuelle Seite gleich Datensatz hervorheben
		if($_GET['page_id'] == $row['news_cat_id']) {
			#$page_settings = getPageSettings($row['id']); 
			if(@$_SESSION['login'] == 1 && $page_settings['fertig_de'] == '100') {
				$url .= '<img src="'.$_SESSION['domain_name'].'/images/page_done.png" border="0">';
			}
			$url .= '<strong>'.$row['name_de'].'</strong></a>';
		} else {
			# Page Settings holen
			#$page_settings = getPageSettings($row['id']); 
			if(@$_SESSION['login'] == 1) {									
				if ($page_settings['fertig_de'] == '100') {						
					$url .= '<img src="'.$_SESSION['domain_name'].'/images/page_done.png"  title="'.$titel.'" border="0">';
				}
			}
			#echo $convertMenue['text'];
			$url .= $row['name_de'].'</a>';		
		}
			
		# Kein Inhalt
		if($content < 1 && @$_SESSION['login'] != '1') { 
			#$url='<bdo '.$class.'>'.$row['name_de'].'</bdo>'; 
		}
		
		# Externer Link im Men�
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
			
			#echo $kinder[];
			#exit;
			if($kinder[$row['news_cat_id']] > 0) { //4
				$opencon++;					
				$html .= '    <ul>'."\n"; 										
			} //4
			
			$html =news_category($row['news_cat_id'], $level+1,$html,$openli,$opencon,$typ,$page_id); 

			if($openli > $level && $kinder[$row['news_cat_id']] == 0) { 
				$openli--;
			}	   
			
			if($kinder[$row['news_cat_id']] == 0 && $opencon > $level) { 
				$html .= '    </ul>'."\n"; 															
				$opencon--;
			} 
			if($openli == $level && $opencon ==$level) { 
				$html .= '</li>'."\n"; 		 
			} 
		} else if($typ == 'api') { 
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
				$html .= $row['news_cat_id'].'|'.$space.'|'.$level.'|'.$row['name_de'].'|'.$row['news_cat_parent'].'<br>';
				
				$html = news_category($row['news_cat_id'], $level+1,$html,$openli,$opencon,$typ,$page_id);  
				
		}
		else if($typ == 'select') {
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
			if($page_id == $row['news_cat_id']) {
				$html .= '<option value="'.$row['news_cat_id'].'" selected=true>'.$space.$row['name_de'].'</option>';			
			} else {
				$html .= '<option value="'.$row['news_cat_id'].'">'.$space.$row['name_de'].'</option>';
			}
			$html = news_category($row['news_cat_id'], $level+1,$html,$openli,$opencon,$typ,$page_id);  
		}

   } # END WHILE MENU GENERIEREN 

   return $html;
} 
function get_modulselector($params) {
		if($domain_res['bIsShop'] == 'N') {
			$query = "SELECT * FROM benutzer JOIN benutzer_gruppe ON benutzer.profile_id = benutzer_gruppe.benutzer_gruppe_id JOIN module_installiert_profiles  ON module_installiert_profiles.profile_id = benutzer_gruppe.benutzer_gruppe_id JOIN module_installiert ON module_installiert.module_installed_id = module_installiert_profiles.module_installiert_id WHERE benutzer.id ='".$_SESSION['user_id']."'  AND isShop = '".$domain_res['bIsShop']."' AND module_installiert.status_aktiv = 'Y' ORDER BY module_installiert.name_de ASC";
		} else {		
			$query = "SELECT * FROM benutzer JOIN benutzer_gruppe ON benutzer.profile_id = benutzer_gruppe.benutzer_gruppe_id JOIN module_installiert_profiles  ON module_installiert_profiles.profile_id = benutzer_gruppe.benutzer_gruppe_id JOIN module_installiert ON module_installiert.module_installed_id = module_installiert_profiles.module_installiert_id WHERE benutzer.id ='".$_SESSION['user_id']."' AND module_installiert.status_aktiv = 'Y' ORDER BY module_installiert.name_de ASC";
		}
		$resModule = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		$html .= '<select id="optModul" class="opt" name="optModul" size="1">';
		while($dataModules = mysqli_fetch_assoc($resModule)) {
		
			$html .= '<option value="'.$dataModules['typ'].'">'.$dataModules['name_de'].'</option>';
		
		} 
		$html .= '</select>';
		return $html;
}
	########################################################
	# Admin Login Check 
	########################################################
	function admin_cookie_check() {
		
		#$_COOKIE = mysql_real_escape_array($_COOKIE);
		
		# Prüfen ob Cookie überhaupt gesetzt
		#echo $_COOKIE['admin_user'];
		#echo $_COOKIE['admin_pwd'];
		#echo $_SESSION['domain_id'];
		if (isset($_COOKIE['admin_user'])) {
			# Verbindung zur Datenbank
			//include ("include/inc_db_connect.php");
			
			# Seiten Einstellungen laden
			#echo $_GET['page_id'];
			
			# OLD
			#$aryPage = getPageSettings($_GET['page_id']);
			
			$aryPage = getPageSettings($_SESSION['page_id']);
			
			#print_r($aryPage);
			
			#echo $_SESSION['domain_id'];
			
			if($aryPage['domain_id'] == $_SESSION['domain_id']) {
		
				# SQL Abfrage der Benutzerdaten
				$query = "SELECT * FROM benutzer WHERE username='".DBi::mysql_escape($_COOKIE['admin_user'],DBi::$conn)."' AND domain_id='".$_SESSION['domain_id']."'";
				
				$result = DBi::$conn->query($query);

				# Auswertung der Abfrage
				while($i = mysqli_fetch_assoc($result)) {
					# MD5-Hash vom Datenbank passwort erzeugen
					#$pwd = md5 ($i[2]);     
					# Daten richtig mit Cookie Daten ? 
	    #echo 				md5($i['password']).' -  '.$_COOKIE['admin_pwd'].' - '.$_COOKIE['admin_user'].' - '.$i['username'];
					if ((md5($i['password']) == $_COOKIE['admin_pwd']) and ($_COOKIE['admin_user'] == $i['username'])) {
						#exit;
						#echo "---->1";
						// Session Restore
						$_SESSION['login'] == '1';
						$_SESSION['user_id'] = $i['id'];
						// Relogin
						#$res = setcookie("admin_UID",$i['id'] , time() + 2592000,"/", $_SERVER['SERVER_NAME']);
						$res = setcookie("admin_pwd",md5($i['password']), time() + 2592000,"/", $_SERVER['SERVER_NAME']);
						$res = setcookie("admin_user",$i['username'], time() + 2592000,"/", $_SERVER['SERVER_NAME']);
						
						return "1";
						exit;
					}
					else {
						#exit;
						return "0";
						exit;
					}
				} # End While 
			
				return "0";
				exit;
			} # END ELSE
			else {
				# Geladene Seite gehört nicht zur Domain
				echo "KEIN Quelldomain: ".$_SESSION['domain_id']." Zieldomain: ".$aryPage['domain_id']." LOGIN";
				return 0;
				exit;
			}
		}
		else {
			#echo "KEIN Username LOGIN";
			return 0;
			exit;
		}
} # End Funktion 

#############################################
# CMS: PageSettings
#############################################
function getPageSettings($page_id) {

	if (!empty($page_id)) {
		$query = "SELECT * FROM menue WHERE id='".$page_id."'";
		$respage = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		$page_settings = mysqli_fetch_array($respage);
	}	
	
	return $page_settings;
}

##################################
# >> Seiten Titel holen 
##################################
function getPageTitle($page_settings) { 

	# fix jb 
	# global $page_settings;
	$domain = $_SERVER['HTTP_HOST'];
	$domain = str_replace("www.", "", $domain);
	
	if(isset($page_settings['id'])) {
		$parentres = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM menue_parent WHERE menue_id='$page_settings[id]'"));
		$parentmenures = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM menue WHERE id='$parentres[parent_id]'"));
	}

	if(isset($parentmenures['id'])) {
		$parentres2 = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM menue_parent WHERE menue_id='$parentmenures[id]'"));
		$parentmenures2 = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM menue WHERE id='$parentres2[parent_id]'"));
	}

	if(isset($parentmenures2['id'])) {
		$parentres3 = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM menue_parent WHERE menue_id='$parentmenures2[id]'"));
		$parentmenures3 = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM menue WHERE id='$parentres3[parent_id]'"));
	}

	$pagetitle = ''; 
	if($page_settings["titel_".$_SESSION["language"]] != '') {
	
		if (empty($pagetitle)) {
			$pagetitle .=  $page_settings["titel_".$_SESSION["language"]];
		} else {
			$pagetitle .=  ' - '.$page_settings["titel_".$_SESSION["language"]];
		}			
		
	} else if ($page_settings["name_".$_SESSION["language"]] != '') {
	
		if (empty($pagetitle)) {
			$pagetitle .= $page_settings["name_".$_SESSION["language"]];
		} else {
			$pagetitle .= ' - '.$page_settings["name_".$_SESSION["language"]];
		}			
		
		
	}

	if(isset($parentmenures3["titel_".$_SESSION["language"]])) {

		if (empty($pagetitle)) {
			$pagetitle .= $parentmenures3["titel_".$_SESSION["language"]];
		} else {
			$pagetitle .= " - ".$parentmenures3["titel_".$_SESSION["language"]];		
		}
		
	} else if (isset($parentmenures3["name_".$_SESSION["language"]])) {
	
		if (empty($pagetitle)) {
			$pagetitle .= $parentmenures3["name_".$_SESSION["language"]];
		} else {
			$pagetitle .= " - ".$parentmenures3["name_".$_SESSION["language"]];
		}	
		
	}
	
	if(isset($parentmenures["titel_".$_SESSION["language"]])) {

		if (empty($pagetitle)) {
			$pagetitle .=  $parentmenures["titel_".$_SESSION["language"]];
		} else {
			$pagetitle .=  ' - '.$parentmenures["titel_".$_SESSION["language"]];
		}	
		
		
	} else if (isset($parentmenures["name_".$_SESSION["language"]])) {
	
		if (empty($pagetitle)) {
			$pagetitle .=  $parentmenures["name_".$_SESSION["language"]];
		} else {
			$pagetitle .=  ' - '.$parentmenures["name_".$_SESSION["language"]];
		}		
		
	}


	if(isset($parentmenures2["titel_".$_SESSION["language"]])) {
	
		if (empty($pagetitle)) {
			$pagetitle .= $parentmenures2["titel_".$_SESSION["language"]];
		} else {
			$pagetitle .= " - ".$parentmenures2["titel_".$_SESSION["language"]];
		}	
		
	} else if (isset($parentmenures2["name_".$_SESSION["language"]])) {

		if (empty($pagetitle)) {
			$pagetitle .= $parentmenures2["name_".$_SESSION["language"]];
		} else {
			$pagetitle .= " - ".$parentmenures2["name_".$_SESSION["language"]];
		}	
				
	}
 
	return $pagetitle;
}

########################
# >>  Holt den Pfad 
#
# RET: DomainArray
########################
function getPathUrl($lang='de',$page) {

	$res4 = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM menue_parent WHERE menue_id='$page'"));	

	if(isset($res4['parent_id'])) {

		if(isset($res4['parent_id'])) $convertMenue = menue_NameConvert($res4['parent_id']);

		if(!empty($convertMenue['linktext'])) { 
			$MenuePath[4] = utf8_encode(strip_tags($convertMenue['linktext'])).'/'; 		
			unset($convertMenue); 
		}
		$res3 = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM menue_parent WHERE menue_id='$res4[parent_id]'"));
	
		if(isset($res3['parent_id'])) $convertMenue = menue_NameConvert($res3['parent_id']);	
		if(isset($convertMenue['linktext'])) { 
			$MenuePath[3] = utf8_encode(strip_tags($convertMenue['linktext'])).'/'; 
			unset($convertMenue); 
		}
		
	}

	if(isset($res3['parent_id'])) {

		$res2 = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM menue_parent WHERE menue_id='$res3[parent_id]'"));

		if(isset($res2['parent_id'])) $convertMenue = menue_NameConvert($res2['parent_id']);	
		if(!empty($convertMenue['linktext'])) { 
			$MenuePath[2] = utf8_encode(strip_tags($convertMenue['linktext'])).'/'; 
			unset($convertMenue); 
		}
	}
	
	if(isset($res2['parent_id'])) {

		$res1 = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM menue_parent WHERE menue_id='$res2[parent_id]'"));

		if(isset($res1['parent_id'])) $convertMenue = menue_NameConvert($res1['parent_id']);
	
		if(!empty($convertMenue['linktext'])) { 
			$MenuePath[1] = utf8_encode(strip_tags($convertMenue['linktext'])).'/'; 
			unset($convertMenue); 
		}
	}
	
	if(isset($res1['parent_id'])) {

		$res0 = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM menue_parent WHERE menue_id='$res1[parent_id]'"));
	
		if(isset($res0['parent_id']))	$convertMenue = menue_NameConvert($res0['parent_id']);	
	
		if(!empty($convertMenue['linktext'])) { 
			$MenuePath[0] = utf8_encode(strip_tags($convertMenue['linktext'])).'/'; 
			unset($convertMenue); 
		}
	
		if(isset($page)) $convertMenue = menue_NameConvert($page);
	
		if (!empty($convertMenue['linktext'])) $MenuePath[5] = utf8_encode(strip_tags($convertMenue['linktext'])).'/';
	}

 	$path = $_SESSION['domainLanguage']."/".$page."/";
	if(isset($MenuePath[0])) {
		$path .= $MenuePath[0];
	}
	if(isset($MenuePath[1])) {
		$path .= $MenuePath[1];
	}
	if(isset($MenuePath[2])) {
		$path .= $MenuePath[2];
	}
	if(isset($MenuePath[3])) {
		$path .= $MenuePath[3];
	}
	if(isset($MenuePath[4])) {
		$path .= $MenuePath[4];
	}
	if(isset($MenuePath[5])) {
		$path .= $MenuePath[5];
	}
	
/*	$path = str_replace('&#252;','ue',$path);
	$path = str_replace('&#228;','ae',$path);
	$path = str_replace('&#246;','oe',$path); 
	$path = str_replace('&','%26',$path); 
	$path = str_replace('"','%22',$path); 
	$path = str_replace('#','%23',$path); 
	$path = str_replace('$','%24',$path); 
	$path = str_replace('%','%25',$path); 
	$path = str_replace('%','%25',$path); 
	$path = str_replace('+','%2B',$path); 
	$path = str_replace(',','%2C',$path); 
	#$path = str_replace('/','%2F',$path); 
	$path = str_replace(':','%3A',$path); 
	$path = str_replace(';','%3B',$path); 
	$path = str_replace('<','%3C',$path); 
	$path = str_replace('=','%3D',$path); 
	$path = str_replace('>','%3E',$path); 
	$path = str_replace('?','%3F',$path); 
	$path = str_replace('@','%40',$path); 
	$path = str_replace('?','',$path); 				
		$path = str_replace('�','',$path); 
	$path = str_replace('�','',$path); 	
	$path = str_replace('|','',$path); 	
	$path = str_replace ("_/_","-", $path); 
	$path = str_replace ("__","-", $path);
	$path = str_replace ("_-_","-", $path); 
	$path = str_replace ("_","-", $path); */

	$path = str_replace('&#252;','ue',$path);
	$path = str_replace('&#228;','ae',$path);
	$path = str_replace('&#246;','oe',$path); 
	$path = str_replace('&','',$path); 
	$path = str_replace('"','',$path); 
	$path = str_replace('#','',$path); 
	$path = str_replace('$','',$path); 
	$path = str_replace('+','',$path); 
	$path = str_replace(',','',$path); 
	#$path = str_replace('/','%2F',$path); 
	$path = str_replace(':','',$path); 
	$path = str_replace(';','',$path); 
	$path = str_replace('<','',$path); 
	$path = str_replace('=','',$path); 
	$path = str_replace('>','',$path); 
	$path = str_replace('?','',$path); 
	$path = str_replace('@','',$path); 
	$path = str_replace('(','',$path);
	$path = str_replace(')','',$path);
	$path = str_replace('[','',$path);
	$path = str_replace(']','',$path);
#	$path = str_replace('.','',$path);
	$path = str_replace('�','',$path); 
	$path = str_replace('\'','',$path); 	
	$path = str_replace('|','',$path); 	
	$path = str_replace ("_/_","-", $path); 
	$path = str_replace ("__","-", $path);
	$path = str_replace ("_-_","-", $path); 
	$path = str_replace ("_","-", $path); 
	$path = str_replace('%','',$path); 
	$path = str_replace('#','',$path); 

	$path = str_replace("%2F","/",urlencode($path));
	$path = str_replace("%C3%BC","ü",$path);
	$path = str_replace("%C3%A4","ä",$path);
	$path = str_replace("%C3%B6","ö",$path);
	
	$path = str_replace("%C3%9C","Ü",$path);
	$path = str_replace("%C3%84","Ä",$path);
	$path = str_replace("%C3%96","Ö",$path);

	$path = str_replace("%C3%9F","ß",$path);
	$path = str_replace("ampquot","-",$path);
	$path = str_replace("--","-",$path);
	return $path;
}
function getMember($id) {
	if(!empty($id)) {
		$query = 'SELECT * FROM benutzer WHERE id='.$id; 
		$result = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));	
		$usr = mysqli_fetch_assoc($result); 
	}
	return $usr['username'];
} 
########################################
# >> Datum generieren 
########################################
function getDateDE($old) {
	$SplitDatum = explode(' ', $old);
	
	 $datum = explode('-',$SplitDatum[0]);
	 #print_r($datum);
	 $datum['jahr']		= $datum[0];
	 $datum['monat']	= $datum[1];
	 $datum['tag']		= $datum[2];
	  	 
	 $zeit = explode(':', $SplitDatum[1]);
	 
	 
	 #print_r($zeit);
	 $datum['stunde']	= $zeit[0];
	 $datum['minute']	= $zeit[1];
	 $datum['sekunde']	= $zeit[2];
	 
	 $datum_ganz 		= $datum['tag'].'.'.$datum['monat'].'.'.$datum['jahr'].' um '.$datum['stunde'].':'.$datum['minute'].' Uhr';
	 return $datum_ganz;
}

###########################################
# >> Shop Men� 
###########################################
function rss_category($parent, $level,$html,$openli,$opencon,$typ='menue',$page_id='') { 
	
	if($level > 0) {
			#echo "LEVEL:".$level." ".$row['menue_id'];
	}
	
	if($_SESSION['login'] == 1) {
		#$extSichtbar = ''; 	
	} else {
		#$extSichtbar = " AND status_".$_SESSION["language"]."='sichtbar'"; 
	} 
	
	# Alle Kinder des Men�punkts abrufen
	$query = "SELECT * FROM modul_rss_category_parent LEFT JOIN modul_rss_category ON modul_rss_category_parent.news_cat_id=modul_rss_category.news_cat_id	WHERE modul_rss_category_parent.news_cat_parent=$parent AND modul_rss_category.domain_id='".$_SESSION['domain_id']."'".$extSichtbar." ORDER BY name_".$_SESSION["language"]." ASC";   
	
	$result = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 
 
	
		
	while ($row = mysqli_fetch_array($result)) {
		$content = DBi::mysql_num_row(DBi::$conn->query("SELECT * FROM module_in_menue WHERE menue_id=$row[page_id]"));
		 
		# Kinder abrufen
		$query = "SELECT * FROM modul_rss_category_parent WHERE news_cat_parent=".$row['news_cat_id'];
		$resMenuParent = DBi::$conn->query($query);
		$kinder[$row['news_cat_id']] = DBi::mysql_num_row($resMenuParent);
	
		# Umlaute entfernen
		#$convertMenue = menue_NameConvert($row['menue_id']);
		
		# Kein Target angegeben _self 
		if($row['target'] == '') { 
			$row['target'] ='_self'; 
		} 
		
		# Link Bauen YUI Class
		$url = '<a ';
		if($level == '0') { 
			$class = 'class="flexnav-item"'; 
		}
		if($level != '0') { 
			$class = 'class="flexnav-item"'; 
		}
		$url .= $class;
		
		# Page Settings holen
		$page_settings = getPageSettings($row['id']);
		
		# Titel der Seite holen Link title Attribut
		$titel = getPageTitle($page_settings);			
		
		# URL mit absoluten Pfad 
		$url .= ' href="'.$_SESSION['domain_name'].'/'.getPathUrl($_SESSION["language"],$row['page_id']).'" target="'.$row['target'].'" title="'.$titel.'">';
		
		# Aktuelle Seite gleich Datensatz hervorheben
		if($_GET['page_id'] == $row['news_cat_id']) {
			#$page_settings = getPageSettings($row['id']); 
			if(@$_SESSION['login'] == 1 && $page_settings['fertig_de'] == '100') {
				$url .= '<img src="'.$_SESSION['domain_name'].'/images/page_done.png" border="0">';
			}
			$url .= '<strong>'.$row['name_de'].'</strong></a>';
		} else {
			# Page Settings holen
			#$page_settings = getPageSettings($row['id']); 
			if(@$_SESSION['login'] == 1) {									
				if ($page_settings['fertig_de'] == '100') {						
					$url .= '<img src="'.$_SESSION['domain_name'].'/images/page_done.png"  title="'.$titel.'" border="0">';
				}
			}
			#echo $convertMenue['text'];
			$url .= $row['name_de'].'</a>';		
		}
			
		# Kein Inhalt
		if($content < 1 && @$_SESSION['login'] != '1') { 
			#$url='<bdo '.$class.'>'.$row['name_de'].'</bdo>'; 
		}
		
		# Externer Link im Men�
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
			
			#echo $kinder[];
			#exit;
			if($kinder[$row['news_cat_id']] > 0) { //4
				$opencon++;					
				$html .= '    <ul>'."\n"; 										
			} //4
			
			$html =rss_category($row['news_cat_id'], $level+1,$html,$openli,$opencon,$typ,$page_id); 

			if($openli > $level && $kinder[$row['news_cat_id']] == 0) { 
				$openli--;
			}	   
			
			if($kinder[$row['news_cat_id']] == 0 && $opencon > $level) { 
				$html .= '    </ul>'."\n"; 															
				$opencon--;
			} 
			if($openli == $level && $opencon ==$level) { 
				$html .= '</li>'."\n"; 		 
			} 
		} else if($typ == 'api') { 
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
				$html .= $row['news_cat_id'].'|'.$space.'|'.$level.'|'.$row['name_de'].'|'.$row['news_cat_parent'].'<br>';
				
				$html = rss_category($row['news_cat_id'], $level+1,$html,$openli,$opencon,$typ,$page_id);  
				
		}
		else if($typ == 'select') {
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
			if($page_id == $row['news_cat_id']) {
				$html .= '<option value="'.$row['news_cat_id'].'" selected=true>'.$space.$row['name_de'].'</option>';			
			} else {
				$html .= '<option value="'.$row['news_cat_id'].'">'.$space.$row['name_de'].'</option>';
			}
			$html = rss_category($row['news_cat_id'], $level+1,$html,$openli,$opencon,$typ,$page_id);  
		}

   } # END WHILE MENU GENERIEREN 

   return $html;
} 
###########################################
# >> Shop Men� 
###########################################
function new_category($parent, $level,$html,$openli,$opencon,$typ='menue',$page_id='') { 
	global $menu_class;

	$menu_class[0] = "yui-skin-sam";
	$menu_class[1] = "yuimenubar yuimenubarnav";
	$menu_class[2] = "bd";
	#yuimenubaritemlabel-hassubmenu 
	$menu_class[3] = "yuimenubaritemlabel";
	#yuimenubaritemlabel-hassmenu-selected
	$menu_class[4] = "yuimenuitemlabel";
	#yuimenubaritemlabel-selected 
	$menu_class[5] = "yuimenubaritem";
	$menu_class[6] = "yuimenuitem";
	$menu_class[7] = "yuimenu";
	$menu_class[8] = "first-of-type";	
	
	if($level > 0) {
			#echo "LEVEL:".$level." ".$row['menue_id'];
			
	}
	if($_SESSION['login'] == 1) {
		#$extSichtbar = ''; 	
	} else {
		#$extSichtbar = " AND status_".$_SESSION["language"]."='sichtbar'"; 
	} 
	
	# Alle Kinder des Men�punkts abrufen
	$query = "SELECT * FROM modul_news_category_parent LEFT JOIN modul_news_category ON modul_news_category_parent.news_cat_id=modul_news_category.news_cat_id	WHERE modul_news_category_parent.news_cat_parent=$parent AND modul_news_category.domain_id='".$_SESSION['domain_id']."'".$extSichtbar." ORDER BY name_".$_SESSION["language"]." ASC";   
	
	$result = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 
 
	
		
	while ($row = mysqli_fetch_array($result)) {
		$content = DBi::mysql_num_row(DBi::$conn->query("SELECT * FROM module_in_menue WHERE menue_id=$row[page_id]"));
		 
		# Kinder abrufen
		$query = "SELECT * FROM modul_news_category_parent WHERE news_cat_parent=".$row['news_cat_id'];
		$resMenuParent = DBi::$conn->query($query);
		$kinder[$row['news_cat_id']] = DBi::mysql_num_row($resMenuParent);
	
		# Umlaute entfernen
		#$convertMenue = menue_NameConvert($row['menue_id']);
		
		# Kein Target angegeben _self 
		if($row['target'] == '') { 
			$row['target'] ='_self'; 
		} 
		
		# Link Bauen YUI Class
		$url = '<a ';
		if($level == '0') { 
			$class = 'class="flexnav-item"'; 
		}
		if($level != '0') { 
			$class = 'class="flexnav-item"'; 
		}
		$url .= $class;
		
		# Page Settings holen
		$page_settings = getPageSettings($row['id']);
		
		# Titel der Seite holen Link title Attribut
		$titel = getPageTitle($page_settings);			
		
		# URL mit absoluten Pfad 
		$url .= ' href="'.$_SESSION['domain_name'].'/'.getPathUrl($_SESSION["language"],$row['page_id']).'" target="'.$row['target'].'" title="'.$titel.'">';
		
		# Aktuelle Seite gleich Datensatz hervorheben
		if($_GET['page_id'] == $row['news_cat_id']) {
			#$page_settings = getPageSettings($row['id']); 
			if(@$_SESSION['login'] == 1 && $page_settings['fertig_de'] == '100') {
				$url .= '<img src="'.$_SESSION['domain_name'].'/images/page_done.png" border="0">';
			}
			$url .= '<strong>'.$row['name_de'].'</strong></a>';
		} else {
			# Page Settings holen
			#$page_settings = getPageSettings($row['id']); 
			if(@$_SESSION['login'] == 1) {									
				if ($page_settings['fertig_de'] == '100') {						
					$url .= '<img src="'.$_SESSION['domain_name'].'/images/page_done.png"  title="'.$titel.'" border="0">';
				}
			}
			#echo $convertMenue['text'];
			$url .= $row['name_de'].'</a>';		
		}
			
		# Kein Inhalt
		if($content < 1 && @$_SESSION['login'] != '1') { 
			#$url='<bdo '.$class.'>'.$row['name_de'].'</bdo>'; 
		}
		
		# Externer Link im Men�
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
			
			#echo $kinder[];
			#exit;
			if($kinder[$row['news_cat_id']] > 0) { //4
				$opencon++;					
				$html .= '    <ul>'."\n"; 										
			} //4
			
			$html =new_category($row['news_cat_id'], $level+1,$html,$openli,$opencon,$typ,$page_id); 

			if($openli > $level && $kinder[$row['news_cat_id']] == 0) { 
				$openli--;
			}	   
			
			if($kinder[$row['news_cat_id']] == 0 && $opencon > $level) { 
				$html .= '    </ul>'."\n"; 															
				$opencon--;
			} 
			if($openli == $level && $opencon ==$level) { 
				$html .= '</li>'."\n"; 		 
			} 
		} else if($typ == 'api') { 
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
				$html .= $row['news_cat_id'].'|'.$space.'|'.$level.'|'.$row['name_de'].'|'.$row['news_cat_parent'].'<br>';
				
				$html = new_category($row['news_cat_id'], $level+1,$html,$openli,$opencon,$typ,$page_id);  
				
		}
		else if($typ == 'select') {
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
			if($page_id == $row['news_cat_id']) {
				$html .= '<option value="'.$row['news_cat_id'].'" selected=true>'.$space.$row['name_de'].'</option>';			
			} else {
				$html .= '<option value="'.$row['news_cat_id'].'">'.$space.$row['name_de'].'</option>';
			}
			$html = new_category($row['news_cat_id'], $level+1,$html,$openli,$opencon,$typ,$page_id);  
		}

   } # END WHILE MENU GENERIEREN 

   return $html;
} 
###########################################
# >> Shop Men� 
###########################################
function shop_category($parent, $level,$html,$openli,$opencon,$typ='menue',$page_id='') { 
	global $menu_class;

	$menu_class[0] = "yui-skin-sam";
	$menu_class[1] = "yuimenubar yuimenubarnav";
	$menu_class[2] = "bd";
	#yuimenubaritemlabel-hassubmenu 
	$menu_class[3] = "yuimenubaritemlabel";
	#yuimenubaritemlabel-hassmenu-selected
	$menu_class[4] = "yuimenuitemlabel";
	#yuimenubaritemlabel-selected 
	$menu_class[5] = "yuimenubaritem";
	$menu_class[6] = "yuimenuitem";
	$menu_class[7] = "yuimenu";
	$menu_class[8] = "first-of-type";	
	
	if($level > 0) {
			#echo "LEVEL:".$level." ".$row['menue_id'];
			
	}
	if($_SESSION['login'] == 1) {
		#$extSichtbar = ''; 	
	} else {
		#$extSichtbar = " AND status_".$_SESSION["language"]."='sichtbar'"; 
	} 
	
	# Alle Kinder des Men�punkts abrufen
	$query = "SELECT * FROM shop_category_parent LEFT JOIN shop_category ON shop_category_parent.shop_cat_id=shop_category.shop_cat_id	WHERE shop_category_parent.shop_cat_parent=$parent AND shop_category.domain_id='".$_SESSION['domain_id']."'".$extSichtbar." ORDER BY name_".$_SESSION["language"]." ASC";   
	
	$result = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 
 
	
		
	while ($row = mysqli_fetch_array($result)) {
		$content = DBi::mysql_num_row(DBi::$conn->query("SELECT * FROM module_in_menue WHERE menue_id=$row[page_id]"));
		 
		# Kinder abrufen
		$query = "SELECT * FROM shop_category_parent WHERE shop_cat_parent=".$row['shop_cat_id'];
		$resMenuParent = DBi::$conn->query($query);
		$kinder[$row['shop_cat_id']] = DBi::mysql_num_row($resMenuParent);
	
		# Umlaute entfernen
		#$convertMenue = menue_NameConvert($row['menue_id']);
		
		# Kein Target angegeben _self 
		if($row['target'] == '') { 
			$row['target'] ='_self'; 
		} 
		
		# Link Bauen YUI Class
		$url = '<a ';
		if($level == '0') { 
			$class = 'class="flexnav-item"'; 
		}
		if($level != '0') { 
			$class = 'class="flexnav-item"'; 
		}
		$url .= $class;
		
		# Page Settings holen
		$page_settings = getPageSettings($row['id']);
		
		# Titel der Seite holen Link title Attribut
		$titel = getPageTitle($page_settings);			
		
		# URL mit absoluten Pfad 
		$url .= ' href="/'.getPathUrl($_SESSION["language"],$row['page_id']).'" target="'.$row['target'].'" title="'.$titel.'">';
		
		# Aktuelle Seite gleich Datensatz hervorheben
		if($_GET['page_id'] == $row['shop_cat_id']) {
			#$page_settings = getPageSettings($row['id']); 
			if(@$_SESSION['login'] == 1 && $page_settings['fertig_de'] == '100') {
				$url .= '<img src="'.$_SESSION['domain_name'].'/images/page_done.png" border="0">';
			}
			$url .= '<strong>'.$row['name_de'].'</strong></a>';
		} else {
			# Page Settings holen
			#$page_settings = getPageSettings($row['id']); 
			if(@$_SESSION['login'] == 1) {									
				if ($page_settings['fertig_de'] == '100') {						
					$url .= '<img src="'.$_SESSION['domain_name'].'/images/page_done.png"  title="'.$titel.'" border="0">';
				}
			}
			#echo $convertMenue['text'];
			$url .= $row['name_de'].'</a>';		
		}
			
		# Kein Inhalt
		if($content < 1 && @$_SESSION['login'] != '1') { 
			#$url='<bdo '.$class.'>'.$row['name_de'].'</bdo>'; 
		}
		
		# Externer Link im Men�
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
			
			#echo $kinder[];
			#exit;
			if($kinder[$row['shop_cat_id']] > 0) { //4
				$opencon++;					
				$html .= '    <ul>'."\n"; 										
			} //4
			
			$html =shop_category($row['shop_cat_id'], $level+1,$html,$openli,$opencon,$typ,$page_id); 

			if($openli > $level && $kinder[$row['shop_cat_id']] == 0) { 
				$openli--;
			}	   
			
			if($kinder[$row['shop_cat_id']] == 0 && $opencon > $level) { 
				$html .= '    </ul>'."\n"; 															
				$opencon--;
			} 
			if($openli == $level && $opencon ==$level) { 
				$html .= '</li>'."\n"; 		 
			} 
		} else if($typ == 'api') { 
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
				$html .= $row['shop_cat_id'].'|'.$space.'|'.$level.'|'.$row['name_de'].'|'.$row['shop_cat_parent'].'<br>';
				
				$html = shop_category($row['shop_cat_id'], $level+1,$html,$openli,$opencon,$typ,$page_id);  
				
		}
		else if($typ == 'select') {
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
			$html = shop_category($row['shop_cat_id'], $level+1,$html,$openli,$opencon,$typ,$page_id);  
		}

   } # END WHILE MENU GENERIEREN 

   return $html;
} 
###########################################
# >> Men�funktion 
###########################################
function menue_generator($parent, $level,$html,$openli,$opencon,$typ='menue',$page_id='') { 
	global $menu_class;
	
	
	$menu_class[0] = "yui-skin-sam";
	$menu_class[1] = "yuimenubar yuimenubarnav";
	$menu_class[2] = "bd";
	#yuimenubaritemlabel-hassubmenu 
	$menu_class[3] = "flex-nav-item";
	#yuimenubaritemlabel-hassmenu-selected
	$menu_class[4] = "flex-nav-item";
	#yuimenubaritemlabel-selected 
	$menu_class[5] = "yuimenubaritem";
	$menu_class[6] = "yuimenuitem";
	$menu_class[7] = "yuimenu";
	$menu_class[8] = "first-of-type";	
	
	if($level > 0) {
			#echo "LEVEL:".$level." ".$row['menue_id'];
			
	}
	if($_SESSION['login'] == 1) {
		$extSichtbar = " AND status_".$_SESSION["language"]."='sichtbar'"; 
	} else {
		$extSichtbar = " AND status_".$_SESSION["language"]."='sichtbar'"; 
	} 
	
	# Alle Kinder des Menüpunkts abrufen
	$query = "SELECT * FROM menue_parent LEFT JOIN menue ON menue_parent.menue_id=menue.id	WHERE menue_parent.parent_id=$parent AND domain_id=".$_SESSION['domain_id']." ".$extSichtbar." ORDER BY sortierung ASC";   
	$result = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));; 
 
	
	while ($row = mysqli_fetch_array($result)) {
		#$content = @mysql_num_rows(DBi::$conn->query("SELECT * FROM module_in_menue WHERE menue_id=$row[id]"));
		 
		# Kinder abrufen
		#$query = "SELECT * FROM menue_parent WHERE parent_id=".$row['id'];
		#$query = "SELECT * FROM menue_parent LEFT JOIN menue ON menue_parent.menue_id=menue.id	WHERE menue_parent.parent_id=".$row['id']." AND domain_id=".$_SESSION['domain_id']." ".$extSichtbar." ORDER BY sortierung ASC"
		$query = "SELECT * FROM menue_parent LEFT JOIN menue ON menue_parent.menue_id=menue.id	WHERE menue_parent.parent_id=".$row['id']." AND domain_id=".$_SESSION['domain_id']." ".$extSichtbar." ORDER BY sortierung ASC";   
		
		$resMenuParent = DBi::$conn->query($query);
		$kinder[$row['id']] = DBi::mysql_num_row($resMenuParent);
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
			$class = 'class="'.$menu_class[3].'"'; 
		}
		if($level != '0') { 
			$class = 'class="'.$menu_class[4].'"'; 
		}
		$url .= $class;
		
		# Page Settings holen
		$page_settings = getPageSettings($row['id']);
		
		# Titel der Seite holen Link title Attribut
		$titel = getPageTitle($page_settings);			
		
		# URL mit absoluten Pfad 
		$url .= ' href="/'.getPathUrl($_SESSION["language"],$row['id']).'?pk_campaign=nav-mainmenu&pk_kwd=nav-mainmenu&pk_source=nav-mainmenu" target="'.$row['target'].'" title="'.$titel.'">  </strong>';
		
		# Aktuelle Seite gleich Datensatz hervorheben
		if($_GET['page_id'] == $row['id']) {
			#$page_settings = getPageSettings($row['id']); 
			if(@$_SESSION['login'] == 1 && $page_settings['fertig_de'] == '100') {
				$url .= '<img alt="done" src="'.$_SESSION['domain_name'].'/images/page_done.png" border="0">';
			}
			$url .= '<strong>'.$convertMenue['text'].' <strong style="font-size:10px">('.$page_settings['visitors'].')</strong></strong></a>';
		} else {
			# Page Settings holen
			#$page_settings = getPageSettings($row['id']); 
			if(@$_SESSION['login'] == 1) {									
				#if ($page_settings['fertig_de'] == '100') {						
				#	$url .= '<img src="'.$_SESSION['domain_name'].'/images/page_done.png"  title="'.$titel.'" border="0">';
				#}
			}
			#echo $convertMenue['text'];
			$url .= $convertMenue['text'].'</a> <strong style="font-size:10px">('.number_format($page_settings['visitors'], 0, ',', '.').')</strong>';		
		}
			
		# Kein Inhalt
		if(@$_SESSION['login'] != '1') { 
			#$url='<bdo '.$class.'>'.$row['name_de'].'</bdo>'; 
		}
		
		# Externer Link im Men�
		if($row['exturl'] != '' && @$_SESSION['login'] != '1') {  
			$url='<a '.$class.' href="'.$row['exturl'].'" target="'.$row['target'].'">'.$row['name_de'].'</a>'; 
		}
		
		if($typ == 'menue') {
			$openli++;
			if($level == '0') { 
				$html .= '<li>'.$url;  	
			}
			
			if($level != '0') { 
				for($i=0; $i < $level; $i++) {
					$str .= ' ';					
				}
				$html .= $str.'<li>'.$url;	 
			} 
			
			if($kinder[$row['id']] > 0) { //4
				$opencon++;					
				$html .= "\n    <ul>\n"; 										
			} //4
			
			$html = menue_generator($row['menue_id'], $level+1,$html,$openli,$opencon);  

			if($openli > $level) { 
				$openli--;
			}	   
			
			if($opencon > $level) { 
				$html .= "\n   </ul>\n"; 															
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
			$html = menue_generator($row['menue_id'], $level+1,$html,$openli,$opencon,$typ,$page_id);  
		}

   } # END WHILE MENU GENERIEREN 

   return $html;
} 
function getMenueSelectbox($parent, $level, $eltern) {
 
   // retrieve all children of $parent 
   $result = DBi::$conn->query("SELECT * FROM menue_parent
		LEFT JOIN menue ON menue_parent.menue_id=menue.id
		WHERE menue_parent.parent_id=$parent && domain_id=".$_SESSION['domain_id'].""); 

   // display each child    
   while ($row = mysqli_fetch_array($result)) { 
		$html .= '<option value="'.$row['id'].'"'; if($row['id'] == $eltern) { $html .= ' selected="selected"'; } $html .= '>';
		for ($i=0;$i<$level;$i++) {
			$html .='..';
		};

		$html .= $row["name_".$_SESSION['domainLanguage']];
		$html .= '</option>';

		getMenueSelectbox($row['menue_id'], $level+1,$eltern,$html); 
	}
   return $html;
}
########################
# >> Holt die aktuelle Domain 
#
# RET: DomainArray
########################
function getDomain($page_id) {
	
	$strQuery = "SELECT * FROM menue JOIN domains ON menue.domain_id = domains.domain_id WHERE menue.id = '".$page_id."'"; 
	$res_domain = DBi::$conn->query($strQuery) or die(mysqli_error(DBi::$conn));	
	$data_domain = mysqli_fetch_assoc($res_domain);
	
	#print_r($data_domain);
	return $data_domain;
} 

function getDomainAry($domain_id) {
	
	$query ="SELECT *,domains.domain_id as d_id,shop_info.domain_id as d_info_id FROM domains JOIN shop_info ON domains.domain_id = shop_info.domain_id  WHERE  domains.domain_id='".$domain_id."' AND email_freischaltung='Y'";
	$resDomains = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
	$strDomain = mysqli_fetch_assoc($resDomains);
	
	#print_r($data_domain);
	return $strDomain;
} 


function convertUmlaute($text){
$text = utf8_decode($text);
   //DIE UMLAUTE WERDEN KONVERTIERT  /////
   $pattern1="/�/";
   $replace1="&#228;";
   $text=preg_replace($pattern1,$replace1, $text);
   $pattern2="/�/";
   $replace2="&#246;";
   $text=preg_replace($pattern2,$replace2, $text);
   $pattern3="/�/";
   $replace3="&#252;";
   $text=preg_replace($pattern3,$replace3, $text);
   $pattern1a="/�/";
   $replace1a="&#196;";
   $text=preg_replace($pattern1a,$replace1a, $text);
   $pattern2a="/�/";
   $replace2a="&#214;";
   $text=preg_replace($pattern2a,$replace2a, $text);
   $pattern3a="/�/";
   $replace3a="&#220;";
   $text=preg_replace($pattern3a,$replace3a, $text);
   $pattern4="/�/";
   $replace4="&#xDF;";
   $text=preg_replace($pattern4,$replace4, $text);
    $pattern4a="/�/";
   $replace4a="&#171;";
   $text=preg_replace($pattern4a,$replace4a, $text);
   $pattern5="/�/";
   $replace5="&#187;";
   $text=preg_replace($pattern5,$replace5, $text);
   //$pattern6="/'/";
   //$replace6="`";
   //$text=preg_replace($pattern6,$replace6, $text);

   return $text;
}

function menue_NameConvert($menue_id) {
	$query = "SELECT name_".$_SESSION['language']." FROM menue WHERE id='$menue_id'";	
	$resPageName = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));	
	$data = mysqli_fetch_assoc($resPageName);
	
	if(isset($data["name_de"])) {
		if(!empty($_SESSION['language'])) {
			if(strlen($data['name_'.$_SESSION['language']]) <= 1) { $data['name_'.$_SESSION['language']] = $data["name_de"]; }
		}
	}
	
  	#if(strlen($data["name_".$_SESSION['language']]) <= 1) { $data["name_".$_SESSION['language']] = $data["name_en"]; }  
	#echo $data["name_".$_SESSION['language']];
	
	if(isset($data["name_".$_SESSION['language']])) {
  		$menue['linktext'] 	= utf8_decode($data["name_".$_SESSION['language']]);
	} else { 
		$menue['linktext'] 	=  ''; 
	}

	if(isset($data["name_".$_SESSION['language']])) {
  		$menue['text'] 		= utf8_decode($data["name_".$_SESSION['language']]);
	} else { 
		$menue['text'] = ''; 
	}
	
	$sucheLink 		= array ( 'ä','&auml;','ü','&uuml;','ö','&ouml;','Ä','&Auml;','Ü','&Uuml;','Ö','&Ouml;','ß','&szlig;',' ' ); //15
	$ersetzeLink 	= array ( 'ae','ae','ue','ue','oe','oe','Ae','Ae','Ue','Ue','Oe','Oe','ss','ss','_' ); //15
	$sucheText 		= array ( 'ä','ü','ö','Ä','Ü','Ö','ß' ); //7
	$ersetzeText 	= array ( '&auml;','&uuml;','&ouml;','&Auml;','&Uuml;','&Ouml;','&szlig;' ); //7
	if(!empty($menue['linktext'])) {
		for ( $x = 0; $x < 15; $x++ ){ $menue['linktext'] =  str_replace ( $sucheLink[$x], $ersetzeLink[$x], $menue['linktext'] ); }
	}
	for ( $x = 0; $x < 7; $x++ ){ $menue['text']	 =  str_replace ( $sucheText[$x], $ersetzeText[$x], $menue['text'] ); }

	return $menue;   
}
/*
function menue_NameConvert($menue_id) {
	
	$menue = "";

	if (is_numeric($menue_id)) {

		$query = "SELECT name_".$_SESSION["language"]." FROM menue WHERE id='$menue_id'";	
		$resPageName = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		$data = mysqli_fetch_assoc($resPageName);
	 
	#	echo $menue_id.' - '.$data["name_".$_SESSION["language"]];
		if($menue_id != 0 && strlen($data["name_".$_SESSION["language"]]) <= 1) { $data["name_".$_SESSION["language"]] = $data["name_de"]; }
		  #if(strlen($data["name_".$_SESSION["language"]]) <= 1) { $data["name_".$_SESSION["language"]] = $data["name_en"]; }
	  
		#echo $data["name_".$_SESSION["language"]];
		if(!empty($data["name_".$_SESSION["language"]])) {
			$menue['linktext'] 	= utf8_decode($data["name_".$_SESSION["language"]]);
			$menue['text'] 		= utf8_decode($data["name_".$_SESSION["language"]]);
		}

		
		$sucheLink 		= array ( '�','&auml;','�','&uuml;','�','&ouml;','�','&Auml;','�','&Uuml;','�','&Ouml;','�','&szlig;',' ' ); //15
		$ersetzeLink 	= array ( 'ae','ae','ue','ue','oe','oe','Ae','Ae','Ue','Ue','Oe','Oe','ss','ss','_' ); //15
		$sucheText 		= array ( '�','�','�','�','�','�','�' ); //7
		$ersetzeText 	= array ( '&auml;','&uuml;','&ouml;','&Auml;','&Uuml;','&Ouml;','&szlig;' ); //7
	
		if(!empty($menue['linktext'])) {
			for ( $x = 0; $x < 15; $x++ ) { $menue['linktext'] =  str_replace ( $sucheLink[$x], $ersetzeLink[$x], $menue['linktext'] ); }
		}
		for ( $x = 0; $x < 7; $x++ ) { $menue['text']	 =  str_replace ( $sucheText[$x], $ersetzeText[$x], $menue['text'] ); }		

	}

	return $menue;
}
*/
function convertMenueName($menue_id) {

	$query = "SELECT name_".$_SESSION['domainLanguage']." FROM menue WHERE id='$menue_id'";
	$resErg = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
	$data = mysqli_fetch_array($resErg);
  	
	if(isset($data["name_".$_SESSION['domainLanguage']])) { 
		$data["name_".$_SESSION['domainLanguage']] = $data["name_de"]; 
		  
		$menue['linktext'] 	= utf8_decode($data["name_".$_SESSION['domainLanguage']]);
		$menue['name_de'] 		= utf8_decode($data["name_".$_SESSION['domainLanguage']]);
	} else {
		$menue['linktext'] = '';
		$menue['name_de'] = '';
	}
	
  	#if(strlen($data["name_".$_SESSION['domainLanguage']]) <= 1) { 
	#	$data["name_".$_SESSION['domainLanguage']] = $data["name_en"]; 
	#}

	
  
	$sucheLink 		= array ( '�','&auml;','�','&uuml;','�','&ouml;','�','&Auml;','�','&Uuml;','�','&Ouml;','�','&szlig;',' ' ); //15
	$ersetzeLink 	= array ( 'ae','ae','ue','ue','oe','oe','Ae','Ae','Ue','Ue','Oe','Oe','ss','ss','_' ); //15
	$sucheText 		= array ( '�','�','�','�','�','�','�' ); //7
	$ersetzeText 	= array ( '&auml;','&uuml;','&ouml;','&Auml;','&Uuml;','&Ouml;','&szlig;' ); //7

	for ($x = 0; $x <= 14; $x++){ 
		$menue['linktext'] =  str_replace ($sucheLink[$x], $ersetzeLink[$x], $menue['linktext']); 
	}
	
	for ($x = 0; $x <= 6; $x++){ 
		$menue['name_de']	 =  str_replace ($sucheText[$x], $ersetzeText[$x], $menue['name_de']); 
	}

	return $menue;   
}
###############################################
# >> Menüpfad holen 
###############################################
function getMenuePath($page,$bPlain=false) {

	$strTrenner[0] = '';
	$strTrenner[1] = '';
	$strTrenner[2] = '';
	$strTrenner[3] = '';
	$strTrenner[4] = '';
	$strTrenner[5] = '';
	if(is_numeric($page)) {

		$res4 = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM menue_parent WHERE menue_id='$page'"));
		$convertMenue = convertMenueName($res4['parent_id']);
		$convertMenue_tmp = $convertMenue;
		
		if(!isset($rootpath)) {
			$rootpath = '';
		}
		 
		#print_r($convertMenue);
		if($convertMenue['name_de'] != '') {	
				if($bPlain == true) {
					$MenuePath[4] = $convertMenue['name_de'];
					$strTrenner[4] = '-';
				} else {
					$MenuePath[4] = '<a property="item" typeof="WebPage" href="'.$rootpath.'/'.getPathUrl($_SESSION['domainLanguage'],$res4['parent_id']).'" title="'.    $convertMenue['name_de'].'"><span property="name">'.$convertMenue['name_de'].'</span></a>'; unset($convertMenue); 	
				}
		}
	
		if(isset($res4['parent_id'])) {

			$res3 = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM menue_parent WHERE menue_id='$res4[parent_id]'"));

			if(isset($res3['parent_id'])) { $convertMenue = convertMenueName($res3['parent_id']); }

			if($convertMenue['name_de'] != '') {	
				if($bPlain == true && strlen($convertMenue['name_de']) > 0) {
					$MenuePath[3] = $convertMenue['name_de'];
					$strTrenner[3] = '-';
				} else {
					$MenuePath[3] = '<a property="item" typeof="WebPage" href="'.$rootpath.'/'.getPathUrl($_SESSION['domainLanguage'],$res3['parent_id']).'" title="'.$convertMenue['name_de'].'"><span property="name">'.$convertMenue['name_de'].'</span></a>'; unset($convertMenue); 
				}
			}
		}
		
		if(isset($res3['parent_id'])) {
			$res2 = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM menue_parent WHERE menue_id='$res3[parent_id]'"));			
			
			if(isset($res2['parent_id'])) { $convertMenue = convertMenueName($res2['parent_id']); }


			if($convertMenue['name_de'] != '') {
				if($bPlain == true && strlen($convertMenue['name_de']) > 0) {
					$MenuePath[2] = $convertMenue['name_de'];
					$strTrenner[2] = '-';
				} else {
					$MenuePath[2] = '<a property="item" typeof="WebPage" href="'.$rootpath.'/'.getPathUrl($_SESSION['domainLanguage'],$res2['parent_id']).'" title="'.$convertMenue['name_de'].'"><span property="name">'.$convertMenue['name_de'].'</span></a>'; unset($convertMenue); 
				}
			}
		}

		if(isset($res2['parent_id'])) {

			$res1 = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM menue_parent WHERE menue_id='$res2[parent_id]'"));

			if(isset($res1['parent_id'])) { $convertMenue = convertMenueName($res1['parent_id']); }
			
			if($convertMenue['name_de'] != '') {
				if($bPlain == true && strlen($convertMenue['name_de']) > 0) {
					$MenuePath[1] = $convertMenue['name_de'];
					$strTrenner[1] = '-';
				} else {
					$MenuePath[1] = '<a property="item" typeof="WebPage" href="'.$rootpath.'/'.getPathUrl($_SESSION['domainLanguage'],$res1['parent_id']).'" title="'.$convertMenue['name_de'].'"><span property="name">'.$convertMenue['name_de'].'</span></a>'; unset($convertMenue); 
				}
			}
		}

		if(isset($res1['parent_id'])) {

			$res0 = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM menue_parent WHERE menue_id='$res1[parent_id]'"));

			if(isset($res0['parent_id'])) { $convertMenue = convertMenueName($res0['parent_id']); }						
			
			if($convertMenue['name_de'] != '') {
				if($bPlain == true && strlen($convertMenue['name_de']) > 0) {
					$MenuePath[0] = $convertMenue['name_de'];
					$strTrenner[0] = '-';
				} else {
					$MenuePath[0] = '<a property="item" typeof="WebPage" href="'.$rootpath.'/'.getPathUrl($_SESSION['domainLanguage'],$res0['parent_id']).'" title="'.$convertMenue['name_de'].'"><span property="name">'.$convertMenue['name_de'].'</span></a>'; unset($convertMenue); 
				}
			}
		}

		if(is_numeric($page)) {
			$convertMenue = convertMenueName($page);
			$path = getPathUrl($_SESSION['domainLanguage'],$res4['parent_id']);
			$MenuePath[5] = '<a property="item" typeof="WebPage" href="'.$path.'"><span property="name">'.$convertMenue['name_de'].'</span></a>';
		}

		 
		if($bPlain == true) {
			return utf8_encode($MenuePath[0].$strTrenner[0].$MenuePath[1].$strTrenner[1].$MenuePath[2].$strTrenner[2].$MenuePath[3].$strTrenner[3].$MenuePath[4].$strTrenner[4].$MenuePath[5]);
		} else {	
		
		if(isset($MenuePath[5])) {
			$gesPath = '<ol vocab="http://schema.org/" typeof="BreadcrumbList">';
		}		
		
		#$gesPath = $str." ";
		
		if(isset($MenuePath[0])) {
			$gesPath .= '<li property="itemListElement" typeof="ListItem">'.$MenuePath[0].' <meta property="position" content="6"></li>';
		}
		if(isset($MenuePath[1])) {
			$gesPath .= '<li property="itemListElement" typeof="ListItem">'.$MenuePath[1];
			if(isset($MenuePath[2])) {
				$gesPath .= ' > ';
			}
			$gesPath .= ' <meta property="position" content="5"></li>';
		}
		if(isset($MenuePath[2])) {
			$gesPath .= '<li property="itemListElement" typeof="ListItem">'.$MenuePath[2];
			if(isset($MenuePath[3])) {
				$gesPath .= ' > ';
			}
			$gesPath .= ' <meta property="position" content="4"></li>';
		}
		if(isset($MenuePath[3])) {
			$gesPath .= '<li property="itemListElement" typeof="ListItem">'.$MenuePath[3];
			if(isset($MenuePath[4])) {
				$gesPath .= ' > ';
			}
			$gesPath .= ' <meta property="position" content="3"></li>';
		}
		if(isset($MenuePath[4])) {
			$gesPath .= '<li property="itemListElement" typeof="ListItem">'.$MenuePath[4];
			if(isset($MenuePath[5])) {
				$gesPath .= ' > ';
			}
			$gesPath .= ' <meta property="position" content="2"></li>';
		}
		if(isset($MenuePath[5])) {
			$gesPath .= '<li property="itemListElement" typeof="ListItem">'.$MenuePath[5].'<meta property="position" content="1"></li>';
		}

	
		#$gesPath = $str." ".$MenuePath[0].$MenuePath[1].$MenuePath[2].$MenuePath[3].$MenuePath[4].$MenuePath[5];
		#echo $gesPath.'A';
		
		if(isset($MenuePath[5])) {
			$gesPath .= '</ol>';
		}
			
			$pos = strpos($gesPath, '<a href=');
			#if ($pos != 0) {
				return utf8_encode($gesPath);
			#} else {
			#	return "";
			#}
		}
	}
}

########################################################
# >> getIsMarketPlacePageID
########################################################
function getIsMarketPlacePageID($marketplacepageid) {
	$query ="SELECT * FROM `shop_item` WHERE shopste_marktplatz_menue_id ='".$marketplacepageid."'";
	$resIsMarketPlace =  DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
	$strIsMarketPlace= mysqli_fetch_assoc($resIsMarketPlace);
	
	if ($strIsMarketPlace['shopste_marktplatz_menue_id'] != 0) {
		$strIsMarketPlace['isMarketPlace'] = true;
	}
	else {
		$strIsMarketPlace['isMarketPlace'] = false;
	}
	
	return $strIsMarketPlace;
}

function getGebuehrenTotal($preis) {
	
	$query ="SELECT * FROM `portal_abrechnung_gebuehr` WHERE von_preis <='".$preis."' AND bis_preis >='".$preis."'";
	$resGeb�hr =  DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
	$strGeb�hr = mysqli_fetch_assoc($resGeb�hr);
	
	$endsumme['gebuehr'] = ($preis / 100) * $strGeb�hr['gebuehr_prozent'];
	$endsumme['prozent'] = $strGeb�hr['gebuehr_prozent'];
	
	return $endsumme;
}
//Funktion deklarieren
function url_check($url) { 
        $hdrs = @get_headers($url); 
        return is_array($hdrs) ? preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/',$hdrs[0]) : false; 
}; 

function debug_message($message,$typ='output') {
	
	switch($typ) {
		case 'output':
			print_r($message);
			break;
		case 'email':
			break;
		default:
			print_r($message);
			break;
	}
}

function post_tweet($tweet_text) {

  // This lives at: https://github.com/themattharris/tmhOAuth
	require_once(dirname(__FILE__).'/../framework/twitter-api-php/TwitterAPIExchange.php');
	#echo "Tweet wird gepostet..";
 
  $settings = array(
    'oauth_access_token' => twitter_oauth_access_token,
    'oauth_access_token_secret' => twitter_oauth_access_token_secret,
    'consumer_key' => twitter_consumer_key,
    'consumer_secret' => twitter_consumer_secret
	);

$url = 'https://api.twitter.com/1.1/statuses/update.json';
$requestMethod = 'POST';

$postfields = array(
    'status' => $tweet_text
);
$twitter = new TwitterAPIExchange($settings);
#print_r('<pre>');
#print_r($twitter);
#print_r('</pre>');
#echo "Tweet wurde gepostet..<br/>";

 $twitter->buildOauth($url, $requestMethod)
    ->setPostfields($postfields)
    ->performRequest();
  
  return true;
} 

function tweet_get_rate_limit() {

	// This lives at: https://github.com/themattharris/tmhOAuth
	require_once(dirname(__FILE__).'/../framework/twitter-api-php/TwitterAPIExchange.php');
   
	$settings = array(
	  'oauth_access_token' => twitter_oauth_access_token,
	  'oauth_access_token_secret' => twitter_oauth_access_token_secret,
	  'consumer_key' => twitter_consumer_key,
	  'consumer_secret' => twitter_consumer_secret
	  );
  
	$url = 'https://api.twitter.com/1.1/application/rate_limit_status.json';
	$requestMethod = "GET";
	$getfield = '';

	$twitter = new TwitterAPIExchange($settings);

	
	$string = json_decode($twitter->setGetfield($getfield)
	->buildOauth($url, $requestMethod)
	->performRequest(),$assoc = TRUE);
		
	print_r($string);
} 

function utf8encodeArray($array)
{
        foreach($array as $key =>  $value)
        {
            if(is_array($value))
            {
                $array[$key] = utf8encodeArray($value);
            }
            elseif(!mb_detect_encoding($value, 'UTF-8', true))
            {
                $array[$key] = utf8_encode($value);
            }
        }
}

function encodeArray(array $array, string $sourceEncoding, string $destinationEncoding = 'UTF-8'): array
{
    if($sourceEncoding === $destinationEncoding){
        return $array;
    }

    array_walk_recursive($array,
        function(&$array) use ($sourceEncoding, $destinationEncoding) {
            $array = mb_convert_encoding($array, $destinationEncoding, $sourceEncoding);
        }
    );

    return $array;
}
?>