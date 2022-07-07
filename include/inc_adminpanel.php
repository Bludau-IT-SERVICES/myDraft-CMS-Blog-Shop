<div class="ACP">
	<h2>Adminbereich</h2><span id="help_show">Hilfe einblenden</span>
	<div id="hilfe_adminpanel_general">
	Sie können hier Ihren Online Shop verwalten. Durch einen Doppelklick auf einen Textbereich (TEXT HTML) Ihrer Seite können Sie die Inhalte verändern.<br/>
	Legen Sie eine neue Seite an und fügen dort Module hinzu. 
	</div>
	<script>
	$("#help_show").click(function(){
		$("#hilfe_adminpanel_general").toggle();
	});
	</script>
	<?php 
		#error_reporting(E_ALL);
		#ini_set('display_errors', 1);
 
	?>
	<div id="optSelectBox" style="float:left">
	<?php 
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
		echo $html;
	?>
		
	</div>
	<div id="acp_main_functions">		
		<button class="modalInput" rel="#newmodul">Neues Seitenmodul</button>
		<button id="acp_edit_page" class="newpage" rel="#editpage">Seite bearbeiten</button>
		<button id="acp_new_page" class="newpage" rel="#newpage">Seite neu anlegen</button>
		<?php 
		if($domain_res['bIsShop'] == 'Y') {
		?>
		<button id="acp_shop_category" class="newpage" rel="#newpage">Shop Kategorien anlegen</button>
		<button id="acp_shop_category_portal" class="newpage" rel="#newpage">Portal Kategorien anlegen</button>
		<button id="acp_shop_products" class="newpage" rel="#newpage">Shop Produkt anlegen</button>
		<button id="acp_shop_products_list" class="newpage" rel="#newpage">Shop Produktverwaltung</button>
		<button id="acp_shop_orders" class="newpage" rel="#newpage">Shop Bestellungen</button>
		<button id="acp_shop_shipping" class="newpage" rel="#newpage">Shop Versandkosten</button>
		<button id="acp_shop_attribute" class="newpage" rel="#newpage">Shop Attribute</button>
		<?php 
		}
		?>
		<button id="acp_domain_page" class="newpage" rel="#newpage">Domain Einstellungen</button>
		<button id="acp_page_delete" onClick="set_page_delete('<?php echo $_GET['page_id']; ?>')" class="newpage" rel="#newpage">Seite l&ouml;schen</button>
		<a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, "utf-8"); ?>?modus=logout">Logout <?php echo $_SESSION['username']; ?></a> 
		<div class="acp_main">
			<div id="result-module-add"></div>
		</div>
	</div>
</div>