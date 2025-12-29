<?php
session_start();
include 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['student'])) {
    header("location: login.php");
    exit;
}

$email = $_SESSION['student'];
$result = mysqli_query($conn, "SELECT * FROM students WHERE email='$email'");
$student = mysqli_fetch_assoc($result);
?>

<!doctype html>
<html>
<head>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body { font-family: Arial; background: #f0f8ff; padding: 30px; }
    h2 { color: #004080; }
    table { border-collapse: collapse; width: 100%; margin-top: 20px; background: white; }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
    th { background: #007bff; color: white; }
    tr:hover { background-color: #f1f1f1; }
    a { color: #007bff; text-decoration: none; }
    a:hover { text-decoration: underline; }
  </style>
</head>
<body>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>
<p><a href="logout.php">Logout</a></p>

<h3>Upcoming Exams</h3>

<table>
<tr>
  <th>Subject</th>
  <th>Date</th>
  <th>Time</th>
  <th>Venue</th>
  <th>Description</th>
</tr>

<?php
$branch = $student['branch'];
$semester = $student['semester'];

$exams = mysqli_query($conn, "SELECT * FROM exams WHERE branch='$branch' AND semester='$semester' ORDER BY exam_date ASC");

if (mysqli_num_rows($exams) > 0) {
    while ($row = mysqli_fetch_assoc($exams)) {
        $description = !empty($row['description']) ? htmlspecialchars($row['description']) : "No details available";
        echo "<tr>
                <td>" . htmlspecialchars($row['subject']) . "</td>
                <td>" . htmlspecialchars($row['exam_date']) . "</td>
                <td>" . htmlspecialchars($row['exam_time']) . "</td>
                <td>" . htmlspecialchars($row['venue']) . "</td>
                <td>$description</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No upcoming exams found.</td></tr>";
}
?>
</table>

</body>
</html>
