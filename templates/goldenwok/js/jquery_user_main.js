var shop_cat_order_price = "ASC";
var shop_cat_order_name = "ASC";
var ajax_load = "<img src='image/load.gif' alt='loading...' />";


function shop_cart_delete(item_id,modul_typ,modul_id) {
	
	$("#box_" + modul_typ + "_" + modul_id).html(ajax_load).load("/cart/cart_item_delete.php", "shop_item_id=" + item_id + "&module_id=" + modul_id);
	
	$('#shop_footer').html(ajax_load).load("/cart/cart_info.php", "bRefreshAjax=true");
	
	$("#box_" + modul_typ + "_" + modul_id).html(ajax_load).load("/ACP/acp_load_modul.php", "module_name=" + modul_typ + "&module_id=" + modul_id);
}

function Weiterleitung_user(data) {
	//alert(data);
	window.location.href = data;
}
function onEnter_acp_produkt(e,modus){
	 
	//alert("IN..");
	if (e.keyCode == 13) {
		
		SuchText = $("#txtSucheArtikel").val();
		
		var postData = "modus=" + modus + "&suche=" + SuchText;
		var formURL = "/ACP/acp_shop_item_list.php";
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR)
			{
				$("#result-module-add").html(data);
				return false;
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
			   alert(data + ' ' + errorThrown);
			}
		});
	}
}
function portal_signup(modul_typ,modul_id) {
	$("#box_" + modul_typ + "_" + modul_id).html(ajax_load).load("/ACP/acp_load_modul.php", "module_name=" + modul_typ + "&module_id=" + modul_id + "&modus=signup");
}
function buynow_abort(modul,id) {
	$("#box_" + modul + "_" + id).html(ajax_load).load("/cart/cart_items_clear.php", "");
	$("#box_" + modul + "_" + id).html(ajax_load).load("/ACP/acp_load_modul.php", "module_name=" + modul + "&module_id=" + id);
}
function portal_login(frm,modul_typ,modul_id) {
	var bError = false;
	var pageID = "";
	var strURL = "";
	pageID  = $('#login_page').val();
	//window.clearTimeout(TimerWeiterleitung);	
	if($('#txtRegPasswort').val()  == false) {
		$('#txtRegPasswort_err').html('Bitte Passwort eintragen.');
		bError = true;
	} else {
		$('#txtRegPasswort_err').html('');
	}	
	if($('#txtRegUsername').val()  == false) {
		$('#txtRegUsername_err').html('Bitte Passwort eintragen.');
		bError = true;
	} else {
		$('#txtRegUsername_err').html('');
	}		
	
	if(bError == false) {
		var postData = "modus=api_login_user&txtUsername=" + $('#txtRegUsername').val() + "&txtPasswort=" + $('#txtRegPasswort').val();
		var formURL = "/api.php";
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR)
			{		
				//$("#box_" + modul_typ + "_" + modul_id).html("Warenkorb abgeschickt");
				var Login_ok  = data.split("-");	
				
				if(Login_ok[0].indexOf('LOGIN_OK') != -1) {
				
					$.ajax(
						{
							url : "/api.php",
							type: "POST",
							data : "modus=get_page_url&pageid=" + pageID,
							success:function(data, textStatus, jqXHR)
							{		
								//alert(data);
								strURL = data;
								window.location.href = strURL;
								return false;
							},
							error: function(jqXHR, textStatus, errorThrown)
							{
								//if fails    
								return false;				
							}
						});
					//alert(strURL);
					
					//TimerWeiterleitung = Weiterleitung_user(strURL);
				}
				return false;
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				//if fails    
				return false;				
			}
		});
	}
return false;	
}

