var ajax_load = "<img src='image/load.gif' alt='loading...' />";
var TimerWeiterleitung;
    $.ajaxSetup ({
        cache: false
    });
	
// JB NEW
function setBestellung_status(order_id,bestellstatus) {
		$.ajax(
    {
        url : '/ACP/mekong_acp_shop_orderlist_bestellstatus.php',
        type: "POST",
        data : "orderstatus=" + bestellstatus + "&order_id=" + order_id,
        success:function(data, textStatus, jqXHR)
        { 
			var loadUrl = 'ACP/mekong_acp_shop_orderlist.php';
			$("#result-module-add").html(ajax_load).load(loadUrl,"");
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails     
        }
    });
	
}

function getTexthtmlEdit(id) {
	$(document).ready(function(){
		var txtContent = $("#modul_texthtml_" + id).html();
		$("#texthtml_" + id).html('<form id="texthtml_editText_"' + id + '><input id="texthtml_id" type="hidden" name="texthtml_id" value="' + id + '"/><textarea class="texthtml"  rows="60" id="modul_texthtml_' + id + '" name="editor1" style="height:400px;width:100%"">' + txtContent + '</textarea><input type="button" id="submit_texthtml" value="Speichern" name="submit_editTitle"/></form>');
		//$("#edit_title_" + modul + "_" + id).html(ajax_load).load("/ACP/acp_save_title.php", "modul=" + modul + "&id=" + id + "&content=" + newValue);
		
		//var oFCKeditor = new CKeditor( 'texthtml_content' ) ;
		//oFCKeditor.BasePath	= '/framework/ckeditor/';

		var editor = CKEDITOR.replace('editor1');
		//oFCKeditor.ReplaceTextarea() ;
		//CKEDITOR.replace( 'texthtml_content');
		
		//$('input#submit_texthtml').click( function() {
			//alert('da');
		$("#submit_texthtml").click( function (event) { 
			// Stop form from submitting normally
			event.preventDefault();			
			var texthtml_id = $("#texthtml_id").val();
			
			//cKEditor
			var editorData= CKEDITOR.instances['modul_texthtml_' + texthtml_id].getData();
         			 
			var dataString ='texthtml_id=' + texthtml_id + "&content=" + escape(editorData);
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
		// Textbox hinzuf??gen
		//  onkeydown="if (event.keyCode == 13) document.getElementById(\'submit_editTitle\').click()"
		//alert(oldTitle);
		if(oldTitle != null) {
			$("#edit_title_" + modul + "_" + id).html('<input type="text" id="' + txtID + '" name="editTitle" value="' + oldTitle + '" style="width:80%"/>Enter dr??cken');	
			
			$(txtIDJ).keyup(function(event){
			if(event.keyCode == 13){				
				var newValue = $(txtIDJ).val();
				//alert("senden.."+ newValue);
				$("#edit_title_" + modul + "_" + id).html(ajax_load).load("/ACP/acp_save_title.php", "modul=" + modul + "&id=" + id + "&content=" + encodeURIComponent(newValue));
			}
			});
		}
		
		// erneutes Klick Event l??schen
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
		//var oFCKeditor = FCKeditorAPI.GetInstance("module_texthtml_content"); 
			
		//var fckeditor =  oFCKeditor.GetXHTML(); //$('textarea#modul_texthtml_' + id).val();
		
		//cKEditor
		var editorData= CKEDITOR.instances['module_texthtml_content'].getData();
         			
					
		//alert(fckeditor);
		dataString ='&module_texthtml_content=' + escape(editorData);
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
			$("#tabs-2").html('??nderungen gespeichert.');
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
 
		if($("#acp_get_hasHTMLModule").val() == '1') {
			//var oFCKeditor = FCKeditorAPI.GetInstance("module_texthtml_content"); 			
			//var fckeditor =  oFCKeditor.GetXHTML(); //$('textarea#modul_texthtml_' + id).val();
			//alert(fckeditor);
			
			//cKEditor
			var editorData= CKEDITOR.instances['module_texthtml_content'].getData();
         			
					
			dataString ='&module_texthtml_content=' + escape(editorData);
		}
		
		var postData = $('#' + frm).serialize() + dataString
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
					//TimerWeiterleitung = window.setTimeout(Weiterleitung(data), 2000);				
					
				} else {
					TimerWeiterleitung = window.setTimeout(Weiterleitung(data), 2000);
					$("#acp_main_new_domain_form").html("<h3>Webseite wurde neu angelegt. Leite auf die neue Webseite weiter...</h3>");
				}
	 
				return false;
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				//if fails     
			}
		});
	} else {
		alert('Es wurden nicht alle Pflichtfelder der Eingabemaske ausgef??llt. Bitte korrigieren Sie die Eingabe.');
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
		$('#shop_item_menge_err').html("Bitte Lagerbestand eintragen, bei keinem Lager einfach 1000 St??ck eintragen");
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

		 
		var editorData= CKEDITOR.instances['shop_item_beschreibung'].getData();

			
		//var oFCKeditor = FCKeditorAPI.GetInstance("shop_item_beschreibung"); 
		
		//var fckeditor =  oFCKeditor.GetXHTML(); //$('textarea#modul_texthtml_' + id).val();
		//alert(fckeditor);
		var dataString ='shop_item_beschreibung=' + escape(editorData);
			
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
		alert('Es wurden nicht alle Pflichtfelder ausgef??llt. Schauen Sie sich die Eingabemaske erneut an, es befindet sich ein Hinweistext hinter dem Eingabefeld');
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
	var loadUrl = 'ACP/acp_shop_order_list_subtable_details.php';
	$("#shop_order_details_" + order_id).html(ajax_load).load(loadUrl,"&order_id=" + order_id);	
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
	alert(strModus); 
	$.ajax(
    {
        url : '/ACP/acp_shop_item_list.php',
        type: "POST",
        data : "seite=" + seite + "&modus=" + strModus,
        success:function(data, textStatus, jqXHR)
        {
            $("#acp_main_shop_item_form").html(data);
			return false;
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            alert(errorThrown);
			//if fails     
			return false;
        }
    });
}
function set_shop_item_stock_0(shop_item_id) {
	
	check = confirm("Wollen Sie den Lagerbestand auf 0 ??ndern?");
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
        data : "modus=" + strModus + "&sort=" + strSort + "&catid=" + strCatId,
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

$(document).ready(function() {	


   // Modul Form laden
   $(".modalInput").click(function(){
		var modulename = $('#optModul').val();	
		var loadUrl = 'module/' + modulename + '/admin/form/' + modulename + '-settings.php';
		$("#result-module-add").html(ajax_load).load(loadUrl, "module_name=" + modulename + "&modus=new");
   });
   
   // Page Form laden
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
		$("#result-module-add").html(ajax_load).load(loadUrl,"modus=byLastChange");      
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
 
    $("#acp_news_category").click(function() {
		var loadUrl = 'ACP/acp_news_category.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"modus=new");
   });
   
    $("#acp_news_content").click(function() {
		var loadUrl = 'ACP/acp_news_post.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"modus=new");
   });

   $("#acp_rss_category").click(function() {
		var loadUrl = 'ACP/acp_rss_category.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"modus=new");
   });
   
    $("#acp_rss_content").click(function() {
		var loadUrl = 'ACP/acp_rss_post.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"modus=new");
   }); 
    $("#acp_rss_quelle").click(function() {
		var loadUrl = 'ACP/acp_rss_quelle.php';
		$("#result-module-add").html(ajax_load).load(loadUrl,"modus=new");
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