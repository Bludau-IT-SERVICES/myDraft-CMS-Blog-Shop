{include file="header.tpl" title="Nachrichtenportal Freie Welt" }
<!-- Cachepunkt: {$CACHED_TIMESTAMP} -->
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-58RSLG7"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
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
							<a title="Nachrichtenportal Freie Welt " href="/">
								<img alt="Nachrichtenportal Freie Welt Logo" src="{$logo_pfad}" {$logo_width} {$logo_height}/>
							</a>
						</div>
						<div class="header">
							<a style="color:yellow" class="button" title="tsecurity.de Nachrichtenportal" href="https://tsecurity.de/?pk_campaign=franchise&pk_kwd=franchise-link&pk_source=franchise-link">
								ZU TEAM-SECURITY (tsecurity.de) wechseln - IT-NACHRICHTENPORTAL alle 15 Minuten neuste Nachrichten aus über 400 Quellen
							</a>
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
							<i class="fas fa-angle-right" style="font-size: 100px;position: fixed;top: 37.5%;left: 0%;z-index: 1000;"></i>

							<ul id="menu" style="display:none;">						
								{$menue_html = menue_generator(0,0,'',0,0)}
								{$menue_html}
							</ul>
					  </nav>
					</div>
 
				</section>
				
				<div class="brotkruemmel">
				
					{$brotkruemmel_navi = getMenuePath({$page_id})}
					{$brotkruemmel_navi} 
					
				</div>
				
				<div style="clear:both"></div>
		
				<div class="main {$layout_style}">
				
					<div  id= "main_page_container" class="main-border">				
			
			{$layout_content = getPageLayoutHTML_tpl({$aryPage.layout},{$aryPage.spalte_links_breite},{$aryPage.spalte_rechts_breite},{$aryPage.spalte_mitte_breite})}
			{$layout_content}
			</div>
		</div>
		<div style="clear:both"></div>
		{include file="footer.tpl" page_id="$page_id"} 
	</div>
 
	<script async src="/templates{$template_folder}/js/jquery_user_main.js"></script>
	<script async src="/framework/fckeditor/fckeditor.js"></script>
	<link media="all" href="/templates{$template_folder}/css/template_master.css" type="text/css" rel="stylesheet">
	<link rel="stylesheet" href="/framework/raty-2.7.0/lib/jquery.raty.css">		    
	
	{if $admCheck == "1"} 
		<script async src="/framework/jquery-ui/jquery-ui.min.js"></script>
		<link rel="stylesheet" type="text/css" src="/framework/jquery-ui/jquery-ui.min.css"/>
		<script async src="/js/jquery_admin_main.js"></script>
		<script async src="js/jquery.uploadprogress.0.3.js"></script>
		<script async src="/framework/ajax_upload/assets/js/jquery.knob.js" ></script>

		<!-- jQuery File Upload Dependencies -->
		<script async src="/framework/ajax_upload/assets/js/jquery.ui.widget.js" ></script>
		<script async src="/framework/ajax_upload/assets/js/jquery.iframe-transport.js" ></script>
		<script async src="/framework/ajax_upload/assets/js/jquery.fileupload.js" ></script>		
	{/if}		

<link rel="stylesheet" href="/framework/fontawesome/css/all.css">
</body>
</html>