function shopste_registierung(frm,modul_typ,modul_id) {
	var bError = false;
	if($('#txtEmail').val() == '') {
		$('#txtEmail_err').html('Bitte Email Adresse benutzername@emailanbieter.de eintragen');
		bError = true;
	} else {
		$('#txtEmail_err').html('');
	}
	if($('#txtVorname').val() == '') {
		$('#txtVorname_err').html('Bitte Ihren Vornamen eingeben');
		bError = true;
	} else {
		$('#txtVorname_err').html('');
	}
	if($('#txtNachname').val() == '') {
		$('#txtNachname_err').html('Bitte Ihren Nachnamen eingeben');
		bError = true;
	} else {
		$('#txtNachname_err').html('');
	}
	if($('#txtStrasse').val() == '') {
		$('#txtStrasse_err').html('Bitte Ihren Strasse eingeben');
		bError = true;
	} else {
		$('#txtStrasse_err').html('');
	}
	if($('#txtPLZ').val() == '') {
		$('#txtPLZ_err').html('Bitte Ihren Postleitzahl eingeben');
		bError = true;
	} else {
		$('#txtPLZ_err').html('');
	}
	if($('#txtOrt').val() == '') {
		$('#txtOrt_err').html('Bitte Ihren Stadt eingeben');
		bError = true;
	} else {
		$('#txtOrt_err').html('');
	}
	if($('#txtLand').val() == '') {
		$('#txtLand_err').html('Bitte Ihr Land eingeben');
		bError = true;
	} else {
		$('#txtLand_err').html('');
	}

	if($('#chkAGB').is(':checked')  == false) {
		$('#chkAGB_err').html('Bitte AGB lesen und akzeptieren.');
		bError = true;
	} else {
		$('#chkAGB_err').html('');
	}	
	 
	if($('#chkWiderruf').is(':checked')  == false) {
		$('#chkWiderruf_err').html('Bitte Widerruf lesen und akzeptieren.');
		bError = true;
	} else {
		$('#chkWiderruf_err').html('');
	}
	if($('#txtRegDomainName').val()  == false) {
		$('#txtRegDomainName_err').html('Bitte Domainnamen eintragen.');
		bError = true;
	} else {
		$('#txtRegDomainName_err').html('');
	}
	if($('#txtRegPasswort').val()  == false) {
		$('#txtRegPasswort_err').html('Bitte Passwort eintragen.');
		bError = true;
	} else {
		$('#txtRegPasswort_err').html('');
	}	
	if($('#txtRegUsername').val()  == false) {
		$('#txtRegUsername_err').html('Bitte Passwort eintragen.');
		bError = true;
	} else {
		$('#txtRegUsername_err').html('');
	}	
	
	var strDomain = $('#txtRegDomainName').val();
	$("#txtRegDomainName_status").html("http://" + strDomain + ".shopste.com");
	var postData = "modus=register_add_subdomain&reg_domain_name=" + strDomain;
	var formURL = "/api.php";
	$.ajax(
	{
		url : formURL,
		type: "POST",
		data : postData,
		success:function(data, textStatus, jqXHR)
		{
			//alert(data);
			//$("#box_" + modul_typ + "_" + modul_id).html("Warenkorb abgeschickt");
			if(data == 'Domain bereits belegt') {
				bError = true;
			}
			return false;
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			//if fails     
		}
	});
	var strDomain = $('#txtRegUsername').val();
	$("#txtRegUsername_status").html(strDomain);
	if(strDomain != '') {
		var postData = "modus=register_add_benutzer&reg_username_name=" + strDomain;
		var formURL = "/api.php";
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR)
			{
				//alert(data);
				//$("#box_" + modul_typ + "_" + modul_id).html("Warenkorb abgeschickt");
				if(data == 'Benutzername bereits belegt') {
					bError = true;
				}
				$("#txtRegUsername_check_err").html(data);
				return false;
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				//if fails     
			}
		});
	}	
	if(bError == false) {
		var postData = $('#' + frm).serialize()
		var formURL = $('#' + frm).attr("action");
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR)
			{
				//alert(data);
				//$("#box_" + modul_typ + "_" + modul_id).html("Warenkorb abgeschickt");
				$("#box_" + modul_typ + "_" + modul_id).html(ajax_load).load("/ACP/acp_load_modul.php", "module_name=" + modul_typ + "&module_id=" + modul_id + "&status=sended");
				return false;
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				//if fails     
			}
		});
	} else {
		alert('Es sind Fehler aufgetreten \n Bitte überprüfen Sie Ihre Formulareingaben');
	}
	return false;
}
function mydraft_registierung(frm,modul_typ,modul_id) {
	var bError = false;
	if($('#txtEmail').val() == '') {
		$('#txtEmail_err').html('Bitte Email Adresse benutzername@emailanbieter.de eintragen');
		bError = true;
	} else {
		$('#txtEmail_err').html('');
	}
	if($('#txtVorname').val() == '') {
		$('#txtVorname_err').html('Bitte Ihren Vornamen eingeben');
		bError = true;
	} else {
		$('#txtVorname_err').html('');
	}
	if($('#txtNachname').val() == '') {
		$('#txtNachname_err').html('Bitte Ihren Nachnamen eingeben');
		bError = true;
	} else {
		$('#txtNachname_err').html('');
	}
	if($('#txtStrasse').val() == '') {
		$('#txtStrasse_err').html('Bitte Ihren Strasse eingeben');
		bError = true;
	} else {
		$('#txtStrasse_err').html('');
	}
	if($('#txtPLZ').val() == '') {
		$('#txtPLZ_err').html('Bitte Ihren Postleitzahl eingeben');
		bError = true;
	} else {
		$('#txtPLZ_err').html('');
	}
	if($('#txtOrt').val() == '') {
		$('#txtOrt_err').html('Bitte Ihren Stadt eingeben');
		bError = true;
	} else {
		$('#txtOrt_err').html('');
	}
	if($('#txtLand').val() == '') {
		$('#txtLand_err').html('Bitte Ihr Land eingeben');
		bError = true;
	} else {
		$('#txtLand_err').html('');
	}

	if($('#chkAGB').is(':checked')  == false) {
		$('#chkAGB_err').html('Bitte AGB lesen und akzeptieren.');
		bError = true;
	} else {
		$('#chkAGB_err').html('');
	}	
	 
	if($('#chkWiderruf').is(':checked')  == false) {
		$('#chkWiderruf_err').html('Bitte Widerruf lesen und akzeptieren.');
		bError = true;
	} else {
		$('#chkWiderruf_err').html('');
	}
	if($('#txtRegDomainName').val()  == false) {
		$('#txtRegDomainName_err').html('Bitte Domainnamen eintragen.');
		bError = true;
	} else {
		$('#txtRegDomainName_err').html('');
	}
	if($('#txtRegPasswort').val()  == false) {
		$('#txtRegPasswort_err').html('Bitte Passwort eintragen.');
		bError = true;
	} else {
		$('#txtRegPasswort_err').html('');
	}	
	if($('#txtRegUsername').val()  == false) {
		$('#txtRegUsername_err').html('Bitte Passwort eintragen.');
		bError = true;
	} else {
		$('#txtRegUsername_err').html('');
	}	
	
	var strDomain = $('#txtRegDomainName').val();
	$("#txtRegDomainName_status").html("http://" + strDomain + ".shopste.com");
	var postData = "modus=register_add_subdomain&reg_domain_name=" + strDomain;
	var formURL = "/api.php";
	$.ajax(
	{
		url : formURL,
		type: "POST",
		data : postData,
		success:function(data, textStatus, jqXHR)
		{
			//alert(data);
			//$("#box_" + modul_typ + "_" + modul_id).html("Warenkorb abgeschickt");
			if(data == 'REGISTERIEREN_VERBOTEN') {
				bError = true;
			}
			return false;
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			//if fails     
		}
	});
	var strDomain = $('#txtRegUsername').val();
	$("#txtRegUsername_status").html(strDomain);
	if(strDomain != '') {
		var postData = "modus=register_add_benutzer&reg_username_name=" + strDomain;
		var formURL = "/api.php";
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR)
			{
				//alert(data);
				//$("#box_" + modul_typ + "_" + modul_id).html("Warenkorb abgeschickt");
				if(data == 'REGISTERIEREN_VERBOTEN') {
					bError = true;
				}
				$("#txtRegUsername_check_err").html(data);
				return false;
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				//if fails     
			}
		});
	}	
	if(bError == false) {
		var postData = $('#' + frm).serialize()
		var formURL = $('#' + frm).attr("action");
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR)
			{
				//alert(data);
				//$("#box_" + modul_typ + "_" + modul_id).html("Warenkorb abgeschickt");
				$("#box_" + modul_typ + "_" + modul_id).html(ajax_load).load("/ACP/acp_load_modul.php", "module_name=" + modul_typ + "&module_id=" + modul_id + "&status=sended");
				return false;
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				//if fails     
			}
		});
	} else {
		alert('Es sind Fehler aufgetretten \n Bitte überprüfen Sie Ihre Formulareingaben');
	}
	return false;
}
 
