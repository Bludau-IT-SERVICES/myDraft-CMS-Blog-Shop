<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
	<title>{$page_title}</title>
		<!-- CORE PRELOADING CSS -->	
	<link rel="preload" href="/templates{$template_folder}/css/template_master.css" as="style">
	<link rel="preload" href="/templates{$template_folder}/css/bootstrap/css/bootstrap.min.css" as="style">
	<!-- CORE PRELOADING SCRIPTS-->
	<link rel="preload" src="/js/jquery-3.4.0.min.js" as="script">
	<link rel="preload" src="/js/track.js" as="script">	
	<link rel="preload" src="/templates{$template_folder}/css/bootstrap/js/bootstrap.min.js" as="script">

	<base href="https://{$domain_name}">
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta content="INDEX,FOLLOW" name="robots">
	<meta http-equiv="language" content="deutsch, de">
	<meta name="author" content="Jan Bludau">
	<meta name="copyright" content=" (c) Bludau IT Services">
	<meta name="page-topic" content="Dienstleistung,Onlineshop,Ecommerce">
	<meta name="revisit-after" content="14 days">
	<meta name="audience" content="alle">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="alternate" hreflang="de" href="{$page_http_uri}" />
	<link rel="alternate" type="application/rss+xml" title="Shopste Marktplatz News" href="https://{$domain_name}/marktplatz-nachrichten/" />
	<link rel="alternate" type="application/rss+xml" title="Shopste Produkt News" href="https://{$domain_name}/shop-produkte/" />
	<link rel="icon" type="image/png" sizes="32x32" href="/templates{$template_folder}/media/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/templates{$template_folder}/media/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/templates{$template_folder}/media/favicon-16x16.png">
	<link rel="stylesheet" href="/framework/animate.css/animate.min.css">
	{if $google_webmaster != ''}
		{$google_webmaster}
	{/if}
	<!-- <script  src="/framework/jquery/jquery-1.11.2.min.js"></script> -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<link rel="stylesheet" href="/framework/fancyBox-master/source/jquery.fancybox.css" type="text/css" media="screen" />
	<script type="text/javascript" src="/framework/fancyBox-master/source/jquery.fancybox.pack.js"></script>			
    <script type="text/javascript">
      //$.noConflict();	  
    </script>			

	<script  type="text/javascript" src="/framework/zoomimage/js/eye.js"></script>
	<script  type="text/javascript" src="/framework/zoomimage/js/utils.js"></script>
	<script  type="text/javascript" src="/framework/zoomimage/js/zoomimage.js"></script>
	<script  src="/framework/flexnav-master/js/jquery.flexnav.min.js"></script>
	<!-- <link async media="all" href="/js/cloud-zoom/cloud-zoom.css" type="text/css" rel="stylesheet"> -->	
	<!-- <script src="/js/cloud-zoom/cloud-zoom.js"></script> 
	<script async src="/js/jquery_user_main.js"></script>
	-->
	<script defer src="/js/jquery_user_main.js"></script>

	<link href="/framework/flexnav-master/css/flexnav.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" media="screen" type="text/css" href="/framework/zoomimage/css/zoomimage.css" />
	<link media="all" href="/templates{$template_folder}/css/template_master.css" type="text/css" rel="stylesheet">			 
	<script async type="text/javascript" src="/framework/fckeditor/fckeditor.js"></script>
	<script type="text/javascript" src="/framework/fckeditor/fckeditor.php"></script>
	{if $admCheck == "1"}
		<script async type="text/javascript" src="/framework/fckeditor/fckeditor.js"></script>
		<script type="text/javascript" src="/framework/fckeditor/fckeditor.php"></script>
		<link rel="stylesheet" type="text/css" src="/framework/jquery-ui/css/jquery-ui-1.10.4.custom.min.css"/>			
	{/if}
</head>
