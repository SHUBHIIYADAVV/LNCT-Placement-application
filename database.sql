CREATE DATABASE IF NOT EXISTS placement_db;
USE placement_db;

-- 1. Users: students and the admin (placement officer)
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  branch VARCHAR(50),
  cgpa DECIMAL(3,2),
  role ENUM('student','admin') DEFAULT 'student'
);

-- 2. Jobs posted by companies
CREATE TABLE jobs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company VARCHAR(100) NOT NULL,
  title VARCHAR(100) NOT NULL,
  package VARCHAR(50),
  min_cgpa DECIMAL(3,2) DEFAULT 0
);

-- 3. Applications: links a student to a job
CREATE TABLE applications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  job_id INT NOT NULL,
  status ENUM('Applied','Shortlisted','Selected','Rejected') DEFAULT 'Applied',
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (job_id) REFERENCES jobs(id),
  UNIQUE (user_id, job_id)
);