function getKontaktFormModul(modul_typ,modul_id) {
	$(document).ready(function(){
		var txtContent = $("#modul_kontakt_" + modul_id).html();
		$("#kontakt_nachricht" + modul_id).html('<textarea class="texthtml"  rows="60" id="modul_kontakt_' + modul_id + '" name="kontaktform_content" style="height:400px;width:100%""></textarea>');
		
		var oFCKeditor = new FCKeditor( 'kontaktform_content' ) ;
		oFCKeditor.BasePath	= '/framework/fckeditor/';
		//oFCKeditor.Height	= 300 ;
		//oFCKeditor.Value	= '<p>This is some <strong>sample text<\/strong>. You are using <a href="http://www.fckeditor.net/">FCKeditor<\/a>.<\/p>' ;
		oFCKeditor.ReplaceTextarea() ;
		
		//$('input#submit_texthtml').click( function() {
			//alert('da');
		$("#submit_kontaktform").click( function (event) { 
			// Stop form from submitting normally
			event.preventDefault();			
			var firma = $("#txtFirma").val();
			var email = $("#txtEmail").val();
			var vorname = $("#txtVorname").val();
			var nachname = $("#txtNachname").val();
			var telefon = $("#txtTelefon").val();
			var oFCKeditor = FCKeditorAPI.GetInstance("kontaktform_content"); 
			
			var fckeditor =  oFCKeditor.GetXHTML(); //$('textarea#modul_texthtml_' + id).val();
			//alert(fckeditor);
			var dataString ="txtNachricht=" + escape(fckeditor)  + "&txtFirma=" + firma + "&txtEmail=" + email + "&txtVorname=" + vorname + "&txtNachname=" + nachname + "&txtTelefon=" + telefon + "&modus=kontakt_send_mail&modul&kontakt_modul_id=" + modul_id;
            $.ajax({
					type:'POST',
					data:dataString,
					url:'/api.php',
					success:function(data) {
						$("#box_" + modul_typ + "_" + modul_id).html(ajax_load).load("/ACP/acp_load_modul.php", "module_name=" + modul_typ + "&module_id=" + modul_id + "&status=sended");
					}
				  });
         });           
		//});
	}); // ready 
}

function setKontaktForm(frm,modul_typ,modul_id) {
	var bError = false;
	if($('#txtEmail').val() == '') {
		$('#txtEmail_err').html('Bitte Email Adresse benutzername@emailanbieter.de eintragen');
		bError = true;
	} else {
		$('#txtEmail_err').html('');
	}
	if($('#txtVorname').val() == '') {
		$('#txtVorname_err').html('Bitte Ihren Vornamen eingeben');
		bError = true;
	} else {
		$('#txtVorname_err').html('');
	}
	if($('#txtNachname').val() == '') {
		$('#txtNachname_err').html('Bitte Ihren Nachnamen eingeben');
		bError = true;
	} else {
		$('#txtNachname_err').html('');
	}
  
	if(bError == false) {
		var postData = $('#' + frm).serialize()
		var formURL = $('#' + frm).attr("action");
		alert(postData);
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR)
			{
				//alert(data);
				//$("#box_" + modul_typ + "_" + modul_id).html("Warenkorb abgeschickt");
				$("#box_" + modul_typ + "_" + modul_id).html(ajax_load).load("/ACP/acp_load_modul.php", "module_name=" + modul_typ + "&module_id=" + modul_id + "&status=sended");
				return false;
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				//if fails     
			}
		});
	} else {
		alert('Es sind Fehler aufgetretten \n Bitte überprüfen Sie Ihre Formulareingaben');
	}
	return false;
}
function portal_umkreis_suche(frm,modul_typ,modul_id,plz) {
		
		var strPLZ = $('#txtPortalUmkreisPLZ').val();
		var strPLZ_umkreis = $('#portal_umkreis_km').val();
		$("#box_" + modul_typ + "_" + modul_id).html(ajax_load).load("/ACP/acp_load_modul.php", "module_name=" + modul_typ + "&module_id=" + modul_id + "&status=sended&portal_plz=" + strPLZ + "&suche=" + strPLZ + "&portal_umkreis_km=" + strPLZ_umkreis);
		
/* 		var postData = $('#' + frm).serialize()
		var formURL = $('#' + frm).attr("action");
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR)
			{
				//alert(data);
				//$("#box_" + modul_typ + "_" + modul_id).html("Warenkorb abgeschickt");
				return false;
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				//if fails     
			}
		}); */
	return false;
}

function portal_umkreis_suche_realtime() {
	var strPLZ = $('#txtPortalUmkreisPLZ').val();
	var strPLZ_umkreis = $('#portal_umkreis_km').val();
	
	var postData = "modus=portal_umkreis_plz&portal_plz=" + strPLZ + "&portal_umkreis_km=" + strPLZ_umkreis;
	var formURL = "/api.php";
	$.ajax(
	{
		url : formURL,
		type: "POST",
		data : postData,
		success:function(data, textStatus, jqXHR)
		{
			//alert(data);
			//$("#box_" + modul_typ + "_" + modul_id).html("Warenkorb abgeschickt");
			$.ajax(
			{
				url : '/module/portal_umkreis/index.php',
				type: "POST",
				data : "&bAjax=Y&CSVdata=" + data + "&portal_plz=" + strPLZ + "&suche=" + strPLZ ,
				success:function(data, textStatus, jqXHR)
				{
					//alert(data);
					//$("#box_" + modul_typ + "_" + modul_id).html("Warenkorb abgeschickt");
					$("#frm_portal_umkreis_info").html(data);
					return false;
				},
				error: function(jqXHR, textStatus, errorThrown)
				{
					//if fails     
				}
			});
			
			$("#frm_portal_umkreis_info").html(data);
			return false;
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			//if fails     
		}
	});
	
}

