<?php 

// Release: 2011-10-11

if ( version_compare(PHP_VERSION,'5.0.0','<') ) {
	die('ABBRUCH: es wird PHP5 oder hoeher benoetigt.\n'
		.' -> http://de.php.net/downloads.php\n');
}

define('OGDB_REMOTE_DATA_FILE','http://fa-technik.adfc.de/code/opengeodb/DE.tab'); // german data
//define('OGDB_REMOTE_DATA_FILE','http://fa-technik.adfc.de/code/opengeodb/PLZ.tab'); // only ogdbDistance supported, but faster ogdbDistance calculation
//define('OGDB_REMOTE_DATA_FILE','http://fa-technik.adfc.de/code/opengeodb/AT.tab'); // austrian data
//define('OGDB_REMOTE_DATA_FILE','http://fa-technik.adfc.de/code/opengeodb/CH.tab'); // swiss data

#define('OGDB_LOCAL_DATA_FILE','./'.basename(OGDB_REMOTE_DATA_FILE)); // local file cache
define('OGDB_LOCAL_DATA_FILE','./framework/ogdbDistance/DE.tab'); // local file cache

DEFINE('OGDB_EARTH_RADIUS',6371); // https://secure.wikimedia.org/wikipedia/de/wiki/Erdradius#Res.C3.BCmee

ini_set('precision', 49); // http://de2.php.net/manual/de/function.pi.php

function ogdbGetData() {
	if ( !is_file(OGDB_LOCAL_DATA_FILE) || filesize(OGDB_LOCAL_DATA_FILE)==0 ) {
		$fileData = file_get_contents(OGDB_REMOTE_DATA_FILE);
		if ( $fileData == FALSE ) {
			die('ABBRUCH: konnte Daten nicht laden ('.OGDB_REMOTE_DATA_FILE.")\n");
		}
		if ( file_put_contents(OGDB_LOCAL_DATA_FILE,$fileData) == FALSE ) {
			die('ABBRUCH: konnte Daten nicht speichern ('.OGDB_LOCAL_DATA_FILE.")\n");
		}
		unset($fileData);
	}
	$fileData = @file_get_contents(OGDB_LOCAL_DATA_FILE);
	if ( $fileData == FALSE ) {
		die('ABBRUCH: konnte Daten nicht laden ('.OGDB_LOCAL_DATA_FILE.")\n");
	}
	return $fileData;
}

function ogdbDataStructure($explodedRow) {
	$dataStructure = FALSE;
	if ( count($explodedRow) == 5 ) { // PLZ.tab
		$dataStructure = array('zip_pos' => 1, 'lon_pos' => 2, 'lat_pos' => 3);
	}
	if ( count($explodedRow) == 16 ) {
		$dataStructure = array('zip_pos' => 7, 'lon_pos' => 5, 'lat_pos' => 4);
	}
	if ( count($explodedRow) == 13 ) {
		echo "IN";
		$dataStructure = array('zip_pos' => 7, 'lon_pos' => 5, 'lat_pos' => 6);
	}	
	return $dataStructure;
}

function ogdbDistance($origin,$destination) {
	$fileData = explode("\n",ogdbGetData());
	foreach ( $fileData as $fileRow ) {
		$fileRow = explode("\t",$fileRow);
		$dataStructure = ogdbDataStructure($fileRow);
		if ( $dataStructure ) {
			if ( isset($fileRow[$dataStructure['zip_pos']]) && isset($fileRow[$dataStructure['lon_pos']]) && isset($fileRow[$dataStructure['lat_pos']]) ) {
				if ( substr_count($fileRow[$dataStructure['zip_pos']],$origin) == 1 ) {
					$origin_lon = deg2rad($fileRow[$dataStructure['lon_pos']]);
					$origin_lat = deg2rad($fileRow[$dataStructure['lat_pos']]);
				}
				if ( substr_count($fileRow[$dataStructure['zip_pos']],$destination) == 1 ) {
					$destination_lon = deg2rad($fileRow[$dataStructure['lon_pos']]);
					$destination_lat = deg2rad($fileRow[$dataStructure['lat_pos']]);
				}
			}
		}
		unset($dataStructure,$fileRow);
	}
	$distance = FALSE;
	if ( isset($origin_lon) && isset($origin_lat) && isset($destination_lon) && isset($destination_lat) ) {
		$distance = acos(sin($destination_lat)*sin($origin_lat)+cos($destination_lat)*cos($origin_lat)*cos($destination_lon - $origin_lon))*OGDB_EARTH_RADIUS;
	}
	return $distance;
}

// $sort = "asc", "desc" or "" for nothing
function ogdbRadius($zip,$km,$sort='asc') {
	$fileData = explode("\n",ogdbGetData());
	foreach ( $fileData as $fileRow ) {
		$fileRow = explode("\t",$fileRow);
		$dataStructure = ogdbDataStructure($fileRow);
		echo $fileRow;
		if ( isset($fileRow[$dataStructure['zip_pos']]) && isset($fileRow[$dataStructure['lon_pos']]) && isset($fileRow[$dataStructure['lat_pos']]) ) {
			if ( substr_count($fileRow[$dataStructure['zip_pos']],$zip) == 1 ) {
				$origin_lon = $fileRow[$dataStructure['lon_pos']];
				$origin_lat = $fileRow[$dataStructure['lat_pos']];
				$id = $fileRow[0];
			}
		}
		unset($dataStructure, $fileRow);
	}

	$lambda = $origin_lon * pi() /180;
	$phi = $origin_lat * pi() / 180;
	// Umwandlung der Kurgelkoordinaten ins kartesische Koordinatensystem
	$geoKoordX = OGDB_EARTH_RADIUS * cos($phi) * cos($lambda);
	$geoKoordY = OGDB_EARTH_RADIUS * cos($phi) * sin($lambda);
	$geoKoordZ = OGDB_EARTH_RADIUS * sin($phi);
	$data = array();
	if ( isset($origin_lon) && isset($origin_lat) && isset($id) ) {
		foreach ( $fileData as $fileRow ) {
			$fileRow = explode("\t",$fileRow);
			$dataStructure = ogdbDataStructure($fileRow);
			if ( isset($fileRow[$dataStructure['zip_pos']]) && isset($fileRow[$dataStructure['lon_pos']]) && isset($fileRow[$dataStructure['lat_pos']]) ) {
				$distance =  2*OGDB_EARTH_RADIUS * asin(
					SQRT(
					pow($geoKoordX - (OGDB_EARTH_RADIUS * cos($fileRow[$dataStructure['lat_pos']]*pi()/180) * cos($fileRow[$dataStructure['lon_pos']]*pi() /180)),2)
					+pow($geoKoordY - (OGDB_EARTH_RADIUS * cos($fileRow[$dataStructure['lat_pos']]*pi()/180) * sin($fileRow[$dataStructure['lon_pos']]*pi() /180)),2)
					+pow($geoKoordZ - (OGDB_EARTH_RADIUS * sin($fileRow[$dataStructure['lat_pos']]*pi()/180)),2)
					) / (2*OGDB_EARTH_RADIUS));
				if( $distance < $km && $id <> $fileRow[0]  ) {
					$data[$distance] = array('loc_id'=>$fileRow[0],'name'=>$fileRow[3],'zip'=>$fileRow[$dataStructure['zip_pos']],'distance'=>$distance);
				}
				unset($distance);
			}
			unset($dataStructure, $fileRow);
		}
	}
	switch ( $sort ) {
		case 'asc':
			ksort($data);
			break;
		case 'desc':
			krsort($data);
			break;
	}
	return $data;
}

