<?php 
	
function pushnote($dtoken ,$message,$ident,$badge=1)
{
 
//echo $dtoken;
//echo $message;
//echo $ident;
//print_r($data);

$message=stripslashes($message); 	
$sound = "azan.aiff";
$data=0;
// Construct the notification payload
$body = array();
$body['aps'] = array('alert' => $message);
if ($badge)
$body['aps']['badge'] = 1;
if ($sound)
$body['aps']['sound'] = $sound;


if($data)
{


$body['aps']['data'] = $data;

}
$body['aps']['ident'] = $ident;
$body['aps']['badge'] = 1;


$ctx = stream_context_create();
/*stream_context_set_option($ctx, 'ssl','allow_self_signed', true);
stream_context_set_option($ctx, 'ssl', 'verify_peer',false);*/
stream_context_set_option($ctx, 'ssl', 'local_cert', 'pem/Certificates.pem'); 
// assume the private key passphase was removed.
// stream_context_set_option($ctx, 'ssl', '1234', $pass);

$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
if (!$fp) {
print "Failed to connect $err $errstr\n";
$fail=1;
return;
}
else {
print "Connection OK\n";
}

$payload = json_encode($body);
$msg = chr(0) . pack("n",32) . pack('H*', str_replace(' ', '', $dtoken)) . pack("n",strlen($payload)) . $payload;
print "sending message :" . $payload . "\n";
fwrite($fp, $msg);
fclose($fp); 
			
}	

 //pushnote('93f90b0ce243bbae0b50b8d5a68ce299446b68c02805e97024dc853b6d128fd1','hi','TEST','1');

?>