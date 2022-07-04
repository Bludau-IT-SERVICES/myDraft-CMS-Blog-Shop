<?php 
session_start();

####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_portal_gebuehrenanzeige($config) {

		$dataTextHTML = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_portal_gebuehrenanzeige WHERE id=".$config['modul_id']));
		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='portal_gebuehrenanzeige'"));
		#echo "IN";
		#print_r($dataTextHTML);
		$dataTextHTML['typ'] = 'portal_gebuehrenanzeige';
		
		$text = '<div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
		
		$text .= convertUmlaute($dataTextHTML["content_".$_SESSION['language']]);
		$titel = convertUmlaute($dataTextHTML["title_".$_SESSION['language']]);
		

		
		if($text == '') {   
			$text = convertUmlaute($dataTextHTML["content_de"]); 
		} 
		#echo $dataTextHTML["id"];
		if($titel == '') { 
			$titel = convertUmlaute($dataTextHTML["title_de"]); 
		} 
		
		// && $config["container"]
		if($_SESSION['login'] == '1'  AND $module_in_menue['container'] == 'col-main') {
			$strReturn = getMember($dataTextHTML['last_usr']);
			if(!empty($strReturn)) {
				$ary = explode(" ",$dataTextHTML['lastchange']);
				$german_de = getDateDE($ary[0]);
				$titel .= '</h1> - '.$strReturn.' - '.$german_de.' '.$ary[1];
			}
		}
		
		# Eingeloggt 
		if (@$_SESSION['login'] == '1')  { 
			if($titel == '') { 
				$titel = "Kein Titel"; 
			} 
		} 
 
		$text .='Die Verkaufsprovision gilt für alle Kategorien auf dem Shopste Marktplatz und ebenso für Verkäufe in dem eigenen Onlineshop.<br/>
		Die Rechnung wird am 01. jeden Monats um 0 Uhr automatisch erzeugt. Sie müssen den Betrag aus der Rechnungsemail überweisen. Sollten Sie nicht innerhalb von 14 Tagen bezahlt haben droht eine Schließung Ihres Onlineshop und auch deaktivierung vom Shopste Marktplatz.<br/>Für die Rechnung wird der Monatsumsatz (Marktplatz und Shop) ohne Versandkosten herrangezogen und die entsprechenden prozentualen Gebühren auf den Monatsumsatz gerechnet.<br/> 
		<br/>';
		
		$query = "SELECT * FROM portal_abrechnung_gebuehr ORDER BY gruppierung DESC";
		$resAbrechnung = DBi::$conn->query($query) or die(mysqli_error());
		$iCount = 0;
		while($strAbrechnung = mysqli_fetch_assoc($resAbrechnung)) {
			if($iCount == 0) {
				$text .='<table width="100%">
				<tr><th align="left">Verkäufer Typ</th><th align="left">von Monatsumsatz</th><th align="left">Bis Monatsumsatz</th><th align="left">Verkaufsprovision</th></tr>';
				
 
			}
			
			if($tmpGruppierung != $strAbrechnung['gruppierung_name']) {
					$text .= '<tr><td colspan="5">&nbsp;</td></tr>';
			}
			$tmpGruppierung = $strAbrechnung['gruppierung_name'];
			
			
			if($strAbrechnung['bis_preis'] == "999999.00") {
				$text .= '<tr>
				<td>'.$strAbrechnung['gruppierung_name'].'</td><td>'.str_replace('.',',',$strAbrechnung['von_preis']).' EUR</td><td>und mehr </td><td>'.$strAbrechnung['gebuehr_prozent'].'%</td>
				</tr>';
			} else {
				$text .= '<tr>
				<td>'.$strAbrechnung['gruppierung_name'].'</td><td>'.str_replace('.',',',$strAbrechnung['von_preis']).' EUR</td><td>'.str_replace('.',',',$strAbrechnung['bis_preis']).' EUR</td><td>'.$strAbrechnung['gebuehr_prozent'].'%</td>
				</tr>';
			}
			$iCount++;
		}
		if($iCount != 0) {
			$text .= '</table>';
		}
	 
		$text .= '</div>'; // config modus 

		
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 } 
 ?>