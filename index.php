<?php
include "config.php";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $_POST['email']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        header("Location: " . ($user['role'] == 'admin' ? "admin.php" : "student.php"));
        exit;
    }
    $error = "Wrong email or password";
}
include "header.php";
?>
<div class="box">
  <h2>Login</h2>
  <p class="error"><?= $error ?></p>
  <form method="post">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button>Login</button>
  </form>
  <p>New student? <a href="register.php">Register here</a></p>
</div>
<?php include "footer.php"; ?>
