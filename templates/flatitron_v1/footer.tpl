<footer>
	<div class="footer">
	{if $domain_id == 1}
					
		<div style="float:left;margin-right:30px">
		<h2>Kontakt mit Bludau Media aufnehmen</h2>
		24/7 <a href="mailto:kontakt@bludau-media.de">kontakt@bludau-media.de</a><br/>
		Telefonisch Mo. - Fr. 9 - 17 Uhr unter 0441-2 33 33 05<br/>
		<a href="https://get.teamviewer.com/bludau-media">Teamviewer Support möglich</a>
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
			<h2>Bludau Media auf Social Media</h2>
			<ul>
				<li><a href="https://twitter.com/Bludau_Media">Bludau-Media auf Twitter</a></li>
			</ul>
			<br/><strong>Inhalt mit einem Klick bewerten</strong><div id="raty-benutzer"></div>
		</div>
 
<script>	
		
		//$('body').addClass('animated bounceInLeft');
		
	$.fn.raty.defaults.path = '../../framework/raty-2.7.0/demo/images';
	
	$('#raty-benutzer').raty({ 
	half: true, hints       : ['magenhalft', 'ausreichend', 'befriediegend', 'gut', 'sehr gut'],
	score:{$sys_score_page},
	click: function(score, evt) {
			setSaveScore({$page_id},score); 
	} 
	});	
	function setSaveScore(text_id,score) {
	var ajax_load = '';
	//<img src='image/load.gif' alt='loading...' />
		$('#acp_message').html(ajax_load).load('/vote_save.php', 'page_id=' + text_id + '&score=' + score); 
	}
	</script>
	<div style="clear:both"></div>
	
	{/if}
	</div>
	<div class="footer">
	&copy; <a title="Bludau Media" href="https://bludau-media.de">2016 Bludau-Media.de</a> | <a href="{$domain_name}/feed/">News Feed<img src="/image/rss-small.png"/> | </a><a title="MyDraft PHP Portal Software" href="https://php-consulting.com/de/7/MyDraft-CMS/">MyDraft PHP Portal Software</a> 

	</div>
	{literal}
<script>
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

</script>
	{/literal}
				</footer>


<script>
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
</script>
{literal}<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-80209657-1', 'auto');
  ga('send', 'pageview');

</script>
{/literal}
<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//bludau-media.de/framework/piwik/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', '1']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src="//bludau-media.de/framework/piwik/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->
<!-- Go to www.addthis.com/dashboard to customize your tools --> <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-594f10386266827c"></script> 