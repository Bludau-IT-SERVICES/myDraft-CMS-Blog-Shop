<?php
//session_start();
//echo ini_get("memory_limit")."\n";
ini_set("memory_limit","556M");
//echo ini_get("memory_limit")."\n";
set_time_limit(0);
  
$ServerPathComplete = dirname(__FILE__);

require_once($ServerPathComplete.'/include/inc_config-data.php');
require_once($ServerPathComplete.'/include/inc_basic-functions.php');
require_once($ServerPathComplete.'/newsletter.php');
require_once($ServerPathComplete.'/libs/mysqli.php');
#echo $ServerPathComplete;
#DBi::$conn = new mysqli(HOST, USER, PASS, DB);

#DBi::$conn->query("SET NAMES 'utf8mb4'");
#DBi::$conn->query("SET CHARACTER SET 'utf8mb4'");

$_SESSION['domain_id'] ='1'; 
$_SESSION['language'] = 'de';
$_SESSION['domainLanguage'] = 'de';

if($_GET['modus'] == 'rate') {
	tweet_get_rate_limit();
}

if(isset($argv[1])) {
	$_GET['modus'] = $argv[1];
}

if(isset($argv[2])) {
	$_GET['api-key'] = $argv[2];
}
if(isset($argv[3])) {
	$_GET['modus_sub'] = $argv[3];
	$_POST['modus_sub'] = $argv[3];
}
	
