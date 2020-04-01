<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'application/PHPMailer/src/Exception.php';
require 'application/PHPMailer/src/PHPMailer.php';
require 'application/PHPMailer/src/SMTP.php';


// This model sends different emails to specified email address based on the payment method
class EmailModel extends CI_Model {
    public function sendEmail($emailAddress, $paymentMethod)
    {
        // email details
        $data['EMAIL_RECIEVER'] = $emailAddress;
        $data['EMAIL_SENDER'] = "uoawdcc@gmail.com";
        
        // event details
        $data['EVENT_NAME'] = "Pool Tournament";
        $data['EVENT_TIME'] = "27th March, 5:30 pm";
        $data['EVENT_MONTH'] = "March 2020";
        $data['EVENT_DAY'] = "27";
        $data['EVENT_LOCATION'] = "Orange Pool club: 9 City Road, Auckland CBD";
        $data['EVENT_FEE'] = "3$ For ASPA Members (5$ Membership Fee)";
        $data['EVENT_IMAGE'] = "https://secure.meetupstatic.com/photos/event/a/6/d/6/600_484542710.jpeg";

        // transfer details
        $data['TRANSFER_AMOUNT'] = "$3";
        $data['TRANSFER_ACCOUNT'] = "00000";

        // default colour of the payment method shown on email (red)
        $data['MSG_COLOUR'] = "#ff0000"; 
        
        // change email details based on different payment method
        if ($paymentMethod == "online") 
        {
            $data['EMAIL_SUBJECT'] = "Event Payment Confirmation - ASPA 2020";
            $data['TICK_IMAGE'] = "assets/images/Green_Tick.png";
            $data['PAYMENT_DETAIL'] = "PAID ONLINE";
            $data['MSG_COLOUR'] = "#00ff00";
        }
        elseif ($paymentMethod == "cash") {
            $data['EMAIL_SUBJECT'] = "Event Registration - ASPA 2020";
            $data['TICK_IMAGE'] = "assets/images/Grey_Tick.jpg";
            $data['PAYMENT_DETAIL'] = "CASH";
            $data['TRANSFER_DETAIL'] = "";
        }
        else {
            $data['EMAIL_SUBJECT'] = "Event Registration - ASPA 2020";
            $data['TICK_IMAGE'] = "assets/images/Grey_tick.png";
            $data['PAYMENT_DETAIL'] = "TRANSFER";
            $data['TRANSFER_DETAIL'] = "Please transfer " . $data['TRANSFER_AMOUNT'] . " to our bank account - " . $data['TRANSFER_AMOUNT'] . "\r\n";
        }


        // Body of email in HTML format (Extracted from mailchimp template)
        $message = $this->load->view('EmailTemplate.php', $data, TRUE);

        // mail($to,$subject,$message,$headers);


        // Load Composer's autoloader
        // require 'vendor/autoload.php';

        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'uoawdcc@gmail.com';                     // SMTP username
            $mail->Password   = 'orkyhxoabrnpqeri';                               // SMTP password
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('uoawdcc@gmail.com', 'WDCC');
            $mail->addAddress($data['EMAIL_RECIEVER']);     // Add a recipient
            // $mail->addAddress('ellen@example.com');               // Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            // Attachments
            // $mail->AddEmbeddedImage('assets/images/ASPA_logo.png','ASPA_logo');
            // $mail->AddEmbeddedImage($TICK_IMAGE,'Tick');
            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $data['EMAIL_SUBJECT'];
            $mail->Body    = $message;
            $mail->AltBody = 'Thank you for signing up to ASPA event. These email is shown due to the your device restriction.';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

