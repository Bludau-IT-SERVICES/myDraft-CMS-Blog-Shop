<?php session_start(); ?> 
<?php header("Content-type: application/javascript"); ?>



//JB Opening
//    return $.inArray(d.getDay(), [1, 2, 3, 4, 5]) 
//            && hours >= 9   
//            && (hours < 17 || hours === 16 && mins <= 45);

function isWorkingHour() {
    var d = new Date(), // current time
        hours = d.getHours(),
        mins = d.getMinutes();
	return true;
}
$(document).ready(function() {
	
	$(".Sende_Chat").click(function(){
		console.log("Klick Event vom Button ausgeschlösst");
		SendMessage2Chat();
	});
	
}); 

function callBasicView_startpage(msg,bError) {
	//alert(msg);
	var isOpen = isWorkingHour(); 
	var strChatting;
	if(isOpen) {
		strChatting = '<span id="chat_room_status"> <a onClick="javascript:call2chat(\'step1\')"><img id="call2action_live_chatten" src="/templates/tsecurity.de/media/live-chatten.png" border="0" style="" class="wnd shake"/></a></span>';
	} else {
		strChatting = '<span id="chat_room_status"><img id="call2action_live_chatten" src="/templates/tsecurity.de/media/live-chat-Eng-grau.png" border="0" style="" class="wnd_inactive shake"/></span>';
	}
	
	//<img src="/templates/tsecurity.de/media/Sprechblase.png"  id="guy-sprechblase" border="0">
	
	$("#call2action").html('<div id="call2action_sub"><center style="margin-top: 5px;margin-bottom: -4px;height:80px">' + strChatting + '<a onClick="javascript:call2me();"><img id="call2action_anrufen" src="/templates/tsecurity.de/media/anrufen.png" border="0" style="" class="wnd shake"/></a></center><br/><br/><div class="channelist"></div><div id="optChannel_send" style="display:none"><input type="submit" onclick="javascript:call2chat(\'step3\')" stlye="display:none" class="Sende_Chat_step2 btn btn-primary" style="display:none" name="btnSenden" value="Zum Chat"><br/><br/><br/></div><div id="call_guy">' +  msg + '</div><img id="call_guy_bubble" src="/templates/tsecurity.de/media/Sprechblase-trichter.png"/><input type="file" style="display:none" id="siofu_input" /><div style="display:none" id="file_drop"></div></div>');
}

function callBasicView() {
	
	
  $("#call2action").css({marginLeft:"200px"});
  $("#call2action").animate({ marginLeft: "0"} , 500);
  
      
	socket.emit('register', localStorage.getItem('uUID'),window.location,'',"<?php echo $_COOKIE['room']; ?>","<?php echo $_COOKIE['txtNickname']; ?>",'false','<?php echo $_SESSION['page_id']; ?>','<?php echo  $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] .'?'. $_SERVER['QUERY_STRING']; ?>');
	  
	console.log("Lade Chatfenster...");
	<?php if($_COOKIE['vorgang_id'] > 0) { ?>
		call2chat('step3');
	<?php } else if($_COOKIE['room'] != '') { ?> 
		 
		call2chat('step3');
		 
	<?php }  else { ?>
		 callBasicView_startpage('Was kann ich f&uuml;r Sie tun?',false);

	<?php } ?>
}

function getCharCouting() {
	var strChars = $(".txtMessage0").val();
	if(strChars.length > 40) {
	//console.log(strChars.length);
		$(".txtMessage0").css({"font-size":"12px"})
	} else {
		$(".txtMessage0").css({"font-size":"14px"})
	} 
}

