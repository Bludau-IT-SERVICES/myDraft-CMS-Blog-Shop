<?php 
 	# Neue RSS-Klasse einbauen 
	include_once('lib/cls_rss_feed.php'); 
	include_once('include/inc_db_connect.php');
	include_once('include/inc_mod_section.php');
	$rss_feed = new rss_feed;
	
	$installConf['rss_file'] = '/var/www/vhosts/shopste.com/httpdocs/shopste-marktplatz.rss';	
	$installConf['server_url'] = 'http://shopste.com';
	$installConf['feed_count'] = '25';
	$installConf['feed_title'] = 'Shopste.com Neue Produkte';	
	$installConf['feed_descripton'] = 'Shopste.com Neue Produkte';	
			
	# GET FORM DATA 	
	#$rss_feed->pri($_POST); // Print debug info 

	$rss_feed_limit_arry = $rss_feed->set_xmlnewsfeed('shop_item',$installConf['feed_count']);	
	
	#$rss_feed->pri($rss_feed_limit_arry); // Print debug info 
	$rss_feed->set_xmlnewsfeed_file($installConf,$rss_feed_limit_arry,'shop_item'); 
	
	#header('Location: http://www.freie-welt.eu/rss_feed/freie_welt_feed.rss');
	header("Content-Type: application/rss+xml");
	#C:\Program Files (x86)\xampp\htdocs\-invincible\Freie-Welt\rss_feed 
	echo file_get_contents('/var/www/vhosts/shopste.com/httpdocs/shopste-marktplatz.rss');
		
?>