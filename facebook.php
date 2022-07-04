<?php
session_start();	
if(empty($_GET['userid'])) {
?>

<html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
		<meta content="INDEX,FOLLOW" name="robots">
		<link media="all" href="../css/template_master.css" type="text/css" rel="stylesheet">
		<title>Shopste2Facebook App Freigabe</title>
	</head>
	<body>
 
			<div class="page">
			<div class="block block-cart" id="box_texthtml_21"><div class="block-title"> <h1>Facebook Freigabe für Shopste</h1></div>
			<div class="content" id="modul_texthtml_21"><p>
			<h2>Facebook App Freigabe</h2>
			
			Sie müssen als Administrator in Ihrem Subshop angemeldet sein.
			</p></div></div>
			</div>
		 
	</body>
</html>
<?php
exit(0);
}	
 	include_once('include/inc_basic-functions.php');
	include_once('include/inc_config-data.php');
	
 	$_POST = mysql_real_escape_array($_POST);
	$_GET = mysql_real_escape_array($_GET);

	
	$_SESSION['domainLanguage'] = 'de';
	$_SESSION['language'] = 'de';
	$_POST = mysql_real_escape_array($_POST);
	$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);

#define('FACEBOOK_SDK_V4_SRC_DIR', '/var/www/vhosts/shopste.com/httpdocs/framework/Facebook/');
#require __DIR__ . '/framework/Facebook/autoload.php';
set_include_path("/var/www/vhosts/shopste.com/httpdocs/framework/Facebook/");

require_once('framework/Facebook/HttpClients/FacebookHttpable.php');
require_once('framework/Facebook/HttpClients/FacebookCurl.php');
require_once('framework/Facebook/HttpClients/FacebookCurlHttpClient.php');
require_once('framework/Facebook/FacebookSession.php');
require_once('framework/Facebook/FacebookRedirectLoginHelper.php');
require_once('framework/Facebook/FacebookRequest.php');
require_once('framework/Facebook/FacebookResponse.php');
require_once('framework/Facebook/FacebookSDKException.php');
require_once('framework/Facebook/FacebookRequestException.php');
require_once('framework/Facebook/FacebookOtherException.php');
require_once('framework/Facebook/FacebookAuthorizationException.php');
require_once('framework/Facebook/GraphObject.php');
require_once('framework/Facebook/GraphSessionInfo.php');
require_once('framework/Facebook/GraphUser.php');
require_once( 'framework/Facebook/Entities/AccessToken.php' );
require_once( 'framework/Facebook/Entities/SignedRequest.php' );
require_once( 'framework/Facebook/FacebookPermissionException.php' );

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

// init app with app id (APPID) and secret (SECRET)
FacebookSession::setDefaultApplication('102136003453690','35450212093a4f87fefc3e574d82914d');


