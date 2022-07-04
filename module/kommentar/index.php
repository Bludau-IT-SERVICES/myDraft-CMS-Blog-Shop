<?php 
@session_start();
 
###########################################
# >> Menüfunktion 
###########################################
function comment_generator($parent, $level,$html,$openli,$opencon,$typ='menue',$page_id) { 
	 
	# Alle Kinder des Menüpunkts abrufen
	$query = "SELECT * FROM modul_kommentar_content LEFT JOIN modul_kommentar_parent ON modul_kommentar_parent.kommentar_id=modul_kommentar_content.id	WHERE modul_kommentar_parent.kommentar_parent=$parent AND modul_kommentar_content.menue_id='".$page_id."'";   
	# echo $query;
	/*if($_SESSION['login'] == 1) {
		
		//SELECT * FROM modul_kommentar_parent LEFT JOIN modul_kommentar_content ON modul_kommentar_parent.kommentar_id=modul_kommentar_content.id	WHERE modul_kommentar_parent.modul_kommentar_parent_id=0
		echo $query;
		
	}*/
	
	$result = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));
 
	
	while ($row = mysqli_fetch_assoc($result)) {
		#$content = @mysql_num_rows(DBi::$conn->query("SELECT * FROM module_in_menue WHERE menue_id=$row[id]"));
		 
		# Kinder abrufen
		#$query = "SELECT * FROM menue_parent WHERE parent_id=".$row['id'];
		#$query = "SELECT * FROM menue_parent LEFT JOIN menue ON menue_parent.menue_id=menue.id	WHERE menue_parent.parent_id=".$row['id']." AND domain_id=".$_SESSION['domain_id']." ".$extSichtbar." ORDER BY sortierung ASC"
		
		// JB V
		//$query = "SELECT * FROM modul_kommentar_parent LEFT JOIN modul_kommentar_content ON modul_kommentar_parent.menue_id=modul_kommentar_content.id	WHERE modul_kommentar_parent.kommentar_parent=".$row['menue_id']."";   
		
		$query = "SELECT * FROM modul_kommentar_parent LEFT JOIN modul_kommentar_content ON modul_kommentar_parent.menue_id=modul_kommentar_content.id	WHERE modul_kommentar_parent.kommentar_parent=".$row['kommentar_id']."";   
		/* if($_SESSION['login'] == 1) {
			 echo $query;
		 }*/
		$resMenuParent = DBi::$conn->query($query) or die(mysql_error());	
		$kinder[$row['id']] = @DBi::mysql_num_row($resMenuParent);
		#$id = $row['id'];
		
	/*if($_SESSION['login'] == 1) {
		echo '<pre>';
		print_r($row);
		echo '</pre>';
	}*/
		
		$text = '<strong>'.$row['title_de'].'<strong><br/>'.$row['content_de'].'<a onClick="kommentar_antworten(\''.$row['kommentar_id'].'\')">
		Antworten</a>';
		$text .= "<div id=\"kommentar_antworten_".$row['kommentar_id']."\" style=\"display:none\">
		<form action=\"#\" onsubmit=\"return setKommentar('setKommentar_".$row['kommentar_id']."','".$config['typ']."','".$config['modul_id']."','".$row['kommentar_id']."');\" id=\"setKommentar_".$row['kommentar_id']."\" name=\"setKommentar_".$row['kommentar_id']."\"><br/><input type=\"text\" id=\"txtEmail_".$row['kommentar_id']."\" name=\"txtEmail_".$row['kommentar_id']."\" placeholder=\"Bitte Email eingeben\"> <br/><input type=\"text\" id=\"txtTitle_".$row['kommentar_id']."\" name=\"txtTitle_".$row['kommentar_id']."\" placeholder=\"Bitte Titel eingeben...\"> <br/>
		<textarea id=\"txtKommentar_".$row['kommentar_id']."\" name=\"txtKommentar_".$row['kommentar_id']."\" rows=\"4\" cols=\"50\"></textarea><br/>
			<input type=\"hidden\" id=\"txtKommentar_id_".$row['kommentar_id']."\" name=\"txtKommentar_id_".$row['kommentar_id']."\" value=\"".$row['kommentar_id']."\"><input type=\"hidden\" id=\"txtMenue_".$row['kommentar_id']."\" name=\"txtMenue_".$row['kommentar_id']."\" value=\"".$_SESSION['page_id']."\"><input type=\"submit\" name=\"btnSenden\" value=\"Kommentieren\" class=\"button\"></form>
		</div>";	
		  
		 //$html .= $text; 
		if($typ == 'menue') {
			$openli++;
			if($level == '0') { 
				$html .= '      <li id="kommentaritem_'.$row['kommentar_id'].'">'.$text;  	
			}
			
			if($level != '0') { 
				$html .= '      <li id="kommentaritem_'.$row['kommentar_id'].'">'.$text;	 
			}
			
			if($kinder[$row['id']] > 0) { //4
				$opencon++;					
				$html .= '    <ul class="mod_kommentar">'."\n"; 										
			} //4
			
			$html = comment_generator($row['kommentar_id'], $level+1,$html,$openli,$opencon,$typ,$page_id);  

			if($openli > $level) { 
				$openli--;
			}	   
			
			if($opencon > $level) { 
				$html .= '    </ul>'."\n"; 															
				$opencon--;
			} 
			if($openli == $level && $opencon ==$level) { 
				$html .= '</li>'."\n"; 		 
			} 
		} else if($typ == 'select') {
			# jb de-en
			#echo "IN";
 
			#echo $level.'--'.$space.$row['name_de'];
			
			switch($level) {
				case '0':
					$space ='';
					break;
				case '1':
					$space ='...';
					break;					
				case '2':
					$space ='......';
					break;							
				case '3':
					$space ='.........';
					break;
				case '4':
					$space ='............';					
					break;
				case '5':
					$space ='...............';
					break;
				case '6':
					$space ='..................';
					break;
				default: 
					$space ='..................';
					break;								
			}
			if($page_id == $row['menue_id']) {
				$html .= '<option value="'.$row['menue_id'].'" selected=true>'.$space.$row['name_de'].'</option>';			
			} else {
				$html .= '<option value="'.$row['menue_id'].'">'.$space.$row['name_de'].'</option>';
			}
			$html = comment_generator($row['kommentar_id'], $level+1,$html,$openli,$opencon,$typ,$page_id);  
		}

   } # END WHILE MENU GENERIEREN 

   return $html;
} 

