<!-- <link rel="stylesheet" href="/framework/animate.css/animate.min.css"> -->
<!-- <script async src="/templates{$template_folder}/js/jquery_user_main.js"></script>  -->
{if $domain_id != 1}
	<div id="footer"><span id="frame_detail_info"></span><div id="shop_footer">{$cart_info_bar}</div></div>
{/if}

{if $admCheck == "1"}
	<script async src="/frameworks/jqueriy-ui/jquery-ui.min.js"></script>
	<script async src="/js/jquery_admin_main.js"></script>
	
	<!-- jQuery File Upload Abhängigkeiten -->
	<script async src="js/jquery.uploadprogress.0.3.js"></script>
	<script async src="/framework/ajax_upload/assets/js/jquery.knob.js"></script>
	<script async src="/framework/ajax_upload/assets/js/jquery.ui.widget.js"></script>
	<script async src="/framework/ajax_upload/assets/js/jquery.iframe-transport.js"></script>
	<script async src="/framework/ajax_upload/assets/js/jquery.fileupload.js"></script>
	<script src="/framework/ckeditor/ckeditor.js"></script> 

{/if}
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
							"background-color": "#F5F6FA",
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
					"background-color": "#F5F6FA",
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
			"background-color": "#F5F6FA",
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
				  "background-color": "#F5F6FA",
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
			"background-color": "#F5F6FA",
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