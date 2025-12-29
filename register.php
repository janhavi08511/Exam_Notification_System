<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ✅ Include PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// ✅ Connect to MySQL (your existing project database)
$conn = new mysqli("localhost", "root", "", "exam_notification");
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

// ✅ Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname  = mysqli_real_escape_string($conn, $_POST['fullname']);
    $studentid = mysqli_real_escape_string($conn, $_POST['studentid']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $password  = md5($_POST['password']); // ✅ Hash password (matches login.php)
    $phone     = mysqli_real_escape_string($conn, $_POST['phone']);
    $branch    = mysqli_real_escape_string($conn, $_POST['branch']);
    $semester  = mysqli_real_escape_string($conn, $_POST['semester']);

    // Check if email already exists
    $check_stmt = $conn->prepare("SELECT * FROM students WHERE email=?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<script>alert('❌ Email already registered!');</script>";
    } else {
        // ✅ Insert into students table with password
        $stmt = $conn->prepare("INSERT INTO students (fullname, studentid, email, password, phone, branch, semester) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $fullname, $studentid, $email, $password, $phone, $branch, $semester);

        if ($stmt->execute()) {
            echo "<script>alert('✅ Student Registered Successfully!');</script>";

            // ✅ Fetch exams for this branch & semester
            $exam_stmt = $conn->prepare("SELECT * FROM exams WHERE branch=? AND semester=?");
            $exam_stmt->bind_param("si", $branch, $semester);
            $exam_stmt->execute();
            $exam_res = $exam_stmt->get_result();

            if ($exam_res->num_rows > 0) {
                $exam_details_html = '';
                while ($exam = $exam_res->fetch_assoc()) {
                    $exam_details_html .= "
                        <b>Date:</b> {$exam['exam_date']}<br>
                        <b>Time:</b> {$exam['exam_time']}<br>
                        <b>Subject:</b> {$exam['subject']}<br>
                        <b>Venue:</b> {$exam['venue']}<br><br>
                    ";
                }

                // ✅ Send email via Brevo SMTP
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp-relay.brevo.com';   // Brevo SMTP
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'YOUR_BREVO_USERNAME';    // Replace
                    $mail->Password   = 'YOUR_BREVO_API_KEY';     // Replace
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;

                    $mail->setFrom('no-reply@yourdomain.com', 'Exam Cell');
                    $mail->addAddress($email, $fullname);

                    $mail->isHTML(true);
                    $mail->Subject = "📢 Exam Timetable Notification – Semester $semester";
                    $mail->Body = "
                        Dear $fullname,<br><br>
                        The examination schedule for Semester $semester has been finalized.<br><br>
                        $exam_details_html
                        Regards,<br>
                        <b>Exam Cell</b>
                    ";
                    $mail->send();
                    echo "<script>alert('📩 Exam schedule emailed successfully!');</script>";
                } catch (Exception $e) {
                    echo "<script>alert('⚠ Email sending failed: {$mail->ErrorInfo}');</script>";
                }
            } else {
                echo "<script>alert('ℹ No exams found for this branch and semester.');</script>";
            }
            $exam_stmt->close();
        } else {
            echo "<script>alert('❌ Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
    $check_stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Student Registration</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #c6e2ff, #f0f8ff);
      padding: 30px;
    }
    .container {
      max-width: 600px;
      margin: auto;
      background: #ffffff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0,0,0,0.15);
    }
    .header { text-align: center; margin-bottom: 25px; }
    .header h1 { color: #002244; font-size: 24px; margin: 0; }
    h2 { text-align: center; color: #0066cc; margin-bottom: 25px; }
    label { display: block; margin-top: 15px; font-weight: 600; font-size: 14px; color: #003366; }
    input, select, button {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
    }
    button {
      background-color: #28a745;
      color: white;
      border: none;
      margin-top: 20px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
    }
    button:hover { background-color: #218838; }
    .error { color: red; font-size: 13px; margin-top: 4px; display: none; }
  </style>
  <script>
    function validatePhone(input) {
      const error = document.getElementById('phoneError');
      const value = input.value;
      if (value.length > 0 && value.length < 10) {
        error.style.display = 'block';
        error.textContent = 'Please enter a 10-digit mobile number.';
      } else { error.style.display = 'none'; }
      if (value.length > 10) { input.value = value.slice(0, 10); }
    }
  </script>
</head>
<body>

<div class="container">
  <div class="header"><h1>Government Polytechnic Nashik</h1></div>
  <h2>Student Registration Form</h2>

  <form method="POST" action="">
    <label>Full Name</label>
    <input type="text" name="fullname" required>

    <label>Student ID</label>
    <input type="text" name="studentid" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <!-- ✅ Added Password field -->
    <label>Password</label>
    <input type="password" name="password" required>

    <label>Phone Number (10 digits)</label>
    <input type="tel" name="phone" id="phone" pattern="[0-9]{10}" maxlength="10"
           oninput="validatePhone(this)" required>
    <div id="phoneError" class="error">Please enter a 10-digit mobile number.</div>

    <label>Branch</label>
    <select name="branch" required>
      <option value="">Select Branch</option>
      <option>Computer Technology</option>
      <option>Information Technology</option>
      <option>Mechanical</option>
      <option>Electronics and Telecommunication</option>
      <option>Civil</option>
      <option>Electrical</option>
      <option>Polymer</option>
      <option>Interior Design</option>
      <option>Dress Designing</option>
    </select>

    <label>Semester</label>
    <select name="semester" required>
      <option value="">Select Semester</option>
      <option>1</option>
      <option>2</option>
      <option>3</option>
      <option>4</option>
      <option>5</option>
      <option>6</option>
    </select>

    <button type="submit">Register</button>
  </form>
</div>

</body>
</html>
