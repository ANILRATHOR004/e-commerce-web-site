<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        // SMTP Settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'anilrathore913148@gmail.com'; // Apni email
        $mail->Password   = 'ihbk jfab dqvl qqwb'; // Yahan wo 16-digit password dalo
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('anilrathore913148@gmail.com', 'Schools Hub Support');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset OTP';
        $mail->Body    = "Aapka OTP code hai: <b>$otp</b>. Isse kisi ko na batayein.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Agar yahan error aaye toh browser mein dikhega
        error_log("Mail Error: " . $mail->ErrorInfo); 
        return false;
    }
}
?>