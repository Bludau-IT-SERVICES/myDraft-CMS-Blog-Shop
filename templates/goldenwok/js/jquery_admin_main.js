var ajax_load = "<img src='image/load.gif' alt='loading...' />";
var TimerWeiterleitung;
    $.ajaxSetup ({
        cache: false
    });

function getTexthtmlEdit(id) {
	$(document).ready(function(){
		var txtContent = $("#modul_texthtml_" + id).html();
		$("#texthtml_" + id).html('<form id="texthtml_editText_"' + id + '><input id="texthtml_id" type="hidden" name="texthtml_id" value="' + id + '"/><textarea class="texthtml"  rows="60" id="modul_texthtml_' + id + '" name="texthtml_content" style="height:400px;width:100%"">' + txtContent + '</textarea><input type="button" id="submit_texthtml" value="Speichern" name="submit_editTitle"/></form>');
		//$("#edit_title_" + modul + "_" + id).html(ajax_load).load("/ACP/acp_save_title.php", "modul=" + modul + "&id=" + id + "&content=" + newValue);
		
		var oFCKeditor = new FCKeditor( 'texthtml_content' ) ;
		oFCKeditor.BasePath	= '/framework/fckeditor/';

		oFCKeditor.ReplaceTextarea() ;
		
		//$('input#submit_texthtml').click( function() {
			//alert('da');
		$("#submit_texthtml").click( function (event) { 
			// Stop form from submitting normally
			event.preventDefault();			
			var texthtml_id = $("#texthtml_id").val();
			var oFCKeditor = FCKeditorAPI.GetInstance("texthtml_content"); 
			
			var fckeditor =  oFCKeditor.GetXHTML(); //$('textarea#modul_texthtml_' + id).val();
			//alert(fckeditor);
			var dataString ='texthtml_id=' + texthtml_id + "&content=" + escape(fckeditor);
            $.ajax({
					type:'POST',
					data:dataString,
					url:'/ACP/acp_save_texthtml_content.php',
					success:function(data) {
					  $("#texthtml_" + id).html(data);
					}
				  });
         });           
		//});
	}); // ready 
}
function setShopItemPictureDelete(shop_id) {
	  $.ajax({
					type:'POST',
					data:"picture_id=" + shop_id,
					url:'/ACP/acp_shop_item_picture_delete.php',
					success:function(data) {
						var shop_id;
						shop_id = $("#shop_id").val();
					   var loadUrl = '/ACP/acp_shop_item_picture_preview.php';
						$("#box_item_picture").html(ajax_load).load(loadUrl, "shop_id=" + shop_id);
					}
         }); 
}

// JB
function IsNumeric(input){
    var RE = /^-{0,1}\d*\.{0,1}\d+$/;
    return (RE.test(input));
}

// JB
function setShop_item_save(cat_id) {
	
	var newValue_number = $("#shop_item_number").val();
	var newValue_name = $("#shop_item_name").val();
	var newValue_price = $("#shop_item_price").val(); 
	var bError = false;
	
	alert(IsNumeric(newValue_price));
	if(newValue_name.length == 0) {		
		bError = true;	
	}
	if(newValue_number.length == 0) {		
		bError = true;
	}
	if(newValue_price.length == 0) {		
		bError = true;
	} else {
		if (IsNumeric(newValue_price) == false) {
			bError = true;	
		}  
	}
	 
	//alert(newValue_number);
	if(bError == false) {
		$("#shop_item_new_" + cat_id).html(ajax_load).load("/ACP/acp_shop_item_ajax_save.php", "modus=new_item&content_number=" + encodeURIComponent(newValue_number) + "&content_name=" + encodeURIComponent(newValue_name) + "&content_price=" + encodeURIComponent(newValue_price) + "&content_cat_id=" + cat_id);
	} else {
		alert('Nicht alle Felder sind ausgefüllt, oder Ihr Preis ist nicht Dezimal.');
	} 
}
// JB
function shop_item_new(cat_id) {
	$(document).ready(function(){
		$("#shop_item_new_" + cat_id).html('<input type="text" id="shop_item_number" placeholder="Nr." name="shop_item_number" value="" style="float:left;width:7%"><input style="float:left;width:65%" type="text" name="shop_item_name" id="shop_item_name" placeholder="Speisename..." value=""><input type="text" placeholder="Preis" name="shop_item_price" id="shop_item_price" value="" style="float:left;width:20%"><br/><input type="button" value="Speise hinzufügen" onClick="setShop_item_save(\'' + cat_id +'\')">');
	});
}
// JB
function setShop_item_change(id,modus) {
	$(document).ready(function(){
		
		switch(modus) {
			case 'shop_item_name':
				var txtID = "#shop_item_name_" + id;
				var txtID_save = "#shop_item_name_" + id + "_save";
				var txtID_html_element = "shop_item_name_" + id;
				var txtID_html_element_save = "shop_item_name_" + id + "_save";
				var oldTitle = $(txtID).html();	
				
				// Nicht doppelt öffnen
				if(oldTitle.indexOf("<input") ==  -1) {				
					$(txtID).html('<input type="text" id="' + txtID_html_element_save + '" name="shop_item_name_change" value="' + oldTitle + '" style="width:75%"/>');	
					
					$(txtID).keyup(function(event){
					if(event.keyCode == 13){				
						var newValue = $(txtID_save).val();
						// Speichern nur bei gültem Wert
						if(typeof(newValue) != "undefined") {
							//alert("senden.."+ newValue);
							$(txtID).html(ajax_load).load("/ACP/acp_shop_item_ajax_save.php", "modus=shop_item_name&id=" + id + "&content=" + encodeURIComponent(newValue));
						}
					} 
					});		
				}			
			break;
			case 'shop_item_number':
				var txtID = "#shop_item_number_" + id;
				var txtID_save = "#shop_item_number_" + id + "_save";
				var txtID_html_element = "shop_item_number_" + id;
				var txtID_html_element_save = "shop_item_number_" + id + "_save";
				var oldTitle = $(txtID).html();	
				
				// Nicht doppelt öffnen
				if(oldTitle.indexOf("<input") ==  -1) {				
					$(txtID).html('<input type="text" id="' + txtID_html_element_save + '" name="shop_item_name_change" value="' + oldTitle + '" style="width:25%"/>');	
					
					$(txtID).keyup(function(event){
					if(event.keyCode == 13){				
						var newValue = $(txtID_save).val();
						// Speichern nur bei gültem Wert
						if(typeof(newValue) != "undefined") {
							//alert("senden.."+ newValue);
							$(txtID).html(ajax_load).load("/ACP/acp_shop_item_ajax_save.php", "modus=shop_item_number&id=" + id + "&content=" + encodeURIComponent(newValue));
						}
					} 
					});		
				}							
				break;
			case 'shop_item_price':
				var txtID = "#shop_item_price_" + id;
				var txtID_save = "#shop_item_price_" + id + "_save";
				var txtID_html_element = "shop_item_price_" + id;
				var txtID_html_element_save = "shop_item_price_" + id + "_save";
				var oldTitle = $(txtID).html();	
				
				// Nicht doppelt öffnen
				if(oldTitle.indexOf("<input") ==  -1) {				  
					$(txtID).html('<input type="text" id="' + txtID_html_element_save + '" name="shop_item_name_change" value="' + oldTitle + '" style="width:25%;float:right"/>');	
					
					$(txtID).keyup(function(event){
					if(event.keyCode == 13){				
						var newValue = $(txtID_save).val();
						// Speichern nur bei gültem Wert
						if(typeof(newValue) != "undefined") {
							//alert("senden.."+ newValue);
							$(txtID).html(ajax_load).load("/ACP/acp_shop_item_ajax_save.php", "modus=shop_item_price&id=" + id + "&content=" + encodeURIComponent(newValue));
						}
					} 
					});		
				}							
				break;				
		} // end switch 
	}); // end document-ready
}

