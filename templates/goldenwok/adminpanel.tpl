 <div class="ACP">
	<!-- <h2>Administrationsbereich für Seite "{$aryPage.name_de}"</h2> -->
		<script>
	$("#hilfe_adminpanel_general").hide();
	$("#help_show").click(function(){
		$("#hilfe_adminpanel_general").fadeToggle('fast',"swing");
	});
	</script>
	
	<h1>Lieferservice</h1>
	
	<div id="acp_main_functions">
	<!--- <h2>Seiten verwalten</h2>-->
		<!-- <div id="optSelectBox" style="float:left">	
			{$modul_option_list = get_modulselector({$domain_ary.bIsNews})}
			{$modul_option_list}
		</div>  
	 
		<button class="button_acp modalInput" rel="#newmodul">Neues Seitenmodul</button>
		<button id="acp_edit_page" class="button_acp" rel="#editpage">Seite bearbeiten</button>
		<button id="acp_new_page" class="button_acp newpage" rel="#newpage">Seite neu anlegen</button> -->
		{if $domain_ary.bIsShop == "Y"}
		<br/>
			<!--<button id="acp_shop_category" class="button_acp" rel="#newpage">Shop Kategorien anlegen</button> -->
			<!-- <button id="acp_shop_category_portal" class="button_acp" rel="#newpage">Portal Kategorien anlegen</button> -->
			<!-- <button id="acp_shop_products" class="button_acp" rel="#newpage">Shop Produkt anlegen</button> -->
			<!-- <button id="acp_shop_products_list" class="button_acp" rel="#newpage">Produktverwaltung</button> -->
			<!-- <button id="mekong_acp_shop_orders" class="button_acp" rel="#newpage">Bestellungen verwalten</button> -->
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
		
		
		
		<div class="acp_main">
			<div id="acp_main_functions">
					<div class="main col3-layout">
						<div id="main_page_container" class="main-border">
							<div class="col-left">
								<h1><strong>Bestellungen</strong></h1>							
								<ul>
									<li><button id="mekong_acp_shop_orders_layout" class="button_acp_link" rel="#newpage">Auftragsverwaltung</button></li>
										<ul>
											<li><button id="acp_terminbestellungen_layout" class="button_acp_link" rel="#newpage">Terminbestellungen</button</li>
										</ul>
									<li><button id="acp_statistik_bestellungen_layout" class="button_acp_link" rel="#newpage">Statistik</button></li>
								</ul>
								<h1><strong>Einstellungen</strong></h1>		 					
								<ul>
									<li><button id="acp_shop_products_list_layout" class="button_acp_link" rel="#newpage">Speiseverwaltung</button></li>
										<ul>  
											<li><button id="acp_shop_category_layout" class="button_acp_link" rel="#newpage">Neue Speisekategorie</button></li>
											<li><button id="acp_shop_products_layout" class="button_acp_link" rel="#newpage">Neue Speise</button></li>
										</ul>
									<li><button id="acp_rechnungslayout_layout" class="button_acp_link" rel="#newpage">Rechnungslayout</button</li>									
									<li><button id="acp_userverwaltung_list_layout" class="button_acp_link" rel="#newpage">Benutzerverwaltung</button></li>
									<li><a href="index.php?modus=logout">Abmelden</a></li>
								</ul>
							</div>
							<div class="col-main" id="result-module-add" name="acp_layout_3columns_middle">			
								<script>
								$(document).ready(function(){
									var loadUrl = 'ACP/mekong_acp_shop_orderlist.php';
									$("#result-module-add").html(ajax_load).load(loadUrl,"");
								});
								</script>
							</div> 
							<div  class="col-right">
								<h1><strong>Aktionen</strong></h1>
								<ul>  
									<li><a href="">Neue Bestellung anlegen</a></li>
								</ul>
							</div>
							<div style="clear:both">
							</div>
						</div>
					</div>

			</div>
		</div>
	</div>
</div><br/>