//window.call2chat = function (){
window.call2chat = function(step) {
	
	// All Data out of Step 1 is there
	
	if(step == "step2") {
			// Check if Input Chatname is filled
			if($(".usernameInput").val() == "") {
				$(".setStep").val("1");
				step = 'step1';
				alert("Bitte Chatnamen eingeben.");
			}
			
		$("#call2action").animate({height:"530px"});
	} else {
		$("#call2action").animate({height:"310px"});
	}
	
	$("#call_guy_bubble").css({display:"none"});
	$("#call_guy").css({border:"1px solid #D13575"});
	$("#call_guy").css({padding:"8px"}); 
	  
	if(isWorkingHour()) {
		
	
		
		<?php
		if(empty($_COOKIE['vorgang_id'])) {
			$_COOKIE['vorgang_id'] = '""';
		}
		?>
		
		//socket.emit('fetch channellist', username);
		//setUsername();
		
		
		if(<?php echo $_COOKIE['vorgang_id'] ?> != '') {  
		
			var strMessage = '<div class="chatArea"></div><div class="channelist"></div><input type="text" class="usernameInput" name="txtNickname" placeholder="Bitte Nickname eingeben..." value="<?php echo $_COOKIE['txtNickname']; ?>" style="display:none"><input type="hidden" class="private_chat" value="<?php echo $_COOKIE['dest_browserid'] ?>"/><input type="hidden" class="isPrivat" value="true"/><input type="hidden" class="private_chat_vorgang" value="<?php echo $_COOKIE['vorgang_id']; ?>"/><input type="text" style="display:none" class="txtMessage_privat" name="txtMessage_privat"><br/><input type="file" style="display:none" id="siofu_input" /><select style="display:none;" class="optChannel"><option value="<?php echo $_COOKIE['optChannel'] ?>" selected="true"><?php echo $_COOKIE['optChannel'] ?></select><div style="display:none" id="file_drop"></div>'; 
			
			// Initalisiert den Chat
			//$(".private_chat").val(browserid);
			
			callBasicView_startpage(strMessage,false);

			$("#step1").css({display:"none"});
			$(".channelist").css({display:"none"});
			$("#optChannel_send").css({display:"none"});
			$(".Sende_Chat_step2 ").css({display:"none"});
			
			$("#call2chat_msg").html('<div class="msg_chat_header"><span id="setHeaderMsg">Willkommen im maiwell Chat</span><label><input type="checkbox" value="Y" name="chkSounds" id="chkSoundOn" checked="true" style="display:none" class="Sende_Chat_abort"></label><input type="button" onclick="javascript:SendCancelChat()" class="Sende_Chat_abort btn btn-primary" name="btnAbort" value="X"></div><ul class="messages"></ul><textarea name="txtMessage" style="display:none" cols="40" rows="2" placeholder="Ihre Nachricht..." onKeyUp="javascript:getCharCouting()" class="txtMessage0"></textarea><input type="submit" onclick="javascript:SendMessage2Chat()" class="Sende_Chat_send_message btn btn-primary" name="btnSenden" value=">>">');
			
			$("#call_guy").css({height:"",padding:"10px"});
			$("#call_guy_bubble").css({top:"198px",display:"",position:"absolute"});
			$("#call2chat_msg").animate({height:"51em"}); 
			$(".chatArea").html("<p>Willkommen im maiwell Chat.</p>");

			$(".Sende_Chat_step2").css({display:"none"});
			$(".setStep").val("3");  
			$("#call_guy").css({width:"88%"});
			$(".txtMessage0").css({display:"block"});
					
			// JB A
			setUsername(); 
			
		// Channel Mode
		} else if ("<?php echo $_COOKIE['room'] ?>" != '') {
			
			var strMessage = '<div class="chatArea"></div><div class="channelist"></div><input type="text" class="usernameInput" name="txtNickname" placeholder="Bitte Nickname eingeben..." value="<?php if(isset($_COOKIE['txtNickname'])) { echo $_COOKIE['txtNickname']; } else { echo $_SESSION['Kunde']->cMail; } ?>" style="display:none"><input type="hidden" class="private_chat" value=""/><input type="hidden" class="isPrivat" value="true"/><input type="hidden" class="private_chat_vorgang" value=""/><input type="text" style="display:none" class="txtMessage_privat" name="txtMessage_privat"><br/><input type="file" style="display:none" id="siofu_input" /><select style="display:none;" class="optChannel"><option value="<?php echo $_COOKIE['optChannel'] ?>" selected="true"><?php echo $_COOKIE['optChannel'] ?></select><div style="display:none" id="file_drop"></div>'; 
			
			// Initalisiert den Chat
			//$(".private_chat").val(browserid);
			
			callBasicView_startpage(strMessage,false);

			$("#step1").css({display:"none"});
			$(".channelist").css({display:"none"});
			$("#optChannel_send").css({display:"none"});
			$(".Sende_Chat_step2 ").css({display:"none"});
			
			$("#call2chat_msg").html('<div class="msg_chat_header"><span id="setHeaderMsg" style="margin-left:5px;">Willkommen im maiwell Chatraum</span><input type="checkbox" value="Y" name="chkSounds" style="display:none" id="chkSoundOn" checked="true" class="Sende_Chat_abort"></label><input type="submit" onclick="javascript:SendCancelChat()" class="Sende_Chat_abort btn btn-primary" name="btnAbort" value="X"></div><ul class="messages"></ul><textarea name="txtMessage" style="display:none" cols="40" rows="2" placeholder="Ihre Nachricht..." onKeyUp="javascript:getCharCouting()" class="txtMessage0"></textarea><input type="submit" onclick="javascript:SendMessage2Chat()" class="Sende_Chat_send_message btn btn-primary" name="btnSenden" value=">>">');
			
			$("#call_guy").css({height:"",padding:"10px"});
			$("#call_guy_bubble").css({top:"198px",display:"",position:"absolute"});
			$("#call2chat_msg").animate({height:"51em"}); 
			$(".chatArea").html("<p>Willkommen im maiwell Chat.</p>");

			$(".Sende_Chat_step2").css({display:"none"});
			$(".setStep").val("3");  
			$("#call_guy").css({width:"88%"});
			$(".txtMessage0").css({display:"block"});
					
			// JB A
			setUsername(); 
			
		// Normal Modus
		} else {
			if(step == 'step1') {				
			//<input type="text" style="display:none" placeholder="Nachricht..." class="txtMessage0" name="txtMessage">
			
			var kunden_name = $("#kunden_vorname").val() + ' ' + $("#kunden_nachname").val();

			if('<?php echo $_COOKIE['txtNickname']; ?>' != '') {
				kunden_name = '<?php echo $_COOKIE['txtNickname']; ?>';
			}
			
			var strMessage = '<div id="step1"></div><div class="chatArea"></div><input type="text" class="usernameInput" name="txtNickname" placeholder="Chatname eingeben..." value="' + kunden_name +'"><input type="hidden" class="isPrivat" name="isPrivat" value="false"/><input type="hidden" name="setStep" class="setStep" value="1"/><input type="hidden" name="private_chat" class="private_chat" value=""/><input type="hidden" name="private_chat_vorgang" class="private_chat_vorgang" value=""/><input type="text" style="display:none" class="txtMessage_privat" name="txtMessage_privat"><br/><input type="submit" onclick="javascript:call2chat(\'step2\')" class="Sende_Chat btn btn-primary" name="btnSenden" value=">>"><div id="step2"></div>'; 
			
				callBasicView_startpage(strMessage,false);
				$("#call_guy_bubble").css({position:"absolute",top:"182px"});
					$("#call2action_email_schreiben").animate({ width: "60px",height:"60px"} , 500);
		$("#call2action_anrufen").animate({ width: "60px",height:"60px"} , 500);
		$("#call2action_live_chatten").animate({ width: "85px",height:"85px"} , 500);
		 
		$("#call_guy").hide();
		$("#call_guy").fadeIn(1500, function() {
			// Animation complete
		});
			} else if(step == 'step2') {
				$(".usernameInput").css({display:"none"});
				$(".txtMessage0").css({position: "relative",top: "-85px",left: "0px",height:"30px"});
				
				$("#step1").html('<strong id="strg_channel_info">Bitte w&auml;hlen Sie Ihr Anliegen aus</strong><img id="bubble_free" src="/templates/tsecurity.de/media/Sprechblase-trichter.png"/>');

				
				$("#step1").animate({height: ""});
				$("#bubble_free").css({position: "relative",top: "4px",left: "100px"});
				$("#bubble").css({display:"none"});
				$("#optChannel_send").css({display:"block"});
				$(".Sende_Chat_step2").css({right:"25px",marginTop:"12px",display:"block"});
				$(".Sende_Chat").css({display:"none"});
				
				$(".setStep").val("2");
				
				// Receive Channellist 
				getChannellist("<?php echo $_COOKIE['optChannel'] ?>","<?php echo $_SERVER['SERVER_NAME']; ?>");
			
			} else if (step == 'step3') {
				setUsername();
				$("#step1").css({display:"none"});
				$(".channelist").css({display:"none"});
				$("#optChannel_send").css({display:"none"});
				$(".Sende_Chat_step2 ").css({display:"none"});
				$("#call2chat_msg").html('<div class="msg_chat_header"><span id="setHeaderMsg">Willkommen im maiwell Chat</span><input type="checkbox" value="Y" name="chkSounds" id="chkSoundOn" checked="true" style="display:none" class="Sende_Chat_abort"></label><input type="submit" onclick="javascript:SendCancelChat()" class="Sende_Chat_abort btn btn-primary" name="btnAbort" value="X"></div><ul class="messages"></ul><textarea name="txtMessage" style="display:none" cols="40" rows="5" placeholder="Ihre Nachricht..." onKeyUp="javascript:getCharCouting()" class="txtMessage0"></textarea><input type="submit" onclick="javascript:SendMessage2Chat()" class="Sende_Chat_send_message btn btn-primary" name="btnSenden" value=">>">');
				 
				
				$.post( "chat-client-storage.php", { session_store: "true", modus:"room" ,session_value: $('.optChannel option').filter(':selected').val()})
					.done(function( data ) {  
					//alert( "Data Loaded: " + data );
				});	
	
				
				$("#call_guy").css({height:"",padding:"10px"});
				$("#call_guy_bubble").css({top:"197px",display:"",position:"absolute"});
				$("#call2chat_msg").animate({height:"51em"}); 
				$(".chatArea").html("<p>Willkommen im maiwell Chat.</p>");

				$(".Sende_Chat_step2").css({display:"none"});
				$(".setStep").val("3");  
				$("#call_guy").css({width:"88%"});
				$(".txtMessage0").css({display:"block"});
			} 
			// 
			//
			
			
			//$("#call_guy").html('<div class="chatArea"><ul class="messages"></ul></div><input type="text" class="usernameInput" name="txtNickname" placeholder="Bitte Nickname eingeben..." value="<?php echo $_COOKIE['txtNickname']; ?>"><input type="hidden" class="isPrivat" value="false"/><input type="hidden" class="private_chat" value=""/><input type="hidden" class="private_chat_vorgang" value=""/><input type="text" style="display:none" class="txtMessage_privat" name="txtMessage_privat"><br/><br/><input type="submit" class="Sende_Chat btn btn-primary" name="btnSenden" value="In Raum Anmelden"><strong id="strg_channel_info"><br/><br/>Bitte w&auml;hlen Sie Ihr Anliegen aus</strong><div class="channelist"></div>'); 
		
			// Initalisiert den Chat
			// setUsername(); 
		
		
		}
	} else {
		alert("Chatten nur zwischen Mo. - Fr. 9:00 Uhr - 16:45 Uhr.");
	}
}

