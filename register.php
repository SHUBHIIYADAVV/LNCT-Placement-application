<?php
include "config.php";
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name,email,password,branch,cgpa) VALUES (?,?,?,?,?)");
    $stmt->bind_param("ssssd", $_POST['name'], $_POST['email'], $hash, $_POST['branch'], $_POST['cgpa']);
    $msg = $stmt->execute() ? "Registered! You can login now." : "Email already registered.";
}
include "header.php";
?>
<div class="box">
  <h2>Student Registration</h2>
  <p class="error"><?= $msg ?></p>
  <form method="post">
    <input type="text" name="name" placeholder="Full name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="text" name="branch" placeholder="Branch (e.g. CSE)" required>
    <input type="number" step="0.01" max="10" name="cgpa" placeholder="CGPA" required>
    <button>Register</button>
  </form>
  <p><a href="index.php">Back to login</a></p>
</div>
<?php include "footer.php"; ?>
