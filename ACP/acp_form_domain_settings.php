<?php 
	session_start();
	// Datenbankverbindung
	require_once('../include/inc_config-data.php');
	require_once('../include/inc_basic-functions.php');
	
	$_POST = mysql_real_escape_array($_POST);
	$_GET = mysql_real_escape_array($_GET);

	// Login überprüfen
	$chkCookie = admin_cookie_check();

	#echo $chkCookie;
	/*if($_SESSION['login'] == 1) {	
		$_SESSION['login'] = 1;
	} else {
		exit(0);
	}*/
	
	if(!empty($_POST['domain_startseite'])) {
		# Domain abspeichern
		if($_POST['domain_startseite'] == 'KEINE-AUSWAHL') {
			$_POST['domain_startseite'] = 1;
		}
		if($_POST['domain_warenkorb'] == 'KEINE-AUSWAHL') {
			$_POST['domain_warenkorb'] = 1;
		}	
		if($_POST['domain_zurkasse'] == 'KEINE-AUSWAHL') {
			$_POST['domain_zurkasse'] = 1;
		}	
		if($_POST['domain_agb'] == 'KEINE-AUSWAHL') {
			$_POST['domain_agb'] = 1;
		}	
		if($_POST['domain_widerruf'] == 'KEINE-AUSWAHL') {
			$_POST['domain_widerruf'] = 1;
		}	
		if($_POST['domain_versandkosten'] == 'KEINE-AUSWAHL') {
			$_POST['domain_versandkosten'] = 1;
		}	

		$query = "UPDATE `domains` SET `startseite`='".$_POST['domain_startseite']."',warenkorb_id='".$_POST['domain_warenkorb']."',zurkasse_id='".$_POST['domain_zurkasse']."',agb_id='".$_POST['domain_agb']."',widerruf='".$_POST['domain_widerruf']."',shipping_id='".$_POST['domain_versandkosten']."',bIsShop='".$_POST['domain_isshop']."', system_shop_marktplatz_disable='".$_POST['domain_disable_complete']."',shop_mwst_setting='".$_POST['shop_mwst_setting']."', template_folder='".$_POST['domain_template_folder']."',bWebShopAnimation='".$_POST['shop_animation']."' WHERE  `domain_id`=".$_SESSION['domain_id'].";";
		DBi::$conn->query($query) or die(mysqli_error());

		$query = "UPDATE `shop_item` SET `system_closed_shop`='".$_POST['domain_disable_complete']."' WHERE  `domain_id`=".$_SESSION['domain_id']."; ";
		DBi::$conn->query($query) or die(mysqli_error());
			
		
		$path = getPathUrl($_SESSION['language'],$_SESSION['page_id']);		
		$strLink = 'http://'.$_SERVER['SERVER_NAME'].'/'.$path;
		echo $strLink;
		exit();
	} elseif (!empty($_POST['btnSenden'])) {
		#echo $_POST['shop_animation'];
		$query = "UPDATE `domains` SET bWebShopAnimation='".$_POST['shop_animation']."' WHERE  `domain_id`=".$_SESSION['domain_id'].";";
		DBi::$conn->query($query) or die(mysqli_error());
	}
	$query = "SELECT * FROM domains WHERE domain_id='".$_SESSION['domain_id']."'";
	$resDomainData = DBi::$conn->query($query) or die(mysqli_error());
	$strDomainData = mysqli_fetch_assoc($resDomainData);	
				
	$strOptMenueSelekt2 = menue_generator(0,0,'',0,0,'select',$strDomainData['startseite']);
	$strOptMenueSelekt3 = menue_generator(0,0,'',0,0,'select',$strDomainData['warenkorb_id']);
	$strOptMenueSelekt4 = menue_generator(0,0,'',0,0,'select',$strDomainData['zurkasse_id']);
	$strOptMenueSelekt5 = menue_generator(0,0,'',0,0,'select',$strDomainData['agb_id']);
	$strOptMenueSelekt6 = menue_generator(0,0,'',0,0,'select',$strDomainData['widerruf']);
	$strOptMenueSelekt7 = menue_generator(0,0,'',0,0,'select',$strDomainData['shipping_id']);
	
	$strButtonName = 'Domain Änderungen speichern';
