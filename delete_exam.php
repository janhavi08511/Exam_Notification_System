<?php
include 'config.php';
if (!isset($_GET['id'])) { header('location: view_exams.php'); exit; }
$id = intval($_GET['id']);
mysqli_query($conn, "DELETE FROM exams WHERE id=$id");
header('location: view_exams.php');
exit;
?>