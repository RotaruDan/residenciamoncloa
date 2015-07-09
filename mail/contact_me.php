<?php
// Check for empty fields
if(empty($_POST['name'])  		||
   empty($_POST['email']) 		||
   empty($_POST['phone']) 		||
   empty($_POST['message'])	||
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
   {
	echo "¡No se han proporcionado argumentos!";
	return false;
   }

if(isset($_POST['g-recaptcha-response'])){
 	if(!verify($_POST['g-recaptcha-response'])) {
		echo "La comprobación NoCAPTCHA es incorrecta";
		return false;
 	}
} else {	
	echo "¡No se han proporcionado captcha!";
	return false;
}

	echo "¡No se han proporcionado captcha!";
	return false;
	
$name = $_POST['name'];
$email_address = $_POST['email'];
$phone = $_POST['phone'];
$message = $_POST['message'];
	
// Create the email and send the message
$to = 'testresidenciamoncloa@gmail.com'; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
$email_subject = "Formulario de contacto de Residenciamoncloa:  $name";
$email_body = "Has recibido un nuevo mensaje de tu formulario de contacto de la web.\n\n"."Aquí tienes los detalles:\n\nNomnre: $name\n\nEmail: $email_address\n\nPteléfono: $phone\n\nMensaje:\n$message";
$headers = "From: noreply@residenciamoncloa.com\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
$headers .= "Reply-To: $email_address";	
mail($to,$email_subject,$email_body,$headers);
return true;	



function verify($response) {
	$ip = $_SERVER['REMOTE_ADDR']; //server Ip
	$key="6LeMlAkTAAAAAMWa7WWMcyjHhow7JkP6yCppLyWG"; // Secret key

	//Build up the url
	$url = 'https://www.google.com/recaptcha/api/siteverify';
	$full_url = $url.'?secret='.$key.'&response='.$response.'&remoteip='.$ip;

	//Get the response back decode the json
	$data = json_decode(file_get_contents($full_url));
	//Return true or false, based on users input
	if(isset($data->success) && $data->success == true) {
		return True;
	}
	return False;
}		
?>
