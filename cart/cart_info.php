<?php
	@session_start();
	
	$path = realpath($_SERVER["DOCUMENT_ROOT"]);
	
	include_once($path.'/include/inc_config-data.php');
	include_once($path.'/include/inc_basic-functions.php');
	
	function getCartFooterInfo() {		
		
		if(!empty($_SESSION['shop_cart_ids'])) {
			$strAry = explode("|",$_SESSION['shop_cart_ids']);
		} else {
			$strAry = array();
		}
		$shop_artikel_anzahl = 0;
		
		$shop_artikel_preis = 0.0;
		
		// Alle Artikel durchlaufen
		for($i=0; $i < count($strAry) -1; $i++) {
			
			// Artikel Details holen 
			$strItemDetailAry = explode("-",$strAry[$i]);			
			#echo $strItemDetailAry[1];
			$shop_artikel_anzahl += $strItemDetailAry[1];
			$shop_artikel_preis += ($strItemDetailAry[2] * $strItemDetailAry[1]);
			
			
		}
		
		$query = "SELECT * from domains WHERE domain_id='".$_SESSION['domain_id']."'";			
		$resDomainData = DBi::$conn->query($query) or die(mysqli_error());
		$domain_pages = mysqli_fetch_assoc($resDomainData);
			
		if ($shop_artikel_anzahl == 0) {
			if($domain_pages['isOrderAllowed'] == 'Y') {
				$html = '<b>Warenkorb ist leer!</b>';
			} else {				
				$html = '<b>Momentan sind keine Online Bestellungen m&ouml;glich!</b>';
			}
		} else {

			
			$pathWarenkorb = getPathUrl($_SESSION['language'],$domain_pages['warenkorb_id']);
			$pathZurKasse = getPathUrl($_SESSION['language'],$domain_pages['zurkasse_id']);
			
			if($domain_pages['isRestaurant'] == 'Y' || $domain_pages['enable_new_frame_warenkorb'] == 'Y') { 
				#<div class="overlap"></div>
				#javascript:mini_warenkorb_frame()
				
				$html =  '<span class="spanlink" onclick="javascript:mini_zur_kasse_frame()"><div class="overlap"><img src="/templates/mekong/images/warenkorb_symbol.png"/></div></span>
				
				<span class="spanlink" onclick="javascript:mini_zur_kasse_frame(\'OK\')"><div class="overlap_right"><img src="/templates/mekong/images/bestellen_symbol.png"/></div></span><div id="frame_detail_info"></div><span class="spanlink_frame" onclick="javascript:mini_zur_kasse_frame(\'OK\')">'.$shop_artikel_anzahl.' Gericht(e) f&uuml;r '.number_format($shop_artikel_preis, 2, ',', '.').' &euro; im Warenkorb (anklicken)</span>';
				
				$html .= '
				<script>
				$(document).ready(function(){
				// Anzahl Warenkorb holen
				$.ajax(
						{
							url : "/ACP/user_warenkorb_count.php",
							type: "POST",
							data : "",
							success:function(data, textStatus, jqXHR)
							{
 
								$(\'<style>.overlap:after{content:"Ihre Bestellung \' + data + \' Gericht(e)";white-space: pre-wrap;}</style>\').appendTo(\'head\');
								
								return false;
							},
							error: function(jqXHR, textStatus, errorThrown)
							{
								//if fails     
							}
						});
					});
				</script>';
				
			} else {
				$html = '<a href="'.$pathWarenkorb.'">'.$shop_artikel_anzahl.' Produkte f&uuml;r '.number_format($shop_artikel_preis, 2, ',', '.').' EUR im Warenkorb</a> | <a href="'.$pathZurKasse.'">Jetzt bestellen</a>';
			}
		}
		return $html;
	}
	if(isset($_GET['bRefreshAjax'])) {
		if(isset($_GET['session_dead'])) {
			session_destroy();
		}
		if($_GET['bRefreshAjax'] == 'true') {
			echo getCartFooterInfo();
		}
	}
 ?>