?>
<div id="acp_main_new_domain_form">
	<script>
  $(function() {
    $( "#tabs" ).tabs();
  });
  </script>
<h3>Meta Daten</h3>
Shop erstellt am: <?php echo getDateDE($strDomainData['created_at']) ?><br/>
Shop Einstellungen aktuallisiert am: <?php echo getDateDE($strDomainData['updated_at']) ?><br/>
<div id="tabs"> 
<br/>
  <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
    <li><a href="#tabs-1">Allgemeine Einstellungen</a></li>
    <li><a href="#tabs-2">Shop Inhaber Informationen</a></li>
    <li><a href="#tabs-3">Shop Einstellungen</a></li>
    <li><a href="#tabs-4">Facebook</a></li>
  </ul>
  <div id="tabs-1">
    <h2>Domain Einstellungen</h2>
	<form name="frmPageSetting" id="domain_save" action="/ACP/acp_form_domain_settings.php" method="POST" onSubmit="return domain_save_form('domain_save');">
			<input type="hidden" id="acp_get_modus" name="modus" value="<?php echo $_GET['modus']; ?>"/>
			<div class="label" style="float:left;">Shopdomain:</div>
			<div id="domain_name">
				<?php echo $strDomainData['name']; ?><br/><br/>
			</div>
			<div id="mwst_setting">
				<h3>Mehrwertsteuer Einstellungen</h3>
				<?php 
				if($strDomainData['shop_mwst_setting'] == 'MwSt_inkl') {
					$strMwstinkl = 'checked="checked" ';
				} elseif($strDomainData['shop_mwst_setting'] == 'MwSt_exkl') {
					$strMwstexkl = 'checked="checked" ';
				} elseif($strDomainData['shop_mwst_setting'] == 'MwSt_befreit') {
					$strMwstKleinunternehmer = 'checked="checked" ';
				} elseif($strDomainData['shop_mwst_setting'] == 'MwSt_privatverkauf') {
					$strMwstPrivatverkauf = 'checked="checked" ';
				}
				?>
				<input type="radio" name="shop_mwst_setting" value="MwSt_inkl" <?php echo $strMwstinkl; ?>> Shop inkl. MwSt.<br/>
				<input type="radio" name="shop_mwst_setting" value="MwSt_exkl" <?php echo $strMwstexkl; ?>>Shop exkl. MwSt.<br/>
				<input type="radio" name="shop_mwst_setting" value="MwSt_befreit" <?php echo $strMwstKleinunternehmer; ?>>Kleinunternehmer (befreit)<br/>
				<input type="radio" name="shop_mwst_setting" value="MwSt_privatverkauf" <?php echo $strMwstPrivatverkauf; ?>>Privatverkauf<br/>
				<br/>
			</div>
			<div style="clear:both"></div>
			<div class="label" style="float:left;">Shop Module aktiv?</div>
			<div id="domain_isshop">
			<?php 
			if($strDomainData['bIsShop'] == 'Y') {
				$strSelectedShop = 'checked="checked" ';
			} else {
				$strSelectedNoShop = 'checked="checked" ';
			}
			?>
			<input type="radio" name="domain_isshop" value="Y" <?php echo $strSelectedShop; ?>> Online Shop aktivieren<br>
			<input type="radio" name="domain_isshop" value="N" <?php echo $strSelectedNoShop ?>>Kein Online Shop<br>
			</div>
			<h2>Shop Status<h2/>
			<div id="domain_isshop">
			<?php 
			if($strDomainData['system_shop_marktplatz_disable'] == 'Y') {
				$strSelectedShop_disable_2 = 'checked="checked" ';
			} else {
				$strSelectedShop_disable_1 = 'checked="checked" ';
			}
			?>
			<input type="radio" name="domain_disable_complete" value="Y" <?php echo $strSelectedShop_disable_2; ?>> Shop und Marktplatz deaktivieren<br>
			<input type="radio" name="domain_disable_complete" value="N" <?php echo $strSelectedShop_disable_1; ?>>Shop und Marktplatz aktivieren<br>
			</div>
			<div class="label" style="float:left;">Template Ordner auswählen:</div>
			<div id="module_url_path">
				<select id="domain_template_folder"  name="domain_template_folder" size="1">
					<option value="shopste.com" selected="true">Shopste.com</option>
				</select>
			</div>			
			<div style="clear:both"></div>
			
			<h2>Vordefinierte Webseiten<h2/>
			<div style="clear:both"></div>	
			<div class="label" style="float:left;">Startseite (Seiten-ID):</div>
			<div id="module_url_path">
				<select id="domain_startseite"  name="domain_startseite" size="1">
					<option value="KEINE-AUSWAHL">Keine Auswahl</option>
					<?php 
					 echo $strOptMenueSelekt2;
					?>
				</select>
			</div>			
			<div style="clear:both"></div>
			
			<div class="label" style="float:left;">Warenkorb (Seiten-ID):</div>
			<div id="module_url_path">
				<select id="domain_warenkorb"  name="domain_warenkorb" size="1">
					<option value="KEINE-AUSWAHL">Keine Auswahl</option>
					<?php 
					 echo $strOptMenueSelekt3;
					?>
				</select>
			</div>			
			<div style="clear:both"></div>			
			<div class="label" style="float:left;">Zur Kasse (Seiten-ID):</div>
			<div id="module_url_path">
				<select id="domain_zurkasse"  name="domain_zurkasse" size="1">
					<option value="KEINE-AUSWAHL">Keine Auswahl</option>
					<?php 
					 echo $strOptMenueSelekt4;
					?>
				</select>
			</div>			
			<div style="clear:both"></div>			
			<div class="label" style="float:left;">AGB (Seiten-ID):</div>
			<div id="module_url_path">
				<select id="domain_agb"  name="domain_agb" size="1">
					<option value="KEINE-AUSWAHL">Keine Auswahl</option>
					<?php 
					 echo $strOptMenueSelekt5;
					?>
				</select>			
			</div>				
			<div style="clear:both"></div>						
			<div class="label" style="float:left;">Widerruf (Seiten-ID):</div>
			<div id="module_url_path">
				<select id="domain_widerruf"  name="domain_widerruf" size="1">
					<option value="KEINE-AUSWAHL">Keine Auswahl</option>
					<?php 
					 echo $strOptMenueSelekt6;
					?>
				</select>			
			</div>	
			<div class="label" style="float:left;">Versandkosten (Seiten-ID):</div>
			<div id="module_url_path">
				<select id="domain_versandkosten"  name="domain_versandkosten" size="1">
					<option value="KEINE-AUSWAHL">Keine Auswahl</option>
					<?php 
					 echo $strOptMenueSelekt7;
					?>
				</select>			
			</div>				
			<div style="clear:both"></div>							
			<div class="label" style="float:left;">Eintragen:</div>
			<div id="module_submit">
				<input type="submit" class="button module_form_submit" id="module_form_submit" name="btnSenden" value="<?php echo $strButtonName; ?>">
			</div>		
	</form>
