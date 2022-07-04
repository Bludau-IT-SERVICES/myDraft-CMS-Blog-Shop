<?php
session_start();
$path = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once($path.'/include/inc_basic-functions.php');
include_once($path.'/include/inc_config-data.php');
if(empty($strText)) {
	$strText = '';
}
#function getModule_rss_popular() {
	
	if(isset($_GET['submodus'])) {
		$dataTextHTML['typ'] = mysql_real_escape_string($_GET['submodus']);
	}
	if(empty($dataTextHTML['title_de'])) {
		$bNoTitle = true;
	}
#	echo $dataTextHTML['typ']."---";
	$timestamp = time();
	switch($dataTextHTML['typ']) {
		case 'MONTH':
			$query = "SELECT * FROM statistik WHERE content_modul='analyse_by_hits_all' AND content_group_typ='".$dataTextHTML['typ']."' AND content_group_by='".date("m/Y")."' AND menue_typ='rss_content' ORDER BY content_hits DESC LIMIT 0,".$dataTextHTML['limit'];
			if($bNoTitle == true) {
				$dataTextHTML['title_de'] = 'Beliebt diesen Monat';
			}
			break;
		case 'MONTH_LAST':
			$week = date("m");
			$week = $week -1;
			$typ = 'MONTH';
			$query = "SELECT * FROM statistik WHERE content_modul='analyse_by_hits_all' AND content_group_typ='".$typ."' AND content_group_by='".$week.date("/Y")."' ORDER BY content_hits DESC LIMIT 0,".$dataTextHTML['limit'];
			if($bNoTitle == true) {
				$dataTextHTML['title_de'] = 'Beliebt letzten Monat';
			}			
			break;			
		case 'DAY_24':
			/* $query = "SELECT *,sum(content_hits) as hits FROM statistik WHERE statistik.content_created_at  >= CURDATE() - INTERVAL 1 day GROUP BY menue_id ORDER BY statistik.content_hits DESC LIMIT 0,10";*/
			$date=date_create(date("d.m.Y",$timestamp));
			date_sub($date,date_interval_create_from_date_string("1 day"));			
			
			$query = "SELECT *,sum(content_hits) as hits FROM statistik WHERE statistik.content_created_at  >= '".date_format($date,"Y-m-d 00:00:00")."' AND menue_typ='rss_content' GROUP BY menue_id ORDER BY statistik.content_hits DESC LIMIT 0,".$dataTextHTML['limit'];

			if($bNoTitle == true) {
				$dataTextHTML['title_de'] = 'Letzten 24 Std.';
			}
			break;
		case 'DAY_48':
			/* $query = "SELECT *,sum(content_hits) as hits FROM statistik WHERE statistik.content_created_at  >= CURDATE() - INTERVAL 2 day GROUP BY menue_id ORDER BY statistik.content_hits DESC  LIMIT 0,10"; */
			$date=date_create(date("d.m.Y",$timestamp));
			date_sub($date,date_interval_create_from_date_string("2 days"));			
			
			$query = "SELECT *,sum(content_hits) as hits FROM statistik WHERE statistik.content_created_at  >= '".date_format($date,"Y-m-d 00:00:00")."' AND menue_typ='rss_content' GROUP BY menue_id ORDER BY statistik.content_hits DESC LIMIT 0,".$dataTextHTML['limit'];

			if($bNoTitle == true) {
				$dataTextHTML['title_de'] = 'Letzten 48 Std.';
			}		
			break;
		case 'MONTH_1':
			/* $query = "SELECT *,sum(content_hits) as hits FROM statistik WHERE statistik.content_created_at  >= CURDATE() - INTERVAL 1 MONTH GROUP BY menue_id ORDER BY statistik.content_hits DESC  LIMIT 0,10"; */

			$date=date_create(date("d.m.Y",$timestamp));
			date_sub($date,date_interval_create_from_date_string("1 Month"));		

			$query = "SELECT *,sum(content_hits) as hits FROM statistik WHERE statistik.content_created_at  >= '".date_format($date,"Y-m-d 00:00:00")."' AND menue_typ='rss_content' GROUP BY menue_id ORDER BY statistik.content_hits DESC  LIMIT 0,".$dataTextHTML['limit'];
			if($bNoTitle == true) {
				$dataTextHTML['title_de'] = 'Letzten 30 Tage';
			}				
			break;
		case 'DAY':
			$query = "SELECT * FROM statistik WHERE content_modul='analyse_by_hits_all' AND content_group_typ='".$dataTextHTML['typ']."' AND content_group_by='".date("Y-m-d")."'  AND menue_typ='rss_content' ORDER BY content_hits DESC LIMIT 0,".$dataTextHTML['limit'];
			if($bNoTitle == true) {
				$dataTextHTML['title_de'] = 'Heute beliebt';  
			}
			break;			
		case 'DAY_LAST':
			$day = date_create(date("Y-m-d"));
			date_sub($day, date_interval_create_from_date_string('1 day'));
			#echo date_format($day, 'Y-m-d');
			$typ = 'DAY';
			$query = "SELECT * FROM statistik WHERE content_modul='analyse_by_hits_all' AND content_group_typ='".$typ."' AND content_group_by='".date_format($day, 'Y-m-d')."'  AND menue_typ='rss_content' ORDER BY content_hits DESC LIMIT 0,".$dataTextHTML['limit'];
			#echo $query;
			
			if($bNoTitle == true) {
				$dataTextHTML['title_de'] = 'Gestern beliebt';
			}
			break;			
		case 'WEEK':		
		$query = "SELECT * FROM statistik WHERE content_modul='analyse_by_hits_all' AND content_group_typ='".$dataTextHTML['typ']."' AND content_group_by='".date("W/Y")."'  AND menue_typ='rss_content' ORDER BY content_hits DESC LIMIT 0,".$dataTextHTML['limit'];
			if($bNoTitle == true) {
				$dataTextHTML['title_de'] = 'Diese Woche beliebt';
			}
			break;			
		case 'WEEK_LAST':
			$week = (date("W") - 1);
			$typ = 'WEEK';
			
#echo "IN";
			$query = "SELECT * FROM statistik WHERE content_modul='analyse_by_hits_all' AND content_group_typ='".$typ."' AND content_group_by='".$week.'/'.date("Y")."'  AND menue_typ='rss_content' ORDER BY content_hits DESC LIMIT 0,".$dataTextHTML['limit'];
			#echo $query;
			if($bNoTitle == true) {
				$dataTextHTML['title_de'] = 'Letzte Woche beliebt';
			}			
			break;
		case 'YEAR':
			$query = "SELECT * FROM statistik WHERE content_modul='analyse_by_hits_all' AND content_group_typ='".$dataTextHTML['typ']."' AND content_group_by='".date("Y")."' AND menue_typ='rss_content' ORDER BY content_hits DESC LIMIT 0,".$dataTextHTML['limit'];
			if($bNoTitle == true) {
				$dataTextHTML['title_de'] = 'Diese Jahr beliebt';
			}			
			break;
		case 'YEAR_LAST':
			$year = (date("Y") - 1);
			$typ = 'YEAR';
			$query = "SELECT * FROM statistik WHERE content_modul='analyse_by_hits_all' AND content_group_typ='".$typ ."' AND content_group_by='".$year."'  AND menue_typ='rss_content' ORDER BY content_hits DESC LIMIT 0,".$dataTextHTML['limit'];
			if($bNoTitle == true) {
				$dataTextHTML['title_de'] = 'Letztes Jahr beliebt';
			}						
			break;
	} 


	#echo $query;
	$resTop = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));

	//$dataTextHTML['typ'] = 'stats';
	$bIn = false;
	while($strContent = mysqli_fetch_assoc($resTop)) {
		$bIn = true;

		$query = "SELECT sum(score) as ges, count(*) as anzahl  FROM seiten_bewertung where seiten_id='".DBi::mysql_escape($strContent['menue_id'],DBi::$conn)."'";
		$res2 = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));	
		$strBewertung = mysqli_fetch_assoc($res2);
		
		if($strBewertung['anzahl'] > 0) {
			$score = $strBewertung['ges'] / $strBewertung['anzahl'];					
		} else {
			$score = 0.0;
		}
		
		# Mit Inhalt vorhanden 
		if(!empty($strContent['name'])) {
			if($dataTextHTML['typ'] == 'DAY_24' || $dataTextHTML['typ'] == 'DAY_48' || $dataTextHTML['typ'] == 'MONTH_1' ) {
				$strField =  'hits';
			} else {
				$strField =  'content_hits';				
			}
			$strText .= '<li class="menue_side_item"><a href="'.str_replace("CORE_SERVER_DOMAIN_METHOD","",$strContent['http_link']).'?pk_campaign=nav-menu-stats&pk_kwd=nav-menu-stats-'.$dataTextHTML['typ'].'&pk_source=nav-menu-stats" title="'.$strContent['name'].'"><strong>➥ '.$strContent['name'].'</strong></a> <font size="1">('.$strContent[$strField].')</font>'.$strRating.'</li>';  
		}
	}
	 
	if($bIn == false) {
		$strText .= "Keine Daten vorhanden.";
	}
	if($_GET['bAjax'] == "true") {
		echo $strText;
	}
	if(empty($dataTextHTML['title_de'])) {
		$dataTextHTML['title_de'] = '';
	}
	$strTitel = $dataTextHTML['title_de'];
#	return $strContent;
#}
?> 