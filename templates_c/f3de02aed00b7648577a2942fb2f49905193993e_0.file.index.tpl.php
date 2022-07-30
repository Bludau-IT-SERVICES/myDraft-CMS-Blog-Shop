<?php
/* Smarty version 4.1.1, created on 2022-07-30 17:54:10
  from '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/flatitron_v1/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62e55422e1e2b5_75639540',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f3de02aed00b7648577a2942fb2f49905193993e' => 
    array (
      0 => '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/flatitron_v1/index.tpl',
      1 => 1659195726,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:adminpanel.tpl' => 1,
    'file:func_google_translate.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_62e55422e1e2b5_75639540 (Smarty_Internal_Template $_smarty_tpl) {
?> <?php $_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('title'=>"Shopste.com"), 0, false);
?> 
<body>
	<div class="wrapper">
		<div class="page">
			<?php if ($_smarty_tpl->tpl_vars['admCheck']->value == "1") {?>
				<?php $_smarty_tpl->_subTemplateRender("file:adminpanel.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('admCheck'=>((string)$_smarty_tpl->tpl_vars['admCheck']->value)), 0, false);
?> 
			<?php }?>			 
			<?php if ($_smarty_tpl->tpl_vars['logo_pfad']->value != '') {?>
			<div class="header-container">
				<header>
					<div class="header" style="background-color:#6C6C6C">  
					
						<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['logo_width'] != '') {?>
							<?php $_smarty_tpl->_assignInScope('logo_width_value', $_smarty_tpl->tpl_vars['domain_ary']->value['logo_width']);?>
							<?php $_smarty_tpl->_assignInScope('logo_width', "width=\"".((string)$_smarty_tpl->tpl_vars['logo_width_value']->value)."\"");?>
						<?php }?>
						<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['logo_height'] != '') {?>
							<?php $_smarty_tpl->_assignInScope('logo_height_value', $_smarty_tpl->tpl_vars['domain_ary']->value['logo_height']);?>
							<?php $_smarty_tpl->_assignInScope('logo_height', "height=\"".((string)$_smarty_tpl->tpl_vars['logo_height_value']->value)."\"");?>
						<?php }?>						
						<img alt="logo" src="<?php echo $_smarty_tpl->tpl_vars['logo_pfad']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['logo_width']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['logo_height']->value;?>
/>
					</div>
				</header> 
			</div>
			<?php }?>	
			<nav  role="navigation">
				<div class="navbar yamm navbar-default navbar-fixed-top">
					<div class="container_neo" style="left:9%;position:relative">			 
						<div class="navbar-header">
								<button type="button" data-toggle="collapse" data-target="#navbar-collapse-1" class="navbar-toggle"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a href="#" class="navbar-brand">Shopste.com</a>
						</div>
						<div id="navbar-collapse-1" class="navbar-collapse collapse">
							<ul class="nav navbar-nav" data-breakpoint="800" >						
								<?php $_smarty_tpl->_assignInScope('menue_html', menue_generator(0,0,'',0,0));?>
								<?php echo $_smarty_tpl->tpl_vars['menue_html']->value;?>

							</ul>
						</div> 
					</div>
				</div>
			</nav>				
			<div class="brotkruemmel">
				<?php ob_start();
echo $_smarty_tpl->tpl_vars['page_id']->value;
$_prefixVariable1 = ob_get_clean();
$_smarty_tpl->_assignInScope('brotkruemmel_navi', getMenuePath($_prefixVariable1));?> &nbsp;
				<?php echo $_smarty_tpl->tpl_vars['brotkruemmel_navi']->value;?>
<br/>
				<div id="google_translate_box">
					<?php $_smarty_tpl->_subTemplateRender("file:func_google_translate.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
				</div>
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
<div id="footer"><span id="frame_detail_info"></span><div id="shop_footer"><?php echo $_smarty_tpl->tpl_vars['cart_info_bar']->value;?>
</div></div>
<?php }
echo '<script'; ?>
>
	$(document).ready(function() {	 	
		setTrack('<?php echo $_smarty_tpl->tpl_vars['trackid']->value;?>
','<?php echo $_smarty_tpl->tpl_vars['page_id']->value;?>
','0');
	});
<?php echo '</script'; ?>
>

<?php echo '<script'; ?>
>
      $(function() {      
        $(document).on('click', '.yamm .dropdown-menu', function(e) {
			alert(this);
          e.stopPropagation()
        })
      })
    <?php echo '</script'; ?>
>

<a href="#" class="fa fa-angle-double-up scrollup"></a>
</body>
</html><?php }
}
