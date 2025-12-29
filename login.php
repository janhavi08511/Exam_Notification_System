<?php 
include 'config.php';
 session_start();
 
 if (isset($_POST['login'])) {
     $email = mysqli_real_escape_string($conn, $_POST['email']);
      $password = md5($_POST['password']); 
      $result = mysqli_query($conn, "SELECT * FROM students WHERE 
      email='$email' AND password='$password'");
      if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $_SESSION['student'] = $email;
    $_SESSION['name'] = $row['fullname']; // ✅ Store name in session
    header("location: student_home.php");
    exit;


          } else 
          { echo "<script>alert('Invalid credentials');</script>"; } }
           ?> 
           <!doctype html>
            <html><head><link rel="stylesheet" href="css/style.css">  </head>
        <body> 
            <form method="POST"> 
            <h2>Student Login</h2> 
            Email: <input type="email" name="email" required><br>
             Password: <input type="password" name="password" required>
             <br>
              <button type="submit" name="login">Login</button> 
              <p>Not registered? <a href="register.php">Register here</a></p> 
              <p>Admin? <a href="admin_login.php">Admin Login</a></p> 
            </form> 
</body></html>