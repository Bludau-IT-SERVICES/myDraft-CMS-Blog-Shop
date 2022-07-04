<?php
	session_start();
	include_once('../include/inc_config-data.php');
	include_once('../include/inc_basic-functions.php');

	function getDistance($addressFrom, $addressTo, $unit){
    //Change address format
    $formattedAddrFrom = str_replace(' ','+',$addressFrom);
    $formattedAddrTo = str_replace(' ','+',$addressTo);
    
	#echo $formattedAddrFrom;
	
    //Send request and receive json data
    $geocodeFrom = file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$formattedAddrFrom.'&sensor=false&key=AIzaSyBvbUKhsxk6i-pryZxxbC_0MG-9Oahoz7U');
	#print_r($geocodeFrom);
    $outputFrom = json_decode($geocodeFrom);
	
    $geocodeTo = file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$formattedAddrTo.'&sensor=false&key=AIzaSyBvbUKhsxk6i-pryZxxbC_0MG-9Oahoz7U');
    $outputTo = json_decode($geocodeTo);
    
	#print_r($outputFrom);
	
    //Get latitude and longitude from geo data
    $latitudeFrom = $outputFrom->results[0]->geometry->location->lat;
    $longitudeFrom = $outputFrom->results[0]->geometry->location->lng;
    $latitudeTo = $outputTo->results[0]->geometry->location->lat;
    $longitudeTo = $outputTo->results[0]->geometry->location->lng;
    
    //Calculate distance from latitude and longitude
    $theta = $longitudeFrom - $longitudeTo;
    $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);
    if ($unit == "K") {
        return str_replace('.',',',round(($miles * 1.609344),3)).' km';
    } else if ($unit == "N") {
        return ($miles * 0.8684).' nm';
    } else {
        return str_replace('.',',',round($miles,3)).' mi';
    }
}

	$_POST = mysql_real_escape_array($_POST);
	$_GET = mysql_real_escape_array($_GET);
	// Login überprüfen
	$chkCookie = admin_cookie_check();

	#echo $chkCookie;
	if($_SESSION['login'] == 1) {		
		$_SESSION['login'] = 1;
	} else {
		exit(0);
	}
	#print_r($_GET);
	if(!empty($_GET)) {
	 
		if(!empty($_GET['modus'])) {
			switch($_GET['modus']) {
				case 'datum_backwards':
					#echo;
					#echo $_GET['bestelldatum'];
					$strDatumPart = explode(".",$_GET['bestelldatum']);
					$strDatumEng = $strDatumPart[2].'-'.$strDatumPart['1'].'-'.$strDatumPart[0];
					$bestell_datum = new DateTime($strDatumEng.' 00:00:00');
					$bestell_datum->modify('-1 day');
					$strDatumEng = $bestell_datum->format('Y-m-d');
					$strDatumDE = $bestell_datum->format('d.m.Y');
					$strWhereAdd = "AND lieferzeit_datum >= '".$strDatumEng." 00:00:00' AND lieferzeit_datum <= '".$strDatumEng." 23:59:59'";
					break;
				case 'datum_forward':
					$strDatumPart = explode(".",$_GET['bestelldatum']);
					$strDatumEng = $strDatumPart[2].'-'.$strDatumPart['1'].'-'.$strDatumPart[0];
					$bestell_datum = new DateTime($strDatumEng.' 00:00:00');
					$bestell_datum->modify('+1 day');
					$strDatumEng = $bestell_datum->format('Y-m-d');
					$strDatumDE = $bestell_datum->format('d.m.Y');

					$strWhereAdd = "AND lieferzeit_datum >= '".$strDatumEng." 00:00:00' AND lieferzeit_datum <= '".$strDatumEng." 23:59:59'";
					break;
				case 'datum_post':
					$strDatumPart = explode(".",$_GET['bestelldatum']);
					$strDatumEng = $strDatumPart[2].'-'.$strDatumPart['1'].'-'.$strDatumPart[0];
					$bestell_datum = new DateTime($strDatumEng.' 00:00:00');
					#$bestell_datum->modify('+1 day');
					$strDatumEng = $bestell_datum->format('Y-m-d');
					$strDatumDE = $bestell_datum->format('d.m.Y');

					$strWhereAdd = "AND lieferzeit_datum >= '".$strDatumEng." 00:00:00' AND lieferzeit_datum <= '".$strDatumEng." 23:59:59'";			
					break;
				default:
					$strDatumPart = explode(".",date('d.m.Y'));
					$strDatumEng = $strDatumPart[2].'-'.$strDatumPart['1'].'-'.$strDatumPart[0];
					$bestell_datum = new DateTime($strDatumEng.' 00:00:00');
					$bestell_datum->modify('+1 day');
					$strDatumEng = $bestell_datum->format('Y-m-d');
					$strDatumDE = $bestell_datum->format('d.m.Y');

					$strWhereAdd = "AND lieferzeit_datum >= '".$strDatumEng." 00:00:00' AND lieferzeit_datum <= '".$strDatumEng." 23:59:59'";
					
					$strDatumDE = date('d.m.Y');
					} 
				}
			else {
					$strDatumPart = explode(".",date('d.m.Y'));
					$strDatumEng = $strDatumPart[2].'-'.$strDatumPart['1'].'-'.$strDatumPart[0];
					$bestell_datum = new DateTime($strDatumEng.' 00:00:00');
					
					#$bestell_datum->modify('+1 day');
					$strDatumEng = $bestell_datum->format('Y-m-d');
					$strDatumDE = $bestell_datum->format('d.m.Y');						
		 
				$strWhereAdd = "AND lieferzeit_datum >= '".$strDatumEng." 00:00:00' AND lieferzeit_datum <= '".$strDatumEng." 23:59:59'";
			}
	} else {
 
			$strDatumPart = explode(".",date('d.m.Y'));
			$strDatumEng = $strDatumPart[2].'-'.$strDatumPart['1'].'-'.$strDatumPart[0];
			$bestell_datum = new DateTime($strDatumEng.' 00:00:00');
			$bestell_datum->modify('+1 day');
			$strDatumEng = $bestell_datum->format('Y-m-d');
			$strDatumDE = $bestell_datum->format('d.m.Y');						
 
		$strWhereAdd = "AND lieferzeit_datum >= '".$strDatumEng." 00:00:00' AND lieferzeit_datum <= '".$strDatumEng." 23:59:59'";
	}
	#echo $strWhereAdd;
	$query = "SELECT *,shop_order.created_at as order_created_at FROM shop_order JOIN shop_order_customer ON shop_order.ges_order_customer_id = shop_order_customer.shop_order_customer_id LEFT JOIN shop_order_nr ON shop_order_nr.shop_order_nr_id = shop_order.shop_order_nr_id WHERE shop_order.domain_id='".$_SESSION['domain_id']."' ".$strWhereAdd." ORDER BY shop_order.lieferzeit_datum DESC";
	
	#echo $query;
	
	$resOrderMeta = DBi::$conn->query($query)or die(mysqli_error());
	$html = '
	<h1>Shop Bestellungen vom '.$strDatumDE.'</h1>
	
	###BESTELlLISTE_HEADER###
	
	<span class="spanlink" onClick="setCore_datum(\'bestellung_datum\',\'datum_backwards\');" style="float:left;margin-right:10px;"><img height="20" width="20" src="/templates/mekong/images/Mekong_Web_Symbole_--02.png"> Gestern </span> 
	<input style="float:left;margin-right:10px;" type="text" id="bestellung_datum" name="txtDatum" value="'.$strDatumDE.'">
	<span class="spanlink" style="float:left" onClick="setCore_datum(\'bestellung_datum\',\'datum_forward\');"><img height="20" width="20" src="/templates/mekong/images/Mekong_Web_Symbole_+-01.png"> Morgen</span> <span style="float:left;margin-left:10px;margin-right:5px"> | </span>
	<span class="spanlink" onClick="setCore_datum(\'bestellung_datum\',\'datum_post\');" style="float:left;margin-right:10px;"> Jetzt anzeigen </span>
	 | <span class="spanlink" onClick="setCore_datum(\'bestellung_datum\',\'\');"> Heute</span><br/><br/>
	
	<script>
	
                 $(function() {
                     $( "#bestellung_datum" ).datepicker({
       prevText: \'&#x3c;zurück\', prevStatus: \'\',
        prevJumpText: \'&#x3c;&#x3c;\', prevJumpStatus: \'\',
        nextText: \'Vor&#x3e;\', nextStatus: \'\',
        nextJumpText: \'&#x3e;&#x3e;\', nextJumpStatus: \'\',
        currentText: \'heute\', currentStatus: \'\',
        todayText: \'heute\', todayStatus: \'\',
        clearText: \'-\', clearStatus: \'\',
        closeText: \'schließen\', closeStatus: \'\',
        monthNames: [\'Januar\',\'Februar\',\'März\',\'April\',\'Mai\',\'Juni\',
        \'Juli\',\'August\',\'September\',\'Oktober\',\'November\',\'Dezember\'],
        monthNamesShort: [\'Jan\',\'Feb\',\'Mär\',\'Apr\',\'Mai\',\'Jun\',
        \'Jul\',\'Aug\',\'Sep\',\'Okt\',\'Nov\',\'Dez\'],
        dayNames: [\'Sonntag\',\'Montag\',\'Dienstag\',\'Mittwoch\',\'Donnerstag\',\'Freitag\',\'Samstag\'],
        dayNamesShort: [\'So\',\'Mo\',\'Di\',\'Mi\',\'Do\',\'Fr\',\'Sa\'],
        dayNamesMin: [\'So\',\'Mo\',\'Di\',\'Mi\',\'Do\',\'Fr\',\'Sa\'],
      showMonthAfterYear: false,
      showOn: \'focus\',
	  showWeek: false,
	  showButtonPanel:  false,
	  changeMonth: false,
	  changeYear: false,
setDate:new Date(),
      dateFormat:\'d.m.yy\'
    } );    
                 }); 
                
	</script>
	
	
	<table width=100%>';
	$html .= '<tr>
	<td class="td_center">Bestelldatum</td>
	<td class="td_center">Bestelluhrzeit</td>
	<td class="td_center">Re-Nr.</td>
	<td class="td_center">Bemerkung</td>
	<td class="td_center">Name</td>
	<td class="td_center">Stra&szlig;e</td>
	<td class="td_center">Status</td>
	<td class="td_center">Anzahl Speisen</td>
	<td class="td_center">Endsumme</td>
	<td class="td_center">Entfernung</td>
	<td class="td_center">Aktion</td>
	</tr>';
	$bIn = false;
	while($Order_meta = mysqli_fetch_assoc($resOrderMeta)) {
		$bIn = true;
		$strDatum = explode(" ",$Order_meta['lieferzeit_datum']);
		#print_r($Order_meta['lieferzeit_datum']);
		$str = explode("-",$strDatum[0]);		
		#print_r($strDatum);
		#>> bestellt -> rot
		#>> bearbeitung -> orange
		#>> lieferung -> grün
		switch($Order_meta['ges_order_status']) {
			case 'bestellt':
				$tbl_css_row = 'style="background-color:#e7a9a9"';
				$tbl_css_row2 = 'style="display: none;background-color:#e2c9c9"';
				break;
			case 'Wird bearbeitet':
				$tbl_css_row = 'style="background-color:#ffd696"';
				$tbl_css_row2 = 'style="display: none;background-color:#ffe1b3"';
				break;
			case 'Lieferung':
				$tbl_css_row = 'style="background-color:#68c983"';
				$tbl_css_row2 = 'style="display: none;background-color:#a1cfae"';
				break;				
		}
		
		$gesSummeAmTag += $Order_meta['ges_order_endsumme'];
		$gesBestellungen += 1;
		$geBestellungen_speisen_anzahl += $Order_meta['ges_order_anzahl'];
		
		$addressFrom = 'Staulinie 20,Oldenburg';
		$addressTo = $Order_meta['strasse_hnr'].', '.$Order_meta['stadt'];
	#	echo $addressTo;
		$distance = getDistance($addressFrom, $addressTo, "K");
		#echo $distance;

		$html .= '<tr '.$tbl_css_row.'>
		<td class="td_center">'.$str[2].'.'.$str[1].'.'.$str[0].'</td>
		<td class="td_center">'.$strDatum[1].'</td>
		<td class="td_center">'.$Order_meta['nr'].'</td>
		<td class="td_center">'.$Order_meta['order_comment'].'</td>
		<td class="td_center">'.$Order_meta['vorname'].' '.$Order_meta['nachname'].' ('.$Order_meta['stadt'].')<br/>'.$Order_meta['telefon'].'</td>
		<td class="td_center">'.$Order_meta['strasse_hnr'].'</td>
		<td class="td_center">'.$Order_meta['ges_order_status'].'</td>
		<td class="td_center"><a class="spanlink" onClick="core_toggle(\''.'#subtable_'.$Order_meta['shop_order_id'].'\');shop_order_subtable_details(\''.$Order_meta['shop_order_id'].'\')">'.$Order_meta['ges_order_anzahl'].'x</a></td>
		<td  class="td_center">'.number_format($Order_meta['ges_order_endsumme'],2,',','.').' EUR</td>
		<td class="td_center">'.$distance.'</td>
		<td class="td_center"><a href="http://www.restaurantmekong.de/api.php?modus=show_invoice&order_id='.$Order_meta['shop_order_id'].'&domain_id='.$_SESSION['domain_id'].'" id="re_'.$Order_meta['shop_order_id'].'" onClick="javascript:getRechnung(\''.$Order_meta['shop_order_id'].'\')"><img src="/templates/mekong/images/Icons_Drucken.png" title="Rechnung drucken"/></a><br/>
		
		<span class="spanlink" onClick="setBestellung_status(\''.$Order_meta['shop_order_id'].'\',\'bestellt\')" style="margin-left:3px"><img widht="50" height="50" src="/templates/mekong/images/Icon_BestellungsEingang.png" title="Bestellungseingang"/></span>
		
		<span class="spanlink"  onClick="setBestellung_status(\''.$Order_meta['shop_order_id'].'\',\'Wird bearbeitet\')"><img widht="50" height="50"  src="/templates/mekong/images/Icon_InBearbeitung.png" title="Wird bearbeitet"/></span>
		
		<span  onClick="setBestellung_status(\''.$Order_meta['shop_order_id'].'\',\'Lieferung\')" class="spanlink"><img widht="50" height="50" src="/templates/mekong/images/Icon_InLieferung.png" title="Lieferung"/></span></td>
		</tr>
		<tr '.$tbl_css_row2.'>
			<td colspan="11">&nbsp;</td>
		</tr>
		<tr '.$tbl_css_row2.' id="subtable_'.$Order_meta['shop_order_id'].'">
			<td colspan="11"><div id="shop_order_details_'.$Order_meta['shop_order_id'].'"></div></td>
		</tr>
		';	
	}
	$html .= '</table>';
	
	
	$_SESSION['bestell_datum'] = $strDatumDE;
#	$html_bestellheader = 'Anzahl Bestellungen: '.$gesBestellungen.'x | ';
#	$html_bestellheader .= 'Speisenanzahl: '.$geBestellungen_speisen_anzahl.'x | ';
#	$html_bestellheader .= 'Summe: '.number_format($gesSummeAmTag,2,',','.').' EU | ';
#	$html_bestellheader .= '<span id="clock"></span><br/><br/>';
	$html = str_replace('###BESTELlLISTE_HEADER###',$html_bestellheader,$html);
	$html .= "
	<script>
function updateClock ( )
 	{
 	var currentTime = new Date ( );
  	var currentHours = currentTime.getHours ( );
  	var currentMinutes = currentTime.getMinutes ( );
  	var currentSeconds = currentTime.getSeconds ( );

  	// Pad the minutes and seconds with leading zeros, if required
  	currentMinutes = ( currentMinutes < 10 ? \"0\" : \"\" ) + currentMinutes;
  	currentSeconds = ( currentSeconds < 10 ? \"0\" : \"\" ) + currentSeconds;
 

  	// Compose the string for display
  	var currentTimeString = currentHours + \":\" + currentMinutes + \":\" + currentSeconds + \" \";
  	
  	
   	$(\"#clock\").html(currentTimeString);
   	  	
 }
$(document).ready(function()
{
   setInterval('updateClock()', 1000);
});
</script>";

	if ($bIn == false) {
		#$html = '<h2>Shop Bestellungen</h2>';
		$html .= '<h1>Keine Bestellungen vom '.$strDatumDE.' gefunden!</h1>
		<br/><br/>';		
	}
	echo $html;
?>