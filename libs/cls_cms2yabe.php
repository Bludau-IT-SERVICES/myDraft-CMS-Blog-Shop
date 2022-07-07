<?php 
class cls_cms2yabe {
	###########################################
	# >> In Datenbank setzten 
	###########################################
	function setDB($modus) {

		switch($modus) {
			case 'YABE':
					DBi::$conn->query('USE yabe')or die(mysqli_error());
					break;
			case 'CMS':	
					DBi::$conn->query('USE db_u10097_01') or die(mysqli_error());
					break;
		}
		return true; 
	}

	###########################################
	# >> Existert die eMail schon in YABE Profil
	###########################################
	function chkYABE_Profile_exists($email) {	    
		
		$strQuery_chk = "SELECT * FROM yabe_personals_profile WHERE email='".$email."'";  
		#echo $strQuery_chk.'<br>';
		$res_chk = DBi::$conn->query($strQuery_chk) or die(mysqli_error());
		$data = mysqli_fetch_assoc($res_chk);
		$anzahl = mysql_affected_rows();		
				 
		if ($anzahl == 0) {
			return false; 
		} else {
			return $data['ID']; 
		}		
	}
	###########################################
	# >> Existiert die Adressinfo schon ? 
	###########################################
	function chkYABE_adress_exists($email) {	    
		
		$strQuery_chk = "SELECT * FROM yabe_personals_adress WHERE email='".$email."'";  
		#echo $strQuery_chk.'<br>';
		$res_chk = DBi::$conn->query($strQuery_chk) or die(mysqli_error());
		$data = mysqli_fetch_assoc($res_chk);
		$anzahl = mysql_affected_rows();		
				
		if ($anzahl == 0) {
			return false; 
		} else {
			return $data['ID']; 
		}		
	}
	
	###########################################
	# >> YABE - Adressinformationen aktuallisieren 
	###########################################
	function setYABE_Adress_update($data) {
	
		if($data['type'] == '') {
			$data['type'] = 'CMS';
        }

		$strUpdate = "UPDATE yabe_personals_adress SET Type='".$data['type']."',name='".$data['txtVorname']." ".$data['txtNachname']."',street='".$data['txtStrasse']."',zipcode='".$data['txtPLZ']."',city='".$data['txtOrt']."',country='".$data['txtLand']."',telefon='".$data['txtTele']."' WHERE email='".$data['txteMail']."'";
		
		#echo $strUpdate.'<br>';
		DBi::$conn->query($strUpdate) or die(mysqli_error());
		return true; 
	} 
	
	#############################################
	# >> Ruft SQL Array der Kunden -> yabe_personals_adress
	#############################################
	function getYABE_Profile_Adress($KID) {		
		$query = 'SELECT * FROM yabe_personals_adress WHERE profil_id='.$KID;
		$res_user = DBi::$conn->query($query) or die(mysqli_error());		
		$strUser =  mysqli_fetch_assoc($res_user);
		
		#print_r(strUser);
		
		$strUserReturn['txtVorname'] = '';
		$strUserReturn['txtNachname'] = $strUser['name'];
		$strUserReturn['txtOrt'] = $strUser['city'];
		$strUserReturn['txtPLZ'] = $strUser['zipcode'];
		$strUserReturn['txtStrasse'] = $strUser['street'];
		$strUserReturn['txtBenutzernamen'] = ''; // to DO
		
		return $strUserReturn;
	}
	
