<?php

function resize($imagePath,$opts=null){

	# start configuration
	
	$cacheFolder = './cache/'; # path to your cache folder, must be writeable by web server
	$remoteFolder = $cacheFolder.'remote/'; # path to the folder you wish to download remote images into
	$quality = 90; # image quality to use for ImageMagick (0 - 100)
	
	$cache_http_minutes = 20; 	# cache downloaded http images 20 minutes

	$path_to_convert = 'convert'; # this could be something like /usr/bin/convert or /opt/local/share/bin/convert
	
	## you shouldn't need to configure anything else beyond this point

	$purl = parse_url($imagePath);
	$finfo = pathinfo($imagePath);
	$ext = $finfo['extension'];

	# check for remote image..
	if(isset($purl['scheme']) && $purl['scheme'] == 'http'):
		# grab the image, and cache it so we have something to work with..
		list($filename) = explode('?',$finfo['basename']);
		$local_filepath = $remoteFolder.$filename;
		$download_image = true;
		if(file_exists($local_filepath)):
			if(filemtime($local_filepath) < strtotime('+'.$cache_http_minutes.' minutes')):
				$download_image = false;
			endif;
		endif;
		if($download_image == true):
			$img = file_get_contents($imagePath);
			file_put_contents($local_filepath,$img);
		endif;
		$imagePath = $local_filepath;
	endif;

	if(file_exists($imagePath) == false):
		$imagePath = $_SERVER['DOCUMENT_ROOT'].$imagePath;
		if(file_exists($imagePath) == false):
			return 'image not found';
		endif;
	endif;

	if(isset($opts['w'])): $w = $opts['w']; endif;
	if(isset($opts['h'])): $h = $opts['h']; endif;

	$filename = md5_file($imagePath);

	if(!empty($w) and !empty($h)):
		$newPath = $cacheFolder.$filename.'_w'.$w.'_h'.$h.(isset($opts['crop']) && $opts['crop'] == true ? "_cp" : "").(isset($opts['scale']) && $opts['scale'] == true ? "_sc" : "").'.'.$ext;
	elseif(!empty($w)):
		$newPath = $cacheFolder.$filename.'_w'.$w.'.'.$ext;	
	elseif(!empty($h)):
		$newPath = $cacheFolder.$filename.'_h'.$h.'.'.$ext;
	else:
		return false;
	endif;

	$create = true;

	if(file_exists($newPath) == true):
		$create = false;
		$origFileTime = date("YmdHis",filemtime($imagePath));
		$newFileTime = date("YmdHis",filemtime($newPath));
		if($newFileTime < $origFileTime):
			$create = true;
		endif;
	endif;

	if($create == true):
		if(!empty($w) and !empty($h)):

			list($width,$height) = getimagesize($imagePath);
			$resize = $w;
		
			if($width > $height):
				$resize = $w;
				if(isset($opts['crop']) && $opts['crop'] == true):
					$resize = "x".$h;				
				endif;
			else:
				$resize = "x".$h;
				if(isset($opts['crop']) && $opts['crop'] == true):
					$resize = $w;
				endif;
			endif;

			if(isset($opts['scale']) && $opts['scale'] == true):
				$cmd = $path_to_convert." ".$imagePath." -resize ".$resize." -quality ".$quality." ".$newPath;
			else:
				$cmd = $path_to_convert." ".$imagePath." -resize ".$resize." -size ".$w."x".$h." xc:".(isset($opts['canvas-color'])?$opts['canvas-color']:"transparent")." +swap -gravity center -composite -quality ".$quality." ".$newPath;
			endif;
						
		else:
			$cmd = $path_to_convert." ".$imagePath." -thumbnail ".(!empty($h) ? 'x':'').$w."".(isset($opts['maxOnly']) && $opts['maxOnly'] == true ? "\>" : "")." -quality ".$quality." ".$newPath;
		endif;

		$c = exec($cmd);
		
	endif;

	# return cache file path
	return str_replace($_SERVER['DOCUMENT_ROOT'],'',$newPath);
	
}


