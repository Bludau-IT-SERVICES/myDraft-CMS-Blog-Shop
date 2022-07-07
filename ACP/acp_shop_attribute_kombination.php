<?php
#echo "IN................"; 
	@session_start();
	
	if(isset($_SESSION['shop_item_id'])) {
		$_POST['shop_item_id'] = $_SESSION['shop_item_id'];
	}
	// Datenbankverbindung
	require_once('../include/inc_config-data.php');
	require_once('../include/inc_basic-functions.php');
	
	$_POST = mysql_real_escape_array($_POST);
	$_GET = mysql_real_escape_array($_GET);
	
	// Login überprüfen
	$chkCookie = admin_cookie_check();

	#echo $chkCookie;
	if($_SESSION['login'] == 1) {		
		$_SESSION['login'] = 1;
	} else {
		exit(0);
	}
	
function showCombinations($string, $traits, $i)
{
    if ($i >= count($traits))
        echo trim($string) . "<br/>";
    else
    {
        foreach ($traits[$i] as $trait)
            showCombinations("$string $trait", $traits, $i + 1);
    }
}

 
function pc_next_permutation($p, $size) {
    // slide down the array looking for where we're smaller than the nextguy
    for ($i = $size - 1; $p[$i] >= $p[$i+1]; --$i) { }
    // if this doesn't occur, we've finished our permutations
    // the array is reversed: (1, 2, 3, 4) => (4, 3, 2, 1)
      if ($i == -1) { return false; }
      // slide down the array looking for a bigger number than what we foundbefore
      for ($j = $size; $p[$j] <= $p[$i]; --$j) { }
      // swap them
      $tmp = $p[$i]; $p[$i] = $p[$j]; $p[$j] = $tmp;
      // now reverse the elements in between by swapping the ends
      for (++$i, $j = $size; $i < $j; ++$i, --$j) {
            $tmp = $p[$i]; $p[$i] = $p[$j]; $p[$j] = $tmp;
      }
      return $p;
}

