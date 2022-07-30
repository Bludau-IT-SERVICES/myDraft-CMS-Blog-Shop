<footer>
	<div class="footer">
	{if $domain_id == 1}			
		<div style="float:left;width:32%;margin-right:30px">
		<h2>Social Media</h2>
		<ul>
			<li><a href="https://twitter.com/">Auf Twitter folgen</a></li>
			<li><a href="https://www.facebook.com/">Auf Facebook folgen</a></li>
		</ul><br/>
		Email: <a href="mailto:info@mydomain.com">info@tsecurity.de</a> schreiben (24 / 7)<br/>
		</div>
		<div style="float:left;width:32%">
			<h2>&Uuml;ber TSEC</h2>
			<ul>
				<li><a href="https://tsecurity.de/de/3612/Ueber-TSEC/">Sitemap</a></li>
				<li><a href="https://tsecurity.de/de/2657/Impressum/">Impressum</a></li>
				<li><a href="https://tsecurity.de/de/2658/Datenschutzerklaerung/">Datenschutz</a></li>
			</ul>
		</div>				
	<div style="clear:both"></div>	
	{/if}
	</div>
	<div class="footer">
	<h2>RSS Feeds abonieren</h2>
	<ul>
		<li><a href="{$domain_name}/it-security-feed/"><img alt="RSS-Feed" src="/image/rss-small.png"/> TSEC alle NachrichtenNews</a></li>
		<li><a href="{$domain_name}/tsec-nachrichten/"><img alt="RSS-Feed" src="/image/rss-small.png"/> TSEC Webseiten Nachrichten</a></li>
	<ul>
	<span style="float:right">&copy; <a title="TSecurity News Portal" href="https://tsecurity.de">2015-2017 TSecurity</a></span>

	</div>
 
<script>
    $(".flexnav").flexNav({         
		'animationSpeed' : 'fast',
         'calcItemWidths': true , // dynamically calcs top level nav item widths
         //'hoverIntent': true, // true for use with hoverIntent plugin
         'hoverIntentTimeout': 150 // hoverIntent default timeout
		});
	
	
$(window).bind('scroll', function () {
    if ($(window).scrollTop() > 50) {
        $('.flexnav').addClass('fixed');
    } else {
        $('.flexnav ').removeClass('fixed');
    }
});
</script>
</footer>
<script>
$('.menue').addClass('wow zoomIn');
$('.texthtml').addClass('wow zoomInDown');
$('.portal_shop_cat_list').addClass('wow zoomInDown');
$('.shop_cat_list').addClass('wow zoomInDown');
$('.registrieren_shopste').addClass('wow zoomIn');
$('.portal_umkreis').addClass('wow zoomIn');
$('.portal_gebuehrenanzeige').addClass('wow zoomIn');
$('.portal_shop_item_detail').addClass('wow zoomIn');
$('.menue_shopcategory').addClass('wow zoomIn');
$('.portal_userlogin').addClass('wow zoomIn');
$('.shop_item_detail').addClass('wow zoomIn');
$('.lvw_item_single').addClass('wow zoomIn');
</script>