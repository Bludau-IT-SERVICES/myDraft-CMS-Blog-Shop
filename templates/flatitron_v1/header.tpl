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
		
		<meta name="author" content="{$domain_ary.meta_autor}">
		<meta name="copyright" content=" (c) {$domain_ary.meta_autor}"> 
		<meta name="page-topic" content="{$domain_ary.meta_page_topic}">
		<meta name="revisit-after" content="{$domain_ary.meta_revisit_after}">
		<meta name="audience" content="alle">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<link rel="alternate" hreflang="de" href="{$page_http_uri}" />
		<!-- <link rel="alternate" type="application/rss+xml" title="Shopste Marktplatz News" href="{$domain_name}/marktplatz-nachrichten/" />
		<link rel="alternate" type="application/rss+xml" title="Shopste Produkt News" href="{$domain_name}/shop-produkte/" /> -->
		<link href='https://fonts.googleapis.com/css?family=Kotta+One' rel='stylesheet' type='text/css'>
		<link rel="icon" type="image/png" sizes="32x32" href="/templates/flatitron_v1/img/bludau-media-icon.png">

		<link rel="stylesheet" href="/framework/animate.css/animate.min.css">
		<!-- <link href="/templates/flatitron_v1/css/flexnav.css" rel="stylesheet" type="text/css" /> -->
		<link rel="stylesheet" href="/framework/raty-2.7.0/lib/jquery.raty.css">	
		<link media="all" href="/templates{$template_folder}/css/template_master.css" type="text/css" rel="stylesheet">
		<link rel="stylesheet" href="/framework/raty-2.7.0/lib/jquery.raty.css">		
		<link rel="stylesheet" href="/framework/fancyBox-master/source/jquery.fancybox.css" type="text/css" media="screen" />
		<!-- <link rel="stylesheet" type="text/css" href="/framework/yui/build/fonts/fonts-min.css" />	-->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">	
		{if $google_webmaster != ''}
			{$google_webmaster}
		{/if}
    <!-- Bootstrap and demo CSS -->
    <link href="/framework/yamm3-master/demo/components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/framework/yamm3-master/demo/components/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="/framework/yamm3-master/demo/css/demo.css" rel="stylesheet">
	<script type="text/javascript" src="/framework/ckeditor/ckeditor.js"></script>
		
	<!-- <script src="/framework/yamm3-master/demo/components/jquery/dist/jquery.js"></script> -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="/framework/ckeditor/adapters/jquery.js"></script>
		
	<script src="/framework/yamm3-master/demo/components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Yamm styles-->
    <link href="/framework/yamm3-master/yamm/yamm.css" rel="stylesheet">
	
		<!-- <script  src="/framework/jquery/jquery-1.11.2.min.js"></script> -->
		 <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script type="text/javascript" src="/framework/fancyBox-master/source/jquery.fancybox.pack.js"></script>
		<script async src="/framework/raty-2.7.0/lib/jquery.raty.js"></script>
    <script type="text/javascript">
      //$.noConflict();
	  
    </script>			
	<!-- <script  async type="text/javascript" src="/framework/zoomimage/js/eye.js"></script>
	<script  async type="text/javascript" src="/framework/zoomimage/js/utils.js"></script>
	<script  async type="text/javascript" src="/framework/zoomimage/js/zoomimage.js"></script> -->
	<script  src="/framework/raty-2.7.0/lib/jquery.raty.js"></script>
	<!-- <script  src="/framework/flexnav-master/js/jquery.flexnav.min.js"></script> -->
	<!-- <link async media="all" href="/js/cloud-zoom/cloud-zoom.css" type="text/css" rel="stylesheet"> -->	
	<!-- <script src="/js/cloud-zoom/cloud-zoom.js"></script> 
	<script async src="/js/jquery_user_main.js"></script>
	-->
	<script src="/js/jquery_user_main.js"></script>
	
		<script async src="/framework/ajax_upload/assets/js/jquery.knob.js"></script>

		<!-- jQuery File Upload Dependencies -->
		<script  src="/framework/ajax_upload/assets/js/jquery.ui.widget.js"></script>
		<script  src="/framework/ajax_upload/assets/js/jquery.iframe-transport.js"></script>
		<script  src="/framework/ajax_upload/assets/js/jquery.fileupload.js"></script>
		
	<script src="/templates/flatitron_v1/js/track.js"></script>
		
	<script type="text/javascript" src="/framework/jquery_lazyload/jquery.lazyload.min.js"></script> 
 <!-- ANFANG der Voraussetzungen  -->
 {if $page_id == 1}
  <script type="text/javascript">
    (function () {
      var po = document.createElement('script');
      po.type = 'text/javascript';
      po.async = true;
      po.src = 'https://plus.google.com/js/client:plusone.js?onload=start';
      var s = document.getElementsByTagName('script')[0];
      s.parentNode.insertBefore(po, s);
    })();

	
  </script>
  {/if}
 <!-- ENDE der Voraussetzungen -->
  			 
	<script async type="text/javascript" src="/framework/fckeditor/fckeditor.js"></script>
	<script async type="text/javascript" src="/framework/fckeditor/fckeditor.php"></script>
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
      //if ($('html').hasClass('desktop')) {
        new WOW().init();
      //} else {
	//}	  
    });

  </script> 
  {/if}
</HEAD>
