<?php 
####################################
# >> RSS Content View Modul 
####################################
function LoadModul_rss_content_view($config) {
		
		$dataRSS_DB = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM modul_rss_content_view JOIN modul_rss_content ON modul_rss_content_view.news_cat = modul_rss_content.news_content_id WHERE id=".$config['modul_id']));	 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='rss_content_view'"));
	
		$dataRSS_DB['typ'] = 'rss_content_view';

		$text = '<article class="content rss_content_view" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';

		$text .= '<header><h1>'.$dataRSS_DB['AddTitel'].'</h1>';
  
		# Kategorie abrufen
		$query = "SELECT * FROM modul_rss_category WHERE news_cat_id='".$dataRSS_DB['news_cat']."'";
		$resKategorie = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
		$strKategorie = mysqli_fetch_assoc($resKategorie); 
		
		$pathinfo = parse_url($dataRSS_DB["Webseite"]);
		
		$strPath = getPathUrl($_SESSION['language'],$strKategorie['page_id']);

		if($dataRSS_DB['gui_header_show_date'] == 'Y') {
			$text .= ' <h3><time datetime="'.$dataRSS_DB['AddDatum'].'">'.getDateDE($dataRSS_DB['AddDatum']).'</time></h3><p>';
		}

		if($dataRSS_DB['gui_header_show_category'] == 'Y') {
			/*if($dataRSS_DB['gui_header_show_date'] == 'Y') {
				$text .= ' <strong><time datetime="'.$dataRSS_DB['AddDatum'].'">'.getDateDE($dataRSS_DB['AddDatum']).'</time></strong>';	  			
			}*/
			if($dataRSS_DB['gui_header_show_category_link'] == 'Y') {
				$text .= '<a title="'.$strKategorie['name_de'].'" href="'.$strPath.'"><strong><i class="fas fa-circle"></i> '.$strKategorie['name_de'].'</strong></a>';
			} else {
				$text .= '<strong>'.$strKategorie['name_de'].'</strong>';
			}
		}
		
		if($dataRSS_DB['gui_header_show_sources_link'] == 'Y') {
			if($dataRSS_DB['gui_header_show_external_link'] == 'Y') {
				$text .= ' <a title="Nachrichtenwebseite direkt öffnen" target="_blank" href="/weiterleitung.php?rss_id='.$dataRSS_DB["news_content_id"].'&page_id='.$dataRSS_DB['page_id'].'"><strong><i class="fas fa-external-link-alt"></i> '.str_replace("www.","",$pathinfo['host']).'</strong></a>
				<strong> <a title="Startseite der RSS-Quelle öffnen" target="_blank" href="'.$pathinfo['scheme'].'://'.$pathinfo['host'].'" target="_blank"><i class="fas fa-link"></i></a></strong>';	
			} else {
				$text .= ' <a title="Startseite der RSS-Quelle öffnen" target="_blank" href="'.$pathinfo['scheme'].'://'.$pathinfo['host'].'" target="_blank"><strong>'.str_replace("www.","",$pathinfo['host']).'</strong></a>';						
			}
		}
 

		$text .= '</p></header>';
		
		###################################################
		# <iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/BFUxzfGIuDY?controls=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		#################################################
		# >> Youtube Snipptet einbauen bei Youtube Daten
		#################################################
	#	$text .=$dataRSS_DB["AddText"];
	if(strrpos($dataRSS_DB["AddText"],"ytplayer_") > 0) {
		$dataRSS_DB["AddText"] = str_replace("$(document).ready(function() { onYouTubePlayerAPIReadyByID('","",$dataRSS_DB["AddText"]);
		$dataRSS_DB["AddText"] = str_replace('\');}); ','',$dataRSS_DB["AddText"]); 
		$dataRSS_DB["AddText"] = str_replace('<script>','',$dataRSS_DB["AddText"]); 
		$dataRSS_DB["AddText"] = str_replace('</script>','',$dataRSS_DB["AddText"]); 
		$dataRSS_DB["AddText"] = str_replace('<div id="ytplayer_','',$dataRSS_DB["AddText"]); 
		$youtube_code = $dataRSS_DB["AddText"];
		$dataRSS_DB["AddText"] = ' <iframe width="100%" height="600" src="https://www.youtube-nocookie.com/embed/'.$youtube_code.'?controls=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
		$text .= '<p>'.$dataRSS_DB["AddText"].'...</p>';
	} else {
		  $text .= '<p>'.$dataRSS_DB["AddText"].'...</p>';
	}
		$strPathSelf = CORE_SERVER_DOMAIN.getPathUrl($_SESSION['language'],$dataRSS_DB['page_id']);
		$text .= '		<div class="social-media">
		<h3>Sharing is caring</h3>	
		<div class="row">
			<div class="col-sm-4">
				<a href="https://www.reddit.com/submit?url='.$strPathSelf.'?mtm_campaign=reddit_21%26mtm_source=reddi%26mtm_medium=social&title='.substr($dataRSS_DB["AddTitel"],0,250).'" class="btn-social btn-reddit" title="reddit share" target="_blank" rel="noopener"><i class="fab fa-reddit-square"></i> Auf Reddit teilen</a>
			</div>
			<div class="col-sm-4">
				<a href="https://telegram.me/share/url?url='.$strPathSelf.'?mtm_campaign=telegram_21%26mtm_source=telegram%26mtm_medium=social&bodytext=&text='.strip_tags(substr($dataRSS_DB["AddTitel"],0,250)).'" class="btn-social btn-whatsapp" title="Telegram share" target="_blank" rel="noopener"><i class="fab fa-telegram-square"></i> Auf Telegram teilen</a>
			</div>
			<div class="col-sm-4">
				<a href="https://share.flipboard.com/bookmarklet/popout?v=2&url='.$strPathSelf.'?mtm_campaign=flipboard_21%26mtm_source=flipboard%26mtm_medium=social" class="btn-social btn-facebook" title="Flupboard" target="_blank" rel="noopener"><i class="fab  fa-flipboard-square"></i> Auf Flipboard teilen</a>
			</div>
			<div class="col-sm-4">
				<a href="https://www.linkedin.com/sharing/share-offsite/?url='.$strPathSelf.'?mtm_campaign=flipboard_21%26mtm_source=flipboard%26mtm_medium=social" class="btn-social btn-facebook" title="LinkedIn" target="_blank" rel="noopener"><i class="fab  fa-linkedin-square"></i> Auf LinkedIn teilen</a>
			</div>
			<div class="col-sm-4">
				<a href="https://www.xing.com/social/share/spi?url='.$strPathSelf.'?mtm_campaign=flipboard_21%26mtm_source=flipboard%26mtm_medium=social" class="btn-social btn-facebook" title="Xing" target="_blank" rel="noopener"><i class="fab  fa-xing-square"></i> Auf Xing teilen</a>
			</div>												
			<div class="col-sm-4">
				<a href="https://twitter.com/intent/tweet?text='.substr($dataRSS_DB["AddTitel"],0,250).'&url='.$strPathSelf.'?mtm_campaign=twitter_21%26mtm_source=twitter%26mtm_medium=social" class="btn-social btn-twitter" title="Twitter share" target="_blank" rel="noopener"><i class="fab fa-twitter-square"></i> Auf Twitter teilen</a>
			</div>
			<div class="col-sm-4">
				<a href="https://api.whatsapp.com/send?text='.strip_tags(substr($dataRSS_DB["AddTitel"],0,250)).' '.$strPathSelf.'?mtm_campaign=whatsapp_21%26mtm_source=whatsapp%26mtm_medium=social" class="btn-social btn-whatsapp" title="Whatsapp share" target="_blank" rel="noopener"><i class="fab fa-whatsapp-square"></i> Auf Whatsapp teilen</a>
			</div>
			<div class="col-sm-4">
				<a href="https://facebook.com/sharer.php?u='.$strPathSelf.'?mtm_campaign=facebook_21%26mtm_source=facebook%26mtm_medium=social" class="btn-social btn-facebook" title="Facebook" target="_blank" rel="noopener"><i class="fab  fa-facebook-square"></i> Auf Facebook teilen</a>
			</div>
			
		</div>	
		<h3>Join the Community (beta)</h3>
		<div class="row">
			<!-- banned <div class="col-sm-4">
				<a href="https://twitter.com/'.social_media_twitter_username.'" class="btn-social btn-twitter" title="Twitter" target="_blank" rel="noopener"><i class="fab  fa-twitter-square"></i> Freie-Welt.eu Twitter Kanal</a>
			</div> -->
			<div class="col-sm-4">
				<a href="https://www.reddit.com/r/'.social_media_reddit_username.'/" class="btn-social btn-reddit" title="Reddit Kanal" target="_blank" rel="noopener"><i class="fab  fa-reddit-square"></i>"Politik" Reddit-Gruppe</a>
			</div>
			<!-- <div class="col-sm-4">
				<a href="https://t.me/'.social_media_telegramm_username.'" class="btn-social btn-facebook" title="Facebook" target="_blank" rel="noopener"><i class="fab  fa-facebook-square"></i> "Freie-Welt.eu" Telegram-Gruppe .</a>
			</div> -->						
			<div class="col-sm-4">
				<a href="https://www.facebook.com/'.social_media_facebook_username.'/" class="btn-social btn-facebook" title="Facebook" target="_blank" rel="noopener"><i class="fab  fa-facebook-square"></i>"Freie-Welt.eu" Facebookseite</a>
			</div>
		</div>		
 
		</div>
		<br/>
		<a class="button_ext" title="'.$dataRSS_DB["AddTitel"].'" target="_blank" href="/weiterleitung.php?rss_id='.$dataRSS_DB["news_content_id"].'&page_id='.$dataRSS_DB['page_id'].'&mtm_campaign=tsec_21&mtm_source=tsec&mtm_medium=social"><i class="fas fa-external-link-alt"></i> Kompletten Artikel lesen</a> <font size="0.8em">(externe Quelle: '.$dataRSS_DB["Webseite"].')</font>
		<br/><br/>
		<a class="button" title="Freie Welt Nachrichtenportal Startseite" href="'.CORE_SERVER_DOMAIN.'?pk_campaign=pull_startseite&pk_kwd=startseite&pk_source=link"><i class="fa fa-home" aria-hidden="true"></i> Zur Freie Welt Nachrichtenportal Startseite</a><br/><br/>';
		
		$text .='</article>		


<ins class="adsbygoogle"
     style="display:block"
     data-ad-format="autorelaxed"
     data-ad-client="ca-pub-9851833893867858"
     data-ad-slot="9736316268"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-format="autorelaxed"
     data-ad-client="ca-pub-9851833893867858"
     data-ad-slot="2779371119"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>';

	######################################
	# >> Social Media
	######################################
	if($dataRSS_DB['gui_footer_social_media'] == 'Y') {



	}
		
	$text .= '</article>'; // config modus 

	$result = array("title"=>'&#10056; '.$dataRSS_DB["AddTitel"],"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	return $result;
 } 
 

 ?>