<!DOCTYPE html>
<html lang="de" prefix="og: http://ogp.me/ns#">
<head>
<!-- DNS Verbindung aufbauen -->
<link rel="dns-prefetch" href="//use.fontawesome.com">
<link rel="dns-prefetch" href="https://fonts.googleapis.com">
<link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
<link rel="preconnect" href="//use.fontawesome.com">
<link rel="preconnect" href="https://cdnjs.cloudflare.com">
<link rel="preconnect" href="https://adservice.google.de">
<link rel="preconnect" href="https://translate.googleapis.com">
<link rel="preconnect" href="https://tpc.googlesyndication.com">
<!-- CORE -->	
<link rel="preload" href="/templates/freie-welt.eu/css/template_master.css" as="style">
<!-- CORE -->
<link href="https://fonts.googleapis.com/css2?family=Trade+Winds&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@700&family=Trade+Winds&display=swap" rel="stylesheet">
<script rel="preload" src="/js/jquery-3.4.0.min.js"></script>
<script rel="preload" src="/js/track.js"></script>

{literal}
<!-- Cookie Consent by https://www.FreePrivacyPolicy.com -->
<script type="text/javascript" src="//www.freeprivacypolicy.com/public/cookie-consent/4.0.0/cookie-consent.js" charset="UTF-8"></script>
<script type="text/javascript" charset="UTF-8">
document.addEventListener('DOMContentLoaded', function () {
cookieconsent.run({"notice_banner_type":"interstitial","consent_type":"express","palette":"dark","language":"de","page_load_consent_levels":["strictly-necessary"],"notice_banner_reject_button_hide":false,"preferences_center_close_button_hide":false,"page_refresh_confirmation_buttons":false,"website_name":"FREIE-WELT.EU","website_privacy_policy_url":"https://freie-welt.eu/de/163326/%EF%BF%BDber-FW/Datenschutz/"});
});
</script>

<noscript>Cookie Consent by <a href="https://www.freeprivacypolicy.com/" rel="nofollow noopener">Free Privacy Policy Generator website</a></noscript>
<!-- End Cookie Consent -->
{/literal}
{if $meta_titel != ''}
<title>{$meta_titel} {$suche} 
{if  $seite_aktuell != ''}
	Seite: 
{/if} {$seite_aktuell}</title>	
{else}
<title>{if $suche != ''}  {$suche}{else} {if $page_title != ''}{$page_title}{/if}{/if}
{if  $seite_aktuell != ''}
	Seite: 
{/if} {$seite_aktuell}</title>
{/if}
<base href="{$domain_name}"> 
<link rel="canonical" href="{$page_url_cononical}"/>
<meta charset="utf-8">
<link rel="manifest" href="manifest.json">
<!-- <meta http-equiv="expires" content="0">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">	 -->

<meta name="subject" content="Nachrichtenportal und Aufklärung" />	
<meta name="coverage" content="Worldwide" /> 
<meta name="distribution" content="Global" />
<meta name="category" content="Nachrichtenportal" />
<meta name="owner" content="" />
<meta name="rating" content="general" /> 
<meta name="web_author" content="Jan Bludau" />

<!-- <meta name="date" content="2010-05-15T08:49:37+02:00"> -->
{if $meta_description != ''}
<meta name="description" content="{$meta_description}">
{/if}	
{if $domain_ary.meta_email != ''}
<meta name="email" content="{$domain_ary.meta_email}" />
{/if}
{if $domain_ary.meta_fb_page_id != ''}
<meta property="fb:page_id" content="{$domain_ary.meta_fb_page_id}" />
{/if}
{if $domain_ary.twitter_account_id != ''}
<meta property="twitter:account_id" content="{$domain_ary.twitter_account_id}" />
{/if} 

<!-- PWA Setup -- 
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="application-name" content="myDraft PHP CMS+">
<meta name="apple-mobile-web-app-title" content="myDraft PHP CMS+">
<meta name="theme-color" content="#2196f3">
<meta name="msapplication-navbutton-color" content="#2196f3">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="msapplication-starturl" content="/?pk_campaign=PWA&pk_kwd=startup">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="icon" type="image/jpeg" sizes="192x192" href="/templates/universelles_rss/media/favicon-96x96.png">
<link rel="apple-touch-icon" type="image/jpeg" sizes="192x192" href="/templates/universelles_rss/media/favicon-96x96.png">
<link rel="icon" type="image/jpeg" sizes="512x512" href="/templates/universelles_rss/media/favicon-96x96.png">
<link rel="apple-touch-icon" type="image/jpeg" sizes="512x512" href="/templates/universelles_rss/media/favicon-96x96.png">		
-->

{if $domain_ary.meta_nowfollow != ''} 
<meta content="{$domain_ary.meta_nowfollow}" name="robots"> 
{else}
<meta content="INDEX,FOLLOW" name="robots"> 
{/if}

