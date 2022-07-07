<?php 
session_start();

require_once('../include/inc_config-data.php');
require_once('../include/inc_buildbox.php');
require_once('../include/inc_basic-functions.php');
#include_once('../include/inc_pagging.php');
$_POST = mysql_real_escape_array($_POST);
$_GET = mysql_real_escape_array($_GET);
 
#################################
# >> Abfrage der vorhandenen Module auf der Seite 
#################################
function getModuleContent($id,$name,$dataposted) {
	$query = "SELECT * FROM module_in_menue WHERE modul_id='".$id."' AND typ='".$name."' ORDER BY position asc";
 	$qry = DBi::$conn->query($query) or die(mysqli_error());

	if(empty($page_id)) {
		$page_id = '';
	}
	#######################################		
	# >> Abrufen der Moduldaten 
	#######################################
	while($res = mysqli_fetch_assoc($qry)) {


			$conf['typ']   	= $res["typ"];
			$conf['modul_id'] 	= $res["modul_id"];
			
			$conf['design_box'] 	= $res['container']; // Positionierung auf der Seite 		
			$conf['width'] 	= '100%';
			$conf['design'] = 'box';			
			$conf['pid']	= $page_id;
			$conf['status'] = $dataposted['status'];
			#$html .=  $res["typ"];
			#$conf = isModul_class($conf);
			
			$html .= setBuildBox($conf);
	} # END WHILE CONTAINER  
	
	return $html; 
}

echo getModuleContent($_GET['module_id'],$_GET['module_name'],$_GET);
?>