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
                    <a href="/"><div class="icon"><i class="fa fa-glass"></i></div>
                    <h1>Restaurant Mekong</h1>
                    <p class="logo-quote">Fernöstlich Genießen</p></a>
                    

                  </div>

                  <div id="stuck_container">
                    <div class="header-navigation">
                        <nav>
                          <ul class="sf-menu">
                            <li><a href="/">Über uns</a></li>
                            <li class="inner_menu"><a href="/de/34283/Galerie/">Galerie</a></li>
                            <li><a href="/de/34284/Lieferservice/">Lieferservice</a></li>
							
                            <li><a href="/de/34286/Speisekarte/">Speisekarte</a></li>
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
    <div class="container">
      <div class="row">
        <div class="grid_12">
          <div class="page-text">

<h1>
	Impressum</h1>
<table class="Imprint" style="margin-left: 7px; width: 407px; height: 155px;"><tbody>
<tr>
<td class="Label">
				Inhaberin</td>
			<td>
				Thi Dong Nguyen&nbsp; • Restaurant Mekong •</td>
		</tr>
<tr>
<td class="Label">
				Adresse:</td>
			<td>
				Staulinie 20<br>
				26122 Oldenburg<br>
				Deutschland Germany</td>
		</tr>
<tr>
<td class="Label">
				Telefon:</td>
			<td>
				+49 (0) 441 26 000 / 0441 17 366</td>
		</tr>
<tr>
<td class="Label">
				Administrator:</td>
			<td>
				Jan Bludau</td>
		</tr>
<tr>
<td class="Label">
				E-Mail:</td>
			<td>
				<a href="mailto:bestellung@restaurantmekong.de">bestellung@restaurantmekong.de</a>
</td>
		</tr>
</tbody></table>
<p class="ImprintText">
	<br><strong>Geschäftsführerin:</strong> Thi Dong Nguyen</p>
<p class="ImprintText">
	<strong>Steuer-Nr.: </strong>64/131/14642<br><strong>Ust-ID:</strong></p>
<p class="ImprintText">
	&nbsp;</p>
<p class="ImprintText">
	Gerichsstand Oldenburg</p>
 
<p class="ImprintText">
	<strong>Konzeption: Ngoc Hoa Lu</strong></p>
<p class="ImprintText">
	Fotografie und Gestaltung: <strong>Fabio Maiaroli</strong></p>
<p>
	Inhaltlich Verantwortlicher gemäß § 55 Abs. 2 RStV: <strong>Thi Dong Nguyen</strong></p>
<p>
	Haftungshinweis: Trotz sorgfältiger inhaltlicher Kontrolle übernehmen wir keine Haftung für die Inhalte sogenannter externer HTML-Links. Für den Inhalt der verlinkten Seiten sind ausschließlich deren Betreiber verantwortlich.</p>
<p>
	Keine Abmahnung ohne den vorherigen Kontakt zu uns!</p>
<p>
	Sollten durch die Aufmachung oder den Inhalt dieser Internetseiten Rechte Dritter oder gesetzliche Bestimmungen verletzt worden sein, so bitten wir Sie um eine entsprechende Nachricht ohne Kostennote. Wir garantieren die unverzügliche Entfernung der zu Recht beanstandeten Passagen, ohne dass die Einschaltung eines Rechtbeistandes von Ihnen aus erforderlich ist. Sollten dennoch von Ihnen Kosten ausgelöst werden, ohne dass vorher eine Kontaktaufnahme mit uns erfolgt ist, werden wir diese voll umfänglich zurückweisen. Gegebenenfalls werden wir wegen Verletzung vorgenannter Bestimmungen Widerklage einreichen.</p>
           
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
              <p class="foot2"> Restaurant Mekong<br>
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