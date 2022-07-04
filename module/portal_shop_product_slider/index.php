<?php 
session_start();

function getSubKategorie_slider_slider($iParrentCat,$strIDs,$level) {

	$query = "SELECT * FROM shop_category_parent LEFT JOIN shop_category ON shop_category_parent.shop_cat_id=shop_category.shop_cat_id	WHERE shop_category_parent.shop_cat_parent=".$iParrentCat." AND shop_category.domain_id='".$_SESSION['domain_id']."'  ORDER BY name_".$_SESSION['language']." ASC"; 
	
	if($iParrentCat == 601) {
		#echo $query.'-'.$level;
	}
	$resCat = DBi::$conn->query($query) or die(mysqli_error());
	
	while($strCatMenue = mysqli_fetch_assoc($resCat)) {	
		$strIDs .= " OR shopste_marktplatz_cat=".$strCatMenue['shop_cat_id'];
		#echo $strIDs; 
		$strIDs = getSubKategorie_slider_slider($strCatMenue['shop_cat_id'],$strIDs,$level+1);
		#echo $strCatMenue['shop_cat_id'];
	} 
	
	return $strIDs;
	
}	
  
####################################
# >> TEXTHTML Modul 
####################################
function LoadModul_portal_shop_product_slider($config) {

		$dataTextHTML = mysqli_fetch_array(DBi::$conn->query("SELECT * FROM modul_portal_shop_product_slider WHERE id=".$config['modul_id']));
		 
		$module_in_menue = mysqli_fetch_assoc(DBi::$conn->query("SELECT * FROM module_in_menue WHERE modul_id='".$config['modul_id']."' AND typ='shop_cart_order'"));
		#echo "IN";
		
		$dataTextHTML['typ'] = 'modul_portal_shop_product_slider';
		
		$text = '<div class="content" id="modul_'.$config['typ'].'_'.$config['modul_id'].'">';
		
		$text .= convertUmlaute($dataTextHTML["content_".$_SESSION['language']]);
		$titel = convertUmlaute($dataTextHTML["title_".$_SESSION['language']]);
		
			$query = "SELECT * from domains WHERE domain_id='".$_SESSION['domain_id']."'";			
			$resDomainData = DBi::$conn->query($query) or die(mysqli_error());
			$domain_pages = mysqli_fetch_assoc($resDomainData);
		
		if($text == '') {   
			$text = convertUmlaute($dataTextHTML["content_de"]); 
		} 
		
		if($titel == '') { 
			$titel = convertUmlaute($dataTextHTML["title_de"]); 
		} 
		  
		// && $config["container"]
		if($_SESSION['login'] == '1'  AND $module_in_menue['container'] == 'col-main') {
			$strReturn = getMember($dataTextHTML['last_usr']);
			if(!empty($strReturn)) {
				$ary = explode(" ",$dataTextHTML['lastchange']);
				$german_de = getDateDE($ary[0]);
				$titel .= '</h1> - '.$strReturn.' - '.$german_de.' '.$ary[1];
			}
		}
		
		# Eingeloggt 
		if (@$_SESSION['login'] == '1')  { 
			if($titel == '') { 
				$titel = "Kein Titel"; 
			} 
		} 
		
		$text .= ' <!-- Caption Style -->
    <style> 
        .captionOrange, .captionBlack
        {
            color: #fff;
            font-size: 20px;
            line-height: 30px;
            text-align: center;
            border-radius: 4px;
        }
        .captionOrange
        {
            background: #EB5100;
            background-color: rgba(235, 81, 0, 0.6);
        }
        .captionBlack
        {
        	font-size:16px;
            background: #000;
            background-color: rgba(0, 0, 0, 0.4);
        }
        a.captionOrange, A.captionOrange:active, A.captionOrange:visited
        {
        	color: #ffffff;
        	text-decoration: none;
        }
        a.captionOrange:hover
        {
            color: #eb5100;
            text-decoration: underline;
            background-color: #eeeeee;
            background-color: rgba(238, 238, 238, 0.7);
        }
        .bricon
        {
            background: url(/framework/slider-master/img/browser-icons.png);
        }
    </style>
 
    <script type="text/javascript" src="/framework/slider-master/js/jssor.js"></script>
    <script type="text/javascript" src="/framework/slider-master/js/jssor.slider.mini.js"></script>
    <script>
        jQuery(document).ready(function ($) {
            var options = {
                $AutoPlay: true,                                    //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
                $AutoPlayInterval: 4000,                            //[Optional] Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000
                $PauseOnHover: 1,                                   //[Optional] Whether to pause when mouse over if a slider is auto playing, 0 no pause, 1 pause for desktop, 2 pause for touch device, 3 pause for desktop and touch device, 4 freeze for desktop, 8 freeze for touch device, 12 freeze for desktop and touch device, default value is 1
                $ArrowKeyNavigation: true,   			            //[Optional] Allows keyboard (arrow key) navigation or not, default value is false
                $SlideDuration: 800,                                //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
                $MinDragOffsetToSlide: 20,                          //[Optional] Minimum drag offset to trigger slide , default value is 20
                //$SlideWidth: 600,                                 //[Optional] Width of every slide in pixels, default value is width of slides container
                //$SlideHeight: 300,                                //[Optional] Height of every slide in pixels, default value is height of slides container
                $SlideSpacing: 0, 					                //[Optional] Space between each slide in pixels, default value is 0
                $DisplayPieces: 1,                                  //[Optional] Number of pieces to display (the slideshow would be disabled if the value is set to greater than 1), the default value is 1
                $ParkingPosition: 0,                                //[Optional] The offset position to park slide (this options applys only when slideshow disabled), default value is 0.
                $UISearchMode: 1,                                   //[Optional] The way (0 parellel, 1 recursive, default value is 1) to search UI components (slides container, loading screen, navigator container, arrow navigator container, thumbnail navigator container etc).
                $PlayOrientation: 1,                                //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, 5 horizental reverse, 6 vertical reverse, default value is 1
                $DragOrientation: 1,                                //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)
                $ArrowNavigatorOptions: {                       //[Optional] Options to specify and enable arrow navigator or not
                    $Class: $JssorArrowNavigator$,              //[Requried] Class to create arrow navigator instance
                    $ChanceToShow: 1,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
                    $AutoCenter: 2,                                 //[Optional] Auto center arrows in parent container, 0 No, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
                    $Steps: 1                                       //[Optional] Steps to go for each navigation request, default value is 1
                },
                $ThumbnailNavigatorOptions: {
                    $Class: $JssorThumbnailNavigator$,              //[Required] Class to create thumbnail navigator instance
                    $ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
                    $ActionMode: 1,                                 //[Optional] 0 None, 1 act by click, 2 act by mouse hover, 3 both, default value is 1
                    $AutoCenter: 0,                                 //[Optional] Auto center thumbnail items in the thumbnail navigator container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 3
                    $Lanes: 1,                                      //[Optional] Specify lanes to arrange thumbnails, default value is 1
                    $SpacingX: 3,                                   //[Optional] Horizontal space between each thumbnail in pixel, default value is 0
                    $SpacingY: 3,                                   //[Optional] Vertical space between each thumbnail in pixel, default value is 0
                    $DisplayPieces: 9,                              //[Optional] Number of pieces to display, default value is 1
                    $ParkingPosition: 260,                          //[Optional] The offset position to park thumbnail
                    $Orientation: 1,                                //[Optional] Orientation to arrange thumbnails, 1 horizental, 2 vertical, default value is 1
                    $DisableDrag: false                            //[Optional] Disable drag or not, default value is false
                }
            };
            var jssor_slider1 = new $JssorSlider$("'.$config['modul_id'].'_slider1_container", options);
            //responsive code begin
            //you can remove responsive code if you dont want the slider scales while window resizes
            function ScaleSlider() {
                var bodyWidth = document.body.clientWidth;
                if (bodyWidth)
                    jssor_slider1.$ScaleWidth(Math.min(bodyWidth, 980));
                else
                    window.setTimeout(ScaleSlider, 30);
            }
            ScaleSlider();
            $(window).bind("load", ScaleSlider);
            $(window).bind("resize", ScaleSlider);
            $(window).bind("orientationchange", ScaleSlider);
            //responsive code end
        });
    </script>
    <div style="position: relative; width: 100%;overflow: hidden;">
        <div style="position: relative; left: 50%; width: 5164px; text-align: center; margin-left: -2500px;">
            <!-- Jssor Slider Begin -->
            <div id="'.$config['modul_id'].'_slider1_container" style="position: relative; margin: 0 auto;
                top: 0px; left: 0px; width: 980px; height: 400px;">
                <!-- Loading Screen -->
                <div u="loading" style="position: absolute; top: 0px; left: 0px;">
                    <div style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block;
                        top: 0px; left: 0px; width: 100%; height: 100%;">
                    </div>
                    <div style="position: absolute; display: block; background: url(../img/loading.gif) no-repeat center center;
                        top: 0px; left: 0px; width: 100%; height: 100%;">
                    </div>
                </div>
                <!-- Slides Container -->
                <div u="slides" style="cursor: move; position: absolute; left: 0px; top: 0px; width: 980px;
                    height: 400px; overflow: hidden;">';

			
			$ids = getSubKategorie_slider_slider($dataTextHTML['shop_category'],"",0);
			 
			if($dataTextHTML['zufall'] == 'Y') {
				$strColumZufall = ' ORDER BY rand()';	
			} else {
				$strColumZufall = ' ORDER BY updated_at DESC';	
			}
			
			$query="SELECT * FROM shop_item WHERE shopste_marktplatz_cat='".$dataTextHTML['shop_category']."' ".$ids." AND system_closed_shop='N' AND item_enabled='Y' ".$strColumZufall." LIMIT 0,".$dataTextHTML['produkt_anzahl'];
			#echo $query;
			$resShopItem = DBi::$conn->query($query) or die(mysqli_error());
			while($ShopItem = mysqli_fetch_assoc($resShopItem)) {
				#echo $ShopItem['name_de'];
				
				$query="SELECT * FROM shop_item_picture WHERE shop_item_id='".$ShopItem['shop_item_id']."'";	
				$res = DBi::$conn->query($query) or die('ERR:002:'.mysqli_error());
				$strBild = mysqli_fetch_assoc($res);
				
				$pathItem = 'http://'.$_SERVER['SERVER_NAME'].'/'.getPathUrl($_SESSION['language'],$ShopItem['shopste_marktplatz_menue_id']);
				$text .= '<div>';
				
                $text .= '
                <div>
                        <div style="position: absolute; width: 480px; height: 300px; top: 10px; left: 10px;
                            text-align: left; line-height: 1.8em; font-size: 12px;">
							
							<img style="position: absolute; top: 23px; left: 480px; width: auto; height: auto;" u="image" src="'.str_replace('/orginal/','/detail/',$strBild['picture_url']).'" />
                            <br />
							 <span style="display: block; line-height: 1.1em; font-size: 2.5em; color: #000;">
                                Shopste Marktplatz
                            </span><br/>
                            <span style="display: block; line-height: 1em; text-transform: uppercase; font-size: 24px;
                                color: #000;">'.$ShopItem['name_de'].'</span>
                            <br />
                            <br />
							<span style="display: block; line-height: 1.em; font-size: 2.5em; color: #000;">
                                '.str_replace('.',',',$ShopItem['preis']).' EUR
                            </span>
                           
                            <br />
                            <span style="display: block; line-height: 1.1em; font-size: 1.5em; color: #FFFFFF;">
                                
                            </span>
                            <br />
                            <br />
                            <a class="button" href="'.$pathItem.'">
                                Artikelansicht
                            </a>
                        </div>
                         
                    </div>
                   
                </div>
        ';
			}
        
    $text .= ' </div></div>';
                  $text .= '
                <!-- Arrow Navigator Skin Begin -->
                <style>
                    /* jssor slider arrow navigator skin 07 css */
                    /*
                    .jssora07l              (normal)
                    .jssora07r              (normal)
                    .jssora07l:hover        (normal mouseover)
                    .jssora07r:hover        (normal mouseover)
                    .jssora07ldn            (mousedown)
                    .jssora07rdn            (mousedown)
                    */
                    .jssora07l, .jssora07r, .jssora07ldn, .jssora07rdn {
                        position: absolute;
                        cursor: pointer;
                        display: block;
                        background: url(../img/a07.png) no-repeat;
                        overflow: hidden;
                    }
                    .jssora07l {
                        background-position: -5px -35px;
                    }
                    .jssora07r {
                        background-position: -65px -35px;
                    }
                    .jssora07l:hover {
                        background-position: -125px -35px;
                    }
                    .jssora07r:hover {
                        background-position: -185px -35px;
                    }
                    .jssora07ldn {
                        background-position: -245px -35px;
                    }
                    .jssora07rdn {
                        background-position: -305px -35px;
                    }
                </style>
                <!-- Arrow Left -->
                <span u="arrowleft" class="jssora07l" style="width: 50px; height: 50px; top: 123px;
                    left: 8px;"></span>
                <!-- Arrow Right -->
                <span u="arrowright" class="jssora07r" style="width: 50px; height: 50px; top: 123px;
                    right: 8px"></span>
                <!-- Arrow Navigator Skin End -->
                <!-- ThumbnailNavigator Skin Begin -->
                <div u="thumbnavigator" class="jssort04" style="position: absolute; width: 600px;
                    height: 60px; right: 0px; bottom: 0px;">
                    <!-- Thumbnail Item Skin Begin -->
                    <style>
                        /* jssor slider thumbnail navigator skin 04 css */
                        /*
                        .jssort04 .p            (normal)
                        .jssort04 .p:hover      (normal mouseover)
                        .jssort04 .pav          (active)
                        .jssort04 .pav:hover    (active mouseover)
                        .jssort04 .pdn          (mousedown)
                        */
                        .jssort04 .w, .jssort04 .pav:hover .w {
                            position: absolute;
                            width: 60px;
                            height: 30px;
                            border: #0099FF 1px solid;
                        }
                        * html .jssort04 .w {
                            width: /**/ 62px;
                            height: /**/ 32px;
                        }
                        .jssort04 .pdn .w, .jssort04 .pav .w {
                            border-style: solid;
                        }
                        .jssort04 .c {
                            width: 62px;
                            height: 32px;
                            filter: alpha(opacity=45);
                            opacity: .45;
                            transition: opacity .6s;
                            -moz-transition: opacity .6s;
                            -webkit-transition: opacity .6s;
                            -o-transition: opacity .6s;
                        }
                        .jssort04 .p:hover .c, .jssort04 .pav .c {
                            filter: alpha(opacity=0);
                            opacity: 0;
                        }
                        .jssort04 .p:hover .c {
                            transition: none;
                            -moz-transition: none;
                            -webkit-transition: none;
                            -o-transition: none;
                        }
                    </style>
                    <div u="slides" style="bottom: 25px; right: 30px;">
                        <div u="prototype" class="p" style="position: absolute; width: 62px; height: 32px; top: 0; left: 0;">
                            <div class="w">
                                <div u="thumbnailtemplate" style="width: 100%; height: 100%; border: none; position: absolute; top: 0; left: 0;"></div>
                            </div>
                            <div class="c" style="position: absolute; background-color: #000; top: 0; left: 0">
                            </div>
                        </div>
                    </div>
                    <!-- Thumbnail Item Skin End -->
                </div>
                <!-- ThumbnailNavigator Skin End -->
                <a style="display: none" href="http://www.jssor.com">Image Slider</a>
            </div>
            <!-- Jssor Slider End -->
        </div>
    ';
 
  
		
		$text .= '<div style="clear:both"></div></div>'; // config modus 

		
	  $result = array("title"=>$titel,"content"=>$text,"modul_id"=>$config['modul_id'],"modul_typ"=>$config['typ']);

	  return $result;
 } 
 ?>