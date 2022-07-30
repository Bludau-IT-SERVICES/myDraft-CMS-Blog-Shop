<footer>
	<div class="footer">
	{if $domain_id == 1}					
		<div style="float:left;margin-right:30px">
		<h2>Kontakt aufnehmen</h2>
		24/7 <a href="mailto:kontakt@mydomain.com">kontakt@mydomain.com</a><br/>
		Telefonisch Mo. - Fr. 9 - 17 Uhr unter 0441-2 33 33 05<br/>
		Teamviewer Support möglich
		</div>
		<div style="float:left;margin-right:30px">
		<h2>Weitere wichtige Informationen</h2>
		<ul>
			<li><a href="https://shopste.com/de/891/Ueber-Shopste/Kontakt-aufnehmen/" title="Kontakt aufnehmen">Kontakt aufnehmen</a></li>
			<li><a href="https://shopste.com/de/528/Ueber-Shopste/AGB/" title="Shopste Allgemeine Geschäftsbedinungen">Shopste AGB</a></li>
			<li><a href="https://shopste.com/de/529/Ueber-Shopste/Widerruf/">Shopste Widerruf</a></li>
			<li><a href="https://shopste.com/marktplatz-nachrichten/">Shopste Marktplatz RSS Feed Nachrichten</a></li>
			<li><a href="https://shopste.com/shop-produkte/">Shopste Marktplatz neuste Produkte RSS Feed</a></li>
			<li><a href="https://php-consulting.com/de/2/PHP-Entwicklung/" title="PHP Dienstleistungen">PHP Dienstleistungen</a></li> 
		</ul>
		</div>
		<div style="float:left">
		<h2>Social Media</h2>
		&copy; Bludau IT SERVICES 2022<br/>
		<ul>
			<li><a href="https://twitter.com/">Bei Twitter</a> | <a href="https://www.facebook.com/pages/">Bei Facebook</a></li>
		</ul><br/>
		<a href="{$domain_name}/marktplatz-nachrichten/">Marktplatz News <img src="/image/rss-small.png"/>
		</div> 				
	<div style="clear:both"></div>
	{/if}
	</div>
	<div class="footer">
	<a title="shopste.com Marktplatz" href="https://shopste.com">2015 shopste.com</a> | <a href="{$domain_name}/shop-produkte/">Produkte <img src="/image/rss-small.png"/> | </a><a title="MyDraft PHP Portal Software" href="https://php-consulting.com/de/7/MyDraft-CMS/">MyDraft PHP Portal Software</a> 
	</div>
<div class="overlay" id="overlay" style="display:none;"></div>
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
{if $domain_id == 1}
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
{/if}