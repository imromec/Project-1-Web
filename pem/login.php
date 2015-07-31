<?php
include('mysql.php');
include('config.php');
include('imageLib.php');

error_reporting(0);
$email=$_GET['email'];
$pass=$_GET['password'];



$result=SQL_QueryResult("select * from users where `email`='$email' and password='$pass'");

$response=array();
if(count($result)==0)
{
$response['response']="FAILED";
echo json_encode($response);
}
else
{
 
$time1=time();
$time2=strtotime($result[0]['time_stamp']);

$hrs=($time1-$time2)/3600;



$response['user_id']=$result[0]['user_id'];
$response['username']=$result[0]['username'];
$response['email']=$result[0]['email']; 
$response['contact_no']=$result[0]['contact_no']; 
$response['country_id']=$result[0]['country_id']; 

$response['image']=$result[0]['picture']; 
$response['response']="SUCCESS";
$value=array();

 $businessid="Select class_id from class_followers where user_id=".$result[0]['user_id'];
 
 
 $business_ids=array();
 
$bids=mysql_query($businessid);
while($resultbusinessid=mysql_fetch_array($bids))
{
$business_ids[]=$resultbusinessid['class_id'];
} 

//print_r($business_ids);

if(count($business_ids)>0){
$busids=implode(',',$business_ids);
 $queryin=" And  `id`  IN (".$busids.")";
}

//echo "select * from schools where country_id = '".$result[0]['country_id']."'";

$category=mysql_query("select * from schools where country_id = '".$result[0]['country_id']."'"); 

$categorycout=mysql_num_rows($category);
 $i=0;
 echo '{
  "response" : "success",
  "response data" :';
echo '{';
$userquery=mysql_query("select * from users where user_id='".$result[0]['user_id']."'");
$userdetails=mysql_fetch_array($userquery);
 if($userdetails['picture']!=''){
 $userimage='"'.$userdetails['picture'].'"';
 }else{
 $userimage='"null"';
 }
	  echo '"user_id":"'.$userdetails['user_id'].'",';
	  echo '"country_id":"'.$userdetails['country_id'].'",';
	   echo '"username":"'.$userdetails['username'].'",';
	   echo '"email":"'.$userdetails['email'].'",';
	    echo '"contact_no":"'.$userdetails['contact_no'].'",';
		echo '"user_image":'.$userimage.',';

while($categorydata=mysql_fetch_array($category)){


//echo '"school_id":"'.$categorydata['school_id'].'",';
//echo '"school_name":"'.$categorydata['school_name'].'"';

echo '"'.$categorydata['school_name'].'"';

echo ':[';
 


if($i==$categorycout-1){
echo ']';
}else{
echo '],';
}
$i++;

}
echo '}}';



if($_GET['type']=="IOS"){
 $insarray['android_token']='';

}else{
if(isset($_GET['android_token']))
$insarray['android_token']=$_GET['android_token'];
}
if($_GET['type']=="ANDROID"){
$insarray['ios_token']='';

}else{
if(isset($_GET['ios_token']))
$insarray['ios_token']=$_GET['ios_token'];
}

if(isset($_GET['type']))
$insarray['type']=$_GET['type'];
$insarray['latitude']=$_GET['latitude'];
$insarray['longitude']=$_GET['longitude'];
SQL_Update('users',$insarray,array('user_id'=>$result[0]['id']));

}
//

?>
