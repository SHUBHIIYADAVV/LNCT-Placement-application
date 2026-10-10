<!DOCTYPE html>
<html>
<head>
  <title>LNCT College Placement</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navbar">
  <span>LNCT College - Placement Cell</span>
  <?php if (isset($_SESSION['user_id'])) { ?>
    <a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['name']) ?>)</a>
  <?php } ?>
</div>
<div class="container">