function registrieren_mydraft_domain_check() {
	var strDomain = $('#txtRegDomainName').val();
	$("#txtRegDomainName_status").html("http://" + strDomain + ".shopste.com");
	var postData = "modus=register_add_subdomain&reg_domain_name=" + strDomain;
	var formURL = "/api.php";
	$.ajax(
	{
		url : formURL,
		type: "POST",
		data : postData,
		success:function(data, textStatus, jqXHR)
		{
			//alert(data);
			//$("#box_" + modul_typ + "_" + modul_id).html("Warenkorb abgeschickt");
			$("#txtRegDomainName_check_err").html(data);
			return false;
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			//if fails     
		}
	});
	
}
function registrieren_mydraft_username_check() {
	var strDomain = $('#txtRegUsername').val();
	$("#txtRegUsername_status").html(strDomain);
	if(strDomain != '') {
		var postData = "modus=register_add_benutzer&reg_username_name=" + strDomain;
		var formURL = "/api.php";
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR)
			{
				//alert(data);
				//$("#box_" + modul_typ + "_" + modul_id).html("Warenkorb abgeschickt");
				$("#txtRegUsername_check_err").html(data);
				return false;
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				//if fails     
			}
		});
	}
}
function shop_cat_search_intro() {
	if($('#txtQuickSearch').val() == 'Bitte Suchbegriff eingeben') {
		$('#txtQuickSearch').val('');
	}
}
function news_cat_search_intro() {
	if($('#txtQuickSearch').val() == 'Bitte Suchbegriff eingeben') {
		$('#txtQuickSearch').val('');
	}
}
function shop_cat_search_intro_reset() {
	if($('#txtQuickSearch').val() == '') {
		$('#txtQuickSearch').val('Bitte Suchbegriff eingeben');
	}
}
function news_cat_search_intro_reset() {
	if($('#txtQuickSearch').val() == '') {
		$('#txtQuickSearch').val('Bitte Suchbegriff eingeben');
	}
}
function cart_order_send(frm,modul_typ,modul_id) {
	var bError = false;
	if($('#txtEmail').val() == '') {
		$('#txtEmail_err').html('Bitte Email Adresse benutzername@emailanbieter.de eintragen');
		bError = true;
	} else {
		$('#txtEmail_err').html('');
	}
	if($('#txtVorname').val() == '') {
		$('#txtVorname_err').html('Bitte Ihren Vornamen eingeben');
		bError = true;
	} else {
		$('#txtVorname_err').html('');
	}
	if($('#txtNachname').val() == '') {
		$('#txtNachname_err').html('Bitte Ihren Nachnamen eingeben');
		bError = true;
	} else {
		$('#txtNachname_err').html('');
	}
	if($('#txtStrasse').val() == '') {
		$('#txtStrasse_err').html('Bitte Ihren Strasse eingeben');
		bError = true;
	} else {
		$('#txtStrasse_err').html('');
	}
	if($('#txtPLZ').val() == '') {
		$('#txtPLZ_err').html('Bitte Ihren Postleitzahl eingeben');
		bError = true;
	} else {
		$('#txtPLZ_err').html('');
	}
	if($('#txtOrt').val() == '') {
		$('#txtOrt_err').html('Bitte Ihren Stadt eingeben');
		bError = true;
	} else {
		$('#txtOrt_err').html('');
	}
	if($('#txtLand').val() == '') {
		$('#txtLand_err').html('Bitte Ihr Land eingeben');
		bError = true;
	} else {
		$('#txtLand_err').html('');
	}

	if($('#chkAGB').is(':checked')  == false) {
		$('#chkAGB_err').html('Bitte AGB lesen und akzeptieren.');
		bError = true;
	} else {
		$('#chkAGB_err').html('');
	}	
	 
	/*if($('#chkWiderruf').is(':checked')  == false) {
		$('#chkWiderruf_err').html('Bitte Widerruf lesen und akzeptieren.');
		bError = true;
	} else {
		$('#chkWiderruf_err').html('');
	}*/	
	//alert(bError);
	if(bError == false) {
		
		var postData = "option_name=EISO_IMPORT";
		var formURL = '/ACP/acp_domain_setting.php';
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR)
			{
				//alert(data);
				if (data == 'Y') {
					
					var postData = $('#' + frm).serialize()
					var formURL = '/ACP/acp_eiso_order_post.php';
					$.ajax(
					{
						url : formURL,
						type: "POST",
						data : postData,
						success:function(data, textStatus, jqXHR)
						{
							//alert(data);
							//$("#box_" + modul_typ + "_" + modul_id).html("Warenkorb abgeschickt");
							//$("#box_" + modul_typ + "_" + modul_id).html(ajax_load).load("/ACP/acp_load_modul.php", "module_name=" + modul_typ + "&module_id=" + modul_id + "&status=sended");
							//return false;
							//alert(data);

							
						},
						error: function(jqXHR, textStatus, errorThrown)
						{
							//if fails     
						}
					});
					
				}
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				//if fails     
			}
		});
		
	
		
		var postData = $('#' + frm).serialize()
		var formURL = $('#' + frm).attr("action");
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR)
			{
				//alert(data);
				//$("#box_" + modul_typ + "_" + modul_id).html("Warenkorb abgeschickt");
				$("#box_" + modul_typ + "_" + modul_id).html(ajax_load).load("/ACP/acp_load_modul.php", "module_name=" + modul_typ + "&module_id=" + modul_id + "&status=sended");
											$("#footer").css("height","25px");
							$("#footer").html(data);
				return false;
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				//if fails     
			}
		});
	} else {
		alert('Es sind Fehler aufgetretten \n Bitte überprüfen Sie Ihre Formulareingaben');
	}
	return false;
}
function cart_item_amount(modus,what) {
	var txtVal = parseInt($("#" + what).val(),10);
	if(modus == 'plus') {
		txtVal += 1;
		var txtStock = parseInt($("#cart_item_stock").val(),10);
		//alert(txtStock);
		if(txtVal > txtStock) {
			txtVal = txtStock;
		}
		
	} else {
		txtVal -= 1;
		if(txtVal < 0) {
			txtVal = 0;	
		}
	}
	
	$("#" + what).val(txtVal);
}
function shop_cart_change_menge(id,modus,bWarenkorb) {
	var menge = $('#warenkorb_frame_menge_' + id).html();
	
	if (modus == "plus") {
		menge = parseInt(menge) + 1;
		//alert(menge);
		$('#warenkorb_frame_menge_' + id).html(menge);
		shop_cart_frame_update_menge(id,'','',bWarenkorb,menge);
	} else {
		menge = parseInt(menge) - 1;
		
		if(menge == 0) {
			//alert(menge)
			shop_cart_delete_frame(id,'shop_cart','',bWarenkorb)
		} else {
			shop_cart_frame_update_menge(id,'','',bWarenkorb,menge);
		}
		$('#warenkorb_frame_menge_' + id).html(menge);
	}
}
function cart_item_add_frame(frm) {

	if(shop_order_check() == false) {
		alert('Sie müssen die Attribute auswählen um bestellen zu können.');
	} else {
		
		var postData = $('#' + frm).serialize()
		var formURL = $('#' + frm).attr("action");
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR)
			{
				$('#shop_footer').html(data);
				
				// Anzahl Warenkorb holen
				$.ajax(
						{
							url : "/ACP/user_warenkorb_count.php",
							type: "POST",
							data : postData,
							success:function(data, textStatus, jqXHR)
							{
								$('<style>.overlap:after{content:"Ihre Bestellung ' + data + ' Gericht(e)";white-space: pre-wrap;}</style>').appendTo('head');
								
								return false;
							},
							error: function(jqXHR, textStatus, errorThrown)
							{
								//if fails     
							}
						});
								
				
				if($("#footer").css("height") == "426px") {
					//alert("IN.." + status);
					var postData = "bWarenkorbOnly=false"
					var formURL = "/ACP/user_frame_warenkorb.php"
					$.ajax(
					{
						url : formURL,
						type: "POST",
						data : postData,
						success:function(data, textStatus, jqXHR)
						{
								if(status == 'NO_RESIZE' || status == '') {
									//alert(data);
									$("#shop_footer").html(data);
								} else {
									if($("#footer").css("height") == "426px") {
										$("#footer").css("height", "25px");
										$("#shop_footer").html("");
									} else {
										$("#footer").css("overflow-x", "auto");
										$('#footer').animate({
											height: "426px", 
											padding:"10px",
											opacity:1.0
										}, 500); 
										
										//$("#footer").css("height", "300px");
										//alert("in..");
										
										// Kein Scrolling
										/*if ($(document).height() > $(window).height()) {
											 var scrollTop = ($('html').scrollTop()) ? $('html').scrollTop() : $('body').scrollTop(); // Arbeitet mit Chrome, Firefox, IE...
											 $('html').addClass('noscroll').css('top',-scrollTop);         
										}*/
										
										//$("html").css("overflow", "hidden");
										//$("body").css("overflow", "hidden");
										//$("html").css("scroll", "no");
										$("#shop_footer").html(data);
									}
								}
								
					 
							return false;
						},
						error: function(jqXHR, textStatus, errorThrown)
						{
						   alert(data + ' ' + errorThrown);
						}
					});
				}
				return false;
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				$('#shop_footer').html('Konnte Produkt nicht in den Warenkorb aufnehmen! ERR:00001');
				return false;
			}
		});
	}
	return false;
} 
function cart_item_add(frm) {

	if(shop_order_check() == false) {
		alert('Sie müssen die Attribute auswählen um bestellen zu können.');
	} else {
		
		var postData = $('#' + frm).serialize()
		var formURL = $('#' + frm).attr("action");
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR)
			{
				$('#shop_footer').html(data);
				 	
				
				if($("#footer").css("height") == "426px") {
					//alert("IN.." + status);
					var postData = "bWarenkorbOnly=false"
					var formURL = "/ACP/user_frame_warenkorb.php"
					$.ajax(
					{
						url : formURL,
						type: "POST",
						data : postData,
						success:function(data, textStatus, jqXHR)
						{
								if(status == 'NO_RESIZE' || status == '') {
									//alert(data);
									$("#shop_footer").html(data);
								} else {
									if($("#footer").css("height") == "426px") {
										$("#footer").css("height", "25px");
										$("#shop_footer").html("");
									} else {
										$("#footer").css("overflow-x", "auto");
										$('#footer').animate({
											height: "426px", 
											padding:"10px",
											opacity:1.0
										}, 500); 
										
										//$("#footer").css("height", "300px");
										//alert("in..");
										
										// Kein Scrolling
										/*if ($(document).height() > $(window).height()) {
											 var scrollTop = ($('html').scrollTop()) ? $('html').scrollTop() : $('body').scrollTop(); // Arbeitet mit Chrome, Firefox, IE...
											 $('html').addClass('noscroll').css('top',-scrollTop);         
										}*/
										
										//$("html").css("overflow", "hidden");
										//$("body").css("overflow", "hidden");
										//$("html").css("scroll", "no");
										$("#shop_footer").html(data);
									}
								}
								
					 
							return false;
						},
						error: function(jqXHR, textStatus, errorThrown)
						{
						   alert(data + ' ' + errorThrown);
						}
					});
				}
				return false;
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				$('#shop_footer').html('Konnte Produkt nicht in den Warenkorb aufnehmen! ERR:00001');
				return false;
			}
		});
	}
	return false;
}

