<?php
require 'PHPMailerAutoload.php';
$mail = new PHPMailer;
//$mail->isSMTP();
$mail->SMTPSecure = 'ssl';
$mail->SMTPAuth = true;
$mail->Host = 'smtp.gmail.com'; 'mail.mydomain.com';
$mail->Port = 465;
$mail->Username = 'ogn10767@gmail.com';
$mail->Password = 'thongthai';
$mail->setFrom('ogn10767@gmail.com');
$mail->addAddress('thongph88@gmail.com');
$mail->Subject = 'Hello from PHPMailer!';
$mail->Body = 'This is a test.';
//send the message, check for errors
if (!$mail->send()) {
    echo "ERROR: " . $mail->ErrorInfo;
} else {
    echo "SUCCESS";
}
