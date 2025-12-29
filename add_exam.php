<?php
include 'config.php';
if (isset($_POST['add'])) {
    $branch = mysqli_real_escape_string($conn, $_POST['branch']);
    $semester = (int)$_POST['semester'];
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $exam_date = $_POST['exam_date'];
    $exam_time = mysqli_real_escape_string($conn, $_POST['exam_time']);
    $venue = mysqli_real_escape_string($conn, $_POST['venue']);

    $sql = "INSERT INTO exams (branch, semester, subject, exam_date, exam_time, venue)
            VALUES ('$branch', '$semester', '$subject', '$exam_date', '$exam_time', '$venue')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('✅ Exam Added Successfully');window.location='view_exams.php';</script>";
    } else {
        echo "<script>alert('❌ Error: ".mysqli_error($conn)."');</script>";
    }
}
?>

<!doctype html>
<html>
<head>
<title>Add Exam - Admin Panel</title>
<link rel="stylesheet" href="css/style.css">
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(to right, #f0f4f8, #d9e2ef);
    margin: 0;
    padding: 40px 20px;
}

.container {
    max-width: 600px;
    margin: auto;
    background: #ffffff;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

h2 {
    text-align: center;
    color: #003366;
    margin-bottom: 25px;
    font-size: 24px;
}

label {
    display: block;
    margin-top: 15px;
    font-weight: 600;
    color: #003366;
}

input, select {
    width: 100%;
    padding: 10px;
    margin-top: 6px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
    transition: 0.3s;
}

input:focus, select:focus {
    border-color: #0066cc;
    box-shadow: 0 0 5px rgba(0,102,204,0.3);
    outline: none;
}

button {
    width: 100%;
    padding: 12px;
    margin-top: 25px;
    border: none;
    border-radius: 8px;
    background-color: #007bff;
    color: white;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background-color: #0056b3;
}

form label::after {
    content: " *";
    color: red;
}

</style>
</head>
<body>

<div class="container">
    <h2>Add New Exam</h2>
    <form method="POST">
        <label>Branch</label>
        <select name="branch" required>
            <option value="">Select Branch</option>
            <option>Computer Technology</option>
            <option>Information Technology</option>
            <option>Mechanical</option>
            <option>Electronics and Communication</option>
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

        <label>Subject</label>
        <input type="text" name="subject" placeholder="Enter subject name" required>

        <label>Exam Date</label>
        <input type="date" name="exam_date" required>

        <label>Exam Time</label>
        <input type="text" name="exam_time" placeholder="HH:MM AM/PM" required>

        <label>Venue</label>
        <input type="text" name="venue" placeholder="Enter exam venue" required>

        <button type="submit" name="add">Add Exam</button>
    </form>
</div>

</body>
</html>