function shop_order_check() {
	strKombi = "";
	var bIn = false;
	if(typeof attribute!='undefined') {
		for($i=0; $i < attribute.length; $i++) {
			//alert($("#eigenschaft_" + attribute[$i] + " option:selected").val());
			if($("#eigenschaft_" + attribute[$i] + " option:selected").val() == 'Keine-Auswahl') {
				bIn=true;
				break;
			}
		}
	}
	if(bIn == true) {
		//alert('Bitte alle Optionen auswählen');
		return false;
	} else {
		return true;
	}
}
function cart_item_add_buynow(frm,modultyp,modulid) {

	if(shop_order_check() == false) {
		alert('Sie müssen die Attribute auswählen um bestellen zu können.');
	} else {
		
		var postData = $('#' + frm).serialize()
		var formURL = $('#' + frm).attr("action");
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR)
			{
				$('#shop_footer').html(data);
				
				var postData = "modus=buynow_checkout&typ=" + modultyp + "&modul_id=" + modulid;
				var formURL = "/module/shop_cart_order_buynow/index.php";
				$.ajax(
				{
					url : formURL,
					type: "POST",
					data : postData,
					success:function(data, textStatus, jqXHR)
					{
						//alert(data);
						//$("#box_" + modul_typ + "_" + modul_id).html("Warenkorb abgeschickt");
						$("#modul_" +  modultyp + "_" + modulid).html(data);
						return false;
					},
					error: function(jqXHR, textStatus, errorThrown)
					{
						//if fails     
					}
				});
				
				return false;
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				//if fails     
			}
		});
	}
	return false;
}

function shop_speisekarte_search(modul_id,catID) {
	var orderBy;
	var SuchText;
	var isPortal; 
	var strModule;
	var strDivErg;
 
	orderBy = $("#shop_category_sortby").val();
	SuchText = $("#txtQuickSearch").val();
	isPortal = $("#isPortal").val();
	// Sortiermodus festlegen
	if(orderBy != '') {
		if(isPortal == 'Y') {
			strModule = 'modul_shop_speisekarte';
			strDivErg = "#shop_speisekarte_result_" + modul_id;
		} else {
			strModule = 'shop_speisekarte';
			strDivErg = "#shop_speisekarte_result_" + modul_id;
		}
		$(strDivErg).html(ajax_load).load("/module/" + strModule + "/index.php","module_id=" + modul_id + "&bAjaxLoad=true&shop_cat_id=" + catID + "&orderby=" + orderBy +  "&suchtext=" + SuchText);
		return false;		
	}
}

function shop_cat_search(modul_id,catID,aktuelle_seite) {
	var orderBy;
	var SuchText;
	var isPortal; 
	var strModule;
	var strDivErg;
	//var aktuelle_seite;
 
	orderBy = $("#shop_category_sortby").val();
	SuchText = $("#txtQuickSearch").val();
	isPortal = $("#isPortal").val();
	//aktuelle_seite = $(this).attr("id")
	// Sortiermodus festlegen
	if(orderBy != '') {
		if(isPortal == 'Y') {
			strModule = 'portal_shop_cat_list';
			strDivErg = "#portal_shop_cat_list_result_" + modul_id;
		} else {
			strModule = 'shop_cat_list';
			strDivErg = "#shop_cat_list_result_" + modul_id;
		}
		$(strDivErg).html(ajax_load).load("/module/" + strModule + "/index.php","module_id=" + modul_id + "&bAjaxLoad=true&shop_cat_id=" + catID + "&orderby=" + orderBy +  "&suchtext=" + SuchText + "&seite=" + aktuelle_seite);
		return false;		
	}
}

