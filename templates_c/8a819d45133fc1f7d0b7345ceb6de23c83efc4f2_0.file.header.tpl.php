<?php
/* Smarty version 4.1.1, created on 2022-07-30 17:45:32
  from '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/Design1/header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62e5521c31e355_21660366',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8a819d45133fc1f7d0b7345ceb6de23c83efc4f2' => 
    array (
      0 => '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/Design1/header.tpl',
      1 => 1659195832,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62e5521c31e355_21660366 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
	<title><?php echo $_smarty_tpl->tpl_vars['page_title']->value;?>
</title>
		<!-- CORE PRELOADING CSS -->	
	<link rel="preload" href="/templates<?php echo $_smarty_tpl->tpl_vars['template_folder']->value;?>
/css/template_master.css" as="style">
	<link rel="preload" href="/templates<?php echo $_smarty_tpl->tpl_vars['template_folder']->value;?>
/css/bootstrap/css/bootstrap.min.css" as="style">
	<!-- CORE PRELOADING SCRIPTS-->
	<link rel="preload" src="/js/jquery-3.4.0.min.js" as="script">
	<link rel="preload" src="/js/track.js" as="script">	
	<link rel="preload" src="/templates<?php echo $_smarty_tpl->tpl_vars['template_folder']->value;?>
/css/bootstrap/js/bootstrap.min.js" as="script">

	<base href="https://<?php echo $_smarty_tpl->tpl_vars['domain_name']->value;?>
">
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta content="INDEX,FOLLOW" name="robots">
	<meta http-equiv="language" content="deutsch, de">
	<meta name="author" content="Jan Bludau">
	<meta name="copyright" content=" (c) Bludau IT Services">
	<meta name="page-topic" content="Dienstleistung,Onlineshop,Ecommerce">
	<meta name="revisit-after" content="14 days">
	<meta name="audience" content="alle">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="alternate" hreflang="de" href="<?php echo $_smarty_tpl->tpl_vars['page_http_uri']->value;?>
" />
	<link rel="alternate" type="application/rss+xml" title="Shopste Marktplatz News" href="https://<?php echo $_smarty_tpl->tpl_vars['domain_name']->value;?>
/marktplatz-nachrichten/" />
	<link rel="alternate" type="application/rss+xml" title="Shopste Produkt News" href="https://<?php echo $_smarty_tpl->tpl_vars['domain_name']->value;?>
/shop-produkte/" />
	<link rel="icon" type="image/png" sizes="32x32" href="/templates<?php echo $_smarty_tpl->tpl_vars['template_folder']->value;?>
/media/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/templates<?php echo $_smarty_tpl->tpl_vars['template_folder']->value;?>
/media/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/templates<?php echo $_smarty_tpl->tpl_vars['template_folder']->value;?>
/media/favicon-16x16.png">
	<link rel="stylesheet" href="/framework/animate.css/animate.min.css">
	<?php if ($_smarty_tpl->tpl_vars['google_webmaster']->value != '') {?>
		<?php echo $_smarty_tpl->tpl_vars['google_webmaster']->value;?>

	<?php }?>
	<!-- <?php echo '<script'; ?>
  src="/framework/jquery/jquery-1.11.2.min.js"><?php echo '</script'; ?>
> -->
	<?php echo '<script'; ?>
 src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"><?php echo '</script'; ?>
>
	<link rel="stylesheet" href="/framework/fancyBox-master/source/jquery.fancybox.css" type="text/css" media="screen" />
	<?php echo '<script'; ?>
 type="text/javascript" src="/framework/fancyBox-master/source/jquery.fancybox.pack.js"><?php echo '</script'; ?>
>			
    <?php echo '<script'; ?>
 type="text/javascript">
      //$.noConflict();	  
    <?php echo '</script'; ?>
>			

	<?php echo '<script'; ?>
  type="text/javascript" src="/framework/zoomimage/js/eye.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
  type="text/javascript" src="/framework/zoomimage/js/utils.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
  type="text/javascript" src="/framework/zoomimage/js/zoomimage.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
  src="/framework/flexnav-master/js/jquery.flexnav.min.js"><?php echo '</script'; ?>
>
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

	<link href="/framework/flexnav-master/css/flexnav.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" media="screen" type="text/css" href="/framework/zoomimage/css/zoomimage.css" />
	<link media="all" href="/templates<?php echo $_smarty_tpl->tpl_vars['template_folder']->value;?>
/css/template_master.css" type="text/css" rel="stylesheet">			 
	<?php echo '<script'; ?>
 async type="text/javascript" src="/framework/fckeditor/fckeditor.js"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="/framework/fckeditor/fckeditor.php"><?php echo '</script'; ?>
>
	<?php if ($_smarty_tpl->tpl_vars['admCheck']->value == "1") {?>
		<?php echo '<script'; ?>
 async type="text/javascript" src="/framework/fckeditor/fckeditor.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="/framework/fckeditor/fckeditor.php"><?php echo '</script'; ?>
>
		<link rel="stylesheet" type="text/css" src="/framework/jquery-ui/css/jquery-ui-1.10.4.custom.min.css"/>			
	<?php }?>
</head>
<?php }
}
