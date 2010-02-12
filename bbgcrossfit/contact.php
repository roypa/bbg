<?php

if(!$_POST) exit;

$email = $_POST['email'];


//$error[] = preg_match('/\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i', $_POST['email']) ? '' : 'INVALID EMAIL ADRESS';
if(!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*" ."@"."([a-z0-9]+([\.-][a-z0-9]+)*)+"."\\.[a-z]{2,}"."$",$email )){
$error.="Invalid email adress entered";
$errors=1;
}
if($errors==1) echo $error;
else{

$values = array ('name','email','message');
$required = array('name','email','message');
 
$your_email = "info@bbgcrossfit.no";
$email_subject = "Melding fra nett";
$email_content = "new message:\n";
 
//for( $i = 0 ; $i < count( $values ) ; ++$i ) {
//	for( $c = 0 ; $c < count( $required ) ; ++$c ) {
//		if( $values[$i]==$required[$c] ) {
//			echo $required[$x];
//			if( empty($_POST[$values[$i]]) ) { echo 'VENNLIGST FYLL INN ALLE FELT'; exit; }
//		}
//	}
//	$email_content .= $values[$i].': '.$_POST[$values[$i]]."\n";
//}

foreach($values as $value){
  if(in_array($value,$required)){
    if( empty($_POST[$value]) ) { echo 'VENNLIGST FYLL INN ALLE FELT'; exit; }
    $email_content .= $value.': '.$_POST[$value]."\n";
  }
}
 
if(mail($your_email,$email_subject,$email_content)) {
	echo 'Melding sent! Klikk tilbake for å returnere til BBG Crossfit'; 
} else {
	echo 'ERROR!';
}
}
?>