####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_kommentar($config) {

	
		$query = "SELECT * FROM modul_kommentar WHERE id='".$config['modul_id']."'";
		$resKommentar = DBi::$conn->query($query);
		$dataTextHTML = mysqli_fetch_array($resKommentar);
		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='kommentar'"));
		#echo "IN";
		#print_r($dataTextHTML);
		$dataTextHTML['typ'] = 'kommentar';
		
		$text = '<div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
		
		$text .= convertUmlaute($dataTextHTML["content_".$_SESSION['language']]);
		$titel = convertUmlaute($dataTextHTML["title_".$_SESSION['language']]);
		

		
		if($text == '') {   
			$text .= convertUmlaute($dataTextHTML["content_de"]); 
		} 
		#echo $dataTextHTML["id"];
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

		// Prototyp   
		// if (@$_SESSION['login'] == '1')  {
			$text .= "<form action=\"#\" onsubmit=\"return setKommentar('setKommentar','".$config['typ']."','".$config['modul_id']."','0');\" id=\"setKommentar\" name=\"frmKommentar\"><br/><input type=\"text\" id=\"txtEmail\" name=\"txtEmail\" placeholder=\"Bitte Email eingeben...\"><br/><input type=\"text\" id=\"txtTitle\" name=\"txtTitle\" placeholder=\"Bitte Titel eingeben...\"> <br/><br/>
		<textarea id=\"txtKommentar\" name=\"txtKommentar\" placeholder=\"Bitte Kommentar eingeben...\" rows=\"4\" cols=\"50\"></textarea><br/>
			<input type=\"hidden\" id=\"txtMenue\" name=\"txtMenue\" value=\"".$_SESSION['page_id']."\"><input type=\"submit\" name=\"btnSenden\" value=\"Kommentieren\" class=\"button\"></form>";
			$inhalt = comment_generator(0, 0,$html,0,0,'menue',$_SESSION['page_id']);
			$text .= $inhalt; 
			
		/*} else {
				$text .='In der Entwicklung..';
		}*/
		 
		$text .= '</div>'; // config modus 

			if(empty($titel)) {
		$titel = "Kommentar abgeben";
	}
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ'],"box_design"=>"plain");

	  return $result;
 } 
 ?>