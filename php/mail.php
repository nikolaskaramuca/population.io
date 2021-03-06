<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

if ($_GET["auth"] != "jLFscl7E7oz85D8P") {
  header('X-PHP-Response-Code: 404', true, 404);
  exit();
}

require 'libs/PHPMailer/PHPMailerAutoload.php';
require 'libs/Template.php';

$config = array(
  'host' => 'smtp.gmail.com',
  'username' => 'no-reply@47nord.de',
  'password' => '8x6HgzTf',
  'subject' => 'population.io'
);

$request_body = file_get_contents('php://input');
$data = json_decode($request_body);
$view = new Template();
$view->setVars(get_object_vars($data));

$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = $config['host'];
$mail->SMTPAuth = true;
$mail->Username = $config['username'];
$mail->Password = $config['password'];
$mail->SMTPSecure = 'tls';
$mail->WordWrap = 100;
$mail->isHTML(true);
$mail->Subject = $config['subject'];
$mail->CharSet  = 'UTF-8';
$mail->SetFrom("worldpopulationproject@gmail.com");
$mail->Body = $view->render('template.tpl');
$mail->addAddress("aweiss@47nord.de");

$response = array();

if (!$mail->send()) {
  $response = array(
    'success' => false,
    'message' => $mail->ErrorInfo
  );
} else {
  $response = array(
    'success' => true,
    'message' => 'Message has been sent'
  );
}

header('Content-type: application/json');
exit(json_encode($response));
?>