function call2me() { 

	$("#call_guy").html("Sie erreichen uns Mo.-Fr. 9-17 Uhr u. So. 10-17 Uhr  unter <br/>0441 - 2 3333 05");
	$("#call_guy").css({border: "1px solid #D13575",borderradius:"15px"});
		
	//$("#call_guy").css({marginLeft:"200px",maxheight:"60px"});
	//$("#call_guy").animate({ marginLeft: "0"} , 500);

	$(".optChannel").css({display:"none"});
	$(".Sende_Chat_step2").css({display:"none"});
	
	$("#call2action_email_schreiben").animate({ width: "60px",height:"60px"} , 500);
	$("#call2action_anrufen").animate({ width: "85px",height:"85px"} , 500);
	$("#call2action_live_chatten").animate({ width: "60px",height:"60px"} , 500);

	$("#maiwell-guy-img").css({display:""});
	$("#call_guy_bubble").css({display:""});
	$("#call_guy_bubble").css({top:"198px"});
  
	$("#maiwell-guy-img").hide();
	
	$("#maiwell-guy-img").fadeIn(700, function() {
		// Animation complete
	});
	
	$("#call_guy_bubble").hide();
	
	$("#call_guy_bubble").fadeIn(1500, function() {
		// Animation complete
	});
	
	$("#call_guy").hide();
	$("#call_guy").fadeIn(1500, function() {
	
		// Animation complete
	});
}

