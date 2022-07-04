<?php
$writabledir='/js/cache/';

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
 // $js=file_get_contents('jquery-1.9.0.js');
  $js.=file_get_contents('jquery.flexnav.min.js');
  $js.=file_get_contents('jquery_user_main.js');
  $js.=file_get_contents('jquery.fancybox.pack.js');
  $js.=file_get_contents('jquery.fileupload.js');
  $js.=file_get_contents('jquery.iframe-transport.js');
  $js.=file_get_contents('jquery.knob.js');
  $js.=file_get_contents('jquery.raty.js');
  $js.=file_get_contents('jquery.ui.widget.js');
  $js.=file_get_contents('jquery_admin_main.js');
  $js.=file_get_contents('jquery.lazyload.min.js');
  require $_SERVER['DOCUMENT_ROOT'].'/framework/jsmin-php-master/jsmin.php';
  $js=JSMin::minify($js);
  file_put_contents($writabledir.$name,$js);
$expires = 60*60*24; // how long to cache in secs..
header('Cache-control: public');
header("Pragma: public");
header("Cache-Control: maxage=".$expires);
header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');    
   header('Content-Type: application/javascript');
  echo $js;
}
?>