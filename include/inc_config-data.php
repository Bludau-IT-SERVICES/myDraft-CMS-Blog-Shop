<?php
###############################
# SETTINGS
# v. 1.0.0a
###############################

# CUSTOMIZE INSTALL PATH
define("CORE_SERVER_PATH",     "/var/www/vhosts/freie-welt.eu/httpdocs/");
define("CORE_PIWIK_ACTIVE",     "YES");
define("CORE_SHOP_URL",     "https://freie-welt.eu");

### RSS MODULE CONFIG
define("CORE_SERVER_RSS_ADDING_LINK",     "/de/1137/Ueber-FW/RSS-Feed-eintragen/#rss_adding");
define("CORE_SERVER_RSS_ADDING_LINK_TITLE",     "Eigene Webseite, Blog oder andere Quelle hinzufügen");
define("CORE_SERVER_RSS_ADDING_TEXT",     "Eigene Webseite / Blog / Quelle ");
define("CORE_SERVER_RSS_DOWNLOAD_TEXT",'<h3>RSS Reader</h3>Als weitere Online Plattform empfiehlt sich <a target="_blank" href="https://www.inoreader.com/">InnoReader RSS Online Web Reader</a><br/>
Es empfiehlt sich für <a title="Chrome Erweiterung "RSS-Abonnement" (von Google)" href="https://chrome.google.com/webstore/detail/rss-subscription-extensio/nlbjncdgjeocebhnmkbbbdekmmmcbfjd" target="_blank">Chrome Erweiterung "RSS-Abonnement" (von Google)</a> oder für <a title="Firefox Awesome RSS" href="https://addons.mozilla.org/de/firefox/addon/awesome-rss/?src=search" target="_blank">Firefox Addon / Erweiterung Awesome RSS</a><br/>');

#define("CORE_HTTPS",     "false");
#define("CORE_HTTPS_METHOD",     "http");
define("CORE_PIWIK_API_KEY",     "9bbbdc953850bc25ad86281db231b8ce");
define("CORE_CRON_API_KEY",     "SLHzR386lNMoKCoPJKvi");
define("CORE_DEFAULT_ITEMS_PER_PAGE_MOBILE",     "66");
define("CORE_DEFAULT_ITEMS_PER_PAGE_COMPUTER",     "99");
define("CORE_RSS_CONTENT_HTTP_PATH",     "feed");
define("CORE_RSS_NEWS_HTTP_PATH",     "/freie-welt-nachrichten/");

define("CORE_SERVER_DOMAIN",     "https://freie-welt.eu/");
define("CORE_API_DOMAIN",     "freie-welt.eu");
define("CORE_SERVER_PLATTFORM_NAME",     "Freie Welt Plattform");
define("CORE_MAIL_SEND_BCC",     "kontakt@freie-welt.eu");
define("CORE_MAIL_SEND_BCC_NAME",     "Freie Welt Support");
define("CORE_MAIL_FROM_EMAIL",     "kontakt@freie-welt.eu");
define("CORE_MAIL_FROM_EMAIL_NAME",     "Freie Welt Support");

# API KOMPONENTE NEWS
define("CORE_MAIL_FROM_API",     "kontakt@freie-welt.eu");
define("CORE_MAIL_FROM_API_NAME",     "Freie Welt API Support");
define("CORE_MAIL_SEND_API_BCC",     "kontakt@freie-welt.eu");
define("CORE_MAIL_SEND_API_BCC_NAME",     "Freie Welt API Support");
define("CORE_MAIL_FROM_API_REGISTER",     "kontakt@freie-welt.eu");
define("CORE_MAIL_FROM_API_REGISTER_NAME",     "Freie Welt Anmeldebestätigung");

# KOMPONENTE RSS-FEEDs
define("CORE_MAIL_FROM_RSS",     "kontakt@freie-welt.eu");
define("CORE_MAIL_FROM_RSS_NAME",     "RSS Support");
define("CORE_MAIL_FROM_RSS_BCC",     "kontakt@freie-welt.eu");
define("CORE_MAIL_FROM_RSS_BCC_NAME",     "RSS Support");

# KOMPONENTE NEWS
define("CORE_MAIL_FROM_NEWS",     "kontakt@freie-welt.eu");
define("CORE_MAIL_FROM_NEWS_NAME",     "News Support");
define("CORE_MAIL_FROM_NEWS_BCC",     "kontakt@freie-welt.eu");
define("CORE_MAIL_FROM_NEWS_BCC_NAME",     "News Support");

#### SOCIAL MEDIA
define("social_media_twitter_username","FreieWeltEu");
define("social_media_facebook_username","FreieWelt.eu");
define("social_media_reddit_username","PolitischeNachrichten");
define("social_media_telegramm_username","");

define("CORE_SERVER_RSS_SOCIAL_MEDIA",'<div class="social-media"><br/><h3>Social Media</h3>
&nbsp;<a href="https://www.facebook.com/'.social_media_facebook_username.'/" class="btn-social btn-facebook" title="Facebook" target="_blank" rel="noopener"><i class="fab  fa-facebook-square"></i> Eigene Facebook Seite mit ausgewählten Nachrichten.</a>&nbsp;&nbsp;&nbsp;
<a href="https://twitter.com/'.social_media_twitter_username.'" class="btn-social btn-twitter" title="Twitter" target="_blank" rel="noopener"><i class="fab  fa-twitter-square"></i> Automatische Tweets Nachrichten alle 5 Minuten neu.</a>
<br><br>
<a title="Freie Welt Nachrichtenportal" href="'.CORE_SERVER_DOMAIN.'"><i class="fa fa-home" aria-hidden="true"></i> Zur Startseite</a>
</div>');

### RSS 2 Twitter
define("twitter_account_name","FreieWeltEu");
define("twitter_rss_post_count","7");
define("twitter_news_post","Y");
define("twitter_rss_feed_on_load","N");
define("twitter_rss_cron_post","Y");
define("twitter_rss_cron_post_send_later","Y");
define("twitter_rss_category_post","Y");
define("twitter_shop_item_post","Y");
 
 define("twitter_shop_item_post_with_menu","N");
#define("twitter_shop_item_post_with_stats_day_24","N");
define("twitter_shop_item_post_with_stats_day_48","Y");
define("twitter_shop_item_post_with_stats_day_30","Y");
define("twitter_shop_item_post_with_comments","Y"); # Kommentar Modul
define("twitter_shop_item_post_with_similar","Y"); # Änliche Artikel Menü

define("twitter_oauth_access_token","2280206959-rKtbWMEQRka63r0FHuu5m5VBPtzyAiRuGfTfkcJ");
define("twitter_oauth_access_token_secret","hoOc0JGhi38Rowll14phuALYs1IcjXQjCDK8Xaowvtm50");
define("twitter_consumer_key","d6buxUSMWwt2k3dsCaqVUA4ln");
define("twitter_consumer_secret","fCn2kIUmgNAXfldyvDo4L4H3g7xkxMDbJs5QOoMyEAnZnTjrtM");

define("SECRECT_KEY","zoi(U}y>(mJlB-F_Zeid"); 
define("SECRECT_IV","qkCLm2OJ0WrV7<v28EW}"); 

### Plattformtype
define("PLATTFORM_TYPE","BLOG");
define("PLATTFORM_ITEM_TYPE","Beitrag");
define("PLATTFORM_ITEM_TYPES","Beitr&auml;ge");


# HTML SONDERZEICHEN ZU NAMEN
define("core_text_symbol_done","&#10003;"); // Das Hakensymbol für fertig
define("core_text_symbol_attention","&#10082;"); // Dickes geschwungenes Ausrufezeichen
define("core_text_symbol_paragraph","&#10081;"); // Dickes geschwungenes Absatzzeichen
define("core_text_symbol_anführungszeichen","&#10077;"); // Dickes doppeltes Anführungszeichen
define("core_text_symbol_anführungszeichen_dick","&#10075;"); // Dickes einzelnes Anführungszeichen
define("core_text_symbol_koma","&#10076;"); // Dickes einzelnes Komma
define("core_text_symbol_heart","&#10084;"); // Dickes geschwungenes Herz
define("core_text_symbol_heart_natur","&#10086;"); // Herz, floral
define("core_text_symbol_heart_natur_90","&#10087;"); // Herz, floral, 90°
define("core_text_symbol_stern_8","&#10039;"); // Stern mit acht Zacken
define("core_text_symbol_stern_4","&#10023;"); // Stern vierseitig weiß
define("core_text_symbol_stern_4_filled","&#10022;"); // Stern vierseitig gefüllt
define("core_text_symbol_stern_5_filled","&#9733;"); // 5-zackiger Stern, gefüllt
define("core_text_symbol_stern_5_weiß","&#9734;"); // 5-zackiger Stern, weiß
define("core_text_symbol_stern_filled_white","&#10026;"); // Stern weiß im gefüllten Kreis
define("core_text_symbol_stern_6","&#10039;"); // Stern mit sechs Zacken
define("core_text_symbol_schneefloke_1","&#10058;"); // Stern mit Tropfenzacken (Asterisk)
define("core_text_symbol_schneefloke_2","&#10057;"); // Stern aus Ballons (Asterisk)
define("core_text_symbol_schneefloke_3","&#10035;"); //Stern mit acht Zacken
define("core_text_symbol_hashtag","&#35;"); // Hashtag als Symbol
define("core_text_symbol_arrow_left","&#171;"); // schöner Pfeil nach links
define("core_text_symbol_link_right","&#10149;"); // schöner Pfeil nach rechts
define("core_text_symbol_email","&#9993;"); // Email-Umschlag
define("core_text_symbol_email_at","&#64;"); // schöneres at Zeichen
define("core_text_symbol_cross","&#10007;"); // X-Zeichen
define("core_text_symbol_telephone","&#9990;"); 
define("core_text_symbol_header_sample_1","&#8801;"); // Identisch, steht auch für Liste
define("core_text_symbol_header_sample_2","&#10070;"); // Vier schwarze Rauten als Kreuz
define("core_text_symbol_arrow_right","&#187;");

# System-Modul "menu"
define("core_menu_default_navigation_content_prefix_symbol","&#10149;");

# System-Modul "kontakt_form"
define("core_menu_default_header_titel","Navigation");
define("core_sitemap_preview_rss_content","N");
define("core_sitemap_preview_news_content","N");
define("core_sitemap_preview_shop_content","N");

define("core_kontakt_form_default_submit_button_text","Ihr Anliegen abschicken");
define("core_kontakt_form_default_prefix_title_symbol",core_text_symbol_email);
define("core_kontakt_form_default_title","<h2>".core_kontakt_form_default_prefix_title_symbol." Ihre Projekrealisierungs-Agentur | Projektvermittelung | Ihr Anliegen</h2>");
define("core_kontakt_form_default_messeage_header_title","Ihre Nachricht an ".CORE_SERVER_PLATTFORM_NAME);
define("core_kontakt_form_default_content_text","Bludau Media ist auch telefonisch zu diesem Thema zu erreichen unter ".core_text_symbol_telephone." +049 0441 - 2 3333 05.<br/>Erstkontakt kostenlos.<br/>Auch per Whatsapp Chat unter ".core_text_symbol_telephone." 0176 - 62 00 36 26 zu erreichbar.<br/><br/>"); 
define("core_kontakt_form_after_send_messagebox_content","<strong>Vielen Dank f&uuml;r Ihre Anfrage bei '".CORE_SERVER_PLATTFORM_NAME."'! <br/>Sie erhalten in K&uuml;rze eine Best&auml;tigungsemail Ihrer Anfrage<br/>Wir werden schnellst möglichst Rückantworten.</strong>");

# System-Modul "Login"
define("core_system_login_header_title",'Login in den Administrationsbereich');
define("core_system_login_header_header",'Login in den Administrationsbereich');

define("core_system_login_email_invalid",'<br/><font color="red">Ihre Emailadresse wurde noch nicht bestätigt oder Sie wurden gesperrt!<br/>');
define("core_system_login_wrong",'<font color="red">Falscher Login</font>');
define("core_system_login_ok",'<font color="red">LOGIN OK</font>');

date_default_timezone_set("Europe/Berlin");

define("HOST","127.0.0.1");
define("USER","");
define("PASS","");
define("DB","");

// server should keep session data for AT LEAST 1 hour
//ini_set('session.gc_maxlifetime', 360000);

// each client should remember their session id for EXACTLY 1 hour
//session_set_cookie_params(360000);

$path = dirname(__FILE__);
include_once($path."/../libs/mysqli.php");
DBi::$conn = new mysqli(HOST, USER, PASS, DB);

#DBi::$conn->query("SET NAMES 'utf8mb4'");
#DBi::$conn->query("SET CHARACTER SET 'utf8mb4'");	

function encrypt_decrypt($action, $string) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = SECRECT_KEY;
    $secret_iv = SECRECT_IV;
    // hash
    $key = hash('sha256', $secret_key);
    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if( $action == 'decrypt' ) {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}
?>
