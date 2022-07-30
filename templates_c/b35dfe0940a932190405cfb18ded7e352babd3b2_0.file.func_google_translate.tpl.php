<?php
/* Smarty version 4.1.1, created on 2022-07-30 17:54:10
  from '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/flatitron_v1/func_google_translate.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62e55422e2ec69_50395031',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b35dfe0940a932190405cfb18ded7e352babd3b2' => 
    array (
      0 => '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/flatitron_v1/func_google_translate.tpl',
      1 => 1656941469,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62e55422e2ec69_50395031 (Smarty_Internal_Template $_smarty_tpl) {
?><!-- GTranslate: https://gtranslate.io/ -->
<a href="#" onclick="doGTranslate('de|en');return false;" title="English" class="gflag nturl" style="background-position:-0px -0px;"><img src="//gtranslate.net/flags/blank.png" height="16" width="16" alt="English" /></a><a href="#" onclick="doGTranslate('de|fr');return false;" title="French" class="gflag nturl" style="background-position:-200px -100px;"><img src="//gtranslate.net/flags/blank.png" height="16" width="16" alt="French" /></a><a href="#" onclick="doGTranslate('de|de');return false;" title="German" class="gflag nturl" style="background-position:-300px -100px;"><img src="//gtranslate.net/flags/blank.png" height="16" width="16" alt="German" /></a><a href="#" onclick="doGTranslate('de|it');return false;" title="Italian" class="gflag nturl" style="background-position:-600px -100px;"><img src="//gtranslate.net/flags/blank.png" height="16" width="16" alt="Italian" /></a><a href="#" onclick="doGTranslate('de|pt');return false;" title="Portuguese" class="gflag nturl" style="background-position:-300px -200px;"><img src="//gtranslate.net/flags/blank.png" height="16" width="16" alt="Portuguese" /></a><a href="#" onclick="doGTranslate('de|ru');return false;" title="Russian" class="gflag nturl" style="background-position:-500px -200px;"><img src="//gtranslate.net/flags/blank.png" height="16" width="16" alt="Russian" /></a><a href="#" onclick="doGTranslate('de|es');return false;" title="Spanish" class="gflag nturl" style="background-position:-600px -200px;"><img src="//gtranslate.net/flags/blank.png" height="16" width="16" alt="Spanish" /></a>

<style type="text/css">
<!--

a.gflag {vertical-align:middle;font-size:16px;padding:1px 0;background-repeat:no-repeat;background-image:url(//gtranslate.net/flags/16.png);}
a.gflag img {border:0;}
a.gflag:hover {background-image:url(//gtranslate.net/flags/16a.png);}
#goog-gt-tt {display:none !important;}
.goog-te-banner-frame {display:none !important;}
.goog-te-menu-value:hover {text-decoration:none !important;}
body {top:0 !important;}
#google_translate_element2 {display:none!important;}

-->
</style>

<br /><select onchange="doGTranslate(this);"><option value="">Select Language</option><option value="de|af">Afrikaans</option><option value="de|sq">Albanian</option><option value="de|ar">Arabic</option><option value="de|hy">Armenian</option><option value="de|az">Azerbaijani</option><option value="de|eu">Basque</option><option value="de|be">Belarusian</option><option value="de|bg">Bulgarian</option><option value="de|ca">Catalan</option><option value="de|zh-CN">Chinese (Simplified)</option><option value="de|zh-TW">Chinese (Traditional)</option><option value="de|hr">Croatian</option><option value="de|cs">Czech</option><option value="de|da">Danish</option><option value="de|nl">Dutch</option><option value="de|en">English</option><option value="de|et">Estonian</option><option value="de|tl">Filipino</option><option value="de|fi">Finnish</option><option value="de|fr">French</option><option value="de|gl">Galician</option><option value="de|ka">Georgian</option><option value="de|de">German</option><option value="de|el">Greek</option><option value="de|ht">Haitian Creole</option><option value="de|iw">Hebrew</option><option value="de|hi">Hindi</option><option value="de|hu">Hungarian</option><option value="de|is">Icelandic</option><option value="de|id">Indonesian</option><option value="de|ga">Irish</option><option value="de|it">Italian</option><option value="de|ja">Japanese</option><option value="de|ko">Korean</option><option value="de|lv">Latvian</option><option value="de|lt">Lithuanian</option><option value="de|mk">Macedonian</option><option value="de|ms">Malay</option><option value="de|mt">Maltese</option><option value="de|no">Norwegian</option><option value="de|fa">Persian</option><option value="de|pl">Polish</option><option value="de|pt">Portuguese</option><option value="de|ro">Romanian</option><option value="de|ru">Russian</option><option value="de|sr">Serbian</option><option value="de|sk">Slovak</option><option value="de|sl">Slovenian</option><option value="de|es">Spanish</option><option value="de|sw">Swahili</option><option value="de|sv">Swedish</option><option value="de|th">Thai</option><option value="de|tr">Turkish</option><option value="de|uk">Ukrainian</option><option value="de|ur">Urdu</option><option value="de|vi">Vietnamese</option><option value="de|cy">Welsh</option><option value="de|yi">Yiddish</option></select><div id="google_translate_element2"></div>
<?php echo '<script'; ?>
 type="text/javascript">

function googleTranslateElementInit2() {new google.translate.TranslateElement({pageLanguage: 'de',autoDisplay: false}, 'google_translate_element2');}

<?php echo '</script'; ?>
><?php echo '<script'; ?>
 type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit2"><?php echo '</script'; ?>
>


<?php echo '<script'; ?>
 type="text/javascript">

/* <![CDATA[ */
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('6 7(a,b){n{4(2.9){3 c=2.9("o");c.p(b,f,f);a.q(c)}g{3 c=2.r();a.s(\'t\'+b,c)}}u(e){}}6 h(a){4(a.8)a=a.8;4(a==\'\')v;3 b=a.w(\'|\')[1];3 c;3 d=2.x(\'y\');z(3 i=0;i<d.5;i++)4(d[i].A==\'B-C-D\')c=d[i];4(2.j(\'k\')==E||2.j(\'k\').l.5==0||c.5==0||c.l.5==0){F(6(){h(a)},G)}g{c.8=b;7(c,\'m\');7(c,\'m\')}}',43,43,'||document|var|if|length|function|GTranslateFireEvent|value|createEvent||||||true|else|doGTranslate||getElementById|google_translate_element2|innerHTML|change|try|HTMLEvents|initEvent|dispatchEvent|createEventObject|fireEvent|on|catch|return|split|getElementsByTagName|select|for|className|goog|te|combo|null|setTimeout|500'.split('|'),0,{}))
/* ]]> */

<?php echo '</script'; ?>
>
 <?php }
}