function call2email() {
	
	
	$("#call_guy").css({left:"17px"});
	$("#call2action").css({height:""});
	$("#guy-sprechblase").css({display:"none"});
	$("#maiwell-guy-img").css({display:"none"});
	$("#call_guy_bubble").css({display:"none"});
	$(".optChannel").css({display:"none"});
	$(".Sende_Chat_step2").css({display:"none"});
	$("#call_guy").css({border:"0px solid #FFF"});
	
	// Main Window 
	//$("#call2action").css({ width: "321px"} , 500);
	//$("#call2action").css({ height: "317px"} , 500);
	
	
	
	//$("#call2action").animate({ marginLeft: "0"} , 500);
  
	$("#call2action_email_schreiben").animate({ width: "85px",height:"85px"} , 500);
	$("#call2action_anrufen").animate({ width: "60px",height:"60px"} , 500);
	$("#call2action_live_chatten").animate({ width: "60px",height:"60px"} , 500);
	
	//$("#maiwell-guy-img").animate({ marginTop: "200px"} , 500);
	var email = $("#kunden_email").val();
	var jtl_token = $("#jtl_token").val();
	var strAppEmail = '<div id="chat_email_schreiben"><form style="margin-top: -17px;" onsubmit="return call2email_send(\'http://jtl-shop.bludau-media.de/Kontakt\')" id="email_schreiben_form" name="contact" action="/Kontakt" method="post">' + 
                '<input type="hidden" class="jtl_token" name="jtl_token" value="' + jtl_token + '"> ' +
                '<fieldset> ' +
	'<div class="row" style="margin-bottom:-17px">' +
	'<div class="col-xs-12 col-md-6">' +
'<div class="form-group required">' +
    '<input type="email" name="email"  id="chat_email_eingabe" class="form-control" value="' + email + '" placeholder="Ihre Emailaddresse..." required="" data-cip-id="email">' +
    '</div>' +
                        '</div>' +
                    '</div>' +
                '</fieldset>' +
                '<fieldset>' +
					'<div class="row">' +
                            '<div class="col-xs-12 col-md-6">' +
                                '<div class="form-group float-label-control required">' +
                                    '<label style="display:none" for="subject" class="control-label">Betreff</label>' +
                                    '<select style="display:none" class="form-control" name="subject" id="subject" required="">' +
                                        '<option value="" selected="" disabled="">Betreff</option>' +
                                                                                    '<option selected=true value="4">Bludau Media Testshop / Devshop</option>' +
                                    '</select>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                                        '<div class="row">' +
                        '<div class="col-xs-12 col-md-12">' +
                            '<div class="form-group float-label-control required">' +
                                '<textarea name="nachricht" placeholder="Ihre Nachricht..." class="form-control" rows="10" id="chat_message_schreiben" required=""></textarea>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</fieldset>' +
                    '<div class="row">' +
                        '<div class="col-xs-12 col-md-12">' +
                                                        '<div class="g-recaptcha" data-sitekey=""></div>' +
                        '</div>' +
                    '</div>' +
                                '<input type="hidden" name="kontakt" value="1">' +
                '<button style="width:231px;border-radius: 4px;" type="submit" class="btn btn-primary">Nachricht senden</button>' +
            '</form></div>';
	$("#call_guy").html(strAppEmail);
	
	
    //$("#chat_email_schreiben").css({marginLeft:"300px"});
	//$("#chat_email_schreiben").animate({ marginLeft: "0"} , 500);
	$("#chat_email_eingabe").css({width:"233px"});
	$("#chat_email_schreiben").hide();
	$("#chat_email_schreiben").fadeIn(1500, function() {
		// Animation complete
	});
	
}

function call2email_send(url) {
	var Fields = $("#email_schreiben_form").serialize();
	//alert(Fields);
	
	$.post(url, Fields)
		.done(function( data ) {
			var bError = false;
			var iPosBeginn = data.indexOf("class=\"alert alert-success",0);
			
			if(iPosBeginn == -1) {
				bError = true;
				var iPosBeginn = data.indexOf("class=\"alert alert-danger",0);
			}
			
			var iPosEnd = data.indexOf("</div>",iPosBeginn);
			
			//alert(iPosBeginn + " " + iPosEnd + " | " +(iPosEnd - iPosBeginn));
			
			var strMessage = data.substr(iPosBeginn,(iPosEnd - iPosBeginn));
			if(bError == false) {
				strMessage = strMessage.replace('class=\"alert alert-success">','');
			} else {
				strMessage = strMessage.replace('class=\"alert alert-danger">','');
			}
			
			callBasicView_startpage(strMessage,bError);
			
			//alert(strMessage);
			
			return false; 
	});	
	return false;
}


