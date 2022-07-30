<!DOCTYPE html>
<HTML xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
	<title>{$page_title}</title>
		<base href="https://{$domain_name}">
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
		<meta content="INDEX,FOLLOW" name="robots">
		<meta http-equiv="language" content="deutsch, de">
		<meta name="author" content="{$meta_autor}">
		<meta name="copyright" content=" (c) {$meta_autor}"> 
		<meta name="page-topic" content="{$meta_page_topic}">
		<meta name="revisit-after" content="{$meta_revisit_after}">
		<meta name="audience" content="alle">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="alternate" hreflang="de" href="{$page_http_uri}" />
		<link rel="alternate" type="application/rss+xml" title="Shopste Marktplatz News" href="https://{$domain_name}/marktplatz-nachrichten/" />
		<link rel="alternate" type="application/rss+xml" title="Shopste Produkt News" href="https://{$domain_name}/shop-produkte/" />
		<link href='http://fonts.googleapis.com/css?family=Kotta+One' rel='stylesheet' type='text/css'>
		<link rel="icon" type="image/png" sizes="32x32" href="/templates/shopste.com/media/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="/templates/shopste.com/media/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/templates/shopste.com/media/favicon-16x16.png">
		 <link rel="stylesheet" href="/framework/animate.css/animate.min.css">
		{if $google_webmaster != ''}
			{$google_webmaster}
		{/if}
		{if $meta_nofollow != ''}
			{$meta_nofollow}
		{/if}	 
		<!-- <script  src="/framework/jquery/jquery-1.11.2.min.js"></script> -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<link rel="stylesheet" href="/framework/fancyBox-master/source/jquery.fancybox.css" type="text/css" media="screen" />
		<script type="text/javascript" src="/framework/fancyBox-master/source/jquery.fancybox.pack.js"></script>
		<link rel="stylesheet" type="text/css" href="/framework/yui/build/fonts/fonts-min.css" />	
		<script src="/framework/raty-2.7.0/lib/jquery.raty.js"></script>
		<link rel="stylesheet" href="/framework/raty-2.7.0/lib/jquery.raty.css">		
    <script type="text/javascript">
      //$.noConflict();
	  
    </script>			
	<script  type="text/javascript" src="/framework/zoomimage/js/eye.js"></script>
	<script  type="text/javascript" src="/framework/zoomimage/js/utils.js"></script>
	<script  type="text/javascript" src="/framework/zoomimage/js/zoomimage.js"></script>
	<script  src="/framework/raty-2.7.0/lib/jquery.raty.js"></script>
	<script  src="/framework/flexnav-master/js/jquery.flexnav.min.js"></script>
	<!-- <link async media="all" href="/js/cloud-zoom/cloud-zoom.css" type="text/css" rel="stylesheet"> -->	
	<!-- <script src="/js/cloud-zoom/cloud-zoom.js"></script> 
	<script async src="/js/jquery_user_main.js"></script>
	-->
	<script defer src="/js/jquery_user_main.js"></script>

	<script src="/framework/ajax_upload/assets/js/jquery.knob.js"></script>
	<!-- jQuery File Upload Dependencies -->
	<script src="/framework/ajax_upload/assets/js/jquery.ui.widget.js"></script>
	<script src="/framework/ajax_upload/assets/js/jquery.iframe-transport.js"></script>
	<script src="/framework/ajax_upload/assets/js/jquery.fileupload.js"></script>
	<link href="/framework/flexnav-master/css/flexnav.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" media="screen" type="text/css" href="/framework/zoomimage/css/zoomimage.css" />
	<link rel="stylesheet" href="/framework/raty-2.7.0/lib/jquery.raty.css">	
	<link media="all" href="/templates{$template_folder}/css/template_master.css" type="text/css" rel="stylesheet"> 			 
	<script async type="text/javascript" src="/framework/fckeditor/fckeditor.js"></script>
	<script type="text/javascript" src="/framework/fckeditor/fckeditor.php"></script>
	{if $admCheck == "1"}
		<script async type="text/javascript" src="/framework/fckeditor/fckeditor.js"></script>
		<script type="text/javascript" src="/framework/fckeditor/fckeditor.php"></script>
		<!-- <script type="text/javascript" src="/framework/jquery-ui/jquery-ui.min.js"></script> -->
		<link rel="stylesheet" type="text/css" src="/framework/jquery-ui/css/jquery-ui-1.10.4.custom.min.css"/>
		
	{/if}
</head>
