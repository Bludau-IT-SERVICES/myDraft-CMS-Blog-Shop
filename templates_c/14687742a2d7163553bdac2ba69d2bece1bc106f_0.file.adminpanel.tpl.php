<?php
/* Smarty version 4.1.1, created on 2022-07-07 18:09:52
  from '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/freie-welt.eu/adminpanel.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62c705503c0f67_24682862',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '14687742a2d7163553bdac2ba69d2bece1bc106f' => 
    array (
      0 => '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/freie-welt.eu/adminpanel.tpl',
      1 => 1656941469,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62c705503c0f67_24682862 (Smarty_Internal_Template $_smarty_tpl) {
?> <div class="ACP">
	<h2>Administrationsbereich für Seite "<?php echo $_smarty_tpl->tpl_vars['page_title']->value;?>
" <font size="2">

	<div id="acp_main_functions">
	<h2>Seiten verwalten</h2>
		<div id="optSelectBox" style="float:left">	
			<?php ob_start();
echo $_smarty_tpl->tpl_vars['bIsShop']->value;
$_prefixVariable1 = ob_get_clean();
$_smarty_tpl->_assignInScope('modul_option_list', get_modulselector($_prefixVariable1));?>
			<?php echo $_smarty_tpl->tpl_vars['modul_option_list']->value;?>

		</div>  
		<button class="button_acp modalInput" rel="#newmodul">Neues Seitenmodul</button>
		<button id="acp_edit_page" class="button_acp" rel="#editpage">Seite bearbeiten</button>
		<button id="acp_new_page" class="button_acp newpage" rel="#newpage">Seite neu anlegen</button>
		<?php if ($_smarty_tpl->tpl_vars['bIsShop']->value == "Y") {?>
		<br/>
		<h2>Shop Inhalte verwalten</h2>
			<button id="acp_shop_category" class="button_acp" rel="#newpage">Shop Kategorien anlegen</button>
			<!-- <button id="acp_shop_category_portal" class="button_acp" rel="#newpage">Portal Kategorien anlegen</button> -->
			<button id="acp_shop_products" class="button_acp" rel="#newpage">Shop Produkt anlegen</button>
			<button id="acp_shop_products_list" class="button_acp" rel="#newpage">Shop Produktverwaltung</button>
			<button id="acp_shop_orders" class="button_acp" rel="#newpage">Shop Bestellungen verwalten</button>
			<button id="acp_shop_shipping" class="button_acp" rel="#newpage">Shop Versandkosten verwalten</button>
			<button id="acp_shop_attribute" class="button_acp" rel="#newpage">Shop Attribute verwalten</button><br/>
		<?php }?>
		
		<h2>Eigene Nachrichten verwalten</h2>
		<button id="acp_news_category" class="button_acp" rel="#newpage">News Kategorien anlegen</button>
		<button id="acp_news_content" class="button_acp" rel="#newpage">News Inhalt eintragen</button>
		
		<h2>Fremde RSS-Feed verwalten</h2>
		<button id="acp_rss_category" class="button_acp" rel="#newpage">RSS-Feed Kategorien anlegen</button>
		<button id="acp_rss_quelle" class="button_acp" rel="#newpage">RSS-Quelle URL anlegen</button>
		<button id="acp_rss_content" class="button_acp" rel="#newpage">RSS-Feed Inhalt eintragen</button>	
		
		<h2>Allgemeine Einstellungen</h2>
		<button id="acp_domain_page" class="button_acp" rel="#newpage"><?php if ($_smarty_tpl->tpl_vars['bIsShop']->value == "Y") {?> Shop <?php } else { ?> Webseiten <?php }?>Einstellungen</button>
		<button id="acp_page_delete" onClick="set_page_delete('<?php echo $_smarty_tpl->tpl_vars['page_id']->value;?>
')" class="button_acp" rel="#newpage">Seite l&ouml;schen</button>
		<a href="index.php?modus=logout">Logout</a> <br/>
		<div class="acp_main">
			<div id="result-module-add"></div>
		</div>
	</div>
</div><br/><?php }
}
