 <div class="ACP">
	<h2>Administrationsbereich für Seite "{$page_title}" <font size="2">

	<div id="acp_main_functions">
	<h2>Seiten verwalten</h2>
		<div id="optSelectBox" style="float:left">	
			{$modul_option_list = get_modulselector({$bIsShop})}
			{$modul_option_list}
		</div>  
		<button class="button_acp modalInput" rel="#newmodul">Neues Seitenmodul</button>
		<button id="acp_edit_page" class="button_acp" rel="#editpage">Seite bearbeiten</button>
		<button id="acp_new_page" class="button_acp newpage" rel="#newpage">Seite neu anlegen</button>
		{if $bIsShop == "Y"}
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
		
		<h2>Eigene Nachrichten verwalten</h2>
		<button id="acp_news_category" class="button_acp" rel="#newpage">News Kategorien anlegen</button>
		<button id="acp_news_content" class="button_acp" rel="#newpage">News Inhalt eintragen</button>
		
		<h2>Fremde RSS-Feed verwalten</h2>
		<button id="acp_rss_category" class="button_acp" rel="#newpage">RSS-Feed Kategorien anlegen</button>
		<button id="acp_rss_quelle" class="button_acp" rel="#newpage">RSS-Quelle URL anlegen</button>
		<button id="acp_rss_content" class="button_acp" rel="#newpage">RSS-Feed Inhalt eintragen</button>	
		
		<h2>Allgemeine Einstellungen</h2>
		<button id="acp_domain_page" class="button_acp" rel="#newpage">{if $bIsShop == "Y"} Shop {else} Webseiten {/if}Einstellungen</button>
		<button id="acp_page_delete" onClick="set_page_delete('{$page_id}')" class="button_acp" rel="#newpage">Seite l&ouml;schen</button>
		<a href="index.php?modus=logout">Logout</a> <br/>
		<div class="acp_main">
			<div id="result-module-add"></div>
		</div>
	</div>
</div><br/>