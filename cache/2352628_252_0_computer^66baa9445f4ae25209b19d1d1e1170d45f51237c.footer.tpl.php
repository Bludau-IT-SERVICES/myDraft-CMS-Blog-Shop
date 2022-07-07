<?php
/* Smarty version 4.1.1, created on 2022-07-07 12:59:11
  from '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/universelles_rss/footer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62c6bc7fab2eb6_63914143',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '04506329723a67a785969510d74cf6ca2d2b72f0' => 
    array (
      0 => '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/universelles_rss/footer.tpl',
      1 => 1657190914,
      2 => 'file',
    ),
  ),
  'cache_lifetime' => -1,
),true)) {
function content_62c6bc7fab2eb6_63914143 (Smarty_Internal_Template $_smarty_tpl) {
?><footer>
	<div class="footer">
</div>
<div class="footer">
	&copy; <a href="#" id="open_preferences_center">Datenschutz Einstellugen ändern</a> <a title="Bludau IT Services PHP Portal Software (powered by CMS myDraft)" href="https://bludau.io/">Bludau IT SERVICES CMS "myDraft" a PHP Portal Software</a>
</div>
</footer>

<script>
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
		
				window.addEventListener("load", function(){
		window.cookieconsent.initialise({
		  "palette": {
			"popup": {
			  "background": "#000"
			},
			"button": {
			  "background": "#f1d600"
			}
		  },
		  "content": {
			"message": "Diese Webseite verwendet Cookies. Wenn Sie diese Webseite weiterhin besuchen, stimmen Sie der Nutzung von Cookies zu. Weitere Informationen finden Sie unter Datenschutz",
			"dismiss": "Habe verstanden",
			"link": "Weitere Informationen",
			"href": "https://freie-welt.eu/de/163326/Datenschutzerklaerung/"
		  }
		})});
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
</script>

<?php }
}
