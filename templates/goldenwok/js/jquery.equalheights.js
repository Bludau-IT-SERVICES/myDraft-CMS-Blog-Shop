/*parsed HTML*/
jQuery(function(){
	jQuery(".maxheight, .maxheight2").each(function(){
		jQuery(this).contents().wrapAll("<div class='box_inner'></div>");
	})
})
/*add event*/
jQuery(window).bind("resize", height_handler).bind("load", height_handler)
function height_handler(){

	if(jQuery(window).width()> 480 ){
		jQuery(".maxheight").equalHeights()
	}else{
		jQuery(".maxheight").css({'height':'auto'});
	}

	if(jQuery(window).width()> 480 ){
		jQuery(".maxheight").equalHeights()
	}else{
		jQuery(".maxheight1").css({'height':'auto'});
	}
    
}
/*glob function*/
(function($){
	$.fn.equalHeights=function(minHeight,maxHeight){
		tallest=(minHeight)?minHeight:0;
		this.each(function(){
			if($(">.box_inner", this).outerHeight()>tallest){
				tallest=$(">.box_inner", this).outerHeight()
			}
		});
		if((maxHeight)&&tallest>maxHeight) tallest=maxHeight;
		return this.each(function(){$(this).height(tallest)})
	}
})(jQuery)