$helper = new FacebookRedirectLoginHelper('http://shopste.com/facebook.php?userid='.$_GET['userid']);
try {
  $session = $helper->getSessionFromRedirect();
} catch(FacebookRequestException $ex) {
  // When Facebook returns an error
} catch(\Exception $ex) {
  // When validation fails or other local issues
}
if (!empty($session)) {
	
 
// Make a new request and execute it.
try {
  $response = (new FacebookRequest($session, 'GET', '/me'))->execute();
  $object = $response->getGraphObject();
  // echo $object->getProperty('name');
  // echo $object->getProperty('id');
  // echo $object->getProperty('birthday');
  // echo $object->getProperty('age_range');
  // echo $object->getProperty('email');
  // echo $object->getProperty('first_name');
  // echo $object->getProperty('gender');
  // echo $object->getProperty('hometown');
  // echo $object->getProperty('last_name');
  // echo $object->getProperty('locale');
  // echo $object->getProperty('link');
  // echo $object->getProperty('is_verified');
  // echo $object->getProperty('political');
  // echo $object->getProperty('religion');
  // echo $object->getProperty('relationship_status');
  // echo $object->getProperty('currency');
  // echo $object->getProperty('bio');
  
  #$accessToken = new AccessToken($_GET['code']);
    // Get info about the token
  // Returns a GraphSessionInfo object
  #$accessTokenInfo = $accessToken->getInfo();
  #print_r($accessTokenInfo);
  #echo '<pre>';
  #print_r($session->getToken());
  #echo '</pre>';
  
  $query = "SELECT count(*) as anzahl FROM api_facebook WHERE user_id='".$object->getProperty('id')."'";
  $resFacebook = DBi::$conn->query($query) or die(mysqli_error());
  $strFacebookDB = mysqli_fetch_assoc($resFacebook); 
  if($strFacebookDB['anzahl'] == "0") {
	  $query = "INSERT INTO api_facebook(user_access_token,user_id,vorname,nachname,email,birthday,age_range,gender,hometown,locale,link,is_verified,political,religion,relationship_status,currency,bio) VALUES('".$session->getToken()."','".$object->getProperty('id')."','".$object->getProperty('first_name')."','".$object->getProperty('last_name')."','".$object->getProperty('email')."','".$object->getProperty('birthday')."','".$object->getProperty('age_range')."','".$object->getProperty('gender')."','".$object->getProperty('hometown')."','".$object->getProperty('locale')."','".$object->getProperty('link')."','".$object->getProperty('is_verified')."','".$object->getProperty('political')."','".$object->getProperty('religion')."','".$object->getProperty('relationship_status')."','".$object->getProperty('currency')."','".$object->getProperty('bio')."')";
	  #echo $query;
	  $res = DBi::$conn->query($query) or die(mysqli_error());
	  $strFaceBookAPIID = mysqli_insert_id(DBi::$conn);
  } else {
	  	  $query = "UPDATE api_facebook SET user_access_token = '".$session->getToken()."',vorname='".$object->getProperty('first_name')."',nachname='".$object->getProperty('last_name')."',email='".$object->getProperty('email')."',birthday='".$object->getProperty('birthday')."',age_range='".$object->getProperty('age_range')."',gender='".$object->getProperty('gender')."',hometown='".$object->getProperty('hometown')."',locale='".$object->getProperty('locale')."',link='".$object->getProperty('link')."',is_verified='".$object->getProperty('is_verified')."',political='".$object->getProperty('political')."',religion='".$object->getProperty('religion')."',relationship_status='".$object->getProperty('relationship_status')."',currency='".$object->getProperty('currency')."',bio='".$object->getProperty('bio')."' WHERE user_id='".$object->getProperty('id')."'";
	  #echo $query;
	  $res = DBi::$conn->query($query) or die(mysqli_error());
	  $query = "SELECT * FROM api_facebook WHERE user_id='".$object->getProperty('id')."'";
	  $strFacebookID = mysqli_fetch_assoc(DBi::$conn->query($query));
	  $strFaceBookAPIID = $strFacebookID['api_facebook_id'];
  }
  
  $query = "UPDATE benutzer SET api_facebook_id='".$strFaceBookAPIID."' WHERE email_crc='".$_GET['userid']."'";
  #echo $query;
  $resBenutzerupdate = DBi::$conn->query($query) or die(mysqli_error());
  
} catch (FacebookRequestException $ex) {
  echo $ex->getMessage();
} catch (\Exception $ex) {
  echo $ex->getMessage();
} 
  // Logged in
  // Get a list of pages that this user admins; requires "manage_pages" permission
	$request = new FacebookRequest($session, 'GET', '/me/accounts?fields=name,access_token,perms');
	$pageList = $request->execute()
	  ->getGraphObject()
	  ->asArray();
 
	  try {
	$z=0;
	foreach ($pageList as $page) {
	  #$pageAccessToken = $page['access_token'];
	  #echo $pageAccessToken.'<br/><br/>';
	  // Store $pageAccessToken and/or
	  // send requests to Graph on behalf of the page
	  #echo '<pre>';
	  #print_r($page[0]->access_token);
		# echo " -0 OK".count($page);
		if($z > 0) {
			break;
		}
		
		 
		  for($i=0; $i < count($page); $i++) {

		#		if(isset($page[$i])) {
		# echo " -000 OK";
								#echo $i.' - '.count($page).' - '.$page[$i]->name.'<br/><br/>';
			#echo "<pre>";
			#print_r($page[$i]->id).'<br/><br/>';
		#	echo "</pre>";
			if(isset($page[$i]->id)) {
					$query = "SELECT count(*) as anzahl FROM api_facebook_fanpages WHERE fanpage_id='".$page[$i]->id."'";
					#echo $query.'<br/><br/>';
					$res = DBi::$conn->query($query) or die(mysqli_error());
					$strPageExists = mysqli_fetch_assoc($res);
					
					if($strPageExists['anzahl'] == "0") {
						$query = "INSERT INTO api_facebook_fanpages(fanpage_name,fanpage_token,fanpage_id,api_facebook_id) VALUES('".$page[$i]->name."','".$page[$i]->access_token."','".$page[$i]->id."','".$strFaceBookAPIID."')";
						$resInsert = DBi::$conn->query($query) or die(mysqli_error());
					} else {
						$query = "UPDATE api_facebook_fanpages SET fanpage_name='".$page[$i]->name."',fanpage_token='".$page[$i]->access_token."',fanpage_id='".$page[$i]->id."',api_facebook_id='".$strFaceBookAPIID."' WHERE fanpage_id='".$page[$i]->id."'";
						$resInsert = DBi::$conn->query($query) or die(mysqli_error());			
					}
					  /* try {
						$response = (new FacebookRequest(
						  $session, 'POST', '/'.$page[$i]->id.'/feed', array(
							'access_token' => $page[$i]->access_token,
							'link' => 'http://shopste.com',
							'message' => 'API TEST'
						  )
						))->execute()->getGraphObject();
						echo "Eintrag auf Facebook veröffentlicht: " . $response->getProperty('id');
					  } catch(FacebookRequestException $e) {
						echo "Fehlercode: " . $e->getCode();
						echo " Fehlermeldung: " . $e->getMessage();
					  }   */
				  
			 }
			
			  #print_r($page);
			  #echo '</pre>';
			}


	  # echo " -1 OK";
		$z++;
	}		  }			catch (Exception $ex) {
  echo $ex->getMessage();
} 
  # echo " -2 OK";
}


