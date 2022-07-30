<?php
/* Smarty version 4.1.1, created on 2022-07-30 17:39:38
  from '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/Design1/footer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62e550ba73a2f0_74580129',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a823b66d9daf83461d55dfbd35ad43aeea160282' => 
    array (
      0 => '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/Design1/footer.tpl',
      1 => 1659195268,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62e550ba73a2f0_74580129 (Smarty_Internal_Template $_smarty_tpl) {
?><footer>
	<div class="footer">
	<?php if ($_smarty_tpl->tpl_vars['domain_id']->value == 1) {?>
		<div style="float:left;margin-right:30px">
		<h2>Kontakt mit Shopste.com aufnehmen</h2>
		24/7 <a href="mailto:kontakt@shopste">kontakt@shopste.com</a><br/>
		Telefonisch Mo. - Fr. 9 - 17 Uhr unter 0441-2 33 33 05<br/>
		Teamviewer Support möglich
		</div>
		<div style="float:left;margin-right:30px">
			<h2>Weitere wichtige Informationen</h2>
			<ul>
				<li><a href="http://shopste.com/de/891/Ueber-Shopste/Kontakt-aufnehmen/" title="Kontakt aufnehmen">Kontakt aufnehmen</a></li>
				<li><a href="http://shopste.com/de/528/Ueber-Shopste/AGB/" title="Shopste Allgemeine Geschäftsbedinungen">Shopste AGB</a></li>	
				<li><a href="http://shopste.com/de/529/Ueber-Shopste/Widerruf/">Shopste Widerruf</a></li>
				<li><a href="http://shopste.com/marktplatz-nachrichten/">Shopste Marktplatz RSS Feed Nachrichten</a></li>
				<li><a href="http://shopste.com/shop-produkte/">Shopste Marktplatz neuste Produkte RSS Feed</a></li>
				<li><a href="http://www.php-consulting.com/de/2/PHP-Entwicklung/" title="PHP Dienstleistungen">PHP Dienstleistungen</a></li> 
				<li><a href="http://downloads.cubss.net/" title="Softwareprodukte JTL Erweiterungen, etc.">Software Produkte Downloaden</a></li>
			</ul>
		</div>
		<div style="float:left">
			<h2>Shopste.com auf Social Media</h2>
			&copy; Bludau-Media 2015 <br/><strong>Inhalt mit einem Klick bewerten</strong><div id="raty-benutzer"></div>
			<ul>
				<li><a href="https://twitter.com/Shopste">Bei Twitter</a> | <a href="https://www.facebook.com/pages/Bludau-Media/194094684113578?ref=hl">Bei Facebook</a></li>
			</ul>
		</div>
		<?php echo '<script'; ?>
>	
			$('body').addClass('animated bounceInLeft');
		<?php echo '</script'; ?>
>	
<?php }?>
	</div>
	<div class="footer">&copy; <a title="shopste.com Marktplatz" href="http://shopste.com">2015 shopste.com</a> | <a title="MyDraft Portal Software" href="http://www.php-consulting.com/de/7/MyDraft-CMS/">MyDraft PHP Software</a></div>
	<div class="box" id="box" style="overflow:auto;">
	<a class="boxclose" id="boxclose" style="float:right">Schlie&szlig;en</a>
	<span id="overlay_header"><h1>Rechnung erstellen</h1></span>
	<p id="acp_message">
	Rechnung erstellen
	</p>
	</div>
	<div class="overlay" id="overlay" style="display:none;"></div>
	<?php echo '<script'; ?>
>
		$(".flexnav").flexNav({         
			'animationSpeed' : 'fast',
			'calcItemWidths': true , // dynamically calcs top level nav item widths
			//'hoverIntent': true, // true for use with hoverIntent plugin
			'hoverIntentTimeout': 150 // hoverIntent default timeout
			});
		
		
	$(window).bind('scroll', function () {
		if ($(window).scrollTop() > 50) {
			$('.flexnav').addClass('fixed');
		} else {
			$('.flexnav ').removeClass('fixed');
		}
	});
	<?php echo '</script'; ?>
>
</footer><?php }
}