$(function() {  


 
//$(document).ready(function() {
  var FADE_TIME = 150; // ms
  var TYPING_TIMER_LENGTH = 400; // ms
  var COLORS = [
    '#e21400', '#91580f', '#f8a700', '#f78b00',
    '#58dc00', '#287b00', '#a8f07a', '#4ae8c4',
    '#3b88eb', '#3824aa', '#a700ff', '#d300e7'
  ]; 

  // Initialize variables
  var $window = $(window);
  var $usernameInput = $('.usernameInput'); // Input for username
  var $messages = $('.messages'); // Messages area
  var $inputMessage = $('.txtMessage0'); // Input message input box

  var $loginPage = $('.login.page'); // The login page
  var $chatPage = $('.chat.page'); // The chatroom page

  // Prompt for setting a username
  var username;
  var connected = false;
  var typing = false;
  var lastTypingTime;
  var $currentInput = $usernameInput.focus(); 
  
  //var socket = io({transports: ['websocket'], upgrade: false});
 
try {
var socket = io.connect('https://tsecurity.chattet.de:1337',{secure:true});
}
catch(err) {
  console.log(err);
}
try {
 var uploader = new SocketIOFileUpload(socket);
}
catch(err) {
  console.log(err);
}
    
  //uploader.listenOnInput(document.getElementById("siofu_input"));
  //uploader.listenOnDrop(document.getElementById("file_drop")); 

// When the client starts, create the uid.
console.log('USER-CLIENT-ID: ' + localStorage.getItem('uUID'));
if(localStorage.getItem('uUID') == '' || localStorage.getItem('uUID') == null) {
	var uid = Math.random().toString(24); // + new Date();
	console.log('GEN User ID: ' + uid);
	localStorage.setItem('uUID', uid);
}
 
$('.submit').click(function(){
	var price = $('.price ').html();
	var title = $('.product-title').html();
	var sku = $('.product-sku').html();
	var dest_browserid = $('.private_chat').val();
	var room = $('.channel_room').val();
   
    var bIsPrivat = 'false';
    if($(".isPrivat").val() == 'true') {
		bIsPrivat = 'true';
	}
	console.log("Warenkorbereignis..");
	socket.emit('add 2 basket', {title:title,sku:sku,price:price,dest_browserid:localStorage.getItem('uUID'),room:room,isPrivat:bIsPrivat,username:$(".usernameInput").val()});
	//alert(title + ' ' + price + ' / ' + sku);
});  


function fetch_session() {
	$.post( "chat-client-storage.php", { session_store: "true", modus:"get vorgang_id"})
		.done(function( data ) {
			alert( ">> Data Loaded: " + data );
	});	
}

  function addParticipantsMessage (data) {
   /* var message = '';
    if (data.numUsers === 1) {
      message += "Chatroom Teilnehmer: 1";
    } else {
      message += "Chatroom Teilnehmer: " + data.numUsers;
    }
    log(message); */
  }


  // Sets the client's username
window.setUsername  = function (){

    console.log("Connection Init...." + localStorage.getItem('uUID'));
    // Emit the UID right after connection
	// JB 
	
	var room;
	var room_tmp;
	room = $('.optChannel option').filter(':selected').val();
	
	username = cleanInput($(".usernameInput").val());
	
	//user_uid, url,dest_browserid,room,username,isSupporter
	socket.emit('register', localStorage.getItem('uUID'),window.location,$(".private_chat").val(),room,username,'false','<?php echo $_SESSION['page_id']; ?>','<?php echo  $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING']; ?>'); 
	console.log('<?php echo  $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING']; ?>');
	   
    console.log("Setzte Usernamen..");
	//username = cleanInput($usernameInput.val());
	console.log("BenutzerID: " + $(".usernameInput").val());
	//username = cleanInput($usernameInput.val().trim());
	/*$(".optChannel option:selected").each(function() {
	room_tmp = $( this ).text(); 
	if(typeof room_tmp === 'undefined') {		  
	} else {
	room += $( this ).text() + " ";
	}
	*/
	
	
	
		
	  
	  
    //});
	
	//alert(room);
    // If the username is valid
	
    if (username) {
      //JB
	  //socket.emit('login', username);
	  
	  $loginPage.fadeOut();
      $chatPage.show();
      $loginPage.off('click');
      $currentInput = $(".txtMessage0").focus();

	  
	// Save Nickname 2 COOKIE
	$.post( "chat-client-storage.php", { session_store: "true", modus:"txtNickname", session_value: username})
		.done(function( data ) {
			//alert( "Data Loaded: " + data );
	});
	
      // Tell the server your username
		// JB Data
	  //connected = true;   
		if(localStorage.getItem('uUID') == '' || localStorage.getItem('uUID') == null) {
			var uid = Math.random().toString(24); // + new Date();
			console.log('GEN User ID: ' + uid);
			localStorage.setItem('uUID', uid);
		}
	
	//Reconnect Logic
	  <?php if($_COOKIE['room'] == '') { ?>
		socket.emit('add user', username,room,localStorage.getItem('uUID'),window.location,$(".private_chat_vorgang").val());
	  <?php } else { ?> 
		socket.emit('restore add user', {room:room,vorgang_id:$(".private_chat_vorgang").val(),browserid:localStorage.getItem('uUID'),username:username});		  
	  <?php } ?>
	   
		// Save Chatroom COOKIE
		$.post( "chat-client-storage.php", { session_store: "true", modus:"optChannel", session_value: $('.optChannel option').filter(':selected').val()})
			.done(function( data ) { 
				//alert( "Data Loaded: " + data );
		});
		
		// Save Chatroom COOKIE
		$.post( "chat-client-storage.php", { session_store: "true", modus:"room", session_value: $('.optChannel option').filter(':selected').val()})
			.done(function( data ) {
				//alert( "Data Loaded: " + data );
		});
		
    }  
  }

  // Sends a chat message
  function sendMessage () {
    var message = $(".txtMessage0").val();
    // Prevent markup from being injected into the message
    message = cleanInput(message);
    // if there is a non-empty message and a socket connection
   
   console.log("Init sende Nachricht: " + message + " con: " + connected);
   if (message && connected) { 

      //$(".txtMessage0").val().replace(/\n/g, "");;
	  $(".txtMessage0").val("");
	  console.log($(".txtMessage0").text(''));

		var date = new Date();
		var current_hour = date.getHours();
		var current_minute = date.getMinutes();
	   
      addChatMessage({
        username: username,
        message: message + " | " + current_hour + ":" + current_minute,
		own_message: "true"
      }); 
	  
	  room = $('.optChannel option').filter(':selected').val();
	  
      // tell server to execute 'new message' and send along one parameter
      console.log({message:message, room:room, username:username,isPrivat:$(".isPrivat").val()});
	  
	  
	  socket.emit('new message', {message:message, room:room, username:username,isPrivat:$(".isPrivat").val(),browserid:localStorage.getItem('uUID')});
    } else { 
		if(message == "") {
			alert("Bitte geben Sie eine Chat-Nachricht ein.");
		} else {
			alert("Sie sind leider nicht korrekt mit dem Chatserver verbunden.");
			
		}
		console.log("Keine Nachricht abgeschickt / connected -> " + connected + " | Nachricht -> " + message);
	}  
  }

  // Log a message
  function log (message, options) {
    var $el = $('<li>').addClass('log').text(message);
    addMessageElement($el, options);
  }

  // Adds the visual chat message to the message list
  function addChatMessage (data, options) {
    
	console.log('starte message log');
	
	// Don't fade the message in if there is an 'X was typing'
    var $typingMessages = getTypingMessages(data);
    options = options || {};
    if ($typingMessages.length !== 0) {
      options.fade = false;
      $typingMessages.remove();
    }
	
	$('#title').val(data.username);
	$('#body').val(data.message);
	//send();
	  
	if($('#chkSoundOn').is(':checked') == true && data.own_message == "false") {	
		if(data.message != 'schreibt gerade') {			
			document.getElementById("soundfile_1").play();
		}
	}
	

	var strFloat="";
	var strFloat_pad="";
	if(data.own_message == "true") {
		strFloat ="right";
		strFloat_pad ="Right";
	} else {
		strFloat ="left";
		strFloat_pad ="Left";
	}
	
	console.log('Ankommende Chatnachricht: ',data);  
    var $usernameDiv = $('<span class="username"/>')
      .text(data.username)
	  .css({'color': getUsernameColor(data.username),'float' : strFloat});
    var $messageBodyDiv = $('<span class="messageBody">').css({'float' : strFloat,'paddingRight':"20px"})
      .html(data.message);

    var typingClass = data.typing ? 'typing' : '';
    var $messageDiv = $('<li class="message"/>')
      .data('username', data.username)
      .addClass(typingClass)
	  .css({'float' : strFloat})
      .append($usernameDiv, $messageBodyDiv);
	  
	
    addMessageElement($messageDiv, options);
  }

  // Adds the visual chat typing message
  function addChatTyping (data) {
    data.typing = true;
    data.message = 'schreibt gerade';
	console.log(data);
    addChatMessage(data);
  }
  
  // Removes the visual chat typing message
  function removeChatTyping (data) {
    getTypingMessages(data).fadeOut(function () {
      $(this).remove();
    });
  }

  // Adds a message element to the messages and scrolls to the bottom
  // el - The element to add as a message
  // options.fade - If the element should fade-in (default = true)
  // options.prepend - If the element should prepend
  //   all other messages (default = false)
  function addMessageElement (el, options) {
    var $el = $(el); 
	
	//console.log('hänge dran message log');
    
	// Setup default options
    if (!options) {
      options = {};
    }
    if (typeof options.fade === 'undefined') {
      options.fade = true;
    }
    if (typeof options.prepend === 'undefined') {
      options.prepend = false;
    }

    // Apply options
    if (options.fade) {
      $el.hide().fadeIn(FADE_TIME);
    }
    if (options.prepend) {
      $('.messages').prepend($el);
    } else {
      $('.messages').append($el);
    }
    $('.messages')[0].scrollTop = $('.messages')[0].scrollHeight;
  }

  // Prevents input from having injected markup
  function cleanInput (input) {
    return $('<div/>').text(input).text();
  }

  // Updates the typing event
  function updateTyping () {
    
	if (connected) {
      if (!typing) {
        typing = true;
		console.log("tippe.. / Connection: " + connected);  
        socket.emit('typing',{room:$('.optChannel option').filter(':selected').val(),browserid:localStorage.getItem('uUID'),isPrivat:$(".isPrivat").val()});
      }
      lastTypingTime = (new Date()).getTime();

      setTimeout(function () {
        var typingTimer = (new Date()).getTime();
        var timeDiff = typingTimer - lastTypingTime;
        if (timeDiff >= TYPING_TIMER_LENGTH && typing) {  
          socket.emit('stop typing',{room:$('.optChannel option').filter(':selected').val(),browserid:localStorage.getItem('uUID'),isPrivat:$(".isPrivat").val()});
          typing = false;
        }
      }, TYPING_TIMER_LENGTH);
    }
  }

  // Gets the 'X is typing' messages of a user
  function getTypingMessages (data) {
    return $('.typing.message').filter(function (i) {
      return $(this).data('username') === data.username;
    });
  }

  function convertFloat32ToInt16(buffer) {
  l = buffer.length;
  buf = new Int16Array(l);
  while (l--) {
    buf[l] = Math.min(1, buffer[l])*0x7FFF;
  }
  return buf.buffer;
}

function recorderProcess(e) {
  var left = e.inputBuffer.getChannelData(0);
  window.Stream.write(convertFloat32ToInt16(left));
}

  // Gets the color of a username through our hash function
  function getUsernameColor (username) {
    // Compute hash code
    var hash = 7;
    if(typeof username != 'undefined') {

		for (var i = 0; i < username.length; i++) {
		   hash = username.charCodeAt(i) + (hash << 5) - hash;
		}
		// Calculate color
		var index = Math.abs(hash % COLORS.length);
		return COLORS[index];
			
	} 
  }
  
// Cancel the Chat (Web-Client-Side)
  window.SendCancelChat = function() {	  	 
	console.log("Abbruch Chat.");
	$("#call2chat_msg").animate({height:"0em"})
	$(".chatArea").html("Ihre Unterhaltung wurde beendet.");
	
	var bPrivat = "false";
	if($(".private_chat_vorgang").val() != '') {
		bPrivat = "true";
	}
  socket.emit('cancel chat',{msg:$(".txtMessage0").val(),browserid:$(".private_chat").val(),vorgang_id:$(".private_chat_vorgang").val(),local_browser_id:localStorage.getItem('uUID'),username:$(".usernameInput").val(),room:$('.optChannel option').filter(':selected').val(),isPrivat:bPrivat});
	     
	
	$.post( "chat-client-storage.php", { session_store: "true", modus:"vorgang_id" ,session_value: ""})
		.done(function( data ) {  
			//alert( "Data Loaded: " + data );
	});	

	$.post( "chat-client-storage.php", { session_store: "true", modus:"room" ,session_value: ""})
		.done(function( data ) {  
			//alert( "Data Loaded: " + data );
	});	
	$.post( "chat-client-storage.php", { session_store: "true", modus:"optChannel" ,session_value: ""})
		.done(function( data ) {  
			//alert( "Data Loaded: " + data );
	});	

	// Destroy Private Chat Session
	$(".private_chat").val("");
 	
	
  }  
  
window.SendMessage2Chat = function () {
	  
		if($(".usernameInput").val() != '') {
			
			if($('.optChannel option').filter(':selected').text()  != '') {
		
				if($(".private_chat").val() != '') {
					console.log('>> Private Nachricht an' + $(".private_chat").val() + ' Inhalt:' + $(".txtMessage0").val());
								
					// JB B
					addChatMessage({
							username: $('.usernameInput').val(),
							message: $(".txtMessage0").val(),
							browserid : localStorage.getItem('uUID'),
							vorgang_id: $(".private_chat_vorgang").val(),
							own_message : "true"
					});
						 
					socket.emit('private message',$(".txtMessage0").val(),$(".private_chat").val(),$(".private_chat_vorgang").val(),localStorage.getItem('uUID'),$(".usernameInput").val(),$('.optChannel option').filter(':selected').val());
					 
					$(".txtMessage0").val(""); 
					$(".txtMessage0").text(""); 
					
				} else {
					if (username) {  
						console.log('>> Schicke Raum Nachricht von ' + username);
						sendMessage();

						$.post( "chat-client-storage.php", { session_store: "true", modus:"room" ,session_value: $('.optChannel option').filter(':selected').val()})
							.done(function( data ) {  
								//alert( "Data Loaded: " + data );
						});
						
						socket.emit('stop typing',{room:$('.optChannel option').filter(':selected').val(),browserid:localStorage.getItem('uUID'),isPrivat:$(".isPrivat").val()});
						typing = false;
					} else {
						console.log('Init Benutzername..');
						$(".txtMessage0").css({display : ''});
						$(".usernameInput").css({display : 'none'});
						$(".optChannel").css({display : 'none'});
						$("#strg_channel_info").css({display : 'none'});
						setUsername();
					}			
				}
		
			} else {
				alert("Bitte Anliegen aussuchen, um Thema des Chats vorzugeben.");
			}
			
					
		} else {
			alert("Bitte geben Sie Ihren Nickname ein.");
		}
		
  }
  
  // Keyboard events

  $window.keydown(function (event) {
    // Auto-focus the current input when a key is typed
    if (!(event.ctrlKey || event.metaKey || event.altKey)) {
      // JB popup fix
	  //$currentInput.focus();
    }
	
		//console.log('Tasta: ' + event);
		updateTyping();
    // When the client hits ENTER on their keyboard
    if (event.which === 13) {
		//console.log(event);
		console.log("Daten sendn...");
		console.log($(".setStep").val());
		
		if($(".setStep").val() != "1" && $(".setStep").val() != "2") {
			console.log("Login...");
			SendMessage2Chat();
		} else if ($(".setStep").val() == "1") {
			
			//alert($(".usernameInput").val());
			
			if($(".usernameInput").val() != "") {
				call2chat('step2');
			} else {
				alert("Bitte Chatnamen eingeben!");
			}
		} else if($(".setStep").val() == "2") {
			if($('.optChannel option').filter(':selected').text() != "") {
				call2chat('step3');
			} 
			
		}
		
		
		
    }
  });
  $window.keyup(function (event) {
	if (event.which === 13) {
		$(".txtMessage0").val("");
	}
  })
  
  // Do something on upload progress:
    uploader.addEventListener("progress", function(event){
        var percent = event.bytesLoaded / event.file.size * 100;
        console.log("File is", percent.toFixed(2), "percent loaded");
		
    });

    // Do something when a file is uploaded:
    uploader.addEventListener("complete", function(event){
        console.log(event.success);
        console.log(event.file);
		
	 
		var ext = event.file.name.split('.');
		
		addChatMessage({
			username: socket.username,
			message: '<img src="uploads/' + event.file.base + '.' + ext[ext.length - 1] + '"/>'		
		});  
    });

socket.on('open', function() {
  // for the sake of this example let's put the stream in the window
  window.Stream = client.createStream();
});	
  
  $inputMessage.on('input', function() {
	console.log("/\ typing...");
    updateTyping();
  });
  
  // Click events

  // Focus input when clicking anywhere on login page
  $loginPage.click(function () {
    $currentInput.focus();
  });

  // Focus input when clicking on the message input's border
  $(".txtMessage0").click(function () {
    $(".txtMessage0").focus();
  });

  // Socket events
	socket.on("connection", function(data){
	
	});  
	
  // Whenever the server emits 'login', log the login message
  socket.on('login', function (data) {
	

    connected = true;
	console.log("Login / Mit Chat-Server verbunden: " + connected);
	console.log("Logindata:",data);
    
	$("#setHeaderMsg").html("Online im Themenchat");
	// Display the welcome message
    var message = "Chatraum: " + data.room_name_text;
        
	log(message, {
      prepend: true
    });
	 
	var message = data.room_welcome_msg;
        
	log(message, {
      prepend: false
    });
	
	//Default Message on Login
	$(".chatArea").html("Wir freuen uns auf eine Unterhaltung mit Ihnen.");
	 
    addParticipantsMessage(data);
  });


//JB Opening
function isWorkingHour() {
    var d = new Date(), // current time
        hours = d.getHours(),
        mins = d.getMinutes();
	return true;
}
  
  socket.on('chatroom_status', function (data) {
	console.log("Support-Online.." + data.anzahl);
	
	var isOpen = isWorkingHour(); 
	 	
		
	if(data.anzahl > 0 && isOpen == true) {		
		
		$("#chat_room_status").html('<a onClick="javascript:call2chat(\'step1\')"><img id="call2action_live_chatten" src="/templates/tsecurity.de/media/live-chatten.png" border="0" style="" class="shake wnd"/></a>');
			
	} else {
		$("#chat_room_status").html('<img id="call2action_live_chatten" src="/templates/tsecurity.de/media/live-chat-Eng-grau.png" border="0" style="" class="shake wnd_inactive"/></div>');	
		}		
  });

  // Whenever the server emits 'new message', update the chat body
  socket.on('cancel chat', function (data) {
		console.log("Chat vom Support geschlossen."); 
		$(".chatArea").html(data.message);
		$("#call2chat_msg").animate({height:"0%"});
  
		$.post( "chat-client-storage.php", { session_store: "true", modus:"vorgang_id" ,session_value: ""})
			.done(function( data ) {  
				//alert( "Data Loaded: " + data );
		});	

		$.post( "chat-client-storage.php", { session_store: "true", modus:"room" ,session_value: ""})
			.done(function( data ) {  
				//alert( "Data Loaded: " + data );
		});	
		$.post( "chat-client-storage.php", { session_store: "true", modus:"optChannel" ,session_value: ""})
			.done(function( data ) {  
				//alert( "Data Loaded: " + data );
		});	

		// Destroy Private Chat Session
		$(".private_chat").val("");
		
  }); 
  
  // Whenever the server emits 'new message', update the chat body
  socket.on('new message', function (data) {
	
	console.log('new message: ');
	console.log(data); 
	console.log(data.message.indexOf('/N~'));
	
	// Special Commands 
	if(data.message.indexOf('/N~') >= 0) {
		var str = data.message.split("~");
		window.location = str[1];
	} 
	
	if(data.message.indexOf('/S~') >= 0) {
		var str = data.message.split("~");
		document.getElementById(str[1]).play();
		exit(0);
	} 

	if(data.message.indexOf("Ihre Chatanfrage wurde angenommen.") > 0) {
	 	 
		//leave room damit keine Channel Nachrichten mehr empfangen werden..
		socket.emit('leave room', {room:$('.optChannel option').filter(':selected').val()});
		
		console.log("Quell-Socket-ID: " + data.source_socketid)
		if(data.source_socketid != '') {
		
			console.log("new message -> erkenne private chat: " + data.source_socketid + " | Vorgang ID: " + data.vorgang_id );
			
			$.post( "chat-client-storage.php", { session_store: "true", modus:"vorgang_id", session_value: data.vorgang_id})
				.done(function( data ) {
					//alert( "Data Loaded: " + data );
			});  
					
			$.post( "chat-client-storage.php", { session_store: "true", modus:"destination_id" ,session_value: data.source_socketid})
				.done(function( data ) {
					//alert( "Data Loaded: " + data );
			}); 
	
			$.post( "chat-client-storage.php", { session_store: "true", modus:"dest_browserid" ,session_value: data.dest_browserid})
				.done(function( data ) {  
					//alert( "Data Loaded: " + data );
			}); 			

	 
	  
			$(".private_chat_vorgang").val(data.vorgang_id);
			$(".private_chat").val(data.dest_browserid);
			$(".isPrivat").val("true");
			$("#setHeaderMsg").html("Privat Chat mit " + data.username);
		}
	}
	
	data.own_message = "false";
	console.log(data);
    addChatMessage(data);
  });
 
    // Whenever the server emits 'private message', update the chat body
  socket.on('private message', function (data) {
	data.own_message = "false";
	console.log('private message -> ' + data);
 
    addChatMessage(data);
  });

  
socket.on('channel messages', function (data) {
	var i=0;
	console.log("Channel Messages");

	for(i=0; i <= data.message.length; i++) {
		
		console.log(data.message[i]);
		
		try {
			if(typeof data.message[i].user_chat_nickname !== 'undefined') {
				
				if(typeof data.message[i].user_chat_nickname !== 'undefined') {
					addChatMessage({
						username: data.message[i].user_chat_nickname,
						message: data.message[i].chat_message		
					});
				}
				
			}						
		} catch(e){
			console.log("Error:",e);
		}
		 
		//log(data[i].chat_message);	
	}
	
      
});
  
  socket.on('user list', function (data) {
	var i=0; 
	var strUsers = "";
	
	//console.log(data);
	
	//alert(data.usernamelist[0].nickname);
	
	// 0 = Username
	// 1 = Socket ID
	// 2 = Channel-Name (Internal)
	
	for(i = 0; i <= data.usernamelist.length; i++) {
		if(data.usernamelist[i] != null) {
			strUsers += '<a href="javascript:popup(\'' + data.usernamelist[i].socketid +  '\',\'' + data.usernamelist[i].nickname + '\')">' + data.usernamelist[i].nickname + '</a> ';
		}
	}
	
	$(".userlist").html(strUsers); 
  });
   
  socket.on('channellist', function(data) {
		console.log(data);
		//var i = 0;
		var strSelect = '<select size="7" class="optChannel" ondblclick="javascript:call2chat(\'step3\')" name="optChannel">';
		    
		for(var i = 0; i <= data.channellist.length -1; i++) {
			console.log(data.channellist[i]);
			
			var strMessage = "";
			var strCSSColor = "";
			if(data.channellist[i].room_status == 'OFFLINE') {
				strMessage = " (Wartezeit)";
				strCSSColor = ' style="text-decoration: line-through;"';
			} else {
				strCSSColor = ' style="color:white"';
			}
			 
			if(data.room = data.channellist[i].chatroom_value) {
				strSelect += '<option ' + strCSSColor + ' value="' + data.channellist[i].chatroom_value + '" selected="true">' + data.channellist[i].chatroom_name + strMessage +'</option>';
			} else {
				strSelect += '<option ' + strCSSColor + ' value="' + data.channellist[i].chatroom_value + '">' + data.channellist[i].chatroom_name + strMessage +'</option>';
			}
		}
		
		strSelect += '</select>';
		
		$(".channelist").html(strSelect);
		
  });
  
  // Whenever the server emits 'user joined', log it in the chat body
  socket.on('user joined', function (data) {
	log(data.username + ' hat sich gerade angemeldet.');
    addParticipantsMessage(data);
  });


  // Whenever the server emits 'user left', log it in the chat body
  socket.on('user left', function (data) {
	console.log("Nutzer geht Offline..." + data.username + " / Browserid: " + data.browserid);
     
	if(typeof data.username != 'undefined') {
		log(data.username + ' offline.'); 
		addParticipantsMessage(data);		
	}
	
	removeChatTyping(data);
  });

  // Whenever the server emits 'typing', show the typing message
  socket.on('typing', function (data) {
	console.log(data);
    addChatTyping(data);
  });

  // Whenever the server emits 'stop typing', kill the typing message
  socket.on('stop typing', function (data) {
    removeChatTyping(data);
  });

  });