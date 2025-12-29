<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
include 'config.php';

// Fetch exams for dropdown
$exams_result = mysqli_query($conn, "SELECT * FROM exams ORDER BY exam_date ASC");

$successEmails = [];
$failedEmails = [];

if (isset($_POST['send'])) {
    $exam_id = intval($_POST['exam_id']);
    $custom_message = mysqli_real_escape_string($conn, $_POST['custom_message']);

    // Fetch exam details
    $exam_res = mysqli_query($conn, "SELECT * FROM exams WHERE id=$exam_id");
    $exam = mysqli_fetch_assoc($exam_res);

    if ($exam) {

        // Ensure description exists to avoid warnings
        $exam_description = isset($exam['description']) ? $exam['description'] : 'N/A';

        // Prepare exam table for email
        $exam_info = "
        <table style='width:100%; border-collapse:collapse; font-family:Arial,sans-serif;'>
            <tr style='background:#0277bd; color:white;'>
                <th style='padding:8px;'>Branch</th>
                <th style='padding:8px;'>Semester</th>
                <th style='padding:8px;'>Subject</th>
                <th style='padding:8px;'>Date</th>
                <th style='padding:8px;'>Time</th>
                <th style='padding:8px;'>Venue</th>
                <th style='padding:8px;'>Description</th>
            </tr>
            <tr style='background:#f1f8ff;'>
                <td style='padding:8px;'>{$exam['branch']}</td>
                <td style='padding:8px;'>{$exam['semester']}</td>
                <td style='padding:8px;'>{$exam['subject']}</td>
                <td style='padding:8px;'>{$exam['exam_date']}</td>
                <td style='padding:8px;'>{$exam['exam_time']}</td>
                <td style='padding:8px;'>{$exam['venue']}</td>
                <td style='padding:8px;'>$exam_description</td>
            </tr>
        </table><br>";
        
        // Fetch all students
        $students = mysqli_query($conn, "SELECT fullname, email FROM students");

        while ($row = mysqli_fetch_assoc($students)) {
            $email = isset($row['email']) ? $row['email'] : '';
            $fullname = isset($row['fullname']) ? $row['fullname'] : 'Student';

            if(empty($email)) continue; // Skip if no email

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = SMTP_HOST;
                $mail->SMTPAuth = true;
                $mail->Username = SMTP_USER; // replace with Brevo SMTP username
                $mail->Password = SMTP_PASSWORD;  // replace with Brevo SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = SMTP_PORT;

                $mail->setFrom('no-reply@yourdomain.com', 'Exam Notification System');
                $mail->addAddress($email, $fullname);
                $mail->isHTML(true);
                $mail->Subject = "📢 Exam Notification: {$exam['subject']}";

                $mail->Body = "
                    <div style='font-family:Arial,sans-serif; color:#333; line-height:1.6;'>
                        <h2 style='text-align:center; color:#0277bd;'>Exam Notification</h2>
                        <p>Dear <b>$fullname</b>,</p>
                        <p>Here are the details for your upcoming exam:</p>
                        $exam_info
                        <h3 style='color:#0277bd;'>Rules & Instructions:</h3>
                        <p style='background:#f1f8ff; padding:12px; border-left:4px solid #0277bd;'>$custom_message</p>
                        <p>Wishing you the best of luck!<br><b>Exam Cell</b></p>
                        <hr style='border:none; border-top:1px solid #ccc; margin-top:20px;'>
                        <p style='font-size:12px; color:#555;'>This is an automated notification from your institution's Exam System.</p>
                    </div>";

                $mail->send();
                $successEmails[] = $email;
            } catch (Exception $e) {
                $failedEmails[] = [
                    'email' => $email,
                    'error' => $mail->ErrorInfo
                ];
            }
        }
    } else {
        echo "<script>alert('❌ Selected exam not found.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Send Exam Notification</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background:#e3f2fd; padding:30px; }
        .container { max-width:700px; margin:auto; background:white; padding:30px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.1); }
        h2 { text-align:center; color:#0277bd; margin-bottom:20px; }
        label { display:block; font-weight:bold; margin-top:15px; }
        select, textarea, input { width:100%; padding:10px; margin-top:5px; border-radius:8px; border:1px solid #ccc; font-size:14px; }
        button { margin-top:20px; width:100%; padding:12px; font-size:16px; background:#0288d1; color:white; border:none; border-radius:8px; cursor:pointer; transition:0.3s; }
        button:hover { background:#0277bd; }
        .note { font-size:13px; color:#555; margin-top:15px; text-align:center; }
        table { width:100%; border-collapse:collapse; margin-top:15px; }
        table, th, td { border:1px solid #ccc; }
        th, td { padding:8px; text-align:left; }
        th { background:#0277bd; color:white; }
    </style>
</head>
<body>

<div class="container">
    <h2>Send Exam Notification to Students</h2>
    <form method="POST">
        <label>Select Exam</label>
        <select name="exam_id" required>
            <option value="">-- Select Exam --</option>
            <?php 
            mysqli_data_seek($exams_result, 0);
            while($exam_row = mysqli_fetch_assoc($exams_result)) { ?>
                <option value="<?php echo $exam_row['id']; ?>">
                    <?php echo htmlspecialchars($exam_row['subject'])." (".$exam_row['branch']." Sem ".$exam_row['semester'].")"; ?>
                </option>
            <?php } ?>
        </select>

        <label>Rules / Additional Information</label>
        <textarea name="custom_message" rows="6" placeholder="Enter any exam rules or instructions..." required></textarea>

        <button type="submit" name="send">Send Notification</button>
    </form>

    <div class="note">
        📌 Note: This message will be sent to all registered students with complete exam details and instructions.
    </div>

    <?php if(!empty($successEmails) || !empty($failedEmails)): ?>
        <h3>Send Report:</h3>

        <?php if(!empty($successEmails)): ?>
            <h4 style="color:green;">✅ Successfully Sent:</h4>
            <table>
                <tr><th>Email</th></tr>
                <?php foreach($successEmails as $email): ?>
                    <tr><td><?php echo htmlspecialchars($email); ?></td></tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

        <?php if(!empty($failedEmails)): ?>
            <h4 style="color:red;">❌ Failed to Send:</h4>
            <table>
                <tr><th>Email</th><th>Error</th></tr>
                <?php foreach($failedEmails as $fail): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($fail['email']); ?></td>
                        <td><?php echo htmlspecialchars($fail['error']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>
