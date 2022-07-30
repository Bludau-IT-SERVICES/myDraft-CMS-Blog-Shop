<?php
/* Smarty version 4.1.1, created on 2022-07-30 17:54:10
  from '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/flatitron_v1/footer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62e55422e3ac17_86691562',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e71fc84d5d3b7738ce1f03e77e0160161d3c31cf' => 
    array (
      0 => '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/flatitron_v1/footer.tpl',
      1 => 1659196146,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62e55422e3ac17_86691562 (Smarty_Internal_Template $_smarty_tpl) {
?><footer>
	<div class="footer">
	<?php if ($_smarty_tpl->tpl_vars['domain_id']->value == 1) {?>					
		<div style="float:left;margin-right:30px">
		<h2>Kontakt mit aufnehmen</h2>
		24/7 <a href="mailto:kontakt@bludau-media.de">kontakt@mydomain.com</a><br/>
		Telefonisch Mo. - Fr. 9 - 17 Uhr unter 0441-2 33 33 05<br/>
		</div>
		<div style="float:left;margin-right:30px">
			<h2>Weitere wichtige Informationen</h2>
			<ul>
				<li><a href="/de/3511/Kostenloser-Online-Shop-bei-Shopste/">In 5 Minuten einen eigenen Online Shop erstellen</a></li>
				<li><a href="/de/3518/Bludau-Media-Windows-Software/Eiso-Verkaufsabwicklung/">EiSo Verkaufsabwicklung für eBay, Shopste, Delcampe, EiSo Shop</a></li>
				<li><a href="/de/3520/Bludau-Media-Windows-Software/JTL-WaWi-Translator-3/">JTL Translator 3 - JTL WaWi mit Google übersetzen</a></li>  
				<li><a href="/de/3523/Bludau-Media-Windows-Software/JTL-WaWi-Lagerbestand-Report/">JTL Lagerbestand + eMail Report mit Bildern</a></li>  
				<li><a href="/de/3519/Bludau-Media-Windows-Software/Shopste-Lister/">Shopste CSV Importer</a></li>  
				<li><a href="/de/3527/Ueber-mich/Impressum/">Impressum</a></li>
			</ul>  
		</div>
		<div style="float:left">
			<h2>Social Media</h2>
			<ul>
				<li><a href="https://twitter.com/">Twitter</a></li>
			</ul>
		</div>
	<div style="clear:both"></div>
<?php }?>
</div>
	<div class="footer">&copy; <a title="Bludau IT Services" href="https://bludau.io">2022 Bludau.io</a> | <a href="<?php echo $_smarty_tpl->tpl_vars['domain_name']->value;?>
/feed/">News Feed<img src="/image/rss-small.png"/> | </a><a title="MyDraft PHP Portal Software" href="https://php-consulting.com/de/7/MyDraft-CMS/">MyDraft PHP Portal Software</a></div>
	
<?php echo '<script'; ?>
>
/*
    $(".flexnav").flexNav({         
		'animationSpeed' : 'fast',
         'calcItemWidths': true , // dynamically calcs top level nav item widths
         //'hoverIntent': true, // true for use with hoverIntent plugin
         'hoverIntentTimeout': 150 // hoverIntent default timeout
		});
*/
		
	
$(window).bind('scroll', function () {
    if ($(window).scrollTop() > 50) {
        $('.flexnav').addClass('fixed');
    } else {
        $('.flexnav ').removeClass('fixed');
    }
});

<?php echo '</script'; ?>
>
	
</footer>
<?php echo '<script'; ?>
>
$('.menue').addClass('wow zoomIn');
$('.texthtml').addClass('wow zoomInDown');
$('.portal_shop_cat_list').addClass('wow zoomInDown');
$('.shop_cat_list').addClass('wow zoomInDown');
$('.registrieren_shopste').addClass('wow zoomIn');
$('.portal_umkreis').addClass('wow zoomIn');
$('.portal_gebuehrenanzeige').addClass('wow zoomIn');
$('.portal_shop_item_detail').addClass('wow zoomIn');
$('.menue_shopcategory').addClass('wow zoomIn');
$('.portal_userlogin').addClass('wow zoomIn');
$('.shop_item_detail').addClass('wow zoomIn');
$('.lvw_item_single').addClass('wow zoomIn');
$('.kontakt_form').addClass('wow zoomIn');
$('.footer').addClass('wow zoomIn');
$('.sitemap').addClass('wow zoomIn');
$('.content img').addClass('wow zoomIn');
$('.header').addClass('wow zoomInUp');
$('.flexnav').addClass('wow zoomInRight');
<?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-80209657-1', 'auto');
  ga('send', 'pageview');

<?php echo '</script'; ?>
>

<?php }
}
