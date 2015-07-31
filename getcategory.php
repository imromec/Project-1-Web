<?php
include('mysql.php');
include('imageLib.php');
error_reporting(0);


 if($_GET['type']=='CATEGORYLIST')
{



// IN (".implode(',',$array).")
$q="Select *  from  category order by category_id" ;

//echo $q;exit;
$res=SQL_QueryResult($q);

 $result=array();
foreach($res as $r)
{

 $time1=time();
 
array_push($result,$r);

}

echo json_encode($result);






 
}


else
{

$res=array();
$res['result']='FAILED';
echo json_encode($res);

}

?>