function getEditTitle(id,modul) {
	$(document).ready(function(){
		//alert(id + " " + modul);
		// Alten Wert auslesen
		var oldTitle = $("#edit_title_" + modul + "_" + id + " h1").html();	
		if(oldTitle == null) {
			var oldTitle = $("#edit_title_" + modul + "_" + id + " h3").html();		
		}
		var txtID = "txtEdit_" + modul + "_" + id;
		var txtIDJ = "#txtEdit_" + modul + "_" + id;
		// Textbox hinzufügen
		//  onkeydown="if (event.keyCode == 13) document.getElementById(\'submit_editTitle\').click()"
		//alert(oldTitle);
		if(oldTitle != null) {
			$("#edit_title_" + modul + "_" + id).html('<input type="text" id="' + txtID + '" name="editTitle" value="' + oldTitle + '" style="width:80%"/>Enter drücken');	
			
			$(txtIDJ).keyup(function(event){
			if(event.keyCode == 13){				
				var newValue = $(txtIDJ).val();
				//alert("senden.."+ newValue);
				$("#edit_title_" + modul + "_" + id).html(ajax_load).load("/ACP/acp_save_title.php", "modul=" + modul + "&id=" + id + "&content=" + encodeURIComponent(newValue));
			}
			});
		}
		
		// erneutes Klick Event löschen
		/*document.getElementById("#edit_title_" + modul + "_" + id).onclick = function () {
			return false; // <-- to suppress the default link behaviour
		};*/
	});
}
function setModuleOrder(modul_typ,modul_id,modul_direction,page_id) {
	$("#main_page_container").html(ajax_load).load("/ACP/acp_save_modul_order.php", "modul=" + modul_typ + "&id=" + modul_id + "&modul_direction=" + modul_direction + "&page_id=" + page_id);
}

