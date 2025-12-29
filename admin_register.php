<?php
include 'config.php';
session_start();

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $check = mysqli_query($conn, "SELECT * FROM admins WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('❌ Admin already exists with this email.');</script>";
    } else {
        $query = "INSERT INTO admins (username, email, password) VALUES ('$username', '$email', '$password')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('✅ Admin Registered Successfully!'); window.location='admin_login.php';</script>";
        } else {
            echo "<script>alert('❌ Error registering admin.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Registration</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #c6e2ff, #f0f8ff);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    form {
      background: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
      width: 400px;
      text-align: center;
    }
    h2 { color: #0066cc; margin-bottom: 25px; }
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
  <h2>Admin Registration</h2>
  <input type="text" name="username" placeholder="Enter Username" required><br>
  <input type="email" name="email" placeholder="Enter Email" required><br>
  <input type="password" name="password" placeholder="Enter Password" required><br>
  <button type="submit" name="register">Register</button>
  <p>Already registered? <a href="admin_login.php">Login here</a></p>
</form>
</body>
</html>
