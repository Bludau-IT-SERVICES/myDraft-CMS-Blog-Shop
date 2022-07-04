<?php 
session_start();

function encrypt_eiso_post($text,$keybytes,$ivbytes) {

	// Padding the text
	$padding = strlen($text)%8;
	for($i=$padding; $i<8; $i++){
		$text .= chr(8-$padding);
	}

	$decryptRaw = mcrypt_encrypt(MCRYPT_3DES, $keybytes, $text, MCRYPT_MODE_CBC, $ivbytes);
	$encoded = base64_encode($decryptRaw);

	#print "$encoded<br/>";
	$encryptedString64 = $encoded;
	return $encryptedString64;
}
function decrypt_eiso_post($encryptedString64,$keybytes,$ivbytes) {
	$decryptbytes = base64_decode($encryptedString64);
	$decryptRaw = mcrypt_decrypt(MCRYPT_3DES, $keybytes, $decryptbytes, MCRYPT_MODE_CBC, $ivbytes);
	$decryptString=trim($decryptRaw,"\x00..\x1F");
	#print "$decryptString<br/>";	
	return $decryptString;
}

function httpPost($url,$params)
{
  $postData = '';
   //create name value pairs seperated by &
   foreach($params as $k => $v) 
   { 
      $postData .= $k . '='.$v.'&'; 
   }
   rtrim($postData, '&');

	$ch = curl_init();  

	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch,CURLOPT_HEADER, false); 
	curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);	

	$output=curl_exec($ch);

	curl_close($ch);
	return $output;

}

# Shopste DB verbinden
require_once('../include/inc_config-data.php');
# Domain Settings abrufen

if(!empty($_POST['domain_id']) && $_POST['domain_id'] != 'undefined') {
	$strDomain_id = $_POST['domain_id'];
} else {
	$strDomain_id = $_SESSION['domain_id'];
}

$query = "SELECT * FROM domain_settings WHERE domain_id='".$strDomain_id."' AND name='EISO_USERNAME'";
$res = DBi::$conn->query($query) or die(mysqli_error());
$strShopsteDomainConfig = mysqli_fetch_assoc($res);  
$strShopsteDomainConfig1['EISO_USERNAME'] = $strShopsteDomainConfig['value'];

$query = "SELECT * FROM domain_settings WHERE domain_id='".$strDomain_id."' AND name='EISO_PASSWORT'";
$res = DBi::$conn->query($query) or die(mysqli_error());
$strShopsteDomainConfig = mysqli_fetch_assoc($res);  
$strShopsteDomainConfig1['EISO_PASSWORT'] = $strShopsteDomainConfig['value'];

$query = "SELECT * FROM domain_settings WHERE domain_id='".$strDomain_id."' AND name='EISO_SERVER'";
$res = DBi::$conn->query($query) or die(mysqli_error());
$strShopsteDomainConfig = mysqli_fetch_assoc($res);  
$strShopsteDomainConfig1['EISO_SERVER'] = $strShopsteDomainConfig['value'];

$query = "SELECT * FROM domain_settings WHERE domain_id='".$strDomain_id."' AND name='EISO_SERVER_HTTPS'";
$res = DBi::$conn->query($query) or die(mysqli_error());
$strShopsteDomainConfig = mysqli_fetch_assoc($res);  
$strShopsteDomainConfig1['EISO_SERVER_HTTPS'] = $strShopsteDomainConfig['value'];

$query = "SELECT * FROM domain_settings WHERE domain_id='".$strDomain_id."' AND name='EISO_PREFIX'";
$res = DBi::$conn->query($query) or die(mysqli_error());
$strShopsteDomainConfig = mysqli_fetch_assoc($res);  
$strShopsteDomainConfig1['EISO_PREFIX'] = $strShopsteDomainConfig['value'];

$query = "SELECT * FROM domain_settings WHERE domain_id='".$strDomain_id."' AND name='DELCAMPE_AKTIV'";
$res = DBi::$conn->query($query) or die(mysqli_error());
$strShopsteDomainConfig = mysqli_fetch_assoc($res);  
$strShopsteDomainConfig1['DELCAMPE_AKTIV'] = $strShopsteDomainConfig['value'];

$query = "SELECT * FROM domain_settings WHERE domain_id='".$strDomain_id."' AND name='DELCAMPE_ENDE_BEI_NULL'";
$res = DBi::$conn->query($query) or die(mysqli_error());
$strShopsteDomainConfig = mysqli_fetch_assoc($res);  
$strShopsteDomainConfig1['DELCAMPE_ENDE_BEI_NULL'] = $strShopsteDomainConfig['value'];

