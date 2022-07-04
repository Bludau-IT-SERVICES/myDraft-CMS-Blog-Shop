<?php 


####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_portal_shop_item_detail($config) {

	#	echo "IN....";
		$dataShopDetail = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_portal_shop_item_detail WHERE id=".$config['modul_id']));
		$query = "SELECT * FROM shop_item WHERE shop_item_id=".$dataShopDetail['shop_item_id'];
		$resData = DBi::$conn->query($query) or die(mysqli_error());
		$ShopItem = mysqli_fetch_assoc($resData);
		
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='portal_shop_item_detail'"));
		
		$dataShopDetail['typ'] = 'portal_shop_item_detail';
		
		$text = '<div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'" itemscope itemtype="http://schema.org/Product">';
		if($config['status'] == 'sended') {
			$titel = 'Bestellung abgeschlossen';
			$strOrderHinweis = '<h3 style="color:indigo">Vielen Dank f&uuml;r Ihre Bestellung!<br/>Ihr Warenkorb wurde per Email abgeschickt und die Bestellung ist abgeschlossen.</h3>';
			$text .= $strOrderHinweis;
		} 
		$text .= convertUmlaute($dataShopDetail["content_".$_SESSION['language']]);
		$titel = convertUmlaute($dataShopDetail["title_".$_SESSION['language']]);
		

		
		if($text == '') {   
			$text = convertUmlaute($dataShopDetail["content_de"]); 
		} 
		
		if($titel == '') { 
			$titel = convertUmlaute($dataShopDetail["title_de"]); 
		} 
		
		// && $config["container"]
		if($_SESSION['login'] == '1'  AND $module_in_menue['container'] == 'col-main') {
			$strReturn = getMember($dataShopDetail['last_usr']);
			if(!empty($strReturn)) {
				$ary = explode(" ",$dataShopDetail['lastchange']);
				$german_de = getDateDE($ary[0]);
				$titel .= '</h1> - '.$strReturn.' - '.$german_de.' '.$ary[1];
			}
		}
		
		# Eingeloggt 
		if (@$_SESSION['login'] == '1')  { 
			if($titel == '') { 
				$titel = $ShopItem['name_de']; 
			} 
		} 
		
		# Eingeloggt 
		if (@$_SESSION['login'] == '1')  { 
			if($titel == '') { 
				$titel = $ShopItem['name_de']; 
			} 
		}  else {
			if($titel == '') { 
				$titel = '<span itemprop="name">'.$ShopItem['name_de'].'</span>'; 
			}			
		}		
		
		#echo $ShopItem['name_de'];
		
		##############################
		# >> Inline suche
		##############################
		#$text = stringToFunction($text);
		$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$ShopItem['shop_item_id']."'";
		$resPictures = DBi::$conn->query($query) or die(mysqli_error());
		$iCount = 0;
		#$text .= '<h1><span itemprop="name">'.$ShopItem['name_de'].'</span></h1>';
		$text .= '<div class="shop_item_picture_box" id="shop_item_picture_box">';	
		
		while($strPic = mysqli_fetch_assoc($resPictures)) {
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
		$text .= '<div style="clear:both"></div>';
		$text .= '</div>
		<div id="shop_item_detail_info">';
		$query = "SELECT * FROM  shop_info WHERE  domain_id='".$ShopItem['domain_id']."'";
		$resShopInfo = DBi::$conn->query($query) or die(mysqli_error());
		$ShopInfodata = mysqli_fetch_assoc($resShopInfo);
		$text .= '<div itemscope itemtype="http://schema.org/Person"><h2>Verk&auml;ufer '.$ShopInfodata['firma'].'</h2>'; 
		#$text .= ' <span itemprop="address" itemscope itemtype="http://data-vocabulary.org/Address">'
		$text .= '<span itemprop="name">'.$ShopInfodata['vorname'].' '.$ShopInfodata['nachname'].'</span><br/>'; 
		#$text .= $ShopInfodata['strasse_hnr'].'<br/>'; 
		$text .= $ShopInfodata['plz'].' <span itemprop="address" itemscope itemtype="http://schema.org/Address"><span itemprop="locality">'.$ShopInfodata['stadt'].'</span></span><br/></div>'; 
		
		
		$text .= '<h2>Artikel Informationen</h2>';	
		$text .= '<span itemprop="name">'.$ShopItem['name_de'].'</span><br/>';
		$text .= '<form name="frmItemAdd" id="cart_item_add_'.$ShopItem['shop_item_id'].'" action="cart/cart_item_add.php" method="POST" onSubmit="return cart_item_add_buynow(\'cart_item_add_'.$ShopItem['shop_item_id'].'\',\''.$config['typ'].'\',\''.$config['modul_id'].'\');">';
	 
				
				$text .= '<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">Preis: <strong><span itemprop="price">'.$ShopItem['preis'].'</span><span itemprop="priceCurrency">EUR</span></strong></div>';
				
				
		$query = "SELECT * from domains WHERE domain_id='".$ShopItem['domain_id']."'";
					
					#$strItem['preis'] = str_replace(".",",",$strItem['preis']);
					$domain_res = mysqli_fetch_assoc(DBi::$conn->query($query));
					switch($domain_res['shop_mwst_setting']) {
						case 'MwSt_inkl':
							$text .= '<font size="1">inkl. '.$strItem['item_mwst'].'% MwSt.</font>';
							break;
						case 'MwSt_exkl':
							$text .= '<font size="1">exkl. '.$strItem['item_mwst'].'% MwSt.</font>';
							break;
						case 'MwSt_befreit':
							$text .= '<font size="1">Kleinunternehmer</font>';
							break;
						case 'MwSt_privatverkauf':
							$text .= '<font size="1">Privatverkauf</font>';
							break;
					default:
						$text .= '<br/>';						
					}
				
				$text .= ' von ';
                     
				if(!empty($ShopInfodata['firma'])) {
					$text .= $ShopInfodata['firma'];
				} else {
					$text .= $ShopInfodata['shop_mitgliedsname'];
				}
				$text .= '<br/>';
					 
				
				#$text .= '</strong> inkl. '.$ShopItem['item_mwst'].'% MwSt.<br/></div>';
				
				if($ShopItem['menge'] > 0) {
					$strStock = '<link itemprop="in_stock" href="http://schema.org/InStock" />';
				} else {
					$strStock = '<link itemprop="out_of_stock" href="http://schema.org/InStock" />';				
				}
				
				$text .= 'Menge: <strong><span id="item_menge_update">'.$ShopItem['menge'].'</span> </strong>auf Lager<br/>';
				$text .= 'Gewicht: <strong><span id="item_gewicht_update">'.str_replace(".",",",$ShopItem['gewicht']).'</span> KG</strong><br/>';
				$text .= 'Lieferzeit: <strong><span id="item_lieferzeit_update">'.$ShopItem['lieferzeit'].'</span> </strong><br/>';
				$text .= 'Artikelnummer: <strong><span id="item_artikelnummer_update">'.$ShopItem['item_number'].'</span> </strong><br/>';
		
		// Versandkosten berechnen
		$query = "SELECT * FROM shop_shippment_detail WHERE shop_shippment_detail.gewicht_von <= '".$ShopItem['gewicht']."' AND shop_shippment_detail.gewicht_bis>= '".$ShopItem['gewicht']."' AND domain_id='".$ShopItem['domain_id']."'";

		$resVersandkostenPreis = DBi::$conn->query($query) or die(mysqli_error());
		$shop_artikel_preisVersand = 0;
		$bVersandartGefunden = false;
		while($strVersandKosten = mysqli_fetch_assoc($resVersandkostenPreis)) {
			
			$query ="SELECT * FROM shop_shippment WHERE shop_shippment_id='".$strVersandKosten['shop_shippment_id']."'";
			$resVersandArt = DBi::$conn->query($query);
			$strVersandKostenName = mysqli_fetch_assoc($resVersandArt);
				
			$strVersandHTML .= 'Versandkosten: '.$strVersandKostenName['name_de'].' = <strong>'.str_replace('.',',',$strVersandKosten['versandkosten']).' EUR</strong><br/>';
			$shop_artikel_preisVersand = $strVersandKosten['versandkosten'];
			$shop_artikel_preisVersand_mwst = $strVersandKostenName['mwst'];
			$bVersandartGefunden = true;
		}
		
		if($bVersandartGefunden == false) {
			if($ShopItem['versandkosten'] != '') {
				if(!empty($ShopItem['versandkosten_name'])) {
					$strVersandHTML .= 'Versandkosten: <strong>'.$ShopItem['versandkosten_name'].' '.str_replace('.',',',$ShopItem['versandkosten']).' EUR</strong><br/>';
				} else {
					$strVersandHTML .= 'Versandkosten: <strong>'.str_replace('.',',',$ShopItem['versandkosten']).'EUR</strong><br/>';
				}
			} else {				
				if($domain_res['shipping_id'] != 1) {
					$strPathLink = '<a href="http://'.$domain_res['name'].'/'.getPathUrl($_SESSION['language'],$domain_res['shipping_id']).'" target="_blank" title="Versandkosten anzeigen">Versandkosten</a>';
				} else {
					$strPathLink = '';
				}  
				$strVersandHTML .= 'Versandkosten: Sie bekommen die '.$strPathLink.' nach der Bestellung mitgeteilt.<br/>';
			}
		} else {
			 
			if($ShopItem['versandkosten'] != '') {
				if(!empty($ShopItem['versandkosten_name'])) {

					$strVersandHTML = 'Versandkosten: <strong>'.$ShopItem['versandkosten_name'].' '.str_replace('.',',',$ShopItem['versandkosten']).' EUR</strong><br/>';
				} else {
					if($ShopItem['versandkosten'] == '0,00') {						
						$strVersandHTML = 'Kostenloser Versand innerhalb Deutschland<br/>';
					} else {
						$strVersandHTML = 'Versandkosten: <strong>'.str_replace('.',',',$ShopItem['versandkosten']).'</strong> EUR<br/>';
					}
				} 
			} 
		}
		
		$text .= $strVersandHTML;
		
		if(empty($ShopItem['shop_item_id'])) {
			$ShopItem['shop_item_id'] = 0;
		}
			$query = "SELECT * FROM shop_item_eigenschaft JOIN shop_item_eigenschaftwert ON shop_item_eigenschaft.shop_item_eigenschaft_id = shop_item_eigenschaftwert.id_shop_item_eigenschaft WHERE shop_item_eigenschaft.id_shop_item =".$ShopItem['shop_item_id'].' ORDER BY shop_item_eigenschaft.shop_item_eigenschaft_id ASC';
			$res = DBi::$conn->query($query) or die(mysqli_error());
			$iCount = 0;
			$iCountAttribute = 0;
			$bIn = false;
			$iSelectClose =0;
			$strAttribAry = array();
			while($strEigenschaft = mysqli_fetch_assoc($res)) {

					if($iEigenschaft != $strEigenschaft['shop_item_eigenschaft_id']) {
						$iCount = 0;
						$strAttribAry[$iCountAttribute] = $strEigenschaft['shop_item_eigenschaft_id'];
						$iCountAttribute++;
					}
				#echo $iEigenschaft.'--'.$iCount.' ';
					if($iCount == 0) {
						if($iSelectClose > 0) {
							$text .= '</select><br/>';
							#$iCountAttribute++;
						}
						$iSelectClose++;
						$text .= '<strong>'.$strEigenschaft['eigenschaft_name_de'].'</strong>*<br/>';
						$text .= '<select size="1" name="optModul" id="eigenschaft_'.$strEigenschaft['shop_item_eigenschaft_id'].'" onChange="shop_item_attribute(\''.$strEigenschaft['shop_item_eigenschaft_id'].'\')">';						
						$text .= '<option value="Keine-Auswahl">Bitte Auswahl treffen</option>';
						#$text .= '<option value="'.$strEigenschaft['shop_item_eigenschaftwert_id'].'">'.$strEigenschaft['name_de'].'</option>';
						$text .= '<option value="'.$strEigenschaft['name_de'].'">'.$strEigenschaft['name_de'].'</option>';
						
					} else {
						#$text .= '<option value="'.$strEigenschaft['shop_item_eigenschaftwert_id'].'">'.$strEigenschaft['name_de'].'</option>';
						$text .= '<option value="'.$strEigenschaft['name_de'].'">'.$strEigenschaft['name_de'].'</option>';
					}

					$iEigenschaft = $strEigenschaft['shop_item_eigenschaft_id'];

				$iCount++;
				
				$bIn=true;
			}
			if($bIn == true) {
				$text .='</select><br/>';
			}
			$text .= '<br/><strong>Bestellmenge</strong><br/><input id="cart_order_amount_minus" name="cart_order_amount_minus" type="button" value="-" onClick="javascript:cart_item_amount(\'minus\',\'shop_item_count\')" /><input type="text" id="shop_item_count" name="shop_item_count" style="text-align:center" value="1"/><input id="cart_order_amount_plus" name="cart_order_amount_plus" type="button" value="+" onClick="javascript:cart_item_amount(\'plus\',\'shop_item_count\')"/><br/><br/>';
			# Shop geschlossen?
			
			$text .= $strOrderHinweis;
			if($ShopItem['system_closed_shop'] == 'Y') {
				$text .='<br/><strong>Der Verk&auml;ufer hat seinen Online Shop Gesch&auml;ft zur Zeit geschlossen.</strong>';
			} else {
				if($ShopItem['item_enabled'] == 'N') {
					$text .='<br/><strong>Dieser Artikel ist zur Zeit nicht aktiv.</strong>';
				} else {
					# Artikel ausverkauft?
					if($ShopItem['menge']  > 0) {
						$text .='<br/><input type="submit" class="button" value="Artikel Sofortkaufen"/><br/><br/>';
					} else {
						$text .='<br/><strong>Artikel ist ausverkauft.</strong>';
					}				
				}
			}
				$text .='<input type="hidden" id="shop_item_id" name="shop_item_id" value="'.$ShopItem['shop_item_id'].'"/>
			 
				<input id="shop_item_price" type="hidden" name="shop_item_price" value="'.$ShopItem['preis'].'"/>
			</form><div style="clear:both"></div>';
			
			if($ShopItem['beschreibung'] != '') {
				$text .= '<h2>Beschreibung</h2><span itemprop="description">'.utf8_encode($ShopItem['beschreibung']).'</span><br/><br/>';
			}
			
			$query = "SELECT * FROM domains WHERE domain_id='".$ShopItem['domain_id']."'";
			$resDomain = DBi::$conn->query($query) or die(mysqli_error());
			$strDomainLink = mysqli_fetch_assoc($resDomain);
			$pathAGB = getPathUrl($_SESSION['language'],$strDomainLink['agb_id']);
			$pathShopCat = getPathUrl($_SESSION['language'],$ShopItem['menue_id']);
			$query ="SELECT * FROM shop_category WHERE shop_cat_id='".$ShopItem['shop_cat_id']."'";
			$strShopCatMenue = mysqli_fetch_assoc(DBi::$conn->query($query));
			$pathShopCat2 = getPathUrl($_SESSION['language'],$strShopCatMenue['page_id']);
			
			if($strDomainLink['bIsSSL'] == 'Y') {
				$strSchema = 'https';
			} else {
				$strSchema = 'http';
			}
			
			$text .='<ul>';
			$text .= '<li><a href="'.$strSchema.'://'.$strDomainLink['name'].'/'.$pathShopCat.'">Produkt im Online Shop ansehen</a></li>';
			$text .= '<li><a href="'.$strSchema.'://'.$strDomainLink['name'].'/'.$pathShopCat2.'">Kategorieauflistung "'.$strShopCatMenue['name_de'].'" des Online Shop ansehen</a></li>';
			$text .= '<li><a href="'.$strSchema.'://'.$strDomainLink['name'].'/">Online Shop Startseite</a></li>';
			#echo $pathAGB;
			if($pathAGB != 'de///') {
					$text .= '<ul><li><a href="'.$strSchema.'://'.$strDomainLink['name'].'/'.$pathAGB.'" target="_blank">AGB von '.$ShopInfodata['firma'].'</a></li></ul>';
			} 		
			$text .= '</ul>';
			$text .= "<script>
    $(document).ready(function() {
        $('.fancybox').fancybox({
            padding : 0,
            openEffect  : 'elastic'
        });
		
		//$('.shop_item_header h1 a').bigText();
    });
</script>";
			#if($ShopItem['beschreibung'] != '') {
				#$text .='<h2>Beschreibung</h2>';
				#$text .=$ShopItem['beschreibung'];
			#}
		?>
		<script>
		var attribute = new Array ();
		var iAnzahl = <?php echo $iCountAttribute; ?> ;
		var strShopID = '<?php echo $ShopItem['item_number']; ?>';
		var strKombi;
		<?php 
		for($i=0; $i < count($strAttribAry); $i++) {
			echo "attribute[".$i."] =".$strAttribAry[$i].";\n";
		}
		?>
function shop_item_attribute(eigenschaft_id) {
 
			strKombi = "";
			var bIn = false;
			for($i=0; $i < attribute.length; $i++) {
				//alert($("#eigenschaft_" + attribute[$i] + " option:selected").val());
				if($("#eigenschaft_" + attribute[$i] + " option:selected").val() == 'Keine-Auswahl') {
					bIn=true;
					break;
				}
				if($i == attribute.length -1) {				
					strKombi = strKombi + $("#eigenschaft_" + attribute[$i] + " option:selected").val();
				} else {
					strKombi = strKombi + $("#eigenschaft_" + attribute[$i] + " option:selected").val() + "-";
				}
			}			
			//alert(strKombi);
			//alert(iAnzahl + " " + attribute.length);
			if(bIn == false) {
				$.ajax(
					{
						url : "/api.php",
						type: "POST",
						data : "modus=get_shop_item&item_number=" + strShopID  + "-" + strKombi,
						success:function(data, textStatus, jqXHR)
						{
							var ItemFields = data.split("~");
							alert(ItemFields);
							$("#item_price_update").html(ItemFields[2]);
							$("#item_menge_update").html(ItemFields[5]);
							$("#item_gewicht_update").html(ItemFields[7]);
							$("#shop_item_id").val(ItemFields[0]);
							$("#shop_item_price").val(ItemFields[2]);
							$('#shop_footer').html(data);
							return false;
						},
						error: function(jqXHR, textStatus, errorThrown)
						{
							//if fails     
						}
					});
					return false;
			}
			/*$( "select" ).each(function() {
					alert(this.value);
				}); */
}
function shop_order_check() {
	strKombi = "";
	var bIn = false;
	for($i=0; $i < attribute.length; $i++) {
		//alert($("#eigenschaft_" + attribute[$i] + " option:selected").val());
		if($("#eigenschaft_" + attribute[$i] + " option:selected").val() == 'Keine-Auswahl') {
			bIn=true;
			break;
		}
	}
	if(bIn == true) {
		//alert('Bitte alle Optionen auswählen');
		return false;
	} else {
		return true;
	}
}
		</script>
		<?php
		
		$text .='</div>';
		
		$text .='<div class="softclear"></div>';
		
		
		
		
		
		###############
		# >> Eingelogt 
		###############
		
		/*if (@$_SESSION["login"] == '1')  { 			
			$text = '<div ondblclick="getTexthtmlEdit('.$dataShopDetail['id'].');" id="texthtml_'.$dataShopDetail['id'].'">'.$text.'</div>'; 
		} */
		

		#$text .= '</div>';
		#$text .='<div class="softclear"></div>';
		####################################################
		# Ähnliche Artikel
		####################################################
		
		if(!empty($ShopItem['beschreibung'])) {
			
		
			$text .= '<div class="portal_similiar">
			<h3>Weitere Produkte ansehen</h3>'; 
			$query = 'SELECT * FROM shop_item WHERE MATCH (Beschreibung) AGAINST ("'.DBi::mysql_escape($ShopItem['beschreibung'],DBi::$conn).'" IN NATURAL LANGUAGE MODE) and status_de=\'verkaufsbereit\' LIMIT 1,12;';  
			$resItems =  DBi::$conn->query($query) or die(mysqli_error());
			$text .= '<ul class="shop_cat_similar">';
			while($strItem = mysqli_fetch_assoc($resItems)) { 
			
				$query = "SELECT * FROM domains WHERE domain_id='".$strItem['domain_id']."'";
				$resDomain = DBi::$conn->query($query) or die(mysqli_error());
				$strDomainLink = mysqli_fetch_assoc($resDomain);
				if($strItem['shopste_marktplatz_menue_id'] != 0) {
					$path = getPathUrl($_SESSION['language'],$strItem['shopste_marktplatz_menue_id']);				
					$strLink = 'https://shopste.com/'.$path;
				} else {
					$path = getPathUrl($_SESSION['language'],$strItem['menue_id']);				
					$strLink = 'http://'.$strDomainLink['name'].'/'.$path;				
				}
				$text .= '<li>';
				
				$text .= '<div class="shop_list_similar" id="shop_list_similar">
					
					<form name="frmItemAdd_'.$strItem['shop_item_id'].'" id="cart_item_add_'.$strItem['shop_item_id'].'" action="cart/cart_item_add.php" method="POST" onSubmit="return cart_item_add(\'cart_item_add_'.$strItem['shop_item_id'].'\');">';
					


					
					$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$strItem['shop_item_id']."' AND picture_nr=1";
					$resPicture = DBi::$conn->query($query) or die(mysqli_error());
					$strPicture_cat = mysqli_fetch_assoc($resPicture);
					
					// Thumb Nail laden
					#$pic_type = strtolower(strrchr($strPicture['picture_url'],"."));
					#$pic_filename = str_replace($pic_type,"",$strPicture['picture_url']);	
					#$strNewPic = str_replace($pic_filename,$pic_filename.'_catList'.$pic_type,$pic_filename);
			
					
					if($strPicture_cat['picture_url'] != '') {
						$text .= '<a class="cloud-zoom" href="'.$strPicture_cat['picture_url'].'" rel="useWrapper: true,showTitle: true, zoomWidth:\'300\', zoomHeight:\'400\', adjustY:0, adjustX:10;" style="margin-left: auto;margin-right: auto;">';
					} else {
						$text .= '<a href="'.$strPicture['picture_url'].'" >'.$strItem['name_de'];
					}
					
					if($strPicture_cat['picture_url'] != '') {
						$strBig = str_replace("/produkte/orginal/","/produkte/kategorie/",$strPicture_cat['picture_url']);
						$text .= '<img id="shop_item_picture_'.$strItem['shop_item_id'].'" src="'.$strBig.'" height="200" width="230" border="0" title="'.$strItem['name_de'].'" style="z-index:9;"/></a><br/>';
					}
					$text .= '<div class="shop_item_header"><a style="width:90%;display:block;" href="'.$strLink.'">'.substr($strItem['name_de'],0,100).'</a></div>';
					#'MwSt_inkl','MwSt_exkl','MwSt_befreit'
					# Domain bestimmmen
					#$domain = mysql_real_escape_string($_SERVER['HTTP_HOST']);
					#$domain = str_replace("www.", "", $domain);
					
					
					$text .= '<br/>
							<input type="hidden" name="shop_item_id" value="'.$strItem['shop_item_id'].'"/>
							<input type="hidden" name="shop_item_price" value="'.$strItem['preis'].'"/>';
					if($strItem['menge']  > 0) {
						#$text .='<input type="submit" class="button" value="In den Warenkorb"/>';
					} else {
						$text .='<h2>Artikel ausverkauft</h2>';
					}
						
							$text .='</form>
					</div>';
					$text .= '</li>';				
			}
			$text .= '</ul></div>';				
		}
		$text .= '<div style="clear:both"></div>'; // config modus 
		
		
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 } 
 ?>