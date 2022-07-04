<!DOCTYPE html>
<HTML xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<HEAD>
	<title>{$page_title}</title>
		<base href="{$domain_name}">
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
		
		{if $domain_ary.meta_nofollow != ''}
			<meta content="{$domain_ary.meta_nofollow}" name="robots">
			{else}
			<meta content="INDEX,FOLLOW" name="robots">
		{/if}
		
		<meta http-equiv="language" content="deutsch, de">
		<meta name="author" content="{$domain_ary.meta_autor}">
		<meta name="copyright" content=" (c) {$domain_ary.meta_autor}"> 
		<meta name="page-topic" content="{$domain_ary.meta_page_topic}">
		<meta name="revisit-after" content="{$domain_ary.meta_revisit_after}">
		<meta name="audience" content="alle">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<link rel="alternate" hreflang="de" href="{$page_http_uri}" />
		<link rel="alternate" type="application/rss+xml" title="Restaurant Mekong News" href="http://{$domain_name}/marktplatz-nachrichten/" />
		<link rel="alternate" type="application/rss+xml" title="Restaurant Mekong  News" href="http://{$domain_name}/shop-produkte/" />
		<!-- <link rel="icon" href="/templates/goldenwok/images/fav.ico" type="image/x-icon" />-->
		
		{if $google_webmaster != ''}
			{$google_webmaster}
		{/if}
		 

		<!-- <script  src="/framework/jquery/jquery-1.11.2.min.js"></script> -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript">
      //$.noConflict();
	  
    </script>			
	<script async src="/templates{$template_folder}/js/jquery_user_main.js"></script>
	
		<script async src="/framework/ajax_upload/assets/js/jquery.knob.js"></script>

		<!-- jQuery File Upload Dependencies -->
		<script  src="/framework/ajax_upload/assets/js/jquery.ui.widget.js"></script>
		<script  src="/framework/ajax_upload/assets/js/jquery.iframe-transport.js"></script>
		<script  src="/framework/ajax_upload/assets/js/jquery.fileupload.js"></script>
		
	<script src="/templates/shopste.com/js/track.js"></script>
		
	<script type="text/javascript" src="/framework/jquery_lazyload/jquery.lazyload.min.js"></script> 
 
  	<!-- <script type="text/javascript" src="/framework/Datepair/dist/datepair.min.js"></script>
	<script type="text/javascript" src="/framework/Datepair/dist/jquery.datepair.min.js"></script> -->
	
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">

<!-- Updated stylesheet url -->
<link rel="stylesheet" href="//jonthornton.github.io/jquery-timepicker/jquery.timepicker.css">

<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>

<!-- Updated JavaScript url -->
<script src="//jonthornton.github.io/jquery-timepicker/jquery.timepicker.js"></script>

	<script async type="text/javascript" src="/framework/fckeditor/fckeditor.js"></script>
	<script async type="text/javascript" src="/framework/fckeditor/fckeditor.php"></script>
		{if $admCheck == "1"}
			<script async type="text/javascript" src="/framework/fckeditor/fckeditor.js"></script>
			<script async type="text/javascript" src="/framework/fckeditor/fckeditor.php"></script>
			<!-- <script type="text/javascript" src="/framework/jquery-ui/jquery-ui.min.js"></script> -->
			<link rel="stylesheet" type="text/css" src="/framework/jquery-ui/css/jquery-ui-1.10.4.custom.min.css"/>
			
		{/if}
		
<!-- Mekong Template Additional -->
  <link rel="stylesheet" href="/templates{$template_folder}/css/jquery.jscrollpane.css">

  <script src="/templates{$template_folder}/js/jquery.mousewheel.js"></script>
  <script src="/templates{$template_folder}/js/jquery.jscrollpane.min.js"></script>
  
  <link rel="stylesheet" href="/templates{$template_folder}/css/grid.css">
  <link rel="stylesheet" href="/templates{$template_folder}/css/style.css">

<link href='https://fonts.googleapis.com/css?family=Roboto&subset=latin-ext' rel='stylesheet' type='text/css'>
  
  <script src="/templates{$template_folder}/js/jquery-migrate-1.2.1.js"></script>

 <script src="/templates{$template_folder}/js/jquery.equalheights.js"></script> 
<!--[if (gt IE 9)|!(IE)]><!-->
  <script src="/templates{$template_folder}/js/wow/wow.js"></script>
  <script>
    $(document).ready(function () {       
     // if ($('html').hasClass('desktop')) {
        new WOW().init();
      //}   
    });

  </script>
  {if $admCheck == "1"}
	<link media="all" href="/templates{$template_folder}/css/template_master.css" type="text/css" rel="stylesheet">
 
	<script src="/templates{$template_folder}/js/jquery_admin_main.js"></script>
	<script src="js/jquery.uploadprogress.0.3.js"></script>
{/if}
 
  <!--<![endif]-->
 <!--[if lt IE 8]>
   <div style=' clear: both; text-align:center; position: relative;'>
     <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
       <img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />
    </a>
  </div>
<![endif]-->
<!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <link rel="stylesheet" type="text/css" media="screen" href="/templates{$template_folder}/css/ie.css">
<![endif]-->

