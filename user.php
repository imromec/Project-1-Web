<?php
include('mysql.php');
include('imageLib.php');
include('config.php');
error_reporting(0);


 if($_GET['type']=='REGISTER'&& isset($_GET['email']))
{
 
$rs=SQL_QueryResult("Select * from users where email='".$_GET['email']."'");
if(count($rs)>0)
{
$rs=$rs[0];
$res=array();
$res['user_id']=$rs['user_id'];
$res['result']='EMAIL_EXISTS';
echo json_encode($res);
exit(0);
}


 $insarray=array();
$insarray['username']=$_GET['username'];
$insarray['first_name']=$_GET['first_name'];
$insarray['last_name']=$_GET['last_name'];
$insarray['email']=$_GET['email'];
$insarray['password']=$_GET['password'];
$insarray['user_type_id']='5'; 
$insarray['country_id']=$_GET['country_id'];
$insarray['picture']=$_GET['picture'];
$insarray['created_date']=date("Y-m-d H:i:s");



 if(isset($_FILES['image']['name']))
{

$ext=explode('.',$_FILES['image']['name']);
$ext=$ext[count($ext)-1];
$fname=rand(000000000,999999999).'_'.rand(000000000,999999999).'_'.rand(000000000,999999999).'_'.rand(000000000,999999999).'.'.$ext;

if(move_uploaded_file($_FILES['image']['tmp_name'],'images/'.$fname))
{
$insarray['picture']=$fname;
//$insarray['time_stamp']=date("Y-m-d H:i:s");

}
}

$id=SQL_Insert('users',$insarray);



$rs=SQL_QueryResult("Select * from users where user_id='".$id."'");

//$rs=$rs[0];
$res=array();

$res['user_id']=$id;

$res['username']=$rs[0]['username']; 
$res['first_name']=$rs[0]['first_name'];
$res['last_name']=$rs[0]['last_name'];
$res['email']=$rs[0]['email']; 
$res['image']=$rs[0]['picture'];
$res['user_type_id']=$rs[0]['user_type_id'];
$res['country_id']=$rs[0]['country_id'];
$res['created_date']=$rs[0]['created_date'];

$res['result']='SUCCESS';
echo json_encode($res);
}


