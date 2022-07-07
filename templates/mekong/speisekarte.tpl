{include file="header.tpl" title="Schlemmertal.de" domain_name="$domain_name" template_folder="$template_folder" admCheck="$admCheck" page_title="$page_title" domain_id="$domain_id" google_webmaster="$google_webmaster"} 
</head>
<body>
			{if $admCheck == "1"}
				{include file="adminpanel.tpl" admCheck="$admCheck"} 
			{/if}
  <!--========================================================
                            HEADER 
  =========================================================-->

  <header id="header">
    
                <div class="header-top-bottom grey_bottom_edge">
                  
                 
                </div>


                <div class="texture-back header-back align-center">
                   <div class="header-logo">
                    <a href="/"><div class="icon" style="background-color:#FFF"><img src="/templates/mekong/media/bilder/kugeloben.png"/></div></a>
                    <h1 id="logo_text">Restaurant Mekong</h1>
                    <p class="logo-quote">Fernöstlich Genießen</p></a>
                    

                  </div>

                  <div id="stuck_container">
                    <div class="header-navigation">
                        <nav>
                          <ul class="sf-menu">
                            <li><a href="/">Über uns</a></li>
                            <li class="inner_menu"><a href="/de/34283/Galerie/">Galerie</a></li>
                            <li><a href="/de/34284/Lieferservice/">Lieferservice</a></li>
							
                            <li class="current" ><a href="/de/34286/Speisekarte/">Speisekarte</a></li>
                            <li><a href="/de/34285/Kontakt/"> Kontakt</a></li>
                      
                          </ul>
                        </nav>
                  <div class="clear"></div>

                </div>
                  </div>
                </div>


  </header>
<!--========================================================
                            CONTENT 
  =========================================================-->
  
  <section id="content">
     <div class="conf-pad3">
       <div class="container">
         
          <h2 class="dark-2-title">Restaurant Mekong Speisekarte Oldenburg</h2>
          <h3 class="orange-3-title pt37">Unsere Speisekarte</h3>

		{$speisekarte_html = shop_speisekarte_goldenwok('61','yes')}
		{$speisekarte_html}
		
       </div>


     </div>

    </section>

    
    <!--========================================================
                            FOOTER
  =========================================================-->

     <footer class="dark_top_edge back-dark">
       <div class="container">
         <div class="row">
           <div class="grid_4">
              <p class="foot1"><a href="/"><span class="footer-title">Restaurant Mekong</span></a>
              &copy; <span id="copyright-year"></span><span class="vert-line">|</span><a class="footer-link" href="/de/34287/Datenschutz/">Datenschutz</a>|</span><a class="footer-link" href="/de/36862/Impressum/">Impressum</a></p>
           </div>
           <div class="grid_4">
             <div class="align-center">
              <img src="images/gmap_marker.png" alt="marker"><br>
            <p class="foot2">
               Restaurant Mekong<br>
                Staulinie 20 (bei der Bushaltestelle) <br>
            26 122 Oldenburg <br>
           Telefon: 0441 - 26 00 0 <br>
           0441 - 17 36 6<br>
</p>
             </div>
           </div>
           <div class="grid_4">
             <ul class="footer-socials">
               <li><a href=""><i class="fa fa-facebook"></i></a></li>
               <li><a href=""><i class="fa fa-rss"></i></a></li>
               <li><a href=""><i class="fa fa-twitter"></i></a></li>
               <li><a href=""><i class="fa fa-google-plus"></i></a></li>
             </ul>
           </div>
         </div>
       </div>
        
      
      
    </footer> 
   
    <!--========================================================
                            END FOOTER
  =========================================================-->
  
<script>
function css_column_height (css_class,icount) {
	var max_height = 0;
	$(css_class).each(function(e) {
	  h = $(this).height();
	  if(typeof(h) != "undefined") {
			if(h > max_height) {
					max_height = h;
			}
	  }
	});
	if(max_height > 0) {
		if( css_class.indexOf( ".autosize" ) !== -1 ) {
			//max_height	+= 20;
			$(css_class).height(max_height);		
		} else {	
			if (icount == "1") {
				//console.log(max_height); 
			}
			max_height += 20;
			$(css_class).height(max_height);
			//max_height += 20;
			if (icount == "1") {
				//console.log(max_height); 
			}
			$(".speisekarte_nr" + icount).height(max_height);
			$(".speisekarte_nr" + icount).each(function(e) {
			  h = $(this).height();
			  if(typeof(h) != "undefined") {
					if(h > max_height) {
							max_height = h;
					}
			  }
			});
			//alert(max_height);
			$("#box_id_" + icount).height(max_height);
		}
	}
}
</script>
<span id="popup"></span>
<script src="/templates{$template_folder}/js/script.js"></script>
<div id="footer"><div id="shop_footer">{$cart_info_bar}</div></div>
</body>
</html>