-- ✅ Create Database
CREATE DATABASE IF NOT EXISTS exam_notification;
USE exam_notification;

-- ✅ Create Students Table
CREATE TABLE IF NOT EXISTS students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ✅ Create Exams Table
CREATE TABLE IF NOT EXISTS exams (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100) NOT NULL,
  date DATE NOT NULL,
  description TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ✅ Create Admins Table
CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ✅ Insert Sample Admin
-- Username: admin | Password: 1234
INSERT INTO admins (username, email, password)
VALUES ('admin', 'admin@example.com', MD5('1234'))
ON DUPLICATE KEY UPDATE username=username;

-- ✅ Insert Sample Student
-- Email: student@example.com | Password: password
INSERT INTO students (name, email, password)
VALUES ('Test Student', 'student@example.com', MD5('password'))
ON DUPLICATE KEY UPDATE name=name;

-- ✅ Insert Sample Exam
INSERT INTO exams (title, date, description)
VALUES ('Mid Term Exam', '2025-11-20', 'Midterm Examination for Semester 5')
ON DUPLICATE KEY UPDATE title=title;
