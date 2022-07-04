<?php 
####################################
# >> LoadModul_rss_content_popular Modul 
####################################
function LoadModul_rss_content_popular($config) {

	#print_r($config);
	#echo "O";
	$strText = '<div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'"><ul class="modul_menue_normal">';
	
	$dataTextHTML = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_rss_content_popular WHERE id=".$config['modul_id']));
	
	if($dataTextHTML['bLoadAjax'] == 'N') {		
		#echo "OK";
		include('mod_rss_popular.php');
		#echo $strText;
	} else {
		# AJAX LOADEN
		# ===============
		echo "<script>
	$(document).ready(function() {	 	
		load_rss_popular('".$config['typ']."','".$config['modul_id']."','".$dataTextHTML['typ']."');
	});
</script>";		
		#$strText .= "<span id=\"box_".$config['typ']."_".$config['modul_id'].'\">';
		#$strText .= "</span>";
	}
	$strText .= '</ul></div>'; 
	
	$result = array("title"=>$strTitel,"content"=>$strText,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	return $result;
} 
?>