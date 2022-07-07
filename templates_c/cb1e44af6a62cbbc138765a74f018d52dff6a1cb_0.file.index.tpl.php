<?php
/* Smarty version 4.1.1, created on 2022-07-07 11:38:12
  from '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/universelles_rss/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62c6a984636c20_83039520',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'cb1e44af6a62cbbc138765a74f018d52dff6a1cb' => 
    array (
      0 => '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/universelles_rss/index.tpl',
      1 => 1656941469,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:adminpanel.tpl' => 1,
    'file:header_link.tpl' => 1,
    'file:menue_cached.tpl' => 1,
    'file:small_news.tpl' => 1,
    'file:werbung_header.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_62c6a984636c20_83039520 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 1, -1, array('title'=>"FREIE WELT.EU NACHRICHTENPORTAL"), 0, false);
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
							<a title="Nachrichtenportal Team Security (tsecurity.de)" href="/?pk_campaign=logo-nav&pk_kwd=logo-nav&pk_source=klick-logo">
								<img alt="logo" src="<?php echo $_smarty_tpl->tpl_vars['logo_pfad']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['logo_width']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['logo_height']->value;?>
/>
							</a>
						</div>
						<div class="header">
							<?php $_smarty_tpl->_subTemplateRender("file:header_link.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
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
							<i class="fas fa-angle-right" style="font-size: 100px;position: fixed;top: 37.5%;left: 0%;z-index: 1000;"><i style="font-size: 12px;display: block;margin-top: -21px;"><br/>Menü</i></i>


							<ul id="menu" style="display:none;">						
								<?php $_smarty_tpl->_subTemplateRender("file:menue_cached.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 1, -1, array(), 0, false);
?>
							</ul>
					  </nav>
					</div>
 
				</section>
		
				<div class="main <?php echo $_smarty_tpl->tpl_vars['layout_style']->value;?>
">
				
					<div  id= "main_page_container" class="main-border">				
						<?php $_smarty_tpl->_subTemplateRender("file:small_news.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 1, -1, array(), 0, false);
?>
						<br/>
						<?php $_smarty_tpl->_subTemplateRender("file:werbung_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?> 
						<?php ob_start();
echo $_smarty_tpl->tpl_vars['aryPage']->value['layout'];
$_prefixVariable1 = ob_get_clean();
ob_start();
echo $_smarty_tpl->tpl_vars['aryPage']->value['spalte_links_breite'];
$_prefixVariable2 = ob_get_clean();
ob_start();
echo $_smarty_tpl->tpl_vars['aryPage']->value['spalte_rechts_breite'];
$_prefixVariable3 = ob_get_clean();
ob_start();
echo $_smarty_tpl->tpl_vars['aryPage']->value['spalte_mitte_breite'];
$_prefixVariable4 = ob_get_clean();
$_smarty_tpl->_assignInScope('layout_content', getPageLayoutHTML_tpl($_prefixVariable1,$_prefixVariable2,$_prefixVariable3,$_prefixVariable4));?>
						<?php echo $_smarty_tpl->tpl_vars['layout_content']->value;?>

					</div>
				</div>
		<div style="clear:both"></div>
		<?php $_smarty_tpl->_subTemplateRender("file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 1, -1, array('page_id'=>((string)$_smarty_tpl->tpl_vars['page_id']->value)), 0, false);
?> 
	</div>
 
	<?php echo '<script'; ?>
 async src="/templates<?php echo $_smarty_tpl->tpl_vars['template_folder']->value;?>
/js/jquery_user_main.js"><?php echo '</script'; ?>
>  
	
	<?php if ($_smarty_tpl->tpl_vars['admCheck']->value == "1") {?> 
		<?php echo '<script'; ?>
 async src="/framework/fckeditor/fckeditor.js"><?php echo '</script'; ?>
>
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
	<!-- <audio preload id="soundfile_1">
		<source src="soundfile_1.mp3" type="audio/mp3">	
	</audio>
	<audio preload id="soundfile_2">
		<source src="soundfile_2.mp3" type="audio/mp3">
	</audio>
	<audio preload id="soundfile_3">
		<source src="soundfile_4.mp3" type="audio/mp3">	
	</audio>
<div id="call2action"></div>
<?php echo '<script'; ?>
>
callBasicView_startpage('',false);
<?php echo '</script'; ?>
> -->
<link rel="stylesheet" href="/framework/fontawesome/css/all.css">
</body>
</html>
<?php }
}