function combos($data, &$all = array(), $group = array(), $val = null, $i = 0) {
	if (isset($val)) {
		array_push($group, $val);
	}
 
	if ($i >= count($data)) {
		array_push($all, $group);
	}
	else {
		foreach ($data[$i] as $v) {
			combos($data, $all, $group, $v, $i + 1);
		}
	}
	return $all;
}
 if($_POST['modus'] == 'generate_kombinations') {
   #print_r($_POST);
   
   $query = "SELECT * FROM shop_attribute WHERE attribute_set_id='".$_POST['attributset_id']."'";
   #echo $query;
   $resAttritbute = DBi::$conn->query($query) or die(mysqli_error());
   $ary[] = array();
   $iCountAttribute = 0;
   while($strAttribute = mysqli_fetch_assoc($resAttritbute)) {
	   $ary[$iCountAttribute] = array();
	   #echo $iCountAttribute.'attribute-';
	   
	   # ATRIBUT VORHANDEN
	   $query = "SELECT count(*) as anzahl FROM shop_item_eigenschaft WHERE shop_attribut_id='".$strAttribute['attribute_id']."' AND id_shop_item='".$_POST['shop_item_id']."'";
	   $strAttributeFieldCount = mysqli_fetch_assoc(DBi::$conn->query($query));
	   if($strAttributeFieldCount['anzahl'] == 0) {
	   
			#ATTRIBUTE ABRUFEN (NAMEN)
			$query = "SELECT * FROM shop_attribute WHERE attribute_id='".$strAttribute['attribute_id']."'";
			$resAttribValue = DBi::$conn->query($query) or die(mysqli_error());
			$strAttributeField = mysqli_fetch_assoc($resAttribValue);
			
			# ATTRIBUTE INSERT
			$query = "INSERT INTO shop_item_eigenschaft(id_shop_item,eigenschaft_name_de,typ,shop_attribut_id) VALUES('".$_POST['shop_item_id']."','".$strAttributeField['name_de']."','select','".$strAttribute['attribute_id']."');";
			DBi::$conn->query($query) or die(mysqli_error());
			
			$strAttributID = mysqli_insert_id(DBi::$conn);
	   } else {
			$query = "SELECT * FROM shop_item_eigenschaft WHERE shop_attribut_id='".$strAttribute['attribute_id']."' AND id_shop_item='".$_POST['shop_item_id']."'";
			$strAttributeField = mysqli_fetch_assoc(DBi::$conn->query($query));
			
			$strAttributID = $strAttributeField['shop_item_eigenschaft_id'];
	   }
	   
	   for ($zi = 0;$zi < count($_POST['shop_attribute_'.$strAttribute['attribute_id']]); $zi++){
			$wert = $wert.$_POST['shop_attribute_'.$strAttribute['attribute_id']][$zi].",";
			$ary[$iCountAttribute][$zi] = array();
			$ary[$iCountAttribute][$zi] =$_POST['shop_attribute_'.$strAttribute['attribute_id']][$zi];
			
			# ATTRIBUTE FELD WERT VORHANDEN
			$query = "SELECT count(*) as anzahl FROM shop_item_eigenschaftwert WHERE shop_attribut_value_id='".$_POST['shop_attribute_'.$strAttribute['attribute_id']][$zi]."' AND id_item_shop='".$_POST['shop_item_id']."'";
			$strAttributeFieldCountWert = mysqli_fetch_assoc(DBi::$conn->query($query));
			if($strAttributeFieldCountWert['anzahl'] == 0) {
				#ATTRIBUTE FELD WERT ABRUFEN (NAMEN)
				$query = "SELECT * FROM shop_attribute_value WHERE shop_attribute_value_id='".$_POST['shop_attribute_'.$strAttribute['attribute_id']][$zi]."'";
				$resAttribValue = DBi::$conn->query($query) or die(mysqli_error());
				$strAttributeFieldWert = mysqli_fetch_assoc($resAttribValue);
				
				# ATTRIBUTE WERT INSERT
				$query = "INSERT INTO shop_item_eigenschaftwert(id_shop_item_eigenschaft,name_de,aufpreis_de,lagerbestand,shop_attribut_value_id,id_item_shop) VALUES('".$strAttributID."','".$strAttributeFieldWert['value_de']."','0.0','100','".$strAttributeFieldWert['shop_attribute_value_id']."','".$_POST['shop_item_id']."');";
				DBi::$conn->query($query) or die(mysqli_error()); 
				
			}
			# ATTRIBUTE FELD WERT 
			
	   } 	   
	   $iCountAttribute++;
	}
	
#$permutations = array_permutation($ary);
echo "<pre>";
print_r($ary);
echo "</pre>";
echo "OK";
$combinations = combos($ary);

$query = "SELECT * FROM shop_item WHERE shop_item_id='".$_POST['shop_item_id']."'";
$resItemFound = DBi::$conn->query($query) or die(mysqli_error());
$strItemData = mysqli_fetch_assoc($resItemFound);
	
for($i=0; $i < count($combinations); $i++) {
	for($z=0; $z < count($combinations[$i]);$z++) {
		$query = "SELECT * FROM shop_attribute_value WHERE shop_attribute_value_id='".$combinations[$i][$z]."'";
		$resAttribValue = DBi::$conn->query($query) or die(mysqli_error());
		$strKombi = mysqli_fetch_assoc($resAttribValue);
		if($z == count($combinations[$i]) -1) {
			$strTrenner = '';
		} else {
			$strTrenner = '-';
		}
		
		$Kombi .= $strKombi['value_de'].$strTrenner;		
	}
	$query = "SELECT count(*) as anzahl FROM shop_item WHERE item_number='".$Kombi."'";
	$resItemFound = DBi::$conn->query($query) or die(mysqli_error());
	$strItemFound = mysqli_fetch_assoc($resItemFound);
	if($strItemFound['anzahl'] == 0) {
		$query = "INSERT INTO `shop_item` (`name_de`, `preis`, `shop_cat_id`, `menue_id`,menge,beschreibung,gewicht,item_number,parrent_shop_item_id,domain_id) VALUES ('".$strItemData['name_de']."', '".$strItemData['preis']."', '".$strItemData['shop_cat_id']."', '".$strItemData['menue_id']."', '".$strItemData['menge']."', '".$strItemData['beschreibung']."', '".$strItemData['gewicht']."','".$strItemData['item_number'].'-'.$Kombi."','".$_POST['shop_item_id']."','".$_SESSION['domain_id']."');";
		$resInsert = DBi::$conn->query($query) or die(mysqli_error());
	}
	$gesKombi .= $Kombi;
	echo $strItemData['item_number'].'-'.$Kombi.'<br/>';
	$Kombi ='';
}
/*$set = array
(
    array('red', 'blue', 'green'),
    array('small', 'medium'),
    array('car', 'truck', 'van')
);
$set = $ary;
$size = count($set) - 1;
$perm = range(0, $size);
$j = 0;
do {
      foreach ($perm as $i) { $perms[$j][] = $set[$i]; }
} while ($perm = pc_next_permutation($perm, $size) and ++$j);
foreach ($perms as $p) {
showCombinations('', $p, 0);

} */

echo "<pre>";
print_r($permutations);
echo "</pre>";
   /*for($i=0; $i < count($ary); $i++) {
		
		for($z=0; $z < count($ary[$i]);$z++) {
			$html .= $ary[$i][$z];
			for($y=0; $y < count($ary); $y++) {
				for($c=0; $c < count($ary[$i]);$c++) {
					$html .= $ary[$y][$c].'-';		
				}
			}
			$html .='<br/>';
		}
   }*/
   echo $html;
   echo print_r($ary);
   exit;
 } elseif($_POST['modus'] == 'delete_attribute_combinations') {
	$query = "DELETE FROM `shop_item_eigenschaft` WHERE id_shop_item='".$_POST['shop_item_id']."'";
	$resInsert = DBi::$conn->query($query) or die(mysqli_error());
	
	$query = "DELETE FROM `shop_item_eigenschaftwert` WHERE id_item_shop='".$_POST['shop_item_id']."'";
	$resInsert = DBi::$conn->query($query) or die(mysqli_error());

	$query = "DELETE FROM `shop_item` WHERE parrent_shop_item_id='".$_POST['shop_item_id']."'";
	$resInsert = DBi::$conn->query($query) or die(mysqli_error());	
	
	
	echo "Alle Kombinationen entfernt, bitte neue bei Bedarf anlegen.";
	exit;
}
	$query = "SELECT * FROM shop_attribute_set WHERE domain_id='".$_SESSION['domain_id']."'";
