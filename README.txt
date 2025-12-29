Exam Notification System - Ready to run

Setup:
1. Place this folder in your webserver's document root (e.g., XAMPP: htdocs/).
2. Import db.sql into MySQL (phpMyAdmin or mysql CLI).
   - By default a sample student is added: student@example.com / password
3. Update SMTP credentials in send_notification.php with your email and app password.
4. Download PHPMailer from https://github.com/PHPMailer/PHPMailer and put its files into includes/PHPMailer/
   or install via Composer and adjust require() accordingly.
5. Open the site in your browser and test.

Admin credentials (hardcoded):
Username: admin
Password: admin123

Files included: basic PHP pages, SQL, CSS. PHPMailer not bundled due to licensing/practical reasons.
