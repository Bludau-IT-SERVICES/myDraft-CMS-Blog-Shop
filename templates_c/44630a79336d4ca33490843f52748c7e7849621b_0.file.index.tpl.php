<?php
/* Smarty version 4.1.1, created on 2022-07-30 17:39:38
  from '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/Design1/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62e550ba72f530_92949712',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '44630a79336d4ca33490843f52748c7e7849621b' => 
    array (
      0 => '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/Design1/index.tpl',
      1 => 1659194919,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:adminpanel.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_62e550ba72f530_92949712 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>"Shopste.com"), 0, false);
?>
<body>
	<div class="wrapper">
		<div class="page">
			<?php if ($_smarty_tpl->tpl_vars['admCheck']->value == "1") {?>
				<?php $_smarty_tpl->_subTemplateRender("file:adminpanel.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('admCheck'=>((string)$_smarty_tpl->tpl_vars['admCheck']->value),'page_id'=>((string)$_smarty_tpl->tpl_vars['page_id']->value),'bIsShop'=>((string)$_smarty_tpl->tpl_vars['bIsShop']->value),'modul_option_list'=>((string)$_smarty_tpl->tpl_vars['modul_option_list']->value)), 0, false);
?> 
			<?php }?>			 
			<?php if ($_smarty_tpl->tpl_vars['logo_pfad']->value != '') {?>
			<div class="header-container">
				<header>
					<div class="header">
						<h1 style="background-image:url('<?php echo $_smarty_tpl->tpl_vars['logo_pfad']->value;?>
');width:100%" onclick="location.href='';" title="Shopste Marktplatz" class="logo" id="logo"><a href="http://shopste.com/">Shopste Marktplatz</a></h1>
					</div>
				</header>
			</div>
			<?php }?>	
			<div class="menu-button">Menu</div>
			<div id="mydraft_menue" style="height:69px;margin-left: -13px;">				
				<nav>
				<ul class="flexnav" data-breakpoint="800" style="left:-40">						
					<?php echo $_smarty_tpl->tpl_vars['menue_html']->value;?>

				</ul>				
				</nav>						 
			</div>
			<div class="brotkruemmel"><?php echo $_smarty_tpl->tpl_vars['brotkruemmel_navi']->value;?>
 &nbsp;</div>
			<div style="clear:both"></div>
			<div class="main <?php echo $_smarty_tpl->tpl_vars['layout_style']->value;?>
">
				<div  id= "main_page_container" class="main-border">
					<?php echo $_smarty_tpl->tpl_vars['layout_content']->value;?>

				</div>
			</div>
			<div style="clear:both"></div>
			<?php $_smarty_tpl->_subTemplateRender("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('page_id'=>((string)$_smarty_tpl->tpl_vars['page_id']->value)), 0, false);
?> 
	</div>
<?php if ($_smarty_tpl->tpl_vars['admCheck']->value == "1") {?>
	<?php echo '<script'; ?>
 src="/js/jquery_admin_main.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="js/jquery.uploadprogress.0.3.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="/js/jquery_user_main.js"><?php echo '</script'; ?>
>
<?php }
if ($_smarty_tpl->tpl_vars['domain_id']->value != 1) {?>
<div id="footer"><div id="shop_footer"><?php echo $_smarty_tpl->tpl_vars['cart_info_bar']->value;?>
</div></div>
<?php }?>
</body>
</html><?php }
}