function news_cat_search(modul_id,catID) {
	var orderBy;
	var SuchText;
	var isPortal; 
	var strModule;
	var strDivErg;
 
	orderBy = $("#shop_category_sortby").val();
	SuchText = $("#txtQuickSearch").val();
	isPortal = $("#isPortal").val();
	// Sortiermodus festlegen
	if(orderBy != '') {
		if(isPortal == 'Y') {
			strModule = 'portal_shop_cat_list';
			strDivErg = "#portal_shop_cat_list_result_" + modul_id;
		} else {
			strModule = 'shop_cat_list';
			strDivErg = "#shop_cat_list_result_" + modul_id;
		}
		$(strDivErg).html(ajax_load).load("/module/" + strModule + "/index.php","module_id=" + modul_id + "&bAjaxLoad=true&shop_cat_id=" + catID + "&orderby=" + orderBy +  "&suchtext=" + SuchText);
		return false;		
	}
}
function shop_cat_sort(modul_id,catID,orderby) {	
	var isPortal; 
	var strModule;
	var strDivErg;
	var orderBy = $("#shop_category_sortby").val();
	var SuchText = $("#txtQuickSearch").val();
	isPortal = $("#isPortal").val();
	
	// Sortiermodus festlegen
	if(orderBy != '') {
		if(isPortal == 'Y') {
			strModule = 'portal_shop_cat_list';
			strDivErg = "#portal_shop_cat_list_result_" + modul_id;
		} else {
			strModule = 'shop_cat_list';
			strDivErg = "#shop_cat_list_result_" + modul_id;
		}
		$(strDivErg).html(ajax_load).load("/module/" + strModule + "/index.php","module_id=" + modul_id + "&bAjaxLoad=true&shop_cat_id=" + catID + "&orderby=" + orderBy +  "&suchtext=" + SuchText);
		return false;		
	}
 
}

function shop_speisekarte_sort(modul_id,catID,orderby) {	
	var isPortal; 
	var strModule;
	var strDivErg;
	var orderBy = $("#shop_category_sortby").val();
	var SuchText = $("#txtQuickSearch").val();
	isPortal = $("#isPortal").val();
	
	// Sortiermodus festlegen
	if(orderBy != '') {
		if(isPortal == 'Y') {
			strModule = 'portal_shop_speisekarte';
			strDivErg = "#portal_shop_speisekarte_list_result_" + modul_id;
		} else {
			strModule = 'shop_speisekarte';
			strDivErg = "#shop_speisekarte_result_" + modul_id;
		}
		$(strDivErg).html(ajax_load).load("/module/" + strModule + "/index.php","module_id=" + modul_id + "&bAjaxLoad=true&shop_cat_id=" + catID + "&orderby=" + orderBy +  "&suchtext=" + SuchText);
		return false;		
	}
 
}
function shop_item_picture_update(path) {
	$("#shop_item_picture_main").attr("src",path);
}
// Moduleinstellungen speichern
function abrechnung_save_form(frm) {
 
	var postData = $('#' + frm).serialize()
    var formURL = $('#' + frm).attr("action");
    $.ajax(
    {
        url : formURL,
        type: "POST",
        data : postData,
        success:function(data, textStatus, jqXHR)
        {
				
				$("#acp_message").html(data);
	 
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
           alert(data + ' ' + errorThrown);
        }
    });
	return false;
}
function set_rechnung_erstellen(kundenid) {
	//var docHeight = $(document).height();	
	$("#acp_message").html(ajax_load).load("/module/portal_ordercentral/index.php", "bAjax=true&kundenid=" + kundenid + "&modus=rechnung");  
}

function set_rechnung_bezahlt(kundenid,invoice_id) {
	//var docHeight = $(document).height();	
	$("#acp_message").html(ajax_load).load("/module/portal_ordercentral/index.php", "bAjax=true&kundenid=" + kundenid + "&modus=rechnungbezahlen&invoice_id=" + invoice_id);  
}
function set_rechnung_versand(kundenid,invoice_id) {
	//var docHeight = $(document).height();	
	$("#acp_message").html(ajax_load).load("/module/portal_ordercentral/index.php", "bAjax=true&kundenid=" + kundenid + "&modus=versandstarten&invoice_id=" + invoice_id);  
}
function set_rechnung_komplett(kundenid,invoice_id) {
	//var docHeight = $(document).height();	
	$("#acp_message").html(ajax_load).load("/module/portal_ordercentral/index.php", "bAjax=true&kundenid=" + kundenid + "&modus=komplett&invoice_id=" + invoice_id);  
}
function set_rechnung_bewerten(kundenid,invoice_id) {
	//var docHeight = $(document).height();	
	$("#acp_message").html(ajax_load).load("/module/portal_ordercentral/index.php", "bAjax=true&kundenid=" + kundenid + "&modus=bewerten&invoice_id=" + invoice_id);  
}
function  image_galery() {
	$('cloud-zoom').zoomimage();
	return false;
}
function onEnterPortal(e,modul_id,cat_id){
	 
	if (e.keyCode == 13) {
		shop_cat_search(modul_id,cat_id);
	}
}

function mini_warenkorb_frame() {
	
	var postData = "bWarenkorbOnly=true"
    var formURL = "/ACP/user_frame_warenkorb.php"
    $.ajax(
    {
        url : formURL,
        type: "POST",
        data : postData,
        success:function(data, textStatus, jqXHR)
        {
				if($("#footer").css("height") == "426px") {
					$("#footer").css("height", "25px");
					$("#shop_footer").html("");					
				} else {
					$("#footer").css("overflow-x", "auto");
					 $('#footer').animate({
						height: "426px", 
						padding:"10px",
						opacity:1.0
					}, 500); 
					//$("#footer").css("height", "300px");
					  
					// Kein Scrolling
										/*if ($(document).height() > $(window).height()) {
											 var scrollTop = ($('html').scrollTop()) ? $('html').scrollTop() : $('body').scrollTop(); // Arbeitet mit Chrome, Firefox, IE...
											 $('html').addClass('noscroll').css('top',-scrollTop);         
										}*/
					$("#shop_footer").html(data);
				}
				
	 
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
           alert(data + ' ' + errorThrown);
        }
    });
	return false;
}
function mini_frame_close() {
	
	if($("#footer").css("height") == "426px") {
		//$("#footer").css("height","25px");
		$("#footer").css("overflow-x", "visible");
		$('#footer').animate({
			height: "25px", 
			padding:"10px",
			opacity:1.0
		}, 500); 
						
		//var scrollTop = parseInt($('html').css('top'));
		//$('html').removeClass('noscroll');
		//$('html,body').scrollTop(-scrollTop);
		//$("html").css("overflow", "auto");
		//$("body").css("overflow", "auto");
		//$("html").css("scroll", "yes");
	}
	
	$('#shop_footer').html(ajax_load).load("/cart/cart_info.php", "bRefreshAjax=true");
	//alert(bWarenkorbOnly);
}

