<?php 
	#ini_set('display_errors', true);
	#error_reporting(E_WARNING);
	


function getPageLayoutHTML_tpl($layout='',$spalte_links_breite='',$spalte_rechts_breite='',$spalte_mitte_breite='') {
	$html = '';
	#print_r($layout);
	$aryPage['layout'] = $layout;
	$aryPage['spalte_links_breite'] = $spalte_links_breite;
	$aryPage['spalte_rechts_breite'] = $spalte_rechts_breite;
	$aryPage['spalte_mitte_breite'] = $spalte_mitte_breite;
	#print_r($aryPage);
	
	if(!empty($aryPage['spalte_links_breite'])) {
		$colum_links = 'style="width:'.$aryPage['spalte_links_breite'].'"';
	} else {
		$colum_links = '';		
	}
	if(!empty($aryPage['spalte_rechts_breite'])) {
		$colum_rechts = 'style="width:'.$aryPage['spalte_rechts_breite'].'"';
	} else {
		$colum_rechts = '';		
	}
	if(!empty($aryPage['spalte_mitte_breite'])) {
		$colum_mitte = 'style="width:'.$aryPage['spalte_mitte_breite'].'"';
	} else {
		$colum_mitte = '';
	}
			
	switch($aryPage['layout']) {
		case 'col2-left-layout':
 			$col ='col-main';
			
			$html .= '<div class="container-fluid">
			<div class="row">';
				$col ='col-left';
				$html .= '<aside class="col-sm-4">';			
					$html .= getPageColumn($col);
				$html .= '</aside>';
				
				$html .= '<main class="col-sm-8">';						
					$html .= getPageColumn($col);
				$html .= '</main>';
			
			$html .= '</div>
			</div>';	
			break;
		case 'col2-right-layout':
 			$col ='col-main';
			
			$html .= '<div class="container-fluid">
			<div class="row">';
				$html .= '<main class="col-sm-8">';						
					$html .= getPageColumn($col);
				$html .= '</main>';
			
				$col ='col-right';
				$html .= '<aside class="col-sm-4">';			
					$html .= getPageColumn($col);
				$html .= '</aside>';
			$html .= '</div>
			</div>';									

			break;
		case 'col3-layout':
			$html = '<aside class="col-left sidebar" '.$colum_links.'>';
			$col = 'col-left';
			$html .= getPageColumn($col);
			$html .= '</aside>';

			$html .= '<main class="col-main" '.$colum_mitte.'>';
			$col ='col-main';
			$html .= getPageColumn($col);
			$html .= '</main>';
			
			$html .= '<aside class="col-right sidebar" '.$colum_rechts.'>';
			$col ='col-right';
			$html .= getPageColumn($col);
			$html .= '</aside>';
			
			break;
	}

	#echo $aryPage['layout'];
	#echo $html;
	return $html;
}

	
function getPageLayoutHTML($aryPage) {
	$html = '';
#	print_r($aryPage);
	if(!empty($aryPage['spalte_links_breite'])) {
		$colum_links = 'style="width:'.$aryPage['spalte_links_breite'].'"';
	} else {
		$colum_links ='';
	}
	if(!empty($aryPage['spalte_rechts_breite'])) {
		$colum_rechts = 'style="width:'.$aryPage['spalte_rechts_breite'].'"';
	} else {
		$colum_rechts ='';
	}
	if(!empty($aryPage['spalte_mitte_breite'])) {
		$colum_mitte = 'style="width:'.$aryPage['spalte_mitte_breite'].'"';
	} else {
		$colum_mitte ='';
	}
	
	#print_r($aryPage);
	
	switch($aryPage['layout']) {
		case 'col2-left-layout':
			$html = '<aside class="col-left sidebar" '.$colum_links.'>';
			$col = 'col-left';
			$html .= getPageColumn($col);
			$html .= '</aside>';
 
			$html .= '<div class="col-main" '.$colum_mitte.'>';
			$col ='col-main';
			$html .= getPageColumn($col);
			$html .= '</div>';
			break;
		case 'col2-right-layout':
			$html .= '<div class="col-main" '.$colum_mitte.'>';
			$col ='col-main';
			$html .= getPageColumn($col);
			$html .= '</div>';
			
			$html .= '<aside class="col-right sidebar" '.$colum_rechts.'>';
			$col ='col-right';
			$html .= getPageColumn($col);
			$html .= '</aside>';
			break;
		case 'col3-layout':
			$html = '<aside class="col-left sidebar" '.$colum_links.'>';
			$col = 'col-left';
			$html .= getPageColumn($col);
			$html .= '</aside>';

			$html .= '<div class="col-main" '.$colum_mitte.'>';
			$col ='col-main';
			$html .= getPageColumn($col);
			$html .= '</div>';
			
			$html .= '<aside class="col-right sidebar" '.$colum_rechts.'>';
			$col ='col-right';
			$html .= getPageColumn($col);
			$html .= '</aside>';
		default:
			$html = '<aside class="col-left sidebar" '.$colum_links.'>';
			$col = 'col-left';
			$html .= getPageColumn($col);
			$html .= '</aside>';

			$html .= '<div class="col-main" '.$colum_mitte.'>';
			$col ='col-main';
			$html .= getPageColumn($col);
			$html .= '</div>';
			break;
	}

	#echo $aryPage['layout'];
	#echo $html;
	return $html;
}

