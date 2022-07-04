<?
session_start();
session_register('login');
session_register('current_benutzer_id');
session_register('aktuelle_domain');
session_register('lang_variable');  
session_register('debug');  
session_register('letzteseite');

#---->Datenbank Einstellungen----------->
$mysql_host		=	'localhost';
$mysql_user		=	'bo_bocontent';
$mysql_pw		=	'tNjAdW513';
$mysql_db		=	'bo_bocontent';
$main_connect = mysql_connect ($mysql_host, $mysql_user, $mysql_pw) or die(mysql_error());
$main_db =	mysql_select_db ($mysql_db, $main_connect) or die(mysql_error());
mysql_query("SET NAMES 'utf8'");
mysql_query("SET CHARACTER SET 'utf8'");
#---->Datenbank Einstellungen----------->



require_once ("../../../../frameworks/xajax/xajax_core/xajax.inc.php");
$xajax = new xajax();
$xajax->setFlag("debug",false); 

function buildGruppen($input) {
	$objResponse = new xajaxResponse();   
	$html = '';

	$objResponse->assign("pageSelectHeaderGruppeTD","onmouseover","");
	$objResponse->assign("pageSelectHeaderGruppeTD","onmouseout","");
	$objResponse->assign("pageSelectHeaderGruppeTD","style.background","#CCCCCC");

	$objResponse->addEvent("pageSelectHeaderDomainTD","onmouseover","this.style.background='#DDDDDD'");
	$objResponse->addEvent("pageSelectHeaderDomainTD","onmouseout","this.style.background='#EEEEEE'");
	$objResponse->assign("pageSelectHeaderDomainTD","style.background","#EEEEEE");

	$objResponse->addEvent("pageSelectHeaderSeiteTD","onmouseover","this.style.background='#DDDDDD'");
	$objResponse->addEvent("pageSelectHeaderSeiteTD","onmouseout","this.style.background='#EEEEEE'");
	$objResponse->assign("pageSelectHeaderSeiteTD","style.background","#EEEEEE");


	$gruppenQry = mysql_query("SELECT * FROM domain_gruppen order by name ASC");
		while($domaingruppenRes = mysql_fetch_array($gruppenQry)) {

	$domainQry = mysql_query("SELECT * 
	FROM domains_in_domain_gruppen
	LEFT JOIN domains ON domains_in_domain_gruppen.domains_id = domains.id
	WHERE domains_in_domain_gruppen.domain_gruppen_id ='$domaingruppenRes[id]'
	ORDER BY domains.name ASC 
	");
		while($domainRes = mysql_fetch_array($domainQry)) {
		
		$anzahl[$domaingruppenRes[id]] += mysql_num_rows(mysql_query("SELECT id FROM menue WHERE domain_id='$domainRes[id]'"));

		}
		
		
		if($anzahl[$domaingruppenRes[id]] > 0) { $html.='<div style="cursor:hand; border:1px #DDDDDD solid; margin-top:4px; padding:2px;" onmouseover="this.style.background=(\'EEEEEE\');" onmouseout="this.style.background=(\'FFFFFF\')" onClick="xajax_buildDomains('.$domaingruppenRes[id].',\''.$input.'\');">'.$domaingruppenRes[name].'</div>'."\n"; }
		}
	//$objResponse->assign("pageSelectHeaderTD","innerHTML","Gruppen");	
	$objResponse->assign("pageSelectTD","innerHTML",$html);	
	return $objResponse;
}


function buildDomains($domain_gruppen_id,$input) {
	$objResponse = new xajaxResponse();   
	$html='';

	$objResponse->addEvent("pageSelectHeaderGruppeTD","onmouseover","this.style.background='#DDDDDD'");
	$objResponse->addEvent("pageSelectHeaderGruppeTD","onmouseout","this.style.background='#EEEEEE'");
	$objResponse->assign("pageSelectHeaderGruppeTD","style.background","#EEEEEE");

	$objResponse->assign("pageSelectHeaderDomainTD","onmouseover","");
	$objResponse->assign("pageSelectHeaderDomainTD","onmouseout","");
	$objResponse->assign("pageSelectHeaderDomainTD","style.background","#CCCCCC");

	$objResponse->addEvent("pageSelectHeaderSeiteTD","onmouseover","this.style.background='#DDDDDD'");
	$objResponse->addEvent("pageSelectHeaderSeiteTD","onmouseout","this.style.background='#EEEEEE'");
	$objResponse->assign("pageSelectHeaderSeiteTD","style.background","#EEEEEE");


	$gruppenQry = mysql_query("SELECT * FROM domain_gruppen order by name ASC");
	$domainQry = mysql_query("SELECT * 
	FROM domains_in_domain_gruppen
	LEFT JOIN domains ON domains_in_domain_gruppen.domains_id = domains.id
	WHERE domains_in_domain_gruppen.domain_gruppen_id ='$domain_gruppen_id'
	ORDER BY domains.name ASC 
	");
		while($domainRes = mysql_fetch_array($domainQry)) {
		
		$anzahl = mysql_num_rows(mysql_query("SELECT id FROM menue WHERE domain_id='$domainRes[id]'"));
		if($anzahl > 0) { $html.= '<div style="cursor:hand; border:1px #DDDDDD solid; margin-top:4px; padding:2px;" onmouseover="this.style.background=(\'EEEEEE\');" onmouseout="this.style.background=(\'FFFFFF\')" onClick="xajax_buildSeiten('.$domainRes[id].',\''.$input.'\')">'.$domainRes[name].'</div>'."\n"; }
		}
	$objResponse->assign("pageSelectTD","innerHTML",$html);	

	return $objResponse;
}


function buildSeiten($domain,$input) {
	$objResponse = new xajaxResponse();   
	$html = '';

	$objResponse->addEvent("pageSelectHeaderGruppeTD","onmouseover","this.style.background='#DDDDDD'");
	$objResponse->addEvent("pageSelectHeaderGruppeTD","onmouseout","this.style.background='#EEEEEE'");
	$objResponse->assign("pageSelectHeaderGruppeTD","style.background","#EEEEEE");

	$objResponse->addEvent("pageSelectHeaderDomainTD","onmouseover","this.style.background='#DDDDDD'");
	$objResponse->addEvent("pageSelectHeaderDomainTD","onmouseout","this.style.background='#EEEEEE'");
	$objResponse->assign("pageSelectHeaderDomainTD","style.background","#EEEEEE");

	$objResponse->assign("pageSelectHeaderSeiteTD","onmouseover","");
	$objResponse->assign("pageSelectHeaderSeiteTD","onmouseout","");
	$objResponse->assign("pageSelectHeaderSeiteTD","style.background","#CCCCCC");
	
function display_menue($parent, $level, $domain, $input) { 
   // retrieve all children of $parent 
   $result = mysql_query("SELECT * FROM menue_parent
		LEFT JOIN menue ON menue_parent.menue_id=menue.id
		WHERE menue_parent.parent_id='$parent' && domain_id='$domain' order by sortierung asc"); 

   // display each child 


   while ($row = mysql_fetch_array($result)) { 
  $narf .='<div style="cursor:hand; border:1px #DDDDDD solid; margin-top:4px; padding:2px;" onmouseover="this.style.background=(\'EEEEEE\');" onmouseout="this.style.background=(\'FFFFFF\')" onClick="xajax_selectPage('.$row[id].',\''.$input.'\')">';
		 for ($i=0;$i<$level;$i++)
		 
  {
   $narf .='......';
  }
  $narf .= $row["name_de"].'</div>'."\n";
      $narf .=    display_menue($row[menue_id], $level+1, $domain, $input); 
}
   return $narf;
} 

$html .= display_menue('0',0,$domain,$input);
$objResponse->assign("pageSelectTD","innerHTML",$html);	
return $objResponse;
}


function pageSelect($old_id,$input,$preselect=0) {
	global $aktuelle_domain,$aktuelle_domain_gruppe;
	$objResponse = new xajaxResponse(); 
	$html = '<table width="400" border="0" cellpadding="4" cellspacing="1" bgcolor="#CCCCCC" align="center">
	  <tr>
	    <td width="126" bgcolor="#EEEEEE" id="pageSelectHeaderGruppeTD" style="cursor:hand; border:1px #DDDDDD solid; margin-top:4px; padding:2px;" onmouseover="this.style.background=(\'DDDDDD\');" onmouseout="this.style.background=(\'EEEEEE\')" onClick="xajax_buildGruppen(\''.$input.'\');">Gruppe</td>
	    <td width="126" bgcolor="#EEEEEE" id="pageSelectHeaderDomainTD" style="cursor:hand; border:1px #DDDDDD solid; margin-top:4px; padding:2px;" onmouseover="this.style.background=(\'DDDDDD\');" onmouseout="this.style.background=(\'EEEEEE\')" onClick="xajax_buildDomains('.$aktuelle_domain_gruppe.',\''.$input.'\');">Domain</td>
	    <td width="126" bgcolor="#EEEEEE" id="pageSelectHeaderSeiteTD" style="cursor:hand; border:1px #DDDDDD solid; margin-top:4px; padding:2px;" onmouseover="this.style.background=(\'DDDDDD\');" onmouseout="this.style.background=(\'EEEEEE\')" onClick="xajax_buildSeiten('.$aktuelle_domain.',\''.$input.'\');">Seite</td>
	    <td width="20" bgcolor="#DDDDDD" style="cursor:hand; border:1px #DDDDDD solid; margin-top:4px; padding:2px;" onmouseover="this.style.background=(\'EEEEEE\');" onmouseout="this.style.background=(\'DDDDDD\')" onClick="xajax_selectPage('.$old_id.',\''.$input.'\');"><div align="center"><strong>X</strong></div></td>
	  </tr>
	  <tr>
	    <td colspan="4" height="400" bgcolor="#FFFFFF" valign="top"><div id="pageSelectTD" style="width:390px; height:400px;overflow:auto;">&nbsp;</div></td>
	  </tr>
	</table>
	';

	$objResponse->assign("pageSelectDiv","innerHTML",$html);
	if($preselect=='0') {
	$objResponse->call("xajax_buildGruppen","$input");
	} else {

	$pre = mysql_fetch_array(mysql_query("SELECT domain_id FROM menue WHERE id='$old_id'"));
	$objResponse->call("xajax_buildSeiten",$pre[domain_id],"$input");
	}

	return $objResponse;
}



function selectPage($id,$input) {
	$objResponse = new xajaxResponse(); 
	$data = mysql_fetch_array(mysql_query("SELECT * FROM menue WHERE id='$id' limit 1"));
	$domainaktuelle = mysql_fetch_array(mysql_query("SELECT * FROM domains WHERE id='$_SESSION[aktuelle_domain]' limit 1"));
	$domaineigene = mysql_fetch_array(mysql_query("SELECT * FROM domains WHERE id='$data[domain_id]' limit 1"));
	 $objResponse->assign('seite_id','value',$data[id]); 
	 $objResponse->assign('seite_text','value',$data[name_de]);

	$htmldomaintd = '<select name="domain" id="domain" style="width:250px;">
	      <option value="'.$domaineigene[id].'">'.$domaineigene[name].' (eigene)</option>
	      <option value="'.$domainaktuelle[id].'" selected>'.$domainaktuelle[name].' (aktuelle)</option>
	    </select>';
	$objResponse->assign("domaintd","innerHTML",$htmldomaintd);
		
		
		$html = '
	    <div id="pageSelectDiv">
	    <table width="400" border="0" align="center" cellpadding="4" cellspacing="1" bgcolor="#DDDDDD">
	      <tr>
	        <td width="250" bgcolor="#FFFFFF" valign="top">Seite wählen, oder direkte ID eingeben</td>
	        <td bgcolor="#FFFFFF" valign="top"><div align="center">
	          <input type="button" name="button" id="button" value="Seite wählen" onClick="xajax_pageSelect('.$data[id].',\''.$input.'\',\'1\')">
	        </div></td>
	      </tr>
	    </table>
	    </div>';





	$objResponse->assign("pageSelectDiv","innerHTML",$html);
	return $objResponse;
}


function internlinkOk($sseite_id,$sseite_text,$starget,$simage='',$sdomain='') {
$objResponse = new xajaxResponse();  
$js = 'FCKinternlink.Add(\'html\');';
$objResponse->script($js);

return $objResponse;

}
$xajax->registerFunction("pageSelect");
	$xajax->registerFunction("buildGruppen");
	$xajax->registerFunction("buildDomains");
	$xajax->registerFunction("buildSeiten");
	$xajax->registerFunction("selectPage");
	$xajax->registerFunction("internlinkOk");




$xajax->processRequest();
$xajax->printJavascript('../../../../');  




?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<!--
 * FCKeditor - The text editor for Internet - http://www.fckeditor.net
 * Copyright (C) 2003-2008 Frederico Caldeira Knabben
 *
 * == BEGIN LICENSE ==
 *
 * Licensed under the terms of any of the following licenses at your
 * choice:
 *
 *  - GNU General Public License Version 2 or later (the "GPL")
 *    http://www.gnu.org/licenses/gpl.html
 *
 *  - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
 *    http://www.gnu.org/licenses/lgpl.html
 *
 *  - Mozilla Public License Version 1.1 or later (the "MPL")
 *    http://www.mozilla.org/MPL/MPL-1.1.html
 *
 * == END LICENSE ==
 *
 * internlink Plugin.
-->
<html>
	<head>
		<title>internlink Properties</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta content="noindex, nofollow" name="robots">
       
		
		<script language="javascript">



var oEditor = window.parent.InnerDialogLoaded() ;
var FCKLang = oEditor.FCKLang ;
var FCKinternlink = oEditor.FCKinternlink ;
var FCKConfig	= oEditor.FCKConfig ;
function BrowseServer()
{

		OpenServerBrowser(
		'Image',
		FCKConfig.ImageBrowserURL,
		FCKConfig.ImageBrowserWindowWidth,
		FCKConfig.ImageBrowserWindowHeight ) ;

	
}
function OpenServerBrowser( type, url, width, height )
{
	sActualBrowser = type ;
	OpenFileBrowser( url, width, height ) ;
}
function OpenFileBrowser( url, width, height )
{

	// oEditor must be defined.

	var iLeft = ( oEditor.FCKConfig.ScreenWidth  - width ) / 2 ;
	var iTop  = ( oEditor.FCKConfig.ScreenHeight - height ) / 2 ;

	var sOptions = "toolbar=no,status=no,resizable=yes,dependent=yes,scrollbars=yes" ;
	sOptions += ",width=" + width ;
	sOptions += ",height=" + height ;
	sOptions += ",left=" + iLeft ;
	sOptions += ",top=" + iTop ;

	// The "PreserveSessionOnFileBrowser" because the above code could be
	// blocked by popup blockers.
	if ( oEditor.FCKConfig.PreserveSessionOnFileBrowser && oEditor.FCKBrowserInfo.IsIE )
	{
		// The following change has been made otherwise IE will open the file
		// browser on a different server session (on some cases):
		// http://support.microsoft.com/default.aspx?scid=kb;en-us;831678
		// by Simone Chiaretta.
		var oWindow = oEditor.window.open( url, 'FCKBrowseWindow', sOptions ) ;

		if ( oWindow )
		{
			// Detect Yahoo popup blocker.
			try
			{
				var sTest = oWindow.name ; // Yahoo returns "something", but we can't access it, so detect that and avoid strange errors for the user.
				oWindow.opener = window ;
			}
			catch(e)
			{
				alert( oEditor.FCKLang.BrowseServerBlocked ) ;
			}
		}
		else
			alert( oEditor.FCKLang.BrowseServerBlocked ) ;
    }
    else
		window.open( url, 'FCKBrowseWindow', sOptions ) ;
}

function SetUrl( url, width, height, alt )
{
document.getElementById('image').value = url ;
oWindow = null;
UpdatePreview();
}



window.onload = function ()
{
	// First of all, translate the dialog box texts
	oEditor.FCKLanguageManager.TranslatePage( document ) ;


	// Show the "Ok" button.
	window.parent.SetOkButton( true ) ;
}

///------------------------------------------------------>
function Ok()
{
    var sseite_id = document.getElementById('seite_id').value ;
	if ( sseite_id.length == 0 )
	{
		alert( FCKLang.internlinkErrNoName ) ;
		return false ;
	}
	var sseite_text = document.getElementById('seite_text').value ;
	var starget = document.getElementById('target').value ;
	var simage = document.getElementById('image').value ;
	var sdomain = document.getElementById('domain').value ;
	
	

	
    test = xajax_internlinkOk(sseite_id,sseite_text,starget,simage,sdomain);
alert(test);
}



///------------------------------------------------------>
function SetPreviewElements( imageElement, linkElement )
{
	eImgPreview = imageElement ;
	eImgPreviewLink = linkElement ;

	UpdatePreview() ;

	bPreviewInitialized = true ;
}

function UpdatePreview()
{
var eImgPreview = document.getElementById('image').value

	if ( !eImgPreview )
		return ;

	document.getElementById('imgPreview').src = document.getElementById('image').value;
	document.getElementById('imgPreview').style.display = 'block';
}
		</script>
	</head>
	<body scroll="no" style="OVERFLOW: hidden">
    <div id="pageSelectDiv">
    <table width="400" border="0" align="center" cellpadding="4" cellspacing="1" bgcolor="#DDDDDD">
      <tr>
        <td width="250" bgcolor="#FFFFFF" valign="top">Seite wählen, oder direkte ID eingeben</td>
        <td bgcolor="#FFFFFF" valign="top"><div align="center">
          <input type="button" name="button" id="button" value="Seite wählen" onClick="xajax_pageSelect('1','anzeigen_auf_seite')">
        </div></td>
      </tr>
    </table>
    </div>
    <br>
    <table width="400" border="0" align="center" cellpadding="4" cellspacing="1" bgcolor="#DDDDDD">
  <tr>
    <td width="150" valign="top" bgcolor="#FFFFFF">SeitenID</td>
    <td width="250" valign="top" bgcolor="#FFFFFF"><input name="seite_id" type="text" id="seite_id" style="width:250px;" /></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF" valign="top">Linktitel</td>
    <td width="250" valign="top" bgcolor="#FFFFFF"><input name="seite_text" type="text" id="seite_text" style="width:250px;" /></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF" valign="top">Target</td>
    <td width="250" valign="top" bgcolor="#FFFFFF"><input name="target" type="text" id="target" style="width:250px;" value="_self" /></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF" valign="top">Domain</td>
    <td valign="top" bgcolor="#FFFFFF" id="domaintd">
    </td>
    </tr>
  <tr>
    <td bgcolor="#FFFFFF" valign="top">Bild</td>
    <td valign="top" bgcolor="#FFFFFF"><div id="divBrowseServer">
      <input name="image" type="text" id="image" style="width:250px;" />
    </div></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF" valign="top"><div align="center"><img id="imgPreview" src="javascript:void(0)" style="display: none" alt="" width="150" /></div></td>
    <td valign="top" bgcolor="#FFFFFF"><input type="button" value="Browse Server" fcklang="DlgBtnBrowseServer" onClick="BrowseServer();" /></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF" valign="top">&nbsp;</td>
    <td valign="top" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  </table>
</body>
</html>
