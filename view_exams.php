<?php
include 'config.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header('location: admin_login.php');
    exit;
}

// Fetch all exams
$result = mysqli_query($conn, "SELECT * FROM exams ORDER BY exam_date ASC, semester ASC");
?>
<!doctype html>
<html>
<head>
<link rel="stylesheet" href="css/style.css">
<title>View Exams</title>
<style>
table { border-collapse: collapse; width: 100%; margin-top: 20px; }
th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
th { background-color: #f2f2f2; }
a { text-decoration: none; color: #0066cc; }
a:hover { text-decoration: underline; }
</style>
</head>
<body>

<h2>Exam List</h2>
<table>
<tr>
    <th>Branch</th>
    <th>Semester</th>
    <th>Subject</th>
    <th>Date</th>
    <th>Time</th>
    <th>Venue</th>
    <th>Action</th>
</tr>
<?php
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>" . htmlspecialchars($row['branch']) . "</td>
        <td>" . htmlspecialchars($row['semester']) . "</td>
        <td>" . htmlspecialchars($row['subject']) . "</td>
        <td>" . $row['exam_date'] . "</td>
        <td>" . htmlspecialchars($row['exam_time']) . "</td>
        <td>" . htmlspecialchars($row['venue']) . "</td>
        <td>
            <a href='update_exam.php?id=" . $row['id'] . "'>Edit</a> | 
            <a href='delete_exam.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a>
        </td>
    </tr>";
}
?>
</table>

</body>
</html>
