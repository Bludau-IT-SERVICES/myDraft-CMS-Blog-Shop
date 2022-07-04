<?php 
@session_start();
if(file_exists($_SERVER['DOCUMENT_ROOT'].'/include/inc_pagging.php')) {
	include_once($_SERVER['DOCUMENT_ROOT'].'/include/inc_pagging.php');
} else {
	include_once('../../../include/inc_pagging.php');
}




####################################
# >> RSS Kategorie anzeigen 
####################################
function setUpdateRSSList($config,$dataTextHTML,$titel) {

#print_r($config);
		if($config['searchText'] == '' && isset($config['searchText']) == false) {
			$LikeSuche ='';
		}  else {
			if($config['searchText'] != 'Bitte Suchbegriff eingeben') {
				$strWords = explode(" ",$config['searchText']);
				for($i=0; $i < count($strWords); $i++) {
					$LikeSuche .= " AddTitel LIKE '%".$strWords[$i]."%' AND";		
				}
			}
		}
		
		############################################################
		# Anzahl der Kategorie bestimmen
		############################################################
		if(isset($_SESSION['CORE_default_module_list_item_count'])) {
			if(is_numeric($_SESSION['CORE_default_module_list_item_count'])) {		 
				$iSizePerPage =	 $_SESSION['CORE_default_module_list_item_count'];
			} else {
				$iSizePerPage = 50;	 		 
			}  
		} else {
			$iSizePerPage = 50;	 
		}
 
		##########################################################
		# >> MYSQL Limit festlegen
		##########################################################
		if(isset($_GET['seite'])) {
			if($_GET['seite'] == 1) {
				$strLimitBy = ' LIMIT 0,'.$iSizePerPage;
			} else {
				#$strLimitBy = ' LIMIT '.($_GET['seite'] * 100).','.(($_GET['seite'] * 100) + 100);
				if($_GET['seite'] > 1) {
					$seite = ($_GET['seite'] -1);
				} else {
					$seite = ($_GET['seite']);
				}
				
				$strLimitBy = ' LIMIT '.($seite * $iSizePerPage).','.$iSizePerPage;
			}
		} else {
			$strLimitBy = ' LIMIT 0,'.$iSizePerPage;	
		}	

		$ids = getSubKategorie_rss($dataTextHTML['news_cat'],"",0); 
		
		
		switch($dataShopListe['orderby']) {
			case 'order_datum_asc':
				$query = "SELECT * FROM modul_rss_content WHERE ".$LikeSuche." (news_cat='".$dataTextHTML['news_cat']."' ".$ids.")   ORDER BY AddDatum DESC".$strLimitBy;
				break;
			case 'order_datum_desc':
				$query = "SELECT * FROM modul_rss_content WHERE ".$LikeSuche." (news_cat='".$dataTextHTML['news_cat']."' ".$ids.") ORDER BY AddDatum ASC".$strLimitBy;
				break;
			case 'order_preis_asc':
				$query = "SELECT * FROM modul_rss_content WHERE ".$LikeSuche." (news_cat='".$dataTextHTML['news_cat']."' ".$ids.") ORDER BY preis ASC".$strLimitBy;
				break;
			case 'order_preis_desc':
				$query = "SELECT * FROM modul_rss_content WHERE ".$LikeSuche." (news_cat='".$dataTextHTML['news_cat']."' ".$ids.")  ORDER BY preis DESC".$strLimitBy;
				break;
			default:
				$query = "SELECT *,modul_rss_category.page_id as cat_page_id,modul_rss_content.page_id as rss_page_id FROM modul_rss_content LEFT JOIN modul_rss_category ON modul_rss_content.news_cat = modul_rss_category.news_cat_id WHERE ".$LikeSuche." (news_cat='".$dataTextHTML['news_cat']."' ".$ids.") ORDER BY AddDatum DESC".$strLimitBy;
				break;
		}					

		if($config['searchText'] != '') {	
			$query2 = "SELECT count(*) as anzahl FROM modul_rss_content WHERE ".$LikeSuche." news_cat=".$dataTextHTML['news_cat']." ".$ids." ORDER BY updated_at DESC";
			#print_r($query2);
		
			$resItemsCount = DBi::$conn->query($query2) or die(mysqli_error());
			$strItemsCount = mysqli_fetch_assoc($resItemsCount); 
		
			#############################################################
			# Suchenanfragen speichern 
			#############################################################
			$query3 ="SELECT count(*) as anzahl FROM suche_anfragen WHERE suchanfrage='".$config['searchText']."' AND shop_cat_id='".$dataTextHTML['news_cat']."'";
			$resSuchanfrageCount = DBi::$conn->query($query3) or die(mysqli_error());
			$strSuchanfrageCount = mysqli_fetch_assoc($resSuchanfrageCount);
			if($strSuchanfrageCount['anzahl'] == 0) {
				# INSERT DELAYED 
				$query4 ="INSERT INTO suche_anfragen(suchanfrage,treffer,shop_cat_id,modul_typ) VALUES('".$config['searchText']."','".$strItemsCount['anzahl']."','".$dataTextHTML['news_cat']."','rss_categoryview')";	
				DBi::$conn->query($query4) or die(mysqli_error());
				
			} else {
				$query4 ="UPDATE suche_anfragen SET suchanzahl=suchanzahl+1,treffer='".$strItemsCount['anzahl']."',modul_typ='rss_categoryview' WHERE suchanfrage='".$config['searchText']."'";	
				DBi::$conn->query($query4) or die(mysqli_error());
			}
		}
		
		#$query = "SELECT * FROM modul_news_content WHERE Bereich ='".$dataTextHTML['news_cat']."' ORDER BY news_content_id DESC LIMIT 0,15";

		$query2 = "SELECT count(*) as anzahl FROM modul_rss_content WHERE ".$LikeSuche." (news_cat='".$dataTextHTML['news_cat']."' ".$ids.") ORDER BY AddDatum DESC";
		$tmp =  $query;
		#echo $query2;
		#print_r($dataTextHTML);
		$resItemsCount = DBi::$conn->query($query2) or die('ERR:0001: '.mysqli_error());
		$strItemsCount = mysqli_fetch_assoc($resItemsCount); 
		
		$text .='<input type="hidden" name="modul_id" id="modul_id" value="'.$config['modul_id'].'"/>
		<input type="hidden" name="news_cat" id="news_cat" value="'.$dataTextHTML['news_cat'].'"/>';
		
		
		#echo $ids.'TE';
		
		# News Quellen
		$ids_quellen = str_replace('news_cat','rss_cat',$ids); 
		$query_quellen = "SELECT count(*) as anzahl FROM modul_rss_quelle WHERE (rss_cat='".$dataTextHTML['news_cat']."' ".$ids_quellen.") ORDER BY created_at DESC";
		
		#echo $query_quellen;
		#print_r($dataTextHTML);
		$resItemsCount_quellen = DBi::$conn->query($query_quellen) or die('ERR:0002: '.mysqli_error());
		$strItemsCount_quellen = mysqli_fetch_assoc($resItemsCount_quellen); 
		
		$text .= '<strong>RSS-News Quellen:</strong> '.$strItemsCount_quellen['anzahl'].'x<br/>';
		$strCountQuellen = explode("OR",$ids);
		$text .= '<strong>Kategorien unterhalb von '.$titel.':</strong> '.(count($strCountQuellen)-1).'x<br/>';
		
		$url = str_replace("&","",$dataTextHTML['name_de']);
		$url = str_replace(" ","-",$url);
		
		$text .= '<strong>RSS Feed dieser '.$titel.' Kategorie: </strong><a title="RSS Feed'.$dataTextHTML['name_de'].'" href="'.CORE_SERVER_DOMAIN.CORE_RSS_CONTENT_HTTP_PATH.'/'.rawurlencode($url).'|'.$dataTextHTML['news_cat'].'"><img title="RSS Feed'.$dataTextHTML['name_de'].'" alt="RSS-Feed" src="/image/rss-small.png"/> '.$dataTextHTML['name_de'].'</a><br/>';
		
		$text .= '<div id="rss_categoryview_result_'.$config['modul_id'].'"><br/>';

				
		#$text .= '###RSS_STATS###';
		#$text .= str_replace('###RSS_STATS###',$html_rss_stat,$text);
		
		###########################
		# Blättern einbinden
		###########################
		$strPagging .= getPageBrowse($strItemsCount['anzahl'],'rss_categorieview'); 
		$text .= $strPagging;	
		$text .= '</div>';
		$text .= '</div>'; 
		#$text .= '</div>';
		
		
		if(twitter_rss_feed_on_load == 'Y') {
					####################################################
					# >> Daten als RSS-Feed abrufen
					####################################################
					$path = dirname(__FILE__);
					require_once($path.'/../../framework/simplepie/autoloader.php');
					$feed = new SimplePie();
					
					$query = "SELECT * FROM modul_rss_quelle WHERE rss_cat='".$dataTextHTML['news_cat']."' and enabled='Y'";
					$resRSSQuelle = DBi::$conn->query($query) or die('ERR:0003: '.mysqli_error());
					$rssFeedMulti = array();
					$queryInsertBulk = 'INSERT INTO api_twitter_history(message) VALUES';
					$bValueSet = false;
					while($dataRSSFeed = mysqli_fetch_assoc($resRSSQuelle)) {		
						
						$rssFeedMulti[] = $dataRSSFeed['rss_quelle'];
					
					}	
					$feed->set_feed_url($rssFeedMulti);
					
					$feed->enable_order_by_date(true);
					$feed->set_cache_location($_SERVER['DOCUMENT_ROOT'] . '/cache');
					$feed->init();
					$iCount=0;
					foreach ($feed->get_items() as $item) {
							/*$text .= '<div class="item">
								<h2><a href="'.$item->get_permalink().'">'.$item->get_title().'</a></h2>
								<p>'.$item->get_description().'</p>
								<p><small>Datum: '.$item->get_date('j.m.Y | H:i').'</small></p>
							</div>';*/
							#$data = $item->get_item_tags('http://search.yahoo.com/mrss/', 'media');
							$iCount++;
							# Zusatzfelder mit SimplePie auslesen
							$data = $item->get_item_tags('http://search.yahoo.com/mrss/', 'group');  
							if (count($data) > 0) {
								$ytContent = $data[0]['child']['http://search.yahoo.com/mrss/']['content'];
								$ytEmbeddedfile = $ytContent[0]['attribs']['']['url'];
						
						$ytID = str_replace('http://www.youtube.com/watch?v=','',$ytEmbeddedfile);
						$ytID = str_replace('https://www.youtube.com/v/','',$ytID);
						$ytID_quelle = explode('?',$ytID);
						
						#print_r($ytEmbeddedfile);
						#echo '<iframe width="560" height="315" src="'.$ytEmbeddedfile.'" frameborder="0" allowfullscreen></iframe>';				
						$strContent = $item->get_description()."<div id=\"ytplayer_".$ytID_quelle[0]."\"></div>

			<script>
			  // Load the IFrame Player API code asynchronously.
			  var tag = document.createElement('script');
			  tag.src = \"https://www.youtube.com/player_api\";
			  var firstScriptTag = document.getElementsByTagName('script')[0];
			  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

			  var player;
			  function onYouTubePlayerAPIReady() {
				player = new YT.Player('ytplayer_".$ytID_quelle[0]."', {
				  height: '315',
				  width: '560',
				  videoId: '".$ytID_quelle[0]."'
				});
			  }
			</script>";
							} else {
								$strContent = $item->get_description();
							}
							$query ="SELECT count(*) as anzahl FROM modul_rss_content WHERE news_cat='".$dataTextHTML['news_cat']."' AND Webseite='".mysql_real_escape_string($item->get_permalink())."'";
							$resRSSContent = DBi::$conn->query($query) or die('ERR:0004: '.mysqli_error());
							$strRSSContentVorhaden = mysqli_fetch_assoc($resRSSContent);
							if ($strRSSContentVorhaden['anzahl'] > 0) {
								#$query ="UPDATE modul_rss_content SET AddTitel='".mysql_real_escape_string($item->get_title())."', AddText='".mysql_real_escape_string($item->get_description())."' WHERE Bereich='".$dataTextHTML['news_cat']."' AND Webseite='".mysql_real_escape_string($item->get_permalink())."'";
								#DBi::$conn->query($query) or die(mysqli_error());
								
							} else {
			 
								// Page Einstellugen Speichern
								$query = "INSERT INTO `menue` (`name_de`, `titel_de`, `sortierung`, `status_de`, `layout`,domain_id,content_type) VALUES ('".mysql_real_escape_string($item->get_title())."', '".mysql_real_escape_string($item->get_title())."', '0', 'unsichtbar', 'col2-right-layout','".$_SESSION['domain_id']."','rss_content');";
								$resInsert = DBi::$conn->query($query) or die('ERR:0005: '.$query.mysqli_error());
								$iPageID = mysql_insert_id();
								
								$tweetURLID = $iPageID;
								$strTweetText = $item->get_title();
								
								$query ="INSERT INTO modul_rss_content(Bereich,Webseite,AddTitel,AddText,AddDatum,page_id,domain_id,news_cat) VALUES('".mysql_real_escape_string($dataTextHTML['news_cat'])."','".mysql_real_escape_string($item->get_permalink())."','".mysql_real_escape_string($item->get_title())."','".mysql_real_escape_string($strContent)."','".$item->get_date('Y-m-d | H:i:s')."','".$iPageID."','".$_SESSION['domain_id']."','".$dataTextHTML['news_cat']."')";
								DBi::$conn->query($query) or die('ERR:0006: '.mysqli_error());  
								$iPageID2 = mysql_insert_id();
								$icat = $iPageID2;
							
								$_SESSION['system_shop_last_cat'] = mysql_real_escape_string($dataTextHTML['news_cat']);
								
								if(empty($_POST['shop_cat_id'])) {
									$_POST['shop_cat_id'] = '0';
								}
					 
								$query = "SELECT * FROM modul_rss_category WHERE news_cat_id='".mysql_real_escape_string($dataTextHTML['news_cat'])."'";
								$resNewsCat = DBi::$conn->query($query) or die('ERR:0007: '.mysqli_error());
								$strNewsCat = mysqli_fetch_assoc($resNewsCat);
								
								
								$query = "INSERT INTO `menue_parent` (`menue_id`, `parent_id`) VALUES (".$iPageID.", ".$strNewsCat['page_id'].");";
								$resInsert = DBi::$conn->query($query) or die('ERR:0008: '.mysqli_error());
								
						 
								
								// Modul Einstellugen Speichern
								$query = "INSERT INTO `modul_rss_content_view` (`title_de`, `menue_id`, `last_usr`,news_cat) VALUES ('".mysql_real_escape_string($item->get_title())."', ".$iPageID.", 0,'".$icat."');";
								$resInsert = DBi::$conn->query($query) or die('ERR:0009: '.mysqli_error());
								$iModulID = mysql_insert_id();
							
								// Modul auf einer Seite bekannt machen
								$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'rss_content_view', 'col-main', '0');";
								$resInsert = DBi::$conn->query($query) or die('ERR:0010: '.mysqli_error());
								
								
								// Kommentarfunktion
								$query = "INSERT INTO `modul_kommentar` (`title_de`, `menue_id`, `last_usr`,news_cat) VALUES ('Kommentiere zu ".mysql_real_escape_string($item->get_title())."', ".$iPageID.", 0,'".$icat."');";
								$resInsert = DBi::$conn->query($query) or die('ERR:0011: '.mysqli_error());
								$iModulID = mysql_insert_id();
							
								// Modul auf einer Seite bekannt machen
								$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'kommentar', 'col-main', '2');";
								$resInsert = DBi::$conn->query($query) or die('ERR:0012: '.mysqli_error());
								
								// Kommentarfunktion
								$query = "INSERT INTO `modul_similar` (`title_de`, `menue_id`, `last_usr`,suchwort,typ) VALUES ('Ähnliche Beiträge zu ".mysql_real_escape_string($item->get_title())."', ".$iPageID.", 0,'".mysql_real_escape_string($item->get_title())."','rss_content');";
								$resInsert = DBi::$conn->query($query) or die('ERR:0013: '.mysqli_error());
								$iModulID = mysql_insert_id();
							
								// Modul auf einer Seite bekannt machen
								$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'similar', 'col-main', '3');";
								$resInsert = DBi::$conn->query($query) or die('ERR:0014: '.mysqli_error());
								
								// Modul Einstellugen Speichern
								$query = "INSERT INTO `modul_menue` (`title_de`, `menue_id`, `last_usr`,typ,bAlphabetisch) VALUES ('Navigation', ".$iPageID.", 0, 'submenue', 'Y');";
								$resInsert = DBi::$conn->query($query) or die('ERR:0015: '.mysqli_error());
								$iModulID = mysql_insert_id();
							
								// Modul auf einer Seite bekannt machen
								$query = "INSERT INTO `module_in_menue` (`menue_id`, `modul_id`, `typ`,container,position) VALUES (".$iPageID.", ".$iModulID.", 'menue', 'col-right', '".$_POST['module_position']."');";			
								$resInsert = DBi::$conn->query($query) or die('ERR:0016: '.mysqli_error());
								
								// Tweet absetzten
								$path = getPathUrl($_SESSION['language'],$tweetURLID);				
								$strLink = 'https://'.$_SERVER['SERVER_NAME'].'/'.$path;
								
								if(twitter_rss_cron_post == 'Y') {
									if(twitter_rss_cron_post_send_later == 'N') {
										try {
											$strTweet_text_tmp = html_entity_decode($strTweetText); 
											if(strlen($strTweet_text_tmp) > 124) {		 
												$iLänge_url = strlen($strLink);
												$iLänge = strlen($strTweetText);
												$strTweet_text_tmp = substr($strTweet_text_tmp,0,124);
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
										if(strlen($strTweet_text_tmp) > 124) {		 
											$iLänge_url = strlen($strLink);
											$iLänge = strlen($strTweetText);
											$strTweet_text_tmp = substr($strTweet_text_tmp,0,124);
											$strTweet_text = $strTweet_text_tmp.' '.$strLink;
										} else {
											$strTweet_text = $strTweet_text_tmp.' '.$strLink;
										}	
										# Tweets für History sammeln.
										$queryInsertBulk .= " ('".mysql_real_escape_string($strTweet_text)."'),";
										$bValueSet = true;
									}
								}
							}
					 
					}

				#################################################
				# >> Alle Tweets in History speichern
				#################################################
				if(twitter_rss_cron_post_send_later == 'Y' && $bValueSet == true) {
						$queryInsertBulk = substr($queryInsertBulk,0,(strlen($queryInsertBulk) -1));
						#echo $queryInsertBulk;
						DBi::$conn->query($queryInsertBulk) or die('ERR:00111'.mysqli_error());
				}
		}			
		
		
		# Aus Der Datenbank abrufen
		#$query = "SELECT * FROM modul_rss_content WHERE Bereich ='".$dataTextHTML['news_cat']."' ORDER BY AddDatum DESC LIMIT 0,15";
		$query = $tmp ;
		$resNewsLatest = DBi::$conn->query($query) or die('ERR:0018: '.mysqli_error());
		$iCount = 0;
		while($strNewsLatest = mysqli_fetch_assoc($resNewsLatest)) {
		#print_r($query);
			$strPath = getPathUrl($_SESSION['language'],$strNewsLatest['cat_page_id']);
			
			$pathinfo = parse_url($strNewsLatest["Webseite"]);
			
			$text .= '<div class="block rss_latest" id="box_rss_latest">
			<div class="block-title"">';
				$text .= '<h1>'.$strNewsLatest['AddTitel'].'</h1>';
			$text .= '</div>';
			$text .= '<div class="content" id="modul_rss_categoryview_'.$strNewsLatest['news_content_id'].'">';
			
			if($dataTextHTML['gui_header_show_category'] == 'Y') {
				if($dataTextHTML['gui_header_show_category_link'] == 'Y') {
					$text .= '<a href="'.$strPath.'"><strong><img src="/images/ic_subdirectory_arrow_right_black_18dp.png">'.$strNewsLatest['name_de'].'</strong></a>';				
				} else {
					$text .= '<strong>'.$strNewsLatest['name_de'].'</strong>';									
				}
			}
			
			if($dataTextHTML['gui_header_show_date'] == 'Y') {
				$text .= ' vom <strong>'.getDateDE($strNewsLatest['AddDatum']).' ';				
			}
			
			if($dataTextHTML['gui_header_show_sources_link'] == 'Y') {
				
				if($dataTextHTML['gui_header_show_external_link'] == 'Y') {
					$text .= ' | </strong> <strong>Quelle </strong><a href="'.$pathinfo['scheme'].'://'.$pathinfo['host'].'" target="_blank"><strong>'.str_replace("www.","",$pathinfo['host']).'</strong></a>
					<strong> <a href="'.$strNewsLatest["Webseite"].'" target="_blank"><img src="/images/ic_share_black_18dp.png"></a></strong>';	
				} else {
					$text .= ' | </strong> <strong>Quelle </strong><a href="'.$pathinfo['scheme'].'://'.$pathinfo['host'].'" target="_blank"><strong>'.str_replace("www.","",$pathinfo['host']).'</strong></a>';						
				}
			}
			
			$text .= '<hr>';
			#print_r($strNewsLatest['AddText']);
			if(strlen($strNewsLatest['AddText']) >= 500) {
				if(strlen($strNewsLatest['AddText']) <= 500) {
					
					$iPos = strpos($strNewsLatest['AddText'],'>',550);				
				}
			} else {
				$iPos = 0;
			}
			#echo $iPos.'-';
			#$iPos2 = strpos(html_entity_decode($strNewsLatest['AddText']),'Patentamt',400);
			#if ($iPos2 > 1) {
				#exit;
			#}			
			#if($iPos > 0) {
				#echo $iPos;
			#	$text .= substr($strNewsLatest['AddText'],0,$iPos +1).'';
			#} else {				
			#	$text .= substr($strNewsLatest['AddText'],0,550);
			#}
			$text .= $strNewsLatest['AddText'];
			
			$path = getPathUrl($_SESSION['language'],$strNewsLatest['rss_page_id']);				
			$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
	
		if($dataTextHTML['gui_footer_rateing'] == 'Y') {
				$query = "SELECT sum(score) as ges, count(*) as anzahl  FROM seiten_bewertung where seiten_id='".DBi::mysql_escape($strNewsLatest['rss_page_id'],DBi::$conn)."'";
				$res2 = DBi::$conn->query($query) or die(mysqli_error());	
				$strBewertung = mysqli_fetch_assoc($res2);
				
				if($strBewertung['anzahl'] > 0) {
					$score = $strBewertung['ges'] / $strBewertung['anzahl'];					
				} else {
					$score = 0.0;
				}
				
				$text .= "<hr><div id=\"raty-content-vote-".$strNewsLatest['news_content_id']."\"><strong>Newsbewertung</strong> </div>
				<script>	
		 
				$.fn.raty.defaults.path = '../../framework/raty-2.7.0/demo/images';
			
			$('#raty-content-vote-".$strNewsLatest['news_content_id']."').raty({ 
			half: true, hints       : ['sehr schlechte Nachricht', 'schlechte Nachricht', 'OK Nachricht', 'gute Nachricht', 'sehr gut Nachricht'],
			score:".$score.",
			click: function(score, evt) {			
					setSaveScore_feed(".$strNewsLatest['page_id'].",score); 
			} 
			});	
			function setSaveScore_feed(page_id,score) {
			var ajax_load = '';
			//<img src='/image/load.gif' alt='loading...' />
				$('#raty-content-vote-".$strNewsLatest['news_content_id']."').html(ajax_load).load('/vote_save.php', 'page_id=' + page_id + '&score=' + score); 
			}
			</script>";
		}
	
			$text .= '<hr><h3><strong><a href="'.$strLink.'" class="link-red"><img src="/images/ic_share_black_18dp.png"> Weiterlesen</a></strong></h3>';
						

			
			$text .= '</div>';
			$text .= '';
			#$text .= '</div>';
			$text .= '</div>';
			$iCount++;

		}
		
		$text .= $strPagging;	
		$text .= '</div>';
			#$text .= '</div>';
	 
			#$text .= '</div>';
			#$text .= '</div>';
		
	return '<article>'.$text.'</article>';
}		
function LoadModul_rss_categoryview($config) {

		$dataTextHTML = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_rss_categoryview LEFT JOIN modul_rss_category ON modul_rss_categoryview.news_cat = modul_rss_category.news_cat_id WHERE id=".$config['modul_id']));
		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='rss_categoryview'"));
		#echo "IN";
		#print_r($dataTextHTML);
		$dataTextHTML['typ'] = 'rss_categoryview';
		
		$text = '<div class="content plain" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
				$text .= '<br/>
			<input type="text"  onclick="javascript:news_cat_search_intro()" onBlur="news_cat_search_intro_reset()" onkeypress="onEnterPortal_rss(event,'.$config['modul_id'].','.$dataTextHTML['news_cat'].')" id="txtQuickSearch" name="txtQuickSearch" placeholder="Bitte Suchbegriffe eintragen..." value="'.$strValueSuchen.'"/>';
		$text .= convertUmlaute($dataTextHTML["content_".$_SESSION['language']]);
		$titel = convertUmlaute($dataTextHTML["title_".$_SESSION['language']]);
		$text .= '<span class="button" style="margin-left:10px" onClick="rss_cat_search(\''.$config['modul_id'].'\',\''.$dataTextHTML['news_cat'].'\')" >Suchen</span><br/><br/>';

		
		if($text == '') {   
			$text = convertUmlaute($dataTextHTML["content_de"]); 
		} 
		#echo $dataTextHTML["id"];
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
#$text .= '</div>'; // config modus 		
		$text .= setUpdateRSSList($config,$dataTextHTML,$titel);
		
		
		
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ'],"box_design"=>"plain");

	  return $result;
 } 

