 <div class="ACP">
	<h2>Administrationsbereich für Seite "{$aryPage.name_de}" <font size="2"><span class="spanlink" id="help_show">Hilfe einblenden</span></font></h2>
	
	<div id="hilfe_adminpanel_general">
		<h2>Generelle Shopste.com Hilfe</h2>
		<ul>
		<li>Sie können Texthtml Module (Textbereiche) durch Doppelklick auf den Text ändern.</li>
		<li>Sie können jeden Titel durch einen Doppelklick auf die Überschrift verändern (jedes Modul) durch Doppelklick auf den Text ändern.</li>
		<li>Editieren Sie Impressum, AGB, Widerruf, Versandkosten durch öffnen der Seite im Menü unter "Über uns"</li>
		<li>Erstellen Sie eine "Shop Kategorie" z.B. T-Shirts</li>
		<li>Erstellen Sie ein "Shop Produkt" z.B. Ein T-Shirt.</li>
		<li>Sollte Ihre T-Shirt in Varianten angeboten werden schauen Sie sich "Shop Attribute" an. Legen Sie ein Attributset mit dem Namen T-Shirt an. Erstellen Sie Attribute wie Farbe, Größen</li>
		<li>Legen Sie Ihre "Shop Versandkosten" an, optional können Sie das auch leer Lassen und auf der Seite "Über uns" -> "Versandkosten" Ihre Versandkosten als Text, Tabelle auch für Internationalen Versand anlegen.</li>
		<li>Im Administrationsbereich sehen Sie ein Auswahlfeld, wo Sie Module auf jeder Seite hinzufügen können -> "Neues Seitenmodul". Es wird das Ausgewählte Modul zur Erstellung geladen. Sie fügen das Modul immer auf die Aktive Seite hinzu.</li>
		<li><a href="https://shopste.com/de/877/Eigenen-Onlineshop-erstellen/Hilfe-zu-Shopste/">Weitere Hilfeseiten anzeigen</a></li>
		</ul>
	</div>
		<script>
	$("#hilfe_adminpanel_general").hide();
	$("#help_show").click(function(){
		$("#hilfe_adminpanel_general").fadeToggle('fast',"swing");
	});
	</script>

	<div id="acp_main_functions">
	<h2>Webseiten verwalten</h2>
		<div id="optSelectBox" style="float:left">	
			{$modul_option_list = get_modulselector({$domain_ary.bIsNews})}
			{$modul_option_list}
		</div>  
	 
		<button class="button_acp modalInput" rel="#newmodul">Neues Webseitenmodul</button>
		<button id="acp_edit_page" class="button_acp" rel="#editpage">Webseite bearbeiten</button>
		<button id="acp_new_page" class="button_acp newpage" rel="#newpage">Webseite neu anlegen</button>
		<button id="acp_page_delete" onClick="set_page_delete('{$aryPage['id']}')" class="button_acp" rel="#newpage">Webseite l&ouml;schen</button>
		{if $domain_ary.bIsShop == "Y"}
		<br/>
		<h2>Shop Inhalte verwalten</h2>
			<button id="acp_shop_category" class="button_acp" rel="#newpage">Shop Kategorien anlegen</button>
			<!-- <button id="acp_shop_category_portal" class="button_acp" rel="#newpage">Portal Kategorien anlegen</button> -->
			<button id="acp_shop_products" class="button_acp" rel="#newpage">Shop Produkt anlegen</button>
			<button id="acp_shop_products_list" class="button_acp" rel="#newpage">Shop Produktverwaltung</button>
			<button id="acp_shop_orders" class="button_acp" rel="#newpage">Shop Bestellungen verwalten</button>
			<button id="acp_shop_shipping" class="button_acp" rel="#newpage">Shop Versandkosten verwalten</button>
			<button id="acp_shop_attribute" class="button_acp" rel="#newpage">Shop Attribute verwalten</button><br/>
		{/if}
		
		{if $domain_ary.bIsNews == "Y"}
			<h2>Eigene Nachrichten verwalten</h2>
			<button id="acp_news_category" class="button_acp" rel="#newpage">News Kategorien anlegen</button>
			<button id="acp_news_content" class="button_acp" rel="#newpage">News Inhalt eintragen</button>
		{/if}
		{if $domain_ary.bIsRSSPortal == "Y"}
			<h2>Fremde RSS-Feed verwalten</h2>
			<button id="acp_rss_category" class="button_acp" rel="#newpage">RSS-Feed Kategorien anlegen</button>
			<button id="acp_rss_quelle" class="button_acp" rel="#newpage">RSS-Quelle URL anlegen</button>
			<button id="acp_rss_content" class="button_acp" rel="#newpage">RSS-Feed Inhalt eintragen</button>	
		{/if}
		<h2>Webseite /Shop Einstellungen</h2>
		<button id="acp_domain_page" class="button_acp" rel="#newpage">{if $domain_ary.bIsShop == "Y"} Shop {else} Webseiten {/if}Einstellungen</button>

		<a href="index.php?modus=logout">Logout</a> <br/>
		<div class="acp_main">
			<div id="result-module-add"></div>
		</div>
	</div>
</div><br/>