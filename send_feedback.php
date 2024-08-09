<?php
$title = 'Send Feedback';
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$nav = nav();
//redirect user to home page if not loged in
if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
}else{
    $sub = subjects();
    ob_start();
    include 'layouts/feedback.html.php';
    // Get form data
    try{
        if (isset($_POST['mtitle'])){
            //basic mail data

            $title = $_POST['mtitle'];
            $content = $_POST['mcont'];
            $user_id = $_SESSION['userid'];
            require 'phpmailer/src/Exception.php';
            require 'phpmailer/src/PHPMailer.php';
            require 'phpmailer/src/SMTP.php';
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->SMTPDebug = 0;                                       // Enable verbose debug output (0 for no output)
                $mail->isSMTP();                                            // Set mailer to use SMTP
                $mail->Host       = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
                $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                $mail->Username   = 'manhngo712@gmail.com';                        // SMTP username
                $mail->Password   = 'qaoh jkos hzfo sudb';                        // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption, `PHPMailer::ENCRYPTION_SMTPS` also accepted
                $mail->Port       = 587;                                    // TCP port to connect to

                // Recipients
                $mail->setFrom('manhngo712@gmail.com', 'Mailer');
                $mail->addAddress('manhngo712@gmail.com', 'Joe User');     // Add a recipient
                $mail->addReplyTo('manhngo712@gmail.com', 'Information');   // Add reply-to address

                // Content
                $mail->isHTML(true);                                        // Set email format to HTML
                $mail->Subject = $title;
                $mail->Body    = $content;
                $mail->send();
                echo '<div class="alert alert-success d-flex justify-content-center align-items-center mt-5 mx-auto" role="alert" style="max-width: 18rem;" id="alert">
                            Your feedback has been sent successfully
                        </div>
                        <script>';
            } catch (Exception $e) {
                echo '<div class="alert alert-warning d-flex justify-content-center align-items-center mt-5 mx-auto" role="alert" style="max-width: 18rem;" id="alert">
                        Message could not be sent. Mailer Error
                        </div>
                        <script>';
            }
            //Send success message to user
            echo'setTimeout(function() {
                            document.getElementById("alert").remove();
                        }, 4000);
                    </script>';
        }
    }catch (PDOException $e){
        $output= 'Database error: ' . $e->getMessage();
    }
    $output = ob_get_clean();
}
include 'layouts/index.html.php';
    