function createThumbs( $fname, $pathToThumb, $thumbWidth,$thumbHeight='' ) 
{
  // open the directory
 

  // loop through it, looking for any/all JPG files:
 
    // parse path for the extension
    $info = pathinfo($fname);
    // continue only if this is a JPEG image
    if ( strtolower($info['extension']) == 'jpg'or  strtolower($info['extension']) == 'jpeg' ) 
    {
 

      // load image and get image size
      $img = imagecreatefromjpeg($fname);
      $width = imagesx( $img );
      $height = imagesy( $img );

      
      if($thumbHeight!='')
   { $new_height = $thumbHeight;
         $new_width = floor( $width * ( $thumbHeight / $height ) );
            }   else
			{ 
      $new_width = $thumbWidth;
      $new_height = floor( $height * ( $thumbWidth / $width ) );
			}
      // create a new tempopary image
      $tmp_img = imagecreatetruecolor( $new_width, $new_height );

      // copy and resize old image into new image 
      imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

      // save thumbnail into a file
      imagejpeg( $tmp_img, $pathToThumb );
    }
	
	
	    if ( strtolower($info['extension']) == 'png' ) 
    {
 

      // load image and get image size
      $img = imagecreatefrompng( $fname );
      $width = imagesx( $img );
      $height = imagesy( $img );

      // calculate thumbnail size
     if($thumbHeight!='')
   { $new_height = $thumbHeight;
         $new_width = floor( $width * ( $thumbHeight / $height ) );
            }   else
			{ 
      $new_width = $thumbWidth;
      $new_height = floor( $height * ( $thumbWidth / $width ) );
			}

      // create a new tempopary image
      $tmp_img = imagecreatetruecolor( $new_width, $new_height );

      // copy and resize old image into new image 
      imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

      // save thumbnail into a file
      imagepng( $tmp_img, $pathToThumb );
    }
	    if ( strtolower($info['extension']) == 'gif' ) 
    {
 

      // load image and get image size
      $img = imagecreatefromgif( $fname );
      $width = imagesx( $img );
      $height = imagesy( $img );

      // calculate thumbnail size
      if($thumbHeight!='')
   { $new_height = $thumbHeight;
         $new_width = floor( $width * ( $thumbHeight / $height ) );
            }   else
			{ 
      $new_width = $thumbWidth;
      $new_height = floor( $height * ( $thumbWidth / $width ) );
			}

      // create a new tempopary image
      $tmp_img = imagecreatetruecolor( $new_width, $new_height );

      // copy and resize old image into new image 
      imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

      // save thumbnail into a file
      imagegif( $tmp_img, $pathToThumb );
    }
	
	
	
	
  }


function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }

$arr=array();
$arr=explode('/',$pageURL); 
$count=count($arr);
$new=array();
$i=0;
while($i<$count-1)
{
$new[$i]=$arr[$i];
$i++;
}


$pageURL=implode('/',$new);



 return $pageURL;
}

function getsize($fname)
{

  $info = pathinfo($fname);
    // continue only if this is a JPEG image
    if ( strtolower($info['extension']) == 'jpg' ) 
    {
 

      // load image and get image size
      $img = imagecreatefromjpeg($fname);
      $width = imagesx( $img );
      $height = imagesy( $img );
 
    }
	
	  if ( strtolower($info['extension']) == 'jpeg' ) 
    {
 

      // load image and get image size
      $img = imagecreatefromjpeg($fname);
      $width = imagesx( $img );
      $height = imagesy( $img );
 
    }
	
	
	
	    if ( strtolower($info['extension']) == 'png' ) 
    {




      // load image and get image size
      $img = imagecreatefrompng( $fname );
      $width = imagesx( $img );
      $height = imagesy( $img );



 
    }
	    if ( strtolower($info['extension']) == 'gif' ) 
    {
 

      // load image and get image size
      $img = imagecreatefromgif( $fname );
      $width = imagesx( $img );
      $height = imagesy( $img );
 
    }
	
	
	$arr=array('height'=>$height,'width'=>$width);
	return $arr;
	

}



function cropImageext($x,$y,$nw,$nh,$source,$dest)
{
$size = getimagesize($source);
$w = $size[0];
$h = $size[1];

$stype=explode('.',$source);
$stype=$stype[count($stype)-1];
 
switch($stype) {
case 'gif':
$simg = imagecreatefromgif($source);
break;
case 'jpg':
$simg = imagecreatefromjpeg($source);
break;
case 'jpeg':
$simg = imagecreatefromjpeg($source);
break;
case 'png':
$simg = imagecreatefrompng($source);
break;
}

$dimg = imagecreatetruecolor($nw,$nh);
$bg = ImageColorAllocateAlpha($dimg , 255, 255, 255, 127);
ImageFill($dimg, 0, 0 , $bg);
$x1=($x+$nw);
$y1=($y+$nh);
imagecopyresampled($dimg,$simg,0,0,$x,$y,$nw,$nh,$nw,$nh);
 

switch($stype) {
case 'gif':
imagegif($dimg,$dest);
break;
case 'jpg':
imagejpeg($dimg,$dest,100);
break;
case 'jpeg':
imagejpeg($dimg,$dest,100);
break;
case 'png':
imagepng($dimg,$dest);
break;
}

 
}

function cropImage($nw, $nh, $source, $stype, $dest) {
$size = getimagesize($source);
$w = $size[0];
$h = $size[1];
switch($stype) {
case 'gif':
$simg = imagecreatefromgif($source);
break;
case 'jpg':
$simg = imagecreatefromjpeg($source);
break;
case 'jpeg':
$simg = imagecreatefromjpeg($source);
break;
case 'png':
$simg = imagecreatefrompng($source);
break;
}
$dimg = imagecreatetruecolor($nw, $nh);
$wm = $w/$nw;
$hm = $h/$nh;
$h_height = $nh/2;
$w_height = $nw/2;
if($w > $h) {
$adjusted_width = $w / $hm;
$half_width = $adjusted_width / 2;
$int_width = $half_width - $w_height;
$int_width=$int_width*(-1);
imagecopyresampled($dimg,$simg,$int_width,0,0,0,$adjusted_width,$nh,$w,$h);
} elseif(($w < $h) || ($w == $h)) {
$adjusted_height = $h / $wm;
$half_height = $adjusted_height / 2;
$int_height = $half_height - $h_height;
$int_height=$int_height*(-1);
imagecopyresampled($dimg,$simg,0,$int_height,0,0,$nw,$adjusted_height,$w,$h);
} else {
imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h);
}
imagejpeg($dimg,$dest,100);
}



?>