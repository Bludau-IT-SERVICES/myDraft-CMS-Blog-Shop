<?php 


####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_rss_feed_show($config) {

		$dataRSSFeed = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_rss_feed_show WHERE id=".$config['modul_id']));
		
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='rss_feed_show'"));
		#echo "IN";
		
		$dataRSSFeed['typ'] = 'rss_feed_show';
		
		$text = '<div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
 
		$text .= convertUmlaute($dataRSSFeed["content_".$_SESSION['language']]);
		$titel = convertUmlaute($dataRSSFeed["title_".$_SESSION['language']]);
		

		
		if($text == '') {   
			$text = convertUmlaute($dataRSSFeed["content_de"]); 
		} 
		
		if($titel == '') { 
			$titel = convertUmlaute($dataRSSFeed["title_de"]); 
		} 
		
		# Eingeloggt 
		if (@$_SESSION['login'] == '1')  { 
			if($titel == '') { 
				$titel = "Kein Titel"; 
			} 
		} 

		// Multiple feeds
		/*		$feed->set_feed_url(array(
			'http://simplepie.org/blog/feed/',
			'http://digg.com'
		));*/
		$path = dirname(__FILE__);
		#echo $path;
		require_once($path.'/../../framework/simplepie/autoloader.php');
		$feed = new SimplePie();

		
		$feed->set_feed_url(array(
			$dataRSSFeed['rss_quelle']
		));
		$feed->enable_order_by_date(false);
		$feed->set_cache_location($_SERVER['DOCUMENT_ROOT'] . '/cache');
		$feed->init();
		$iCount =1;
		$text .= $feed->get_title(); 
		foreach ($feed->get_items() as $item) {
			if($iCount <= $dataRSSFeed['anzahl']) {
			
				$text .= '<div class="item">
					<h2><a href="'.$item->get_permalink().'">'.$item->get_title().'</a></h2>
					<p>'.$item->get_description().'</p>
					<p><small>Datum: '.$item->get_date('j.m.Y | H:i').'</small></p>
				</div>';
			} 
			$iCount++;
		}
			 
			
		$text .='<div class="softclear"></div>';
		$text .='</div>';
	
		
 
		
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 } 
 ?>