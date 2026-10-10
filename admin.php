<?php
include "config.php";
// Only admin can see this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') { header("Location: index.php"); exit; }

// Add a new job
if (isset($_POST['add_job'])) {
    $stmt = $conn->prepare("INSERT INTO jobs (company,title,package,min_cgpa) VALUES (?,?,?,?)");
    $stmt->bind_param("sssd", $_POST['company'], $_POST['title'], $_POST['package'], $_POST['min_cgpa']);
    $stmt->execute();
}

// Update an application status
if (isset($_POST['update_status'])) {
    $stmt = $conn->prepare("UPDATE applications SET status=? WHERE id=?");
    $stmt->bind_param("si", $_POST['status'], $_POST['app_id']);
    $stmt->execute();
}

$apps = $conn->query("SELECT applications.id, users.name, users.branch, users.cgpa,
                             jobs.company, jobs.title, applications.status
                      FROM applications
                      JOIN users ON applications.user_id = users.id
                      JOIN jobs ON applications.job_id = jobs.id");
$total_students = $conn->query("SELECT COUNT(*) c FROM users WHERE role='student'")->fetch_assoc()['c'];
$placed = $conn->query("SELECT COUNT(DISTINCT user_id) c FROM applications WHERE status='Selected'")->fetch_assoc()['c'];
include "header.php";
?>
<div class="stats">
  <div>Students<br><b><?= $total_students ?></b></div>
  <div>Placed<br><b><?= $placed ?></b></div>
</div>

<h2>Add Job</h2>
<form method="post" class="inline">
  <input type="text" name="company" placeholder="Company" required>
  <input type="text" name="title" placeholder="Role" required>
  <input type="text" name="package" placeholder="Package (e.g. 6 LPA)" required>
  <input type="number" step="0.01" name="min_cgpa" placeholder="Min CGPA" required>
  <button name="add_job">Add</button>
</form>

<h2>Applications</h2>
<table>
  <tr><th>Student</th><th>Branch</th><th>CGPA</th><th>Company</th><th>Role</th><th>Status</th></tr>
  <?php while ($a = $apps->fetch_assoc()) { ?>
  <tr>
    <td><?= htmlspecialchars($a['name']) ?></td>
    <td><?= htmlspecialchars($a['branch']) ?></td>
    <td><?= $a['cgpa'] ?></td>
    <td><?= htmlspecialchars($a['company']) ?></td>
    <td><?= htmlspecialchars($a['title']) ?></td>
    <td>
      <form method="post" class="inline">
        <input type="hidden" name="app_id" value="<?= $a['id'] ?>">
        <select name="status">
          <?php foreach (['Applied','Shortlisted','Selected','Rejected'] as $s) { ?>
            <option <?= $s == $a['status'] ? 'selected' : '' ?>><?= $s ?></option>
          <?php } ?>
        </select>
        <button name="update_status">Save</button>
      </form>
    </td>
  </tr>
  <?php } ?>
</table>
<?php include "footer.php"; ?>
