<?php


function dateDifference($date) {
	$diff = strtotime(date('Y-m-d H:i:s', time())) - strtotime($date);

	$sec   = $diff % 60;
	$diff  = intval($diff / 60);
	$min   = $diff % 60;
	$diff  = intval($diff / 60);
	$hours = $diff % 24;
	$days  = intval($diff / 24);
        

        if($sec<0)
        $sec=-1*$sec;
if($min<0)
        $min=-1*$min;
if($hours<0)
        $hours=-1*$hours;
if($days<0)
        $days=-1*$days;






if($days==0&&$hours==0&&$min==0)
$date=$sec.'s';
else if($days==0&&$hours==0)
$date=$min.'m';
else if($days==0)
$date= $hours.'h';
else
$date=$days.'d';






	return $date;
}



?>