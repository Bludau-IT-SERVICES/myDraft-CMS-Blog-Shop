<?php


function image_resize($src, $dst, $width, $height, $crop=0){

  if(!list($w, $h) = getimagesize($src)) return "Unsupported picture type!";

  $type = strtolower(substr(strrchr($src,"."),1));
  if($type == 'jpeg') $type = 'jpg';
  switch($type){
    case 'bmp': $img = imagecreatefromwbmp($src); break;
    case 'gif': $img = imagecreatefromgif($src); break;
    case 'jpg': $img = imagecreatefromjpeg($src); break;
    case 'png': $img = imagecreatefrompng($src); break;
    default : return "Unsupported picture type!";
  }

  // resize
  if($crop){
    if($w < $width or $h < $height) return "Picture is too small!";
    $ratio = max($width/$w, $height/$h);
    $h = $height / $ratio;
    $x = ($w - $width / $ratio) / 2;
    $w = $width / $ratio;
  }
  else{
    if($w < $width and $h < $height) return "Picture is too small!";
    $ratio = min($width/$w, $height/$h);
    $width = $w * $ratio;
    $height = $h * $ratio;
    $x = 0;
  }

  $new = imagecreatetruecolor($width, $height);

  // preserve transparency
  if($type == "gif" or $type == "png"){
    imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
    imagealphablending($new, false);
    imagesavealpha($new, true);
  }

  imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

  switch($type){
    case 'bmp': imagewbmp($new, $dst); break;
    case 'gif': imagegif($new, $dst); break;
    case 'jpg': imagejpeg($new, $dst); break;
    case 'png': imagepng($new, $dst); break;
  }
  return true;
}

 function image_resample($im, $nw, $nh, $bg_type = "color", $bg_value="FFFFFF") {

    // Step 1 - Getting actual image size
    $ow = imagesx($im);
    $oh = imagesy($im);

    // Step 2 - Create ne Image with new Width and Height
    $nim = imagecreatetruecolor($nw,$nh);

    // Step 3 - Define the Background
    switch ($bg_type) {
        case "color":
            $bg = imagecolorallocate($nim,hexdec(substr($bg_value,0,2)),hexdec(substr($bg_value,2,2)),hexdec(substr($bg_value,4,2)));
            imagefill($nim,1,1,$bg);
            break;
        case "get":
            $bg = imagecolorat($im,0,0);
            imagefill($nim,1,1,$bg);
            break;
    }

    // Step 4 Check for Job Type (Scale UP or DOWN)
    if ($nw < $ow || $nh < $oh) {
        $scale = "DOWN";
    } else {
        $scale = "UP";
    }

    // Step 5 Calculate Scaling
    if ($scale == "UP") {
        $nx = $ow;
        $ny = $oh;
        while ($nx<$nw && $ny<$nh) {
            $nx = $nx + ($ow/100);
            $ny = $ny + ($oh/100);
        }
        $nx = $nx - ($ow/100);
        $ny = $ny - ($oh/100);    
        echo $nx." ".$ny;
    } else {
        $nx = $ow;
        $ny = $oh;
        while ($nx>$nw || $ny>$nh) {
            $nx = $nx - ($ow/100);
            $ny = $ny - ($oh/100);
        }
    }
    $nx = round($nx,0);
    $ny = round($ny,0);

    // Step 6 Calculate center
    $startx = ($nw-$nx)/2;
    $starty = ($nh-$ny)/2;

    // Step 7 Process Resampling
    imagecopyresampled($nim, $im, $startx, $starty, 0, 0, $nx, $ny, $ow, $oh);

    // Step 8 Clear Memory and return Image
    imagedestroy($im);
    return($nim);
}

?>