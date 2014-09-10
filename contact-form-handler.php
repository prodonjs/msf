<?php
$siteroot = 'http://msandf.com/';

/* Ensure that a valid token has been passed by the form
 * and that it reflects a timestamp no more than 5 minutes (300 seconds) ago */
$token_threshold = time() - 300;
if(!isset($_POST['token']) || $_POST['token'] < $token_threshold) {
    header('Location: contact.php');
    exit;
}


$from_email = $_POST['email'];
$to = 'andrew.p.bader@gmail.com';/* Add email addresses here. Seperate with comma no spaces */
$from = "Website Contact Inquiry for MS&F<$from_email>";
$headers = "From: " . $from . "\nContent-Type: text/html";
$subject = "Inquiry from " . $_POST['name'];

/* Message can contain basic html */
$message = "<div style='font-family:Verdana, Arial;font-size:14px;font-color:#222;'><br />
    <b>Name:</b> ".$_POST['name']."<br />
    <br />
    <b>Company:</b> ".$_POST['company']."<br />
    <br />
    <b>Email:</b> ".$_POST['email']."<br />
    <br />
    <b>Phone:</b> ".$_POST['phone']."<br />
    <br />
    <b>Message:</b> ".$_POST['message']."<br />

    </div>";
/* Send message and redirect to thanks page */
mail($to,$subject,$message,$headers);
header('Location: contact_thanks.php');
