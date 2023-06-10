<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$phpmailer = new PHPMailer(true);

//Server settings
$phpmailer->CharSet = "UTF-8";
$phpmailer->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
$phpmailer->isSMTP();                                            //Send using SMTP
$phpmailer->Host       = 'in-v3.mailjet.com';                     //Set the SMTP server to send through
$phpmailer->SMTPAuth   = true;                                   //Enable SMTP authentication
$phpmailer->Username   = '180bc8fb80ac367d866a264306686344';                     //SMTP username
$phpmailer->Password   = 'ca4520f07c24fdd37b9523f5fb13a4a6';                               //SMTP password
$phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
$phpmailer->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
?>
