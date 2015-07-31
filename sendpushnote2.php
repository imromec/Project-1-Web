<?php

include('mysql.php');
include('imageLib.php');
include('sendtok.php');
include('userlist.php');
include('config.php');
define("GOOGLE_API_KEY", "AIzaSyA-SGYe6Zx-yfeyG43AsidZfKxryuD3qNM");
define("GOOGLE_GCM_URL", "https://android.googleapis.com/gcm/send");

function send_gcm_notify($reg_id, $message) {
 
    $fields = array(
		'registration_ids'  => array( $reg_id ),
		'data'              => array( "message" => $message ),
	);
				
	$headers = array(
		'Authorization: key=' . GOOGLE_API_KEY,
		'Content-Type: application/json'
	);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, GOOGLE_GCM_URL);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));




	$result = curl_exec($ch);
	
	if ($result === FALSE) {
		die('Problem occurred: ' . curl_error($ch));
	}

	curl_close($ch);
	//echo $result; 
 }
	





 //  $userid="Select user_id from  follow_tbl  where    business_id=".$_GET['business_id']; 1,2,3
 
 
 
 
// $user_ids=array();
 
//$uids=mysql_query($userid);
//echo $uids;
//while($resuluserid=mysql_fetch_array($uids))
//{
//$user_ids[]=$resuluserid['user_id'];
//} 

//if(count($user_ids)>0){
//$userids=implode(',',$user_ids);
 //$queryin=" `id`  IN (".$userids.")";
//}else{
 //$queryin=" `id`  IN (".$user_ids.")";
//}

$d1= date("d/m/Y");

echo $d1;

$query = mysql_query("select * from appointment_notification where appointment_notification.date10 + appointment_notification.date = '".$d1."'");

$message;

$t = array( );
while ($row = mysql_fetch_array($query)) {
	
	
	echo $status1 = $row['status1'];
	echo $status10 = $row['status10'];
	echo $today = $row['today'];
	$doctor = $row['doctor'];
	$time = $row['time'];	

	if($time > '12:00')
	{
		$y = $row['time'].' PM';
	}
	else
	{
		$y= $row['time'].' AM';
	}		

	$date10 = $row['date10'];
	$date = $row['date'];

	if($status1 != 'Sent' || $status10 != 'Sent')
	{
		array_push($t,$row['userid']);
		
	} 

} 
if(!empty($t)){
	$string = implode(',', $t);
	echo $string;
}
else{
echo "fail";


}


/*
$query2 = "select * from appointment_notification where appointment_notification.status1 + appointment_notification.status10 = 'Sent'";
		$result2 = mysql_query($query2);
		$k = array();
		while($row2 = mysql_fetch_array($result2))
		{
			echo $row2['userid'];
			$k[] = $row2['userid'];
		}
		
		$string = implode(',', $k);
		echo $string;
*/
$queryin= $string;


  
//echo "Select ios_token,android_token,type from  user where ".$queryin;
//echo "Select ios_token,android_token,type from  user where id='".$queryin."'";

 $tokens=SQL_QueryResult("Select ios_token,android_token,type from  user where ".$queryin);
print_r($tokens);
foreach($tokens as $t)
{
 //echo $t['type'];
if($t['type']=='ANDROID'){
echo 'sending..';
//echo $t['android_token'];exit; 
echo $message;
send_gcm_notify($t['android_token'],$message);
 }
else if($t['type']=='IOS'){
 echo 'sending..';
 
pushnote($t['ios_token'],$message,1,1);
 
 }


}



echo "SUCCESS";

$r = date("d/m/Y");

mysql_query("update appointment_notification set status1='Sent', status10='Sent' where appointment_notification.date10 = '".$r."'");

mysql_query("update appointment_notification set status1='Sent', status10='Sent' where appointment_notification.date = '".$r."'");





exit(0);








 
?>
