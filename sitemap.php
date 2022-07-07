<?php 
set_time_limit(0);
ini_set('memory_limit','1G');
error_reporting(E_ALL & ~E_NOTICE);
header('Content-type: application/xml; charset=utf-8');
session_start();
include_once('include/inc_config-data.php');
$_SESSION['aktuell'] = 0;
if(isset($argv[1])) {
	$_GET['type'] = $argv[1];
}

if(isset($argv[2])) {
	$_GET['startpoint'] = $argv[2];
} 

$GLOBALS["bExit"] = false;
$GLOBALS["xml_length"] = 0;
$GLOBALS["aktuell"] = 0;
# Sprachauswahl
$_SESSION['domainLanguage'] = 'de';
$_SESSION['language'] = 'de'; // menueconvert bug, warum nicht DomainLanguage

include_once('include/inc_basic-functions.php');
include_once('include/inc_buildbox.php');
 
switch($_GET['type']) {
	case 'seiten':
		
		# >> Funktionsparameter - 
		# $parent, $level,$html,$counter,$opencon,$beginn=0,$ende=10000
		$xml_size = 10000;
  
		for($i=$_GET['startpoint']; $i < (1090000 / $xml_size); $i++) {
			
			echo "\t ================================================\n";
			echo "\t -$i / ".(1090000 / $xml_size)." - Startgenarating... - \n";	
			echo "\t ================================================\n";
			$html = '<?xml version="1.0" encoding="UTF-8"?>
			<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		
			$html .=  sitemap_generator(0, $level,'',($i * $xml_size),($i * $xml_size),($i * $xml_size) +$xml_size ,false,0);
			$html .= '</urlset>';

			$handle = fopen ('./httpdocs/pages-'.$i.'.xml', "w");
			fwrite ($handle, $html);
			fclose ($handle);
			$GLOBALS["bExit"] = false;
			$GLOBALS["xml_length"] = 0;
			$GLOBALS["aktuell"] = 0;
		}
		#echo $html;
		# Datei schreiben
		#$gz = gzopen('sitemap-seiten.xml-'.$_GET['startpoint'].'.gz','w9');
		#gzwrite($gz, $html);
		#gzclose($gz);
		break;
}

###########################################
# >> Sitemap Generator 
###########################################
function sitemap_generator($parent, $level,$html,$aktuell,$beginn,$ende,$bExit,$xml_length) { 
	if ($GLOBALS["xml_length"] >= 10000) {
		return $html;
		exit;
	}
	
	/*
	if ($GLOBALS["bExit"] == true) {
		echo "\t -EXITCODE 0- \n";
		return $html;
		exit;
	}
	
	if($level >= 2) {
		echo "\t -EXITCODE 31337- \n";
		return $html;
		exit;
	}*/

	# Alle Kinder des Menüpunkts abrufen
	$query = "SELECT * FROM menue_parent LEFT JOIN menue ON menue_parent.menue_id=menue.id	WHERE menue_parent.parent_id=".$parent." AND menue.domain_id=1 ORDER BY menue.created_at DESC";   
	$result = DBi::$conn->query($query) or die(mysqli_error(DBi::$conn));; 
	
	echo "\t".$GLOBALS["aktuell"]."/ ".$ende."\n";
	
	while ($row = mysqli_fetch_assoc($result)) {
 
		$GLOBALS["aktuell"]++; 
		echo "\t Aktuell: ".$GLOBALS["aktuell"]." / XML-Size: ".$GLOBALS["xml_length"]." | Beginn: ".$beginn."/ Ende: ".$ende."\n";

		# Sitemap Datum generieren
		$datum = explode(" ",$row['updated_at']);
		$datum_explode = explode ("-",$datum[0]);
		$uhrzeit_explode = explode (":",$datum[1]);
		$timestamp = mktime($uhrzeit_explode[0], $uhrzeit_explode[1], 0, $datum_explode[2], $datum_explode[1], $datum_explode[0]);

		if ($GLOBALS["xml_length"] <= 10000 && $GLOBALS["aktuell"] >= $ende) {
			 	// <url>
				// <loc>http://www.sitemappro.com/examples/example3.html</loc>
				// <lastmod>2015-04-16T17:18:47+01:00</lastmod>
				// <changefreq>daily</changefreq>
				// <priority>0.5</priority>
				// </url>
				$html .= ' <url>
				<loc>https://freie-welt.eu/'.getPathUrl('de',$row['id']).'</loc>';  	
				$html .= '<lastmod>'.date("c",$timestamp).'</lastmod>';  	
				$html .= '<changefreq>daily</changefreq>';  	
				$html .= '<priority>0.5</priority> </url>';  
				$GLOBALS["xml_length"]++;
				echo "\t - ====================\n";	
				echo "\t - >> Generating XML...\n";	
				echo "\t - ====================\n";	
		} else {	
			#if($aktuell > 20) {
				
				if ($GLOBALS["xml_length"] < $ende && $GLOBALS["aktuell"] < $ende) {
					echo "\t - ===========================================\n";	
					echo "\t - >> XML-SIZE: ".$GLOBALS["xml_length"]."... \n";	
					echo "\t - ===========================================\n";		
					#return $html;
					#$html = sitemap_generator($row['menue_id'], $level+1,$html,$aktuell, $ende,$beginn,$bExit,$xml_length);  
				} else if($GLOBALS["xml_length"] >= $ende)  {			
					echo "\t -EXITCODE 33333 - \n";
					$GLOBALS["bExit"] = true;
					return $html;
					exit;
				} else {
					echo "\t - EXITCODE 1- \n";
					$GLOBALS["bExit"] = true;
					return $html;
					exit;
				}
		}

		$html = sitemap_generator($row['menue_id'], $level+1,$html,$aktuell, $beginn,$ende,$bExit,$xml_length);  

   } # END WHILE MENU GENERIEREN 
   return $html;
}  

mysqli_close(DBi::$conn); 
?>