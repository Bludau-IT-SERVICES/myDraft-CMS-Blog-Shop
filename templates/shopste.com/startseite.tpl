{include file="header.tpl" title="Schlemmertal.de" domain_name="$domain_name" template_folder="$template_folder" admCheck="$admCheck" page_title="$page_title" domain_id="$domain_id" google_webmaster="$google_webmaster"} 
<body>

	<div class="wrapper">
		<div class="page">
			
			{if $admCheck == "1"}
				{include file="adminpanel.tpl" admCheck="$admCheck" page_id="$page_id" bIsShop="$bIsShop" modul_option_list="$modul_option_list"} 
			{/if}
			<div class="header-container">
				<div class="header1" style="top:0">
				<div id="login-panel">
						<a href="http://www.schlemmertal.de/de/2/Anmeldung/">Firmeneintrag</a> | 
						<a href="http://www.schlemmertal.de/de/7/Benutzeranmeldung/">Anmeldung</a>
				</div>
					<div id="teaser-img">
						<img id="main-logo-background" src="/templates/schlemmertal/media/bilder/iStock_000039568666Large-header.jpg" alt="Bestell Dir einen Partyservice." width="996"/>
						<img src="/templates/schlemmertal/media/bilder/Schlemmertal_Logo_Web.png" alt="Schlemmertal Logo." id="main-logo"/>
					<div id="plzsearch_wrapper"">
						<div id="plz-search"> 
							<form class="searchform" name="plzsearch" method="POST" action="/suche/">
								<div class="plz-input">
									<input type="text" name="plzsearch_input" id="plzsearch_input" autocomplete="off">
									<span class="placeholder" id="plzsearch_placeholder" style="display: block;"></span>                 
								</div>
								<div style="display:none">
									<input type="submit" name="btnSucheSenden"/>
								</div>
								<!-- <div style="padding:0; margin:0; float:left;">
									<a class="newbtndeactive" href="#" id="plzsearch_submit">suchen</a>
								</div> -->
							</form>						
						</div>
						</div>
					</div>
					</div>
					
				</div>
				<div style="clear:both;"></div>
				<div class="main-startseite col2-left-layout">
					<div class="teaser-3">
						<div id="spalte-3-1" class="spalten-3">
							<h2>Catering Service finden</h2>
							<div>
								Online Essen bestellen bei Lieferdiensten in ganz Deutschland. Die Bestellung ist einfach, zuverlässig. Ob ... oder ...: Einfach passende Postleitzahl eintragen und direkt online vergleichen. Für Suchende ist diese Dienstleistung kostenlos. <a href="/catering-service-finden" title="Catering Service finden. Riesige Lieferservice Auswahl">Mehr...</a>
							</div>
						</div>
						<div id="spalte-3-2" class="spalten-3">
								<h2>Catering Dienst eintragen</h2>
								<div>
								 Auf schlemmertal.de können Sie alles anbieten. Egal, ob ... oder ...: Alle Köstlichkeiten, die die moderne Küche zu bieten hat, lassen sich ganz einfach nach Hause bestellen. Aber schlemmertal.de bietet noch mehr: Finde durch die Empfehlungen der Besteller neue Geheimtipp-Catering Dienstleister aus Deiner Stadt.<a href="/catering-dienstleister-eintragung" title="Ihr Catering Dienst im Internet">Mehr...</a>
							</div>
						</div>
						<div id="spalte-3-3" class="spalten-3">
								<h2>Partyservice finden</h2>
								<div>
									Auf der Suche nach einem passenden Partyservice? Bei Schlemmertal.de gibt es eine riesige Auswahl an ..., die frisch zubereitetes direkt nach Hause liefern. <a href="/Partyservice" title="Partyservice in Ihrer Stadt finden">Mehr...</a>
								</div>
						</div>					
					<img src="/templates/schlemmertal/media/bilder/Besteck-small.png" id="teaser-background"/>
					<div style="clear:both"> </div>
					</div>
					<div style="clear:both">.</div>
				</div>
				{include file="footer.tpl" page_id="$page_id"} 
		</div>
	</div>

{if $admCheck == "1"}
	<script src="/js/jquery_admin_main.js"></script>
	<script src="js/jquery.uploadprogress.0.3.js"></script>
	<script src="/js/jquery_user_main.js"></script>
{/if}
 
<script>
    $(".flexnav").flexNav();
</script>
</body>
</html>