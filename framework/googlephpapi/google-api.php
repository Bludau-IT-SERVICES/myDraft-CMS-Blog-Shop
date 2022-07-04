<?php
session_start();
include_once("../../include/inc_config-data.php");
include_once("../../include/inc_basic-functions.php");

$_GET = mysql_real_escape_array($_GET);
$query = "SELECT count(*) as anzahl FROM api_googleplus WHERE person_id='".$_GET['person_id']."'";
$resAnzahl = mysql_query($query) or die(mysql_error());
$strToken  = mysql_fetch_assoc($resAnzahl);

//print_r($strToken);
//print_r($_GET);
if($strToken['anzahl'] == 0) {
	$query = "INSERT INTO api_googleplus(access_token,code,person_id) VALUES('".$_GET['storeToken']."','".$_GET['code']."','".$_GET['person_id']."')";
	mysql_query($query) or die(mysql_error());
} else {
	
	if(isset($_GET['storeToken'])) {
		$query = "UPDATE api_googleplus SET access_token='".$_GET['storeToken']."',code='".$_GET['code']."' ,person_id= '".$_GET['person_id']."' WHERE person_id='".$_GET['person_id']."'";
		mysql_query($query) or die(mysql_error());
	}
	
	$query = "UPDATE api_googleplus SET name='".$_GET['name']."',geburtstag='".$_GET['birthday']."' ,gender= '".$_GET['gender']."' ,url= '".$_GET['url']."' ,hasApp= '".$_GET['hasApp']."' ,aboutMe= '".$_GET['aboutMe']."' ,relationshipStatus= '".$_GET['relationshipStatus']."' ,verified= '".$_GET['verified']."' ,circledByCount= '".$_GET['circledByCount']."' ,plusOneCount= '".$_GET['plusOneCount']."' ,isPlusUser= '".$_GET['isPlusUser']."' ,objectType= '".$_GET['objectType']."' ,email= '".$_GET['email']."' ,vorname= '".$_GET['vorname']."' ,nachname= '".$_GET['nachname']."',displayName= '".$_GET['displayName']."',imageurl= '".$_GET['imageurl']."',currentLocation= '".$_GET['currentLocation']."',language='".$_GET['language']."',ageRange_min='".$_GET['ageRange_min']."',ageRange_max='".$_GET['ageRange_max']."',nickname='".$_GET['nickname']."',tagline='".$_GET['tagline']."',email_type='".$_GET['email_type']."' WHERE person_id='".$_GET['person_id']."'";
	mysql_query($query) or die(mysql_error());
	
}
echo '<h3>Bitte mit der Registrierung fortfahren.</h3><br/><br/>';
?>