function mini_frame_close_del() {
	
	if($("#footer").css("height") == "426px") {
		//$("#footer").css("height","25px");
		$("#footer").css("overflow-x", "visible");
		$('#footer').animate({
			height: "25px", 
			padding:"10px",
			opacity:1.0
		}, 500); 
						
		//var scrollTop = parseInt($('html').css('top'));
		//$('html').removeClass('noscroll');
		//$('html,body').scrollTop(-scrollTop);
		//$("html").css("overflow", "auto");
		//$("body").css("overflow", "auto");
		//$("html").css("scroll", "yes");
	}
	
	$('#shop_footer').html(ajax_load).load("/cart/cart_info.php", "bRefreshAjax=true&session_dead=true");
	//alert(bWarenkorbOnly);
}

function shop_cart_frame_update_menge(item_id,modul_typ,modul_id,bWarenkorbOnly,neueMenge) {
	
	//$("#shop_footer").html(ajax_load).load("", );
	//alert(neueMenge);
	$.ajax(
	{
		url : "/cart/cart_item_new_amount.php",
		type: "GET",
		data : "shop_item_id=" + item_id + "&module_id=" + modul_id + "&shop_item_menge=" + neueMenge ,
		success:function(data, textStatus, jqXHR)
		{		
			//alert(data);
			if(data.indexOf( "<b>Warenkorb ist leer!</b>" ) !== -1 ) { 
				//if($("#footer").css("height") == "426px") {
					mini_frame_close();
					//$("#footer").css("height","25px");
				//}
				$('#shop_footer').html(data)
			} else {
			
			
			$.ajax(
			{
				url : "/ACP/user_frame_warenkorb.php",
				type: "POST",
				data : "bWarenkorbOnly=" + bWarenkorbOnly,
				success:function(data, textStatus, jqXHR)
				{		
					//alert(data);
					if(data.indexOf( "<b>Warenkorb ist leer!</b>" ) !== -1 ) { 
						//if($("#footer").css("height") == "426px") {
							mini_frame_close();
							//$("#footer").css("height","25px");
						//}
											//alert(data);
					
					}
					$('#shop_footer').html(data)
					return false;
				},
				error: function(jqXHR, textStatus, errorThrown)
				{
					//if fails    
					return false;				
				}
			});
			}
			return false;
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			//if fails    
			return false;				
		}
	});
 
	//$("#box_" + modul_typ + "_" + modul_id).html(ajax_load).load("/ACP/acp_load_modul.php", "module_name=" + modul_typ + "&module_id=" + modul_id);
}

function shop_cart_delete_frame(item_id,modul_typ,modul_id,bWarenkorbOnly) {
	
	//$("#shop_footer").html(ajax_load).load("", );
	
	$.ajax(
	{
		url : "/cart/cart_item_delete.php",
		type: "GET",
		data : "shop_item_id=" + item_id + "&module_id=" + modul_id,
		success:function(data, textStatus, jqXHR)
		{		
			//alert(data);
			if(data.indexOf( "<b>Warenkorb ist leer!</b>" ) !== -1 ) { 
				//if($("#footer").css("height") == "426px") {
					mini_frame_close();
					//$("#footer").css("height","25px");
				//}
			} else {
				$.ajax(
				{
					url : "/ACP/user_frame_warenkorb.php",
					type: "POST",
					data : "bWarenkorbOnly=" + bWarenkorbOnly,
					success:function(data, textStatus, jqXHR)
					{		
						//alert(data);
						if(data.indexOf( "<b>Warenkorb ist leer!</b>" ) !== -1 ) { 
							//if($("#footer").css("height") == "426px") {
								mini_frame_close();
								//$("#footer").css("height","25px");
							//}
						}
						//alert(data);
						$('#shop_footer').html(data)
						return false;
					},
					error: function(jqXHR, textStatus, errorThrown)
					{
						//if fails    
						return false;				
					}
				});
			}
			$('#shop_footer').html(data)
			return false;
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			//if fails    
			return false;				
		}
	});
	

	
 
	//$("#box_" + modul_typ + "_" + modul_id).html(ajax_load).load("/ACP/acp_load_modul.php", "module_name=" + modul_typ + "&module_id=" + modul_id);
}


function mini_zur_kasse_frame(status) {
	var postData = "bWarenkorbOnly=false"
    var formURL = "/ACP/user_frame_warenkorb.php"
    $.ajax(
    {
        url : formURL,
        type: "POST",
        data : postData,
        success:function(data, textStatus, jqXHR)
        {
				if(status == 'NO_RESIZE') {
					//alert(data);
					$("#shop_footer").html(data);
				} else {
					if($("#footer").css("height") == "426px") {
						$("#footer").css("height", "25px");
						$("#shop_footer").html("");
					} else {
						$("#footer").css("overflow-x", "auto");
						$('#footer').animate({
							height: "426px", 
							padding:"10px",
							opacity:1.0
						}, 500); 
						//$("#footer").css("height", "300px");
						//alert("in..");
						
						// Kein Scrolling
										/*if ($(document).height() > $(window).height()) {
											 var scrollTop = ($('html').scrollTop()) ? $('html').scrollTop() : $('body').scrollTop(); // Arbeitet mit Chrome, Firefox, IE...
											 $('html').addClass('noscroll').css('top',-scrollTop);         
										}*/
						//$("html").css("overflow", "hidden");
						//$("body").css("overflow", "hidden");
						//$("html").css("scroll", "no");
						$("#shop_footer").html(data);
					}
				}
				
	 
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
           alert(errorThrown);
        }
    });
	return false;
}

function showPopup_shop_item_customize(item_id,modul_typ,modul_id,bWarenkorbOnly) {
	$.ajax(
    {
        url : '/cart/cart_item_customize.php',
        type: "POST",
        data : "item_id=" + item_id,
        success:function(data, textStatus, jqXHR)
        {
			//alert(data);
			$('#popup').html(data);
			//,width: 450,height: 450,
			dialog = $("#dialog-shop-item-customize_" + item_id).dialog({modal: true,            
            closeOnEscape: true,
            resizable: true });
			dialog.dialog("open");
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
           alert(data + ' ' + errorThrown);
        }
    });
}

function shop_item_save_customize(frm) {
	var postData = $('#' + frm).serialize()
    var formURL = $('#' + frm).attr("action");
	
	//alert(postData);
	
	$.ajax(
    {
        url : formURL,
        type: "POST",
        data : postData,
        success:function(data, textStatus, jqXHR)
        {
			var item_id = $("#shop_item_id").val();
			
			//alert(item_id);
			
			//mini_zur_kasse_frame('NO_RESIZE');
			dialog = $("#dialog-shop-item-customize_" + item_id).dialog({ });
			dialog.dialog("destroy");
			//$("#dialog-shop-item-customize_" + item_id).html('');
			
			var postData = "bWarenkorbOnly=false"
			var formURL = "/ACP/user_frame_warenkorb.php"
			var status = 'NO_RESIZE';
			$.ajax(
			{
				url : formURL,
				type: "POST",
				data : postData,
				success:function(data, textStatus, jqXHR)
				{
						if(status == 'NO_RESIZE') {
							//alert(data);
							$("#shop_footer").html(data);
						} else {
							if($("#footer").css("height") == "426px") {
								$("#footer").css("height", "25px");
								$("#shop_footer").html("");
							} else {
								$("#footer").css("overflow-x", "auto");
								$('#footer').animate({
									height: "426px", 
									padding:"10px",
									opacity:1.0
								}, 500); 
								$("#shop_footer").html(data);
							}
						}
						
			 
					return false;
				},
				error: function(jqXHR, textStatus, errorThrown)
				{
				   alert(errorThrown);
				}
			});			
			return false
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
           alert(' ' + errorThrown);
		   return false;
        }
    });	 
	return false;
}

