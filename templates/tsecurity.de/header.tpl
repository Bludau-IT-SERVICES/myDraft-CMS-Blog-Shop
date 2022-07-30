<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
	<title>{$page_title}</title>
	<base href="{$domain_name}">
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	{if $domain_ary.meta_nofollow != ''}
		<meta content="{$domain_ary.meta_nofollow}" name="robots">
		{else}
		<meta content="INDEX,FOLLOW" name="robots">
	{/if}
	<meta name="author" content="{$domain_ary.meta_autor}">
	<meta name="copyright" content=" (c) {$domain_ary.meta_autor}"> 
	<meta name="page-topic" content="{$domain_ary.meta_page_topic}">
	<meta name="revisit-after" content="{$domain_ary.meta_revisit_after}">
	<meta property="og:locale" content="de" />
	<meta property="og:type" content="website" />
	<meta property="og:site_name" content="{$domain_ary.shop_name}" />
	<meta property="og:title" content="{$aryPage.name_de}" />
	<meta property="og:description" content="{$aryPage.titel_de}" />
	<meta property="og:url" content="{$page_http_uri}" />
	<meta property="og:image" content="https://tsecurity.de/templates/tsecurity.de/media/logo-social-media.png" />
	<meta name="twitter:site" content="@security_de" />
	<meta name="twitter:title" content="{$aryPage.name_de}" />
	<meta name="twitter:description" content="{$aryPage.titel_de}" />
	
	<meta name="audience" content="alle">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="alternate" hreflang="de" href="{$page_http_uri}" />
	<link rel="alternate" type="application/rss+xml" title="IT Security News" href="{$domain_name}/marktplatz-nachrichten/" />
	<link rel="alternate" type="application/rss+xml" title="IT Security Feed News" href="{$domain_name}/it-security-feed/" />
	<link href='https://fonts.googleapis.com/css?family=Kotta+One' rel='stylesheet' type='text/css'>
	<link rel="icon" type="image/x-icon" sizes="32x32" href="/templates/tsecurity.de/media/tsecurity.ico">
	<link rel="stylesheet" href="/framework/animate.css/animate.min.css">
	<link href="/templates/tsecurity.de/css/flexnav.css" rel="stylesheet" type="text/css" />		
	<link media="all" href="/templates{$template_folder}/css/template_master.css" type="text/css" rel="stylesheet">
	<link rel="stylesheet" href="/framework/fancyBox-master/source/jquery.fancybox.css" type="text/css" media="screen" />
	<!-- <link rel="stylesheet" type="text/css" href="/framework/yui/build/fonts/fonts-min.css" />	-->	
	{if $google_webmaster != ''}
		{$google_webmaster}
	{/if}
	<!-- <script  src="/framework/jquery/jquery-1.11.2.min.js"></script> -->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script  src="/framework/flexnav-master/js/jquery.flexnav.min.js"></script>
	<script src="/framework/raty-2.7.0/lib/jquery.raty.js"></script>
    <script type="text/javascript">
      //$.noConflict();	  
    </script>				
	<script type="text/javascript" src="/framework/ckeditor/ckeditor.js"></script>
	<script src="/framework/ckeditor/adapters/jquery.js"></script>
	{if $admCheck == "1"}
		<script async type="text/javascript" src="/framework/fckeditor/fckeditor.js"></script>
		<script async type="text/javascript" src="/framework/fckeditor/fckeditor.php"></script>
		<!-- <script type="text/javascript" src="/framework/jquery-ui/jquery-ui.min.js"></script> -->
		<link rel="stylesheet" type="text/css" src="/framework/jquery-ui/css/jquery-ui-1.10.4.custom.min.css"/>
	{/if}
	{if $domain_ary.bWebShopAnimation == 'shop_animation_on'}
		  <script src="/templates{$template_folder}/js/wow/wow.js"></script>
		<script>
			$(document).ready(function () {       
			  if ($('html').hasClass('desktop')) {
				new WOW().init();
			  } else {
			}	  
			});
		</script> 
  {/if}
<script type="text/javascript" src="https://www.youtube.com/player_api"></script>		
<script>
	var player;
	window.onYouTubePlayerAPIReadyByID = function(id) {
			player = new YT.Player('ytplayer_' + id, {
					height: '315',
				width: '560',
				videoId: id
			});
	}				  			
</script> 

<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.js"></script>
{literal}
<script>
window.addEventListener("load", function(){
window.cookieconsent.initialise({
  "palette": {
    "popup": {
      "background": "#000"
    },
    "button": {
      "background": "#f1d600"
    }
  },
  "content": {
    "message": "Diese Webseite verwendet Cookies. Wenn Sie diese Webseite weiterhin besuchen, stimmen Sie der Nutzung von Cookies zu. Weitere Informationen finden Sie unter Datenschutz",
    "dismiss": "Habe verstanden",
    "link": "Weitere Informationen",
    "href": "https://tsecurity.de/de/2658/Datenschutzerklaerung/"
  }
})});
</script>
{/literal}
</head>
