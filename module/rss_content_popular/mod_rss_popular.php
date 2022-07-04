<?php
session_start();
$path = realpath($_SERVER["DOCUMENT_ROOT"]);
include_once($path.'/include/inc_basic-functions.php');
include_once($path.'/include/inc_config-data.php');

#function getModule_rss_popular() {
	
	if(isset($_GET['submodus'])) {
		$dataTextHTML['typ'] = mysql_real_escape_string($_GET['submodus']);
	}
	
	switch($dataTextHTML['typ']) {
		case 'MONTH':
			$query = "SELECT count(*) anzahl,page_id,monthname(ANY_VALUE(menue_visitors.created_at)),menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id WHERE content_type='rss_content' AND showRSS_FeedContent = 'Y' group by page_id,YEAR(menue_visitors.created_at),month(menue_visitors.created_at) ORDER BY YEAR(menue_visitors.created_at) DESC , month(menue_visitors.created_at) DESC, anzahl DESC LIMIT 0,10";
			break;
			
		case 'DAY':
			#$query = "SELECT count(*) anzahl,page_id,dayname(menue_visitors.created_at),menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id WHERE content_type='rss_content' group by page_id,YEAR(menue_visitors.created_at),DAY(menue_visitors.created_at) ORDER BY anzahl DESC, YEAR(menue_visitors.created_at) ASC LIMIT 0,10";
			$query = "SELECT DATE(ANY_VALUE(menue_visitors.created_at)), count(*) anzahl,ANY_VALUE(page_id),menue.* FROM menue_visitors LEFT JOIN menue ON menue.id = menue_visitors.page_id WHERE menue_visitors.created_at >= CURDATE() AND showRSS_FeedContent = 'Y' AND content_type='rss_content' GROUP BY page_id ORDER BY anzahl DESC LIMIT 0,10";
			break;			
		case 'WEEK':
			$query = "SELECT count(*) anzahl,ANY_VALUE(page_id),CONCAT(YEAR(ANY_VALUE(menue_visitors.created_at)), '/', WEEK(ANY_VALUE(menue_visitors.created_at))) AS week_name, YEAR(ANY_VALUE(menue_visitors.created_at)), WEEK(ANY_VALUE(menue_visitors.created_at)),menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id WHERE content_type='rss_content' AND showRSS_FeedContent = 'Y' group by week_name,page_id ORDER BY YEAR(ANY_VALUE(menue_visitors.created_at)) DESC, WEEK(ANY_VALUE(menue_visitors.created_at)) DESC,anzahl DESC LIMIT 0,10";
			break;
		case 'YEAR':
			$query = "SELECT count(*) anzahl,ANY_VALUE(page_id),ANY_VALUE(YEAR(menue_visitors.created_at)) AS jahr,menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id WHERE content_type='rss_content' AND showRSS_FeedContent = 'Y' group by page_id ORDER BY anzahl DESC,ANY_VALUE(YEAR(menue_visitors.created_at)) ASC LIMIT 0,10";
			break;
	} 


	#echo $query;
	$resTop = DBi::$conn->query($query) or die(mysqli_error());

	$dataTextHTML['typ'] = 'rss_content_popular';
	$bIn = false;
	while($strContent = mysqli_fetch_assoc($resTop)) {
		$bIn = true;
					
		# Mit Inhalt vorhanden 
		if(!empty($strContent['name_de'])) {
#echo "IN";
			
			$path = getPathUrl($_SESSION['language'],$strContent['id']);
			$strLink = CORE_SERVER_DOMAIN.$path;  
			
			$strText .= '<li class="menue_side_item"># <a href="'.$strLink.'" title="'.$strTitel_link.'">'.$strContent['name_de'].'</a> ('.$strContent['anzahl'].')</li>'; 
		}
	}
	
	if($bIn == false) {
		echo "Keine Daten vorhanden.";
	}
	if($_GET['bAjax'] == "true") {
		echo $strText;
	}
	$strTitel = $dataTextHTML['title_de'];
#	return $strContent;
#}
?> 