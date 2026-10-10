<?php
include "config.php";
$hash = password_hash("admin123", PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (name,email,password,role) VALUES ('Placement Officer','admin@allencity.edu',?,'admin')");
$stmt->bind_param("s", $hash);
echo $stmt->execute() ? "Admin created: admin@allencity.edu / admin123" : "Admin already exists";
