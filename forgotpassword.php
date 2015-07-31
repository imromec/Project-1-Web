<?php 
include("config.php");

$rowquery=mysql_query("select * from users where email='".$_REQUEST['email']."'");

if(mysql_num_rows($rowquery)>0){
$rowdata=mysql_fetch_array($rowquery);
 $to  = $_REQUEST['email'];
$subject = 'Forget Password!';
$message = 'UserName :&nbsp;'.$rowdata['username'].'<BR>Password :&nbsp;'.$rowdata['password'];
// To send HTML mail, the Content-type header must be set

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= "From:webmaster@educommsystems.com\n"; // Who the email is from 
mail($to, $subject, $message, $headers);
  //header("location:changepassword.php?msg=1");
echo '[';
$value=array();
$val = array("MSG" =>"Password Send On mail address",
			
             );
			
			
			
$output = json_encode($val);
echo stripslashes($output);
echo ']';
	 }else


{

echo '[';
$value=array();
$val = array("MSG" =>"send a valid email",
			
             );
			
			
			
$output = json_encode($val);
echo stripslashes($output);
echo ']';


 // header("location:changepassword.php?msg=2");


 }	
?>