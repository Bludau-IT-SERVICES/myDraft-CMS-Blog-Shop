 <div class="ACP">
	<h2>Administrationsbereich für Seite "{$aryPage.name_de}"</h2>
		<script>
	$("#hilfe_adminpanel_general").hide();
	$("#help_show").click(function(){
		$("#hilfe_adminpanel_general").fadeToggle('fast',"swing");
	});
	</script>

	<div id="acp_main_functions">
	<!-- <h2>Seiten verwalten</h2>-->
		<!-- <div id="optSelectBox" style="float:left">	
			{$modul_option_list = get_modulselector({$domain_ary.bIsNews})}
			{$modul_option_list}
		</div>  
	 
		<button class="button_acp modalInput" rel="#newmodul">Neues Seitenmodul</button>
		<button id="acp_edit_page" class="button_acp" rel="#editpage">Seite bearbeiten</button>
		<button id="acp_new_page" class="button_acp newpage" rel="#newpage">Seite neu anlegen</button> -->
		{if $domain_ary.bIsShop == "Y"}
		<br/>
			<button id="acp_shop_category" class="button_acp" rel="#newpage">Shop Kategorien anlegen</button>
			<!-- <button id="acp_shop_category_portal" class="button_acp" rel="#newpage">Portal Kategorien anlegen</button> -->
			<button id="acp_shop_products" class="button_acp" rel="#newpage">Shop Produkt anlegen</button>
			<button id="acp_shop_products_list" class="button_acp" rel="#newpage">Mekong Produktverwaltung</button>
			<button id="mekong_acp_shop_orders" class="button_acp" rel="#newpage">Mekong Bestellungen verwalten</button>
			<!-- <button id="acp_shop_shipping" class="button_acp" rel="#newpage">Shop Versandkosten verwalten</button>
			<button id="acp_shop_attribute" class="button_acp" rel="#newpage">Shop Attribute verwalten</button><br/> -->
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
		<!-- <h2>Allgemeine Einstellungen</h2>
		<button id="acp_domain_page" class="button_acp" rel="#newpage">{if $domain_ary.bIsShop == "Y"} Shop {else} Webseiten {/if}Einstellungen</button>
		<button id="acp_page_delete" onClick="set_page_delete('{$aryPage['id']}')" class="button_acp" rel="#newpage">Seite l&ouml;schen</button> -->
		<a href="index.php?modus=logout">Logout</a> <br/>
		<div class="acp_main">
			<div id="result-module-add"></div>
		</div>
	</div>
</div><br/>