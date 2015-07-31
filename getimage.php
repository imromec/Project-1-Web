<?php

include("imageLib.php");

$filename=$_GET['image'];
$height=$_GET['h'];
$width=$_GET['w'];
$crop=$_GET['crop'];





$ext=explode('.',$filename);
$ext=$ext[1];
//$filename=$_GET['image'];
 
$type=$_GET['type'];
if($type=='news'){
$pathnew='../news/'.$filename;
}elseif($type=='logo'){
$pathnew='../logo/'.$filename;
}
elseif($type=='business'){
 
$pathnew='../business/'.$filename;
}
elseif($type=='user'){
$path="images/";

$pathnew='images/'.$filename;
}
$s= getimagesize($pathnew);
$size=array();
$size['width']=$s[0];
$size['height']=$s[1];
 
if(isset($width) and isset($height))
{
//echo "http://localhost/golfcms/logo/".$filename;exit;

if(isset($crop))
{

header("Content-Type: image/".$ext);


if($size['height']>$size['width'])
{

$sh=($size['height']/$size['width'])*$width;
$sw=$width;
}
else
{

$sw=($size['width']/$size['height'])*$height;
$sh=$height;
} 
 
 
$newimage=resize($pathnew,array('w'=>$sw,'h'=>$sh)); 

//print_r($newimage);


//echo 'sw_'.$sw.' sh_'.$sh;

// echo $newimage;exit;
cropImageext(($sw-$width)/2,($sh-$height)/2,$width,$height,$newimage,null);
 
 
}
else
{ 

//echo 'images/'.$filename;

$newimage=resize($pathnew,array('w'=>$width,'h'=>$height)); 


 
if(file_exists($newimage))
{
	
	header("Content-Type: image/".$ext);
        flush();
	readfile($newimage);
	unlink($newimage);
} 
}


}
else
{
$newimage=$pathnew;


if(file_exists($newimage))
{
	
	header("Content-Type: image/".$ext);
        flush();
	readfile($newimage);
	//unlink($newimage);
}


}


?>