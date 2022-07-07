
{include file="header.tpl" title="TSecurity News"}
<!-- Cachepunkt: {$CACHED_TIMESTAMP} -->

<body class="{$layout_device_type}">

	<div class="wrapper">
		<div class="page">
			{if $admCheck == "1"}
				{include file="adminpanel.tpl" admCheck="$admCheck"} 
			{/if}
			 
			{if $logo_pfad != ""}
			<div class="header-container">
				<header>
					<div class="header">
					
						{if $domain_ary.logo_width != ""}
							{assign var="logo_width_value" value=$domain_ary.logo_width}
							{assign var="logo_width" value="width=\"$logo_width_value\""}
						{/if}
						{if $domain_ary.logo_height != ""}
							{assign var="logo_height_value" value=$domain_ary.logo_height}
							{assign var="logo_height" value="height=\"$logo_height_value\""}
						{/if}
						<a title="FREIE-WELT.EU Nachrichtenportal" href="/?pk_campaign=logo-nav&pk_kwd=logo-nav&pk_source=klick-logo">
							<img alt="logo" src="{$logo_pfad}" {$logo_width} {$logo_height}/>
						</a>
					</div>
				</header>
			</div> 
			{/if}
				<section>			
				<div class="menu-button">Menu</div>
				<div id="mydraft_menue" style="height:69px;margin-left: -12px;">
					<div id="nav_main">
			
						<nav>
						
						<ul class="flexnav" data-breakpoint="800" >						
							{$menue_html = menue_generator(0,0,'',0,0)}
							{$menue_html}
						</ul>
						 
						</nav>
					</div>
				 
				</div>
				</section>
				<section>
					<div class="brotkruemmel">
					{$brotkruemmel_navi = getMenuePath({$page_id})} &nbsp;
					{$brotkruemmel_navi}<br/>
						<div id="google_translate_box">
							{include file="func_google_translate.tpl"}
						</div>
					</div>
					<div style="clear:both"></div>
					</section>

				
				<section>
					<div class="main {$layout_style}">
						<div  id= "main_page_container" class="main-border">
	<font size="1">Anzeige </font> <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- tsec responsive -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-9851833893867858"
     data-ad-slot="7008804984"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script><br/>
						{$layout_content = getPageLayoutHTML_tpl({$aryPage.layout},{$aryPage.spalte_links_breite},{$aryPage.spalte_rechts_breite},{$aryPage.spalte_mitte_breite})}
						{$layout_content}
						</div>
					</div>
					<div style="clear:both"></div>
				</section>
				<section>{include file="footer.tpl" page_id="$page_id"}</section> 
				<section>{include file="footer_include.tpl" page_id="$page_id"}</section>
	</div>

{if $admCheck == "1"}
	<script src="/js/jquery_admin_main.js"></script>
	<script src="js/jquery.uploadprogress.0.3.js"></script>
{/if}

<script src="/js/jquery_user_main.js"></script>

{if $domain_id != 1}
	<div id="footer"><span id="frame_detail_info"></span><div id="shop_footer">{$cart_info_bar}</div></div>
{/if}

<script>
	$(document).ready(function() {	 	
		setTrack('{$trackid}','{$page_id}','0');
	});
</script>

<a href="#" class="fa fa-angle-double-up scrollup"></a>

</body>
</html>
