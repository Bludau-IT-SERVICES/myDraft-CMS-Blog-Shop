<?php 


####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_shop_item_detail($config) {

		$dataShopDetail = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_shop_item_detail WHERE id=".$config['modul_id']));
		$query = "SELECT * FROM shop_item WHERE shop_item_id=".$dataShopDetail['shop_item_id'];
		$resData = DBi::$conn->query($query) or die(mysqli_error());
		$ShopItem = mysqli_fetch_assoc($resData);
		
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='shop_item_detail'"));
		#echo "IN";
		
		$dataShopDetail['typ'] = 'shop_item_detail';
		
		$text = '<div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
		
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
				$titel = "Kein Titel"; 
			} 
		}  else {
			if($titel == '') { 
				$titel = '<span itemprop="name">'.$ShopItem['name_de'].'</span>'; 
			}			
		}
		
		$titel = utf8_encode($titel); 
		
		##############################
		# >> Inline suche
		##############################
		#$text = stringToFunction($text);
		$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$ShopItem['shop_item_id']."'";
		$resPictures = DBi::$conn->query($query) or die(mysqli_error());
		$iCount = 0;
		#$text .= '<h1>'.$ShopItem['name_de'].'</h1>';
		$text .= '<div class="shop_item_picture_box">';
		
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
		$text .= '</div>
		<div id="shop_item_detail_info">
			<form name="frmItemAdd" id="cart_item_add_'.$ShopItem['shop_item_id'].'" action="cart/cart_item_add.php" method="POST" onSubmit="return cart_item_add(\'cart_item_add_'.$ShopItem['shop_item_id'].'\');">';
			 # Domain bestimmmen
			$domain = $_SERVER['HTTP_HOST'];
			$domain = str_replace("www.", "", $domain);
			$query = "SELECT * from domains WHERE name='$domain'";
			$domain_res = mysqli_fetch_assoc(DBi::$conn->query($query));
			
			switch($domain_res['shop_mwst_setting']) {
				case 'MwSt_inkl':
					$text .= 'Preis: <strong><span id="item_price_update"><span itemprop="price">'.$ShopItem['preis'].'</span></span> EUR </strong><font size="1">inkl. '.$ShopItem['item_mwst'].'% MwSt.<br/></font>';
					break;
				case 'MwSt_exkl':
					$text .= 'Preis: <strong><span id="item_price_update">'.$ShopItem['preis'].'</span> EUR </strong><font size="1">exkl. '.$ShopItem['item_mwst'].'% MwSt.<br/></font>';
					break;
				case 'MwSt_befreit':
					$text .= 'Preis: <strong><span id="item_price_update">'.$ShopItem['preis'].'</span> EUR <br/></strong><font size="1">Kleinunternehmer : Der Umsatz ist nach dem Umsatzsteuergesetz &sect; 19 nicht Steuerpflichtig<br/></font>';
					break;
				case 'MwSt_privatverkauf':
					$text .= 'Preis: <strong><span id="item_price_update">'.$ShopItem['preis'].'</span> EUR <br/></strong>';
					$text .= '<font size="1">Privatverkauf</font><br/>';
					break;					
				default:
					$text .= 'Preis: <strong>'.$ShopItem['preis'].' EUR </strong><br/>';					
			}
			
				#$text .= 'Preis: <strong><span id="item_price_update">'.$ShopItem['preis'].'</span> EUR</strong> inkl. '.$ShopItem['item_mwst'].'% MwSt.<br/>';
				$text .= 'Menge: <strong><span id="item_menge_update">'.$ShopItem['menge'].'</span><input type="hidden" name="cart_item_stock" id="cart_item_stock" value="'.$ShopItem['menge'].'"/> </strong>auf Lager<br/>';
				$text .= 'Gewicht: <strong><span id="item_gewicht_update">'.$ShopItem['gewicht'].'</span> </strong>KG<br/>';
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
				
			$strVersandHTML .= 'Versandkosten '.$strVersandKostenName['name_de'].' '.str_replace('.',',',$strVersandKosten['versandkosten']).' EUR<br/>';
			$shop_artikel_preisVersand = $strVersandKosten['versandkosten'];
			$shop_artikel_preisVersand_mwst = $strVersandKostenName['mwst'];
			$bVersandartGefunden = true;
		}
		
		if($bVersandartGefunden == false) {
			if($domain_res['shipping_id'] != 1) {
				$strPathLink = '<a href="http://'.$domain_res['name'].'/'.getPathUrl($_SESSION['language'],$domain_res['shipping_id']).'" target="_blank" title="Versandkosten anzeigen">Versandkosten</a>';
			} else {
				$strPathLink = '';
			}  
			$strVersandHTML .= 'Versandkosten: Sie bekommen die '.$strPathLink.' nach der Bestellung mitgeteilt.<br/>';
		}
		
		$text .= $strVersandHTML;
		$text .= '<br/>';
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
						$text .= '<strong class="optUserLabel">'.$strEigenschaft['eigenschaft_name_de'].'*</strong><br/>';
						$text .= '<select class="optUserSelect" size="1" name="optModul" id="eigenschaft_'.$strEigenschaft['shop_item_eigenschaft_id'].'" onChange="shop_item_attribute(\''.$strEigenschaft['shop_item_eigenschaft_id'].'\')">';						
						$text .= '<option class="optUserSelectVal" value="Keine-Auswahl">Bitte Auswahl treffen</option>';
						#$text .= '<option value="'.$strEigenschaft['shop_item_eigenschaftwert_id'].'">'.$strEigenschaft['name_de'].'</option>';
						$text .= '<option class="optUserSelectVal" value="'.$strEigenschaft['name_de'].'">'.$strEigenschaft['name_de'].'</option>';
						
					} else {
						#$text .= '<option value="'.$strEigenschaft['shop_item_eigenschaftwert_id'].'">'.$strEigenschaft['name_de'].'</option>';
						$text .= '<option class="optUserSelectVal" value="'.$strEigenschaft['name_de'].'">'.$strEigenschaft['name_de'].'</option>';
					}

					$iEigenschaft = $strEigenschaft['shop_item_eigenschaft_id'];

				$iCount++;
				
				$bIn=true;
			}
			
			if($bIn == true) {
				$text .='</select><br/>';
			}
			$text .= '<span itemprop="name">'.$ShopItem['name_de'].'</span><br/><br/>';
			#$text .=  $_GET['abc'].'-kombi';
			if($ShopItem['beschreibung'] != '') {
				$text .= '<h2>Beschreibung</h2><span itemprop="description">'.$ShopItem['beschreibung'].'</span><br/><br/>';
			}
			if($ShopItem['menge']  > 0 and $ShopItem['menue_id'] == $_SESSION['page_id']) {
				$text .= '<strong>Bestellmenge</strong><br/><input id="cart_order_amount_minus" name="cart_order_amount_minus" type="button" value="-" onClick="javascript:cart_item_amount(\'minus\',\'shop_item_count\')" /><input type="text" id="shop_item_count" name="shop_item_count" style="text-align:center" value="1"/><input id="cart_order_amount_plus" name="cart_order_amount_plus" type="button" value="+" onClick="javascript:cart_item_amount(\'plus\',\'shop_item_count\')"/><br/><br/>';
			}
			# Shop geschlossen?
			if($ShopItem['system_closed_shop'] == 'Y') {
				$text .='<br/><strong>Der Verk&auml;ufer hat seinen Online Shop Gesch&auml;ft zur Zeit geschlossen.</strong>';
			} else {
				if($ShopItem['item_enabled'] == 'N') {
					$text .='<strong>Dieser Artikel ist zur Zeit nicht aktiv.</strong>';
				} else {
					# Artikel ausverkauft?
					if($ShopItem['menge']  > 0) {
						if($ShopItem['menue_id'] == $_SESSION['page_id']) {
							$text .='<input type="submit" style="margin-bottom:25px"; class="button" value="In den Warenkorb"/>';
						} else {
							$query = "SELECT * FROM domains WHERE domain_id='".$ShopItem['domain_id']."'";
							$resDomain = DBi::$conn->query($query) or die(mysqli_error());
							$strDomainLink = mysqli_fetch_assoc($resDomain);
							if($ShopItem['shopste_marktplatz_menue_id'] != 0) {
								$path = getPathUrl($_SESSION['language'],$ShopItem['shopste_marktplatz_menue_id']);				
								$strLink = 'http://shopste.com/'.$path;
							} else {
								$path = getPathUrl($_SESSION['language'],$ShopItem['menue_id']);				
								$strLink = 'http://'.$strDomainLink['name'].'/'.$path;				
							}
							$text .='
							<script>
							function loadurl(url) {
								location.href =url;
							}
							</script>
							<input type="button" Onclick="loadurl(\''.$strLink.'\')" style="margin-bottom:25px"; class="button" value="Zum Online Shop gehen"/>';
							
						}
#						$text .='<input type="submit" class="button" value="In den Warenkorb"/>';
					} else {
						$text .='<h2>Artikel ist ausverkauft.</h2>';
					}				
				}
			}
				$text .='<input type="hidden" id="shop_item_id" name="shop_item_id" value="'.$ShopItem['shop_item_id'].'"/>
				
				<input id="shop_item_price" type="hidden" name="shop_item_price" value="'.str_replace(",",".",$ShopItem['preis']).'"/>
			</form><div style="clear:both"></div>';

			#if($ShopItem['beschreibung'] != '') {
				#$text .='<h2>Beschreibung</h2>';
				#$text .=$ShopItem['beschreibung'];
			#}
		?>
		<script>
		var attribute = new Array ();
		var iAnzahl = <?php echo $iCountAttribute; ?> ;
		var strShopID = <?php echo $ShopItem['item_number']; ?> ;
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
		$text .='</div>
		<div class="softclear"></div>';
		$text .= "<script>
    $(document).ready(function() {
        $('.fancybox').fancybox({
            padding : 0,
            openEffect  : 'elastic'
        });
		
		//$('.shop_item_header h1 a').bigText();
    });
