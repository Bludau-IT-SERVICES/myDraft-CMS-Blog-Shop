<?php
/* Smarty version 4.1.1, created on 2022-07-07 13:20:08
  from '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/universelles_rss/adminpanel.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62c6c168bcb2c6_80758783',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a628d79ed7ee2f96e13d91cf58b866175dc3f956' => 
    array (
      0 => '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/universelles_rss/adminpanel.tpl',
      1 => 1656941469,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62c6c168bcb2c6_80758783 (Smarty_Internal_Template $_smarty_tpl) {
?> <div class="ACP">
	<h2>Administrationsbereich für Seite "<?php echo $_smarty_tpl->tpl_vars['aryPage']->value['name_de'];?>
" <font size="2"><span class="spanlink" id="help_show">Hilfe einblenden</span></font></h2>
	
	<h3>STATUS</h3>
	Cache-Aktiv: <?php echo $_smarty_tpl->tpl_vars['CORE_CACHE']->value;?>
, weil angemeldet<br/>
	
	<div id="acp_main_functions">
	<h2>Webseiten verwalten</h2>
		<div id="optSelectBox" style="float:left">	
			<?php ob_start();
echo $_smarty_tpl->tpl_vars['domain_ary']->value['bIsNews'];
$_prefixVariable1 = ob_get_clean();
$_smarty_tpl->_assignInScope('modul_option_list', get_modulselector($_prefixVariable1));?>
			<?php echo $_smarty_tpl->tpl_vars['modul_option_list']->value;?>

		</div>  
	 
		<button class="button_acp modalInput" rel="#newmodul">Neues Webseitenmodul</button>
		<button id="acp_edit_page" class="button_acp" rel="#editpage">Webseite bearbeiten</button>
		<button id="acp_new_page" class="button_acp newpage" rel="#newpage">Webseite neu anlegen</button>
		<button id="acp_page_delete" onClick="set_page_delete('<?php echo $_smarty_tpl->tpl_vars['aryPage']->value['id'];?>
')" class="button_acp" rel="#newpage">Webseite l&ouml;schen</button>
		<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['bIsShop'] == "Y") {?>
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
		
		<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['bIsNews'] == "Y") {?>
			<h2>Eigene Nachrichten verwalten</h2>
			<button id="acp_news_category" class="button_acp" rel="#newpage">News Kategorien anlegen</button>
			<button id="acp_news_content" class="button_acp" rel="#newpage">News Inhalt eintragen</button>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['bIsRSSPortal'] == "Y") {?>
			<h2>Fremde RSS-Feed verwalten</h2>
			<button id="acp_rss_category" class="button_acp" rel="#newpage">RSS-Feed Kategorien anlegen</button>
			<button id="acp_rss_quelle" class="button_acp" rel="#newpage">RSS-Quelle URL anlegen</button>
			<button id="acp_rss_content" class="button_acp" rel="#newpage">RSS-Feed Inhalt eintragen</button>	
		<?php }?>
		<h2>Webseite /Shop Einstellungen</h2>
		<button id="acp_domain_page" class="button_acp" rel="#newpage"><?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['bIsShop'] == "Y") {?> Shop <?php } else { ?> Webseiten <?php }?>Einstellungen</button>

		<a href="index.php?modus=logout">Logout</a> <br/>
		<div class="acp_main">
			<div id="result-module-add"></div>
		</div>
	</div>
</div><br/><?php }
}
