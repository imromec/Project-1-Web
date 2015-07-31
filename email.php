<?php 
include("config.php");
//echo "select email from admin where  privilage!='user'";exit;
$rowquery=mysql_query("select email from admin where  privilage!='user'");

if(mysql_num_rows($rowquery)>0){
while($rowdata=mysql_fetch_array($rowquery)){
 $to  = $rowdata['email'];
$subject = 'Testing email';
$message = $_GET['message'];
// To send HTML mail, the Content-type header must be set

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= "From:webmaster@thegolfgateway.com\n"; // Who the email is from 
mail($to, $subject, $message, $headers);
  //header("location:changepassword.php?msg=1");

	} 
	
	echo '[';
$value=array();
$val = array("MSG" =>"Msg Send On mail address",
			
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