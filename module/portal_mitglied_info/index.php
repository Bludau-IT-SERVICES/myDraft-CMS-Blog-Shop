<?php 


####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_portal_mitglied_info($config) {

		$dataTextHTML = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_portal_mitglied_info WHERE id=".$config['modul_id']));
		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='portal_mitglied_info'"));
		#echo "IN";
		
		$dataTextHTML['typ'] = 'portal_mitglied_info';
		
		$text = '<div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
		
		$text .= convertUmlaute($dataTextHTML["content_".$_SESSION['language']]);
		$titel = convertUmlaute($dataTextHTML["title_".$_SESSION['language']]);
		

		
		if($text == '') {   
			$text = convertUmlaute($dataTextHTML["content_de"]); 
		} 
		
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
		
		$query ="SELECT * FROM benutzer   JOIN domains ON benutzer.domain_id = domains.domain_id WHERE username='".$_GET['mitglied']."'";
		$resBenutzerInfo = DBi::$conn->query($query) or die(mysqli_error());
		$strBenutzer = mysqli_fetch_assoc($resBenutzerInfo);
		if($strBenutzer['name'] != '') {
			$text = '<h1>Shopste Mitglied '.$strBenutzer['username'].' </h1><br/>
		
		Hat einen eigenen <a href="http://'.$strBenutzer['name'].'">Online Shop</a> ';
		}
		###############
		# >> Eingelogt 
		###############
		
		if (@$_SESSION["login"] == '1')  { 			
			$text = '<div ondblclick="getTexthtmlEdit('.$dataTextHTML['id'].');" id="texthtml_'.$dataTextHTML['id'].'">'.$text.'</div>'; 
		} 
		
		$text .= '</div>'; // config modus 

		
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 } 
 ?>