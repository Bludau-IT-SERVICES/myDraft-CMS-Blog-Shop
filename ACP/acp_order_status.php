<?php
	session_start(); 
	include_once('../include/inc_config-data.php');
	include_once('../include/inc_basic-functions.php');
 
	$_POST = mysql_real_escape_array($_POST);
	$_GET = mysql_real_escape_array($_GET);
	// Login überprüfen
	#$chkCookie = admin_cookie_check();

	#echo $chkCookie;
	/*if($_SESSION['login'] == 1) {		
		$_SESSION['login'] = 1;
	} else {
		echo "Nicht angemeldet ".$_COOKIE['admin_user'];
		exit(0);
	} */
	
	if(isset($_POST['cart_customerid'])) {
		$query = "SELECT * FROM shop_order_customer WHERE shop_order_customer_id='".$_POST['cart_customerid']."'";
		$resUser = DBi::$conn->query($query) or die(mysqli_error());
		$strData = mysqli_fetch_assoc($resUser);
		
		$_POST['txtVorname'] = $strData['vorname'];
		$_POST['txtNachname'] = $strData['nachname'];
		$_POST['txtStrasse'] = $strData['strasse_hnr'];
		$_POST['txtPLZ'] = $strData['plz'];
		$_POST['txtOrt'] = $strData['stadt'];
		$_POST['txtLand'] = $strData['land'];
		$_POST['txtEmail'] = $strData['email'];
		$_POST['txtFirma'] = $strData['firma'];
	}
	
	$_SESSION['order_vorname'] = $_POST['txtVorname'];
	$_SESSION['order_nachname'] = $_POST['txtNachname'];
	$_SESSION['order_strasse'] = $_POST['txtStrasse'];
	$_SESSION['order_plz'] = $_POST['txtPLZ'];
	$_SESSION['order_ort'] = $_POST['txtOrt'];
	$_SESSION['order_land'] = $_POST['txtLand'];
	$_SESSION['order_email'] = $_POST['txtEmail'];
	$_SESSION['order_firma'] = $_POST['txtFirma'];
	
	// Datenbankverbindung
	/*$path = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once($path.'/include/inc_config-data.php');
	require_once($path.'/include/inc_basic-functions.php');*/
	
	switch($_POST['modus']) {
		case 'RECHNUNG_BEZAHLEN':
			echo  '<h1>Ihre Online Rechnung</h1>';
			break;
		case 'RECHNUNG_BEZAHLEN':
			echo '<h1>Ihre Online Rechnung ist jetzt bezahlt</h1>';
			break;
	}
	#echo $_POST['modus'].'--';
	
	#print_r($_POST);
		// Gesamtsumme berechnen
		for($i=0; $i < count($_POST['cart_id']); $i++) {
			// Artikel Details holen 
			#$strItemDetailAry = explode("-",$strAry[$i]);
			#echo $_POST['cart_id'][$i].'--';
			if($i==0) {
				$query = "SELECT * FROM shop_item JOIN shop_info ON shop_item.domain_id = shop_info.domain_id WHERE shop_item.shop_item_id='".$_POST['cart_id'][$i]."'";
				$resShopInfo = DBi::$conn->query($query) or die(mysqli_error());
				$ShopInfoData = mysqli_fetch_assoc($resShopInfo);
				
			}
			$query ="SELECT *,shop_item.name_de as shop_name FROM shop_item JOIN menue ON shop_item.menue_id = menue.id WHERE shop_item.shop_item_id ='".$_POST['cart_id'][$i]."'";
			$resItem = DBi::$conn->query($query) or die(mysqli_error());
			$Cartdata = mysqli_fetch_assoc($resItem);
			
			$shop_artikel_preis = str_replace(".",",",$_POST['cart_price'][$i]);
			$shop_artikel_preisGes += $shop_artikel_preis * $_POST['cart_id'][$i];
			$shop_artikel_gewicht += $Cartdata['gewicht'] * $_POST['cart_id'][$i];
			$shop_artikel_anzahl += $_POST['cart_amount'][$i];
		}
		  
		$shop_artikel_preisVersand = str_replace(",",".",$_POST['order_versandkosten']);
		$shop_artikel_preisVersand_mwst = "19.0";
		
		$strVersandHTML .= '<tr><td colspan="2" style="text-align:right">Gewicht</td><td>'.str_replace('.',',',$shop_artikel_gewicht).' KG</td><td>&nbsp;</td></tr>';
		$strVersandHTML .= '<tr><td colspan="2" style="text-align:right">Versandkosten</td><td>'.str_replace(".",",",$shop_artikel_preisVersand).' EUR</td><td>&nbsp;</td></tr>';
 
	
	// Email abschicken
	$strAry = explode("|",$_SESSION['shop_cart_ids']);
	$shop_artikel_anzahl = 0;
	
	$shop_artikel_preis = 0.0;
	
	switch($_POST['modus']) {
		case 'RECHNUNG_ERSTELLEN':
			$html = '<h1>Ihre Online Rechnung</h1>';
			break;
		case 'RECHNUNG_BEZAHLEN':
			$html = '<h1>Ihre Online ist jetzt bezahlt</h1>';
			break;
		case 'RECHNUNG_VERSAND_STARTEN':
			$html = '<h1>Versand wurde gestartet</h1>';
			#echo "Transaktion wird Versand wird verschickt!";
			break;
		case 'RECHNUNG_KOMPLETT':
			$html = '<h1>Transaktion abgeschlossen</h1>';
			#echo "Transaktion wird abgeschlossen!"; 
			break;
		case 'RECHNUNG_BEWERTEN':
			$html = '<h1>Danke für Ihre Bewertung</h1>';
			#echo "Transaktion wird bewertet!"; 
			break;
	}
	
	$html .= '<table>';	
	$html .= '<tr><td>Firma</td><td>'.utf8_decode($_POST['txtFirma']).'</td></tr>';	
	$html .= '<tr><td>Name</td><td>'.utf8_decode($_POST['txtVorname']).' '.utf8_decode($_POST['txtNachname']).'</td></tr>';	
	$html .= '<tr><td>Stra&szlig;e</td><td>'.utf8_decode($_POST['txtStrasse']).'</td></tr>';	
	$html .= '<tr><td>PLZ + Ort</td><td>'.utf8_decode($_POST['txtPLZ']).' '.utf8_decode($_POST['txtOrt']).'</td></tr>';	
	$html .= '<tr><td>Land</td><td>'.utf8_decode($_POST['txtLand']).'</td></tr>';	
	$html .= '</table>';	
	$html .= '<h2>Bestell&uuml;bersicht</h2>';
	$html .= '<table width="100%"><tr><td><h3>Bild</h3></td><td><h3>Name</h3></td><td><h3>Menge</h3></td><td><h3>Preis</h3></td></tr>';
	
	// Alle Artikel durchlaufen
	$shop_artikel_preisGes = 0;
	$shop_artikel_gewicht = 0;
		switch($_POST['modus']) {
			case 'RECHNUNG_ERSTELLEN':
				$query ="INSERT INTO shop_invoice(ges_order_status,ges_order_versandkosten,ges_order_endsumme,ges_order_customer_id,ges_order_gewicht,ges_order_artikelsumme,ges_order_anzahl,created_at,domain_id) VALUES('Rechnung gestellt','".$shop_artikel_preisVersand."','".($shop_artikel_preisGes + $shop_artikel_preisVersand + $cart_mwst_ges)."','".$_POST['cart_customerid']."','".$shop_artikel_gewicht."','".$shop_artikel_preisGes."','".$shop_artikel_anzahl."','".date("Y-m-d H:i:s")."','".$_POST['domain_id']."')";
				DBi::$conn->query($query) or die(mysqli_error());
				$invoice_id = mysqli_insert_id(DBi::$conn);
				break;
			case 'RECHNUNG_VERSAND_STARTEN':
				#echo "Transaktion wird Versand wird verschickt!";
				break;
			case 'RECHNUNG_KOMPLETT':
				#echo "Transaktion wird abgeschlossen!"; 
				break;
			case 'RECHNUNG_BEWERTEN':
				#echo "Transaktion wird bewertet!"; 
				break;
			case 'RECHNUNG_BEZAHLEN':
				#echo "Rechnung ist als Email abgeschickt worden!";
				break;
			default:
				#echo "Email abgeschickt worden!";
				break;
		}	

	
	for($i=0; $i < count($_POST['cart_id']); $i++) {
 
		
		$query = "SELECT * FROM shop_order JOIN shop_order_list ON shop_order.shop_order_id = shop_order_list.id_shop_order WHERE domain_id='".$_SESSION['portal_domain_id']."' AND shop_item_id='".$_POST['cart_id'][$i]."' ".$strFilterByKunde." ".$strOrderStatus." ORDER by invoice_id DESC, ges_order_customer_id ASC, shop_order.updated_at DESC "; 
		
		#$query ="SELECT *,shop_item.name_de as shop_name FROM shop_item JOIN menue ON shop_item.menue_id = menue.id WHERE shop_item.shop_item_id ='".$_POST['cart_id'][$i]."'";
		$resItem = DBi::$conn->query($query) or die(mysqli_error());
		$Cartdata = mysqli_fetch_assoc($resItem);
		
			
		$shop_artikel_preis = str_replace(".",",",$_POST['cart_price'][$i]);
		#$shop_artikel_preis = $_POST['cart_price'][$i];
		$shop_artikel_preisGes += $shop_artikel_preis * $_POST['cart_amount'][$i];
		$shop_artikel_gewicht += $Cartdata['gewicht'] * $_POST['cart_amount'][$i];
		$shop_artikel_anzahl += $_POST['cart_amount'][$i];
		
		# Verschiedene Mehrwertsteuer speichern
		#echo $shop_artikel_preisGes.' '.$Cartdata['item_mwst'].'<br/>';
		$shop_artikel_mwst[$Cartdata['item_mwst']] += ((($shop_artikel_preis * $_POST['cart_amount'][$i]) / 100) * $Cartdata['item_mwst']);
		
		$domain_id = $Cartdata['domain_id'];
		// Seite wo Artikel liegt abrufen
		$pathItem = 'http://'.$_SERVER['SERVER_NAME'].'/'.getPathUrl($_SESSION['language'],$Cartdata['menue_id']);
		
		$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$_POST['cart_id'][$i]."'";
		$strBild = mysqli_fetch_assoc(DBi::$conn->query($query));
		$strBild['picture_url'] = 'http://'.$_SERVER['SERVER_NAME'].'/'.$strBild['picture_url'];
		$html .= '<tr>
		<td><img src="'.$strBild['picture_url'].'" width="50px" Height="50px"/></a></td>
		<td><a href="'.$pathItem.'">'.$Cartdata['name_de'].'</a></td>';
		$html .= '<td>'.$_POST['cart_amount'][$i].'</td>';
		$html .= '<td>'.str_replace(".",",",$_POST['cart_price'][$i]) * $_POST['cart_amount'][$i].' EUR</td>';
		$html .= '<tr>';
		
		##################################################
		# Artikel der Bestellung auf Rechnung stellen
		##################################################
		switch($_POST['modus']) {
			case 'RECHNUNG_ERSTELLEN':
					$query = "UPDATE shop_order_list SET order_status='Rechnung gestellt',created_invoice='".date("Y-m-d H:i:s")."',id_invoice_no='".$invoice_id."' WHERE shop_item_id=".$_POST['cart_id'][$i];
					DBi::$conn->query($query) or die(mysqli_error());
					
					$query = "UPDATE shop_order SET ges_order_status='Rechnung gestellt',created_invoice='".date("Y-m-d H:i:s")."',invoice_id='".$invoice_id."' WHERE shop_order_id=".$_POST['cart_orderid'][$i];
					DBi::$conn->query($query) or die(mysqli_error());
				break;
			case 'RECHNUNG_VERSAND_STARTEN':
				#echo "Transaktion wird Versand wird verschickt!";
								
					$query = "UPDATE shop_order_list SET order_status='Versand gestartet',versendet_am='".date("Y-m-d H:i:s")."',id_invoice_no='".$invoice_id."' WHERE shop_item_id=".$_POST['cart_id'][$i];
					DBi::$conn->query($query) or die(mysqli_error());
					
					$query = "UPDATE shop_order SET ges_order_status='Versand gestartet',versendet_am='".date("Y-m-d H:i:s")."',invoice_id='".$invoice_id."' WHERE shop_order_id=".$_POST['cart_orderid'][$i];
					DBi::$conn->query($query) or die(mysqli_error());

				break;
			case 'RECHNUNG_KOMPLETT':
				#echo "Transaktion wird abgeschlossen!"; 
					$query = "UPDATE shop_order_list SET order_status='Transaktion abgeschlossen',abgeschlossen_am='".date("Y-m-d H:i:s")."',id_invoice_no='".$invoice_id."' WHERE shop_item_id=".$_POST['cart_id'][$i];
					DBi::$conn->query($query) or die(mysqli_error());
					
					$query = "UPDATE shop_order SET ges_order_status='Transaktion abgeschlossen',abgeschlossen_am='".date("Y-m-d H:i:s")."',invoice_id='".$invoice_id."' WHERE shop_order_id=".$_POST['cart_orderid'][$i];
					DBi::$conn->query($query) or die(mysqli_error());				
				break;
			case 'RECHNUNG_BEWERTEN':
				#echo "Transaktion wird bewertet!"; 
					$query = "UPDATE shop_order_list SET order_status='bewertet',bewertet_am='".date("Y-m-d H:i:s")."',id_invoice_no='".$invoice_id."' WHERE shop_item_id=".$_POST['cart_id'][$i];
					DBi::$conn->query($query) or die(mysqli_error());
					
					$query = "UPDATE shop_order SET ges_order_status='bewertet',bewertet_am='".date("Y-m-d H:i:s")."',invoice_id='".$invoice_id."' WHERE shop_order_id=".$_POST['cart_orderid'][$i];
					DBi::$conn->query($query) or die(mysqli_error());				
				break;
			case 'RECHNUNG_BEZAHLEN':
				#echo "Rechnung ist als Email abgeschickt worden!";
					$query = "UPDATE shop_order_list SET order_status='bezahlt',bezahlt_am='".date("Y-m-d H:i:s")."',id_invoice_no='".$invoice_id."' WHERE shop_item_id=".$_POST['cart_id'][$i];
					DBi::$conn->query($query) or die(mysqli_error());
					
					$query = "UPDATE shop_order SET ges_order_status='bezahlt',bezahlt_am='".date("Y-m-d H:i:s")."',created_invoice='".date("Y-m-d H:i:s")."',invoice_id='".$invoice_id."' WHERE shop_order_id=".$_POST['cart_orderid'][$i];
					DBi::$conn->query($query) or die(mysqli_error());				
				break;
			default:
				#echo "Email abgeschickt worden!";
				break;
		}

		
		
	}

	
	
	
	$html = utf8_encode($html);
	$html .= '<tr><td colspan="2" style="text-align:right">Artikel Summe</td><td>'.$shop_artikel_preisGes.' EUR</td><td>&nbsp;</td></tr>';
	$html .= $strVersandHTML;
	$shop_artikel_gesamt = $shop_artikel_preisGes + $shop_artikel_preisVersand;
			
			$query ="SELECT * FROM domains WHERE domain_id = $domain_id";
			$res = DBi::$conn->query($query);
			$domain_data = mysqli_fetch_assoc($res);
 #Mehrwertsteuer vom Versand
			$shop_artikel_mwst[$shop_artikel_preisVersand_mwst] += (($shop_artikel_preisVersand / 100) * $shop_artikel_preisVersand_mwst);
			
			
			#'MwSt_inkl','MwSt_exkl','MwSt_befreit'
			switch($domain_data['shop_mwst_setting']) {
				case "MwSt_inkl":
					$strMWSTText = 'inkl. MwSt.';
					foreach ($shop_artikel_mwst as $key => $value) {
						if($key != '') {
							$html .= '<tr><td colspan="2" style="text-align:right">Mehrwertsteuer '.$strMWSTText.' '.$key.'%</td><td>'.str_replace('.',',',$value).' EUR</td><td>&nbsp;</td></tr>';
							$cart_mwst_ges = 0;
						}
					}
					break;
				case "MwSt_exkl":
					$strMWSTText = 'exkl. MwSt.';
					foreach ($shop_artikel_mwst as $key => $value) {
						if($key != '') {
							$html .= '<tr><td colspan="2" style="text-align:right">Mehrwertsteuer '.$strMWSTText.' '.$key.'%</td><td>'.str_replace('.',',',$value).' EUR</td><td>&nbsp;</td></tr>';
							$cart_mwst_ges += $value;
						}
					}
					break;
				case "MwSt_befreit":
					$strMWSTText = '';
					$html .= '<tr><td colspan="4" style="text-align:right">Mehrwertsteuer befreit Kleinunternehmer</td><td>&nbsp;</td></tr>';
					$cart_mwst_ges = 0;
					break;
			}
			
			 
	$html .= '<tr><td colspan="2" style="text-align:right"><h2>Endsumme</h2></td><td><h2>'.str_replace('.',',',($shop_artikel_preisGes + $shop_artikel_preisVersand + $cart_mwst_ges)).' EUR</h2></td><td>&nbsp;</td></tr></table><br/><br/>Mit freundlichen Gr&uuml;&szlig;en Ihr Shop Team';
	
	$path = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once $path.'/framework/phpmailer/PHPMailerAutoload.php';

	//Create a new PHPMailer instance
	$mail = new PHPMailer();
	$mail->CharSet = 'utf-8'; 
	// Set PHPMailer to use the sendmail transport
	$mail->isSendmail();
	//Set who the message is to be sent from
	$mail->setFrom($ShopInfoData['email_shop_main'],$ShopInfoData['email_shop_main_form_name']);
	//Set an alternative reply-to address
	$mail->addReplyTo($ShopInfoData['email_shop_main'],$ShopInfoData['email_shop_main_form_name']);
	//Set who the message is to be sent to
	$mail->addAddress($_POST['txtEmail'], $_POST['txtVorname'].' '.$_POST['txtNachname']);
	
	$mail->AddBCC($ShopInfoData['email_shop_main'],$ShopInfoData['email_shop_main_form_name']);
	$mail->AddBCC("jbludau@cubss.net","Shopste Shop Rechnung");
	//Set the subject line
		
	switch($_POST['modus']) {
		case 'RECHNUNG_ERSTELLEN':
			$mail->Subject = utf8_decode('Shop Rechnung bei '.$ShopInfoData['shop_name']).' für '.$_POST['txtVorname'].' '.$_POST['txtNachname'];
			break;
		case 'RECHNUNG_BEZAHLEN':
			$mail->Subject = utf8_decode('Shop Rechnung bezahlt bei '.$ShopInfoData['shop_name']).' für '.$_POST['txtVorname'].' '.$_POST['txtNachname'];
			break;
		case 'RECHNUNG_VERSAND_STARTEN':
			#echo "Transaktion wird Versand wird verschickt!";
			$mail->Subject = utf8_decode('Shop Bestellung Versand vorbereitet bei '.$ShopInfoData['shop_name']).' für '.$_POST['txtVorname'].' '.$_POST['txtNachname'];


			break;
		case 'RECHNUNG_KOMPLETT':
			#echo "Transaktion wird abgeschlossen!"; 
			$mail->Subject = utf8_decode('Transaktion abgeschlossen bei '.$ShopInfoData['shop_name']).' für '.$_POST['txtVorname'].' '.$_POST['txtNachname'];
			break;
		case 'RECHNUNG_BEWERTEN':
			#echo "Transaktion wird bewertet!"; 
			$mail->Subject = utf8_decode('Transaktion bewertet bei '.$ShopInfoData['shop_name']).' für '.$_POST['txtVorname'].' '.$_POST['txtNachname'];
			break;
 			
	}
	
	
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	$html = utf8_decode($html);
	$mail->msgHTML($html, dirname(__FILE__));
	//Replace the plain text body with one created manually
	$mail->AltBody = 'Ihre Email wird noch nicht richtig angezeigt.';
	//Attach an image file
	//$mail->addAttachment('images/phpmailer_mini.png');

	//send the message, check for errors
	if (!$mail->send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
				
		switch($_POST['modus']) {
			case 'RECHNUNG_ERSTELLEN':
				echo "Rechnung ist als Email abgeschickt worden!";
				break;
			case 'RECHNUNG_VERSAND_STARTEN':
				echo "Transaktion wird Versand wird verschickt!";
				break;
			case 'RECHNUNG_KOMPLETT':
				echo "Transaktion wird abgeschlossen!"; 
				break;
			case 'RECHNUNG_BEWERTEN':
				echo "Transaktion wird bewertet!"; 
				break;
			case 'RECHNUNG_BEZAHLEN':
				echo "Rechnung ist als Email abgeschickt worden!";
				break;
			default:
				echo "Email abgeschickt worden!";
				break;
		}
		
		$_SESSION['shop_cart_ids'] = '';
	}
	/*$empfaenger = $_POST['txtEmail'];
	$betreff = 'Shop Bestellung';
	$nachricht = $html;
	$header  = 'MIME-Version: 1.0' . "\r\n";
	$header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$header .= 'Bcc: jbludau@cubss.net' . "\r\n";
	$header .= 'From: info@php-consulting.com' . "\r\n" .
    'Reply-To: info@php-consulting.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

	mail($empfaenger, $betreff, $nachricht, $header);*/
 ?>