if ($_GET['bAjaxLoad'] == "true") {
 	include_once('../../include/inc_config-data.php');
	include_once('../../include/inc_basic-functions.php');
	include_once('../../include/inc_pagging.php');
	$_POST = mysql_real_escape_array($_POST);
	$_GET  = mysql_real_escape_array($_GET);	
	
	if(CORE_PIWIK_ACTIVE == 'YES') {
			$path = realpath($_SERVER['DOCUMENT_ROOT']);  
			require_once ($path."/framework/piwik/PiwikTracker.php");
			if(isset($_GET['suchtext'])) {
				PiwikTracker::$URL = 'https://tsecurity.de/framework/piwik/';
				$t = new PiwikTracker( $idSite = 1 );
				$t->setTokenAuth(CORE_PIWIK_API_KEY);
				$t->doTrackPageView('TSEC-RSS-CATEGORY: Suche nach '.$config['searchText']." - ".$_GET['shop_cat_id']);
				$t->setIp($SERVER['REMOTEADDR']);
				
				if(isset($SERVER['HTTPREFERER'])) {
					$t->setUrl($SERVER['HTTPREFERER']);						
				}			
			} else {
				PiwikTracker::$URL = 'https://tsecurity.de/framework/piwik/';
				$t = new PiwikTracker( $idSite = 1 );
				$t->setTokenAuth(CORE_PIWIK_API_KEY);
				$t->doTrackPageView('TSEC-RSS-CATEGORY: Katgorie blättern'.$_GET['shop_cat_id']);
				$t->setIp($SERVER['REMOTEADDR']);
				
				if(isset($SERVER['HTTPREFERER'])) {
					$t->setUrl($SERVER['HTTPREFERER']);						
				}						
			}
	}		
	$config['modul_id'] = $_GET['modul_id'];
	$config['searchText'] = $_GET['suchtext'];
	$dataShopListe['news_cat'] = $_GET['shop_cat_id'];
	$dataShopListe['orderby'] = $_GET['orderby'];
	$dataShopListe['orderby_modus'] = $_GET['orderby_modus'];
	
	echo setUpdateRSSList($config,$dataShopListe,'');
 }
 ?>