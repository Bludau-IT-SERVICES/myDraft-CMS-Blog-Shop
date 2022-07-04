<?php 
		
	

####################################
# >> LoadModul_portal_umkreis Modul 
####################################
function LoadModul_portal_umkreis($config) {

		$dataTextHTML = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_portal_umkreis WHERE id=".$config['modul_id']));
		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='portal_umkreis'"));
		#echo "IN";
		
		$dataTextHTML['typ'] = 'portal_umkreis';
		
		$text = '<div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
		
		$text .= convertUmlaute($dataTextHTML["content_".$_SESSION['language']]);
		$titel = convertUmlaute($dataTextHTML["title_".$_SESSION['language']]);
		
			$query = "SELECT * from domains WHERE domain_id='".$_SESSION['domain_id']."'";			
			$resDomainData = DBi::$conn->query($query) or die(mysqli_error());
			$domain_pages = mysqli_fetch_assoc($resDomainData);
		
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
 
		$text .= '<h2>Shopste.com Umkreissuche</h2>';
		
		$text .= 'Bitte tragen Sie Ihre Postleitzahl oder Stadt ein:';
		
		$text .= '<form name="frmPortalUmkreis" id="frmPortalUmkreis" action="/api.php" method="POST" onSubmit="return portal_umkreis_suche(\'frmPortalUmkreis\',\''.$config['typ'].'\',\''.$config['modul_id'].'\');">';
		#echo $dataTextHTML['suche_letzte_anzahl'];
		$query = "SELECT * FROM suche_shopverzeichnis WHERE freigeschaltet='Y' ORDER BY updated_at DESC LIMIT 0,".mysql_real_escape_string($dataTextHTML['suche_letzte_anzahl']);
		$resSuchanfragen = DBi::$conn->query($query) or die(mysqli_error());
		$iCount = 0 ;
		while($strSuchanfragen = mysqli_fetch_assoc($resSuchanfragen)) {
			#$strPath = str_replace('/Suche/'.urlencode($strSuchanfragen['suchanfrage']),'',$_SERVER['REQUEST_URI']);
			#$strPath = $strPath.'Suche/'.urlencode($strSuchanfragen['suchanfrage']);
			$query = "SELECT * FROM menue JOIN domains ON menue.domain_id = domains.domain_id WHERE menue.id = '".$_SESSION['page_id']."'";
			$resDomain = DBi::$conn->query($query) or die(mysqli_error());
			$strDomain = mysqli_fetch_assoc($resDomain);
			
			if($iCount == 0) {
				$text .= '<br/>Die letzten Suchanfragen: ';
			}
			$strPagePath = 'http://'.$strDomain['name'].'/'.getPathUrl($_SESSION['language'],$_SESSION['page_id']).'Suche/';
			$text .= '<a href="http://'.$strDomain['name'].'/'.getPathUrl($_SESSION['language'],$_SESSION['page_id']).'Suche/'.$strSuchanfragen['suchanfrage'].'/">'.$strSuchanfragen['suchanfrage'].'</a> ';
			$iCount++;
		}		
		if($iCount != 0) {
			$text .= '<br/>';
		}

		#$text .= '<h2>Letzte Suchanfragen</h2>';
		
		$text .= '<div class="label">Umkreis</div>';
		$text .= '<div><select id="portal_umkreis_km" name="portal_umkreis_km">
		<option value="20">20 KM</option>
		<option value="50">50 KM</option>
		<option value="100">100 KM</option></select></div>';
		$text .= '<div class="label">Stadt oder Postleitzahl</div>';
		if(!empty($_GET['suche'])) {
			$text .= "<h2>Sie suchen jetzt nach ".$_GET['suche']."</h2>";
		}
		$text .= '<div><input  type="text" value="'.$_GET['suche'].'" name="txtPortalUmkreisPLZ" id="txtPortalUmkreisPLZ"/>
		<span class="frm_portal_umkreis_info" id="frm_portal_umkreis_info"></span></div>';
		$text .= '<div style="clear:both"></div>';
		
		if (!empty($_GET['suche']) OR !empty($_POST['suche'])) {
			$ch = curl_init();

			if(empty($_POST['portal_umkreis_km'])) {
				$strUmkreisKM = "50";
			} else {
				$strUmkreisKM = $_POST['portal_umkreis_km'];
			}
			curl_setopt($ch, CURLOPT_URL,"http://shopste.com/api.php");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,
						"modus=portal_umkreis_plz&portal_plz=".urlencode($_GET['suche'])."&portal_umkreis_km=50");

			// in real life you should use something like:
			// curl_setopt($ch, CURLOPT_POSTFIELDS, 
			//          http_build_query(array('postvar1' => 'value1')));

			// receive server response ...
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$server_output = curl_exec ($ch);

			curl_close ($ch);
			#echo $server_output;
			
			if (!empty($server_output)) {
				
				if($_GET['suche'] != '') {	
					#############################################################
					# Suchenanfragen speichern 
					#############################################################
					$query3 ="SELECT count(*) as anzahl FROM suche_shopverzeichnis WHERE suchanfrage='".$_GET['suche']."' AND shop_cat_id='0'";
					$resSuchanfrageCount = DBi::$conn->query($query3) or die(mysqli_error());
					$strSuchanfrageCount = mysqli_fetch_assoc($resSuchanfrageCount);
					if($strSuchanfrageCount['anzahl'] == 0) {
						$query4 ="INSERT INTO suche_shopverzeichnis(suchanfrage,treffer,shop_cat_id) VALUES('".$_GET['suche']."','".$strItemsCount['anzahl']."','0')";	
						DBi::$conn->query($query4) or die(mysqli_error());
						
					} else {
						$query4 ="UPDATE suche_shopverzeichnis set suchanzahl=suchanzahl+1 WHERE suchanfrage='".$_GET['suche']."'";	
						DBi::$conn->query($query4) or die(mysqli_error());
					}
				}
				
				$strLinesCSV = explode("\n",$server_output);
				for($i=1; $i < count($strLinesCSV) -1; $i++) {
					$strOrteUmkreis = explode("|",$strLinesCSV[$i]);
					$strPLZ='';
					$strPLZ .= " OR plz LIKE '".$strOrteUmkreis[1]."%'";
					for($z=3; $z < count($strOrteUmkreis) -1; $z++) {
						$strPLZ .= " OR plz LIKE '".$strOrteUmkreis[$z]."%'";
					}
					#print_r($strOrteUmkreis);
					if(!empty($strOrteUmkreis[0])) {
						$text .= '<h2>'.$strOrteUmkreis[0].' <font size="1">'.$strOrteUmkreis[2].'km <a title="Nach Ort Filtern" href="'.$strPagePath.trim($strOrteUmkreis[0]).'/">Suchen...</a></font></h2>';
						$query = "SELECT * FROM shop_info JOIN domains ON shop_info.domain_id = domains.domain_id WHERE stadt LIKE '".$strOrteUmkreis[0]."%' ".$strPLZ;
						#echo $query.'<br/>';
						$resUmkreisProfil = DBi::$conn->query($query) or die(mysqli_error()); 
						$bTreffer = false;
						while($strUmkreisProfil = mysqli_fetch_assoc($resUmkreisProfil)) {
							$text .=  '<h3>'.$strUmkreisProfil['firma'].' aus '.$strUmkreisProfil['stadt'].'</h3><br/>';
							$text .=  '<a title="Online Shop" href="http://'.$strUmkreisProfil['name'].'">Online Shop '.$strUmkreisProfil['shop_name'].' besuchen</a><br/><br/>';
							$bTreffer = true;
						}
						if($bTreffer == false) {
							$text .=  'Jetzt <a href="http://shopste.com/de/3/Eigenen-Onlineshop-erstellen/Suche/'.$_GET['suche'].'/" title="Online-Shop erstellen">eigenen Online-Shop</a> in Ihrer Stadt erstellen.';
						}
					}
				}
			}
		}
		$text .= '<div><input type="hidden" name="modus" value="portal_umkreis_plz"/></div><br/>';
		$text .= '<div><input type="submit" class="button" name="btnSenden" value="Umkreis Suche"/></div><br/>';
		$text .= '<div style="clear:both"></div>';
		$text .= '</form>';
	 
		$text .= '</div>'; // config modus 

		
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 } 
 
 if($_POST['bAjax'] == 'Y') {
	include_once("../../include/inc_config-data.php");
	#$config['modul_id'] = $config['modul_id'];
	#echo $_POST['CSVdata'];
	$strLinesCSV = explode("\n",$_POST['CSVdata']);
	$iCount =0;
	for($i=1; $i < count($strLinesCSV) -1; $i++) {
		$strOrteUmkreis = explode("|",$strLinesCSV[$i]);
		$strPLZ='';
		$strPLZ .= " OR plz LIKE '".$strOrteUmkreis[1]."%'";
		for($z=3; $z < count($strOrteUmkreis) -1; $z++) {
			$strPLZ .= " OR plz LIKE '".$strOrteUmkreis[$z]."%'";
		}
		#print_r($strOrteUmkreis);
		if(!empty($strOrteUmkreis[0])) {
			echo '<h2>'.$strOrteUmkreis[0].'</h2>';
			$query = "SELECT * FROM shop_info JOIN domains ON shop_info.domain_id = domains.domain_id WHERE stadt LIKE '".$strOrteUmkreis[0]."%' ".$strPLZ;
			#echo $query.'<br/>';
			$resUmkreisProfil = DBi::$conn->query($query) or die(mysqli_error()); 
			$bTreffer = false;
			while($strUmkreisProfil = mysqli_fetch_assoc($resUmkreisProfil)) {
				echo '<h3>'.$strUmkreisProfil['firma'].' aus '.$strUmkreisProfil['stadt'].'</h3><br/>';
				echo '<a href="http://'.$strUmkreisProfil['name'].'">Online Shop '.$strUmkreisProfil['shop_name'].' besuchen</a><br/><br/>';
				$bTreffer = true;
				$iCount++;
			}
			if($bTreffer == false) {
				echo 'Erstellen Sie jetzt einen <a href="http://shopste.com/de/3/Eigenen-Onlineshop-erstellen/" title="Online-Shop erstellen">Online-Shop.</a>';
			}
		}
	}
	#echo $_POST['portal_plz'].'abc';
	if($_POST['portal_plz'] != '') {	
		#############################################################
		# Suchenanfragen speichern 
		#############################################################
		$query3 ="SELECT count(*) as anzahl FROM suche_shopverzeichnis WHERE suchanfrage='".$_POST['portal_plz']."'";
		$resSuchanfrageCount = DBi::$conn->query($query3) or die(mysqli_error());
		$strSuchanfrageCount = mysqli_fetch_assoc($resSuchanfrageCount);
		if($strSuchanfrageCount['anzahl'] == 0) {
			$query4 ="INSERT INTO suche_shopverzeichnis(suchanfrage,treffer,shop_cat_id) VALUES('".$_POST['portal_plz']."','".$iCount."','0')";	
			DBi::$conn->query($query4) or die(mysqli_error());
			
		} else {
			$query4 ="UPDATE suche_shopverzeichnis set suchanzahl=suchanzahl+1 WHERE suchanfrage='".$_POST['portal_plz']."'";	
			DBi::$conn->query($query4) or die(mysqli_error());
		}
	}
 }
 ?>