function getPageColumn($current_container) {
	#################################
	# >> Abfrage der vorhandenen Module auf der Seite 
	#################################
	$strContainer = $current_container;
	$query = "SELECT * FROM module_in_menue WHERE menue_id='".$_GET['page_id']."' AND container='".$current_container."' ORDER BY position asc";
	#$html .=  $query;
		
	if($_SESSION['login'] == 1) {
		#$html .=  $query;
	}

	$qry = DBi::$conn->query($query) or die(mysqli_error());

#echo "IN";

	#######################################		
	# >> Abrufen der Moduldaten 
	#######################################
	while($res = mysqli_fetch_assoc($qry)) {


			$conf['typ']   	= $res["typ"];
			$conf['modul_id'] 	= $res["modul_id"];
			
			$conf['design_box'] 	= $res['container']; // Positionierung auf der Seite 		
			$conf['width'] 	= '100%';
			$conf['design'] = 'box';			
			$conf['pid']	= $_GET['page_id'];
			$conf['column'] = $current_container;
			
			#$html .=  $res["typ"];
			#$conf = isModul_class($conf);
			#print_r($conf);
			$html .= '<section>';
			if(!empty($html)) {
				$html .=  setBuildBox($conf);
			} else {
				$html =  setBuildBox($conf);
			}
			$html .= '</section>';
	} # END WHILE CONTAINER  
	
	return $html; 
}

	function setBuildBox($config) {
		#Einbinden des Moduls
		
		# Absoluten Pfad auslesen
		$path = realpath($_SERVER["DOCUMENT_ROOT"]);		
		require_once($path."/module/".$config["typ"]."/index.php");		
		#echo "^----------IN.........";
		if(isset($pageData['container'])) {
			$config["container"] = $pageData['container'];	
		}
		$modultyp = "LoadModul_".$config["typ"];
		#echo $path.'|'.$config["typ"];
		$result = $modultyp($config); 
		#echo "include";
		
		if($config['column'] =='col-main') {
			$strHModifier = '<h1>';
			$strHModifierClose = '</h1>';
		} else {
			$strHModifier = '<h3>';
			$strHModifierClose = '</h3>';
		}
		$result["title"] = str_replace('&quot;','"',$result["title"]);

		# BOXEN Titel einhängen
		if (!empty($result["title"])) {
 
			# KEIN BOXEN TITEL rendern
			switch($result['modul_typ']) {
				case 'rss_content_view':
						# Kein Titel
						$html = '<article class="block '.$result['modul_typ'].'" id="box_'.$result['modul_typ'].'_'.$result['modul_id'].'" >';
						$html .= $result["content"];
						break;
				default:
					$html = '<article class="block '.$result['modul_typ'].'" id="box_'.$result['modul_typ'].'_'.$result['modul_id'].'" >';
				
					if($_SESSION['login'] == 1) {
						$html .= '<div class="block-title" id="edit_title_'.$result['modul_typ'].'_'.$result['modul_id'].'" onClick="javascript:getEditTitle(\''.$result['modul_id'].'\',\''.$result['modul_typ'].'\')"> '.$strHModifier.$result["title"].$strHModifierClose;
					} else {
						$html .= '<div class="block-title"> '.$strHModifier.$result["title"].$strHModifierClose;
					}
					
					$html .='</div>'. $result["content"].'';						
			}
			
		} else {
			$html = '<article class="block '.$result['modul_typ'].'" id="box_'.$result['modul_typ'].'_'.$result['modul_id'].'" >';
			if($_SESSION['login'] == 1) {
				$html .= '<div class="block-title" id="edit_title_'.$result['modul_typ'].'_'.$result['modul_id'].'" onClick="javascript:getEditTitle(\''.$result['modul_id'].'\',\''.$result['modul_typ'].'\')"> '.$strHModifier.$result["title"].$strHModifierClose;
			} else {
				$html .= '<div class="block-title"> '.$strHModifier.$result["title"].$strHModifierClose;
			}

			$html .= '</div>'. $result["content"];
		}
		
		if($_SESSION['login'] == 1) {
			$html .= '<div class="acp_page_navi"><a onClick="getModuleSettings(\''.$result['modul_typ'].'\',\''.$result['modul_id'].'\')" title="Einstellungen laden">Einstellungen</a> | <a onClick="setModuleDelete(\''.$result['modul_typ'].'\',\''.$result['modul_id'].'\')">L&ouml;schen</a> | <a onClick="setModuleOrder(\''.$result['modul_typ'].'\',\''.$result['modul_id'].'\',\'up\',\''.$_GET['page_id'].'\')">Rauf </a> | <a onClick="setModuleOrder(\''.$result['modul_typ'].'\',\''.$result['modul_id'].'\',\'down\',\''.$_GET['page_id'].'\')">Runter </a></div>';
		} 
		#</article>
		$html .='</article>';
		
		return $html;
	}
	
