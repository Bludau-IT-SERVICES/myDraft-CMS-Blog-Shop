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
                            <li  class="current"><a href="/de/37315/Lieferservice/">Lieferservice</a></li>
                            <!-- <li class="inner_menu"><a href="/de/37314/Galerie/">Galerie</a></li>-->							
                            <li><a href="/de/37385/Anfahrt/">Anfahrt</a></li>
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
     <div class="conf-pad3">
       <div class="container">
          <h2 class="dark-2-title">Liefertelefon:<br/><br/>0441 3404508 oder 59067902 oder 59067903</h2>
		  
          
            <div class="grid_12 wow fadeInRight">
               <div class="wrapper1 pt17">
                <div class="wrap-info">
                  <h3 class="orange-3-title pt51">Momentan ist eine online Bestellung nicht möglich<br/>
				  Wir liefern innerhalb von Oldenburg ab einem Bestellwert von 10€ kostenlos. Andere Orte gegen eine kleine Lieferpauschale auf Anfrage.
                  <p>
                    <br/>
Lieferzeiten<br/>
<br/>
Täglich von 17.00 bis 22.00 Uhr<br/>
Kostenlose Lieferung im Umkreis von 4km. Jeder weiterer km 1,50€ Aufpreis.<br/></h3>


                  </p>
                </div>
                <div class="clear"></div>
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