	###########################################
	# >> Existert die eMail schon in YABE Profil
	###########################################
	function chkYABE_Profile_update($data) {
	
		if($data['txtBenutzernamen'] != '' and $data['txtPasswort'] != '') {
			$AddUsername = ',website_account="'.$data['txtBenutzernamen'].'", website_pwd="'.$data['txtPasswort'].'"';
		} else {
			$AddUsername = ''; 
		}
		if($data['delcampeid'] != '') {
			$AddDelcampe = ",delcampe_userid=".$data['delcampeid'];
        } else {
			$AddDelcampe = '';
		} 
		$strUpdate = 'UPDATE yabe_personals_profile SET isWebUser="Y", getNewsletter="'.$data['chkNewsletter'].'"'.$AddDelcampe.''.$AddUsername.' WHERE email="'.$data['txteMail'].'"';
		 
		#echo $strUpdate.'<br>';
		DBi::$conn->query($strUpdate) or die(mysqli_error());
		return true; 
	}	
 
	
	###########################################
	# >> tbl_user  in das YABE Profil  + YABE_
	###########################################
	function setYABE_Profile_insert($data) {
		$strQUERY = 'INSERT INTO yabe_personals_profile(email,isWebUser,CRC,letzter_email_klick,letzte_mail,eMail_click_count,getNewsletter,delcampe_userid)';

	    if($data['delcampeid'] == '') {
			$data['delcampeid'] = 0 ;
        }
		
		$strQUERY .= " VALUES('".$data['txteMail']."','Y','".$data['CRC']."','".$data['letzter_email_klick']."','".$data['letzte_mail']."','".$data['eMail_click_count']."','".$data['chkNewsletter']."','".$data['delcampeid']."')";


		#echo $strQUERY.'<br>';
		DBi::$conn->query($strQUERY) or die(mysqli_error());
		 
		
		return mysqli_insert_id(DBi::$conn);
	}
	
	
	
	###########################################
	# >> setYABE_Adress  
	#  - 
	###########################################	
	function setYABE_Adress_insert($data,$profilID) {

		$strQUERY = 'INSERT INTO yabe_personals_adress(email,Type,name,street,city,zipcode,country,telefon,profil_id)';
		if($data['type'] == '') {
			$data['type'] = 'CMS';
        }

		$strQUERY .= " VALUES('".$data['txteMail']."','".$data['type']."','".$data['txtVorname']." ".$data['txtNachname']."','".$data['txtStrasse']."','".$data['txtOrt']."','".$data['txtPLZ']."','".$data['txtLand']."','".$data['txtTele']."','".$profilID."')";
		
		#echo $strQUERY.'<br>';
		
		DBi::$conn->query($strQUERY) or die(mysqli_error()); 

		return true;		
	}
 
	function getYABE_Adress_id($email) {
		
		$strQUERY .= "SELECT ID FROM yabe_personals_adress WHERE email='".$email."' ORDER BY ID DESC";		
		$res = DBi::$conn->query($strQUERY) or die(mysqli_error()); 
		
		$data = mysqli_fetch_assoc($res);
		
		return $data['ID'];
	}
 
 	function setYABE_AddOrder($item_data,$profil_id) {
		
		if($item_data['price_new'] != 0) {
			$preis = $item_data['price_new'];
		} else {
			$preis = $item_data['preis'];
		}
		#echo print_r($item_data).' - '.$preis;
		$preis = str_replace(",",".",$preis);
		#exit;
		if ($item_data['type'] == '') {
          $item_data['type'] = 'CMS';
        }
		$strQUERY .= "INSERT INTO yabe_auction_list_live (`email`, `auction_end`,auction_begin,auction_title,YABE_ID,current_price,eBay_ID,personal_id,auction_type,iseBayEnded,ListType,bids,eBay_account,startprice,shippment,menge,bild_url) VALUES ('".$item_data['email']."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."','".$item_data['auction_title']."','".$item_data['YABE_ID']."','".$preis."','".$item_data['ID']."','".$profil_id."','".$item_data['type']."','".$item_data['ended']."','".$item_data['ListType']."','".$item_data['bids']."','".$item_data['KID']."','".$item_data['preis']."','0','".$item_data['menge']."','".$item_data['bild_url']."')";
		
		#echo $strQUERY;
		DBi::$conn->query($strQUERY) or die(mysqli_error()); 
		
		return true;
	}
 
