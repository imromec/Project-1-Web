<?php
include('mysql.php');
include('config.php');
include('imageLib.php');

$school_id = $_GET['school_id'];
$user_id = $_GET['user_id'];

$category=mysql_query("select * from classes where school_id='".$school_id."'"); 
$businesscount=mysql_num_rows($category);
//$categorycout=mysql_num_rows($category);
$result = array();
$i=0;
 echo '{ "response" : "success",
  "classes":';
echo '[';

while($categorydata=mysql_fetch_array($category))
{
	
	$result['class_id'] = $categorydata['class_id'];
	$result['class_name'] = $categorydata['class_name'];

	$fl=mysql_query("select class_id from class_followers where user_id='".$user_id."' and class_id = '".$categorydata['class_id']."'");
	 $ct = mysql_num_rows($fl);	
	// $ft;
	if($ct != 0)
	{
		$result['follow_status'] = 'Yes';
//		while($ft = mysql_fetch_array($fl))
//		{			
//			$tt = $ft['class_id'];
//			if($tt == $categorydata['class_id'])
//			{
//			$result['follow_status'] = 'Yes';
//			}
//			else
//			{
//			$result['follow_status'] = 'No';
//			}
//		}
	}
	else
	{
	$result['follow_status'] = 'No';
	}
	
	//$tt = $ft['class_id'];
	
	/*$rs=SQL_QueryResult("Select * from class_followers where user_id='".$_GET['user_id']."'");
	if(count($rs)>0)
	{
	$rs=$rs[0];
	$res=array();
	$res['user_id']=$rs['user_id'];
	$res['class_id']=$rs['class_id'];
	$res['follow_status']='Yes';
	echo json_encode($res);
	}
	else*/
	
	
	if($i==$businesscount-1){
	echo json_encode($result);
	}else{
	echo json_encode($result).',';
	}
	$i++;

}
//print_r($result);
echo ']';
echo '}';

?>