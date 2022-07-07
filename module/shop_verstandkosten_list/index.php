<?php 
		
@session_start();

####################################
# >> LoadModul_portal_umkreis Modul 
####################################
function LoadModul_shop_verstandkosten_list($config) {

		$dataTextHTML = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_shop_verstandkosten_list WHERE id=".$config['modul_id']));
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='shop_verstandkosten_list'"));
		#echo "IN";
		
		$dataTextHTML['typ'] = 'shop_verstandkosten_list';
		
		$text = '<div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
		
		#$text .= convertUmlaute($dataTextHTML["content_".$_SESSION['language']]);
		$titel = convertUmlaute($dataTextHTML["title_".$_SESSION['language']]);
		
		#$query = "SELECT * from domains WHERE domain_id='".$_SESSION['domain_id']."'";			
		#$resDomainData = DBi::$conn->query($query) or die(mysqli_error());
		#$domain_pages = mysqli_fetch_assoc($resDomainData);
		
		#if($text == '') {   
		##	$text = convertUmlaute($dataTextHTML["content_de"]); 
		#} 
		
		if($titel == '') { 
			$titel = convertUmlaute($dataTextHTML["title_de"]); 
		}
		
		$query = "SELECT * FROM shop_shippment WHERE domain_id='".$_SESSION['domain_id']."'";
		$resShippmentList = DBi::$conn->query($query) or die(mysqli_error());
		$bFound = false;
		 #echo "IN".$query;
		while($shippment = mysqli_fetch_assoc($resShippmentList)) {
			$html .= '<h2>'.$shippment['name_de'].'</h2>';
			$bFound = true;
			#echo $html.'abc';
			$query = "SELECT * FROM shop_shippment_detail WHERE shop_shippment_id='".$shippment['shop_shippment_id']."'";
			$resShippmentList2 = DBi::$conn->query($query) or die(mysqli_error());		
			while($shippment_sub = mysqli_fetch_assoc($resShippmentList2)) {		
					$html .= '<div id="shippment_item_'.$shippment_sub['shop_shippment_detail_id'].'">';
						$html .= $shippment_sub['gewicht_von'].' KG ';
						$html .= $shippment_sub['gewicht_bis'].' KG ';
						$html .= $shippment_sub['versandkosten'].' EUR';			
					$html .= '</div>';
					$html .= '<br/><br/>';
				}
				
			
		}
		if($bFound == false) {
			$html .= 'Keine Versandkosten hinterlegt.';
		}
		$text .= $html;
		$text .= '</div>';
	
		$result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);
	
	  return $result;
 } 
 ?>