$params = array(
   "eiso_username" => rawurlencode($strShopsteDomainConfig1['EISO_USERNAME']),
   "eiso_pwd" => rawurlencode($strShopsteDomainConfig1['EISO_PASSWORT'])
);
#print_r($params);
#echo $strShopsteDomainConfig['EISO_SERVER'].'.'.$strDomain_id;
if($strShopsteDomainConfig1['EISO_SERVER'] == '') {
	$strShopsteDomainConfig1['EISO_SERVER'] = 'bludau-media.de';
}
if($strShopsteDomainConfig1['EISO_SERVER_HTTPS'] == 'Y') {
	$strHTTP = 'https';
} else {
	$strHTTP = 'http';
}

if($strShopsteDomainConfig1['EISO_SERVER'] == 'bludau-media.de') {
	#echo $strHTTP.'://eiso-shop.bludau-media.de/'.$strShopsteDomainConfig1['EISO_USERNAME'].'/getFetchConfig.php';
	$strHTMLMessage = httpPost($strHTTP.'://eiso-shop.bludau-media.de/'.$strShopsteDomainConfig1['EISO_USERNAME'].'/getFetchConfig.php',$params);
	echo $strHTMLMessage;
} else {
	$strHTMLMessage = httpPost($strHTTP.'://'.$strShopsteDomainConfig1['EISO_SERVER'].'/getFetchConfig.php',$params);
	echo 'extern: '.$strHTMLMessage;
} 
#echo $strShopsteDomainConfig1['EISO_USERNAME'].' '.$strShopsteDomainConfig1['EISO_PASSWORT']; 
$strAry = explode("-",$strHTMLMessage);
$strAry2 = $strAry;
#print_r($strHTMLMessage.$strShopsteDomainConfig1['EISO_SERVER']); 

$key64 = "fGE1NXdvcnFEcjB3U1NAUDIxMDlqdWh0";
$iv64 = "N2EhIVdvcj8=";
$keybytes = base64_decode($key64);
$ivbytes = base64_decode($iv64);
#print_r($strAry);
$db_username = $strAry[0];
$db_password =  decrypt_eiso_post($strAry[1],$keybytes,$ivbytes);
#echo $db_username.' '.$db_password;
$db_database = $strShopsteDomainConfig1['EISO_PREFIX'].$strAry[2];
$db =  mysql_connect($strShopsteDomainConfig1['EISO_SERVER'],$db_username,$db_password) or die('ABC:'.mysqli_error());
print_r($db);
# Datenbank Auswählen 
$res = mysql_select_db($db_database,$db);
if (! $db)  {
 echo "ERR1: Kein Zugriff auf Datenbank";
 exit;
}
DBi::$conn->query("SET NAMES 'utf8'") or die(mysqli_error());
DBi::$conn->query("SET CHARACTER SET 'utf8'") or die(mysqli_error());

require_once ("../libs/cls_cms2yabe.php");
include_once('../include/inc_basic-functions.php');
	
	$_GET = mysql_real_escape_array($_GET);
	$_POST = mysql_real_escape_array($_POST);
	


