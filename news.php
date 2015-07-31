<?php 
error_reporting(0);
include("config.php");
if($_REQUEST['user_id']!='' && $_REQUEST['business_id']==''){
  
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

 echo '{
  "response" : "success",
  "response data" :[';
 

 
 
$business=mysql_query("select * from golf_details where id IN(".$busids.")");
$businesscount=mysql_num_rows($business);
$ibusinessi=0;

while($businessdetails=mysql_fetch_array($business)){
  
 

   
   $newsdata="Select * from  news  where    business_id='".$businessdetails['id']."'order by id DESC";
 if($businessdetails['logo']!=''){
$logoimage=$businessdetails['logo'];
}else{
$logoimage='null';
}
$business_id=$businessdetails['id'];
$business_name=$businessdetails['title_name'];
 $businessnewsdatatitle=array();
$getnewsdata=mysql_query($newsdata);
$newscount=mysql_num_rows($getnewsdata);

$newscountdata=0;
while($fetchnewsdata=mysql_fetch_array($getnewsdata))
{

if($fetchnewsdata['pdf']!=''){
$newspdf=$fetchnewsdata['pdf'];
}else{
$newspdf='null';
}
//=$fetchnewsdata['title'];
 


if($fetchnewsdata['image2']==''){
$img2='';}else{
$img2=','.$fetchnewsdata['image2'];
}
if($fetchnewsdata['image3']==''){
$img3='';}else{
$img3=','.$fetchnewsdata['image3'];
}
if($fetchnewsdata['image4']==''){
$img4='';}else{

$img4=','.$fetchnewsdata['image4'];
}
if($fetchnewsdata['image5']==''){
$img5='';}else{

$img5=','.$fetchnewsdata['image5'];
}


if($fetchnewsdata['image6']==''){
$img6='';}else{

$img6=','.$fetchnewsdata['image6'];
}
if($fetchnewsdata['image7']==''){
$img7='';}else{

$img7=','.$fetchnewsdata['image7'];
}

if($fetchnewsdata['image8']==''){
$img8='';}else{

$img8=','.$fetchnewsdata['image8'];
}
if($fetchnewsdata['image9']==''){
$img9='';}else{

$img9=','.$fetchnewsdata['image9'];
}
 if($fetchnewsdata['image10']==''){
 $img10='';}else{

$img10=','.$fetchnewsdata['image10'];
}

if($fetchnewsdata['image']!=''){
$img='['.$fetchnewsdata['image'].$img2.$img3.$img4.$img5.$img6.$img7.$img8.$img9.$img10.']';

}else{
$img='[null]';
}
 


$businessnewsdatatitle = array("id" => $fetchnewsdata['id'], 
			"title_name"=>$fetchnewsdata['title'],
			"business_id"=>$business_id,
			"business_name"=>$business_name,
			"business_logo"=>$logoimage,
			
			
			
			"pdf"=>$newspdf,
			"postImage"=>$img,
			"description"=>$fetchnewsdata['description'],
             
            
            );
  
   	$businessnewsdatatitleoutput = json_encode($businessnewsdatatitle);
  $stripdata=stripslashes($businessnewsdatatitleoutput);
 
  
 if($newscountdata<$newscount-1){

 

//echo substr($stripdata,0,-1);
//echo ",".$promotion;
echo $stripdata.',';

} else{echo $stripdata;}

$newscountdata++;
}  
if($ibusinessi<$businesscount-2){
echo ",";

}else{
}
$ibusinessi++;
}	



 






 


echo ' ]   
}';

}
elseif($_REQUEST['business_id']!=''&& $_REQUEST['user_id']!=''){
$businessid="Select  * from follow_tbl  where business_id='".$_GET['business_id']."'  and user_id=".$_GET['user_id'];
  
$bids=mysql_query($businessid);
if(mysql_num_rows($bids)>0){
$isfollow="YES";
}else{
$isfollow="NO";}
 
$value=array();
 
 echo '{
  "response" : "success",
  "response data" :';
//echo '{';

 
 
$business=mysql_query("select * from golf_details where id='".$_REQUEST['business_id']."'");
$businesscount=mysql_num_rows($business);
$ibusinesscount=0;
while($businessdetails=mysql_fetch_array($business)){





 $images='['.'"'.$businessdetails['image1'].'","'.$businessdetails['image2'].'"]';
$imagedata=substr($images,-1); 


if($businessdetails['image2']!=''){
$img2=','.$businessdetails['image2'];
}
if($businessdetails['image3']!=''){
$img3=','.$businessdetails['image3'];
}
if($businessdetails['image4']!=''){

$img4=','.$businessdetails['image4'];
}
if($businessdetails['image5']!=''){

$img5=','.$businessdetails['image5'];
}
 
$img='['.$businessdetails['image1'].$img2.$img3.$img4.$img5.']';
 
 
 
if($businessdetails['logo']!=''){
$logoimage=$businessdetails['logo'];
}else{
$logoimage='null';
}
 
 
 
$val = array("IsFollow"=>$isfollow,
			"id" => $businessdetails['id'], 
			"title_name"=>$businessdetails['title_name'],
             "manager_name" => $businessdetails['manager_name'],
			 "email"=>$businessdetails['email'],
			 "phone"=>$businessdetails['phone'],
			 "address"=>$businessdetails['address'],
			 "description"=>$businessdetails['description'],
			 
			 "weather"=>$businessdetails['weather'],
			 "logo"=>$logoimage,
			 "latitude"=>$businessdetails['latitude'],
			 "longitude"=>$businessdetails['longitude'],
			 "images"=>$img
			 
            
            );
			 
$output = json_encode($val);
  
  $stripoutput=stripslashes($output);
  
 if($ibusinesscount==$businesscount-1){

echo substr($stripoutput,0, -1);
//echo stripslashes($output);
}else{
echo stripslashes($output).',';
}


$adminbusiness=mysql_query("select * from admin_golf_details where business_id='".$_REQUEST['business_id']."'");
$adminbusinesscount=mysql_num_rows($adminbusiness);


echo ',"manager":[';
$adminbusinesscountdata=0;
if(mysql_num_rows($adminbusiness)>0){
while($fetchadminbusiness=mysql_fetch_array($adminbusiness))
{ 
//=$fetchnewsdata['title'];
$adminbusinesmanagerdatatitle = array("name"=>$fetchadminbusiness['manager_name'],
			"email"=>$fetchadminbusiness['email'],
			"phone"=>$fetchadminbusiness['phone'],
			"address"=>$fetchadminbusiness['address'] 
            );
  
  
 
//array_push($businessnewsdatatitle,$businessfeeds);

			$adminbusinesmanagerdatatitleoutput = json_encode($adminbusinesmanagerdatatitle);
  $adminbusinessstripdata=stripslashes($adminbusinesmanagerdatatitleoutput);
  //echo $newscountdata.'<br>';
  
 if($adminbusinesscountdata<$adminbusinesscount-1){



//echo substr($stripdata,0,-1);
//echo ",".$promotion;
echo '{'.substr($adminbusinessstripdata,1,-1).'},';


}else{
echo  $adminbusinessstripdata.']';

//echo ',"feeds":[{'.substr($stripdata,1,-1);

//echo ',}'.substr($stripdata,1,-1);
}

$adminbusinesscountdata++;
} }else{
echo "]";
}


























   $newsdata="Select * from  news  where    business_id='".$businessdetails['id']."'order by id DESC";
 
 $businessnewsdatatitle=array();
$getnewsdata=mysql_query($newsdata);
$newscount=mysql_num_rows($getnewsdata);
echo ',"feeds":[';
$newscountdata=0;
while($fetchnewsdata=mysql_fetch_array($getnewsdata))
{

if($fetchnewsdata['pdf']!=''){
$newspdf=$fetchnewsdata['pdf'];
}

if($fetchnewsdata['image2']==''){
$img2='';}else{
$img2=','.$fetchnewsdata['image2'];
}
if($fetchnewsdata['image3']==''){
$img3='';}else{
$img3=','.$fetchnewsdata['image3'];
}
if($fetchnewsdata['image4']==''){
$img4='';}else{

$img4=','.$fetchnewsdata['image4'];
}
if($fetchnewsdata['image5']==''){
$img5='';}else{

$img5=','.$fetchnewsdata['image5'];
}


if($fetchnewsdata['image6']==''){
$img6='';}else{

$img6=','.$fetchnewsdata['image6'];
}
if($fetchnewsdata['image7']==''){
$img7='';}else{

$img7=','.$fetchnewsdata['image7'];
}

if($fetchnewsdata['image8']==''){
$img8='';}else{

$img8=','.$fetchnewsdata['image8'];
}
if($fetchnewsdata['image9']==''){
$img9='';}else{

$img9=','.$fetchnewsdata['image9'];
}
 if($fetchnewsdata['image10']==''){
 $img10='';}else{

$img10=','.$fetchnewsdata['image10'];
}

if($fetchnewsdata['image']!=''){
$img='['.$fetchnewsdata['image'].$img2.$img3.$img4.$img5.$img6.$img7.$img8.$img9.$img10.']';

}else{
$img='[null]';
}
//echo "this is the image".$fetchnewsdata['image3']; 
//=$fetchnewsdata['title'];
$businessnewsdatatitle = array("id" => $fetchnewsdata['id'], 
			"title_name"=>$fetchnewsdata['title'],
			"image"=>$img,
			"pdf"=>$newspdf,
			"Description"=>$fetchnewsdata['description'] 
            );
  
  
$businessfeeds['Feeds']=$businessnewsdatatitle;
//array_push($businessnewsdatatitle,$businessfeeds);

			$businessnewsdatatitleoutput = json_encode($businessnewsdatatitle);
  $stripdata=stripslashes($businessnewsdatatitleoutput);
  //echo $newscountdata.'<br>';
  
 if($newscountdata<$newscount-1){



//echo substr($stripdata,0,-1);
//echo ",".$promotion;
echo '{'.substr($stripdata,1,-1).'},';


}else{
echo  $stripdata;

//echo ',"feeds":[{'.substr($stripdata,1,-1);

//echo ',}'.substr($stripdata,1,-1);
}

$newscountdata++;
} 




$ibusinesscount++;
}	






 


echo '   ]
			}
}';

}
?>