else  if($_GET['type']=='UPDATE'&&isset($_GET['user_id']))
{
 


$insarray=array();



if(isset($_GET['password']))
{


$rs=SQL_QueryResult("Select * from users where `user_id`='".$_GET['user_id']."'");
if(count($rs)==0)
{

$res=array();
$res['result']='PASSWORD_NOT_MATCHED';
echo json_encode($res);
exit(0);
}
if($_GET['password']!=''){
$insarray['password']=$_GET['password']; 
}
}
if($_GET['username']!=''){
$insarray['username']=$_GET['username'];
}
if($_GET['first_name']!=''){
$insarray['first_name']=$_GET['first_name'];
}
if($_GET['last_name']!=''){
$insarray['last_name']=$_GET['last_name'];
}
if($_GET['email']!=''){
$insarray['email']=$_GET['email'];
}
if($_GET['country_id']!=''){
$insarray['country_id']=$_GET['country_id']; 
}
if($_GET['picture']!=''){
$insarray['picture']=$_GET['picture'];
}
if($_GET['picture']!=''){
$insarray['picture']=$_GET['picture'];
}
$insarray['modified_date']=date("Y-m-d H:i:s");;


if(isset($_FILES['image']['name']))
{

	$select=mysql_query("select picture from users where user_id='".$_GET['user_id']."'");
    $image=mysql_fetch_array($select);

	@unlink('images/'.$image['picture']);
$ext=explode('.',$_FILES['image']['name']);
$ext=$ext[count($ext)-1];
$fname=rand(000000000,999999999).'_'.rand(000000000,999999999).'_'.rand(000000000,999999999).'_'.rand(000000000,999999999).'.'.$ext;

if(move_uploaded_file($_FILES['image']['tmp_name'],'images/'.$fname))
{
$insarray['picture']=$fname;

}

}
 

SQL_Update('users',$insarray,array('user_id'=>$_GET['user_id']));

//$rs=SQL_QueryResult("Select * from user where id='".$_GET['userid']."'");
//$rs=$rs[0];
//$res=array();

//$res['userid']=$rs['id'];
//$res['name']=$rs['username'];
//$res['email']=$rs['email'];

//$res['contact_no']=$rs['contact_no'];
 
//echo json_encode($res);
$value=array();
 $businessid="Select class_id from class_followers where user_id=".$_GET['user_id'];
 
 $business_ids=array();
$bids=mysql_query($businessid);
while($resultbusinessid=mysql_fetch_array($bids))
{
$business_ids[]=$resultbusinessid['class_id'];

} 
if(count($business_ids)>0){
$busids=implode(',',$business_ids);
 $queryin=" And  `id`  IN (".$busids.")";
}

$category=mysql_query("select * from schools where country_id = '".$_GET['country_id']."'");

$categorycout=mysql_num_rows($category);
 $i=0;
 echo '{
  "response" : "success",
  "response data" :';
echo '{';
$userquery=mysql_query("select * from users where user_id='".$_GET['user_id']."'");
$userdetails=mysql_fetch_array($userquery);

	  echo '"user_id":"'.$userdetails['user_id'].'",';
	  echo '"username":"'.$userdetails['username'].'",';
	  echo '"first_name":"'.$userdetails['first_name'].'",';
	  echo '"last_name":"'.$userdetails['last_name'].'",';
	  echo '"password":"'.$userdetails['password'].'",';
	  echo '"email":"'.$userdetails['email'].'",';
	  echo '"userImage":"'.$userdetails['picture'].'",';
	  echo '"country_id":"'.$userdetails['country_id'].'",';
	  echo '"modified_date":"'.$userdetails['modified_date'].'"';
	  
		
		

while($categorydata=mysql_fetch_array($category)){

echo '"'.$categorydata['country_name'].'"';
echo ':[';
 
$business=mysql_query("select * from schools where country_id='".$categorydata['country_id']."'".$queryin);
$businesscount=mysql_num_rows($business);
$ibusinesscount=0;
while($businessdetails=mysql_fetch_array($business)){
 $images='['.'"'.$businessdetails['image1'].'","'.$businessdetails['image2'].'"]';
$imagedata=substr($images,-1); 


if($businessdetails['image2']!=''){
$img2=','.$businessdetails['image2'];
}
else if($businessdetails['image3']!=''){
$img3=','.$businessdetails['image3'];
}
else if($businessdetails['image4']!=''){

$img4=','.$businessdetails['image4'];
}
else if($businessdetails['image5']!=''){

$img5=','.$businessdetails['image5'];
}



$img='['.$businessdetails['image1'].$img2.$img3.$img4.$img5.']';
 
$val = array("school_id" => $businessdetails['school_id'], 
			"school_name"=>$businessdetails['school_name'],
			 "email"=>$businessdetails['email'],
			 "phone"=>$businessdetails['phone'],
			 "address"=>$businessdetails['address'],
			 "logo"=>$businessdetails['logo'],
			 'images'=>$img,
            
            );
			 
$output = json_encode($val);
  
  
 if($ibusinesscount==$businesscount-1){

echo stripslashes($output);
}else{
echo stripslashes($output).',';
}
$ibusinesscount++;
 

}	






if($i==$categorycout-1){
echo ']';
}else{
echo '],';
}
$i++;

}
echo '}}';


}
/*else  if($_GET['type']=='GET'&&isset($_GET['user_id']))
{

$rs=SQL_QueryResult("Select * from users where `userid`='".$_GET['user_id']."'");

$rs=$rs[0];
$res=array();
$res['user_id']=$rs['user_id'];
$res['username']=$rs['username'];
$res['gender']=$rs['gender'];
$res['email']=$rs['email'];


if($rs['interested_male']=='0'){
$interested="Female";
}else{
$interested="Male";
}

$res['interestedin']=$interested;
$res['picture']=$rs['picture'];
$res['want_to']=$rs['want_to'];
$res['latitude']=$rs['latitude'];
$res['longitude']=$rs['longitude'];


$q="Select *  from  friendship where   friendship.to_id=".$_GET['userid']." AND friendship.from_id=".$_GET['frndid']." OR   friendship.from_id=".$_GET['userid']." AND friendship.to_id=".$_GET['frndid']."";
//echo $q;

 $rs=mysql_query($q);
$request=mysql_fetch_array($rs);
 if(mysql_num_rows($rs)>0){
if($request['status']=='ACCEPTED'){
$res['isFriend']="YES";
}else if($request['status']=='PENDING'){
$res['isFriend']="PENDING";

}else{
$res['isFriend']="NO";
}
 }else{ $res['isFriend']="NO";}





$resp=array();
$resp['result']=$res;
echo json_encode($resp);
exit(0);





}
else  if($_GET['type']=='GET_NEARBY'&&isset($_GET['userid']))
{

$rs=SQL_QueryResult("Select * from user where `id`='".$_GET['userid']."'");

$rs=$rs[0];

$lat=$_GET['lat'];
$long=$_GET['long'];
$range=(int)$_GET['range'];
$m=$rs['interested_male'];
$fm=$rs['interested_female'];

$query="";
if(($m==0&&$fm==0)||($m==1&&$fm==1))
$query="Select `id`,username,picture,latitude,`longitude` from user where `id`<>'".$_GET['userid']."'";
else if($m==1)
$query="Select `id`,username,picture,latitude,`longitude` from user where `id`<>'".$_GET['userid']."' AND gender='male'";
else if($fm==1)
$query="Select `id`,username,picture,latitude,`longitude` from user where `id`<>'".$_GET['userid']."' AND gender='female'";


//echo $query;

$rs=SQL_QueryResult($query);

$res=array();
foreach($rs as $r)
{

$frs=SQL_QueryResult("Select * from friendship where `from_id`='".$_GET['userid']."' AND `to_id`='".$r['id']."' AND status='ACCEPTED' OR `to_id`='".$_GET['userid']."' AND `from_id`='".$r['id']."' AND status='ACCEPTED'");

if(count($frs)>0)
$r['is_friend']='YES';
else
$r['is_friend']='NO';

$frs=SQL_QueryResult("Select * from friendship where `from_id`='".$_GET['userid']."' AND `to_id`='".$r['id']."' AND status='ACCEPTED' OR `to_id`='".$_GET['userid']."' AND `from_id`='".$r['id']."' AND status='PENDING'");
if(count($frs)>0)
$r['is_friend']='PENDING';

$dis=(int)distance($lat,$long,$r['latitude'],$r['longitude'],'K');
  //echo $dis.'<br/>';
if($dis<=$range)
{
$r['distance']=$dis;
array_push($res,$r);

}

}


$resp=array();
$resp['Near_by_User']=$res;


$getadvertisementquery="Select  * from ads ";

$addsrs=SQL_QueryResult($getadvertisementquery);
$addsres=array();
 $date = date('Y-m-d H:i:s');
 
foreach($addsrs as $addsr)
{

if($addsr['live']==1 && $addsr['start_time']>=$date && $addsr['privilage']!='user')
{
$addsdis=(int)distance($lat,$long,$addsr['latitude'],$addsr['longitude'],'K');
  //echo $dis.'<br/>';
if($addsdis<=$range)
{
$addsr['distance']=$addsdis;
array_push($addsres,$addsr);

}}

}
$addsresp=array();
$addsresp['Advertisments']=$addsres;

//For Promotions

$promotionquery="Select  * from ads where privilage='user' ";

$promotionsrs=SQL_QueryResult($promotionquery);
$promoaddsres=array();
foreach($promotionsrs as $promotionaddsr)
{

$promotionaddsrdis=(int)distance($lat,$long,$promotionaddsr['latitude'],$promotionaddsr['longitude'],'K');
  //echo $dis.'<br/>';
if($promotionaddsrdis<=$range)
{
$promotionaddsr['distance']=$promotionaddsrdis;
array_push($promoaddsres,$promotionaddsr);

}

}

$promotionresp=array();
$promotionresp['Promotions']=$promoaddsres;












$output1 = json_encode($resp);



//echo json_encode($resp);
//echo json_encode($addsresp);
 //0, -1)
 
echo substr(json_encode($resp),0, -1);
$advertisment=substr(json_encode($addsresp),1, -1);
echo ",".$advertisment;
//echo json_encode($addsresp);
$promotion=substr(json_encode($promotionresp),1);
echo ",".$promotion;

exit(0);




}*/
else  if($_GET['type']=='DELETE'&&isset($_GET['user_id']))
{

$rs=mysql_query("delete  from users where `user_id`='".$_GET['user_id']."'");

//$rs=$rs[0];

$resp['result']="SUCCESS";
echo json_encode($resp);





}

