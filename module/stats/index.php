<?php 
####################################
# >> LoadModul_rss_content_popular Modul 
####################################
function LoadModul_stats($config) {


	$strText = '<div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'"><ul class="modul_menue_normal">';
	
	$dataTextHTML = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_stats WHERE id=".$config['modul_id']));
	
	#print_r($dataTextHTML);
	if($dataTextHTML['bLoadAjax'] == 'N') {		
		#echo "OK";
		include('mod_stat.php');
		#echo $strText;
	} else {
		# AJAX LOADEN
		# ===============
		$strText .= "<script>
	$(document).ready(function() {	 	
		load_stats_popular('".$config['typ']."','".$config['modul_id']."','".$dataTextHTML['typ']."');
	});
</script>";		
		#$strText .= "<span id=\"box_".$config['typ']."_".$config['modul_id'].'\">';
		#$strText .= "</span>";
	}
	$strText .= '</ul></div>'; 
	
	$result = array("title"=>'<i class="fas fa-adjust"></i> '.$strTitel,"content"=>$strText,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	return $result;
} 
?>