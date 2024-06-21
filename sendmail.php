<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $firstName = htmlspecialchars($_POST['name']);
    $lastName = htmlspecialchars($_POST['lastName']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // Verify email
    if (!$email) {
        die('Invalid email address.');
    }

    // Prepare the email
    $to = 'your-email@example.com';  // Replace with your email address
    $email_subject = 'New Contact Form Submission: ' . $subject;
    $email_body = "First Name: $firstName\nLast Name: $lastName\nEmail: $email\n\nMessage:\n$message";
    $headers = 'From: ' . $email . "\r\n" .
               'Reply-To: ' . $email . "\r\n" .
               'X-Mailer: PHP/' . phpversion();

    // Send the email
    if (mail($to, $email_subject, $email_body, $headers)) {
        echo 'Thank you for your message. We will get back to you soon.';
    } else {
        echo 'Sorry, there was an error sending your message. Please try again later.';
    }
} else {
    echo 'Invalid request method.';
}
?>
