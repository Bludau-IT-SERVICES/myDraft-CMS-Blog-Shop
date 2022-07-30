<?php
/* Smarty version 4.1.1, created on 2022-07-30 06:03:06
  from '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/freie-welt.eu/footer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62e4ad7a5ec889_57320873',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1235cb829030c216927df231ebdf6f381753df8b' => 
    array (
      0 => '/var/www/vhosts/bludau.io/cms-mydraft.bludau.io/templates/freie-welt.eu/footer.tpl',
      1 => 1659152428,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62e4ad7a5ec889_57320873 (Smarty_Internal_Template $_smarty_tpl) {
?><footer>
	<div class="footer">
<?php if ($_smarty_tpl->tpl_vars['domain_id']->value == 1) {?>
						
		<div style="float:left;margin-right:30px">
			<h2>Kontakt</h2>
			24/7 <a href="mailto:kontakt@mydomain.com">kontakt@mydomain.com</a><br/>	 
		</div>
		<div style="float:left;margin-right:30px">
			<h2>Weitere Informationen</h2>
			<ul>
				<li><a title="Impressum" href="/de/3/Impressum/">Impressum</a></li>
			</ul>
		</div>
		<div style="float:left">
			<h2>Social Media</h2>
			<ul>
				<li><a title="Twitter" href="https://twitter.com/">Twitter</a></li>
				<li><a title="Facebook" href="https://www.facebook.com/">Facebook</a></li>
			</ul>
		</div>
	</div>
	
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
				   .then(function() { console.log("myDraft CMS Service Worker registriert"); });
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
	&copy; <a title="" href="https://cms-mydraft.bludau.io/">2022 myDraft CMS by Bludau IT SERVICES</a> | <a title="Bludau IT SERVICES" href="https://bludau.io/">Bludau IT SERIVCES CMS "myDraft" a PHP Portal Software</a>
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
