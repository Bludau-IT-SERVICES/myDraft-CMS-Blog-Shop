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
                            <li class="current"><a href="/de/37317/Kontakt/">Kontakt</a></li>
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
     <div class="conf-pad3 white_bottom_edge">
      <div class="container">
        <h2 class="dark-2-title">Kontakt aufnehmen</h2>


        <div class="row">
		
<div style="margin-left:30px">		
<br/>So erreichen Sie uns<br/>
<br/>
Asia Golden Wok<br/>
Staustr. 9<br/>
26122 Oldenburg<br/>
<br/>
Reservierungen nehmen wir gern unter 0441 3404508 entgegen.<br/>
<br/>
Hat es Ihnen bei uns gefallen?<br/>
Oder können wir etwas für Sie in Zukunft noch besser machen?<br/>
Dann schicken Sie uns doch gern ein kurzes Feedback über unser Kontaktformular.<br/>
</div>
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

    </section>

    
    <!--========================================================
                            FOOTER
  =========================================================-->
 {include file="tpl_footer.tpl" domain_name="$domain_name" template_folder="$template_folder" admCheck="$admCheck" page_title="$page_title" domain_id="$domain_id" google_webmaster="$google_webmaster"} 
      
      
    </footer> 
   
    <!--========================================================
                            END FOOTER
  =========================================================-->

<span id="popup"></span>	  
 <script src="/templates{$template_folder}/js/script.js"></script>
<div id="footer"><div id="shop_footer">{$cart_info_bar}</div></div>
</body>
</html>