{if $domain_ary.bing_meta != ''}
<meta name="msvalidate.01" content="{$domain_ary.bing_meta}" />					
{/if}	
{if $domain_ary.meta_autor != ''} 
<!-- <meta name="author" content="{$domain_ary.meta_autor}"> -->
{/if}
{if $domain_ary.meta_copyright != ''} 		 
<meta name="copyright" content="{$domain_ary.meta_copyright}"> 
{/if}
{if $domain_ary.meta_page_topic != ''} 
<meta name="page-topic" content="{$domain_ary.meta_page_topic}">
{/if}
{if $domain_ary.meta_revisit_after != ''} 
<meta name="revisit-after" content="{$domain_ary.meta_revisit_after}">
{/if}
<meta property="og:locale" content="de_DE" />
{if $domain_ary.og_type != ''}
<meta property="og:type" content="{$domain_ary.og_type}" />
{/if}  
{if $domain_ary.webseiten_name != ''} 	 
<meta property="og:site_name" content="{$domain_ary.webseiten_name}" />
{/if}

<link rel="alternate" type="application/rss+xml" title="myDraft Nachrichtenportal" href="{$domain_name}/freie-welt-nachrichten/" />
<!-- <link rel="alternate" id="alternate-androidapp" href="" /> 
<link rel="alternate" id="alternate-iosapp" href="ios-app://" /> --> 
{if $meta_titel != ''}
<meta property="og:title" content="{$meta_titel}" />
{else}
<meta property="og:title" content="{$aryPage.name_de}" />
{/if}	
{if $meta_description != ''}
<meta property="og:description" content="{$meta_description}">
{else}
<meta property="og:description" content="{$aryPage.titel_de}" />
{/if}		
<meta property="og:image" content="https://freie-welt.eu/templates/freie-welt.eu/media/favicon-96x96.png" />
<meta property="og:url" content="{$page_http_uri}" />		
<!-- <meta property="og:updated_time" content="2017-12-03T19:16:44+00:00" /> -->

{if $domain_ary.og_icon != ''} 	
<meta property="og:image" content="{$domain_ary.og_icon}" /> 

{if $domain_ary.og_image_type != ''}
<meta property="og:image:type" content="{$domain_ary.og_image_type}" />
{/if}
{if $domain_ary.og_image_width != ''}
<meta property="og:image:width" content="{$domain_ary.og_image_width}" />
{/if}
{if $domain_ary.og_image_height != ''}
<meta property="og:image:height" content="{$domain_ary.og_image_height}" />
{/if}

{/if}	

{if $domain_ary.twitter_handle_name != ''}
<meta name="twitter:site" content="@{$domain_ary.twitter_handle_name}" />
{/if}
<meta name="twitter:creator" content="@FreieWeltEu">
<!-- 
<meta name="twitter:card" content="summary_large_image">
-->
<meta name="twitter:card" content="summary">

{if $meta_titel != ''}
<meta name="twitter:title" content="{$meta_titel}" />
{else}
<meta name="twitter:title" content="{$aryPage.name_de}" />
{/if}

{if $aryPage.meta_description != ''}
<meta name="twitter:description" content="{$aryPage.meta_description}" />
{else}
<meta name="twitter:description" content="{$aryPage.titel_de}" />
{/if}	

{if $domain_ary.twitter_icon != ''}
<meta name="twitter:image" content="{$domain_ary.twitter_icon}" />
{/if} 
{if $domain_ary.twitter_icon_alt != ''}
<meta name="twitter:image:alt" content="{$domain_ary.twitter_icon_alt}" />
{/if}

<meta name="audience" content="alle">
<link rel="alternate" hreflang="de" href="{$page_http_uri}" />
<!--- <link rel="alternate" type="application/rss+xml" title="Shopste Marktplatz News" href="https://{$domain_name}/marktplatz-nachrichten/" />
<link rel="alternate" type="application/rss+xml" title="Shopste Produkt News" href="https://{$domain_name}/shop-produkte/" /> -->

{if $domain_ary.webseite_icon != ''} 
<link rel="icon" type="image/png" sizes="32x32" href="{$domain_ary.webseite_icon}">
{/if}
{if $domain_ary.webseite_icon_96 != ''} 
<link rel="icon" type="image/png" sizes="96x96" href="{$domain_ary.webseite_icon_96}">
{/if}		 
{if $domain_ary.webseite_icon_16 != ''} 
<link rel="icon" type="image/png" sizes="16x16" href="{$domain_ary.webseite_icon_16}">
{/if}  	  
{if $google_webmaster != ''}
{$google_webmaster}
{/if}

<link rel="stylesheet" href="/templates{$template_folder}/css/bootstrap/css/bootstrap.min.css">
<script src="/templates{$template_folder}/css/bootstrap/js/bootstrap.min.js"></script>
{literal}
<script>
// Smartphone Erkennung
function detectmob() { 
	 if( navigator.userAgent.match(/Android/i)
	 || navigator.userAgent.match(/webOS/i)
	 || navigator.userAgent.match(/iPhone/i)
	 || navigator.userAgent.match(/iPad/i)
	 || navigator.userAgent.match(/iPod/i)
	 || navigator.userAgent.match(/BlackBerry/i)
	 || navigator.userAgent.match(/Windows Phone/i)
	 ){
		return true;
	  }
	 else {
		return false;
	  }
	}	
</script>
{/literal}	

<link media="all" href="/templates{$template_folder}/css/template_master.css" type="text/css" rel="stylesheet">
<link media="all" href="/templates{$template_folder}/css/menu.css" type="text/css" rel="stylesheet">
<script src="/js/jquery-3.4.0.min.js"></script>
<script src="/templates{$template_folder}/js/track.js"></script>
</head>
