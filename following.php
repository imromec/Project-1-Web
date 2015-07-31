<?php
include('mysql.php');
include('config.php');
include('imageLib.php');


$user_id = $_GET['user_id'];

$fl=mysql_query("select class_id from class_followers where user_id='".$user_id."'");

$ct = mysql_num_rows($fl);

$result = array();
$i=0;
 echo '{ "response" : "success",
  "classes":';
echo '[';

while($follower = mysql_fetch_array($fl))
{
	$cls = $follower['class_id'];

$category=mysql_query("select * from classes where class_id='".$cls."'"); 
$businesscount=mysql_num_rows($category);



while($categorydata=mysql_fetch_array($category))
{
	
	
	$result['class_id'] = $categorydata['class_id'];
	$result['class_name'] = $categorydata['class_name'];

	

    
	

}
//print_r($result);
if($i==$ct-1){
	echo json_encode($result);
	}else{
	echo json_encode($result).',';
	}
	$i++;
}
echo ']';
echo '}';
?>