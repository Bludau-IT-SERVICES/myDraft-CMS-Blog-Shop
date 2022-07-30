<?php
/* Smarty version 4.1.1, created on 2022-07-30 06:03:06
  from '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/freie-welt.eu/header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62e4ad7a5ddcf9_96455155',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '19f54dacd7a2ece8ec5f62ee1bf33c06149d67ed' => 
    array (
      0 => '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/freie-welt.eu/header.tpl',
      1 => 1659153577,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62e4ad7a5ddcf9_96455155 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="de" prefix="og: http://ogp.me/ns#">
<head>
	<?php if ($_smarty_tpl->tpl_vars['meta_titel']->value != '') {?>
	<title><?php echo $_smarty_tpl->tpl_vars['meta_titel']->value;?>
</title>	
	<?php } else { ?>
	<title><?php echo $_smarty_tpl->tpl_vars['page_title']->value;?>
</title>
	<?php }?>
	<base href="<?php echo $_smarty_tpl->tpl_vars['domain_name']->value;?>
">
	<!-- DNS Verbindung aufbauen -->
	<link rel="dns-prefetch" href="//use.fontawesome.com">
	<link rel="dns-prefetch" href="https://fonts.googleapis.com">
	<link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
	<link rel="preconnect" href="//use.fontawesome.com">
	<link rel="preconnect" href="https://cdnjs.cloudflare.com">

	<link rel="preload" href="/templates/freie-welt.eu/css/template_master.css" as="style">

	<?php echo '<script'; ?>
 rel="preload" src="/js/jquery-3.4.0.min.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 rel="preload" src="/js/track.js"><?php echo '</script'; ?>
>	
	
	<link rel="canonical" href="<?php echo $_smarty_tpl->tpl_vars['page_url_cononical']->value;?>
"/>
	<meta charset="utf-8">
	<link rel="manifest" href="manifest.json">
	<!-- <meta http-equiv="expires" content="0">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Cache-Control" content="no-cache">	 -->	
	<meta name="subject" content="Nachrichtenportal und Aufklärung" />	
	<meta name="coverage" content="Worldwide" /> 
	<meta name="distribution" content="Global" />
	<meta name="category" content="Nachrichtenportal" />
	<meta name="owner" content="" />
	<meta name="rating" content="general" /> 
	<meta name="web_author" content="" />
	
	<!-- <meta name="date" content="2010-05-15T08:49:37+02:00"> -->
	<?php if ($_smarty_tpl->tpl_vars['meta_description']->value != '') {?>
	<meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['meta_description']->value;?>
">
	<?php }?>	
	<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['meta_email'] != '') {?>
	<meta name="email" content="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['meta_email'];?>
" />
	<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['meta_fb_page_id'] != '') {?>
	<meta property="fb:page_id" content="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['meta_fb_page_id'];?>
" />
	<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['twitter_account_id'] != '') {?>
	<meta property="twitter:account_id" content="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['twitter_account_id'];?>
" />
	<?php }?> 
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="application-name" content="myDraft CMS">
	<meta name="apple-mobile-web-app-title" content="myDraft CMS">
	<meta name="theme-color" content="#2196f3">
	<meta name="msapplication-navbutton-color" content="#2196f3">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<meta name="msapplication-starturl" content="/?pk_campaign=PWA&pk_kwd=startup">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="icon" type="image/jpeg" sizes="192x192" href="/templates/freie-welt.eu/media/favicon-96x96.png">
	<link rel="apple-touch-icon" type="image/jpeg" sizes="192x192" href="/templates/freie-welt.eu/media/favicon-96x96.png">
	<link rel="icon" type="image/jpeg" sizes="512x512" href="/templates/freie-welt.eu/media/favicon-96x96.png">
	<link rel="apple-touch-icon" type="image/jpeg" sizes="512x512" href="/templates/freie-welt.eu/media/favicon-96x96.png">		

	<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['meta_nofollow'] != '') {?>
		<meta content="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['meta_nofollow'];?>
" name="robots"> 
		<?php } else { ?>
		<meta content="INDEX,FOLLOW" name="robots"> 
	<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['bing_meta'] != '') {?>
			<meta name="msvalidate.01" content="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['bing_meta'];?>
" />					
	<?php }?>	
	<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['meta_autor'] != '') {?> 
	     <!-- <meta name="author" content="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['meta_autor'];?>
"> -->
	<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['meta_copyright'] != '') {?> 		 
	     <meta name="copyright" content="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['meta_copyright'];?>
"> 
	<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['meta_page_topic'] != '') {?> 
	    <meta name="page-topic" content="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['meta_page_topic'];?>
">
	<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['meta_revisit_after'] != '') {?> 
	    <meta name="revisit-after" content="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['meta_revisit_after'];?>
">
	<?php }?>
	<meta property="og:locale" content="de_DE" />
	<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['og_type'] != '') {?>
	     <meta property="og:type" content="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['og_type'];?>
" />
	<?php }?>  
    <?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['webseiten_name'] != '') {?> 	 
	      <meta property="og:site_name" content="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['webseiten_name'];?>
" />
	<?php }?>
	
	<link rel="alternate" type="application/rss+xml" title="Freie Welt Nachrichtenportal" href="<?php echo $_smarty_tpl->tpl_vars['domain_name']->value;?>
/freie-welt-nachrichten/" />
	<!-- <link rel="alternate" id="alternate-androidapp" href="" /> 
	<link rel="alternate" id="alternate-iosapp" href="ios-app://" /> --> 
	<?php if ($_smarty_tpl->tpl_vars['meta_titel']->value != '') {?>
	<meta property="og:title" content="<?php echo $_smarty_tpl->tpl_vars['meta_titel']->value;?>
" />
	<?php } else { ?>
	<meta property="og:title" content="<?php echo $_smarty_tpl->tpl_vars['aryPage']->value['name_de'];?>
" />
	<?php }?>
	
	<?php if ($_smarty_tpl->tpl_vars['meta_description']->value != '') {?>
		<meta property="og:description" content="<?php echo $_smarty_tpl->tpl_vars['meta_description']->value;?>
">
	<?php } else { ?>
		<meta property="og:description" content="<?php echo $_smarty_tpl->tpl_vars['aryPage']->value['titel_de'];?>
" />
	<?php }?>	
	
	<meta property="og:image" content="/templates/freie-welt.eu/media/favicon-96x96.png" />
	<meta property="og:url" content="<?php echo $_smarty_tpl->tpl_vars['page_http_uri']->value;?>
" />	
	
	<!-- <meta property="og:updated_time" content="2017-12-03T19:16:44+00:00" /> -->
	
	<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['og_icon'] != '') {?> 	
	     <meta property="og:image" content="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['og_icon'];?>
" /> 
		 
		 <?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['og_image_type'] != '') {?>
	     <meta property="og:image:type" content="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['og_image_type'];?>
" />
		 <?php }?>
		 <?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['og_image_width'] != '') {?>
	     <meta property="og:image:width" content="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['og_image_width'];?>
" />
		 <?php }?>
		 <?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['og_image_height'] != '') {?>
	     <meta property="og:image:height" content="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['og_image_height'];?>
" />
		 <?php }?>

	<?php }?>	
	
	<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['twitter_handle_name'] != '') {?>
	      <meta name="twitter:site" content="@<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['twitter_handle_name'];?>
" />
	<?php }?>
	<meta name="twitter:creator" content="@myTwitterHandle">
	<!-- 
	<meta name="twitter:card" content="summary_large_image">
	-->
	<meta name="twitter:card" content="summary">	
	<?php if ($_smarty_tpl->tpl_vars['meta_titel']->value != '') {?>
	<meta name="twitter:title" content="<?php echo $_smarty_tpl->tpl_vars['meta_titel']->value;?>
" />
	<?php } else { ?>
	<meta name="twitter:title" content="<?php echo $_smarty_tpl->tpl_vars['aryPage']->value['name_de'];?>
" />
	<?php }?>
	
	<?php if ($_smarty_tpl->tpl_vars['meta_description']->value != '') {?>
		<meta name="twitter:description" content="<?php echo $_smarty_tpl->tpl_vars['aryPage']->value['meta_description'];?>
" />
	<?php } else { ?>
		<meta name="twitter:description" content="<?php echo $_smarty_tpl->tpl_vars['aryPage']->value['titel_de'];?>
" />
	<?php }?>	

	<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['twitter_icon'] != '') {?>
		<meta name="twitter:image" content="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['twitter_icon'];?>
" />
	<?php }?> 
	<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['twitter_icon_alt'] != '') {?>
		<meta name="twitter:image:alt" content="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['twitter_icon_alt'];?>
" />
	<?php }?>	
	<meta name="audience" content="alle">
	<link rel="alternate" hreflang="de" href="<?php echo $_smarty_tpl->tpl_vars['page_http_uri']->value;?>
" />
	<!--- <link rel="alternate" type="application/rss+xml" title="Shopste Marktplatz News" href="https://<?php echo $_smarty_tpl->tpl_vars['domain_name']->value;?>
/marktplatz-nachrichten/" />
	<link rel="alternate" type="application/rss+xml" title="Shopste Produkt News" href="https://<?php echo $_smarty_tpl->tpl_vars['domain_name']->value;?>
/shop-produkte/" /> -->
	<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['webseite_icon'] != '') {?> 
	    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['webseite_icon'];?>
">
	<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['webseite_icon_96'] != '') {?> 
	    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['webseite_icon_96'];?>
">
	<?php }?>		 
	<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['webseite_icon_16'] != '') {?> 
	    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['webseite_icon_16'];?>
">
	<?php }?>  	  
	<?php if ($_smarty_tpl->tpl_vars['google_webmaster']->value != '') {?>
		<?php echo $_smarty_tpl->tpl_vars['google_webmaster']->value;?>

	<?php }?>
	<?php echo '<script'; ?>
 src="http://www.youtube.com/player_api"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
// Smartphone Erkennung
function detectmob() { 
	 if( navigator.userAgent.match(/Android/i)
	 || navigator.userAgent.match(/webOS/i)
	 || navigator.userAgent.match(/iPhone/i)
	 || navigator.userAgent.match(/iPad/i)
	 || navigator.userAgent.match(/iPod/i)
	 || navigator.userAgent.match(/BlackBerry/i)
	 || navigator.userAgent.match(/Windows Phone/i)
	 ){
		return true;
	  }
	 else {
		return false;
	  }
	}	
<?php echo '</script'; ?>
>	
<link media="all" href="/templates<?php echo $_smarty_tpl->tpl_vars['template_folder']->value;?>
/css/template_master.css" type="text/css" rel="stylesheet">
<link media="all" href="/templates<?php echo $_smarty_tpl->tpl_vars['template_folder']->value;?>
/css/menu.css" type="text/css" rel="stylesheet">
<?php echo '<script'; ?>
 src="/js/jquery-3.4.0.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 defer src="/templates/freie-welt.eu/js/track.js"><?php echo '</script'; ?>
>
<link rel="stylesheet" href="/templates<?php echo $_smarty_tpl->tpl_vars['template_folder']->value;?>
/css/bootstrap/css/bootstrap.min.css">
<?php echo '<script'; ?>
 src="/templates<?php echo $_smarty_tpl->tpl_vars['template_folder']->value;?>
/css/bootstrap/js/bootstrap.min.js"><?php echo '</script'; ?>
>
<!-- <?php echo '<script'; ?>
>
	var player;
	window.onYouTubePlayerAPIReadyByID = function(id) {
			player = new YT.Player('ytplayer_' + id, {
					height: '315',
				width: '560',
				videoId: id
			});
	}
<?php echo '</script'; ?>
> -->
</head>
<?php }
}
