<?php
// Include DB connection
include '../includes/db_connect.php';

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

// When admin submits the form
if (isset($_POST['send'])) {
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Fetch all student emails from DB
    $query = "SELECT email FROM students";
    $result = mysqli_query($conn, $query);

    $mail = new PHPMailer(true);

    try {
        // SMTP settings (you can use Gmail or Brevo)
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;  // Brevo SMTP host
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER; // your Brevo email
        $mail->Password   = SMTP_PASSWORD;
        $mail->Port       = SMTP_USER;

        $mail->setFrom('your_brevo_email@example.com', 'Exam Notification System');
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = nl2br($message);

        // Send mail to each student
        while ($row = mysqli_fetch_assoc($result)) {
            $mail->addAddress($row['email']);
            $mail->send();
            $mail->clearAddresses();
        }

        echo "<script>alert('✅ Notification sent successfully to all students!'); window.location='send_notification.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('❌ Failed to send notification: {$mail->ErrorInfo}');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Send Notification</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: Arial;
            background: #f8f9fa;
            padding: 30px;
        }
        .container {
            background: white;
            width: 600px;
            margin: auto;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        textarea {
            width: 100%;
            height: 150px;
            margin-top: 10px;
            padding: 10px;
            resize: none;
        }
        input[type=text] {
            width: 100%;
            padding: 10px;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Send Exam Notification to All Students</h2>
    <form method="POST" action="">
        <label>Subject:</label><br>
        <input type="text" name="subject" required><br><br>

        <label>Message:</label><br>
        <textarea name="message" placeholder="Type your exam message here..." required></textarea><br>

        <button type="submit" name="send">Send Notification</button>
    </form>
</div>
</body>
</html>
