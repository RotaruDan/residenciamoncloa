<?php

// error_reporting(E_ALL);

// Desactivar toda notificación de error
error_reporting(0);

date_default_timezone_set('Europe/Madrid');

// Check for empty fields
if(empty($_POST['name'])  		||
   empty($_POST['email']) 		||
   empty($_POST['phone']) 		||
   empty($_POST['message'])	||
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) {
		$output = json_encode(array('type'=>'error', 'text' => '¡No se han proporcionado argumentos!'));
        die($output);
		return false;
   }	

if(isset($_POST['g-recaptcha-response'])){

	function verify($response) {
		$ip = $_SERVER['REMOTE_ADDR']; //server Ip
		$key="secret_key"; // Secret key

		//Build up the url
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$full_url = $url.'?secret='.$key.'&response='.$response.'&remoteip='.$ip;

		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $full_url);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Residenciamoncloa');
		//Get the response back decode the json
		$data = json_decode(curl_exec($curl_handle));
		curl_close($curl_handle);

		//Return true or false, based on users input
		if(isset($data->success) && $data->success == true) {
			return true;
		}
		return false;
	}

 	if(!verify($_POST['g-recaptcha-response'])) {
		$output = json_encode(array('type'=>'error', 'text' => 'La comprobación NoCAPTCHA es incorrecta'));
        die($output);
		return false;
 	}
} else {	
	$output = json_encode(array('type'=>'error', 'text' => '¡No se han proporcionado captcha!'));
    die($output);
	return false;
}
	
$name = $_POST['name'];
$email_address = $_POST['email'];
$phone = $_POST['phone'];
$message = $_POST['message'];
	
// Create the email and send the message
$to = "to_email"; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
$email_subject = "Formulario de contacto de Residenciamoncloa:  $name";
$email_body = "Has recibido un nuevo mensaje de tu formulario de contacto de la web.\n\nAquí tienes los detalles:\n\nNombre: $name\n\nEmail: $email_address\n\nTeléfono: $phone\n\nMensaje:\n$message";	


require_once('./class.phpmailer.php');



$mail             = new PHPMailer();



$body             = $email_body;



$mail->IsSMTP();

$mail->Host       = "localhost";

$mail->SMTPDebug  = false;                     // enables SMTP debug information (for testing), 1 = errors and messages, 2 = messages only


$mail->SMTPAuth   = true;                 



$mail->Port       = 587;                    

$mail->Username   = "smtp_username"; 

$mail->Password   = "smtp_password";        



$mail->SetFrom('informacion@residenciamoncloa.com', 'Info. Residenciamoncloa');



$mail->Subject 	= $email_subject;


$mail->Body = $body;
// $mail->MsgHTML($body);



$mail->AddAddress($to, "Residencia Moncloa");


if(!$mail->Send()) {

  echo "Mailer Error: " . $mail->ErrorInfo;

} else {

  echo "Mensaje enviado.";

}

?>
