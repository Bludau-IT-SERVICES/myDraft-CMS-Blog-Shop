<?php 
	session_start();
	
	require_once('../include/inc_config-data.php');
	require_once('../include/inc_basic-functions.php');
	require_once('../include/inc_thumbnails.php');
	
	require_once('../framework/Facebook/HttpClients/FacebookHttpable.php');
	require_once('../framework/Facebook/HttpClients/FacebookCurl.php');
	require_once('../framework/Facebook/HttpClients/FacebookCurlHttpClient.php');
	require_once('../framework/Facebook/FacebookSession.php');
	require_once('../framework/Facebook/FacebookRedirectLoginHelper.php');
	require_once('../framework/Facebook/FacebookRequest.php');
	require_once('../framework/Facebook/FacebookResponse.php');
	require_once('../framework/Facebook/FacebookSDKException.php');
	require_once('../framework/Facebook/FacebookRequestException.php');
	require_once('../framework/Facebook/FacebookOtherException.php');
	require_once('../framework/Facebook/FacebookAuthorizationException.php');
	require_once('../framework/Facebook/GraphObject.php');
	require_once('../framework/Facebook/GraphSessionInfo.php');
	require_once('../framework/Facebook/GraphUser.php');
	require_once( '../framework/Facebook/Entities/AccessToken.php' );
	require_once( '../framework/Facebook/Entities/SignedRequest.php' );
	require_once( '../framework/Facebook/FacebookPermissionException.php' );

	use Facebook\HttpClients\FacebookHttpable;
	use Facebook\HttpClients\FacebookCurl;
	use Facebook\HttpClients\FacebookCurlHttpClient;
	use Facebook\FacebookSession;
	use Facebook\FacebookRedirectLoginHelper;
	use Facebook\FacebookRequest;
	use Facebook\FacebookResponse;
	use Facebook\FacebookSDKException;
	use Facebook\FacebookRequestException;
	use Facebook\FacebookOtherException;
	use Facebook\FacebookAuthorizationException;
	use Facebook\GraphObject;
	use Facebook\GraphSessionInfo;
	use Facebook\GraphUser;
	use Facebook\Entities\AccessToken;
	use Facebook\Entities\SignedRequest;
	
	# Injection FIX 
 	$_POST = mysql_real_escape_array($_POST);
	$_GET = mysql_real_escape_array($_GET);
	
	###########################################################
	# >> Facebook Fanpage POST
	############################################################
	
	#echo $_SESSION['user_id']." POST";
	# Eingeloggt mit Benutzer ID 
	if(isset($_SESSION['user_id'])) {
		#echo "POST";
		#Facebook Profile vorhanden	
		$query = "SELECT * FROM benutzer JOIN api_facebook ON benutzer.api_facebook_id = api_facebook.api_facebook_id WHERE id='".$_SESSION['user_id']."'";
		#echo $query;
		$resAdmin = DBi::$conn->query($query) or die(mysqli_error());
		$strUserFacebook = mysqli_fetch_assoc($resAdmin);
		
		if(isset($strUserFacebook['api_facebook_id'])) {
			
			##################################
			# >> Fanpage vorhanden?
			##################################
			$query ="SELECT * FROM api_facebook_fanpages WHERE api_facebook_id='".$strUserFacebook['api_facebook_id']."' AND fanpage_id='".$_POST['fanpage_id']."'";
			#echo $query;
			$resUserFacebookFanpage = DBi::$conn->query($query) or die(mysqli_error());
			$strFanpage2Post = mysqli_fetch_assoc($resUserFacebookFanpage);
			


			// init app with app id (APPID) and secret (SECRET)
			FacebookSession::setDefaultApplication('102136003453690','35450212093a4f87fefc3e574d82914d');
			
			$query ="SELECT * FROM shop_item  WHERE shop_item_id='".$_POST['shop_item_id']."'";
			#echo $query;
			$resItem = DBi::$conn->query($query) or die(mysqli_error());
			$strShopItem = mysqli_fetch_assoc($resItem);
			
			# URL Der Seite holen Marktplatz vor Subshop 
			if(!empty($strShopItem['shopste_marktplatz_menue_id'])) {
				$strURL = getPathUrl('de',$strShopItem['shopste_marktplatz_menue_id']);
				$strDomain = "http://shopste.com";
				$strPageID = $strShopItem['shopste_marktplatz_menue_id'];
			} else {
				$strURL = getPathUrl('de',$strShopItem['menue_id']);
				$domain = $_SERVER['HTTP_HOST'];
				$domain = str_replace("www.", "", $domain);
				$query = "SELECT * from domains WHERE name='$domain'";
				$domain_res = mysqli_fetch_assoc(DBi::$conn->query($query));
				$strDomain = $domain_res['name'];
				$strPageID = $strShopItem['menue_id'];
			}
			
			#Bilder holen
			$query = "SELECT * FROM shop_item_picture WHERE shop_item_id='".$_POST['shop_item_id']."' AND picture_nr='1'";
			$resPic = DBi::$conn->query($query) or die(mysqli_error());
			$strPicture = mysqli_fetch_assoc($resPic);
			
			# Produkt Info vorhanden
			if(isset($strShopItem['shop_item_id'])) {
				// If you already have a valid access token:
				$session = new FacebookSession($strUserFacebook['user_access_token']);

				// If you're making app-level requests:
				$session = FacebookSession::newAppSession();

				// To validate the session:
				try {
				  $session->validate();
				} catch (FacebookRequestException $ex) {
				  // Session not valid, Graph API returned an exception with the reason.
				  echo $ex->getMessage();
				} catch (\Exception $ex) {
				  // Graph API returned info, but it may mismatch the current app or have expired.
				  echo $ex->getMessage();
				}
				##################################
				# >> Facebook API POSTEN
				##################################
				if(!empty($strFanpage2Post['fanpage_id'])) {
					try {
						$response = (new FacebookRequest(
							  $session, 'POST', '/'.$strFanpage2Post['fanpage_id'].'/feed', array(
								'access_token' => $strFanpage2Post['fanpage_token'],
								'link' => $strDomain.'/'.$strURL,
								'picture' => $strDomain.$strPicture['picture_url'],
								'message' => $strShopItem['name_de'].' - '.strip_tags(html_entity_decode($strShopItem['beschreibung']))
							  )
							))->execute()->getGraphObject();
						echo "<br/><br/>Eintrag auf Facebook ".$strFanpage2Post['fanpage_name']." veröffentlicht: " . $response->getProperty('id').'<br/><br/>';
					} catch(FacebookRequestException $e) {
						echo "Fehlercode: " . $e->getCode();
						echo " Fehlermeldung: " . $e->getMessage();
					}  
					$query = "INSERT INTO api_facebook_posted(poster_id,page_id,benutzer_id,isPage) VALUES('".$strFanpage2Post['fanpage_id']."','".$strPageID."','".$_SESSION['user_id']."','Y')";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error());
				} #Fanpage 
				else {
					#								'access_token' => $strFanpage2Post['fanpage_token'],
					try {
						$response = (new FacebookRequest(
							  $session, 'POST', '/'.$_POST['fanpage_id'].'/feed', array(
								'link' => $strDomain.'/'.$strURL,
								'picture' => $strDomain.$strPicture['picture_url'],
								'message' => $strShopItem['name_de'].' - '.strip_tags(html_entity_decode($strShopItem['beschreibung']))
							  )
							))->execute()->getGraphObject();
						echo "<br/><br/>Eintrag in Facebook Profil veröffentlicht" . $response->getProperty('id').'<br/><br/>';
					} catch(FacebookRequestException $e) {
						echo "Fehlercode: " . $e->getCode();
						echo " Fehlermeldung: " . $e->getMessage();
					}					
					$query = "INSERT INTO api_facebook_posted(poster_id,page_id,benutzer_id,isPage) VALUES('".$_POST['fanpage_id']."','".$strPageID."','".$_SESSION['user_id']."','N')";
					$resInsert = DBi::$conn->query($query) or die(mysqli_error());
				}
			}
		} # API Daten vorhanden
	} # Eingeloggt
	
?>