<?php
function SQL_Connect($serverip,$user,$pass,$db)
{
//mysql_connect($serverip,$user,$pass);
//mysql_select_db($db);
}

function SQL_QueryResult($query)
{
$result=array();
$res=mysql_query($query);
while($row=mysql_fetch_array($res))
array_push($result,striprow($row));
return $result;

}

function SQL_Update($table,$params,$select)
{
	
$updatequery=array();

foreach($params as $key=>$val)
array_push($updatequery,"`".$key."`='".addslashes($val)."'");
$updatequery=implode(',',$updatequery);
	


$selectquery=array();


foreach($select as $key=>$val)
array_push($selectquery,$key."='".addslashes($val)."'");
$selectquery=implode(' and ',$selectquery);
	
	
$query='UPDATE '.$table.' SET '.$updatequery.' WHERE '.$selectquery;

//echo $query;

mysql_query($query);
	
	
}

function striprow($row)
{
	foreach($row as $key=>$val)
	{
		
	$row[$key]=stripslashes($val);	
	}
	
	return $row;
	
	}


function SQL_Query($query)
{
	
mysql_query($query);	
	
	}

function SQL_Insert($table,$array)
{

$keys=array();
$values=array();
foreach($array as $key=>$val)
{
array_push($keys,'`'.$key.'`');
array_push($values,"'".addslashes($val)."'");

}
$keys="(".implode(',',$keys).")";
$values="(".implode(',',$values).")";

$query="INSERT INTO ".$table.$keys." VALUES ".$values;

 //echo $query;

mysql_query($query);
return mysql_insert_id();

}

?>