/* 
if($session) {
  try {
    $response = (new FacebookRequest(
      $session, 'POST', '/me/feed', array(
        'link' => 'http://shopste.com',
        'message' => 'API TEST'
      )
    ))->execute()->getGraphObject();
    echo "Eintrag auf Facebook veröffentlicht: " . $response->getProperty('id');
  } catch(FacebookRequestException $e) {
    echo "Fehlercode: " . $e->getCode();
    echo " Fehlermeldung: " . $e->getMessage();
  }   
} */
?>
 
<script>
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      testAPI();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '{your-app-id}',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.2' // use version 2.2
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      console.log('Successful login for: ' + response.name);
      document.getElementById('status').innerHTML =
        'Thanks for logging in, ' + response.name + '!';
    });
  }
</script>

<!--
  Below we include the Login Button social plugin. This button uses
  the JavaScript SDK to present a graphical Login button that triggers
  the FB.login() function when clicked.
-->
 
<html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
		<meta content="INDEX,FOLLOW" name="robots">
		<link media="all" href="../css/template_master.css" type="text/css" rel="stylesheet">
		<title>Shopste2Facebook App Freigabe</title>
	</head>
	<body>
 
			<div class="page">
			<div class="block block-cart" id="box_texthtml_21"><div class="block-title"> <h1>Facebook Freigabe für Shopste</h1></div>
			<div class="content" id="modul_texthtml_21"><p>
			<h2>Facebook App Freigabe</h2>
			
			<?php 
				if (!empty($session)) {
					// Logged in
  // Get a list of pages that this user admins; requires "manage_pages" permission
	$request = new FacebookRequest($session, 'GET', '/me/accounts?fields=name,access_token,perms');
	$pageList = $request->execute()
	  ->getGraphObject()
	  ->asArray();
	
	$i = 0;
	echo "<h3>Erfolgreich Facebook mit Shopste verbunden ".$object->getProperty('first_name')." ".$object->getProperty('last_name')."</h3><br/><br/>";
	
	echo "Sie haben auf folgende Facebook Seiten Zugriff. Sie können zwischen Ihrem Profil und Facebook Seiten wählen.<br/><br/>";
	echo '<select name="optDefaultPageToPost">';
	foreach ($pageList as $page) {
	  #$pageAccessToken = $page['access_token'];
	  #echo $pageAccessToken.'<br/><br/>';
	  // Store $pageAccessToken and/or
	  // send requests to Graph on behalf of the page
	  #echo '<pre>';
	  #print_r($page[0]->access_token);
	  for($i=0; $i < count($page); $i++) {
 
		echo '<option value="'.$page[$i]->id.'">'.$page[$i]->name.'</option>';
 
 
	  
	}
}
echo '</select>
<button class="button" name="btnSaveFanpageDefault">Speichern</button>';
				} else {
			?>
				<fb:login-button scope="public_profile,email,manage_pages,publish_pages,publish_stream,publish_actions" onlogin="checkLoginState();">
				</fb:login-button>

				<div id="status">
				</div>
				<?php
				$helper = new FacebookRedirectLoginHelper('http://shopste.com/facebook.php?userid='.$_GET['userid']);
				$scope = array('public_profile,email,manage_pages,publish_pages,publish_actions');
				$loginUrl = $helper->getLoginUrl($scope);
				#$loginUrl = $helper->getLoginUrl();
				echo '<a href="'.$loginUrl.'">Facebook Login</a>';
				
				?>
			<?php 
				}
			?>
			</p></div></div>
			</div>
		 
	</body>
</html>
