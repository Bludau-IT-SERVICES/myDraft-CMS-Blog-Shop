<?php
	session_start();
	require_once('../include/inc_config-data.php');
	require_once('../include/inc_basic-functions.php');
	
	$_POST = mysql_real_escape_array($_POST);
	$_GET = mysql_real_escape_array($_GET);
	// Login überprüfen
	$chkCookie = admin_cookie_check();

	#echo $chkCookie;
	if($_SESSION['login'] == 1) {		
		$_SESSION['login'] = 1;
	} else {
		exit(0);
	}
	
	if(empty($_SESSION['system_shop_last_cat'])) {
		$_SESSION['system_shop_last_cat'] = 0;
	}
	
	if(isset($_GET['modus'])) {
		switch($_GET['modus']) {
			case 'check_all_feed':							
					# SimplePie Include!
					$ServerPathComplete = $_SERVER['DOCUMENT_ROOT']; 
					require_once($ServerPathComplete.'/framework/simplepie/autoloader.php');
				
					$feed = new SimplePie();
					$feed->set_cache_location($ServerPathComplete.'/cache/');
					#$feed->enable_order_by_date(true);
				
					$query = "SELECT * FROM modul_rss_quelle WHERE enabled='Y'";
					$resQuelleCheck = DBi::$conn->query($query);
					while($strQuellen = mysqli_fetch_assoc($resQuelleCheck)) {
						ob_flush(); 
						flush();
						#$rssFeedMulti[] = $strQuellen['rss_quelle'];
						$feed->set_feed_url($strQuellen['rss_quelle']);
						$feed->handle_content_type();

						try {
							$feed->init();
						} catch(Exception $ex) {  
							echo "<strong>ERR:Beim abrufen </strong> - ".$strQuellen['rss_quelle']." (".$strQuellen['id'].") - ".$ex.'<br/>';						
						}
						if ($feed->error()) {
							echo "<strong>ERR:Beim verarbeiten </strong> - ".$strQuellen['rss_quelle']." (".$strQuellen['id'].") - ".print_r($feed->error()).'<br/>';
						} else {							
							echo "Alles OK: -".$strQuellen['title_de']." vom ".getDateDE($strQuellen['title_de'])." - ".$strQuellen['rss_quelle'].'<br/>';
						}  

					}			
				break;
			
		}
		exit(0);
	}
	
	$strCat = rss_category(0,0,'',0,0,'api',$_SESSION['system_shop_last_cat']);
	#print_r($strCat);
	$strSplitCat_0 = explode("<br>",$strCat);
	$strHTML ='';
	$iGblCount = 1;
	$level = 0;
	$strHTML = '<ul><li>';
	for($i=0; $i < count($strSplitCat_0); $i++) {
		$strSplitCat = explode("|",$strSplitCat_0[$i]);
		#echo count($strSplitCat_0);
		#print_r($strSplitCat); 
		
		
		$strPath = getPathUrl($_SESSION['language'],$strSplitCat[5]);
		#print_r($strSplitCat);
		$strHTML .= $strSplitCat[2].' '.$level.'<br/>';
		if($strSplitCat[2] >= $level) {
			$strHTML .= '<ul>';
		}
		
		$strHTML .= '<li><h2><a href="/'.$strPath.'">'.$strSplitCat[3].'</a> ('.$strSplitCat[0].')</h2>'; 
		
		$query = "SELECT * FROM modul_rss_quelle WHERE rss_cat='".$strSplitCat[0]."'";
		$resQuelleProKategorie = DBi::$conn->query($query);
		while($strQuellenProKategorie = mysqli_fetch_assoc($resQuelleProKategorie)) {
			$strHTML .= $iGblCount.' - '.$strQuellenProKategorie['title_de'].' - <a href="'.$strQuellenProKategorie['rss_quelle'].'">'.$strQuellenProKategorie['rss_quelle'].'</a> - '.$strQuellenProKategorie['last_inserted_content_item_date'].'<br/>'; 
			$iGblCount++;
		}
		$strHTML .= '</li>';
		if($strSplitCat[2] >= $level) {
			$strHTML .= '</ul>';
			$level = $strSplitCat[2];
		}		
	} 
	$strHTML .= '</li></ul>';
	echo $strHTML;
?>