</div>
  <div id="tabs-2">
		<?php 
		 # Domain bestimmmen
		$domain = $_SERVER['HTTP_HOST'];
		$domain = str_replace("www.", "", $domain);
		$query = "SELECT * from domains WHERE name='$domain'";
		$domain_res = mysqli_fetch_assoc(DBi::$conn->query($query));
	
		$query = "SELECT * FROM shop_info WHERE domain_id='".$domain_res['domain_id']."'";
		$resShopInfo = DBi::$conn->query($query) or die(mysqli_error());
		$strShopInfo = mysqli_fetch_assoc($resShopInfo);
			
		?>
    	<form name="frmShopInhaber" id="frmShopInhaber" action="/api.php" method="POST" onSubmit="return shopste_edit_shop_info('frmShopInhaber');">
 
			<h2>Shop Inhaber Informationen</h2>
			Diese Informationen werden für den Betrieb und Verkauf von Shop Artikeln benötigt.<br/>
			<div class="label">Shop Name</div>
			<div><input type="text" value="<?php echo $strShopInfo['shop_name']; ?>" name="txtShopName" id="txtShopName"/></div>
			<div class="label">Firma</div>
			<div><input type="text" value="<?php echo $strShopInfo['firma']; ?>" name="txtFirma" id="txtFirma"/></div>
			<div style="clear:both"></div>
			<div class="label">Email*</div>
			<div><input type="text" value="<?php echo $strShopInfo['email_shop_main']; ?>" name="txtEmail" id="txtEmail"/><span class="frm_error" id="txtEmail_err"></span></div>
			<div style="clear:both"></div>	
			<div class="label">Telefon</div>
			<div><input type="text" value="<?php echo $strShopInfo['telefon']; ?>" name="txtTelefon" id="txtTelefon"/><span class="frm_error" id="txtTelefon_err"></span></div>
			<div style="clear:both"></div>				
			<div class="label">Vorname*</div>
			<div><input type="text" value="<?php echo $strShopInfo['vorname']; ?>" name="txtVorname" id="txtVorname"/><span class="frm_error" id="txtVorname_err"></span></div>
			<div style="clear:both"></div>
			<div class="label">Nachname*</div>
			<div><input type="text" value="<?php echo $strShopInfo['nachname']; ?>" name="txtNachname" id="txtNachname"/><span class="frm_error" id="txtNachname_err"></span></div>
			<div style="clear:both"></div>
			<div class="label">Stra&szlig;e + Hausnummer*</div>
			<div><input type="text" value="<?php echo $strShopInfo['strasse_hnr']; ?>" name="txtStrasse" id="txtStrasse"/><span class="frm_error" id="txtStrasse_err"></span></div>
			<div style="clear:both"></div>
			<div class="label">PLZ*</div>
			<div><input type="text" value="<?php echo $strShopInfo['plz']; ?>" name="txtPLZ" id="txtPLZ"/><span class="frm_error" id="txtPLZ_err"></span></div>
			<div style="clear:both"></div>
			<div class="label">Ort*</div>
			<div><input type="text" value="<?php echo $strShopInfo['stadt']; ?>" name="txtOrt" id="txtOrt"/><span class="frm_error" id="txtOrt_err"></span></div>
			<div style="clear:both"></div>
			<div class="label">Land*</div>
			<div><input type="text"value="<?php echo $strShopInfo['land']; ?>" name="txtLand" id="txtLand" value="Deutschland"/><span class="frm_error" id="txtLand_err"></span></div>
			<div style="clear:both"></div>							
			<div class="label"></div>
			<input type="hidden" name="modus" value="shop_info_edit"/>
			<div><input class="button" type="submit" name="btnSenden" value="Shop Inhaber ändern"/></div>
			<div style="clear:both"></div>
			</form>
  </div>
  <div id="tabs-3">
		<h1>Domain Optionen</h1>
				<?php 
				if($strDomainData['bWebShopAnimation'] == 'shop_animation_on') {
					$strShopAnimationON = 'checked="checked" ';
				} elseif($strDomainData['bWebShopAnimation'] == 'shop_animation_off') {
					$strShopAnimationOFF = 'checked="checked" ';
				} 
				?>	
