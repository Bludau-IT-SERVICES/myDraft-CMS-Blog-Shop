<footer>
	<div class="footer">
	{if $domain_id == 1}
		<div style="float:left;margin-right:30px">
		<h2>Kontakt mit Shopste.com aufnehmen</h2>
		24/7 <a href="mailto:kontakt@shopste">kontakt@shopste.com</a><br/>
		Telefonisch Mo. - Fr. 9 - 17 Uhr unter 0441-2 33 33 05<br/>
		Teamviewer Support möglich
		</div>
		<div style="float:left;margin-right:30px">
			<h2>Weitere wichtige Informationen</h2>
			<ul>
				<li><a href="http://shopste.com/de/891/Ueber-Shopste/Kontakt-aufnehmen/" title="Kontakt aufnehmen">Kontakt aufnehmen</a></li>
				<li><a href="http://shopste.com/de/528/Ueber-Shopste/AGB/" title="Shopste Allgemeine Geschäftsbedinungen">Shopste AGB</a></li>	
				<li><a href="http://shopste.com/de/529/Ueber-Shopste/Widerruf/">Shopste Widerruf</a></li>
				<li><a href="http://shopste.com/marktplatz-nachrichten/">Shopste Marktplatz RSS Feed Nachrichten</a></li>
				<li><a href="http://shopste.com/shop-produkte/">Shopste Marktplatz neuste Produkte RSS Feed</a></li>
				<li><a href="http://www.php-consulting.com/de/2/PHP-Entwicklung/" title="PHP Dienstleistungen">PHP Dienstleistungen</a></li> 
				<li><a href="http://downloads.cubss.net/" title="Softwareprodukte JTL Erweiterungen, etc.">Software Produkte Downloaden</a></li>
			</ul>
		</div>
		<div style="float:left">
			<h2>Shopste.com auf Social Media</h2>
			&copy; Bludau-Media 2015 <br/><strong>Inhalt mit einem Klick bewerten</strong><div id="raty-benutzer"></div>
			<ul>
				<li><a href="https://twitter.com/Shopste">Bei Twitter</a> | <a href="https://www.facebook.com/pages/Bludau-Media/194094684113578?ref=hl">Bei Facebook</a></li>
			</ul>
		</div>
		<script>	
			$('body').addClass('animated bounceInLeft');
		</script>	
{/if}
	</div>
	<div class="footer">&copy; <a title="shopste.com Marktplatz" href="http://shopste.com">2015 shopste.com</a> | <a title="MyDraft Portal Software" href="http://www.php-consulting.com/de/7/MyDraft-CMS/">MyDraft PHP Software</a></div>
	<div class="box" id="box" style="overflow:auto;">
	<a class="boxclose" id="boxclose" style="float:right">Schlie&szlig;en</a>
	<span id="overlay_header"><h1>Rechnung erstellen</h1></span>
	<p id="acp_message">
	Rechnung erstellen
	</p>
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