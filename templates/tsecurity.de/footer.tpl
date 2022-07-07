<footer>
	<div class="footer">
	{if $domain_id == 1}
					
						<div style="float:left;width:32%;margin-right:30px">
						<h2>Social Media von TSEC</h2>
						<ul>
							<li><a href="https://twitter.com/security_de">Auf Twitter folgen</a></li>
							<li><a href="https://www.facebook.com/tsec0/">Auf Facebook folgen</a></li>
						</ul><br/>
						Email: <a href="mailto:info@tsecurity.de">info@tsecurity.de</a> schreiben (24 / 7)<br/>
						</div>
						<div style="float:left;width:32%">
						<h2>&Uuml;ber TSEC</h2>
						<ul>
							<li><a href="https://tsecurity.de/de/3612/Ueber-TSEC/">Sitemap</a></li>
							<li><a href="https://tsecurity.de/de/2657/Impressum/">Impressum</a></li>
							<li><a href="https://tsecurity.de/de/2658/Datenschutzerklaerung/">Datenschutz</a></li>
						</ul>
						</div>
						<div style="float:left;width:32%">
						<h2>Diese Seite bewerten</h2>
						<strong>Seiteninhalt mit einem Klick bewerten</strong><div id="raty-benutzer"></div>
						
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
	<h2>RSS Feeds abonieren</h2>
	<ul>
		<li><a href="{$domain_name}/it-security-feed/"><img alt="RSS-Feed" src="/image/rss-small.png"/> TSEC alle NachrichtenNews</a></li>
		<li><a href="{$domain_name}/tsec-nachrichten/"><img alt="RSS-Feed" src="/image/rss-small.png"/> TSEC Webseiten Nachrichten</a></li>
	<ul>
	<span style="float:right">&copy; <a title="TSecurity News Portal" href="https://tsecurity.de">2015-2017 TSecurity</a></span>

	</div>
 
<script>
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
</script>
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
</script>
{literal}
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-71168265-1', 'auto');
  ga('send', 'pageview');

</script>
{/literal}
<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//tsecurity.de/framework/piwik/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', '1']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src="//tsecurity.de/framework/piwik/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->