$(document).ready(function() {	 
	var id;

     $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.scrollup').fadeIn();
        } else {
            $('.scrollup').fadeOut();
        }
    });

    $('.scrollup').click(function () {
        $("html, body").animate({
            scrollTop: 0
        }, 300);
        return false;
    });
	
   $('.order_options').on('click', 'span', function (e) {
   //$('#activator').click(function(){	
			$('#overlay').fadeIn('fast',function(){
				$('#box').animate({'top':'60px'},500);			
				/*$('#box').css('height', '100%');*/
				$('#box').css('height', 'auto');
				/*$('#box').css('max-height', '99%');*/
				$('#box').css('overflow', 'auto');
				$('#box').css('overflow', 'auto');
				//$('#box').css('height', 'auto');
				$('#acp_message').css('padding-bottom', '50px');
				//$('#box').css('margin-bottom', '30px');
			});	
		var strNext = e.target.id.split("_");
		 
		switch(strNext[0]) {
			case 'rechnung':
				$("#overlay_header").html('<h1>Rechnung erstellen</h1>');
				set_rechnung_erstellen(strNext[1]);
				break;
			case 'versandstarten':
				$("#overlay_header").html('<h1>Versandbestätigung senden</h1>');
				set_rechnung_versand(strNext[1],strNext[2]);
				break;
			case 'komplett':
				$("#overlay_header").html('<h1>Transaktion abschließen</h1>');
				set_rechnung_komplett(strNext[1],strNext[2]);
				break;
			case 'bewerten':
				$("#overlay_header").html('<h1>Bewertung abgeben</h1>');
				set_rechnung_bewerten(strNext[1],strNext[2]);
				break;
			case 'zahlungerhalten':
				$("#overlay_header").html('<h1>Zahlung erhalten</h1>');
				set_rechnung_bezahlt(strNext[1],strNext[2]);
				break;
		}
		
    });
	
	//$('#boxclose').on('click', 'a', function (e) {
	$('#boxclose').click(function(){	
		$('#box').animate({'top':'-2000px'},500,function(){
			$('#overlay').fadeOut('fast');
			$('#box').css("height:auto");
			$('#acp_message').css('margin-bottom', '0px');
		});
    });
$( '#footer' ).bind( 'mousewheel DOMMouseScroll', function ( e ) {
  
 var scrollTo = null;

    if (e.type == 'mousewheel') {
        scrollTo = (e.originalEvent.wheelDelta * -1);
    }
    else if (e.type == 'DOMMouseScroll') {
        scrollTo = 40 * e.originalEvent.detail;
    }

    if (scrollTo) {
        e.preventDefault();
        $(this).scrollTop(scrollTo + $(this).scrollTop());
    }
	
  
});
	/*$('#activator').click(function(){
			
			$('#overlay').fadeIn('fast',function(){
				$('#box').animate({'top':'160px,height:100%'},500);
				$('#box').css("height:100%");
				//alert("in");
			});
	});*/
	
	/*$('#boxclose').click(function(){
			$('#box').animate({'top':'-200px'},500,function(){
				$('#overlay').fadeOut('fast');
				$('#box').css("height:auto");
			});
	});*/
	



$(window).scroll(function(){
		var elementPosition = $('#nav_main').offset();
		if(typeof(elementPosition) != "undefined") {
			if($(window).scrollTop() > elementPosition.top){
				  $('#nav_main').css('position','fixed').css('top','0');
				  $('#nav_main').css('width','79.3%');
				  $('#nav_main').css('z-index','10');
			} else {
				$('#nav_main').css('position','static');
				$('#nav_main').css('width','');
				$('#nav_main').css('z-index','10');
			}    
		}
});
});
  $(function() {
    var dialog, form,
 
      // From http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#e-mail-state-%28type=email%29
      emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,
      name = $( "#name" ),
      email = $( "#email" ),
      password = $( "#password" ),
      allFields = $( [] ).add( name ).add( email ).add( password ),
      tips = $( ".validateTips" );
 
    function updateTips( t ) {
      tips
        .text( t )
        .addClass( "ui-state-highlight" );
      setTimeout(function() {
        tips.removeClass( "ui-state-highlight", 1500 );
      }, 500 );
    }
 
    function checkLength( o, n, min, max ) {
      if ( o.val().length > max || o.val().length < min ) {
        o.addClass( "ui-state-error" );
        updateTips( "Length of " + n + " must be between " +
          min + " and " + max + "." );
        return false;
      } else {
        return true;
      }
    }
 
    function checkRegexp( o, regexp, n ) {
      if ( !( regexp.test( o.val() ) ) ) {
        o.addClass( "ui-state-error" );
        updateTips( n );
        return false;
      } else {
        return true;
      }
    }
 
    function addUser() {
      var valid = true;
      allFields.removeClass( "ui-state-error" );
 
      valid = valid && checkLength( name, "username", 3, 16 );
      valid = valid && checkLength( email, "email", 6, 80 );
      valid = valid && checkLength( password, "password", 5, 16 );
 
      valid = valid && checkRegexp( name, /^[a-z]([0-9a-z_\s])+$/i, "Username may consist of a-z, 0-9, underscores, spaces and must begin with a letter." );
      valid = valid && checkRegexp( email, emailRegex, "eg. ui@jquery.com" );
      valid = valid && checkRegexp( password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );
 
      if ( valid ) {
        $( "#users tbody" ).append( "<tr>" +
          "<td>" + name.val() + "</td>" +
          "<td>" + email.val() + "</td>" +
          "<td>" + password.val() + "</td>" +
        "</tr>" );
        dialog.dialog( "close" );
      }
      return valid;
    }
 
    dialog = $( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 300,
      width: 350,
      modal: true,
      buttons: {
        "Create an account": addUser,
        Cancel: function() {
          dialog.dialog( "close" );
        }
      },
      close: function() {
        form[ 0 ].reset();
        allFields.removeClass( "ui-state-error" );
      }
    });
 
    form = dialog.find( "form" ).on( "submit", function( event ) {
      event.preventDefault();
      addUser();
    });
 
    $( "#create-user" ).button().on( "click", function() {
      dialog.dialog( "open" );
    });
  });