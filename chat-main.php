<?php header("Content-type: application/javascript"); ?>
<?php session_start(); ?>



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
 
  var socket = io.connect('https://tsecurity.chattet.de:1337',{secure:true});
    
  var uploader = new SocketIOFileUpload(socket);
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

window.getChannellist = function(room,domain) {
    console.log("Hole Channel-Liste..");
    socket.emit('fetch channellist', {room:room,domain:domain});
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
	socket.emit('register', localStorage.getItem('uUID'),window.location,$(".private_chat").val(),room,username,'false','<?php echo $_SESSION['page_id']; ?>','<?php echo  $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] .'?'. $_SERVER['QUERY_STRING']; ?>'); 
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
/*
return $.inArray(d.getDay(), [1, 2, 3, 4, 5,6,7) 
            && hours >= 9   
            && (hours < 17 || hours === 16 && mins <= 45);*/
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