	###########################################
	# >> tbl_user  in das YABE Profil  + YABE_
	###########################################
	function setYABE_CMSUSER2YABE($data_email) {
 
		
		#$this->setDB('YABE'); 
		$iCount = 0;
 
		
			# Kontrolle Ob Adresse Existiert 
			$strID = $this->chkYABE_Profile_exists($data_email['txteMail']);

			if($strID == false) {
				echo '<strong>NEU:</strong> '.$data_email['txteMail'];
				$strProfilID = $this->setYABE_Profile_insert($data_email);
				echo "(".$strProfilID.")<br>";
				
				$this->setYABE_Adress_insert($data_email,$strProfilID);						
				#exit;				
			} else {
				$iCount++;
				echo $iCount.' - bereits <strong>erfasstes Profil</strong>: '.$data_email['txteMail'].' ('.$strID.')<br>';
				
				$this->chkYABE_Profile_update($data_email); 
 
			}
			
			$strProfilID = $this->chkYABE_adress_exists($data_email['txteMail']);
			if($strProfilID == false) {
				echo '<h2>Neue Adressinfo <strong>erfasstes Profil</strong>: '.$data_email['txteMail'].' ('.$strID.')</h2>';
				$this->setYABE_Adress_insert($data_email,$strID);
			}	else {
				$this->setYABE_Adress_update($data_email,$strProfilID);
			}
 

	#	$this->setDB('CMS');
} 
	###########################################
	# >> tbl_user  in das YABE Profil  + YABE_
	###########################################
	function setYABE_status_ordered($item_data) {
 
		
		#$this->setDB('YABE'); 
		$iCount = 0;
 		$query = "UPDATE tblGes SET Bestellt=1 WHERE ID='".$item_data['YABE_ID']."'";
		#echo $query;
		DBi::$conn->query($query) or die(mysqli_error());
		
	} 
	function setYABE_delcampe_delete_item_old($item_data) {
		 $ch = curl_init();  
		$url = 'http://updater.cubss.net/delcampe/auction_end.php?ref_id='.$item_data['YABE_ID'].'-Deutsch';
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	 
		$output=curl_exec($ch);
	 
		curl_close($ch);
		$bcc .= "Bcc: info@shopste.com\r\n";
		mail('philafriend@gmx.de','Delcampe ID '.$item_data['YABE_ID'],'NACHRICHT:'.$output,$bcc);
	}
	
	function setYABE_delcampe_delete_item($item_data,$strShopsteDomainConfig1) {
		$ch = curl_init();  
		if($strShopsteDomainConfig1['EISO_SERVER'] == '') {
			$strShopsteDomainConfig1['EISO_SERVER'] = 'cubss.net';
		}
		if($strShopsteDomainConfig1['EISO_SERVER_HTTPS'] == 'Y') {
			$strHTTP = 'https';
		} else {
			$strHTTP = 'http';
		}
		
		if($strShopsteDomainConfig1['EISO_SERVER'] == 'cubss.net') {
 
			$url = $strHTTP.'://eiso-shop.cubss.net/'.$strShopsteDomainConfig1['EISO_USERNAME'].'/auction_end.php?ref_id='.$item_data['YABE_ID'].'-Deutsch';
			echo $url; 
		} else {
			$url = $strHTTP.'://'.$strShopsteDomainConfig1['EISO_SERVER'].'/delcampe/auction_end.php?ref_id='.$item_data['YABE_ID'].'-Deutsch';
		}
 
		echo $url;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	  
		$output=curl_exec($ch);
	 
		curl_close($ch);
		$bcc .= "Bcc: info@shopste.com\r\n";
		mail($strShopsteDomainConfig1['verkäufer_email'],'Delcampe Auktion beendet REF-ID '.$item_data['YABE_ID'].' weil '.$strShopsteDomainConfig1['käufer_email'].' ('.$strShopsteDomainConfig1['käufer_kid'].')- '.$item_data['auction_title'],'NACHRICHT:'.$output.'\n \n'.$url,$bcc); 
	}	
} # end class
?>