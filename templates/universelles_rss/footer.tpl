<footer>
	<div class="footer">
{if $domain_id == 1}
						
		<div style="float:left;margin-right:30px">
			<h2>Kontakt</h2>
			24/7 <a href="mailto:kontakt@freie-welt.eu">kontakt@freie-welt.eu</a><br/>	 
		</div>
		<div style="float:left;margin-right:30px">
			<h2>Weitere Informationen</h2>
			<ul>
				<li><a title="FREIE-WELT.EU Impressum" href="https://freie-welt.eu/de/163325/Impressum/">Impressum</a></li>
				<li><a href="https://freie-welt.eu/de/163326/Datenschutzerklaerung/">Datenschutz</a>
			</ul>
		</div>
		<div style="float:left">
			<h2>Social Media</h2>
			<ul>
				<li><a style="color:yellow" class="button" title="TEAM SECURITY (tsecurity.de) Nachrichtenportal" href="https://tsecurity.de/?pk_campaign=franchise&pk_kwd=franchise-link&pk_source=franchise-link">Partner Nachrichtenportal Team Security
				</a></li>
				<li><a title="Nachrichten App Freie Welt" href="https://play.google.com/store/apps/details?id=com.companyname.freie_welt.eu">Nachrichten App Freie Welt</a></li>
				<li> <a href="https://freie-welt.eu/Freie-Welt-eu-1.0.0 Setup.exe">Freie Welt als Elektron App zum Download (Windows only)</a></li>
				<li><a title="FREIE-WELT.EU Nachrichtenportal auf Twitter folgen" href="https://twitter.com/FreieWeltEu">Twitter</a></li>
				<li><a title="FREIE-WELT.EU Nachrichtenportal auf Facebook folgen" href="https://www.facebook.com/FreieWelt.eu/">Facebook</a></li>
			</ul>
		</div>
	</div>
	<a title="FREIE-WELT.EU RSS-Feed Nachrichten abnonnieren" href="{$domain_name}/freie-welt-nachrichten/"><img alt="RSS-Feed" src="/image/rss-small.png"/>RSS-Feed Nachrichtenportal Freie Welt</a>
{literal}	
<script>
 
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
		
		setTrack('{/literal}{$trackid}','{$page_id}','0');{literal}
	});
</script>
{/literal}

<div style="clear:both"></div>
{/if}
</div>
<div class="footer">
	&copy; <a href="#" id="open_preferences_center">Datenschutz Einstellugen ändern</a> <a title="FREIE-WELT.EU Nachrichtenportal und Bildung | Politik | Kultur | Wissenschaft | Videos" href="https://freie-welt.eu">2015-2021 FREIE-WELT.EU Nachrichtenportal</a> | <a title="Bludau IT Services PHP Portal Software (powered by CMS myDraft)" href="https://bludau.io/">Bludau IT SERVICES - CMS "myDraft" a PHP Portal Software</a>
</div>
</footer>
{literal}
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

{/literal}