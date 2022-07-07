<?php 
	session_start();
	require_once('../../include/inc_config-data.php');
	require_once('../../include/inc_basic-functions.php');		
	
	$_POST = mysql_real_escape_array($_POST);
	$_GET  = mysql_real_escape_array($_GET);
####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_shop_cart_order_buynow($config) {

		#$dataTextHTML = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_shop_cart_order WHERE id=".$config['modul_id']));
		 
		#$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='shop_cart_order'"));
		#echo "IN";
		
		$dataTextHTML['typ'] = 'shop_cart_order';
		
		#$text = '<div class="contentborderless" id="order_buynow">';
		
		#$text .= convertUmlaute($dataTextHTML["content_".$_SESSION['language']]);
		#$titel = convertUmlaute($dataTextHTML["title_".$_SESSION['language']]);
		

			
		if($titel == '') { 
			$titel = 'Produkt Sofortkaufen';
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
		if($_GET['status'] == 'sended') {
			$text .= 'Vielen Dank f&uuml;r Ihre Bestellung!<br/>Sie erhalten in K&uuml;ze eine Bestellbest&auml;tigung an Ihre E-Mail-Adresse.';
		} else {
					// Shop Info aus dem Warenkorb Artikel auslesen
			$items = explode("|",$_SESSION['shop_cart_ids']);
	
			
			$itemsData = explode("-",$items[0]);
#$text = '<div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'" itemscope itemtype="http://schema.org/Product">';
			
			$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$itemsData[0]."'";
			$resShopPic = DBi::$conn->query($query) or die(mysqli_error());
			#$ShopInfoPic = mysqli_fetch_assoc($resShopPic);
			
			$query = "SELECT * FROM shop_item JOIN shop_info ON shop_item.domain_id = shop_info.domain_id WHERE shop_item.shop_item_id='".$itemsData[0]."'";
			$resShopInfo = DBi::$conn->query($query) or die(mysqli_error());
			$ShopInfodata = mysqli_fetch_assoc($resShopInfo);
			#print_r($ShopInfodata.$query);
			#$text = '<div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'" itemscope itemtype="http://schema.org/Product">';
			$text .= '<div class="shop_item_picture_box" id="shop_item_picture_box">';	
		
			while($strPic = mysqli_fetch_assoc($resShopPic)) {
				if($iCount ==0) {
					if($strPic['picture_url'] != '') {
						#$text .= '<a class="cloud-zoom" href="'.$strPic['picture_url'].'" rel="useWrapper: true,showTitle: true, zoomWidth:\'500\', zoomHeight:\'400\', adjustY:0, adjustX:10">';
						$text .= '<a href="'.$strPic['picture_url'].'" rel="gallery" class="fancybox" title="'.$ShopItem['name_de'].'">';
					} 
					$strPic['picture_url'] = str_replace("produkte/orginal/","produkte/detail/",$strPic['picture_url']);
					$text .= '<img id="shop_item_picture_main" src="'.$strPic['picture_url'].'" height="350" width="350" class="shop_galery"/></a><br/><br/><img onClick="shop_item_picture_update(\''.$strPic['picture_url'].'\')" itemprop="image"  src="'.$strPic['picture_url'].'" height="50" width="50" style="float:left;cursor: pointer;" class="shop_galery"/>';
					#$text .= '<img src="'.str_replace("/produkte/orginal/","/produkte/kategorie/",$strPicture_cat['picture_url']).'" alt="Icon" height="200" width="230" id="shop_item_picture_'.$data['shop_item_id'].'" title="'.$data['name_de'].'" style="z-index:9;" class="shop_galery"/>';	
				} else {
					$strPic['picture_url'] = str_replace("produkte/orginal/","produkte/detail/",$strPic['picture_url']);
					$text .= '<img onClick="shop_item_picture_update(\''.$strPic['picture_url'].'\')" src="'.$strPic['picture_url'].'" height="50" width="50" style="float:left;cursor: pointer;"/>';				
				}
				$iCount++;
			}
			$text .= '<br/>';
		$text .= '<div style="clear:both"></div></div>';
		$text .= ' 
		<div id="shop_item_detail_info">';
		$text .= '<h2>Artikel bestellen</h2>';
			$text .= '<strong>Artikelname:</strong> '.$ShopInfodata['name_de'].'<br/>';
			$text .= '<strong>Preis:</strong> '.number_format($ShopInfodata['preis'], 2, ',', '.').' EUR<br>';
			$text .= '<strong>Anzahl:</strong> '.$itemsData[1].' x<br>';
/*$text .= '<script>
  $(function() {
    var dialog, form,
 dialog = $( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 300,
      width: 350,
      modal: true,
      buttons: {
        "Create an account": addUser,
        Cancel: function() {
          dialog.dialog( "close" );
        }
      },
      close: function() {
        form[ 0 ].reset();
        allFields.removeClass( "ui-state-error" );
      }
    });
 
    form = dialog.find( "form" ).on( "submit", function( event ) {
      event.preventDefault();
      alert(\'add\');
    });
 
    $( "#create-user" ).button().on( "click", function() {
      dialog.dialog( "open" );
    });
});	
</script>
<div id="dialog-form" title="Neue Menge eingeben">
  <p class="validateTips">Bitte komplett ausf&uuml;llen.</p>
 
  <form>
    <fieldset>
      <label for="menge">Menge</label>
      <input type="text" name="menge" id="name" value="<?php echo $itemsData[1];  ?>" class="text ui-widget-content ui-corner-all">
 
      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
    </fieldset>
  </form>
</div>';*/
			#print_r($_SESSION);
			
			$query = "SELECT * FROM shop_shippment_detail WHERE shop_shippment_detail.gewicht_von <= '".$ShopInfodata['gewicht']."' AND shop_shippment_detail.gewicht_bis>= '".$ShopInfodata['gewicht']."' AND  domain_id='".$_SESSION['domain_id']."'";
			$resVersandkostenPreis = DBi::$conn->query($query) or die(mysqli_error());
			$shop_artikel_preisVersand = 0;
			$bVersandartGefunden = false;
			while($strVersandKosten = mysqli_fetch_assoc($resVersandkostenPreis)) {
				$strVersandHTML .= 'Gewicht '.str_replace('.',',',$shop_artikel_gewicht).' KG';
				
				$query ="SELECT * FROM shop_shippment WHERE shop_shippment_id='".$strVersandKosten['shop_shippment_id']."'";
				$resVersandArt = DBi::$conn->query($query);
				$strVersandKostenName = mysqli_fetch_assoc($resVersandArt);
				
				
				$strVersandHTML .= $strVersandKostenName['name_de'].' '.number_format($strVersandKosten['versandkosten'], 2, ',', '.').' EUR<br/>';
				$shop_artikel_preisVersand = $strVersandKosten['versandkosten'];
				$shop_artikel_preisVersand_mwst = $strVersandKostenName['mwst'];
				$bVersandartGefunden = true;
			}
			$shop_artikel_preisGes = $ShopInfodata['preis'] * $itemsData[1];
			$strVersandHTML .= '<strong>Zwischensumme</strong> '.number_format($shop_artikel_preisGes, 2, ',', '.').' EUR<br/>';
			
		
		
		
		if($bVersandartGefunden == false) {
			if($ShopInfodata['versandkosten'] != '') {
				$shop_artikel_preisVersand = $ShopInfodata['versandkosten'];
				if(!empty($ShopInfodata['versandkosten_name'])) {
			
					$strVersandHTML .= '<strong>Gewicht</strong> '.str_replace('.',',',$shop_artikel_gewicht).' KG<br/>';	
					$strVersandHTML .= '<strong>Versandkosten</strong>'.$ShopInfodata['versandkosten_name'].' '.str_replace('.',',',$ShopInfodata['versandkosten']).' EUR';
				} else {
					$strVersandHTML = '<strong>Zwischensumme</strong> '.number_format($shop_artikel_preisGes, 2, ',', '.').' EUR<br/>';
					$strVersandHTML .= '<strong>Versandkosten</strong> '.number_format($ShopInfodata['versandkosten'], 2, ',', '.').' EUR<br/>';
				}
			} else {				
				
				$strVersandHTML = '<strong>Zwischensumme</strong> '.number_format($shop_artikel_preisGes, 2, ',', '.').' EUR';
				$strVersandHTML .= '<strong>Versandkosten</strong> werden nach Bestellung berechnet';
			}
		} else {
			if($ShopInfodata['versandkosten'] != '') {
				$shop_artikel_preisVersand = $ShopInfodata['versandkosten'];
				if(!empty($ShopInfodata['versandkosten_name'])) {
					$strVersandHTML = '<strong>Zwischensumme</strong> '.number_format($shop_artikel_preisGes, 2, ',', '.').' EUR<br/>';
			
					$strVersandHTML .= '<strong>Versandkosten</strong> '.$ShopInfodata['versandkosten_name'].' '.str_replace('.',',',$ShopInfodata['versandkosten']).' EUR';
				} else {
					$strVersandHTML = '<strong>Zwischensumme</strong> '.number_format($shop_artikel_preisGes, 2, ',', '.').' EUR<br/>';
				
					$strVersandHTML .= '<strong>Versandkosten</strong> '.number_format($ShopInfodata['versandkosten'], 2, ',', '.').' EUR<br/>';
				}
			} 
		}
		
		
			$text .= $strVersandHTML;
			$shop_artikel_preisGes += $shop_artikel_preisVersand;
			
			$text .= '<h2>Endsumme '.number_format($shop_artikel_preisGes, 2, ',', '.').' EUR</h2>';
			$text .= '<h2>Verk&auml;ufer: '.$ShopInfodata['firma'].'</h2>'; 
			$text .= $ShopInfodata['vorname'].' '.$ShopInfodata['nachname'].'<br/>'; 
			$text .= $ShopInfodata['strasse_hnr'].'<br/>'; 
			$text .= $ShopInfodata['plz'].' '.$ShopInfodata['stadt'].'<br/><br/>'; 
			
			// Domain Seiten abrufen für Verlinkung
			$query = "SELECT * from domains WHERE domain_id='".$ShopInfodata['domain_id']."'";			
			$resDomainData = DBi::$conn->query($query) or die(mysqli_error());
			$domain_pages = mysqli_fetch_assoc($resDomainData);
			$text .= $html;
			$text .= '<form name="frmCartOrder" id="frmCartOrder" action="/cart/cart_order_adress.php" method="POST" onSubmit="return cart_order_send(\'frmCartOrder\',\''.$config['typ'].'\',\''.$config['modul_id'].'\');">';
			if($_SESSION['portal_login'] != 1)  {
				$text .= '<div class="label">Firma</div>';
				$text .= '<div><input type="text" value="'.$_SESSION['order_firma'].'" name="txtFirma" id="txtFirma"/></div>';
				$text .= '<div style="clear:both"></div>';
				$text .= '<div class="label">Email</div>';
				$text .= '<div><input type="text" value="'.$_SESSION['order_email'].'" name="txtEmail" id="txtEmail"/><span class="frm_error" id="txtEmail_err"></span></div>';
				$text .= '<div style="clear:both"></div>';		
				$text .= '<div class="label">Vorname</div>';
				$text .= '<div><input type="text" value="'.$_SESSION['order_vorname'].'" name="txtVorname" id="txtVorname"/><span class="frm_error" id="txtVorname_err"></span></div>';
				$text .= '<div style="clear:both"></div>';
				$text .= '<div class="label">Nachname</div>';
				$text .= '<div><input type="text" value="'.$_SESSION['order_nachname'].'" name="txtNachname" id="txtNachname"/><span class="frm_error" id="txtNachname_err"></span></div>';
				$text .= '<div style="clear:both"></div>';
				$text .= '<div class="label">Stra&szlig;e + Hausnummer</div>';
				$text .= '<div><input type="text" value="'.$_SESSION['order_strasse'].'" name="txtStrasse" id="txtStrasse"/><span class="frm_error" id="txtStrasse_err"></span></div>';
				$text .= '<div style="clear:both"></div>';
				$text .= '<div class="label">PLZ</div>';
				$text .= '<div><input type="text" value="'.$_SESSION['order_plz'].'" name="txtPLZ" id="txtPLZ"/><span class="frm_error" id="txtPLZ_err"></span></div>';
				$text .= '<div style="clear:both"></div>';
				$text .= '<div class="label">Ort</div>';
				$text .= '<div><input type="text" value="'.$_SESSION['order_ort'].'" name="txtOrt" id="txtOrt"/><span class="frm_error" id="txtOrt_err"></span></div>';
				$text .= '<div style="clear:both"></div>';
				$text .= '<div class="label">Land</div>';
				$text .= '<div><input type="text"value="'.$_SESSION['order_land'].'" name="txtLand" id="txtLand" value="Deutschland"/><span class="frm_error" id="txtLand_err"></span></div>';
				$text .= '<div style="clear:both"></div>';
				$text .= '<div class="label">AGB</div>';
				$pathAGB = getPathUrl($_SESSION['language'],$domain_pages['agb_id']);
			}
			$text .= '<div><label><input type="checkbox" value="agb_ok" name="chkAGB" id="chkAGB"> AGB akzeptieren</label> <a href="'.$pathAGB.'" target="_blank">lesen</a>* <span class="frm_error" id="chkAGB_err"></span> </div>';
			$text .= '<div style="clear:both"></div>';			
			$text .= '<div class="label">Widerruf</div>';
			$pathWiderruf = getPathUrl($_SESSION['language'],$domain_pages['widerruf']);
			
			$text .= '<div><label><input type="checkbox" value="widerruf_ok" name="chkWiderruf" id="chkWiderruf"> Widerruf akzeptieren</label> <a href="'.$pathWiderruf.'" target="_blank">lesen</a>* <span class="frm_error" id="chkWiderruf_err"></span></div>'; 
			$text .= '<div style="clear:both"></div>';						
			$text .= '<div class="label">Bestellen</div>';
			$text .= '<div><input type="hidden" name="modultyp" value="'.$_POST['typ'].'"/></div>';
			$text .= '<div><input type="hidden" id="order_domain_id" name="domain_id" value="'.$ShopInfodata['domain_id'].'"/></div>';
			$text .= '<div><input type="hidden" name="modulid" value="'.$_POST['modul_id'].'"/></div>';
			$text .= '<div><input type="submit" class="button" name="btnSenden" value="Zahlungspflichtig bestellen"/><br/><br/>
			<input type="button" class="button" name="btnAbbruch" onClick="javascript:buynow_abort(\''.$_POST['typ'].'\',\''.$_POST['modul_id'].'\')" value="Abrechen zurück zum Artikel"/></div><br/>';
 
			$text .= '';
			$text .= '</form>';
		}
		$text .= '</div><div style="clear:both"></div></div>'; // config modus 

		
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 } 
 $res = LoadModul_shop_cart_order_buynow($_POST);
 echo $res['content'];
 ?>