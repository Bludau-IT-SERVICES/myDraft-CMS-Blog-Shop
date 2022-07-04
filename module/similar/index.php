<?php 
session_start();

####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_similar($config) {

		$dataTextHTML = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM modul_similar WHERE id=".$config['modul_id']));	 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='similar'"));
		$dataTextHTML['typ'] = 'similar';
		
		$text = '<div class="content similar" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
	/*	$text .= "<script> 
		let options = {
			root: document.querySelector('.li_similar_normal'),
			rootMargin: '0px',
			threshold: 1.0
		}
		  
		let observer = new IntersectionObserver(callback, options);
		alert(observer);
		</script>";
*/
		# Eingeloggt 
		if (@$_SESSION['login'] == '1')  { 
			if($titel == '') { 
				$titel = "Kein Titel"; 
			} 
		} else {
			$titel = "Weitere Beiträge von Freie-Welt.eu"; 
		}
		
		switch($dataTextHTML['typ']) {
			case 'rss_content':
				$query = 'SELECT *,MATCH (AddText,AddTitel) AGAINST ("'.DBi::mysql_escape($dataTextHTML['suchwort'],DBi::$conn).'" IN NATURAL LANGUAGE MODE) 
AS score FROM modul_rss_content WHERE MATCH (AddText,AddTitel) AGAINST ("'.DBi::mysql_escape($dataTextHTML['suchwort'],DBi::$conn).'" IN NATURAL LANGUAGE MODE)  and domain_id='.$_SESSION['domain_id'].' LIMIT 0,12;';  
				break;
			default:
				$query = 'SELECT *,MATCH (AddText,AddText) AGAINST ("'.DBi::mysql_escape($dataTextHTML['suchwort'],DBi::$conn).'" IN NATURAL LANGUAGE MODE) 
AS score FROM modul_rss_content WHERE MATCH (AddText,AddText) AGAINST ("'.DBi::mysql_escape($dataTextHTML['suchwort'],DBi::$conn).'" IN NATURAL LANGUAGE MODE)  and domain_id='.$_SESSION['domain_id'].'  LIMIT 0,12;';
				break; 
			
		}
		
		$text .='<div class="row">';

		$query2 = "SELECT * FROM domains WHERE domain_id='".$_SESSION['domain_id']."'";
		$resDomain = DBi::$conn->query($query2) or die(mysqli_error(DBi::$conn));
		$strDomainLink = mysqli_fetch_assoc($resDomain);
		
		#echo $query;
		$resItems =  DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		$iCount = 1; 
		
		while($strItem = mysqli_fetch_assoc($resItems)) { 
		
			$path = strip_tags(getPathUrl($_SESSION['language'],$strItem['page_id']));
			$strLink = CORE_SERVER_DOMAIN.$path;

			if(strlen($strItem['AddText']) > 250) {
				$cutPos = strpos($strItem['AddText'],' ',250);
				$strVorschau = substr(strip_tags($strItem['AddText']),0,$cutPos);
			} else {
				$strVorschau =  strip_tags($strItem['AddText']);
			}			
			
			$text .= '<div class="li_similar_normal col-xs-6 col-md-4">';
			$text .= '<div class="box_similar"><p><a title="'.$strItem['AddTitel'].'" href="'.$strLink.'?pk_campaign=klick-similar&pk_kwd=nav-similar&pk_source=klick-similar"><h4>'.$strItem['AddTitel'].'</h4></a> <i>vom <time datetime="'.$strItem['AddDatum'].'">'.getDateDE($strItem['AddDatum']).'</time> '.round($strItem['score'],2).' Punkte</i>
			<br/>'.$strVorschau.'';
			$text .= '</p></div></div>';
			$iCount++;
		} 
		$text .= '</div>
		
		</div>'; // config modus 
		
		$result = array("title"=>'➤ '.$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ'],"box_design"=>"plain");

		return $result;
 } 
 ?>