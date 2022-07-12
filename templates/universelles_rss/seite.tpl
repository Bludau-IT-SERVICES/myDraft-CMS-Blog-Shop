{include file="header.tpl" title="myDraft CMS" }
<!-- Cachepunkt: {$CACHED_TIMESTAMP} -->
<body>
	<div class="wrapper">
		<div class="page">
				{if $admCheck == "1"}
					{include file="adminpanel.tpl" admCheck="$admCheck" page_id="$page_id" bIsShop="$bIsShop" modul_option_list="$modul_option_list"} 
				{/if}
			 
				{if $logo_pfad != ""}
				<header class="header-container">

						<div class="header">
						
							{if $domain_ary.logo_width != ""}
								{assign var="logo_width_value" value=$domain_ary.logo_width}
								{assign var="logo_width" value="width=\"$logo_width_value\""}
							{/if}
							{if $domain_ary.logo_height != ""}
								{assign var="logo_height_value" value=$domain_ary.logo_height}
								{assign var="logo_height" value="height=\"$logo_height_value\""}
							{/if}			
							<a title="{$CORE_PLATTFORMNAME}" href="/">
								<img alt="logo" src="{$logo_pfad}" {$logo_width} {$logo_height}/>
							</a>
						</div>
						<div class="header">
							{include file="header_link.tpl"}
						</div>
				</header>
				{/if}	
				
				<section>			
			          <nav role="navigation"  style="background-color: #1E1E23;height: 65px;">
						<div id="menuToggle">
						  <input name="MenuToggle" id="togglemenu" type="checkbox" />
							<span></span>
							<span></span>
							<span></span>
					
					   </div> 
					  </nav>
					  <nav role="navigation"  style="background-color: #1E1E23;">
							<i class="fas fa-angle-right" style="font-size: 100px;position: fixed;top: 37.5%;left: 0%;z-index: 1000;"><i style="font-size: 12px;display: block;margin-top: -21px;"><br/>Menü</i></i>


							<ul id="menu" style="display:none;">						
								{include file="menue_cached.tpl" caching cache_lifetime=86400}
							</ul>
					  </nav>
					</div>
 
				</section>
		

				<div class="main {$layout_style}">
				
					<div  id= "main_page_container" class="main-border">

						{include file="google_translationbar.tpl" caching cache_lifetime=-1}			
						{include file="small_news.tpl" caching cache_lifetime=-1}
						<br/>
						{include file="werbung_header.tpl"} 
						{$layout_content = getPageLayoutHTML_tpl({$aryPage.layout},{$aryPage.spalte_links_breite},{$aryPage.spalte_rechts_breite},{$aryPage.spalte_mitte_breite})}
						{$layout_content}
					</div>
				</div>
		<div style="clear:both"></div>
		{include file="footer.tpl" page_id="$page_id"} 
	</div>
 
	<script async src="/templates{$template_folder}/js/jquery_user_main.js"></script>  
	
	{if $admCheck == "1"} 
		<link rel="stylesheet" type="text/css" src="/framework/jquery-ui/jquery-ui.min.css"/>
		<script async src="/js/jquery_admin_main.js"></script>		
	{/if}	
	<!-- <audio preload id="soundfile_1">
		<source src="soundfile_1.mp3" type="audio/mp3">	
	</audio>
	<audio preload id="soundfile_2">
		<source src="soundfile_2.mp3" type="audio/mp3">
	</audio>
	<audio preload id="soundfile_3">
		<source src="soundfile_4.mp3" type="audio/mp3">	
	</audio>
<div id="call2action"></div>
<script>
callBasicView_startpage('',false);
</script> -->
</body>
</html>
