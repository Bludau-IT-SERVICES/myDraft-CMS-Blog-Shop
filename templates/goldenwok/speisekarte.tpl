{include file="header.tpl" title="Schlemmertal.de" domain_name="$domain_name" template_folder="$template_folder" admCheck="$admCheck" page_title="$page_title" domain_id="$domain_id" google_webmaster="$google_webmaster"} 
<!-- <script src="/framework/ajax_upload/assets/js/script_speisekarte.js"></script> -->
		<script src="/framework/ajax_upload/assets/js/jquery.knob.js"></script>

		<!-- jQuery File Upload Dependencies -->
		<script  src="/framework/ajax_upload/assets/js/jquery.ui.widget.js"></script>
		<script  src="/framework/ajax_upload/assets/js/jquery.iframe-transport.js"></script>
		<script  src="/framework/ajax_upload/assets/js/jquery.fileupload.js"></script>
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
                            <li  class="current"><a href="/de/37316/Speisekarte/">Speisekarte</a></li>
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
     <div class="conf-pad3">
       <div class="container">
         
          <h2 class="dark-2-title">Asia Golden Wok Speisekarte Oldenburg</h2><br/>
		  
		{$speisekarte_html = shop_speisekarte_goldenwok('127','yes')}
		{$speisekarte_html}
		
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