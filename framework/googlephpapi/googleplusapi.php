<?php 
	if(isset($_GET['version']) == '2') {
?>
<!--
  Copyright (c) 2011 Google Inc.
  Licensed under the Apache License, Version 2.0 (the "License"); you may not
  use this file except in compliance with the License. You may obtain a copy of
  the License at
  http://www.apache.org/licenses/LICENSE-2.0
  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
  WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
  License for the specific language governing permissions and limitations under
  the License.
  To run this sample, replace YOUR API KEY with your application's API key.
  It can be found at https://code.google.com/apis/console/?api=plus under API Access.
  Activate the Google+ service at https://code.google.com/apis/console/ under Services
-->
<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8' />
  </head>
  <body>
    <!--Add a button for the user to click to initiate auth sequence -->
    <button id="authorize-button" style="visibility: hidden">Authorize</button>
    <script type="text/javascript">
      // Enter a client ID for a web application from the Google Developer Console.
      // The provided clientId will only work if the sample is run directly from
      // https://google-api-javascript-client.googlecode.com/hg/samples/authSample.html
      // In your Developer Console project, add a JavaScript origin that corresponds to the domain
      // where you will be running the script.
      var clientId = '475139734714-lfid7d94bb487vjm6o7recg2dejakh66.apps.googleusercontent.com';
      // Enter the API key from the Google Develoepr Console - to handle any unauthenticated
      // requests in the code.
      // The provided key works for this sample only when run from
      // https://google-api-javascript-client.googlecode.com/hg/samples/authSample.html
      // To use in your own application, replace this API key with your own.
      var apiKey = 'AIzaSyDyDbTyycM4PeT4VJ_JYX7fa0IKZ2ZTz7Q';
      // To enter one or more authentication scopes, refer to the documentation for the API.
      var scopes = 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email';
      // Use a button to handle authentication the first time.
      function handleClientLoad() {
        gapi.client.setApiKey(apiKey);
        window.setTimeout(checkAuth,1);
      }
      function checkAuth() {
        gapi.auth.authorize({client_id: clientId, scope: scopes, immediate: true}, handleAuthResult);
      }
      function handleAuthResult(authResult) {
        var authorizeButton = document.getElementById('authorize-button');
        if (authResult && !authResult.error) {
          authorizeButton.style.visibility = 'hidden';
          makeApiCall();
        } else {
          authorizeButton.style.visibility = '';
          authorizeButton.onclick = handleAuthClick;
        }
      }
      function handleAuthClick(event) {
        gapi.auth.authorize({client_id: clientId, scope: scopes, immediate: false}, handleAuthResult);
        return false;
      }
      // Load the API and make an API call.  Display the results on the screen.
      function makeApiCall() {
      
	  /*      function makeApiCall() {
        gapi.client.load('plus', 'v1', function() {
          var request = gapi.client.plus.people.get({
            'userId': 'me'
          });
          request.execute(function(resp) {*/
 
			 // Laden der oauth2-Bibliotheken, um die userinfo-Methoden zu akitvieren.
			gapi.client.load('oauth2', 'v2', function() {
				var request = gapi.client.oauth2.userinfo.get();
				request.execute(function(resp) {
				alert(JSON.stringify(resp));
				console.log(JSON.stringify(resp));
				var heading = document.createElement('h4');
				var image = document.createElement('img');
				image.src = resp.picture;
				heading.appendChild(image);
				heading.appendChild(document.createTextNode(resp.name));
				document.getElementById('content').appendChild(heading);
				document.getElementById('content2').appendChild(resp.nickname);
				 });
			});
		
          
 
      }
    </script>
    <script src="https://apis.google.com/js/client.js?onload=handleClientLoad"></script>
    <div id="content"></div>
    <div id="content2"></div>
    <p>Retrieves your profile name using the Google Plus API.</p>
  </body>
</html>
<?php
	} else {
?>
<!-- Der Anfang der Datei index.html -->
<html itemscope itemtype="http://schema.org/Article">
<head>
  <!-- ANFANG der Voraussetzungen -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js">
  </script>
  <script type="text/javascript">
    (function () {
      var po = document.createElement('script');
      po.type = 'text/javascript';
      po.async = true;
      po.src = 'https://plus.google.com/js/client:plusone.js?onload=start';
      var s = document.getElementsByTagName('script')[0];
      s.parentNode.insertBefore(po, s);
    })();
  </script>
  <!-- ENDE der Voraussetzungen -->
</head>
<body>
<!-- Fügen Sie hinzu, wo Ihre Anmeldeschaltfläche gerendert werden soll -->
<div id="signinButton">
  <span class="g-signin"
    data-scope="https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.stream.write https://www.googleapis.com/auth/plus.circles.read"
    data-clientid="489734902428-cf27amn9t3lha06dtcekheo795jjjlc7.apps.googleusercontent.com"
    data-redirecturi="postmessage"
    data-accesstype="offline"
    data-cookiepolicy="single_host_origin"
    data-callback="signInCallback">
  </span>
</div>
<div id="result"></div> 
<a href="">Login</a>
<!-- ... -->
<!-- Letzter Teil des BODY-Elements in Datei index.html -->
<script type="text/javascript">
function signInCallback(authResult) {
  if (authResult['code']) {
		// Nach der Autorisierung des Nutzers nun die Anmeldeschaltfläche ausblenden, zum Beispiel:
		$('#signinButton').attr('style', 'display: none');

		//alert(authResult['access_token']);
		$.getJSON('https://www.googleapis.com/plus/v1/people/me/?access_token=' + authResult['access_token'] + "&alt=json&callback=?", function(result){
					console.log(JSON.stringify(result));
					//alert(jQuery.type(result.emails.value) );
				 				
					//alert(result.birthday); 
					
					$( "#result" ).load( "/framework/googlephpapi/google-api.php?person_id=" + result.id + "&storeToken=" + authResult['access_token'] + "&code=" + authResult['code'], function() {

							$.ajax(
							{
								url : '/framework/googlephpapi/google-api.php',
								type: "GET",
								data : "person_id=" + result.id + "&name=" + result.displayName + "&geburtstag=" + result.birthday + "&gender=" + result.gender + "&url=" + result.url + "&hasApp=" + result.hasApp + "&aboutMe=" + result.aboutMe + "&relationshipStatus=" +result.relationshipStatus + "&verified=" + result.verified + "&circledByCount=" + result.circledByCount + "&plusOneCount=" + result.plusOneCount + "&isPlusUser=" + result.isPlusUser + "&objectType=" + result.objectType + "&email=" + result.emails[0].value + "&email_type=" + result.emails[0].type + "&vorname=" + result.name.givenName + "&nachname=" + result.name.familyName + "&displayName=" + result.displayName + "&imageurl=" + result.image.url + "&currentLocation=" + result.currentLocation + "&language=" + result.language + "&ageRange_min=" + result.ageRange.min + "&ageRange_max=" + result.ageRange.max + "&nickname=" + result.nickname + "&tagline=" + result.tagline + "&update=1",
								success:function(data, textStatus, jqXHR)
								{
										alert(result.emails[0].value);
										$("#txtEmail").val(result.emails[0].value);
										$("#txtVorname").val(result.name.givenName);
										$("#txtNachname").val(result.name.familyName);
										$("#txtRegUsername").val(result.displayName);
										$("#result").html('<h2>Bitte das Formular zuende ausfüllen</h2>');
							 
									return false;
								},
								error: function(jqXHR, textStatus, errorThrown)
								{
								   alert(data + ' ' + errorThrown);
								}
							});
						
					});
		});

 

  }
}
  
 
</script>
</body>
</html>
 <?php 
 
	}
	?>