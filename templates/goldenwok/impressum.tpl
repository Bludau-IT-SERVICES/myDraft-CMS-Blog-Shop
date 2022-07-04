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
{include file="tpl_header.tpl"} 
                  <div id="stuck_container">
                    <div class="header-navigation">
                        <nav>
                          <ul class="sf-menu">
                            <li><a href="/">Home</a></li>
                            <li><a href="/de/37316/Speisekarte/">Speisekarte</a></li>
                            <li><a href="/de/37315/Lieferservice/">Lieferservice</a></li>
                            <!-- <li class="inner_menu"><a href="/de/37314/Galerie/">Galerie</a></li>-->							
                            <li><a href="/de/37385/Anfahrt/">Anfahrt</a></li>
                            <li><a href="/de/37317/Kontakt/">Kontakt</a></li>
                            <li class="current"><a href="/de/37384/Impressum/">Impressum</a></li>
                      
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

 <h2 class="dark-2-title">Impressum</h2>
 <br/><br/>
<table class="Imprint" style="margin-left: 7px; width: 407px; height: 155px;"><tbody>
<tr>
<td class="Label">
				Inhaber</td>
			<td>
				Duc Son Nguyen&nbsp; • Asia Golden Wok •</td>
		</tr>
<tr>
<td class="Label">
				Adresse:</td>
			<td>
				Staustr. 9<br>
				26122 Oldenburg<br>
				Deutschland Germany</td>
		</tr>
<tr>
<td class="Label">
				Telefon:</td>
			<td>
				+49 441 3404508</td>
		</tr>
<tr>
<td class="Label">
				Administrator:</td>
			<td>
				Jan Bludau</td>
		</tr>
<tr>
<td>
				E-Mail:</td>
<td><a class="link_normal" href="mailto:info@asia-goldenwok.de">info@asia-goldenwok.de</a></td>
		</tr>
</tbody></table>
<p class="ImprintText">
	<br><strong>Geschäftsführer:</strong> Duc Son Nguyen</p>
<p class="ImprintText">
	<!-- <strong>Steuer-Nr.: </strong>64/131/14642<br><strong>Ust-ID:</strong></p> -->
<p class="ImprintText">
	&nbsp;</p>
<p class="ImprintText">
	Gerichsstand Oldenburg</p>
 
<p class="ImprintText">
	<strong>Konzeption: Ngoc Hoa Lu</strong></p>
<p class="ImprintText">
	Fotografie und Gestaltung: <strong>Fabio Maiaroli</strong></p>
<p>
	Inhaltlich Verantwortlicher gemäß § 55 Abs. 2 RStV: <strong>Duc Son Nguyen</strong></p>
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

 {include file="tpl_footer.tpl" domain_name="$domain_name" template_folder="$template_folder" admCheck="$admCheck" page_title="$page_title" domain_id="$domain_id" google_webmaster="$google_webmaster"} 
   
    <!--========================================================
                            END FOOTER
  =========================================================-->
  
<span id="popup"></span>
 <script src="/templates{$template_folder}/js/script.js"></script>
 <div id="footer"><div id="shop_footer">{$cart_info_bar}</div></div>
</body>
</html>