/*else  if($_GET['type']=='MSG_DELETE'&&isset($_GET['from_id'])&&isset($_GET['to_id']))
{


$rs=SQL_QueryResult("Select * from chat where `from_id`='".$_GET['from_id']."' and  to_id='".$_GET['to_id']."' OR `from_id`='".$_GET['to_id']."' and  to_id='".$_GET['from_id']."'");


 
foreach($rs as $r)
{
$insarray=array();
$insarray['msg_id']=$r['id'];
$insarray['user_id']=$_GET['from_id'];



$id=SQL_Insert('delete_msg',$insarray);

}







$res['result']='SUCCESS';
echo json_encode($res);















}
*/
else if($_GET['type']=='FOLLOW'&&isset($_GET['user_id'])&&isset($_GET['class_id']))
{
 



$rs=SQL_QueryResult("Select * from class_followers where user_id='".$_GET['user_id']."' and  class_id='".$_GET['class_id']."'");
if(count($rs)>0)
{
$rs=$rs[0];
$res=array();
$res['user_id']=$rs['user_id'];
$res['class_id']=$rs['class_id'];
$res['result']='User Already Follow This business';
echo json_encode($res);
exit(0);
}


$insarray=array();
$insarray['user_id']=$_GET['user_id'];
$insarray['class_id']=$_GET['class_id']; 


$id=SQL_Insert('class_followers',$insarray);





$rs=SQL_QueryResult("Select * from users where user_id='".$id."'");

//$rs=$rs[0];
$res=array();
 

$res['result']='SUCCESS';
echo json_encode($res);










}
else  if($_GET['type']=='UNFOLLOW'&&isset($_GET['user_id'])&&isset($_GET['class_id']))
{

$rs=mysql_query("delete  from class_followers where user_id='".$_GET['user_id']."' and  class_id='".$_GET['class_id']."'");

//$rs=$rs[0];

$resp['result']="SUCCESS";
echo json_encode($resp);





}

else
{

$res=array();
$res['result']='FAILED';
echo json_encode($res);

}

?>