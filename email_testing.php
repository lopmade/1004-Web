<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './email_credentials.php';


$email = '2003256@sit.singaporetech.edu.sg';
$token = '31002f68a3c994a11f83eece1bb8b93d';
$first_name = 'test';

//Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);
$url = $_SERVER['SERVER_NAME']."/webapplication/verify.php?email=".$email."&token=".$token;

try {
    //Server settings
    $mail->SMTPDebug = 1;                                       //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'ict1004.web.project@gmail.com';        //SMTP username
    $mail->Password   = 'P@$$w0rd1996';                         //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('ict1004.web.project@gmail.com', 'POKEDEX');
    $mail->addAddress('2003256@sit.singaporetech.edu.sg', 'JJ');     //Add a recipient


    //Attachments


    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'Hi'.$first_name.',<br>Thanks for signing up to POKEDEX!<br>We want to make sure that we got your email right. Click the link below or copy the URL to the URL bar to verify your account!<br>'.'<a href = http://'.$url.'>Click Here!<a>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>