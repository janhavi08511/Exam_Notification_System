<?php
include 'config.php';
if (!isset($_GET['id'])) {
    header('location: view_exams.php');
    exit;
}

$id = intval($_GET['id']);

// Handle form submission
if (isset($_POST['update'])) {
    $branch = mysqli_real_escape_string($conn, $_POST['branch']);
    $semester = (int)$_POST['semester'];
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $exam_date = $_POST['exam_date'];
    $exam_time = mysqli_real_escape_string($conn, $_POST['exam_time']);
    $venue = mysqli_real_escape_string($conn, $_POST['venue']);

    $sql = "UPDATE exams 
            SET branch='$branch', semester='$semester', subject='$subject', exam_date='$exam_date', exam_time='$exam_time', venue='$venue' 
            WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('✅ Exam Updated Successfully');window.location='view_exams.php';</script>";
    } else {
        echo "<script>alert('❌ Error: ".mysqli_error($conn)."');</script>";
    }
}

// Fetch existing exam data
$res = mysqli_query($conn, "SELECT * FROM exams WHERE id=$id");
$exam = mysqli_fetch_assoc($res);
?>

<!doctype html>
<html>
<head>
<title>Edit Exam</title>
<link rel="stylesheet" href="css/style.css">
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(to right, #f0f4f8, #d9e2ef);
    padding: 30px 20px;
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
    <h2>Edit Exam</h2>
    <form method="POST">
        <label>Branch</label>
        <select name="branch" required>
            <option value="">Select Branch</option>
            <?php
            $branches = ["Computer Technology","Information Technology","Mechanical","Electronics and Communication","Civil","Electrical","Polymer","Interior Design","Dress Designing"];
            foreach ($branches as $b) {
                $selected = ($exam['branch'] == $b) ? "selected" : "";
                echo "<option value='$b' $selected>$b</option>";
            }
            ?>
        </select>

        <label>Semester</label>
        <select name="semester" required>
            <option value="">Select Semester</option>
            <?php
            for ($i=1; $i<=6; $i++) {
                $selected = ($exam['semester'] == $i) ? "selected" : "";
                echo "<option value='$i' $selected>$i</option>";
            }
            ?>
        </select>

        <label>Subject</label>
        <input type="text" name="subject" value="<?php echo htmlspecialchars($exam['subject']); ?>" required>

        <label>Exam Date</label>
        <input type="date" name="exam_date" value="<?php echo $exam['exam_date']; ?>" required>

        <label>Exam Time</label>
        <input type="text" name="exam_time" value="<?php echo htmlspecialchars($exam['exam_time']); ?>" required>

        <label>Venue</label>
        <input type="text" name="venue" value="<?php echo htmlspecialchars($exam['venue']); ?>" required>

        <button type="submit" name="update">Update Exam</button>
    </form>
</div>

</body>
</html>
