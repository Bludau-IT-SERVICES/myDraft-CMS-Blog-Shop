{include file="header.tpl" title="Schlemmertal.de" domain_name="$domain_name" template_folder="$template_folder" admCheck="$admCheck" page_title="$page_title" domain_id="$domain_id" google_webmaster="$google_webmaster"} 
 <!--[if lt IE 8]>
   <div style=' clear: both; text-align:center; position: relative;'>
     <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
       <img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />
    </a>
  </div>
<![endif]-->
<!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <link rel="stylesheet" type="text/css" media="screen" href="css/ie.css">
<![endif]-->
<script>
  $(document).ready(function(){
        $('.gall_item').touchTouch();
    });
</script>

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
                    <h1>Restaurant Mekong</h1>
                    <p class="logo-quote">Fernöstlich Genießen</p></a>
                    

                  </div>

                  <div id="stuck_container">
                    <div class="header-navigation">
                        <nav>
                          <ul class="sf-menu">
                            <li><a href="/">Über uns</a></li>
                            <li class="inner_menu"><a href="/de/37639/Galerie/">Galerie</a></li>
                            <li class="current"><a href="/de/37640/Lieferservice/">Lieferservice</a></li>
							
                            <li><a href="/de/37641/Speisekarte/">Speisekarte</a></li>
                            <li><a href="/de/37642/Kontakt/"> Kontakt</a></li>
                      
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

          <h2 class="dark-2-title">Liefertelefon: 0441-26000</h2>

          <div class="row">
            <div class="grid_12 wow fadeInRight">
               <div class="wrapper1 pt17">
                <div class="circle back-orange"><img src="/templates/mekong/media/galerie/page3_image1.jpg" alt="post image"></div>
                <div class="wrap-info">
                  <h3 class="orange-3-title pt51"> Wir liefern Ihnen unsere frisch zubereiteten Köstlichkeiten <u>innerhalb Oldenburgs</u> zu Ihnen nach Hause oder an Ihren Arbeitsplatz.
                    Unsere Lieferzeiten sind täglich von 18.00 – 22.30 Uhr.</h3>
                  <p>
                    Lieferzeiten: Innerhalb Oldenburgs 15-45 Minuten.

                  </p>
                </div>
                <div class="clear"></div>
              </div>
            </div>
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
  
<span id="popup"></span>
 <script src="/templates{$template_folder}/js/script.js"></script>
<div id="footer"><div id="shop_footer">{$cart_info_bar}</div></div>
</body>
</html>