$resAttributSet = DBi::$conn->query($query) or die(mysqli_error()); 
$html .= '<select id="shop_attributset" name="shop_attributset" size="6">'; 
/*$html .= '<optgroup label="Kulturelle Feste">';
	$html .= '<option value="-1">Keine Auswahl</option>'; 
$html .= '</optgroup>';
$html .= '<optgroup label="Saisonale Veranstaltungen">';
	$html .= '<option value="-1">Keine Auswahl</option>'; 
$html .= '</optgroup>';
$html .= '<option value="-1">Keine Auswahl</option>'; */
while($strSet = mysqli_fetch_assoc($resAttributSet)) {
	if($_POST['attribut_set'] == $strSet['shop_attribute_id']) {
		$selected = "selected=true";
	} else {
		$selected = "";
	}
	#echo $strSet['set_name_de'];
	$html .= '<option value="'.$strSet['shop_attribute_id'].'" '.$selected.'>'.$strSet['set_name_de'].'</option>'; 
}
$html .= '</select>'; 
if($_POST['modus'] == 'list_attribute') {

	$query = "SELECT count(*) as anzahl FROM shop_attribute WHERE attribute_set_id='".$_POST['attribut_set']."' AND domain_id='". $_SESSION['domain_id']."'";
	$resAttributSet = DBi::$conn->query($query) or die(mysqli_error()); 
	$iAttribute_anzahl = mysqli_fetch_assoc($resAttributSet); 
	
	#for($i=0; $i < $iAttribute_anzahl; $i++) {
	$query = "SELECT * FROM shop_attribute WHERE attribute_set_id='".$_POST['attribut_set']."'";
	$resAttributSet = DBi::$conn->query($query) or die(mysqli_error()); 
	
	while($strAttribute = mysqli_fetch_assoc($resAttributSet)) {
		$html .= '<select id="shop_attribute_'.$strAttribute['attribute_id'].'" name="shop_attribute_'.$strAttribute['attribute_id'].'[]" size="10" multiple>'; 
		$query = "SELECT * FROM shop_attribute_value WHERE shop_attribute_id='".$strAttribute['attribute_id']."'";
		$resAttributValue2 = DBi::$conn->query($query) or die(mysqli_error());
		while($strAttributValue = mysqli_fetch_assoc($resAttributValue2)) {
			$html .= '<option value="'.$strAttributValue['shop_attribute_value_id'].'">'.$strAttributValue['value_de'].'</option>'; 
		}
		$html .= '</select>'; 
	}
}
?> 
<div id="shop_attribut_attribute" class="shop_attribut_attribute">
<form name="shop_attribut_generator" id="shop_attribut_generator" action="/ACP/acp_shop_attribute_kombination.php" method="POST" onSubmit="return shop_attribute_generate('shop_attribut_generator');">
<?php 
echo $html;
?>
<input type="hidden" name="modus" value="generate_kombinations"/>
<input type="hidden" name="attributset_id" value="<?php echo $_POST['attribut_set']; ?>"/>
<input type="hidden" name="shop_item_id" value="<?php echo $_POST['shop_item_id']; ?>"/>
<input type="submit" name="btnGenerate" value="Kombinationen generieren"/>
</div>
<div id="shop_attribut_kombination" class="shop_attribut_kombination">
</div>
</form>

<script>
   $('#shop_attributset').on("change",function(){
    var option = $("option:selected",this).val();
	//alert(option);
	var postData = "modus=list_attribute&attribut_set=" + option;
    var formURL = '/ACP/acp_shop_attribute_kombination.php';
	$.ajax(
    {
        url : formURL,
        type: "POST",
        data : postData,
        success:function(data, textStatus, jqXHR)
        {
			$("#shop_attribut_attribute").html(data);
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });
	return false;
    //console.log(option);
	});
</script>