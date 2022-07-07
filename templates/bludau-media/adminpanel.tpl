 <div class="ACP">
	<h2>Administrationsbereich für Seite "{$page_title}" <font size="2"><span class="spanlink" id="help_show">Hilfe einblenden</span></font></h2>
	<div id="hilfe_adminpanel_general">
		Sie können hier Ihren Online Shop verwalten.<br/>
		Legen Sie eine neue Seite an und fügen dort Module hinzu. <br/>
		Wenn Sie einen Online Shop errichten möchten, empfiehlt es sich eine "Shop Kategorie anzulegen", diese Seite taucht automatisch in Ihrem Menü auf. Sie finden auch einen Knopf "Shop Produkt anlegen". Mit dem Sie Inhalte in Ihre Shopkategorie hinzufügen.<br/>
		Durch einen Doppelklick auf einen Textbereich (TEXT HTML) Ihrer Seite können Sie die Inhalte verändern. Doppelklick auf eine Modul Überschrift und Sie können diese verändern.<br/><br/>
	</div>
		<script>
	$("#hilfe_adminpanel_general").hide();
	$("#help_show").click(function(){
		$("#hilfe_adminpanel_general").fadeToggle('fast',"swing");
	});
	</script>

	<div id="acp_main_functions">
	<h2>Seiten verwalten</h2>
		<div id="optSelectBox" style="float:left">	
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
			<button id="acp_shop_orders" class="button_acp" rel="#newpage">Shop Bestellungen</button>
			<button id="acp_shop_shipping" class="button_acp" rel="#newpage">Shop Versandkosten</button>
			<button id="acp_shop_attribute" class="button_acp" rel="#newpage">Shop Attribute</button><br/>
		{/if}
		<h2>Allgemeine Einstellungen</h2>
		<button id="acp_domain_page" class="button_acp" rel="#newpage">{if $bIsShop == "Y"} Shop {else} Webseiten {/if}Einstellungen</button>
		<button id="acp_page_delete" onClick="set_page_delete('{$page_id}')" class="button_acp" rel="#newpage">Seite l&ouml;schen</button>
		<a href="index.php?modus=logout">Logout</a> <br/>
		<div class="acp_main">
			<div id="result-module-add"></div>
		</div>
	</div>
</div><br/>