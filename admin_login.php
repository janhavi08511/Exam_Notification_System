<?php
include 'config.php';
session_start();

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM admins WHERE email='$email'");
    if (mysqli_num_rows($query) == 1) {
        $admin = mysqli_fetch_assoc($query);
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin'] = $admin['username'];
            header('location: admin_dashboard.php');
            exit;
        } else {
            echo "<script>alert('❌ Incorrect password!');</script>";
        }
    } else {
        echo "<script>alert('❌ No admin found with this email!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Login</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #e0f7fa, #f1f8e9);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    form {
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
      width: 400px;
      text-align: center;
    }
    h2 { color: #007BFF; margin-bottom: 25px; }
    input {
      width: 90%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    button {
      background-color: #007BFF;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      cursor: pointer;
      transition: 0.3s;
    }
    button:hover { background-color: #0056b3; }
    p { margin-top: 10px; }
  </style>
</head>
<body>
<form method="POST">
  <h2>Admin Login</h2>
  <input type="email" name="email" placeholder="Enter Email" required><br>
  <input type="password" name="password" placeholder="Enter Password" required><br>
  <button type="submit" name="login">Login</button>
  <p>New admin? <a href="admin_register.php">Register here</a></p>
</form>
</body>
</html>
