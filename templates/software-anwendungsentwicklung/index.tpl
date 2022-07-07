
{include file="header.tpl" title="Shopste.com"}
<body>
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
						<img alt="logo" src="{$logo_pfad}" {$logo_width} {$logo_height}/>
					</div>
				</header>
			</div>
			{/if}	
				<div class="menu-button">Menu</div>
				<div id="mydraft_menue" style="height:69px">
					<div id="nav_main">
			
						<nav>
						
						<ul class="flexnav" data-breakpoint="800" >						
							{$menue_html = menue_generator(0,0,'',0,0)}
							{$menue_html}
						</ul>
						 
						</nav>
					</div>
				 
				</div>
				<div class="brotkruemmel">
				{$brotkruemmel_navi = getMenuePath({$page_id})} &nbsp;
				{$brotkruemmel_navi}<br/>
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
{if $admCheck == "1"}
	<script src="/js/jquery_admin_main.js"></script>
	<script src="js/jquery.uploadprogress.0.3.js"></script>
	<script src="/js/jquery_user_main.js"></script>
{/if}
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
