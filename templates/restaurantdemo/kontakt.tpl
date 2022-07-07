{include file="header.tpl" title="Schlemmertal.de" domain_name="$domain_name" template_folder="$template_folder" admCheck="$admCheck" page_title="$page_title" domain_id="$domain_id" google_webmaster="$google_webmaster"} 
  <script src="/templates{$template_folder}/js/TMForm.js"></script>
  <script src="/templates{$template_folder}/js/modal.js"></script>  
 <script src='http://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false'></script> 
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
                            <li><a href="/de/37640/Lieferservice/">Lieferservice</a></li>
							
                            <li><a href="/de/37641/Speisekarte/">Speisekarte</a></li>
                            <li class="current"><a href="/de/37642/Kontakt/"> Kontakt</a></li>
                      
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
     <div class="conf-pad3 white_bottom_edge">
      <div class="container">
        <h2 class="dark-2-title">Kontakt aufnehmen</h2>


        <div class="row">
          <div class="grid_12">
                   <div class="contact-form-body">
          <form id="contact-form" method="post" action="#">
             <fieldset>
               
                  
                  <div class="row">
                    <div class="grid_12">
                      <label class="name" style="margin-left: 20px;">
                      <input type="text" name="name" placeholder="Ihr Name" value="" data-constraints="@Required @JustLetters"  />
                      <span class="empty-message">*Feld erforderlich.</span>
                      <span class="error-message">*Dieser Name ist ungültig.</span>
                    </label>
                    </div>
                  </div>
                    

                    <div class="row">
                      <div class="grid_6"><label class="email">
                    <input type="text" name="email" placeholder="Ihre Email" value="" data-constraints="@Required @Email" />
                      <span class="empty-message">*Feld erforderlich.</span>
                      <span class="error-message">*Email nicht gültig.</span>
                    </label></div>
                      <div class="grid_6"><label class="name phone">
                      <input type="text" name="last_name" placeholder="Ihre Telefonnummer" value="" data-constraints="@JustNumbers"  />
                      <span class="empty-message">*Feld erforderlich.</span>
                      <span class="error-message">*Keine gültige Telefonnummer.</span>
                    </label></div>
                    </div>
                    <div class="row"> 
                      <div class="grid_6"><label class="email">
                    <input type="text" name="reservierung_datum" id="datepicker" placeholder="Ihr Reservierungswunsch Datum" value="" />
                      <span class="empty-message">*Feld erforderlich.</span>
                    </label></div>
					
                      <div class="grid_6"><label class="name phone">
                      <input type="text" name="reservierung_uhrzeit" id="timepicker1" placeholder="Ihr Reservierungswunsch Uhrzeit" value="" />
                      <span class="empty-message">*Feld erforderlich.</span>
                      <span class="error-message">*Keine gültige Telefonnummer.</span>
                    </label></div>
					 
                    </div>                
                  <label class="message">
                      <textarea name="message" rows="7" placeholder="Ihre Nachricht" data-constraints='@Required @Length(min=20,max=999999)'></textarea>
                      <span class="empty-message">*Feld erforderlich.</span>
                      <span class="error-message">*Nachricht zu kurz.</span>
                    </label>
                    <!-- <label class="recaptcha"><span class="empty-message">*Feld erforderlich.</span></label> -->
                    <div class="form-buttons">
                      <div class="button-send">
                        <a href="#" class="circle-more send-button"  data-type="submit" >Senden</a>
                        <div class="clear"></div>
                      </div>
                    </div>
          </fieldset> 
           <div class="modal fade response-message">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title">Modal title</h4>
                </div>
                <div class="modal-body">
                  Ihre Nachricht wurde gesendet! Wir werden uns bald bei Ihnen melden.
                </div>      
              </div>
            </div>
          </div>
          </form>
          </div>
          </div>
        </div>
        </div>

     </div>
{literal}
	 <script>
	 $(document).ready(function() {	
                 $(function() {
                     $("#datepicker").datepicker({
       prevText: '&#x3c;zurück', prevStatus: '',
        prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
        nextText: 'Vor&#x3e;', nextStatus: '',
        nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
        currentText: 'heute', currentStatus: '',
        todayText: 'heute', todayStatus: '',
        clearText: '-', clearStatus: '',
        closeText: 'schließen', closeStatus: '',
        monthNames: ['Januar','Februar','März','April','Mai','Juni',
        'Juli','August','September','Oktober','November','Dezember'],
        monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun',
        'Jul','Aug','Sep','Okt','Nov','Dez'],
        dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
        dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
        dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
      showMonthAfterYear: false,
      showOn: 'focus',
	  showWeek: false,
	  showButtonPanel:  false,
	  changeMonth: false,
	  changeYear: false,
setDate:new Date(),
      dateFormat:'d MM, yy'
    } );    
                 }); 
                 $(function() {
$('#timepicker1').timepicker({ 'timeFormat': 'G:i','scrollDefault': 'now','showDuration': false,'step': 15,'maxTime': '22:30','minTime':'12:00','useSelect':false });
$('#timepicker1').on("selectTime", function() {
	var time = $('#timepicker1').val();
	var timeparts = time.split(':');
	if((timeparts[0] >= '15') && (timeparts[0] < '18')) {
		alert('Restaurant Mekong ist um ' + time + ' von 15 Uhr - 18 Uhr in der Mittagspause, bitte andere Uhrzeit selektieren! ');	
		$('#timepicker1').val('');		
	}
});
                 });
				 
});				 
                </script>
{/literal}				
     <div class="map">
             <div class="google-map-api"> 
                 <iframe width=100% src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2393.3082357269805!2d8.21402041614776!3d53.14056247993643!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47b6df060903021b%3A0x7ad754fce7200149!2sStaulinie+20%2C+26122+Oldenburg!5e0!3m2!1sde!2sde!4v1454933457392" height="600" frameborder="0" style="border:0" allowfullscreen></iframe>
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