{include file="header.tpl" domain_name="$domain_name" template_folder="$template_folder" admCheck="$admCheck" page_title="$page_title" domain_id="$domain_id" google_webmaster="$google_webmaster"} 
<link rel="stylesheet" href="/templates{$template_folder}/css/camera.css">		
<script src="/templates{$template_folder}/js/owl.carousel.js"></script>
<script src="/templates{$template_folder}/js/camera.js"></script>
<link rel="stylesheet" href="/templates{$template_folder}/css/touchTouch.css">
<script>
  $(document).ready(function(){
        $('.gall_item').touchTouch();
    });
</script>
 <!--[if lt IE 8]>
   <div style=' clear: both; text-align:center; position: relative;'>
     <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
       <img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />
    </a>
  </div>
<![endif]-->
<!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <link rel="stylesheet" type="text/css" media="screen" href="/templates{$template_folder}/css/ie.css">
<![endif]-->
<script>

      $(window).load(function(){
      $('#camera_wrap').camera({
        loader: false,
        pagination: true ,
        minHeight: '300',
        thumbnails: false,
        height: '33.1794817948718%',
        caption: false,
        navigation: false,
        fx: 'mosaic',
      });
       });

    $(document).ready(function(){
        var owl = $("#owl"); 
            owl.owlCarousel({
            items : 1, //1 items above 1000px browser width
            itemsDesktop : [979,1], //1 items between 1000px and 901px
            itemsDesktopSmall : [767, 1], // betweem 900px and 601px
            itemsTablet: [700, 1], //1 items between 600 and 0
            itemsMobile : [479, 1], // itemsMobile disabled - inherit from itemsTablet option
            navigation : true,
            pagination :  false
            });
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
                            <li class="current"><a href="/">Home</a></li>
                            <li><a href="/de/37316/Speisekarte/">Speisekarte</a></li>
                            <li><a href="/de/37315/Lieferservice/">Lieferservice</a></li>
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
      <div class="slide-bg">
      <div id="camera_wrap">
        <div data-src="/templates/goldenwok/media/slider/startseite/slide1.jpg">
           
        </div>
        <div data-src="/templates/goldenwok/media/slider/startseite/slide2.jpg">
         
        </div>
 
      </div>  
    </div>
      <div class="white_top_edge pt64">
        <div class="container">
          <div class="row">
            <div class="grid_12 wow fadeInRight">
              <div class="wrapper2" style="margin-bottom:23px;">
                <div class="wrap-info">
                  <h2 class="dark-2-title" style="margin-bottom:25px;">Herzlich Willkommen bei Asia Golden Wok! </h2>
                  
                  <div class="txt"><p>
                    Wir begrüßen Sie hier auf unserer Internetseite und natürlich auch gerne bald in unserem Schnellrestaurant in Oldenburg. Hier servieren wir Ihnen köstliche Spezialitäten aus der chinesischen
Küche, die wir mit viel Sorgfalt und Liebe zum kulinarischen Detail zubereiten. Seien Sie unser Gast, und wir verzaubern Sie mit unserer Kochkunst.</p></div>
                </div>
                <div class="clear"></div>
              </div>
            </div>
          </div>

          <div class="row" style="margin-right: 0;
margin-left: 25%;">
            <div class="imple-wrap-3 align-center" style="margin-left:10px;margin-bottom:40px;">
			
				<span style="width:180px;float:left;margin-right:25px">
					<img src="/templates/goldenwok/media/bilder/startseite-1.jpg" style="float:left;margin: 4px 20px 4px 0;display: inline;min-height:141px"/>Suchen Sie ein pünktlich serviertes und leicht bekömmliches Menü für Ihre Mittagspause? Dann empfehlen wir Ihnen unser <a href="/de/37315/Lieferservice/">Mittagsmenü</a>.
				</span>
				<span style="width:180px;float:left;margin-right:25px">
					<img src="/templates/goldenwok/media/bilder/startseite-2.jpg" style="float:left;margin: 4px 20px 4px 0;display: inline;min-height:141px;"/>Wünschen Sie ein festliches Abendessen, das Sie mit mehreren Gängen durch den besonderen Abend begleitet?
				</span>				
				<span style="width:180px;float:left;margin-right:25px">
					<img src="/templates/goldenwok/media/bilder/startseite-3.jpg" style="float:left;margin: 4px 20px 4px 0;display: inline;min-height:141px;"/>Seien Sie unser Gast, und wir verzaubern Sie mit unserer Kochkunst.
				</span>							
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
<script src="/templates{$template_folder}/js/touchTouch.jquery.js"></script>
 <script src="/templates{$template_folder}/js/script.js"></script>
<div id="footer"><div id="shop_footer">{$cart_info_bar}</div></div>
</body>
</html>