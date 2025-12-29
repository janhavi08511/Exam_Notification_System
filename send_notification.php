<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
include 'config.php';

// Fetch exams to select in form
$exams_result = mysqli_query($conn, "SELECT * FROM exams ORDER BY exam_date ASC");

if (isset($_POST['send'])) {
    $exam_id = intval($_POST['exam_id']);
    $custom_message = mysqli_real_escape_string($conn, $_POST['custom_message']);

    // Fetch selected exam details
    $exam_res = mysqli_query($conn, "SELECT * FROM exams WHERE id=$exam_id");
    $exam = mysqli_fetch_assoc($exam_res);

    if ($exam) {
        // Create HTML content for the email using all exam fields as in view_exams
        $exam_info = "
            <table border='1' cellpadding='8' cellspacing='0' style='border-collapse:collapse;width:100%;'>
                <tr>
                    <th>Branch</th><th>Semester</th><th>Subject</th><th>Date</th><th>Time</th><th>Venue</th><th>Description</th>
                </tr>
                <tr>
                    <td>{$exam['branch']}</td>
                    <td>{$exam['semester']}</td>
                    <td>{$exam['subject']}</td>
                    <td>{$exam['exam_date']}</td>
                    <td>{$exam['exam_time']}</td>
                    <td>{$exam['venue']}</td>
                    <td>{$exam['description']}</td>
                </tr>
            </table><br>
        ";

        // Fetch all students
        $students = mysqli_query($conn, "SELECT fullname, email FROM students");
        $sentCount = 0;

        while ($row = mysqli_fetch_assoc($students)) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = SMTP_HOST;
                $mail->SMTPAuth = true;
                $mail->Username = SMTP_USER; // replace
                $mail->Password = SMTP_PASSWORD;  // replace
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = SMTP_PORT;

                $mail->setFrom('no-reply@yourdomain.com', 'Exam Notification System');
                $mail->addAddress($row['email'], $row['fullname']);

                $mail->isHTML(true);
                $mail->Subject = "📢 Exam Notification: {$exam['subject']}";

                $mail->Body = "
                    Dear {$row['fullname']},<br><br>
                    You have an upcoming exam. Here are the details:<br><br>
                    $exam_info
                    <b>Rules and Regulations / Additional Information:</b><br>
                    $custom_message<br><br>
                    Best of luck!<br>
                    <b>Exam Cell</b>
                ";

                $mail->send();
                $sentCount++;
            } catch (Exception $e) {
                continue; // skip failed email
            }
        }

        echo "<script>alert('📩 Notifications sent successfully to $sentCount students!');</script>";
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
        body { font-family: 'Segoe UI', sans-serif; background: #e3f2fd; padding: 30px; }
        .container { max-width: 700px; margin:auto; background:white; padding:30px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.1); }
        h2 { text-align:center; color:#0277bd; margin-bottom:20px; }
        label { display:block; font-weight:bold; margin-top:15px; }
        select, textarea, input { width:100%; padding:10px; margin-top:5px; border-radius:8px; border:1px solid #ccc; font-size:14px; }
        button { margin-top:20px; width:100%; padding:12px; font-size:16px; background:#0288d1; color:white; border:none; border-radius:8px; cursor:pointer; transition:0.3s; }
        button:hover { background:#0277bd; }
        .note { font-size:13px; color:#555; margin-top:15px; text-align:center; }
        table th { background:#0277bd; color:white; }
    </style>
</head>
<body>

<div class="container">
    <h2>Send Exam Notification to Students</h2>
    <form method="POST">
        <label>Select Exam</label>
        <select name="exam_id" required>
            <option value="">-- Select Exam --</option>
            <?php while($exam_row = mysqli_fetch_assoc($exams_result)) { ?>
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
</div>

</body>
</html>