<form name="frmPageSetting" id="domain_save" action="/ACP/acp_form_domain_settings.php" method="POST" onSubmit="return domain_save_form('domain_save');">				
				<label><input type="radio" name="shop_animation" value="shop_animation_on" <?php echo $strShopAnimationON; ?>> Webshop Animation aktivieren</label><br/>
				<label><input type="radio" name="shop_animation" value="shop_animation_off" <?php echo $strShopAnimationOFF; ?>>Webshop Animationen deaktivieren</label><br/>
				<input class="button" type="submit" name="btnSenden" value="Shop Einstellungen speichern"/>
				</form>
  </div>
  <div id="tabs-4">
    <h1>Facebook Login</h1>
	<?php 
	#define('FACEBOOK_SDK_V4_SRC_DIR', '/var/www/vhosts/shopste.com/httpdocs/framework/Facebook/');
#require __DIR__ . '/framework/Facebook/autoload.php';
set_include_path("/var/www/vhosts/shopste.com/httpdocs/framework/Facebook/");

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

// init app with app id (APPID) and secret (SECRET)
FacebookSession::setDefaultApplication('102136003453690','35450212093a4f87fefc3e574d82914d');

// Add `use Facebook\FacebookRedirectLoginHelper;` to top of file
$helper = new FacebookRedirectLoginHelper('https://shopste.com/facebook.php');
try {
  $session = $helper->getSessionFromRedirect();
} catch(FacebookRequestException $ex) {
  // When Facebook returns an error
} catch(\Exception $ex) {
  // When validation fails or other local issues
}
if ($session) {
  // Logged in
  // Get a list of pages that this user admins; requires "manage_pages" permission
	$request = new FacebookRequest($session, 'GET', '/me/accounts?fields=name,access_token,perms');
	$pageList = $request->execute()
	  ->getGraphObject()
	  ->asArray();
	
	$i = 0;
	
	foreach ($pageList as $page) {
	  #$pageAccessToken = $page['access_token'];
	  #echo $pageAccessToken.'<br/><br/>';
	  // Store $pageAccessToken and/or
	  // send requests to Graph on behalf of the page
	  #echo '<pre>';
	  #print_r($page[0]->access_token);
	  for($i=0; $i <= count($page); $i++) {
		print_r($page[$i]->name);
		if($page[$i]->name == 'Shopste.com') {
		  try {
			$response = (new FacebookRequest(
			  $session, 'POST', '/'.$page[$i]->id.'/feed', array(
				'access_token' => $page[$i]->access_token,
				'link' => 'https://shopste.com',
				'message' => 'API Shopste2Facebook'
			  )
			))->execute()->getGraphObject();
			echo "Eintrag auf Facebook veröffentlicht: " . $response->getProperty('id');
		  } catch(FacebookRequestException $e) {
			echo "Fehlercode: " . $e->getCode();
			echo " Fehlermeldung: " . $e->getMessage();
		  }  
	  }
	  }
	  #print_r($page);
	  #echo '</pre>';
	  $i++;
	}
	
	
	
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

<fb:login-button scope="public_profile,email,manage_pages,publish_pages,publish_stream,publish_actions" onlogin="checkLoginState();">
</fb:login-button>

<div id="status">
</div>
<?php
$query ="SELECT * FROM benutzer WHERE id='".$_SESSION['user_id']."'";
#echo $query;
$strUser = mysqli_fetch_assoc(DBi::$conn->query($query));

if(empty($strUser['email_crc'])) {
	#$strUser['email_crc'] = "-1";
}
// Add `use Facebook\FacebookRedirectLoginHelper;` to top of file
$helper = new FacebookRedirectLoginHelper('https://shopste.com/facebook.php?userid='.$strUser['email_crc']);
$scope = array('public_profile,email,manage_pages,publish_pages,publish_actions');
$loginUrl = $helper->getLoginUrl($scope);
#$loginUrl = $helper->getLoginUrl();
// Use the login url on a link or button to 
echo '<a href="'.$loginUrl.'" class="spanlink">Facebook Login (anklicken)</a>';
// redirect to Facebook for authentication
?>
</div>
	
</div>