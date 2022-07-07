<?php 

ini_set('display_errors', 1); ini_set('html_errors', 1);


require_once('ogdbDistance.lib.php');

echo '<pre>';
// Example PLZ.tab or DE.tab
var_dump('Entfernung: '.ogdbDistance(47443,47058)."\n");
// Example DE.tab
var_dump("Umkreis:\n".var_export(ogdbRadius(26121,20),TRUE));
// Example CH.tab
//var_dump('Entfernung: '.ogdbDistance(5430,5400)."\n");
//var_dump("Umkreis:\n".var_export(ogdbRadius(5430,20),TRUE));
echo '</pre>';
