<?php 
function getFirmenEintragSuche($suchanfrage) {
	
	if(is_numeric($suchanfrage) == true) {
		$ErgQuery = DBi::$conn->query("SELECT * FROM firma_shopinfo WHERE plz LIKE '".$suchanfrage."%'");
	} else {
		$ErgQuery = DBi::$conn->query("SELECT * FROM firma_shopinfo WHERE stadt LIKE '".$suchanfrage."%' or  firma LIKE '".$suchanfrage."%'");
	}
	$iCount = 0;
	while($dataFirma = mysqli_fetch_array($ErgQuery)) {
		if($iCount == 1) {
			$strHTML .='<br/><br/><hr/>';
		} 
		$iCount++;
		$strHTML .= '<h1 id="headercms"><a href="/firma/'.$dataFirma['firma'].'/">'.$dataFirma['firma'].'</a></h1>';
		$strHTML .= 'aus '.$dataFirma['plz'].' '.$dataFirma['stadt'].'<br/><br/>';
		$strHTML .= '<strong>Beschreibung</strong><br/>';
		$strHTML .= $dataFirma['shop_beschreibung'].'<br/><br/>';
		$strHTML .= '<strong><a href="/firma/'.$dataFirma['firma'].'/#kontaktanfrage" class="button">Anfrage senden</a></strong><br/>';
	}
	if($iCount == 1) {
		$strHTML .= '<br/>';
	}
	
	return $strHTML;
}	


####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_portal_suche($config) {

		$dataTextHTML = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_portal_suche WHERE id=".$config['modul_id']));
		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='portal_suche'"));
		#echo "IN";
		
		$dataTextHTML['typ'] = 'portal_suche';
		
		$text = '<a name="ergebnis"/></a><div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
		
		$text .= convertUmlaute($dataTextHTML["content_".$_SESSION['language']]);
		$titel = convertUmlaute($dataTextHTML["title_".$_SESSION['language']]);
		
			$query = "SELECT * from domains WHERE domain_id='".$_SESSION['domain_id']."'";			
			$resDomainData = DBi::$conn->query($query) or die(mysqli_error());
			$domain_pages = mysqli_fetch_assoc($resDomainData);
		
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
		if($_POST['plzsearch_input-cms'] != '') {
			$_GET['q'] = $_POST['plzsearch_input-cms'];
		}
		if($_POST['plzsearch_input'] != '') {
			$_GET['q'] = $_POST['plzsearch_input'];
		}
		
		
				$text .= '<div id="plzsearch_wrapper-cms" style="background-color: #f0f0f0;">
						<div id="plz-search-cms"> 
							<form class="searchform" name="plzsearch-cms" method="POST"  action="/suche/#ergebnis">
								<div class="plz-input-cms">
									<input type="text" name="plzsearch_input-cms" value="'.$_GET['q'].'" id="plzsearch_input-cms" autocomplete="off">
									<span class="placeholder" id="plzsearch_placeholder" style="display: block;"></span>                 
								</div>
								<div style="display:none">
									<input type="submit" name="btnSucheSenden2"/>
								</div>
								<!-- <div style="padding:0; margin:0; float:left;">
									<a class="newbtndeactive" href="#" id="plzsearch_submit">Lieferservice suchen</a>
								</div> -->
							</form>						
						</div>
						</div><br/><br/><br/>';
		
		$strSuchergebnis = getFirmenEintragSuche($_GET['q']);
		if($strSuchergebnis == '') {
			$text .= 'Es gab keine Suchtreffer f&uuml;r die Anfrage "'.$_GET['q'].'"<br/><br/>Sie können sich jederzeit <a href="http://www.schlemmertal.de/de/2/Firmeneintrag/">auf Schlemmertal eine kostenlose Anmeldung</a> Ihrer Firma vornehmen.<br/>';
		}
		$text .= $strSuchergebnis;
		$text .= '<div style="clear:both"></div><br/></div>';
	  
		$result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

		return $result;
 } 
 ?>