$yabe = new cls_cms2yabe ();
echo $_POST['txtEmail'];
if(isset($_POST['txtEmail'])) {
	$_POST['txteMail'] = $_POST['txtEmail'];
	$_POST['CRC'] = rand(0,1000).rand(1000,2000);
	$_POST['letzter_email_klick'] = '';
	$_POST['letzte_mail'] = '';
	$_POST['eMail_click_count'] = '0';
	$_POST['chkNewsletter'] = 'Y';
	$_POST['type'] ='Shopste';
	$_POST['txtVorname'] = $_POST['txtVorname'];
	$_POST['txtNachname'] = $_POST['txtNachname'];
	$_POST['txtStrasse'] = $_POST['txtStrasse'];
	$_POST['txtOrt'] = $_POST['txtOrt']; 
	$_POST['txtPLZ'] = $_POST['txtPLZ'];
	$_POST['txtLand'] = $_POST['txtLand'];
	$_POST['txtTele'] = $_POST['txtFirma'];
	
	
	$yabe->setYABE_CMSUSER2YABE($_POST);
	
	$KID = $yabe->chkYABE_Profile_exists($_POST['txteMail']);
	
/* 	echo 'KID:'.$KID['email'];
 
	$strName = explode(" ",$KID['name']);
	
	$_POST['txteMail'] = $KID['email'];	 
	$_POST['txtNachname'] 	= $strName[1];		
	$_POST['txtVorname']	= $strName[0];		
	$_POST['txtStrasse']    = $KID['street'];		
	$_POST['txtOrt'] 		= $KID['city'];		
	$_POST['txtPLZ'] 		= $KID['zipcode'];		
	$_POST['txtLand'] 		= $KID['country']; */
	
	# Warenkorb holen 
	#echo 'ID: '.$_SESSION['shop_cart_ids'];
	$strAry = explode("|",$_SESSION['shop_cart_ids_eiso']);	
	
	$shop_artikel_anzahl = 0;
	
	$shop_artikel_preis = 0.0;
 
	// Alle Artikel durchlaufen
	$shop_artikel_preisGes = 0;
	$shop_artikel_gewicht = 0;
	
	$adress_id = $yabe->getYABE_Adress_id($_POST['txteMail']);	
	#echo "<br/>".$adress_id;
	for($i=0; $i < count($strAry) -1; $i++) {
		$shop_artikel_anzahl++;
		#echo "IN";
		// Artikel Details holen 
		$strItemDetailAry = explode("-",$strAry[$i]);
		
		//Artikel Menge Reduzieren
		#$query = "UPDATE shop_item SET menge = (menge - ".$strItemDetailAry[1].") WHERE shop_item_id='".$strItemDetailAry[0]."'";
		#DBi::$conn->query($query) or die(mysqli_error());
		#include('../include/inc_config-data.php');
		
		# Shopste DB verbinden
		require('../include/inc_config-data.php');

		$query ="SELECT *,shop_item.name_de as shop_name FROM shop_item JOIN menue ON shop_item.menue_id = menue.id WHERE shop_item.shop_item_id ='".$strItemDetailAry[0]."'";
		$resItem = DBi::$conn->query($query) or die(mysqli_error());
		$Cartdata = mysqli_fetch_assoc($resItem);
		
		$shop_artikel_preis = str_replace(".",",",$strItemDetailAry[2]);
		$shop_artikel_preisGes += $shop_artikel_preis * $strItemDetailAry[1];
		$shop_artikel_gewicht += $Cartdata['gewicht'] * $strItemDetailAry[1];	
		
		# Verschiedene Mehrwertsteuer speichern
		#$shop_artikel_mwst[$Cartdata['item_mwst']] += (($shop_artikel_preisGes / 100) * $Cartdata['item_mwst']);
		
		#$domain_id = $Cartdata['domain_id'];
		// Seite wo Artikel liegt abrufen
		$pathItem = 'http://'.$_SERVER['SERVER_NAME'].'/'.getPathUrl($_SESSION['language'],$Cartdata['menue_id']);
		
		$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$strItemDetailAry[0]."'";
		$strBild = mysqli_fetch_assoc(DBi::$conn->query($query));
		$strBild['picture_url'] = 'http://'.$_SERVER['SERVER_NAME'].'/'.$strBild['picture_url'];
	 
		$item_data['bild_url'] = $strBild['picture_url'];
		#echo $_POST['txteMail'].' - '.$KID.' ';
		
		$item_data['auction_title'] = $Cartdata['shop_name'];
		$item_data['email'] = $_POST['txteMail'];
		$item_data['YABE_ID'] = $Cartdata['item_number'];
		$item_data['ID'] = $strItemDetailAry[0].'-'.md5(date("Y-m-d H:i:s".rand(0,1000).rand(1000,2000))); # Hängt zufall dran -> mehrfachbestellen ermöglichen
		$item_data['preis'] = $strItemDetailAry[2];
		$item_data['ended'] = 'Completed';
		$item_data['ListType'] = 'FixedPrice'; 
		$item_data['bids'] = '1';
		$item_data['KID'] = $KID;
		$item_data['price_new'] = $strItemDetailAry[2];	
		$item_data['menge'] = $strItemDetailAry[1];
		$item_data['type'] = 'Shopste';
		
		$query = "SELECT * FROM shop_info WHERE domain_id='".$strDomain_id."'";
		$strShopInfo = mysqli_fetch_assoc(DBi::$conn->query($query));
		$strShopsteDomainConfig1['verkäufer_email'] = $strShopInfo['email_shop_main'];
		$strShopsteDomainConfig1['käufer_kid'] = $KID;
		$strShopsteDomainConfig1['käufer_email'] = $item_data['email'];
		
		# print_r($strAry2);
		$db_username = $strAry2[0];
		$db_password = decrypt_eiso_post($strAry2[1],$keybytes,$ivbytes);
		$db_database = $strShopsteDomainConfig1['EISO_PREFIX'].$strAry2[2];
		#print_r($strAry2);
		$db2 =  mysql_connect($strShopsteDomainConfig1['EISO_SERVER'],$db_username,$db_password) or die('ERR0919:'.mysqli_error());
			
		# Datenbank Auswählen 
		#echo $db_database.' - '.$db_username;
		$res = mysql_select_db($db_database,$db2);
		if (! $res)  {
		 echo "ERR2:Kein Zugriff auf Datenbank";
		 exit;
		}
		DBi::$conn->query("SET NAMES 'utf8'") or die(mysqli_error());
		DBi::$conn->query("SET CHARACTER SET 'utf8'") or die(mysqli_error());
		#print_r($item_data);
		$yabe->setYABE_AddOrder($item_data,$adress_id);
		$yabe->setYABE_status_ordered($item_data);
		
		if($strShopsteDomainConfig1['DELCAMPE_AKTIV'] == 'Y') {
			$yabe->setYABE_delcampe_delete_item($item_data,$strShopsteDomainConfig1);
		}
	}
	$_SESSION['shop_cart_ids_eiso'] ='';
}
?>