<?php
include('mysql.php');
include('imageLib.php');


$userid=$_GET['userid'];

if($_GET['type']=='GET_FRIENDS')
{

$u=SQL_QueryResult('select * from `user` where `id`='.$userid)[0];





$q="Select *  from  friendship where  friendship.status='ACCEPTED' AND friendship.to_id=".$userid." OR friendship.status='ACCEPTED' AND friendship.from_id=".$userid."";
$res=SQL_QueryResult($q);

//echo $q;

$result=array();

foreach($res as $r)
{
 if($r['to_id']==$userid)
$r=SQL_QueryResult("select user.id as 'userid', user.username as 'username',user.picture as 'picture',user.gender as 'gender',user.latitude as 'latitude',user.longitude as 'longitude' from `user` where `id`=".$r['from_id'])[0];
else 
$r=SQL_QueryResult("select user.id as 'userid',  user.username as 'username',user.picture as 'picture',user.gender as 'gender',user.latitude as 'latitude',user.longitude as 'longitude' from `user` where `id`=".$r['to_id'])[0];



$dis=1000*(int)distance($u['lat'],$u['long'],$r['lat'],$r['long'],'K');

$r['distance']=$dis;


array_push($result,$r);

}





echo json_encode($result);




}


if($_GET['type']=='GET_REQUEST')
{

$u=SQL_QueryResult('select * from `user` where `id`='.$userid)[0];


$q="Select friendship.id as 'request_id', user.id as 'userid', user.username as 'username',user.picture as 'picture',user.gender as 'gender',user.latitude as 'latitude',user.longitude as 'longitude'  from user,friendship where friendship.to_id=".$userid." and friendship.status='PENDING' and user.id=friendship.from_id";

$res=SQL_QueryResult($q);

$result=array();

foreach($res as $r)
{
//echo $u['lat'].'-'.$u['long'].'--'.$r['lat'].'-'.$r['long'];

$dis=1000*(int)distance($u['lat'],$u['long'],$r['lat'],$r['long'],'K');

$r['distance']=$dis;


array_push($result,$r);

}





echo json_encode($result);




}

if($_GET['type']=='SEND_REQUEST')
{

$toids=explode(',',$_GET['to_id']);
$resp='';

foreach($toids as $tiod)
{

$ins_array=array();

if(count(SQL_QueryResult("Select * from friendship where from_id=".$userid." and to_id=".$tiod." OR to_id=".$userid." and from_id=".$tiod." and status='PENDING'")))
$resp= 'REQUEST_EXISTS';
else if(count(SQL_QueryResult("Select * from friendship where from_id=".$userid." and to_id=".$tiod." OR to_id=".$userid." and from_id=".$tiod."  and status='ACCEPTED'")))
$resp= 'ALREADY_FRIEND';

else 
{

$ins_array['from_id']=$userid;
$ins_array['to_id']=$tiod;
$ins_array['status']='PENDING';



$res=SQL_Insert('friendship',$ins_array);

if($res)
$resp= 'SUCCESS';
else 
$resp= 'FAILED';

}


}
echo $resp;
}

if($_GET['type']=='REQUEST_ACTION')
{


$q="Update friendship set status='".$_GET['action']."' where `id`=".$_GET['req_id'];
SQL_Query($q);

echo 'SUCCESS';





}

if($_GET['type']=='UNFRIEND')
{
$userid=$_GET['userid'];
$fr_id=$_GET['friendid'];
$q="delete  from  friendship where    friendship.to_id=".$userid." AND  friendship.from_id=".$fr_id." OR  friendship.to_id=".$fr_id."  AND friendship.from_id=".$userid;

$rs=mysql_query($q);

//$rs=$rs[0];

$resp['result']="SUCCESS";
echo json_encode($resp);

}




?>