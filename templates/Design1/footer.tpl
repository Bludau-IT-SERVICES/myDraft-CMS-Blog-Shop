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
							
							<li>
							<a href="http://shopste.com/de/529/Ueber-Shopste/Widerruf/">Shopste Widerruf</a></li>
							<li><a href="http://shopste.com/marktplatz-nachrichten/">Shopste Marktplatz RSS Feed Nachrichten</a></li>
							<li><a href="http://shopste.com/shop-produkte/">Shopste Marktplatz neuste Produkte RSS Feed</a></li>
							<li><a href="http://www.php-consulting.com/de/2/PHP-Entwicklung/" title="PHP Dienstleistungen">PHP Dienstleistungen</a></li> 
							<li>
							<a href="http://downloads.cubss.net/" title="Softwareprodukte JTL Erweiterungen, etc.">Software Produkte Downloaden</a></li>
						</div>
						<div style="float:left">
						<h2>Shopste.com auf Social Media</h2>
						&copy; Bludau-Media 2015 <br/><strong>Inhalt mit einem Klick bewerten</strong><div id="raty-benutzer"></div>
						<ul>
							<li><a href="https://twitter.com/Shopste">Bei Twitter</a> | <a href="https://www.facebook.com/pages/Bludau-Media/194094684113578?ref=hl">Bei Facebook</a></li>
						</ul>
						</div>
 					
  <!-- Platzieren Sie dieses asynchrone JavaScript unmittelbar vor Ihrem </body>-Tag -->
  	<!--   <script type="text/javascript">
      (function() {
       var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
       po.src = 'https://apis.google.com/js/client:plusone.js';
       var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
     })();
	 function signinCallback(authResult) {
  if (authResult['access_token']) {
    // Autorisierung erfolgreich
    // Nach der Autorisierung des Nutzers nun die Anmeldeschaltfläche ausblenden, zum Beispiel:
    document.getElementById('signinButton').setAttribute('style', 'display: none');
	alert(authResult['access_token']);
  } else if (authResult['error']) {
    // Es gab einen Fehler.
    // Mögliche Fehlercodes:
    //   "access_denied" – Der Nutzer hat den Zugriff für Ihre App abgelehnt.
    //   "immediate_failed" – Automatische Anmeldung des Nutzers ist fehlgeschlagen.
    // console.log('Es gab einen Fehler: ' + authResult['Fehler']);
  }
}
    </script>
		<span id="signinButton">
  <span
    class="g-signin"
    data-callback="signinCallback"
    data-clientid="778239151304-fpb4po45r0qmc6goahevm7v5v9e3noup.apps.googleusercontent.com"
    data-cookiepolicy="single_host_origin"
    data-requestvisibleactions="http://schemas.google.com/AddActivity"
    data-scope="https://www.googleapis.com/auth/plus.login">
  </span>
</span> -->
<!-- Go to www.addthis.com/dashboard to customize your tools 
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-53d9b9a51e26b822" async="async"></script>-->

				<script>	
		$('body').addClass('animated bounceInLeft');
	$.fn.raty.defaults.path = '../../framework/raty-2.7.0/demo/images';
	
	$('#raty-benutzer').raty({ 
	half: true, hints       : ['magenhalft', 'ausreichend', 'befriediegend', 'gut', 'sehr gut'],
	score:{$sys_score_page},
	click: function(score, evt) {
			setSaveScore({$page_id},score); 
	} 
	});	
	function setSaveScore(text_id,score) {
	var ajax_load = '';
	//<img src='image/load.gif' alt='loading...' />
		$('#acp_message').html(ajax_load).load('/vote_save.php', 'page_id=' + text_id + '&score=' + score); 
	}
	</script>
	<div style="clear:both"></div>
	
	{/if}
	</div>
	<div class="footer">
	&copy; <a title="shopste.com Marktplatz" href="http://shopste.com">2015 shopste.com</a> | <a title="MyDraft Portal Software" href="http://www.php-consulting.com/de/7/MyDraft-CMS/">MyDraft PHP Software</a>

	</div>
	
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

<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(["setCookieDomain", "*.shopste.com"]);
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://shopste.com/framework/piwik/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 1]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; g.type='text/javascript';
    g.defer=true; g.async=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src="http://shopste.com/framework/piwik/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->