if(isset($_GET['api-key'])) {
	if($_GET['api-key'] != CORE_CRON_API_KEY) {
		exit(0);
	}	
} else {
	exit(0);
}
function getDomain_id($menue_id) {
	$query = "SELECT * FROM menue WHERE id='".$menue_id."'";
	$strMenue = mysqli_fetch_assoc(DBi::$conn->query($query));
	return $strMenue['domain_id'];
}
function getSubKategorie_rss_all($iParrentCat,$strIDs,$level,$POST,$iCount_ges_rss_feeds,$iCountGes) {
	$ServerPathComplete = dirname(__FILE__); 
	global $iCount_ges_rss_feeds;
	global $level;
	global $iCountGes;
	$query = "SELECT * FROM modul_rss_category_parent LEFT JOIN modul_rss_category ON modul_rss_category_parent.id_news_category_parent=modul_rss_category.news_cat_id	WHERE modul_rss_category_parent.news_cat_parent=".$iParrentCat." AND modul_rss_category.domain_id='".$_SESSION['domain_id']."'  ORDER BY name_".$_SESSION['language']." ASC"; 
 
	$resCat = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
	$str ='';
	$striParrent = '';
	while($strCatMenue = mysqli_fetch_assoc($resCat)) {	
		 
		$strIDs = " OR rss_cat=".$strCatMenue['news_cat_id']; 
		$striParrent = $strCatMenue['news_cat_id'];
		#echo $strIDs; 
		$str = $strCatMenue['name_de']; 
		$text='';

	//	switch($POST) {
	//		case 'read_feeds':
				#$query ="SELECT * FROM modul_rss_category";
				#$resCategory = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
				$iCount =0;
				$iCountGes=0; 
 echo "IN-----";
print_r($POST);
				#$mask = "|%5s |%-30s | x |\n";

				# Tweet History
				$bValueSet = false;
				$queryInsertBulk = 'INSERT INTO api_twitter_history(message) VALUES';
				#$queryInsertBulk = substr($queryInsertBulk,0,(strlen($queryInsertBulk) -1));
				#echo $queryInsertBulk;
				#exit;

				#while($dataTextHTML = mysqli_fetch_assoc($resCategory)) {

					# SimplePie einbinden
				require_once($ServerPathComplete.'/framework/simplepie/autoloader.php');
				
				$feed = new SimplePie();
				$feed->set_cache_location($ServerPathComplete.'/cache/');
				$feed->enable_order_by_date(true);
				
				# Alle Quellen der News ID durchgehen
				# Fix: müsste Root Kategorien abbilden 
				$text .= '>> '.$strCatMenue['name_de']." (".$strCatMenue['news_cat_id'].")\r\n";
				$query = "SELECT * FROM modul_rss_quelle LEFT JOIN modul_rss_category ON modul_rss_category.news_cat_id = modul_rss_quelle.rss_cat WHERE rss_cat='".$strCatMenue['news_cat_id']."' and enabled='y'"; 
				#echo $query;
				$resRSSQuelle = DBi::$conn->query($query) or die('ERR:0001:'.mysqli_error(DBi::$conn)); 
				$rssFeedMulti = array();
				$iCount_feeds = 1;
				$twitter_hashtag = '';
				# Multifeed zusammenbauen
					while($dataRSSFeed = mysqli_fetch_assoc($resRSSQuelle)) {		
							
							$rssFeedMulti[] = $dataRSSFeed['rss_quelle'];
							$text .= "OK ".$iCount_ges_rss_feeds.'/'.$iCount_feeds.' '.$dataRSSFeed['title_de']." - (".$strCatMenue['news_cat_id'].") ".$dataRSSFeed['rss_quelle']."\r\n";
							$iCount_feeds++;
							$iCount_ges_rss_feeds++;
							$twitter_hashtag = $dataRSSFeed['twitter_tags'];
							#$strRSSQuelle_ary[] = $dataRSSFeed['rss_quelle']."\r\n";
  
					}	
					
					#print_r($rssFeedMulti);
					#jb debug
					#print_r($rssFeedMulti);
					$feed->set_feed_url($rssFeedMulti);
					try {
						$feed->init();
					} catch(Exception $ex) {
						echo "ERR:Beim verarbeiten".$ex;
					}
					$iCount =0;
					
					# Alle Elemente des Multifeed durchgehen
					foreach ($feed->get_items() as $item) {

							if($item->get_date('Y-m-d | H:i:s') == '') {
								$strDateAddFeed = date('Y-m-d H:i:s');
							} else if($item->get_date('Y-m-d | H:i:s') == '0000-00-00 00:00:00')  {
								$strDateAddFeed = date('Y-m-d H:i:s');
							}
							else {
								$strDateAddFeed = $item->get_date('Y-m-d H:i:s');
							}  
							
							# Zusatzfelder mit SimplePie auslesen
							$data = $item->get_item_tags('http://search.yahoo.com/mrss/', 'group');  
							$bIsYoutube = 'N';
							if(!is_null($data)){
								if (count($data) > 0) {
									$bIsYoutube = 'Y';
									$ytContent = $data[0]['child']['http://search.yahoo.com/mrss/']['content'];
									$ytEmbeddedfile = $ytContent[0]['attribs']['']['url'];
												$ytID = str_replace('http://www.youtube.com/watch?v=','',$ytEmbeddedfile);
								$ytID = str_replace('https://www.youtube.com/v/','',$ytID);
								$ytID_quelle = explode('?',$ytID);
								
								#print_r($ytEmbeddedfile);
								#echo '<iframe width="560" height="315" src="'.$ytEmbeddedfile.'" frameborder="0" allowfullscreen></iframe>';				
								$strContent = $item->get_description()."<div id=\"ytplayer_".$ytID_quelle[0]."\"></div>

					<script>
									$(document).ready(function() {
										onYouTubePlayerAPIReadyByID('".$ytID_quelle[0]."');
									}); 
									</script>";
								} else {
									$strContent = $item->get_description();
								}
							} else {
								$strContent = $item->get_description();
							}
											   
		
							#$strIDs2 = str_replace('rss_cat','news_cat',$strIDs);
							
							############################################################
							# >> Google News
							############################################################
							$pos = strpos($item->get_permalink(),"news.google.com");  
							
							echo $pos.' - '.$item->get_permalink().'<br/>';
							
							# Kein Google News
							if($pos === false) {
								$query ="SELECT count(*) as anzahl FROM modul_rss_content WHERE Bereich='".$strCatMenue['news_cat_id']."' AND (AddTitel='".substr(DBi::mysql_escape($item->get_title(),DBi::$conn),0,254)."' OR Webseite='".DBi::mysql_escape($item->get_permalink(),DBi::$conn)."')";							
							} else {
								$query ="SELECT count(*) as anzahl FROM modul_rss_content WHERE AddTitel='".substr(DBi::mysql_escape($item->get_title(),DBi::$conn),0,254)."'";															
							}
							
							#echo "$query \r\n\r\n"; 
							#echo "$item->get_date('Y-m-d | H:i:s') \r\n\r\n"; 
							
							$query = "SELECT count(*) as anzahl FROM modul_rss_content WHERE Webseite='".DBi::mysql_escape($item->get_permalink(),DBi::$conn)."'"; 
							
							# Daten abrufen 
							$resRSSContent = DBi::$conn->query($query) or die('ERR:0002:'.mysqli_error(DBi::$conn));
							$strRSSContentVorhanden = mysqli_fetch_assoc($resRSSContent);
														
							# Beitrag ist schon vorhanden
							if ($strRSSContentVorhanden['anzahl'] > 0) {
								#$query ="UPDATE modul_rss_content SET AddTitel='".DBi::mysql_escape($item->get_title())."', AddText='".DBi::mysql_escape($item->get_description())."' WHERE Bereich='".$dataTextHTML['news_cat']."' AND Webseite='".DBi::mysql_escape($item->get_permalink())."'";
								#DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
								
							} else {
								$iCount++;
								// Page Einstellugen Speichern
								$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id,content_type) VALUES ('".substr(DBi::mysql_escape($item->get_title(),DBi::$conn),0,254)."', '".substr(DBi::mysql_escape($item->get_title(),DBi::$conn),0,254)."', '0', 'unsichtbar', 'col2-right-layout','".$_SESSION['domain_id']."','rss_content');";
								$resInsert = DBi::$conn->query($query) or die('ERR:0003:'.mysqli_error(DBi::$conn));
								$iPageID = mysqli_insert_id(DBi::$conn);
								$tweetURLID = $iPageID;
								$strTwitterTitle = strip_tags($item->get_title());
								$strTwitterBeschreibung = strip_tags($item->get_description());
								$strTweetText = "➦ ".$strTwitterTitle."\n\n» ".substr($strTwitterBeschreibung,0,(280 - ((strlen($strTwitterTitle) - strlen($twitter_hashtag) + 1))))."\n \n ".$twitter_hashtag;
								
								$query ="INSERT INTO modul_rss_content(Bereich,Webseite,AddTitel,AddText,AddDatum,page_id,domain_id,news_cat,isYoutube) VALUES('".DBi::mysql_escape($strCatMenue['news_cat_id'],DBi::$conn)."','".DBi::mysql_escape($item->get_permalink(),DBi::$conn)."','".substr(DBi::mysql_escape($item->get_title(),DBi::$conn),0,254)."','".DBi::mysql_escape($strContent,DBi::$conn)."','".$strDateAddFeed."','".$iPageID."','".$_SESSION['domain_id']."','".$strCatMenue['news_cat_id']."','".$bIsYoutube."')";
								#echo 'Test:'.$item->get_date('Y-m-d | H:i:s');
								DBi::$conn->query($query) or die('ERR:0004:'.mysqli_error(DBi::$conn));  
								$iPageID2 = mysqli_insert_id(DBi::$conn);
								$icat = $iPageID2;
							
								$_SESSION['system_shop_last_cat'] = DBi::mysql_escape($strCatMenue['news_cat_id'],DBi::$conn);
								
								if(empty($POST['shop_cat_id'])) {
									$POST['shop_cat_id'] = '0';
								}
					 
								$query = "SELECT * FROM modul_rss_category WHERE news_cat_id='".DBi::mysql_escape($strCatMenue['news_cat_id'],DBi::$conn)."'";
								$resNewsCat = DBi::$conn->query($query) or die('ERR:0005:'.mysqli_error(DBi::$conn));
								$strNewsCat = mysqli_fetch_assoc($resNewsCat);
								
								
								$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID.", ".$strNewsCat['page_id'].");";
								$resInsert = DBi::$conn->query($query) or die('ERR:0006:'.mysqli_error(DBi::$conn));
								
								// Modul Stats Einstellugen Speichern
								if(twitter_shop_item_post_with_stats_day_48 == 'Y') {																
									$query = "INSERT INTO `modul_stats` (`title_de`, `menue_id`, `last_usr`,typ) VALUES ('Team Security beliebte IT Sicherheit letzte 48 Stunden', ".$iPageID.", 0,'DAY_48');";
									$resInsert = DBi::$conn->query($query) or die('ERR:0007:'.mysqli_error(DBi::$conn));
									$iModulID = mysqli_insert_id(DBi::$conn);
								
									// Modul auf einer Seite bekannt machen
									$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'stats', 'col-right', '0');";
									$resInsert = DBi::$conn->query($query) or die('ERR:0008a:'.mysqli_error(DBi::$conn));			 
								}
								
								if(twitter_shop_item_post_with_stats_day_30 == 'Y') {
									$query = "INSERT INTO `modul_stats` (`title_de`, `menue_id`, `last_usr`,typ) VALUES ('Team Security beliebte IT Sicherheit letzte 30 Tage', ".$iPageID.", 0,'MONTH_1');";
									$resInsert = DBi::$conn->query($query) or die('ERR:0007a:'.mysqli_error(DBi::$conn));
									$iModulID = mysqli_insert_id(DBi::$conn);
								
									// Modul auf einer Seite bekannt machen
									$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'stats', 'col-right', '1');";
									$resInsert = DBi::$conn->query($query) or die('ERR:0008:'.mysqli_error(DBi::$conn));			 								
								}
								
								// Modul Einstellugen Speichern
								$query = "INSERT INTO `modul_rss_content_view` (`title_de`, `menue_id`, `last_usr`,news_cat) VALUES ('".DBi::mysql_escape($item->get_title(),DBi::$conn)."', ".$iPageID.", 0,'".$icat."');";
								$resInsert = DBi::$conn->query($query) or die('ERR:0007:'.mysqli_error(DBi::$conn));
								$iModulID = mysqli_insert_id(DBi::$conn);
							
								// Modul auf einer Seite bekannt machen
								$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'rss_content_view', 'col-main', '0');";
								$resInsert = DBi::$conn->query($query) or die('ERR:0008:'.mysqli_error(DBi::$conn));
								
								// Ähnliche Artikel
								if(twitter_shop_item_post_with_similar == 'Y') {
									$query = "INSERT INTO `modul_similar` (`title_de`, `menue_id`, `last_usr`,suchwort,typ) VALUES ('Auch interessante Nachrichten ".DBi::mysql_escape($item->get_title(),DBi::$conn)."', ".$iPageID.", 0,'".DBi::mysql_escape($item->get_title(),DBi::$conn)."','rss_content');";
									$resInsert = DBi::$conn->query($query) or die('ERR:00011:'.mysqli_error(DBi::$conn));
									$iModulID = mysqli_insert_id(DBi::$conn);
								
									// Modul auf einer Seite bekannt machen
									$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'similar', 'col-main', '2');";
									$resInsert = DBi::$conn->query($query) or die('ERR:00012:'.mysqli_error(DBi::$conn));
								}

								// Kommentarfunktion
								if(twitter_shop_item_post_with_comments == 'Y') {																
									$query = "INSERT INTO `modul_kommentar` (`title_de`, `menue_id`, `last_usr`,news_cat) VALUES ('Kommentare über ".DBi::mysql_escape($item->get_title(),DBi::$conn)."', ".$iPageID.", 0,'".$icat."');";
									$resInsert = DBi::$conn->query($query) or die('ERR:0009:'.mysqli_error(DBi::$conn));
									$iModulID = mysqli_insert_id(DBi::$conn);
	   
									// Modul auf einer Seite bekannt machen
									$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'kommentar', 'col-main', '3');";
									$resInsert = DBi::$conn->query($query) or die('ERR:00010:'.mysqli_error(DBi::$conn));
								}
								
								// Modul MENÜ Einstellugen Speichern + CRON Schalter
								if(twitter_shop_item_post_with_menu == 'Y') {
									$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('Kategorien', ".$iPageID.", 0, 'submenue', 'Y');";
									$resInsert = DBi::$conn->query($query) or die('ERR:00013:'.mysqli_error(DBi::$conn));
									$iModulID = mysqli_insert_id(DBi::$conn);
								
									// Modul auf einer Seite bekannt machen
									$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,`container`,`position`) VALUES (".$iPageID.", ".$iModulID.", 'menue', 'col-right', 3);";	 
									#echo $query;
									$resInsert = DBi::$conn->query($query) or die('ERR:00014:'.mysqli_error(DBi::$conn));									
								}
								
								
								// Tweet absetzten 
								$path = getPathUrl($_SESSION['language'],$tweetURLID);				
								#$strLink = CORE_SERVER_DOMAIN.$path.'?utm_source=twitter&utm_medium=tweet&utm_campaign=website';
								$strLink = CORE_SERVER_DOMAIN.$path; 
								if(twitter_rss_cron_post == 'Y') {
									if(twitter_rss_cron_post_send_later == 'N') {
										try {
											$strTweet_text_tmp = html_entity_decode($strTweetText); 
											if(strlen($strTweet_text_tmp) > 280) {		 
												$iLänge_url = strlen($strLink);
												$iLänge = strlen($strTweetText);
												$strTweet_text_tmp = substr($strTweet_text_tmp,0,280);
												$strTweet_text = $strTweet_text_tmp.' '.$strLink;
											} else {
												$strTweet_text = $strTweet_text_tmp.' '.$strLink;
											}			 
											$connection = post_tweet($strTweet_text);
										} catch (Exception $e) {
											echo 'Exception abgefangen: ',  $e->getMessage(), "\n";
										}		
									} else {
										$strTweet_text_tmp = html_entity_decode($strTweetText); 
										if(strlen($strTweet_text_tmp) > 280) {		 
											$iLänge_url = strlen($strLink);
											$iLänge = strlen($strTweetText);
											$strTweet_text_tmp = substr($strTweet_text_tmp,0,280);
											$strTweet_text = $strTweet_text_tmp.' '.$strLink;
										} else {
											$strTweet_text = $strTweet_text_tmp.' '.$strLink;
										}									
										# Tweets für History sammeln.
										$queryInsertBulk .= " ('".DBi::mysql_escape($strTweet_text,DBi::$conn)."'),";
										$bValueSet = true;
									}
								}
							}
					 
					}
					
					$text .= 'Neue Beiträge: '.$iCount."\r\n\r\n";
					$iCountGes += $iCount;
				#}
					#################################################
					# >> Alle Tweets in History speichern
					#################################################
					if(twitter_rss_cron_post_send_later == 'Y' && $bValueSet == true) {
							$queryInsertBulk = substr($queryInsertBulk,0,(strlen($queryInsertBulk) -1));
							#echo $queryInsertBulk;
							DBi::$conn->query($queryInsertBulk) or die('ERR:001'.mysqli_error(DBi::$conn));
					}
					
					
	
					$strFirstText = 'Alle neuen Beiträge: '.$iCountGes."\r\n";
					
					$text = $strFirstText.$text;
				echo $text;	
			//	break;
				
		//	}		
		
		#######################################################		
		echo $str.'\r\n';
			if( $striParrent != '') {
				$strIDs = getSubKategorie_rss_all($striParrent,$strIDs,$level+1,$POST,$iCount_ges_rss_feeds,$iCountGes);
				echo "Alle Feeds gesammt: ".$iCount_ges_rss_feeds;
			}
	}

	return $strIDs;
	
}

