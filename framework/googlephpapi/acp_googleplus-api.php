<?php
include_once("/include/inc_config-data.php");
include_once("/../../include/inc_config-data.php");

$query = "SELECT count(*) as anzahl FROM api_googleplus WHERE access_token='".$_POST['storeToken']."'";
$resAnzahl = mysql_query($query) or die(mysql_error());
$strToken  = mysql_fetch_assoc($resAnzahl);

print_r($strToken);

?>