<?php 
include("config.php");
error_reporting(0);
 echo '{';
$value=array();
 $businessid="Select business_id from  follow_tbl  where    user_id=".$_GET['user_id'];
 
 $business_ids=array();
$bids=mysql_query($businessid);
while($resultbusinessid=mysql_fetch_array($bids))
{
$business_ids[]=$resultbusinessid['business_id'];

} 
if(count($business_ids)>0){
$busids=implode(',',$business_ids);
 $queryin=" And  `id`  IN (".$busids.")";
}

if(mysql_num_rows($bids)>0){
$response="Success";
}else{
$response="Success";
}
  echo '"response" :"'.$response.'"';
  
  
  
  echo ',"response data" :';
echo "{";
if($_REQUEST['category_id']!=''){
$catquery="where category_id='".$_REQUEST['category_id']."'";
}else{
$catquery;
}
//echo "select * from category  where category_name='".$_REQUEST['cat_name']."' ";
$category=mysql_query("select * from category  ".$catquery); 

$categorycout=mysql_num_rows($category);
 $i=0;
//echo $categorycount;exit;
while($categorydata=mysql_fetch_array($category)){

echo '"'.$categorydata['category_name'].'"';
echo ":[";
  // echo "select * from golf_details where cat_id='".$categorydata['category_id']."'".$queryin;
  
$business=mysql_query("select * from golf_details where cat_id='".$categorydata['category_id']."'".$queryin);
$businesscount=mysql_num_rows($business);
$ibusinessi=0;

while($businessdetails=mysql_fetch_array($business)){ 
echo '{"'.$businessdetails['title_name'].'"';
echo ":[";
$inews=0;
$news=mysql_query("select * from news where business_id='".$businessdetails['id']."'");
$newscount=mysql_num_rows($news);
while($newsdetails=mysql_fetch_array($news)){
 if($newsdetails['pdf']!=''){
 $pdffile=$newsdetails['pdf'];
 }else{
 $pdffile='';
 }
 
  if($newsdetails['image']!=''){
 $imgfile=$newsdetails['image'];
 }else{
 $imgfile='';
 }
$val = array("news_id" => $newsdetails['id'], 
			"title"=>$newsdetails['title'],
			 "image"=>$imgfile,
			 "pdf"=>$pdf
            
            );
			 
$output = json_encode($val);
 if($inews==$newscount-1){

echo stripslashes($output);
}else{
echo stripslashes($output).',';
}
$inews++;
 
} 
if($ibusinessi==$businesscount-1){
echo "]}";
}else{
echo "]},";
}
$ibusinessi++;
}	

 
if($i==$categorycout-1){
echo "]";
}else{
echo "],";
}
$i++;
 
}
echo "}";
echo "}";