function shop_speisekarte($strID) {
		## 
	## >> Alle Kategorien durchgehen
	##
	
	$text = '<div class="simple-wrap4 pt4 pb160 align-center">';
	
	$query ="SELECT *,shop_category.shop_cat_id as cat_id  FROM shop_category LEFT JOIN shop_category_parent ON shop_category.shop_cat_id = shop_category_parent.id_shop_category_parent LEFT JOIN shop_categories_images ON shop_categories_images.shop_cat_id =  shop_category.shop_cat_id WHERE shop_category.domain_id ='".$strID."' AND NOT name_de='Alle Kategorien' ORDER BY cat_order_id ASC";
	$res = DBi::$conn->query($query) or die(mysqli_error());
	$iCount =-1;
	$iGesCount = 0;
	$iAutoSize = 1;
	$iItemCount = 0;
	while($strCats = mysqli_fetch_assoc($res)) {
		
		$iCount++;
		$iGesCount++;
		if($iCount == 3) {
			$text .= '</div>';
			$iCount = 0;
			$text .= '<script>';
			$text .= '$( document ).ready(function() {';
			$text .= 'css_column_height(\'.autosize'.$iAutoSize.'\');';			
			$text .= '});';			
			$text .= '</script>';
			$iAutoSize++;
		}
		
		if($iCount == 0) {
			$text .= '<div class="row">';
		}
		
		if($_SESSION['login'] == 1) {
			$strAddSpace = 'style="margin-top:75px"';
			$strItem_action = '<div><img src="/templates/mekong/images/Mekong_Web_Symbole_--02.png"></div>';
		} else {
			$strAddSpace = "";			
			$strItem_action = "";
		}
		#<div class="post grey-back  grey_bottom_edge autosize'.$iAutoSize.'">
		$text .= '<div class=" grid_4  wow fadeInLeft">
			<div class="post grey-back  grey_bottom_edge autosize'.$iAutoSize.'">
			<div class="post-image" '.$strAddSpace.'><img src="'.$strCats['img_path'].'" alt="post image"></div>
			<div class="grey_top_edge">
			<h2 class="dark-2-title-2">'.$strCats['name_de'].'</h2>';
		
		$query = "SELECT * FROM shop_item WHERE shop_cat_id='".$strCats['cat_id']."' ORDER BY item_number ASC";
		#echo $query;
		$res2 = DBi::$conn->query($query) or die(mysqli_error());
		# autoheight'.$iItemCount.'" 
		while($strItem = mysqli_fetch_assoc($res2)) {
			$iItemCount++;
			$text .= '<div class="item_single_box" align="left">';
			$text .= '<form name="frmItemAdd_'.$strItem['shop_item_id'].'" class="'.$iItemCount.'" id="cart_item_add_'.$strItem['shop_item_id'].'" action="cart/cart_item_add.php" method="POST" onSubmit="return cart_item_add_frame(\'cart_item_add_'.$strItem['shop_item_id'].'\');">';
			$text .= '<div>';
			$text .= '<input type="hidden" name="shop_item_id" value="'.$strItem['shop_item_id'].'"/>
					<input type="hidden" name="shop_item_count" value="1"/>
					<input type="hidden" name="shop_item_price" value="'.$strItem['preis'].'"/>';
			$text .= '<div class="speisekarte_nr'.$iItemCount.' spnr">'.
			
			$strItem['item_number'].'&nbsp;&nbsp;</div><strong><div class="speisekarte_preis"> '.number_format($strItem['preis'], 2, ',', '.').' &euro;'.$strItem_action.'</div></strong>
			<div class="item_description autoheight'.$iItemCount.'">
			<a class="frmWarenkorb" onClick="$(this).closest(\'form\').submit()"><strong style="font-weight: 600;">'.$strItem['name_de'].'</strong> ';
			
			$strField = $strItem['beschreibung'];
			#$strField = str_replace("<br>","",$strField);
			$strField = str_replace("<p>","",$strField);
			$strField = str_replace("</p>","",$strField);
			
			$text .= $strField.'</a>';
			#$text .= '<div style="clear:both"></div>';
			$text .= '</div>';
			$text .= '</div>';
			$text .= '<script>';
			$text .= '$( document ).ready(function() {';
			$text .= 'css_column_height(\'.autoheight'.$iItemCount.'\',\''.$iItemCount.'\');';			
			$text .= '});';			
			$text .= '</script>';			
			$text .= '</form>';
			$text .= '</div>';
			
		}
		
		$text .= '</div>';
		$text .= '</div>';
		$text .= '</div>';
	}
	
	$text .= '</div>';
	return $text;
}
function shop_speisekarte_goldenwok($strID,$bIMG) {
		##
	## >> Alle Kategorien durchgehen
	##
	
	$text = '<div class="simple-wrap4 pt4 pb160 align-center">';
	
	$query ="SELECT *,shop_category.shop_cat_id as cat_id  FROM shop_category LEFT JOIN shop_category_parent ON shop_category.shop_cat_id = shop_category_parent.id_shop_category_parent LEFT JOIN shop_categories_images ON shop_categories_images.shop_cat_id =  shop_category.shop_cat_id WHERE shop_category.domain_id ='".$strID."' AND NOT name_de='Alle Kategorien' ORDER BY cat_order_id ASC";
#echo $query;	
$res = DBi::$conn->query($query) or die(mysqli_error());
	$iCount =-1;
	$iGesCount = 0;
	$iAutoSize = 1;
	$iItemCount = 0;
	while($strCats = mysqli_fetch_assoc($res)) {
		
		$iCount++;
		$iGesCount++;
		if($iCount == 3) {
			$text .= '</div>';
			$iCount = 0;
			$text .= '<script>';
			$text .= '$( document ).ready(function() {';
			$text .= 'css_column_height(\'.autosize'.$iAutoSize.'\');';			
			$text .= '});';			
			$text .= '</script>';
			$iAutoSize++;
		}
		
		if($iCount == 0) {
			$text .= '<div class="row">';
		}
		#<div class="post grey-back  grey_bottom_edge autosize'.$iAutoSize.'">
		$text .= '<div class=" grid_4  wow fadeInLeft">
			<div class="post grey-back  grey_bottom_edge autosize'.$iAutoSize.'">';
			
		if($_SESSION['login'] == 1) {
			#$strAddSpace = 'style="margin-top:75px"';
			$strAddSpace = "";			
			$strCSS_img_class = "post-image-admin";			
		} else {
			$strCSS_img_class = "post-image";			
			$strAddSpace = "";			
		}
		 
		if ($bIMG == 'yes' && strlen($strCats['img_path']) > 0) { 
			$text .= '<div class="'.$strCSS_img_class.'" id="img_cat_id_'.$strCats['cat_id'].'" '.$strAddSpace.'>';
			
			if($_SESSION['login'] == 1) {	
				$text .= '<span class="opener" id="opener_'.$strCats['cat_id'].'">';
			}
			
			$text .= '<img  id="img_cat_'.$strCats['cat_id'].'" src="'.$strCats['img_path'].'" alt="Speisekarten-Bild"><span id="tmp"></span>';
			
			if($_SESSION['login'] == 1) {	
				$text .= '</span>';
			}
			
			###################################
			# Upload Möglichkeit
			###################################
			if($_SESSION['login'] == 1) {
$text_delete .= '<script>
  $( function() {
    $( "#shop_item_delete_'.$strItem['shop_item_id'].'" ).dialog({
      autoOpen: false,
	  maxWidth:600,
      maxHeight: 300,
      width: 600,
      height: 300,
      modal: true,
      show: {
        effect: "blind",
        duration: 250
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });
 
    $( "#opener_del_'.$strItem['shop_item_id'].',#opener_link_del_'.$strItem['shop_item_id'].'").on( "click", function() {
      $( "#shop_item_delete_'.$strItem['shop_item_id'].'" ).dialog( "open" );
    });
  } );
  </script>';	

				$text .= '<script>
  $( function() {
    $( "#shop_cat_upload_'.$strCats['cat_id'].'" ).dialog({
      autoOpen: false,
	  maxWidth:600,
      maxHeight: 300,
      width: 600,
      height: 300,
      modal: true,
      show: {
        effect: "blind",
        duration: 250
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });
 
    $( "#opener_'.$strCats['cat_id'].',#opener_link_'.$strCats['cat_id'].'").on( "click", function() {
      $( "#shop_cat_upload_'.$strCats['cat_id'].'" ).dialog( "open" );
    });
  } );
  </script>
<script type="text/javascript">
$( "#shop_bild_upload" ).click(function() {
	
//jQuery(function () {
 
	jQuery(\'#shop-bilder-upload\').uploadProgress({ 
		progressURL:\'/ACP/upload_speisekarte_cat_img.php\',
		displayFields : [\'kb_uploaded\',\'kb_average\',\'est_sec\'],
		start: function() { 
			jQuery(\'#upload-message_'.$strCats['cat_id'].'"\').html(\'<strong>Hochladen wurde gestartet...</strong>\'); 
			jQuery(\'#shop_bild_upload\',this).val(\'<strong>Hochladen... Bitte warten</strong>\');
		},
		success: function() { 
			jQuery(\'#shop_bild_upload_'.$strCats['cat_id'].'\',this).val(\'Datei hochladen\');
			jQuery(this).get(0).reset();
			jQuery(\'#upload-message_'.$strCats['cat_id'].'"\').html(\'<strong>Datei wurde hochgeladen!</strong>\'); 
			
			
		}
	});
	//});
}); 
</script>
  <div id="shop_cat_upload_'.$strCats['cat_id'].'" title="Speisekategorie-Bild hochladen">
  <p>
  <form id="upload_'.$strCats['cat_id'].'" method="post" action="/framework/ajax_upload/upload_speisekarte_cat_img.php" enctype="multipart/form-data">
			<div id="drop_'.$strCats['cat_id'].'">
				Bild für Speisekategorie '.$strCats['name_de'].'<br/><br/>

				<a>Datei auswählen</a>
				<input type="file" name="upl" multiple />
			</div>

			<div id="upload-message_'.$strCats['cat_id'].'""></div>
			<ul>
				<!-- The file uploads will be shown here -->
			</ul>
<input type="hidden" name="shop_cat_id" id="shop_cat_id" value="'.$strCats['cat_id'].'"/>

		</form>
  </p>
</div>  
<script>

    var ul = $(\'#upload_'.$strCats['cat_id'].' ul\');
    // Helper function that formats the file sizes
    function formatFileSize(bytes) {
        if (typeof bytes !== \'number\') {
            return \'\';
        }

        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + \' GB\';
        }

        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + \' MB\';
        }

        return (bytes / 1000).toFixed(2) + \' KB\';
    }
    $(\'#drop a\').click(function(){
        // Simulate a click on the file input button
        // to show the file browser dialog
        $(this).parent().find(\'input\').click();
    });

    // Initialize the jQuery File Upload plugin
    $(\'#upload_'.$strCats['cat_id'].'\').fileupload({
  
        // This element will accept file drag/drop uploading
        dropZone: $(\'#drop_'.$strCats['cat_id'].'\'),

        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function (e, data) {

            var tpl = $(\'<li class="working"><input type="text" value="0" data-width="48" data-height="48"\'+
                \' data-fgColor="#0788a5" data-readOnly="1" data-bgColor="#3e4043" /><p></p><span></span></li>\');

            // Append the file name and file size
            tpl.find(\'p\').text(data.files[0].name)
                         .append(\'<i>\' + formatFileSize(data.files[0].size) + \'</i>\');

            // Add the HTML to the UL element
            data.context = tpl.appendTo(ul);

            // Initialize the knob plugin
            tpl.find(\'input\').knob();

            // Listen for clicks on the cancel icon
            tpl.find(\'span\').click(function(){

                if(tpl.hasClass(\'working\')){
                    jqXHR.abort();
                }

                tpl.fadeOut(function(){
                    tpl.remove();
                });

            });

            // Automatically upload the file once it is added to the queue
            var jqXHR = data.submit();
        },

        progress: function(e, data){
			var shop_id;
            // Calculate the completion percentage of the upload
            var progress = parseInt(data.loaded / data.total * 100, 10);

            // Update the hidden input field and trigger a change
            // so that the jQuery knob plugin knows to update the dial
            data.context.find(\'input\').val(progress).change();
			
            if(progress == 100){
                data.context.removeClass(\'working\');
				var ajax_load = "<img src=\'/image/load.gif\' alt=\'loading...\' />";
				var loadUrl = \'/ACP/acp_img_speisecat_reload.php\';
				$("#img_cat_id_'.$strCats['cat_id'].'").html(ajax_load).load(loadUrl, "shop_cat_id=" + '.$strCats['cat_id'].');
				alert(\'test\' + data);
            }
        },

        fail:function(e, data){
            // Something has gone wrong!
            data.context.addClass(\'error\');
        }

    });
</script>';
  
			}
			$text .= '</div>';
			
		}  
		if($_SESSION['login'] == 1) {
			$text .= '<br/><span class="spanlink opener" id="opener_link_'.$strCats['cat_id'].'"><img height="20" width="20" src="/templates/mekong/images/Mekong_Web_Symbole_+-01.png"> Kategorie Bild hochladen</span>';

		}	


		if($_SESSION['login'] == 1) {
			$text .= '<br/><div id="new_shop_item">
				<span class="spanlink" onClick="javascript:shop_item_new(\''.$strCats['cat_id'].'\')"><img height="20" width="20" src="/templates/mekong/images/Mekong_Web_Symbole_+-01.png"> Neues Produkt in dieser Kategorie anlegen</span><br/>
				<span id="shop_item_new_'.$strCats['cat_id'].'"></span>
			</div>';		
		}
		
		$text .='<div class="grey_top_edge">
			<h2 class="dark-2-title-2">'.$strCats['name_de'].'</h2>';
		
		$query = "SELECT * FROM shop_item WHERE shop_cat_id='".$strCats['cat_id']."' ORDER BY item_number ASC";
		#echo $query;
		$res2 = DBi::$conn->query($query) or die(mysqli_error());
		# autoheight'.$iItemCount.'" 
		while($strItem = mysqli_fetch_assoc($res2)) {
			$iItemCount++;
			
			#################################
			# EINGELOGGT
			#################################
			if($_SESSION['login'] == 1) {
				$strFieldJSNumber = 'onclick="javascript:setShop_item_change(\''.$strItem['shop_item_id'].'\',\'shop_item_number\')"';
				$strFieldJSName = 'onclick="javascript:setShop_item_change(\''.$strItem['shop_item_id'].'\',\'shop_item_name\')"';
				$strFieldJSPrice = 'onclick="javascript:setShop_item_change(\''.$strItem['shop_item_id'].'\',\'shop_item_price\')"';
				$text_delete = '<script>
				  $( function() {
					$( "#shop_item_delete_'.$strItem['shop_item_id'].'" ).dialog({
					  autoOpen: false,
					  maxWidth:600,
					  maxHeight: 300,
					  width: 600,
					  height: 300,
					  modal: true,
    create: function (event, ui) {
        $(".ui-widget-header").hide();
    },
					  show: {
						effect: "blind",
						duration: 250
					  },
					  hide: {
						effect: "explode",
						duration: 1000
					  }
					});
				 
					$( "#opener_del_'.$strItem['shop_item_id'].',#opener_link_del_'.$strItem['shop_item_id'].'").on( "click", function() {
					  $( "#shop_item_delete_'.$strItem['shop_item_id'].'" ).dialog( "open" );
					});
				  } );
				  </script>
				  <div id="shop_item_delete_'.$strItem['shop_item_id'].'" title="Artikel löschen">
				  <p>
				  Möchten Sie den Artikel löschen?<br/><br/>
				  '.$strItem['name_de'].' für '.$strItem['preis'].' &euro;<br/><br/>
				  <br/>
				  <a class="button" href="javascript:set_shop_item_delete_speisekarte(\''.$strItem['shop_item_id'].'\')">Ja</a> | <a class="button" href="javascript:set_shop_item_delete_speisekarte_hide(\''.$strItem['shop_item_id'].'\')">Nein</a>
				  </p>
				  </div>';	
					$strItem_action = '<div id="spanlink"><img id="opener_del_'.$strItem['shop_item_id'].'" src="/templates/mekong/images/Mekong_Web_Symbole_--02.png" width="20px" height="20px" style="float:right"></div> ';
			} else {
				$strFieldJSNumber = '';
				$strFieldJSName = '';
				$strFieldJSPrice = '';
				$text_delete = '';
			}
			
			$text .= $text_delete;
			$text .= '<div class="item_single_box" align="left">';
			#$text .= '<form name="frmItemAdd_'.$strItem['shop_item_id'].'" class="'.$iItemCount.'" id="cart_item_add_'.$strItem['shop_item_id'].'" action="cart/cart_item_add.php" method="POST" onSubmit="return cart_item_add_frame(\'cart_item_add_'.$strItem['shop_item_id'].'\');">';
			$text .= '<div>';
			$text .= '<input type="hidden" name="shop_item_id" value="'.$strItem['shop_item_id'].'"/>
					<input type="hidden" name="shop_item_count" value="1"/>
					<input type="hidden" name="shop_item_price" value="'.$strItem['preis'].'"/>';
			$text .= '<div '.$strFieldJSNumber.' id="shop_item_number_'.$strItem['shop_item_id'].'" class="speisekarte_nr'.$iItemCount.' spnr">'.
			 
			$strItem['item_number'].'&nbsp;&nbsp;</div><strong><div '.$strFieldJSPrice.' id="shop_item_price_'.$strItem['shop_item_id'].'" class="speisekarte_preis"> '.number_format($strItem['preis'], 2, ',', '.').' &euro;</div>'.$strItem_action.'</strong>
			<div '.$strFieldJSName.' id="shop_item_name_'.$strItem['shop_item_id'].'" class="item_description autoheight'.$iItemCount.'">'.$strItem['name_de'];
			#<a class="frmWarenkorb" onClick="$(this).closest(\'form\').submit()"><strong style="font-weight: 600;">'.$strItem['name_de'].'</strong> ';
			
			$strField = $strItem['beschreibung'];
			#$strField = str_replace("<br>","",$strField);
			$strField = str_replace("<p>","",$strField);
			$strField = str_replace("</p>","",$strField);
			
			$text .= $strField.'</a>';
			#$text .= '<div style="clear:both"></div>';
			$text .= '</div>';
			$text .= '</div>';
			$text .= '<script>';
			$text .= '$( document ).ready(function() {';
			$text .= 'css_column_height(\'.autoheight'.$iItemCount.'\',\''.$iItemCount.'\');';			
			$text .= '});';			
			$text .= '</script>';			
			#$text .= '</form>';
			
			$text .= '</div>';
			
		}
		
		
		$text .= '</div>';
		$text .= '</div>';
		$text .= '</div>';
	}
	
	$text .= '</div>';
	$text .= '
<script>
function css_column_height (css_class,icount) {
	var max_height = 0;
	$(css_class).each(function(e) {
	  h = $(this).height();
	  if(typeof(h) != "undefined") {
			if(h > max_height) {
					max_height = h;
			}
	  }
	});
	if(max_height > 0) {
		//alert(css_class.indexOf( ".autosize" ));
		//if( css_class.indexOf( ".autosize" ) !== -1 || css_class.indexOf( ".autosize" ) !== 0) {
		if( css_class.indexOf( ".autosize" ) !== -1) {
			max_height	+= 65;
			$(css_class).height(max_height);		
		} else {	
			if (icount == "1") {
				//console.log(max_height); 
			}
			max_height += 20;
			$(css_class).height(max_height);
			//max_height += 20;
			if (icount == "1") {
				//console.log(max_height); 
			}
			$(".speisekarte_nr" + icount).height(max_height);
			$(".speisekarte_nr" + icount).each(function(e) {
			  h = $(this).height();
			  if(typeof(h) != "undefined") {
					if(h > max_height) {
							max_height = h;
					}
			  }
			});
			//alert(max_height);
			$("#box_id_" + icount).height(max_height);
		}
	}
}
</script>';
	return $text;
}
?>