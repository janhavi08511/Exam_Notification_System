<?php
$conn = mysqli_connect("localhost", "root", "", "exam_notification");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
