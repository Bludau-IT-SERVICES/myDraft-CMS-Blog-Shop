<?php
session_start();
		$strAry = explode("|",$_SESSION['shop_cart_ids']);
		$shop_artikel_anzahl = 0;
		
		$shop_artikel_preis = 0.0;
		
		// Alle Artikel durchlaufen
		for($i=0; $i < count($strAry) -1; $i++) {
			
			// Artikel Details holen 
			$strItemDetailAry = explode("-",$strAry[$i]);			
			#echo $strItemDetailAry[1];
			$shop_artikel_anzahl += $strItemDetailAry[1];
			 
			
			
		}
		echo $shop_artikel_anzahl;
?>