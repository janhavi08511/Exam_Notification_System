<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("location: admin_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Exam Notification System</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e0f7fa, #f1f8e9);
            margin: 0;
            padding: 0;
        }
        .dashboard-container {
            max-width: 800px;
            margin: 100px auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.15);
            text-align: center;
        }
        h2 {
            color: #007BFF;
            margin-bottom: 30px;
            font-size: 26px;
            letter-spacing: 1px;
        }
        .card-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
        }
        .card {
            background: #f8f9fa;
            padding: 25px 20px;
            border-radius: 12px;
            width: 180px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            transition: 0.3s;
        }
        .card:hover {
            background: #007BFF;
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .card a {
            text-decoration: none;
            color: inherit;
            font-size: 16px;
            font-weight: 600;
        }
        .logout-btn {
            display: inline-block;
            margin-top: 40px;
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <h2>Admin Dashboard</h2>
    <div class="card-container">
        <div class="card"><a href="add_exam.php">➕ Add Exam</a></div>
        <div class="card"><a href="view_exams.php">📋 View Exams</a></div>
        <div class="card"><a href="send_notification.php">📧 Send Notification</a></div>
    </div>
    <a href="logout.php" class="logout-btn">🚪 Logout</a>
</div>

</body>
</html>
