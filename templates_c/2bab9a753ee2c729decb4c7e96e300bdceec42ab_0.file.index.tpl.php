<?php
/* Smarty version 4.1.1, created on 2022-07-30 06:03:06
  from '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/freie-welt.eu/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62e4ad7a5c8992_73860037',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2bab9a753ee2c729decb4c7e96e300bdceec42ab' => 
    array (
      0 => '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/freie-welt.eu/index.tpl',
      1 => 1659153025,
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
function content_62e4ad7a5c8992_73860037 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>"Mein CMS"), 0, false);
?>
<!-- Cachepunkt: <?php echo $_smarty_tpl->tpl_vars['CACHED_TIMESTAMP']->value;?>
 -->
<body>
	<div class="wrapper">
		<div class="page">
				<?php if ($_smarty_tpl->tpl_vars['admCheck']->value == "1") {?>
					<?php $_smarty_tpl->_subTemplateRender("file:adminpanel.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('admCheck'=>((string)$_smarty_tpl->tpl_vars['admCheck']->value),'page_id'=>((string)$_smarty_tpl->tpl_vars['page_id']->value),'bIsShop'=>((string)$_smarty_tpl->tpl_vars['bIsShop']->value),'modul_option_list'=>((string)$_smarty_tpl->tpl_vars['modul_option_list']->value)), 0, false);
?> 
				<?php }?>
			 
				<?php if ($_smarty_tpl->tpl_vars['logo_pfad']->value != '') {?>
				<header class="header-container">
						<div class="header">
						
							<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['logo_width'] != '') {?>
								<?php $_smarty_tpl->_assignInScope('logo_width_value', $_smarty_tpl->tpl_vars['domain_ary']->value['logo_width']);?>
								<?php $_smarty_tpl->_assignInScope('logo_width', "width=\"".((string)$_smarty_tpl->tpl_vars['logo_width_value']->value)."\"");?>
							<?php }?>
							<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['logo_height'] != '') {?>
								<?php $_smarty_tpl->_assignInScope('logo_height_value', $_smarty_tpl->tpl_vars['domain_ary']->value['logo_height']);?>
								<?php $_smarty_tpl->_assignInScope('logo_height', "height=\"".((string)$_smarty_tpl->tpl_vars['logo_height_value']->value)."\"");?>
							<?php }?>			
							<a title="Mein CMS" href="/">
								<img alt="Mein CMS" src="<?php echo $_smarty_tpl->tpl_vars['logo_pfad']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['logo_width']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['logo_height']->value;?>
/>
							</a>
						</div>
				</header>
				<?php }?>	
				
				<section>			
			          <nav role="navigation"  style="background-color: #1E1E23;height: 65px;">
						<div id="menuToggle">
						  <input name="MenuToggle" id="togglemenu" type="checkbox" />
							<span></span>
							<span></span>
							<span></span>
					   </div>
					  </nav>
					  <nav role="navigation"  style="background-color: #1E1E23;">
							<i class="fas fa-angle-right" style="font-size: 100px;position: fixed;top: 37.5%;left: 0%;z-index: 1000;"></i>

							<ul id="menu" style="display:none;">						
								<?php $_smarty_tpl->_assignInScope('menue_html', menue_generator(0,0,'',0,0));?>
								<?php echo $_smarty_tpl->tpl_vars['menue_html']->value;?>

							</ul>
					  </nav>
				</section>
				
				<div class="brotkruemmel">
				
					<?php ob_start();
echo $_smarty_tpl->tpl_vars['page_id']->value;
$_prefixVariable1 = ob_get_clean();
$_smarty_tpl->_assignInScope('brotkruemmel_navi', getMenuePath($_prefixVariable1));?>
					<?php echo $_smarty_tpl->tpl_vars['brotkruemmel_navi']->value;?>
 
					
				</div>
				
				<div style="clear:both"></div>
		
				<div class="main <?php echo $_smarty_tpl->tpl_vars['layout_style']->value;?>
">
				
					<div  id= "main_page_container" class="main-border">				
			
			<?php ob_start();
echo $_smarty_tpl->tpl_vars['aryPage']->value['layout'];
$_prefixVariable2 = ob_get_clean();
ob_start();
echo $_smarty_tpl->tpl_vars['aryPage']->value['spalte_links_breite'];
$_prefixVariable3 = ob_get_clean();
ob_start();
echo $_smarty_tpl->tpl_vars['aryPage']->value['spalte_rechts_breite'];
$_prefixVariable4 = ob_get_clean();
ob_start();
echo $_smarty_tpl->tpl_vars['aryPage']->value['spalte_mitte_breite'];
$_prefixVariable5 = ob_get_clean();
$_smarty_tpl->_assignInScope('layout_content', getPageLayoutHTML_tpl($_prefixVariable2,$_prefixVariable3,$_prefixVariable4,$_prefixVariable5));?>
			<?php echo $_smarty_tpl->tpl_vars['layout_content']->value;?>

			</div>
		</div>
		<div style="clear:both"></div>
		<?php $_smarty_tpl->_subTemplateRender("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('page_id'=>((string)$_smarty_tpl->tpl_vars['page_id']->value)), 0, false);
?> 
	</div>
	 
	<?php echo '<script'; ?>
 async src="/templates<?php echo $_smarty_tpl->tpl_vars['template_folder']->value;?>
/js/jquery_user_main.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 async src="/framework/fckeditor/fckeditor.js"><?php echo '</script'; ?>
>
	<link media="all" href="/templates<?php echo $_smarty_tpl->tpl_vars['template_folder']->value;?>
/css/template_master.css" type="text/css" rel="stylesheet">		
	<?php if ($_smarty_tpl->tpl_vars['admCheck']->value == "1") {?> 
		<?php echo '<script'; ?>
 async src="/framework/jquery-ui/jquery-ui.min.js"><?php echo '</script'; ?>
>
		<link rel="stylesheet" type="text/css" src="/framework/jquery-ui/jquery-ui.min.css"/>
		<?php echo '<script'; ?>
 async src="/js/jquery_admin_main.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 async src="js/jquery.uploadprogress.0.3.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 async src="/framework/ajax_upload/assets/js/jquery.knob.js" ><?php echo '</script'; ?>
>

		<!-- jQuery File Upload Dependencies -->
		<?php echo '<script'; ?>
 async src="/framework/ajax_upload/assets/js/jquery.ui.widget.js" ><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 async src="/framework/ajax_upload/assets/js/jquery.iframe-transport.js" ><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 async src="/framework/ajax_upload/assets/js/jquery.fileupload.js" ><?php echo '</script'; ?>
>		
	<?php }?>
</body>
</html>
<?php }
}
