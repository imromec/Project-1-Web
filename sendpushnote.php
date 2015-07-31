<?php
include('mysql.php');
include('imageLib.php');
include('sendtok.php');

include('config.php');
define("GOOGLE_API_KEY", "AIzaSyD4Zp7J9Zy4xQjSPlxvi__MLyAtzZzeJQM");
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
	





   $userid="Select user_id from  class_followers  where  class_id=".$_GET['class_id'];
 
 $user_ids=array();
$uids=mysql_query($userid);
while($resuluserid=mysql_fetch_array($uids))
{
$user_ids[]=$resuluserid['user_id'];

} 
if(count($user_ids)>0){
$userids=implode(',',$user_ids);
 $queryin=" `user_id`  IN (".$userids.")";
}else{
 $queryin=" `user_id`  IN (".$user_ids.")";

}
  
  echo "Select ios_token,android_token,type from  users where ".$queryin;

$tokens=SQL_QueryResult("Select ios_token,android_token,type from  users where ".$queryin);
print_r($tokens);
foreach($tokens as $t)
{
 //echo $t['type'];
if($t['type']=='ANDROID'){
echo 'sending..';
//echo $t['android_token'];exit; 
send_gcm_notify($t['android_token'],$_GET['message']);
 }
else if($t['type']=='IOS'){
 echo 'sending..';
 
pushnote($t['ios_token'],$_GET['message'],1,1);
 
 }


}
echo "SUCCESS";

exit(0);



 
?>