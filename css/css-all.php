<?php
$writabledir='/css/cache/';

function md5_of_dir($folder) {
  $dircontent = scandir($folder);
  $ret='';
  foreach($dircontent as $filename) {
    if ($filename != '.' && $filename != '..') {
      if (filemtime($folder.$filename) === false) return false;
      $ret.=date("YmdHis", filemtime($folder.$filename)).$filename;
    }
  }
  return md5($ret);
}

$name=md5_of_dir('./');
if(file_exists($writabledir.$name))readfile($writabledir.$name);
else{
  $js.=file_get_contents('../templates'.$_GET['template'].'/css/template_master.css');
  $js.=file_get_contents('../framework/flexnav-master/css/flexnav.css');
  $js.=file_get_contents('../framework/raty-2.7.0/lib/jquery.raty.css');
  $js.=file_get_contents('../framework/fancyBox-master/source/jquery.fancybox.css');
  #echo $js;
  #$js=file_get_contents('popups.css');
  #$js=file_get_contents('popups.css');
  
  require $_SERVER['DOCUMENT_ROOT'].'/framework/CssMin/cssmin-v3.0.1-minified.php';
  #$js=JSMin::minify($js);
  $js = CssMin::minify($js);
  file_put_contents($writabledir.$name,$js);
$expires = 60*60*24; // how long to cache in secs..
header('Cache-control: public');
header("Pragma: public");
header("Cache-Control: maxage=".$expires);
header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');  
header('Content-Type: text/css');
  echo $js;
}
?>