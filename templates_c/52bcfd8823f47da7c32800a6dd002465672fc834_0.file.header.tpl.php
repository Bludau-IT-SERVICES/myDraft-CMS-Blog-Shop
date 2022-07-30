<?php
/* Smarty version 4.1.1, created on 2022-07-30 17:54:10
  from '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/flatitron_v1/header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62e55422e261f0_34027586',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '52bcfd8823f47da7c32800a6dd002465672fc834' => 
    array (
      0 => '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/flatitron_v1/header.tpl',
      1 => 1659196420,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62e55422e261f0_34027586 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<HTML xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
	<title><?php echo $_smarty_tpl->tpl_vars['page_title']->value;?>
</title>
	<base href="<?php echo $_smarty_tpl->tpl_vars['domain_name']->value;?>
">
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">		
	<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['meta_nofollow'] != '') {?>
		<meta content="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['meta_nofollow'];?>
" name="robots">
		<?php } else { ?>
		<meta content="INDEX,FOLLOW" name="robots">
	<?php }?>		
	<meta name="author" content="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['meta_autor'];?>
">
	<meta name="copyright" content=" (c) <?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['meta_autor'];?>
"> 
	<meta name="page-topic" content="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['meta_page_topic'];?>
">
	<meta name="revisit-after" content="<?php echo $_smarty_tpl->tpl_vars['domain_ary']->value['meta_revisit_after'];?>
">
	<meta name="audience" content="alle">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="alternate" hreflang="de" href="<?php echo $_smarty_tpl->tpl_vars['page_http_uri']->value;?>
" />
	<!-- <link rel="alternate" type="application/rss+xml" title="Shopste Marktplatz News" href="<?php echo $_smarty_tpl->tpl_vars['domain_name']->value;?>
/marktplatz-nachrichten/" />
	<link rel="alternate" type="application/rss+xml" title="Shopste Produkt News" href="<?php echo $_smarty_tpl->tpl_vars['domain_name']->value;?>
/shop-produkte/" /> -->
	<link rel="icon" type="image/png" sizes="32x32" href="/templates<?php echo $_smarty_tpl->tpl_vars['template_folder']->value;?>
/img/bludau-media-icon.png">
	<link rel="stylesheet" href="/framework/animate.css/animate.min.css">
	<!-- <link href="/templates<?php echo $_smarty_tpl->tpl_vars['template_folder']->value;?>
/css/flexnav.css" rel="stylesheet" type="text/css" /> -->	
	<link media="all" href="/templates<?php echo $_smarty_tpl->tpl_vars['template_folder']->value;?>
/css/template_master.css" type="text/css" rel="stylesheet">	
	<link rel="stylesheet" href="/framework/fancyBox-master/source/jquery.fancybox.css" type="text/css" media="screen" />
	<!-- <link rel="stylesheet" type="text/css" href="/framework/yui/build/fonts/fonts-min.css" />	-->
	<?php if ($_smarty_tpl->tpl_vars['google_webmaster']->value != '') {?>
		<?php echo $_smarty_tpl->tpl_vars['google_webmaster']->value;?>

	<?php }?>
    <!-- Bootstrap and demo CSS -->
    <link href="/framework/yamm3-master/demo/components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/framework/yamm3-master/demo/components/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="/framework/yamm3-master/demo/css/demo.css" rel="stylesheet">
	<?php echo '<script'; ?>
 type="text/javascript" src="/framework/ckeditor/ckeditor.js"><?php echo '</script'; ?>
>
	<!-- <?php echo '<script'; ?>
 src="/framework/yamm3-master/demo/components/jquery/dist/jquery.js"><?php echo '</script'; ?>
> -->
	<?php echo '<script'; ?>
 src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="/framework/ckeditor/adapters/jquery.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="/framework/yamm3-master/demo/components/bootstrap/dist/js/bootstrap.min.js"><?php echo '</script'; ?>
>
    <!-- Yamm styles-->
    <link href="/framework/yamm3-master/yamm/yamm.css" rel="stylesheet">
	
	<!-- <?php echo '<script'; ?>
  src="/framework/jquery/jquery-1.11.2.min.js"><?php echo '</script'; ?>
> -->
	<?php echo '<script'; ?>
 src="//code.jquery.com/ui/1.11.4/jquery-ui.js"><?php echo '</script'; ?>
>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<?php echo '<script'; ?>
 type="text/javascript" src="/framework/fancyBox-master/source/jquery.fancybox.pack.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript">
      //$.noConflict();	  
    <?php echo '</script'; ?>
>			
	<!-- <?php echo '<script'; ?>
  async type="text/javascript" src="/framework/zoomimage/js/eye.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
  async type="text/javascript" src="/framework/zoomimage/js/utils.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
  async type="text/javascript" src="/framework/zoomimage/js/zoomimage.js"><?php echo '</script'; ?>
> 
	<?php echo '<script'; ?>
  src="/framework/raty-2.7.0/lib/jquery.raty.js"><?php echo '</script'; ?>
> -->
	<!-- <?php echo '<script'; ?>
  src="/framework/flexnav-master/js/jquery.flexnav.min.js"><?php echo '</script'; ?>
> -->
	<!-- <link async media="all" href="/js/cloud-zoom/cloud-zoom.css" type="text/css" rel="stylesheet"> -->	
	<!-- <?php echo '<script'; ?>
 src="/js/cloud-zoom/cloud-zoom.js"><?php echo '</script'; ?>
> 
	<?php echo '<script'; ?>
 async src="/js/jquery_user_main.js"><?php echo '</script'; ?>
>
	-->
	<?php echo '<script'; ?>
 defer src="/js/jquery_user_main.js"><?php echo '</script'; ?>
>
	
	<?php echo '<script'; ?>
 async src="/framework/ajax_upload/assets/js/jquery.knob.js"><?php echo '</script'; ?>
>

	<!-- jQuery File Upload Dependencies -->
	<?php echo '<script'; ?>
  src="/framework/ajax_upload/assets/js/jquery.ui.widget.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
  src="/framework/ajax_upload/assets/js/jquery.iframe-transport.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
  src="/framework/ajax_upload/assets/js/jquery.fileupload.js"><?php echo '</script'; ?>
>
		
	<?php echo '<script'; ?>
 src="/templates<?php echo $_smarty_tpl->tpl_vars['template_folder']->value;?>
/js/track.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="/framework/jquery_lazyload/jquery.lazyload.min.js"><?php echo '</script'; ?>
>  			 
	<?php echo '<script'; ?>
 async type="text/javascript" src="/framework/fckeditor/fckeditor.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 async type="text/javascript" src="/framework/fckeditor/fckeditor.php"><?php echo '</script'; ?>
>
	<?php if ($_smarty_tpl->tpl_vars['admCheck']->value == "1") {?>
		<?php echo '<script'; ?>
 async type="text/javascript" src="/framework/fckeditor/fckeditor.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 async type="text/javascript" src="/framework/fckeditor/fckeditor.php"><?php echo '</script'; ?>
>
		<!-- <?php echo '<script'; ?>
 type="text/javascript" src="/framework/jquery-ui/jquery-ui.min.js"><?php echo '</script'; ?>
> -->
		<link rel="stylesheet" type="text/css" src="/framework/jquery-ui/css/jquery-ui-1.10.4.custom.min.css"/>
		
	<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['domain_ary']->value['bWebShopAnimation'] == 'shop_animation_on') {?>
		  <?php echo '<script'; ?>
 src="/templates<?php echo $_smarty_tpl->tpl_vars['template_folder']->value;?>
/js/wow/wow.js"><?php echo '</script'; ?>
>
  <?php echo '<script'; ?>
>
    $(document).ready(function () {       
      //if ($('html').hasClass('desktop')) {
        new WOW().init();
      //} else {
	//}	  
    });

  <?php echo '</script'; ?>
> 
  <?php }?>
</head>
<?php }
}
