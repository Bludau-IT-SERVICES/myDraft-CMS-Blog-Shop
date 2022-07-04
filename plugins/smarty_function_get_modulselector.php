<?php 
function get_modulselector($params) {
		if($domain_res['bIsShop'] == 'N') {
			$query = "SELECT * FROM benutzer JOIN benutzer_gruppe ON benutzer.profile_id = benutzer_gruppe.benutzer_gruppe_id JOIN module_installiert_profiles  ON module_installiert_profiles.profile_id = benutzer_gruppe.benutzer_gruppe_id JOIN module_installiert ON module_installiert.module_installed_id = module_installiert_profiles.module_installiert_id WHERE benutzer.id ='".$_SESSION['user_id']."'  AND isShop = '".$domain_res['bIsShop']."' AND module_installiert.status_aktiv = 'Y' ORDER BY module_installiert.sortierung ASC";
		} else {		
			$query = "SELECT * FROM benutzer JOIN benutzer_gruppe ON benutzer.profile_id = benutzer_gruppe.benutzer_gruppe_id JOIN module_installiert_profiles  ON module_installiert_profiles.profile_id = benutzer_gruppe.benutzer_gruppe_id JOIN module_installiert ON module_installiert.module_installed_id = module_installiert_profiles.module_installiert_id WHERE benutzer.id ='".$_SESSION['user_id']."' AND module_installiert.status_aktiv = 'Y' ORDER BY module_installiert.sortierung ASC";
		}
		$resModule = DBi::$conn->query($query) or die(mysqli_error());
		$html .= '<select id="optModul" name="optModul" size="1">';
		while($dataModules = mysqli_fetch_assoc($resModule)) {
		
			$html .= '<option value="'.$dataModules['typ'].'">'.$dataModules['name_de'].'</option>';
		
		}
		$html .= '</select>';
		return $html;
}
?>