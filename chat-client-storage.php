<?php
session_start();

if($_POST['session_store'] == true) {

	switch($_POST['modus']) {
		case 'vorgang_id':
			setcookie("vorgang_id", $_POST['session_value'], time()+36000); 
			//$_SESSSION['vorgang_id'] = $_POST['session_value'];
			echo "VorgangsID gespeichert '".$_COOKIE['vorgang_id']."'";
			break;
		case 'optChannel':
			setcookie("optChannel", $_POST['session_value'], time()+36000); 
			//$_SESSSION['vorgang_id'] = $_POST['session_value'];
			echo "Channel ID gespeichert '".$_COOKIE['optChannel']."'";
			break;
		case 'txtNickname':
			setcookie("txtNickname", $_POST['session_value'], time()+36000); 
			//$_SESSSION['vorgang_id'] = $_POST['session_value'];
			echo "Nickname gespeichert '".$_COOKIE['txtNickname']."'";
			break;
		case 'get vorgang_id':
			echo "VorgangsID aus-gelesen >> '".$_COOKIE['vorgang_id']."'";
			break;
		case 'dest_browserid':
			setcookie("dest_browserid", $_POST['session_value'], time()+36000); 
			break;
		case 'destination_id':
			setcookie("destination_id", $_POST['session_value'], time()+36000); 
			//$_SESSSION['destination_id'] = $_POST['session_value'];
			echo "Chatpartner-ID gespeichert '".$_COOKIE['destination_id']."'";
			break;
		case 'room':
			setcookie("room", $_POST['session_value'], time()+36000); 
			//$_SESSSION['destination_id'] = $_POST['session_value'];
			echo "Room-ID gespeichert '".$_COOKIE['room']."'";
			break;
		case 'get channel rooms':
			
			// Falscher Server 
			$query = "SELECT * FROM web_chat_room";
			$link = mysql_connect('localhost','mai-chatarena','KsgTZozzFHiAvsCbe7ir');
			mysql_select_db('maiwell-chatarena',$link);
			
			if (!$link) {
				die('Verbindung schlug fehl: ' . mysql_error());
			}
			echo 'Erfolgreich verbunden';
			
			$resRooms = mysql_query($query) or die(mysql_error());
			$strFinal = '<select size="2" class="optChannel" name="optChannel">';
			while($strRoom = mysql_fetch_assoc($resRooms)) {
				$strFinal .= '<option selected=true value="'.$strRoom['chatroom_value'].'">'.$strRoom['chatroom_name'].'</option>';
				print_r($strRoom);
			}
			$strFinal .= '</select>';
			
			echo $strFinal;
			break;
	}
	
}

?>