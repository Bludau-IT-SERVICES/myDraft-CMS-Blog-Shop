<?php
/* Smarty version 4.1.1, created on 2022-07-07 12:37:38
  from '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/freie-welt.eu/footer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62c6b772ed95b7_10256149',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1235cb829030c216927df231ebdf6f381753df8b' => 
    array (
      0 => '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/freie-welt.eu/footer.tpl',
      1 => 1656941469,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62c6b772ed95b7_10256149 (Smarty_Internal_Template $_smarty_tpl) {
?><footer>
	<div class="footer">
<?php if ($_smarty_tpl->tpl_vars['domain_id']->value == 1) {?>
						
		<div style="float:left;margin-right:30px">
			<h2>Kontakt</h2>
			24/7 <a href="mailto:kontakt@freie-welt.eu">kontakt@freie-welt.eu</a><br/>	 
		</div>
		<div style="float:left;margin-right:30px">
			<h2>Weitere Informationen</h2>
			<ul>
				<li><a title="FREIE-WELT.EU Impressum" href="https://freie-welt.eu/de/163325/Impressum/">Impressum</a></li>
			</ul>
		</div>
		<div style="float:left">
			<h2>Social Media</h2>
			<ul>
				<li><a title="FREIE-WELT.EU Nachrichtenportal auf Twitter folgen" href="https://twitter.com/FreieWeltEu">Twitter</a></li>
				<li><a title="FREIE-WELT.EU Nachrichtenportal auf Facebook folgen" href="https://www.facebook.com/FreieWelt.eu/">Facebook</a></li>
			</ul>
		</div>
	</div>
	<a title="FREIE-WELT.EU RSS-Feed Nachrichten abnonnieren" href="<?php echo $_smarty_tpl->tpl_vars['domain_name']->value;?>
/freie-welt-nachrichten/"><img alt="RSS-Feed" src="/image/rss-small.png"/>RSS-Feed Nachrichtenportal Freie Welt</a>
	
<?php echo '<script'; ?>
>
 
	$(document).ready(function() {	 	
		if (detectmob() == true) {
			$("#menu").css({
				"position":"relative",
				"box-shadow":"0 0 10px #85888C",
				"margin": "-50px 0 0 -50px",
				"-webkit-font-smoothing": "antialiased",
				"transform-origin":"0% 0%",
				"transform": "translate(-100%, 0)",
				"transition": "transform 0.5s cubic-bezier(0.77,0.2,0.05,1.0)",
				"overflow": "auto",
				"height": "50px", 
				"left": "-650px",
				"width": "360px",
				"top": "950px",
				"transform":"translate(-100%,0%)",
				"transform-origin": "5px 0px",
				"transition": "transform 1.5s cubic-bezier(0.77,0.2,0.05,1.0),background 0.5s cubic-bezier(0.77,0.2,0.05,1.0),opacity 1.55s ease;"
			});
		} else {
			$("#menu").css({
			  "position": "fixed",
			  "width": "180px",
			  "height": "400px",
			  "box-shadow": "0 0 10px #85888C",
			  "margin": "-50px 0 0 -50px",
			  "padding": "50px",
			  "padding-top": "125px",
			  "-webkit-font-smoothing": "antialiased",
			  "transform-origin": "0% 0%",
			  "transform": "translate(-105%, 0%)",
			  "transition": "transform 0.5s cubic-bezier(0.77,0.2,0.05,1.0)",
			  "overflow": "",
			  "display":"none",
			  "left": "115px",
			  "top": "150px"
			});
		}	
		
		if('serviceWorker' in navigator) {
		  navigator.serviceWorker
				   .register('/sw.js')
				   .then(function() { console.log("Freie Welt Service Worker registriert"); });
		}
		
		//$('body').addClass('animated bounceInLeft');
		
		$.fn.raty.defaults.path = '../../framework/raty-2.7.0/demo/images';
		
		$('#raty-benutzer').raty({ 
		half: true, hints       : ['magenhalft', 'ausreichend', 'befriediegend', 'gut', 'sehr gut'],
		score:<?php echo $_smarty_tpl->tpl_vars['sys_score_page']->value;?>
,
		click: function(score, evt) {
				setSaveScore(<?php echo $_smarty_tpl->tpl_vars['page_id']->value;?>
,score); 
		} 
		});	

		function setSaveScore(text_id,score) {
		var ajax_load = '';
		//<img src='image/load.gif' alt='loading...' />
			$('#acp_message').html(ajax_load).load('/vote_save.php', 'page_id=' + text_id + '&score=' + score); 
		}
		setTrack('<?php echo $_smarty_tpl->tpl_vars['trackid']->value;?>
','<?php echo $_smarty_tpl->tpl_vars['page_id']->value;?>
','0');
	});
<?php echo '</script'; ?>
>


<div style="clear:both"></div>
<?php }?>
</div>
<div class="footer">
	&copy; <a title="FREIE-WELT.EU Nachrichtenportal | Politik | Kultur | Wissenschaft | Videos" href="https://freie-welt.eu">2015-2021 FREIE-WELT.EU Nachrichtenportal</a> | <a title="Bludau Media PHP Portal Software (powered by CMS myDraft)" href="https://bludau-media.de/">Bludau Media CMS "myDraft" a PHP Portal Software</a>
</div>
</footer>

<?php echo '<script'; ?>
>
$(document).ready(function(){
	$("#menuToggle").click(function() {
		if ($(".menu_clicked")[0] != 'undefined'){
			//alert($(".menu_clicked")[0]);
			$("#togglemenu").prop('checked', true);	
			$("#menu").addClass("menu_clicked");
			
			$("#menu").removeAttr("style");
			if (detectmob() == true) {
				if($('#togglemenu').is(':checked')) {	
					$("#menu").css({
							"position":"relative",
							"box-shadow":"0 0 10px #85888C",
							"margin": "-15px 0px 0px -15px",
							"padding": "50px",
							"padding-top": "125px",
							"-webkit-font-smoothing": "antialiased",
							"overflow": "auto",
							"right": "-",
							"width": "100%",
							"height": "100%",
							"top": "-340px",
							"left": "",
							"z-index": "9999",
							"transform":"",
							"transform-origin":""
						});
					} 
			} else {
				
					if($('#togglemenu').is(':checked')) {	
	
					} 			
			
			}
		
		} else {			
			alert($(".menu_clicked")[0]);
			$("#menu").removeClass("menu_clicked");
			$("#togglemenu").prop('checked', false);	
			
			if (detectmob() == true) {
				$("#menu").css({
					"position":"relative",
					"box-shadow":"0 0 10px #85888C",
					"margin": "-50px 0 0 -50px",
					"-webkit-font-smoothing": "antialiased",
					"transform-origin":"0% 0%",
					"transform": "translate(-100%, 0)",
					"transition": "transform 0.5s cubic-bezier(0.77,0.2,0.05,1.0)",
					"overflow": "auto",
					"height": "50px",
					"left": "-650px",
					"width": "360px",
					"top": "50px",
					"z-index": "9999",
					"transform":"translate(-100%,0%)",
					"transform-origin": "5px 0px",
					"transition": "transform 1.5s cubic-bezier(0.77,0.2,0.05,1.0),background 0.5s cubic-bezier(0.77,0.2,0.05,1.0),opacity 1.55s ease;"});
			}
		}
	});
	
if (detectmob() == false) {
	$("#menu").mouseenter(function(){
		if ($(".mouseenter")[0]){
		
		} else {
			$("#menu").css({
			"display":"",
			"position":"fixed",
			"z-index":"9999",
			"box-shadow":"0 0 10px #85888C",
			"margin": "-50px 0 0 -50px",
			"padding": "50px",
			"padding-top": "125px",
			"-webkit-font-smoothing": "antialiased",
			"transform-origin":"0% 0%",
			"transform": "translate(-100%, 0)",
			"transition": "transform 0.5s cubic-bezier(0.77,0.2,0.05,1.0)",
			"overflow": "auto",
			"height": "400px",
			"left": "650px",
			"width": "550px",
			"top": "150px",
			"transform-origin": "0px 0px",
			"transform": "translate(-650px, 0)",
			"transition": "transform 0.5s cubic-bezier(0.77,0.2,0.05,1.0)",
			});
			$("#menu").addClass("mouseenter");
			$(".fa-angle-right").css({"display":"none"});
			//$("#menuToggle input").click();		
		} 

	});
	$("#menu").mouseleave(function() {
		if ($(".mouseenter")[0]){
			$("#menu").removeAttr("style");
			$("#menu").removeClass("mouseenter");
			//$("#menuToggle input").click();
			$("#menu").css({
				  "display":"",
				  "position": "fixed",
				  "z-index":"9999",
				  "width": "180px",
				  "height": "400px",
				  "box-shadow": "0 0 10px #85888C",
				  "margin": "-50px 0 0 -50px",
				  "padding": "50px",
				  "padding-top": "125px",
				  "-webkit-font-smoothing": "antialiased",
				  "transform-origin": "0% 0%",
				  "transform": "translate(-650px, 0)",
				  "transition": "transform 0.5s cubic-bezier(0.77,0.2,0.05,1.0)",
				  "overflow": "",
				  "left": "115px",
				  "top": "150px"
				});
			$(".fa-angle-right").css({"font-size": "100px","position": "fixed","top": "37.5%","left": "0.5%","z-index": "1000","display":""});
		} else {
		
		}
	});	
	} 
	if (detectmob() == false) {
	$(".fa-angle-right").mouseenter(function(){
		if ($(".mouseenter")[0]){
		
		} else {
			$("#menu").css({
			"display":"",
			"position":"fixed",
			"box-shadow":"0 0 10px #85888C",
			"margin": "-50px 0 0 -50px",
			"padding": "50px",
			"padding-top": "125px",
			"-webkit-font-smoothing": "antialiased",
			"transform-origin":"0% 0%",
			"transform": "translate(-100%, 0)",
			"transition": "transform 0.5s cubic-bezier(0.77,0.2,0.05,1.0)",
			"overflow": "auto",
			"height": "400px",
			"left": "750px",
			"width": "550px",
			"top": "150px",
			"z-index": "9999",
			"transform":"translate(-100%,0%)",
			"transform-origin": "5px 0px",
			"transition": "transform 1.5s cubic-bezier(0.77,0.2,0.05,1.0),background 0.5s cubic-bezier(0.77,0.2,0.05,1.0),opacity 1.55s ease;"});
			//$("#menuToggle input").click();		
			$(".fa-angle-right").css({"display":"none"});
		} 

	});
 
	} else {
		$(".fa-angle-right").css({"display":"none"});
	}
});
<?php echo '</script'; ?>
>
<?php }
}
