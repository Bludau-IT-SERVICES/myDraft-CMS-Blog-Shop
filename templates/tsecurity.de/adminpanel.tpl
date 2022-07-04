 <div class="ACP">
	<h2>Administrationsbereich für Seite "{$aryPage.name_de}" <font size="2"><span class="spanlink" id="help_show">Hilfe einblenden</span></font></h2>
	
	<h3>STATUS</h3>
	Cache-Aktiv: {$CORE_CACHE}, weil angemeldet<br/>
	
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