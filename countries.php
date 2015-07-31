<?php
include('mysql.php');
include('config.php');
include('imageLib.php');





$category=mysql_query("SELECT * FROM countries ORDER BY countries.country_name ASC"); 
$businesscount=mysql_num_rows($category);

$categorycout=mysql_num_rows($category);


$result = array();
$i=0;
 echo '{ "response" : "success",
  "response data":';
echo '[';
while($categorydata=mysql_fetch_array($category)){


$result['country_id'] = $categorydata['country_id'];
$result['country_name'] = $categorydata['country_name'];



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