// Moduleinstellungen speichern
function module_save_form(frm) {
	var modul = $("#acp_get_modul_name").val();
	var id = $("#acp_get_modul_id").val();
	var modus = $("#acp_get_modus").val();
	var dataString = "";
	var page_id = '';
	if (modus == 'new') {
		page_id = $("#acp_get_page_id").val();
	}
	
	if($("#acp_get_hasHTMLModule").val() == '1') {
		var oFCKeditor = FCKeditorAPI.GetInstance("module_texthtml_content"); 
			
		var fckeditor =  oFCKeditor.GetXHTML(); //$('textarea#modul_texthtml_' + id).val();
		//alert(fckeditor);
		dataString ='&module_texthtml_content=' + escape(fckeditor);
	}
	
	var postData = $('#' + frm).serialize()
    var formURL = $('#' + frm).attr("action");
    $.ajax(
    {
        url : formURL,
        type: "POST",
        data : postData + dataString,
        success:function(data, textStatus, jqXHR)
        {
			if(modus == 'edit') {
				$("#box_" + modul + "_" + id).html(ajax_load).load("/ACP/acp_load_modul.php", "module_name=" + modul + "&module_id=" + id);
			} else {
				$("#main_page_container").html(ajax_load).load("/ACP/acp_load_page.php", "page_id=" + page_id);
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
function Weiterleitung(data) {
	window.clearTimeout(TimerWeiterleitung);	
	window.location.href = data;
}

function page_save_form(frm) {
	var modus = $("#acp_get_modus").val();
	var bError = false;
	//alert($('#page_menue_id').val());
	//alert($("#page_menue_id option:selected").val());
	if($('#page_url_name').val() == '') {
		$('#page_url_name_err').html("Bitte Men&uuml;npunkt Namen angeben");
		bError = true;
	}  else {
		$('#page_url_name_err').html("");
	}
	var strWert = $('#page_menue_id').val();
	//alert(strWert);
	if(strWert == 'KEINE-AUSWAHL') {
		$('#module_url_path_err').html("Bitte Men&uuml;npunkt Namen ausw&auml;hlen");
		bError = true;
	}  else {
		$('#module_url_path_err').html("");
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
				if(modus == 'edit') {
					//alert('edit');
					$("#acp_main_new_page_form").html("<h3>Webseite wurde gespeichert. Leite auf die neue Webseite weiter...</h3>");
					TimerWeiterleitung = window.setTimeout(Weiterleitung(data), 2000);
					
					
				} else {
					$("#acp_main_new_page_form").html("<h3>Webseite wurde neu angelegt. Leite auf die neue Webseite weiter...</h3>");
					TimerWeiterleitung = window.setTimeout(Weiterleitung(data), 2000);
				}
	 
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
function shopste_edit_shop_info(frm) {
	var postData = $('#' + frm).serialize()
	var formURL = $('#' + frm).attr("action");
	$.ajax(
	{
		url : formURL,
		type: "POST",
		data : postData,
		success:function(data, textStatus, jqXHR)
		{
			$("#tabs-2").html('Änderungen gespeichert.');
			return false;
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			//if fails     
		}
	});
	return false;
}
function domain_save_form(frm) {
	var modus = $("#acp_get_modus").val();
	var postData = $('#' + frm).serialize()
    var formURL = $('#' + frm).attr("action");
	$.ajax(
    {
        url : formURL,
        type: "POST",
        data : postData,
        success:function(data, textStatus, jqXHR)
        {
			if(modus == 'edit') {
				//alert('edit');
				$("#acp_main_new_domain_form").html("<h3>Webseite wurde gespeichert. Leite auf die neue Webseite weiter...</h3>");
				TimerWeiterleitung = window.setTimeout(Weiterleitung(data), 2000);				
				
			} else {
				$("#acp_main_new_domain_form").html("<h3>Webseite wurde neu angelegt. Leite auf die neue Webseite weiter...</h3>");
				TimerWeiterleitung = window.setTimeout(Weiterleitung(data), 2000);
			}
 
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });
	return false;
}
function shop_save_form(frm) {
	var bError = false;
	var strWert = $('#page_menue_id').val();
	//alert(strWert);
	if(strWert == 'KEINE-AUSWAHL') {
		$('#module_url_path_err').html("Bitte Men&uuml;npunkt Namen ausw&auml;hlen");
		bError = true;
	}  else {
		$('#module_url_path_err').html("");
	}
	if($('#page_url_name').val() == '') {
		bError = true;
		$('#page_url_name_err').html("Bitte Men&uuml;npunkt Namen ausw&auml;hlen");
	}  else {
		$('#page_url_name_err').html("");
	}
	if($('#shop_cat_title').val() == '') {
		bError = true;
		$('#shop_cat_title_err').html("Bitte Men&uuml;npunkt Namen ausw&auml;hlen");
	}  else {
		$('#shop_cat_title_err').html("");
	}	
	if(bError == false) {
		var modus = $("#acp_get_modus").val();
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
				if(modus == 'edit') {
					//alert('edit');
					//$("#acp_main_new_domain_form").html("<h3>Webseite wurde gespeichert. Leite auf die neue Webseite weiter...</h3>");
					//TimerWeiterleitung = window.setTimeout(Weiterleitung(data), 2000);				
					
				} else {
					//TimerWeiterleitung = window.setTimeout(Weiterleitung(data), 2000);
					//$("#acp_main_new_domain_form").html("<h3>Webseite wurde neu angelegt. Leite auf die neue Webseite weiter...</h3>");
				}
	 
				return false;
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				//if fails     
			}
		});
	} else {
		alert('Es wurden nicht alle Pflichtfelder der Eingabemaske ausgefüllt. Bitte korrigieren Sie die Eingabe.');
	}
	return false;
}
function shop_shippment_new_line(shippment_id) {
	$.ajax(
    {
        url : "/ACP/acp_shop_shippment.php",
        type: "POST",
        data : "modus=newline&shippment_id=" + shippment_id,
        success:function(data, textStatus, jqXHR)
        {
			$("#shop_shippment_new_line").append(data); 
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });	
	return false;
}

function shop_shippment_delete(shippment_item) {
	$.ajax(
    {
        url : "/ACP/acp_shop_shippment.php",
        type: "POST",
        data : "modus=delete_item&shop_shippment_detail_id=" + shippment_item,
        success:function(data, textStatus, jqXHR)
        {
			$("#shippment_item_" + shippment_item).html(''); 
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });	
	return false;
} 
function shop_shippment_new_edit_versandart(versandartid){
	$.ajax(
    {
        url : "/ACP/acp_shop_shippment.php",
        type: "POST",
        data : "modus=versandart_edit&versandart_id=" + versandartid,
        success:function(data, textStatus, jqXHR)
        {
			$("#result-module-add").html(data); 
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });	
	return false;	
}
function shop_shippment_new_delete(versandartid){
	check = confirm("Wollen Sie die Versandart  wirklich entfernen?");
	if(check == true) {
		$.ajax(
    {
        url : '/ACP/acp_shop_shippment.php',
        type: "POST",
        data : "modus=versandart_delete&versandart_id=" + versandartid,
        success:function(data, textStatus, jqXHR)
        {
            $("#result-module-add").html(data);
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });
	}
}

function shop_shippment_save_item(frm) {

	var modus = $("#acp_get_modus").val();
	var postData = $('#' + frm).serialize()
    var formURL = $('#' + frm).attr("action");
	$.ajax(
    {
        url : formURL,
        type: "POST",
        data : postData,
        success:function(data, textStatus, jqXHR)
        {
			if(modus == 'edit') {
				//alert('edit');
				$("#test").html(data);
				//TimerWeiterleitung = window.setTimeout(Weiterleitung(data), 2000);				
				
			} else {
				$("#test").html(data);
				//TimerWeiterleitung = window.setTimeout(Weiterleitung(data), 2000);
			}
 
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });
	return false;
}
function shop_shippment_versandartnewline(frm) {

	var modus = $("#acp_get_modus").val();
	var postData = $('#' + frm).serialize()
    var formURL = $('#' + frm).attr("action");
	$.ajax(
    {
        url : formURL,
        type: "POST",
        data : postData,
        success:function(data, textStatus, jqXHR)
        {
			if(modus == 'edit') {
				//alert('edit');
				$("#result-module-add").html(data);
				//TimerWeiterleitung = window.setTimeout(Weiterleitung(data), 2000);		
				return false;
				
			} else {
				$("#result-module-add").html(data);
				//TimerWeiterleitung = window.setTimeout(Weiterleitung(data), 2000);
				return false;
			}
 
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });
	return false;
}
function shop_attributset_new_attribut(frm) {

	var attributset = $("#attributset_id").val();
	var postData = $('#' + frm).serialize()
    var formURL = $('#' + frm).attr("action");
	$.ajax(
    {
        url : formURL,
        type: "POST",
        data : postData,
        success:function(data, textStatus, jqXHR)
        {
			attribute_liste_load(attributset);
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });
	return false;
}
function shop_attributset_save_values(frm) {

	//var modus = $("#acp_get_modus").val();
	var postData = $('#' + frm).serialize()
    var formURL = $('#' + frm).attr("action");
	$.ajax(
    {
        url : formURL,
        type: "POST",
        data : postData,
        success:function(data, textStatus, jqXHR)
        {
			$("#frmAttribute").html(data);
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });
	return false;
}

function shop_attribute_new_attribute(attributset_id) {

	//var modus = $("#acp_get_modus").val();
	var postData = "modus=attribut_new&attributset_id=" + attributset_id;
    var formURL = "/ACP/acp_shop_attribute.php";
	$.ajax(
    {
        url : formURL,
        type: "POST",
        data : postData,
        success:function(data, textStatus, jqXHR)
        {
			$("#frmAttribute").html(data);
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });
	return false;
}

function shop_attribute_value_new_line(attribut_id) {
	$.ajax(
    {
        url : "/ACP/acp_shop_attribute.php",
        type: "POST",
        data : "modus=shop_attribute_new_attribute&attribute_id=" + attribut_id,
        success:function(data, textStatus, jqXHR)
        {
			$("#shop_attribute_value_new_line_" + attribut_id).append(data); 
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });	
	return false;
}
function shop_attributset_new_save() {
	var postData = $('#' + frm).serialize()
    var formURL = $('#' + frm).attr("action");
	$.ajax(
    {
        url : formURL,
        type: "POST",
        data : postData,
        success:function(data, textStatus, jqXHR)
        {
			$("#frmAttribute").html(data);
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });
	return false;
}
function shop_attributset_delete(attributeset_id) {
	$.ajax(
    {
        url : "/ACP/acp_shop_attribute.php",
        type: "POST",
        data : "modus=shop_attributeset_delete&attributeset_id=" + attributeset_id,
        success:function(data, textStatus, jqXHR)
        {
			$("#frmAttribute").html(data); 
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });	
	return false;
}

function shop_attributset_new() {
	$.ajax(
    {
        url : "/ACP/acp_shop_attribute.php",
        type: "POST",
        data : "modus=shop_attributeset_new",
        success:function(data, textStatus, jqXHR)
        {
			$("#frmAttribute").html(data); 
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });	
	return false;
}
function attribute_set_save_form(frm) {
	//var modus = $("#acp_get_modus").val();
	var postData = $('#' + frm).serialize()
    var formURL = $('#' + frm).attr("action");
	$.ajax(
    {
        url : formURL,
        type: "POST",
        data : postData,
        success:function(data, textStatus, jqXHR)
        {
			$("#shop_attribut_attribute").html(data);
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });
	return false;
}
function shop_attribute_delete(attribute_id) {
	$.ajax(
    {
        url : "/ACP/acp_shop_attribute.php",
        type: "POST",
        data : "modus=delete_attribute&shop_attribute_id=" + attribute_id,
        success:function(data, textStatus, jqXHR)
        {
			attribute_liste_load(data); 
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });	
	return false;
}
function shop_attribute_generate(frm) {
	var postData = $('#' + frm).serialize()
    var formURL = $('#' + frm).attr("action");
	$.ajax(
    {
        url : formURL,
        type: "POST",
        data : postData,
        success:function(data, textStatus, jqXHR)
        {
			$("#shop_attribut_kombination").html(data);
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });
	return false;
}
function shop_attribute_item_clear(shop_id) {
	$.ajax(
    {
        url : "/ACP/acp_shop_attribute_kombination.php",
        type: "POST",
        data : "modus=delete_attribute_combinations&shop_item_id=" + shop_id,
        success:function(data, textStatus, jqXHR)
        {
			$("#combination_clear_message").html(data); 
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });	
	return false;
}
function shop_attribute_value_delete(attribute_value_id) {
	$.ajax(
    {
        url : "/ACP/acp_shop_attribute.php",
        type: "POST",
        data : "modus=delete_attribute_value&shop_attribute_value_id=" + attribute_value_id,
        success:function(data, textStatus, jqXHR)
        {
			$("#attribute_value_" + attribute_value_id).html(''); 
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });	
	return false;
} 
function attribute_liste_load(attribute_set_id) {

	var postData = "modus=edit&attribut_set=" + attribute_set_id;
    var formURL = '/ACP/acp_shop_attribute.php';
	$.ajax(
    {
        url : formURL,
        type: "POST",
        data : postData,
        success:function(data, textStatus, jqXHR)
        {
			$("#frmAttribute").html(data);
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });
	return false;
}
function benutzer_save_item_form(frm) {

	var bError=false;
	
	if($('#txtBenutzername').val() == '') {
		bError = true;
		$('#txtBenutzername_err').html("Bitte <strong>Benutzernamen</strong> eingeben!<br/>");
	}  else {
		$('#txtBenutzername_err').html("");
	}
	if($('#txtVorName').val() == '') {
		bError = true;
		$('#txtVorName_err').html("Bitte <strong>Vornamen</strong> eingeben!<br/>");
	}  else {
		$('#txtVorName_err').html("");
	}
	if($('#txtNachName').val() == '') {
		bError = true;
		$('#txtNachName_err').html("Bitte <strong>Nachname</strong> eingeben!<br/>");
	}  else {
		$('#txtNachName_err').html("");
	}
	if($('#txtEmail').val() == '') {
		bError = true;
		$('#txtEmail_err').html("Bitte <strong>Email-Adresse</strong> eingeben!<br/>");
	}  else {
		$('#txtEmail_err').html("");
	}
	if($('#txtPasswort1').val() == '') {
		bError = true;
		$('#txtPasswort1_err').html("Bitte <strong>1. Passwort</strong> eingeben!<br/>");
	}  else {
		$('#txtPasswort1_err').html("");
	}
	if($('#txtPasswort2').val() == '') {
		bError = true;
		$('#txtPasswort2_err').html("Bitte <strong>2. Passwort</strong> eingeben!<br/>");
	}  else {
		$('#txtPasswort2_err').html("");
	}	
	
	if(bError == false) {		
		//var modus = $("#acp_get_modus").val();
		var postData = $('#' + frm).serialize();	
		var formURL = $('#' + frm).attr("action");
		
		//alert('test--' + postData + ' / '  + formURL);
		
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR)
			{
				 
				//alert('test--' + data + ' / '  + formURL);
				$("#result-module-add").html(data);
				return false;
			},
			error: function(xhr, status, error)
			{
				alert("An AJAX error occured: " + status + "\nError: " + error + "\nError detail: " + xhr.responseText);

				//if fails     
			}
		});
	} else {
		alert('Es müssen noch Pflichtfelder ausgefüllt werden.');
	}
		return false;
}
function shop_save_item_form(frm) {

	var bError = false;
	var strWert = $('#page_menue_id').val();
	//alert(strWert);
	/*if(strWert == 'KEINE-AUSWAHL') {
		$('#module_url_path_err').html("Bitte &uuml;bergeordneten Men&uuml;npunkt ausw&auml;hlen");
		bError = true;
	}  else {
		$('#module_url_path_err').html("");
	}*/
	
	if($('#page_url_name').val() == '') {
		bError = true;
		$('#page_url_name_err').html("Bitte Men&uuml;npunkt Namen ausw&auml;hlen");
	}  else {
		$('#page_url_name_err').html("");
	}
	if($('#shop_item_artnummer').val() == '') {
		bError = true;
		$('#shop_item_artnummer_err').html("Bitte Artikelnummer eingeben");
	}  else {
		$('#shop_item_artnummer_err').html("");
	}	
	if($('#shop_item_gewicht').val() == '') {
		bError = true;
		$('#shop_item_gewicht_err').html("Bitte Gewicht in Kilo angeben");
	}  else {
		$('#shop_item_gewicht_err').html("");
	}	
	if($('#shop_item_menge').val() == '') {
		bError = true;
		$('#shop_item_menge_err').html("Bitte Lagerbestand eintragen, bei keinem Lager einfach 1000 Stück eintragen");
	}  else {
		$('#shop_item_menge_err').html("");
	}		
	if($('#shop_item_price_read').val() == '') {
		bError = true;
		$('#shop_item_price_read_err').html("Bitte g&uuml;ltigen Preis eintragen");
	}  else {
		$('#shop_item_price_read_err').html("");
	}
	if($('#shop_item_name').val() == '') {
		bError = true;
		$('#shop_item_name_err').html("Bitte Produktnamen eintragen");
	}  else {
		$('#shop_item_name').html("");
	}
	if($('#shop_item_mwst').val() == '') {
		bError = true;
		$('#shop_item_mwst_err').html("Bitte Mehrwertsteuer eintragen");
	}  else {
		$('#shop_item_mwst').html("");
	}		
	
	if(bError == false) {
		var modus = $("#acp_get_modus").val();
		var postData = $('#' + frm).serialize();

		var oFCKeditor = FCKeditorAPI.GetInstance("shop_item_beschreibung"); 
		
		var fckeditor =  oFCKeditor.GetXHTML(); //$('textarea#modul_texthtml_' + id).val();
		//alert(fckeditor);
		var dataString ='shop_item_beschreibung=' + escape(fckeditor);
			
		var formURL = $('#' + frm).attr("action");
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postData + "&" + dataString,
			success:function(data, textStatus, jqXHR)
			{
				if(modus == 'edit') {
					//alert('edit');
					$("#acp_main_shop_item_form").html("<h3>Shop Artikel wurde gespeichert</h3>");
					//TimerWeiterleitung = window.setTimeout(Weiterleitung(data), 2000);				
					
				} else {
					//alert("'" + data + "'");
					$.ajax(
						{
							url : "/ACP/acp_shop_item.php",
							type: "POST",
							data : "schritt=bilder_upload&shop_id=" +data,
							success:function(data, textStatus, jqXHR)
							{				
								$("#acp_main_shop_item_form").html(data);
								
								return false;
							},
							error: function(jqXHR, textStatus, errorThrown)
							{
								//if fails     
							}
						});
				}
	 
				return false;
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				//if fails     
			}
		});
	} else {
		alert('Es wurden nicht alle Pflichtfelder ausgefüllt. Schauen Sie sich die Eingabemaske erneut an, es befindet sich ein Hinweistext hinter dem Eingabefeld');
	}
	return false;
}


function getModuleSettings(modulename,id) {
    var loadUrl = 'module/' + modulename + '/admin/form/' + modulename + '-settings.php';
    $("#modul_" + modulename + "_" + id).html(ajax_load).load(loadUrl, "module_name=" + modulename + "&id=" + id + "&modus=edit");
}

function setModuleDelete(modulename,id) {
	var name = $('#edit_title_' + modulename + '_' + id + ' h3').html();
	check = confirm("Wollen Sie das Modul " + name + " wirklich entfernen?");
	if(check == true) {
		$.ajax(
    {
        url : '/ACP/acp_delete_modul.php',
        type: "POST",
        data : "module_name=" + modulename + "&module_id=" + id,
        success:function(data, textStatus, jqXHR)
        {
            $('#box_' + modulename + '_' + id).html("<h2>Modul gel&ouml;scht!</h2>");
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });
	}
}
function getShopItemUpdate(shop_id) {
	var loadUrl = 'ACP/acp_shop_item.php';
	$("#result-module-add").html(ajax_load).load(loadUrl,"modus=edit&shop_id=" + shop_id);      
}
function shop_shippment_load_new() {
	var loadUrl = 'ACP/acp_shop_shippment.php';
	$("#result-module-add").html(ajax_load).load(loadUrl,"modus=versandart_newline");
	return false;
}

function shop_shippment_load(shippment_id) {
	var loadUrl = 'ACP/acp_shop_shippment.php';
	$("#result-module-add").html(ajax_load).load(loadUrl,"modus=list_detail&shippment_id=" + shippment_id);
}

function shop_order_subtable_details(order_id) {
	var loadUrl = 'ACP/mekong_acp_shop_order_list_subtable_details.php';
	$("#shop_order_details_" + order_id).html(ajax_load).load(loadUrl,"&order_id=" + order_id);	
}

// JB NEW
function setBestellung_status(order_id,bestellstatus) {
	var bestelldatum = $("#bestellung_datum").val();
		$.ajax(
    {
        url : '/ACP/mekong_acp_shop_orderlist_bestellstatus.php',
        type: "POST",
        data : "orderstatus=" + bestellstatus + "&order_id=" + order_id,
        success:function(data, textStatus, jqXHR)
        { 
			var loadUrl = 'ACP/mekong_acp_shop_orderlist.php';
			$("#result-module-add").html(ajax_load).load(loadUrl,'bestelldatum=' + bestelldatum);
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });
	
}
function core_toggle(id) {
	$(id).toggle('fast');
}

function setCore_datum(id,modus) {
	var datum_de = $("#" + id).val();
	var loadUrl = 'ACP/mekong_acp_shop_orderlist.php?bestelldatum=' + datum_de + "&modus=" + modus;
	$("#result-module-add").html(ajax_load).load(loadUrl,"");
}

function set_page_delete(seiten_id) {
	if(seiten_id == 'Delete_page') {
		if($("#acp_page_id").val() != '') {
			var loadUrl = 'ACP/acp_delete_page.php';
			var seiten_id = $("#acp_page_id").val();
			$("#result-module-add").html(ajax_load).load(loadUrl,"modus=page_delete&page_id=" + seiten_id);
		}
	} else {
		var loadUrl = 'ACP/acp_delete_page.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"modus=show_delete_warning&page_id=" + seiten_id);
	}
}
function set_shop_item_delete_speisekarte_hide(shop_item_id) {
	$( "#shop_item_delete_" + shop_item_id).dialog("close");
	
}
function set_shop_item_delete_speisekarte(shop_item_id) {
	

		$.ajax(
    {
        url : '/ACP/acp_delete_shop_item.php',
        type: "POST",
        data : "modus=shop_item_page_delete&shop_item_id=" + shop_item_id,
        success:function(data, textStatus, jqXHR)
        {
			var strModus = $("#acp_shop_itemlist_modus").val();
			$.ajax(
			{
				url : '/ACP/acp_shop_item_list.php',
				type: "POST",
				data : "modus=" + strModus + "&shop_item_id=" + shop_item_id,
				success:function(data, textStatus, jqXHR)
				{
					var strModus = $("#acp_shop_itemlist_modus").val();
					//alert(strModus);
					$.ajax(
					{
						url : '/ACP/acp_shop_item_list.php',
						type: "POST",
						data : "modus=" + strModus + "&shop_item_id=" + shop_item_id,
						success:function(data, textStatus, jqXHR)
						{
							$("#result-module-add").html(data);
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
            //$("#result-module-add").html(data);
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });
}

function set_shop_item_delete(shop_item_id) {
	
	check = confirm("Wollen Sie den Shop Artikel wirklich entfernen?");
	if(check == true) {
		$.ajax(
    {
        url : '/ACP/acp_delete_shop_item.php',
        type: "POST",
        data : "modus=shop_item_page_delete&shop_item_id=" + shop_item_id,
        success:function(data, textStatus, jqXHR)
        {
			var strModus = $("#acp_shop_itemlist_modus").val();
			$.ajax(
			{
				url : '/ACP/acp_shop_item_list.php',
				type: "POST",
				data : "modus=" + strModus + "&shop_item_id=" + shop_item_id,
				success:function(data, textStatus, jqXHR)
				{
					var strModus = $("#acp_shop_itemlist_modus").val();
					//alert(strModus);
					$.ajax(
					{
						url : '/ACP/acp_shop_item_list.php',
						type: "POST",
						data : "modus=" + strModus + "&shop_item_id=" + shop_item_id,
						success:function(data, textStatus, jqXHR)
						{
							$("#result-module-add").html(data);
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
            //$("#result-module-add").html(data);
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });
	}
}

function set_shop_item_activator(shop_item_id,status) {
	if(status == 'Y') {
		check = confirm("Wollen Sie den Artikel inaktiv setzen?");
	} else {
		check = confirm("Wollen Sie den Artikel aktiv setzen?");
	}
	if(check == true) {
		$.ajax(
			{
			url : '/api.php',
			type: "POST",
			data : "modus=set_shopste_item_activator&enabled=" + status + "&shop_item_id=" + shop_item_id,
			success:function(data, textStatus, jqXHR)
			{
				var strModus = $("#acp_shop_itemlist_modus").val();
				//alert(strModus);
				$.ajax(
				{
					url : '/ACP/acp_shop_item_list.php',
					type: "POST",
					data : "modus=" + strModus + "&shop_item_id=" + shop_item_id,
					success:function(data, textStatus, jqXHR)
					{
						$("#result-module-add").html(data);
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
}
function acp_item_list_pageing(seite) {
	var strModus = $('#acp_shop_itemlist_modus').val();
	var strListType = $('#acp_shop_itemlist_type').val();
	var strFileName = "";
	if(strListType == 'lieferservice') {
		strFileName = "acp_lieferservice_shop_item_list.php";
	} else {
		strFileName = "acp_shop_item_list.php";		
	}
	$.ajax(
    {
        url : '/ACP/' + strFileName,
        type: "POST",
        data : "seite=" + seite + "&modus=" + strModus,
        success:function(data, textStatus, jqXHR)
        {
            $("#acp_main_shop_item_form").html(data);
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });
	return false;
}
function set_shop_item_stock_0(shop_item_id) {
	
	check = confirm("Wollen Sie den Lagerbestand auf 0 ändern?");
	if(check == true) {
		$.ajax(
    {
        url : '/api.php',
        type: "POST",
        data : "modus=set_shopste_item_ordered_byID&SetMenge=0&shop_item_id=" + shop_item_id,
        success:function(data, textStatus, jqXHR)
        {
            var strModus = $("#acp_shop_itemlist_modus").val();
				//alert(strModus);
				$.ajax(
				{
					url : '/ACP/acp_shop_item_list.php',
					type: "POST",
					data : "modus=" + strModus + "&shop_item_id=" + shop_item_id,
					success:function(data, textStatus, jqXHR)
					{
						$("#result-module-add").html(data);
						return false;
					},
					error: function(jqXHR, textStatus, errorThrown)
					{
						//if fails     
					}
				});
				//$("#result-module-add").html(data);
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });
	}
}

function acp_shop_item_delete_imported(strModus) {
	var strSort;
	var strCatId;
	var shop_item_id;
	$.ajax(
    {
        url : '/ACP/acp_shop_item_list.php',
        type: "POST",
        data : "modus=" + strModus + "&sort=" + strSort + "&catid=" + strCatId,
        success:function(data, textStatus, jqXHR)
        {
            var strModus = $("#acp_shop_itemlist_modus").val();
			//alert(strModus);
			$.ajax(
			{
				url : '/ACP/acp_shop_item_list.php',
				type: "POST",
				data : "modus=" + strModus + "&shop_item_id=" + shop_item_id + "&message=" + data,
				success:function(data, textStatus, jqXHR)
				{
					$("#result-module-add").html(data);
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
function acp_shop_item_search(strModus) {
	var strSort;
	var strSuche;
	strSuche = $("#txtSucheArtikel").val();
	
	$.ajax(
    {
        url : '/ACP/acp_shop_item_list.php',
        type: "POST",
        data : "modus=" + strModus + "&suche=" + strSuche,
        success:function(data, textStatus, jqXHR)
        {
            $("#result-module-add").html(data);
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });	
}
function acp_shop_item_list(strModus) {
	var strSort;
	var strCatId;
	strCatId = $("#marktplatz_shop_category").val();
	
	$.ajax(
    {
        url : '/ACP/acp_shop_item_list.php',
        type: "POST",
        data : "modus=" + strModus + "&sort=" + strSort + "&catid=" + strCatId + "&init=Y",
        success:function(data, textStatus, jqXHR)
        {
            $("#result-module-add").html(data);
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });
}

function setLocationOpen(strURL) {
	$("#result-module-add").html(ajax_load).load(strURL);
}
function getRechnung(order_id) {
	  event.preventDefault();
    window.open($("#re_" + order_id).attr("href"), "popupWindow", "width=600,height=600,scrollbars=yes");
}

$(document).ready(function() {	


   // Modul Form laden
   $(".modalInput").click(function(){
		var modulename = $('#optModul').val();	
		var loadUrl = 'module/' + modulename + '/admin/form/' + modulename + '-settings.php';
		$("#result-module-add").html(ajax_load).load(loadUrl, "module_name=" + modulename + "&modus=new");
   });
   // LAYOUT ACP
   $("#acp_shop_category_layout").click(function() {
		var loadUrl = 'ACP/acp_shop_category.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"modus=new");      
   });   
	$("#acp_shop_products_layout").click(function() {
		var loadUrl = 'ACP/acp_shop_item.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"modus=new");      
   });      
   $("#acp_shop_products_list_layout").click(function() {
		var loadUrl = 'ACP/acp_lieferservice_shop_item_list.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"modus=bySKU&init=Y");      
   });   
   // Benutzerverwaltung
   $("#acp_userverwaltung_list_layout").click(function() {
		var loadUrl = 'ACP/acp_benutzerverwaltung.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"");
   });
   
   // Statistik Bestellungen
   $("#acp_statistik_bestellungen_layout").click(function() {
		var loadUrl = 'ACP/acp_statistik_bestellungen.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"");
   });
   $("#acp_rechnungslayout_layout").click(function() {
		var loadUrl = 'ACP/acp_rechnungslayout.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"");
   });

   $("#acp_terminbestellungen_layout").click(function() {
		var loadUrl = 'ACP/acp_terminbestellungen.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"");
   });
   $("#acp_statistik_bestellungen_layout").click(function() {
		var loadUrl = 'ACP/acp_statistik_bestellungen.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"");
   });
   
   
   
	// NEW LIEFERSERVICE JB
   $("#mekong_acp_shop_orders_layout").click(function() {
		var loadUrl = 'ACP/mekong_acp_shop_orderlist.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"");
   });  
        
   // ACP OHNE LAYOUT Page Form laden
   $("#acp_new_page").click(function() {
		var loadUrl = 'ACP/acp_form_page_settings.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"modus=new");      
   });
   
    $("#acp_edit_page").click(function() {
		var loadUrl = 'ACP/acp_form_page_settings.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"modus=edit");      
   });
    $("#acp_domain_page").click(function() {
		var loadUrl = 'ACP/acp_form_domain_settings.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"modus=edit");      
   });
	$("#acp_shop_category").click(function() {
		var loadUrl = 'ACP/acp_shop_category.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"modus=new");      
   });   
   $("#acp_shop_category_portal").click(function() {
		var loadUrl = 'ACP/acp_shop_category.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"modus=new");      
   });   
	$("#acp_shop_products").click(function() {
		var loadUrl = 'ACP/acp_shop_item.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"modus=new");      
   });   
   $("#acp_shop_products_list").click(function() {
		var loadUrl = 'ACP/acp_shop_item_list.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"modus=bySKU&init=Y");      
   });   
   $("#acp_shop_shipping").click(function() {
		var loadUrl = 'ACP/acp_shop_shippment.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"");
   });
   
   $("#acp_shop_orders").click(function() {
		var loadUrl = 'ACP/acp_shop_order_list.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"");
   });	
   
    $("#acp_shop_attribute").click(function() {
		var loadUrl = 'ACP/acp_shop_attribute.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"");
   });
 
// NEW JB
   $("#mekong_acp_shop_orders").click(function() {
		var loadUrl = 'ACP/mekong_acp_shop_orderlist.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"");
   });
   
   
   /* $('#shop_attributset').on('change', function() {
			alert( this.value ); // or $(this).val()
   }); */
   $('#shop_attributset').on("change",function(){
    var option = $("option:selected",this).val();
	alert(option);
    console.log(option);
	});
 });
function setTextboxValue(id,source) {
	var strName = $("#" + source).val();	
	$("#" + id).val(strName);
}