// Standard Modus abrufen

if(isset($argv[1])) {
	switch($argv[1]) { 
		case 'read_feeds':
			$_POST['modus'] = 'read_feeds';
			$_POST['cron'] = 'Y';
			break;
		case 'tweet_feeds':
			$_POST['modus'] = 'tweet_feeds';
			break;
		case 'update_hits':
			$_POST['modus'] = 'update_hits';
			break;			
	}
}

if(!isset($_POST['modus'])) {
	
	if(isset($_GET['modus'])) {
		$_POST['modus'] = $_GET['modus'];	
	} else {
		$_POST['modus'] = 'read_feeds';		
	}
}

# Email Header
# --------------
$headers = "From: ".CORE_SERVER_PLATTFORM_NAME." <".CORE_MAIL_SEND_BCC.">\r\n"; 
  "X-Mailer: php\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";


switch($_POST['modus']) {
	case 'send_email_rss_content_daliy':
		set_send_email();
		break;
	case 'update_hits':		
			if(isset($_GET['api-key'])) {
				if($_GET['api-key'] != CORE_CRON_API_KEY) {
					exit(0);
				}	
			} else {
				exit(0);
			}
			echo 'Statistik Updaten (Liste): <a href="/cron.php?modus=update_hits&modus_sub=DAY&api-key='.$_GET['api-key'].'">Heute</a> | <a href="/cron.php?modus=update_hits&modus_sub=WEEK&api-key='.$_GET['api-key'].'">Woche</a> | <a href="/cron.php?modus=update_hits&modus_sub=MONTH&api-key='.$_GET['api-key'].'">Monat</a> | <a href="/cron.php?modus=update_hits&modus_sub=YEAR&api-key='.$_GET['api-key'].'">Jahr</a><br/<br/>';
			
			echo 'Hits Gesammt: <a href="/api.php?modus=acp_show_hits&modus_sub=DAY_HIT&api-key='.$_GET['api-key'].'">Hits Heute</a> | <a href="/api.php?modus=acp_show_hits&modus_sub=WEEK_HIT&api-key='.$_GET['api-key'].'">Hits Woche</a> | <a href="/api.php?modus=acp_show_hits&modus_sub=MONTH_HIT&api-key='.$_GET['api-key'].'">Hits Monat</a> | <a href="/api.php?modus=acp_show_hits&modus_sub=YEAR_HIT&api-key='.$_GET['api-key'].'">Hits Jahr</a><br/<br/><br/>';
		
			echo '<h3>Heute '.date("d.m.Y H:i:s").'</h3><br/>';
		
			switch($_GET['modus_sub']) {
				case 'MONTH':
					$query = "SELECT count(*) anzahl,page_id,monthname(menue_visitors.created_at),menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id group by page_id,YEAR(menue_visitors.created_at),month(menue_visitors.created_at) ORDER BY YEAR(menue_visitors.created_at) DESC , month(menue_visitors.created_at) DESC, anzahl DESC ,menue.created_at DESC LIMIT 0,50";
					break;
				case 'DAY':
					$query = "SELECT DATE(menue_visitors.created_at), count(*) anzahl,page_id,menue.* FROM menue_visitors LEFT JOIN menue ON menue.id = menue_visitors.page_id WHERE menue_visitors.created_at >= CURDATE() GROUP BY page_id ORDER BY  anzahl DESC LIMIT 0,50"; 
					break;			
				case 'WEEK':
					$query = "SELECT count(*) anzahl,page_id,CONCAT(YEAR(menue_visitors.created_at), '/', WEEK(menue_visitors.created_at)) AS week_name, YEAR(menue_visitors.created_at), WEEK(menue_visitors.created_at),menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id group by week_name,page_id ORDER BY YEAR(menue_visitors.created_at) DESC, WEEK(menue_visitors.created_at) DESC,anzahl DESC,menue.created_at DESC  LIMIT 0,50";
					break;
				case 'YEAR':
					$query = "SELECT count(*) anzahl,page_id,YEAR(menue_visitors.created_at) AS jahr,menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id  group by page_id ORDER BY anzahl DESC,YEAR(menue_visitors.created_at) ASC,menue.created_at DESC LIMIT 0,50";
					break;
				case 'DAY_HIT':
					$query = "SELECT count(*) anzahl FROM menue_visitors LEFT JOIN menue ON menue.id = menue_visitors.page_id WHERE menue_visitors.created_at >= CURDATE() ORDER BY anzahl DESC";
					break;
				case 'WEEK_HIT':
					$query = "SELECT count(*) anzahl,page_id,CONCAT(YEAR(menue_visitors.created_at), '/', WEEK(menue_visitors.created_at)) AS week_name, YEAR(menue_visitors.created_at), WEEK(menue_visitors.created_at),menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id group by week_name ORDER BY YEAR(menue_visitors.created_at) DESC, WEEK(menue_visitors.created_at) DESC,anzahl DESC";
					break;
				case 'MONTH_HIT':
					$query = "SELECT count(*) anzahl,page_id,monthname(menue_visitors.created_at) as month_name,menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id  group by YEAR(menue_visitors.created_at),month(menue_visitors.created_at) ORDER BY YEAR(menue_visitors.created_at) DESC , month(menue_visitors.created_at) DESC, anzahl DESC";
					break;
				case 'YEAR_HIT':
					$query = "SELECT count(*) anzahl,page_id,YEAR(menue_visitors.created_at) AS jahr,menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id group by YEAR(menue_visitors.created_at)  ORDER BY YEAR(menue_visitors.created_at) DESC,anzahl DESC";
					break;					
			} 
		$resTop = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		
		$dataTextHTML['typ'] = 'rss_content_popular';
		$text = '<div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'"><ul class="modul_menue_normal">';
		$iCount = 1;
		$iHitsGesamt = 0;
		   
		while($strContent = mysqli_fetch_assoc($resTop)) {
			
			switch($_GET['modus_sub']) {
				case 'MONTH':
					
					$path = getPathUrl($_SESSION['language'],$strContent['id']);
					$strLink = '/'.$path;
					
					if(!isset($strContent['created_at'])) {
						$strContent['created_at'] = date("Y-m-d H:i:s");
					}
					# 1. 0 = Bewertung
					
					$query = "SELECT count(*) as anzahl FROM statistik WHERE menue_id='".$strContent['id']."' AND content_group_by='".date("m/Y")."' AND content_modul='analyse_by_hits_all'";
					$strVorhanden = mysqli_fetch_assoc(DBi::$conn->query($query));
					
					if($strVorhanden['anzahl'] == 0) {
						#
						$query = "INSERT INTO statistik(name,http_link,content_created_at,content_hits,content_modul,content_bewertung,content_group_typ,content_group_by,menue_id,menue_typ,domain_id) VALUES('".DBi::mysql_escape($strContent['name_de'],DBi::$conn)."','".DBi::mysql_escape($strLink,DBi::$conn)."','".$strContent['created_at']."','".$strContent['anzahl']."','analyse_by_hits_all','0','MONTH','".date("m/Y")."','".$strContent['id']."','".$strContent['content_type']."','".$strContent['domain_id']."')";
						#echo $query;
						$res = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 						
					} else {
						$query = "UPDATE statistik SET content_hits='".$strContent['anzahl']."' WHERE menue_id='".$strContent['id']."' AND content_group_by='".date("m/Y")."' AND content_modul='analyse_by_hits_all'";
						#echo $query;
						DBi::$conn->query($query);
					}
					
					break;
				case 'DAY':
					
		 			$path = getPathUrl($_SESSION['language'],$strContent['id']);
					$strLink = '/'.$path;
					echo "out ";
					if(!isset($strContent['created_at'])) {
						$strContent['created_at'] = date("Y-m-d H:i:s");
					}
					# 1. 0 = Bewertung
					
					$query = "SELECT count(*) as anzahl FROM statistik WHERE menue_id='".$strContent['id']."' AND content_group_by='".date("Y-m-d")."' AND content_modul='analyse_by_hits_all'";
					$strVorhanden = mysqli_fetch_assoc(DBi::$conn->query($query));
					
					if($strVorhanden['anzahl'] == 0) {
						$query = "INSERT INTO statistik(name,http_link,content_created_at,content_hits,content_modul,content_bewertung,content_group_typ,content_group_by,menue_id,menue_typ) VALUES('".DBi::mysql_escape($strContent['name_de'],DBi::$conn)."','".DBi::mysql_escape($strLink,DBi::$conn)."','".$strContent['created_at']."','".$strContent['anzahl']."','analyse_by_hits_all','0','DAY','".date("Y-m-d")."','".$strContent['id']."','".$strContent['content_type']."')";						
						$res = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 						
					} else {
						$query = "UPDATE statistik SET content_hits='".$strContent['anzahl']."' WHERE menue_id='".$strContent['id']."' AND content_group_by='".date("Y-m-d")."' AND content_modul='analyse_by_hits_all'";
						DBi::$conn->query($query);
					}

					
					#$strContent['anzahl']
					
					break;			
				case 'WEEK':
					
					$path = getPathUrl($_SESSION['language'],$strContent['id']);
					$strLink = '/'.$path;
					
					if(!isset($strContent['created_at'])) {
						$strContent['created_at'] = date("Y-m-d H:i:s");
					}
					# 1. 0 = Bewertung
					
					$query = "SELECT count(*) as anzahl FROM statistik WHERE menue_id='".$strContent['id']."' AND content_group_by='".date("W/Y")."' AND content_modul='analyse_by_hits_all'";
					$strVorhanden = mysqli_fetch_assoc(DBi::$conn->query($query));
					
					if($strVorhanden['anzahl'] == 0) {
						$query = "INSERT INTO statistik(name,http_link,content_created_at,content_hits,content_modul,content_bewertung,content_group_typ,content_group_by,menue_id,menue_typ) VALUES('".DBi::mysql_escape($strContent['name_de'],DBi::$conn)."','".DBi::mysql_escape($strLink,DBi::$conn)."','".$strContent['created_at']."','".$strContent['anzahl']."','analyse_by_hits_all','0','WEEK','".date("W/Y")."','".$strContent['id']."','".$strContent['content_type']."')";						
						$res = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 						
					} else {
						$query = "UPDATE statistik SET content_hits='".$strContent['anzahl']."' WHERE menue_id='".$strContent['id']."' AND content_group_by='".date("W/Y")."' AND content_modul='analyse_by_hits_all'";  
						DBi::$conn->query($query);
					}
					
					break;
				case 'LAST_WEEK':
					
					$path = getPathUrl($_SESSION['language'],$strContent['id']);
					$strLink = '/'.$path;
					
					if(!isset($strContent['created_at'])) {
						$strContent['created_at'] = date("Y-m-d H:i:s");
					}
					# 1. 0 = Bewertung
					$week = (date("W") - 1);
					
					$query = "SELECT count(*) as anzahl FROM statistik WHERE menue_id='".$strContent['id']."' AND content_group_by='".$week.'/'.date("Y")."' AND content_modul='analyse_by_hits_all'";
					$strVorhanden = mysqli_fetch_assoc(DBi::$conn->query($query));
					
					if($strVorhanden['anzahl'] == 0) {
						$query = "INSERT INTO statistik(name,http_link,content_created_at,content_hits,content_modul,content_bewertung,content_group_typ,content_group_by,menue_id,menue_typ) VALUES('".DBi::mysql_escape($strContent['name_de'],DBi::$conn)."','".DBi::mysql_escape($strLink,DBi::$conn)."','".$strContent['created_at']."','".$strContent['anzahl']."','analyse_by_hits_all','0','WEEK','".date("W/Y")."','".$strContent['id']."','".$strContent['content_type']."')";						
						$res = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 						
					} else {
						$query = "UPDATE statistik SET content_hits='".$strContent['anzahl']."' WHERE menue_id='".$strContent['id']."' AND content_group_by='".date("W/Y")."' AND content_modul='analyse_by_hits_all'";  
						DBi::$conn->query($query);
					}
					
					break;					
				case 'YEAR':
					$path = getPathUrl($_SESSION['language'],$strContent['id']);
					$strLink = '/'.$path;
					
					if(!isset($strContent['created_at'])) {
						$strContent['created_at'] = date("Y-m-d H:i:s");
					}
					# 1. 0 = Bewertung
					
					$query = "SELECT count(*) as anzahl FROM statistik WHERE menue_id='".$strContent['id']."' AND content_group_by='".date("Y")."' AND content_modul='analyse_by_hits_all'";
					$strVorhanden = mysqli_fetch_assoc(DBi::$conn->query($query));
					
					if($strVorhanden['anzahl'] == 0) {
						$query = "INSERT INTO statistik(name,http_link,content_created_at,content_hits,content_modul,content_bewertung,content_group_typ,content_group_by,menue_id,menue_typ) VALUES('".DBi::mysql_escape($strContent['name_de'],DBi::$conn)."','".DBi::mysql_escape($strLink,DBi::$conn)."','".$strContent['created_at']."','".$strContent['anzahl']."','analyse_by_hits_all','0','YEAR','".date("Y")."','".$strContent['id']."','".$strContent['content_type']."')";						
						#echo $query;
						$res = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn)); 						
					} else {
						$query = "UPDATE statistik SET content_hits='".$strContent['anzahl']."' WHERE menue_id='".$strContent['id']."' AND content_group_by='".date("Y")."' AND content_modul='analyse_by_hits_all'";
						#echo $query;
						DBi::$conn->query($query);
					}					
					break;
				case 'DAY_HIT':
					$query = "SELECT count(*) anzahl FROM menue_visitors LEFT JOIN menue ON menue.id = menue_visitors.page_id WHERE menue_visitors.created_at >= CURDATE() ORDER BY anzahl DESC";
					break;
				case 'WEEK_HIT':
					$query = "SELECT count(*) anzahl,page_id,CONCAT(YEAR(menue_visitors.created_at), '/', WEEK(menue_visitors.created_at)) AS week_name, YEAR(menue_visitors.created_at), WEEK(menue_visitors.created_at),menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id group by week_name ORDER BY YEAR(menue_visitors.created_at) DESC, WEEK(menue_visitors.created_at) DESC,anzahl DESC";
					break;
				case 'MONTH_HIT':
					$query = "SELECT count(*) anzahl,page_id,monthname(menue_visitors.created_at) as month_name,menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id  group by YEAR(menue_visitors.created_at),month(menue_visitors.created_at) ORDER BY YEAR(menue_visitors.created_at) DESC , month(menue_visitors.created_at) DESC, anzahl DESC";
					break;
				case 'YEAR_HIT':
					$query = "SELECT count(*) anzahl,page_id,YEAR(menue_visitors.created_at) AS jahr,menue.* FROM menue_visitors JOIN menue ON menue_visitors.page_id = menue.id group by YEAR(menue_visitors.created_at)  ORDER BY YEAR(menue_visitors.created_at) DESC,anzahl DESC";
					break;					
			} 
			
			$strExtend ='';
			switch($_GET['modus_sub']) {
				case 'MONTH':
					break;
				case 'DAY':
					break;			
				case 'WEEK':
					break;
				case 'YEAR':
					break;
				case 'DAY_HIT':

					
					break;
				case 'WEEK_HIT':
				
					if(!isset($iOldAnzahl)) {
						$iOldAnzahl = 0;
					} else {
						$iProzent = 100 - ((100 / $iOldAnzahl) * $strContent['anzahl']);
						$strExtend = " Änderung: ".round($iProzent,2)."%";
						$iOldAnzahl = $strContent['anzahl'];
					}
						
					break;
				case 'MONTH_HIT':
					if(!isset($iOldAnzahl)) {
						$iOldAnzahl = 0;
					} else {
						$iProzent = 100 - ((100 / $iOldAnzahl) * $strContent['anzahl']);
						$strExtend = " Änderung: ".round($iProzent,2)."%";
						$iOldAnzahl = $strContent['anzahl'];
					}				
					break;
				case 'YEAR_HIT':
					if(!isset($iOldAnzahl)) {
						$iOldAnzahl = 0;
					} else {
						$iProzent = 100 - ((100 / $iOldAnzahl) * $strContent['anzahl']);
						$strExtend = " Änderung: ".round($iProzent,2)."%";
						$iOldAnzahl = $strContent['anzahl'];
					}				
					break;					
			} 
			
			if(strpos($_GET['modus_sub'],"_") === false) {		
				$path = getPathUrl($_SESSION['language'],$strContent['id']);
				$strLink = '/'.$path;
				
				$d=explode(" ",$strContent['created_at']);
				$d2=explode("-",$d[0]);
				
				if(count($d2) > 1) {
					$unix=mktime(0,0,0,$d2[1],$d2[2],$d2[0]);
					#echo $unix;
					#print_r($d2);
					$stamp=time();
					$diff=$stamp-$unix;
					$diff=$diff/86400;
					$strVergangen = 'Tage vergangen: '.floor($diff);
				} else {
					$strVergangen = '';
				}
				$iHitsGesamt += $strContent['anzahl'];				
				
				#echo 'Es sind '.floor($diff).' Tage seit dem '.$datum.' vergangen';				
				# Mit Link 
				if(!empty($strContent['name_de'])) {
					$text .= '<li class="menue_side_item">'.$iCount.' | '.$strVergangen.' | <a href="'.DBi::mysql_escape($strLink,DBi::$conn).'" title="'.$titel_link.'">'.$strContent['name_de'].'</a> ('.number_format($strContent['anzahl'],0,',','.').') '.$strExtend.'</li>'; 
				}
			} else {
				echo $strContent['jahr'].$strContent['month_name'].$strContent['week_name'].' - '.number_format($strContent['anzahl'],0,',','.').' '.$strExtend.'<br/>';
			}
			$iCount++;
		}
		
		$strEndText = 'Hits: '.$iHitsGesamt.'x';
		$strEndText .= $text;
		$strEndText .= '</ul></div>';
			
		echo $strEndText;
			break;				
			
	case 'set_search_request':
	 
		if($_GET['api-key'] != CORE_CRON_API_KEY) {
			if($_GET['status'] == 'Y') {
				$query = "UPDATE suche_anfragen SET freigeschaltet='Y' WHERE anfrage_id='".$_GET['anfrage_id']."'";				
			} else {
				$query = "UPDATE suche_anfragen SET freigeschaltet='N' WHERE anfrage_id='".$_GET['anfrage_id']."'";								
			}
			$res = DBi::$conn->query($query);
			echo "DONE";			
		} 

		break;
	case 'get_search_request':
		$query = "SELECT * FROM suche_anfragen WHERE  suche_anfragen.created_at >= CURDATE()  ORDER BY created_at DESC"; 
		#$query = "SELECT * FROM api_twitter_history WHERE  DATE(created_at) = DATE( DATE_SUB( NOW() , INTERVAL 1 DAY ) ) ODER BY created_at DESC"; 
		$resNews2Send = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		$strEmail ='<h1>Suchanfragen für '.CORE_SERVER_PLATTFORM_NAME.'</h1>';
		$bIn = "false";
		$iCount = 0;
		while($strNews2Send = mysqli_fetch_assoc($resNews2Send)) {
			$iCount = $iCount + 1;
			
			if($strNews2Send['freigeschaltet'] == 'Y') {
				$strLink = '<a href="'.CORE_SERVER_DOMAIN.'cron_rss_source_read.php?modus=set_search_request&status=N&anfrage_id='.$strNews2Send['anfrage_id'].'&api-key='.CORE_CRON_API_KEY.'">Deaktivieren</a>';
			} else {
				$strLink = '<a href="'.CORE_SERVER_DOMAIN.'cron_rss_source_read.php?modus=set_search_request&status=Y&anfrage_id='.$strNews2Send['anfrage_id'].'&api-key='.CORE_CRON_API_KEY.'">Akitvieren</a>';
			}
			
			$strEmail .= '
			Suchanfrage:'.$strNews2Send['suchanfrage'].' ('.$strNews2Send['anfrage_id'].') <br/>
			Modul:'.$strNews2Send['modul_typ'].'
			Treffer:'.$strNews2Send['treffer'].'<br/>
			Suchanzahl:'.$strNews2Send['suchanzahl'].'<br/>
			Freigeschaltet:'.$strNews2Send['freigeschaltet']." ".$strLink.
			'<hr width="100%"/>';
			   
			$bIn = "true";
		} 
		if($bIn == "true") {
			#$headers .= "Bcc: jbludau@bludau-media.de\r\n";
			echo "gesendet...";
			mail(CORE_MAIL_FROM_API,CORE_SERVER_PLATTFORM_NAME." Suchanfragen: ".$iCount,$strEmail,$headers); 		
		}	
		break;	
	case 'tweet_feeds':
	
		$query = "SELECT * FROM api_twitter_history WHERE bSend='N' ORDER BY created_at DESC LIMIT 0,".twitter_rss_post_count;
		$res = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		
		while($strTweetData = mysqli_fetch_assoc($res)) {
			try {
				$strTweet_text = $strTweetData['message'];
				$connection = post_tweet($strTweet_text);
				
				$query2 = "UPDATE api_twitter_history SET bSend='Y',Sended_count=Sended_count +1 WHERE api_twitter_history_id = '".$strTweetData['api_twitter_history_id']."'";
				DBi::$conn->query($query2) or die('ERR0002:'.mysqli_error(DBi::$conn));
				
				echo $strTweetData['message']."\r\n\r\n";
				
			} catch (Exception $e) {
				echo 'Exception abgefangen: ',  $e->getMessage(), "\n";
			}	
		}
		
		 
		break;
	case 'newsletter':
		
		$query = "SELECT * FROM api_twitter_history WHERE  api_twitter_history.created_at >= CURDATE()  ORDER BY created_at DESC"; 
		#$query = "SELECT * FROM api_twitter_history WHERE  DATE(created_at) = DATE( DATE_SUB( NOW() , INTERVAL 1 DAY ) ) ODER BY created_at DESC"; 
		$resNews2Send = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		$strEmail ='<h1>News von '.CORE_SERVER_PLATTFORM_NAME.'</h1>';
		$bIn = "false";
		while($strNews2Send = mysqli_fetch_assoc($resNews2Send)) {
			$strContent = explode("http",$strNews2Send['message']);
			$strEmail .= utf8_decode($strContent[0])."\r\nhttp".$strContent[1]."\r\n\r\n";
			$bIn = "true";
		} 
		if($bIn == "true") {
			#$headers .= "Bcc: jbludau@bludau-media.de\r\n";
			mail(CORE_MAIL_FROM_API,CORE_SERVER_PLATTFORM_NAME." Newsletter",$strEmail,$headers); 		
		}
		
		break;
	case 'hitsperhour':
		#WHERE `datetime` between '2012-01-01 00:00:00' and '2012-01-01 23:59:59'
		$query = "SELECT DATE(menue_visitors.created_at), count(*) anzahl,page_id,menue.* FROM menue_visitors LEFT JOIN menue ON menue.id = menue_visitors.page_id WHERE menue_visitors.created_at >= date_sub(NOW(), interval 1 hour) AND content_type='rss_content' GROUP BY page_id ORDER BY anzahl DESC LIMIT 0,25";
		$bIn = "false";
		$resNews2Send = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		while($strNews2Send = mysqli_fetch_assoc($resNews2Send)) {
			$path = getPathUrl('de',$strNews2Send['page_id']);
			$strEmail .= $strNews2Send['anzahl']."x - ".utf8_decode($strNews2Send['AddTitel'])." >> ".CORE_SERVER_DOMAIN.$path."\r\n\r\n";
			$strEmail2 = '<br/>'.$strNews2Send['anzahl']."x - ".utf8_decode($strNews2Send['AddTitel'])." >> ".CORE_SERVER_DOMAIN.$path;
			echo $strEmail2;
			$bIn = "true";
		}  
		
		if($bIn == "true") {
			#$headers .= "Bcc: jbludau@bludau-media.de\r\n";
			#mail("jbludau@bludau-media.de","Freie Welt Hitcounter",$strEmail,$headers); 		
		}
		
		break;
	default:  
	
		$text = "====================================\r\n";
		$text .= CORE_SERVER_PLATTFORM_NAME."\r\n";
		$text .= "====================================\r\n";
		echo $text;
		
		getSubKategorie_rss_all(0,'',0,$_POST,0,0);


/* 		$query = "SELECT * FROM api_twitter_history WHERE bSend='N' ORDER BY created_at DESC"; 
		$resNews2Send = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		$strEmail ='<h1>News von '.CORE_SERVER_PLATTFORM_NAME.'</h1>';
		$bIn = "false";
		while($strNews2Send = mysqli_fetch_assoc($resNews2Send)) {
			$strContent = explode("http",$strNews2Send['message']);
			$strEmail .= '<a href="http'.$strContent[1].'">'.$strContent[0]."</a><br>";
			$bIn = "true";
		} 
		if($bIn == "true") {
			mail(CORE_MAIL_FROM_API,"News Feed für ".CORE_SERVER_PLATTFORM_NAME,$strEmail,'Content-Type:text/html; charset="UTF-8"'); 		
		} */
}
mysqli_close(DBi::$conn); 

?>