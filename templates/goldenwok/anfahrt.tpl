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
{include file="tpl_header.tpl"} 

                  <div id="stuck_container">
                    <div class="header-navigation">
                        <nav>
                          <ul class="sf-menu">
                            <li><a href="/">Home</a></li>
                            <li><a href="/de/37316/Speisekarte/">Speisekarte</a></li>
                            <li><a href="/de/37315/Lieferservice/">Lieferservice</a></li>
                            <!-- <li class="inner_menu"><a href="/de/37314/Galerie/">Galerie</a></li>-->							
                            <li  class="current"><a href="/de/37385/Anfahrt/">Anfahrt</a></li>
                            <li><a href="/de/37317/Kontakt/">Kontakt</a></li>
                            <li><a href="/de/37384/Impressum/">Impressum</a></li>
                      
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
     
	 
       <div class="container">
                    <div class="row">
            <div class="grid_12 wow fadeInRight">
               <div class="wrapper1 pt17">
	 <h2 class="dark-2-title">Anfahrt</h2>
 
                <div class="wrap-info">
                  <h3 class="orange-3-title pt51"> Unser Restaurant liegt inmitten der gepflegten und belebten Innenstadt.</h3>
                  <p>
		   <iframe width="100%" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2393.317377716439!2d8.213446815940909!3d53.14039839781813!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47b6df06055d8d71%3A0x1b029c4ecc903b80!2sStaustra%C3%9Fe+9%2C+26122+Oldenburg!5e0!3m2!1sde!2sde!4v1455899601988" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
 
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

 {include file="tpl_footer.tpl" domain_name="$domain_name" template_folder="$template_folder" admCheck="$admCheck" page_title="$page_title" domain_id="$domain_id" google_webmaster="$google_webmaster"} 
   
    <!--========================================================
                            END FOOTER
  =========================================================-->
  
<span id="popup"></span>
 <script src="/templates{$template_folder}/js/script.js"></script>
<div id="footer"><div id="shop_footer">{$cart_info_bar}</div></div>
</body>
</html>