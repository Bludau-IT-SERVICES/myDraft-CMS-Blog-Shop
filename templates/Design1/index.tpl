
{include file="header.tpl" title="Shopste.com"}
<body>

	<div class="wrapper">
		<div class="page">
			{if $admCheck == "1"}
				{include file="adminpanel.tpl" admCheck="$admCheck" page_id="$page_id" bIsShop="$bIsShop" modul_option_list="$modul_option_list"} 
			{/if}
			 
			{if $logo_pfad != ""}
			<div class="header-container">
				
				
			
				<header>
					<div class="header">
						<h1 style="background-image:url('{$logo_pfad}');width:100%" onclick="location.href='';" title="Shopste Marktplatz" class="logo" id="logo"><a href="http://shopste.com/">Shopste Marktplatz</a></h1>
					</div>
				</header>
			</div>
			{/if}	
				<div class="menu-button">Menu</div>
				<div id="mydraft_menue" style="height:69px;margin-left: -13px;">
				 
			
						<nav>
						
						<ul class="flexnav" data-breakpoint="800" style="left:-40">						
							{$menue_html}
						</ul>
						 
						</nav>
			
				 
				</div>
				<div class="brotkruemmel">
				{$brotkruemmel_navi} &nbsp;
				</div>
				<div style="clear:both"></div>
				<div class="main {$layout_style}">
					<div  id= "main_page_container" class="main-border">
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
<div id="footer"><div id="shop_footer">{$cart_info_bar}</div></div>
{/if}
</body>
</html>