</script>";
	
		
		###############
		# >> Eingelogt 
		###############
		
		/*if (@$_SESSION["login"] == '1')  { 			
			$text = '<div ondblclick="getTexthtmlEdit('.$dataShopDetail['id'].');" id="texthtml_'.$dataShopDetail['id'].'">'.$text.'</div>'; 
		} */
					####################################################
		# Ähnliche Artikel
		####################################################
		$query = 'SELECT *,MATCH (Beschreibung) AGAINST ("'.mysql_real_escape_string($ShopItem['name_de']).'" IN NATURAL LANGUAGE MODE) 
AS score FROM shop_item WHERE MATCH (Beschreibung) AGAINST ("'.mysql_real_escape_string($ShopItem['name_de']).'" IN NATURAL LANGUAGE MODE)  and status_de=\'verkaufsbereit\' AND domain_id='.$_SESSION['domain_id'].' ORDER BY score DESC LIMIT 0,12;';  
		$resItems =  DBi::$conn->query($query) or die(mysqli_error());
		$text .= '<ul class="shop_cat_similar">';
		
		while($strItem = mysqli_fetch_assoc($resItems)) { 
		
			$text .= '<li>';
			$query = "SELECT * FROM domains WHERE domain_id='".$strItem['domain_id']."'";
			$resDomain = DBi::$conn->query($query) or die(mysqli_error());
			$strDomainLink = mysqli_fetch_assoc($resDomain);
			if($strItem['shopste_marktplatz_menue_id'] != 0) {
				$path = getPathUrl($_SESSION['language'],$strItem['shopste_marktplatz_menue_id']);				
				$strLink = 'http://shopste.com/'.$path;
			} else {
				$path = getPathUrl($_SESSION['language'],$strItem['menue_id']);				
				$strLink = 'http://'.$strDomainLink['name'].'/'.$path;				
			}
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
				$domain = $_SERVER['HTTP_HOST'];
				$domain = str_replace("www.", "", $domain);
				$query = "SELECT * from domains WHERE name='$domain'";
				
				#$strItem['preis'] = str_replace(".",",",$strItem['preis']);
				$domain_res = mysqli_fetch_assoc(DBi::$conn->query($query));
				switch($domain_res['shop_mwst_setting']) {
					case 'MwSt_inkl':
						$text .= 'Preis: <strong>'.$strItem['preis'].' EUR </strong><font size="1">inkl. '.$strItem['item_mwst'].'% MwSt.</font>';
						break;
					case 'MwSt_exkl':
						$text .= 'Preis: <strong>'.$strItem['preis'].' EUR </strong><font size="1">exkl. '.$strItem['item_mwst'].'% MwSt.</font>';
						break;
					case 'MwSt_befreit':
						$text .= 'Preis: <strong>'.$strItem['preis'].' EUR </strong><br/><font size="1">Kleinunternehmer : Der Umsatz ist nach dem Umsatzsteuergesetz <br/>&sect; 19 nicht Steuerpflichtig</font>';
						break;
					case 'MwSt_privatverkauf':
						$text .= 'Preis: <strong>'.$strItem['preis'].' EUR </strong><font size="1">Privatverkauf</font>';
						break;		
					default:
						$text .= 'Preis: <strong>'.$strItem['preis'].' EUR </strong>';
				}
				
				$text .= '</font><br/>
						<input type="hidden" name="shop_item_id" value="'.$strItem['shop_item_id'].'"/>
						<input type="hidden" name="shop_item_count" value="1"/>
						<input type="hidden" name="shop_item_price" value="'.str_replace(",",".",$strItem['preis']).'"/>';
				
					# Shop geschlossen?
			if($ShopItem['system_closed_shop'] == 'Y') {
				$text .='<br/><strong>Der Verk&auml;ufer hat seinen Online Shop Gesch&auml;ft zur Zeit geschlossen.</strong>';
			} else {
				if($ShopItem['item_enabled'] == 'N') {
					$text .='<strong>Dieser Artikel ist zur Zeit nicht aktiv.</strong>';
				} else {
					# Artikel ausverkauft?
					if($ShopItem['menge']  > 0) {
						if($ShopItem['menue_id'] == $_SESSION['page_id']) {
							$text .='<input type="submit" style="margin-bottom:25px"; class="button" value="In den Warenkorb"/>';
						} else {
							$query = "SELECT * FROM domains WHERE domain_id='".$ShopItem['domain_id']."'";
							$resDomain = DBi::$conn->query($query) or die(mysqli_error());
							$strDomainLink = mysqli_fetch_assoc($resDomain);
							if($ShopItem['shopste_marktplatz_menue_id'] != 0) {
								$path = getPathUrl($_SESSION['language'],$ShopItem['shopste_marktplatz_menue_id']);				
								$strLink = 'http://shopste.com/'.$path;
							} else {
								$path = getPathUrl($_SESSION['language'],$ShopItem['menue_id']);				
								$strLink = 'http://'.$strDomainLink['name'].'/'.$path;				
							}
							$text .='
							<script>
							function loadurl(url) {
								location.href =url;
							}
							</script>
							<input type="button" Onclick="loadurl(\''.$strLink.'\')" style="margin-bottom:25px"; class="button" value="Zum Online Shop gehen"/>';
							
						}
#						$text .='<input type="submit" class="button" value="In den Warenkorb"/>';
					} else {
						$text .='<h2>Artikel ist ausverkauft.</h2>';
					}				
				}
			}
			 
					
						$text .='</form>
				</div>';			
				$text .= '</li>';
		}
		$text .= '</ul>';
		$text .= '<div style="clear:both"></div>'; // config modus 
		$text .= '</div>'; // config modus 

			
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 } 
 ?>